<?php
// includes/functions.php

function sanitizeInput($data) {
    // Menghilangkan spasi berlebih
    $data = trim($data);
    // Menghilangkan backslashes
    $data = stripslashes($data);
    // Mengubah karakter khusus menjadi entitas HTML untuk mencegah XSS
    $data = htmlspecialchars($data);
    return $data;
}
?>