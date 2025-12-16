<?php
    $conn = mysqli_connect("localhost", "root", "", "linkup");
    if (!$conn) {
        die("connection failed:" . mysqli_connect_error());
    }
?>