<?php
    $user = 'a1407626';
    $pass = 'dbs2016';
    $database = 'lab';

    // establish database connection
    $conn = oci_connect($user, $pass, $database);
    if (!$conn) exit;
?>