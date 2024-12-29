<!doctype html>
<html lang="en">
<?php $title = "EduPortal | View Agent Past Questions"; ?>
<?php include "partials/head.php"; ?>

<body>
    <!-- Navbar -->
<?php include "partials/admin_header.php"; ?>

    <main>
        <!--Dashboard home start-->
        <section class="py-lg-7 py-5 bg-light-subtle">
            <div class="container">
                <div class="row">
                    <?php include "partials/admin_menu.php"; ?>
                    <?php include "partials/view_agent_past_questions_content.php"; ?>
                </div>
            </div>
        </section>
        <!--Dashboard home end-->
    </main>

    <?php include 'partials/footer.php'; ?>
    <!-- Scroll top -->
    <?php include 'partials/scroll_top.php'; ?>
    <!-- Libs JS -->
    <?php include 'partials/scripts.php'; ?>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

    <!-- Initialize DataTables -->
    <script>
        $(document).ready(function () {
            $('#usersTable').DataTable({
                "paging": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "retrieve": true
            });
        });
    </script>
</body>

</html>
