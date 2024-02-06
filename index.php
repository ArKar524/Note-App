<?php 
    session_start();
    // ob_start();
    // authentication
    if (!isset($_SESSION['user'])) {
        header("location:login.php");
    }
    // for error reporting
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
    require_once ('connection/conn.php');

    
    // retrieve the notes from the table
    $user_id = $_SESSION['user']->id;
    $getNotes = $conn->prepare("SELECT * FROM notes where user_id =$user_id ORDER BY created_at DESC");
    $getNotes->execute();
    $notes = $getNotes->fetchAll(PDO::FETCH_OBJ);
    $createdAt = date('Y-m-d H:i:s');

    // delete 
    if (isset($_POST['deleteBtn'])) {
        $id = $_POST['id'];
    
        // print_r ($id);
        $stmt = $conn->prepare("DELETE FROM notes WHERE id = $id;");
        $stmt->execute();
    
        header('location:index.php');
    }

    // edit

    if (isset($_POST['edit'])) {
        $id = $_POST['id'];
        $title = $_POST['title'];
        // print_r ($title);
        $description = $_POST['description'];

        $stmt = $conn->prepare("UPDATE notes SET title = '$title', description = '$description' WHERE id = $id");
        $stmt->execute();
        
        if($stmt){
            header('location:index.php');
        }
    }
    // get user information
    $id = $_SESSION['user']->id;
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = $id");
    $stmt->execute();
    $user = $stmt->fetchObject();

    // // share
    if (isset($_POST['share'])) {
        $id = $_POST['id'];
        $title = $_POST['title'];
        $description = $_POST['description'];

        $email = $_POST['email'];

        $stmt = $conn->prepare("SELECT * FROM users WHERE email= '$email'");
        $stmt->execute();
        $user = $stmt->fetchObject();
        $userId= $user->id;

        $userEmail = $user->email;
        print $userEmail;
        // validate $email
        if (!$userEmail) {
            header('location:index.php');
        }

        $shareStmt = $conn->prepare("INSERT INTO notes (user_id, title, description, created_at) VALUES ('$userId', '$title', '$description','$createdAt')");
        $shareStmt->execute();

        if($shareStmt){
            header('location:index.php');
        }
    }
    
   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Note App</title>
</head>
<!-- bootstrap css -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- font-awesome icon -->
<script src="https://kit.fontawesome.com/eed2990b31.js" crossorigin="anonymous"></script>

<!-- custom css -->
<link rel="stylesheet" href="assets/style.css">
<body>
      <!-- Navigation -->
  <nav class="navbar navbar-dark sticky-top">
    <div>
        <span class="text-light mx-3"> Name : <?php echo $_SESSION['user']->name ?></span>
        <span class="text-light mx-3"> Email : <?php echo $_SESSION['user']->email ?></span>
    </div>
    <div class="logout">
    <a class="logout text-light px-2" href="auth/logout.php"></i> Logout</a>
    </div>
  </nav>

  <!-- Page Content -->
  <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 d-none d-md-block bg-light sidebar">
                <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item sideNavIcon">
                        <a class="nav-link active " href="#"><i class="fa-solid fa-book-open-reader "></i> <span class="sideNavText"> Notes</span></a>
                    </li>
                    <li class="nav-item sideNavIcon">
                        <a class="nav-link text-dark" href="#"><i class="fa-solid fa-box-archive "></i><span class="sideNavText">Archive</span></a>
                    </li>
                    <li class="nav-item sideNavIcon">
                        <a class="nav-link text-dark" href="#"><i class="fa-solid fa-trash "></i> <span class="sideNavText">trash</span></a>
                    </li>
                </ul>
                </div>
            </nav>

        <!-- Main Content -->
        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 main-content">
                    <!-- note -->
                <div class="header">
                    <div class="row">
                        <div class="col-md-3"></div>
                        <div class="col-md-6">
                            <form action="create.php" method="POST">

                                <input type="text" onclick="inputBox()" class="form-control mb-4" placeholder="Take a Note .." id="takeANote" readonly>

                                <input type="text" class="form-control my-2" id="title" style="display: none" placeholder="title..." name="title">

                                <textarea class="form-control mb-2 border border" id="description" required cols="20" rows="5" placeholder="Take a Note.." style="display:none" name="description"></textarea>  

                                <button id="saveBtn" class="btn btn-primary btn-sm float-end" name="saveBtn" style="display: none">Save</button>

                                <p id="closeBtn" class="btn btn-danger btn-sm float-end mx-2" onclick="closeBox()" style="display: none">close</p>

                            </form>
                    
                        </div>
                        <div class="col-md-3"></div>

                    <div class="row">
                        <!-- edit Modal -->
                        <form action="" method="post">
                            <div class="modal" id="editModal">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                
                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">Note Edit</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                    
                                        <!-- Modal Body -->
                                        
                                            <div class="modal-body">
                                                <input type="hidden" value="" id="id" name="id">
                                                <label for="title">Tile</label>
                                                <input type="text" value="" class="form-control mb-2" id="editTitle" placeholder="title..." name="title">
                                                <label for="description">Description</label>
                                                <textarea class="form-control mb-2" required id="editDescription" cols="20" rows="5" placeholder="Take a Note.." name="description"></textarea>
                                            </div>                    
                                            <!-- Modal Footer -->
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="" class="btn btn-primary" name="edit">Save changes</button>
                                            </div>                        
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!-- end Edit Modal -->

                        <!-- share Modal -->
                        <form action="" method="post">
                            <div class="modal" id="shareModal">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                
                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">Note Share</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                    
                                        <!-- Modal Body -->
                                        
                                            <div class="modal-body">
                                                <input type="hidden" value="" id="shareId" name="id">
                                                <input type="hidden" value="" class="form-control mb-2" id="shareTitle" placeholder="title..." name="title">
                                                <textarea type="hidden" style="display: none;" class="form-control mb-2" id="shareDescription" cols="20" rows="5" placeholder="Take a Note.." name="description"></textarea>
                                            </div>     
                                            <div class="owner">
                                                <i class="fa-solid fa-user ownerIcon"></i><span id="owner" class="px-3"></span>
                                            </div>
                                            <div class="email" id="ownerEmail">

                                            </div>
                                            <div class="">
                                                <input required type="text" class="form-control" name="email" placeholder="Enter the email address to share...">
                                            </div>               
                                            
                                            <!-- Modal Footer -->
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                <button type="" class="btn btn-primary" name="share">Share</button>
                                            </div>                        
                                    </div>
                                </div>
                            </div>
                        </form>

                        <!-- show note -->
                        <?php foreach($notes as $note): ?>
                            <div class="col-md-4 notes" id="card">
                                <div class="card mb-3">
                                    <i class="fa-solid fa-check check"></i>
                                    <h5 class="p-3 pb-0 "> <?php echo $note->title ?> </h5>
                                    <p class="px-3"><?php echo $note->description ?></p>
                                    <span class="p-3 icons" >
                                        <form method="post">

                                            <input type="hidden" name="id" value="<?php echo $note->id ?>">
                                            
                                            <button type="button" data-toggle="modal" class="float-end mx-1" data-target="#shareModal" name="shareBtn" onclick="share('<?php echo $note->id ?>','<?php echo $note->title ?>', '<?php echo $note->description ?>','<?php echo $user->name; ?>', '<?php echo $user->email ?>')" > 
                                                <i class="fa-solid fa-share-from-square"></i>
                                            </button>

                                            <button formaction="copy.php"class="float-end" name="copy">
                                                <i class="fa-solid fa-copy "></i>
                                            </button>

                                            <button type="button" class="float-end" data-toggle="modal" onclick="edit('<?php echo $note->id ?>','<?php echo $note->title ?>', '<?php echo $note->description ?>')" data-target="#editModal" name="editBtn">
                                                <i class="fa-solid fa-pen-to-square"></i>
                                            </button>

                                            <button  class="float-end d-flex justify-content-center align-items-center" onclick="return confirm('Are you Sure! You want to delete!')" name="deleteBtn">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>

                                        </form>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach;?>

                    
                    </div>
                </div> 
        </main>
  </div>
    <!-- custom  js  -->
    <script src="assets/index.js"></script>

    <!-- bootstrap js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

    <!-- Bootstrap JS and Popper.js (required for Bootstrap) -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="..."></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="..."></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="..."></script>


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>
</html>

