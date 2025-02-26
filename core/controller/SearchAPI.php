<?php

require_once 'core/model/DB.php';

class SearchAPI
{
    private $pdo;
    private $currentUser;

    public function __construct()
    {
        global $pdo;
        $this->pdo = $pdo;

        // Load current user if logged in
        if (Session::get('loggedin')) {
            $this->currentUser = toJson($pdo->select(
                "SELECT * FROM users WHERE id=?",
                [Session::get('loggedin')]
            )->fetch(PDO::FETCH_ASSOC));
        }

        // Start output buffering
        ob_start();

        // Handle different types of requests
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $this->handleSearch();
        }
    }

    private function handleSearch()
    {
        try {
            // Ensure no output before JSON response
            ob_clean();

            // Make PDO and user data available to views
            global $pdo;
            $currentUser = $this->currentUser;

            // Check if this is a suggestion request
            if (isset($_GET['suggest']) && $_GET['suggest'] === 'true') {
                $this->handleSuggestions();
                return;
            }

            // If no query parameter is set, display the search page
            if (! isset($_GET['q'])) {
                include 'view/guest/search_results.php';
                return;
            }

            $query   = $_GET['q'] ?? '';
            $page    = isset($_GET['page']) ? (int) $_GET['page'] : 1;
            $types   = ! empty($_GET['types']) ? explode(',', $_GET['types']) : [];
            $year    = $_GET['year'] ?? '';
            $subject = $_GET['subject'] ?? '';

            // Items per page
            $limit  = 9;
            $offset = ($page - 1) * $limit;

            // Base query for the single document table
            $sql = "SELECT id, title, description, year, subject, exam_body,
                    document_type as type, price, filename,
                    CASE document_type
                        WHEN 'ebook' THEN 'ebooks'
                        WHEN 'publication' THEN 'publications'
                        WHEN 'past question' THEN 'view-past-questions'
                    END as url_prefix
                    FROM document WHERE 1=1";

            $params = [];

            // Add search condition - search across multiple relevant fields
            if (! empty($query)) {
                $searchTerms      = explode(' ', $query);
                $searchConditions = [];

                foreach ($searchTerms as $term) {
                    $conditions = [
                        "title LIKE ?",
                        "description LIKE ?",
                        "subject LIKE ?",
                        "exam_body LIKE ?",
                        "author LIKE ?",
                        "CAST(year AS CHAR) LIKE ?",
                    ];

                    $searchConditions[] = "(" . implode(" OR ", $conditions) . ")";

                    // Add parameters for each field
                    $params = array_merge($params, [
                        "%$term%", // title
                        "%$term%", // description
                        "%$term%", // subject
                        "%$term%", // exam_body
                        "%$term%", // author
                        "%$term%", // year
                    ]);
                }

                $sql .= " AND " . implode(" AND ", $searchConditions);
            }

            // Add type filter
            if (! empty($types)) {
                $placeholders = rtrim(str_repeat('?,', count($types)), ',');
                $sql .= " AND document_type IN ($placeholders)";
                $params = array_merge($params, $types);
            }

            // Add year filter
            if (! empty($year)) {
                $sql .= " AND year = ?";
                $params[] = $year;
            }

            // Add subject filter
            if (! empty($subject)) {
                $sql .= " AND subject = ?";
                $params[] = $subject;
            }

            // For debugging: Log the SQL query with actual parameters
            $debugSql = $sql;
            foreach ($params as $param) {
                $debugSql = preg_replace('/\?/', "'$param'", $debugSql, 1);
            }
            // Clean up the SQL for logging
            $debugSql = preg_replace('/\s+/', ' ', $debugSql);
            error_log("Search Query: " . $debugSql);

            // Get total count for pagination
            $countSql = "SELECT COUNT(*) as total FROM ($sql) as t";
            $stmt     = $this->pdo->select($countSql, $params);
            if (! $stmt) {
                throw new Exception('Error executing count query: ' . print_r($this->pdo->errorInfo(), true));
            }
            $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Add pagination - using integer values directly
            $sql .= " LIMIT " . (int) $limit . " OFFSET " . (int) $offset;

            // Execute final query
            $stmt = $this->pdo->select($sql, $params);
            if (! $stmt) {
                $error = $this->pdo->query->errorInfo();
                error_log("SQL Error: " . print_r($error, true));
                throw new Exception('Error executing search query: ' . $error[2]);
            }
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Log the number of results found
            error_log("Search found " . count($results) . " results");

            // Format results
            $formattedResults = array_map(function ($item) {
                error_log('Processing item: ' . print_r($item, true));
                return [
                    'id'          => $item['id'],
                    'title'       => $item['title'],
                    'type'        => $item['type'],
                    'year'        => $item['year'],
                    'subject'     => $item['subject'],
                    'description' => $item['description'],
                    'exam_body'   => $item['exam_body'],
                    'price'       => $item['price'],
                    'image_url'   => $item['filename'] ? "uploads/{$item['filename']}" : null,
                    'url'         => $item['url_prefix'] . '?id=' . $item['id'],
                ];
            }, $results);

            error_log('Formatted results: ' . print_r($formattedResults, true)); // Debug formatted results

            // Calculate pagination
            $totalPages = ceil($total / $limit);

            // If it's an AJAX request, return JSON
            if (! empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
                strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

                // Ensure clean JSON response
                ob_clean();
                header('Content-Type: application/json');
                header('X-Content-Type-Options: nosniff');

                $response = [
                    'results'    => $formattedResults,
                    'pagination' => [
                        'currentPage' => $page,
                        'totalPages'  => $totalPages,
                    ],
                    'query'      => [
                        'sql'    => $debugSql,
                        'params' => $params,
                        'total'  => $total,
                    ],
                ];

                echo json_encode($response, JSON_THROW_ON_ERROR);
                exit; // Ensure no additional output
            } else {
                include 'view/guest/search_results.php';
            }

        } catch (Exception $e) {
            error_log("Search Error: " . $e->getMessage());

            // Ensure clean error response
            ob_clean();
            http_response_code(500);
            header('Content-Type: application/json');

            echo json_encode([
                'error'   => 'An error occurred while searching',
                'message' => $e->getMessage(),
                'query'   => isset($debugSql) ? $debugSql : null,
            ], JSON_THROW_ON_ERROR);
            exit;
        }
    }

    private function handleSuggestions()
    {
        try {
            $query = $_GET['q'] ?? '';
            if (empty($query)) {
                echo json_encode(['results' => []]);
                return;
            }

            // Limit suggestions to 5 results
            $sql = "SELECT id, title, document_type as type, year, subject
                    FROM document
                    WHERE (title LIKE ? OR description LIKE ?)";

            // Add type filter if specified
            if (! empty($_GET['types']) && $_GET['types'] !== 'all') {
                $sql .= " AND document_type = ?";
                $params = ["%$query%", "%$query%", $_GET['types']];
            } else {
                $params = ["%$query%", "%$query%"];
            }

            $sql .= " LIMIT 5";

            $stmt = $this->pdo->select($sql, $params);
            if (! $stmt) {
                throw new Exception('Error fetching suggestions');
            }

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            header('Content-Type: application/json');
            echo json_encode(['results' => $results]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'error'   => 'Error fetching suggestions',
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function getSuggestions($query)
    {
        // Implement search logic here
        $sql = "SELECT id, title, type, year
                FROM (
                    SELECT id, title, 'ebook' as type, year as year FROM document
                    WHERE document_type = 'ebook' AND _type = 'ebook' AND MATCH(title, author, description) AGAINST (?)
                    UNION
                    SELECT id, title, 'publication' as type, year FROM document
                    WHERE document_type = 'publication' AND MATCH(title, author, description) AGAINST (?)
                    UNION
                    SELECT id, subject as title, 'pastQuestion' as type, year as year
                    FROM document
                    WHERE document_type = 'past question' AND MATCH(subject, description) AGAINST (?)
                ) results
                LIMIT 5";

        $stmt = $this->pdo->select($sql, [$query, $query, $query]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getResults($query, $filters, $page = 1, $perPage = 12)
    {
        // Implement paginated search results
        $offset      = ($page - 1) * $perPage;
        $whereClause = $this->buildWhereClause($filters);

        $sql = "SELECT SQL_CALC_FOUND_ROWS id, title, type, year, author, subject, image_url
                FROM (
                    SELECT id, title, 'ebook' as type, year as year,
                           author, NULL as subject, cover_image as image_url
                    FROM document
                    WHERE document_type = 'ebook' AND MATCH(title, author, description) AGAINST (?)
                    UNION
                    SELECT id, title, 'publication' as type, year,
                           author, NULL as subject, cover_image as image_url
                    FROM document
                    WHERE document_type = 'publication' AND MATCH(title, author, description) AGAINST (?)
                    UNION
                    SELECT id, subject as title, 'pastQuestion' as type, year as year,
                           NULL as author, subject, image_url
                    FROM document
                    WHERE document_type = 'past question' AND MATCH(subject, description) AGAINST (?)
                ) results
                $whereClause
                LIMIT ?, ?";

        $stmt    = $this->pdo->select($sql, [$query, $query, $query, $offset, $perPage]);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get total count for pagination
        $total = $this->pdo->select("SELECT FOUND_ROWS()")->fetchColumn();

        return [
            'results'    => $results,
            'pagination' => [
                'current'  => $page,
                'perPage'  => $perPage,
                'total'    => $total,
                'lastPage' => ceil($total / $perPage),
            ],
        ];
    }

    private function buildWhereClause($filters)
    {
        $where  = [];
        $params = [];

        if (! empty($filters['type'])) {
            $where[] = "type IN (" . implode(',', array_fill(0, count($filters['type']), '?')) . ")";
            $params  = array_merge($params, $filters['type']);
        }

        if (! empty($filters['year'])) {
            $where[]  = "year = ?";
            $params[] = $filters['year'];
        }

        if (! empty($filters['subject'])) {
            $where[]  = "subject = ?";
            $params[] = $filters['subject'];
        }

        return $where ? "WHERE " . implode(' AND ', $where) : "";
    }
}

// Instantiate the controller
new SearchAPI();
