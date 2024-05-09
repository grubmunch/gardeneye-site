<?php
session_start();
include "../connection.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if(!isset($_SESSION["logged_in"])) {
    die("Must be logged in to view this page.");
}
$message = "";

if(isset($_GET["warning"])) {
    $message = "<div class='alert alert-warning' role='alert'>
                You must create an environment before you enter the dashboard!
            </div>";
}

if(isset($_POST["environName"])) {
    if(!empty($_POST["environName"])) {
        $desc = "";

        if(isset($_POST["description"])) {
            $desc = $conn->real_escape_string($_POST["description"]);
        }

        if(strlen($desc) <= 100) {
            $name = $conn->real_escape_string($_POST["environName"]);

            $createEnviron = $conn->prepare('INSERT INTO environments (user_id, environ_name, environ_desc) VALUES (?, ?, ?)');
            $createEnviron->bind_param("iss", $_SESSION["id"], $name, $desc);
            if($createEnviron->execute()) {
                $message = "<div class='alert alert-success' role='alert'>
                                New environment successfully created!
                            </div>";
            } else {
                $message = "<div class='alert alert-danger' role='alert'>
                                Error: could not insert new environment to database.
                            </div>";
            }
        } else {
            $message = "<div class='alert alert-danger' role='alert'>
                            Error: Description cannot be over 100 characters long.
                        </div>";
        }
    } else {
        $message = "<div class='alert alert-danger' role='alert'>
                        Error: Environment name cannot be empty.
                    </div>";
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>GardenEye - Create</title>

    <!-- Custom fonts for this template-->
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../assets/css/sb-admin-2.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php include("../sidebar.php"); ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <?php include("../topbar.php"); ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Create New Environment</h1>
                    </div>

                    <!-- Content Row -->

                    <div class="row">
                        <div class="col">
                            <?php echo $message; ?>
                            <div class="card shadow mb-4">
                                <!-- Card Body -->
                                <div class="card-body">
                                    <form action="" method="post">
                                        <div class="mb-3">
                                            <label for="environName" class="form-label">Environment Name (required)</label>
                                            <input type="text" class="form-control" name="environName" id="environName" placeholder="My Greenhouse">
                                        </div>
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control" name="description" id="description" rows="3" placeholder="Add a short description, if you wish, about this environment. (100 max characters)"></textarea>
                                        </div>
                                        <div class="mb-3">
                                            <input type="submit" id="createEnvironment" class="btn btn-primary" value="Create Environment">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <!-- End of Main Content -->

            <?php include("../footer.php"); ?>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../assets/vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../assets/js/demo/chart-area-demo.js"></script>
    <script src="../assets/js/demo/chart-pie-demo.js"></script>

</body>

</html>