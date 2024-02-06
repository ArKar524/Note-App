<?php
    $dbName = "php_notes_app";
    $dbUser = "root";
    $dbPass = "";

    $conn = new PDO("mysql:host=localhost;dbname=$dbName", $dbUser, $dbPass);

    if (!$conn){
        echo "Connection Failed";
    }
