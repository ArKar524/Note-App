<?php 

    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
    require_once('connection/conn.php');

 // copy

    if (isset($_POST['copy'])) {
        $id = $_POST['id'];


        $originalNote = $conn->query("SELECT * FROM notes WHERE id =$id");
        $notes = $originalNote->fetchObject();
        $title = $notes->title;
        $description = $notes->description;
        $user_id = $notes->user_id;
        $createdAt = date('Y-m-d H:i:s');



        $copyNote = $conn->prepare("INSERT INTO notes(user_id, title, description,created_at) VALUES ('$user_id','$title', '$description','$createdAt')");
        $successCopying = $copyNote->execute();

        if($successCopying) {
            header("location: index.php");
        }
    
    }