<?php 
    session_start();
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
    require_once ('../connection/conn.php');

   
    if (isset($_POST["loginBtn"])) {
        $email = $_POST["email"];
        $password = md5($_POST["password"]);

        $stmt = $conn->prepare("SELECT * FROM users WHERE email='$email' AND password='$password'");
        $stmt->execute();
        $user = $stmt->fetchObject();
        // print_r($user);
        if ($user) {
            $_SESSION['user'] = $user;
            // print_r($_SESSION['user']->name);
            header('location:../index.php');
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Note | login</title>
    <!-- bootstrap css -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- font-awesome icon -->
    <script src="https://kit.fontawesome.com/eed2990b31.js" crossorigin="anonymous"></script>

    <style>
        body {
            background-color: #D0D3D4;
        }
    </style>
</head>
<body>
    <div class="row">
        <div class="container d-flex justify-content-center align-items-center " style="height: 100vh;" >
            <div class="col-md-3">
                <form class="my-5" method="post">
                    <div class="card my-5 rounded-2">
                        <div class=" card-header">
                            Login
                        </div>
                        <div class=" card-body">
                            <label for="email">Email</label>
                            <input type="email" required class="form-control" name="email">
                            <label for="password">Password</label>
                            <input type="password" required class="form-control mb-3" name="password">
                            <div class="d-grid gap-2 col-12 mx-auto mb-3">
                                <button class="btn btn-primary btn-block" name="loginBtn">Login</button>
                            </div>
                            <span>Have Not Account Yet !</span><a href="register.php" class="px-2">click here</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>