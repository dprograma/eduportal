<?php

echo "<div class='d-flex flex-column justify-content-center align-items-center w-100'><h4 class='mb-4'>Notifications</h4>";
$notification = null;

if ($notification) {
    echo "<div class='lead'>Loading...</div></div>";
} else {
    echo "<div class='lead'>No notifications available!</div></div>";
}