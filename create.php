<?php 

  session_start();
  ini_set('display_errors', '1');
  ini_set('display_startup_errors', '1');
  error_reporting(E_ALL);

  require_once ('connection/conn.php');


//   Create Section
// print_r($_POST['title']);

if(isset($_POST['saveBtn'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $createdAt = date('Y-m-d H:i:s');
    $user_id = $_SESSION['user']->id;

    // print($title);
    // print($description);
    $stmt = $conn->prepare("INSERT INTO notes(user_id,title, description,created_at) VALUES('$user_id','$title', '$description','$createdAt');)");
    $stmt->execute();

    print($title);
    header('location:index.php');
}