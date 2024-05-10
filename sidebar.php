<?php

$userEnvironments = null;

if(isset($_SESSION["id"])) {
    $read = $conn->prepare('SELECT environ_id, environ_name FROM environments WHERE user_id=?');
    $read->bind_param('i', $_SESSION['id']);
    $read->execute();
    $result = $read->get_result();
    if ($result->num_rows != 0) {
        while ($row = $result->fetch_assoc()) {
            $userEnvironments[] = $row;
        }
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

    <title>GardenEye - Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../assets/css/sb-admin-2.css" rel="stylesheet">

    <link rel="stylesheet" href= 
"https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"> 
    <script src="https://code.jquery.com/jquery-3.6.1.min.js" 
        integrity= 
    "sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" 
        crossorigin="anonymous"> 
    </script> 
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">


<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

<!-- Sidebar - Brand -->
<a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
    <img src="../assets/images/Logo.png" width="50px" alt="GardenEye">
    <div class="sidebar-brand-text mx-3">GardenEye</div>
</a>

<!-- Divider -->
<hr class="sidebar-divider my-0">

<!-- Nav Item - Dashboard -->
<li class="nav-item">
    <a class="nav-link" href="../dashboard/">
        <i class="fas fa-fw fa-tachometer-alt"></i>
        <span>Dashboard</span></a>
</li>

<!-- Nav Item - Dashboard -->
<li class="nav-item">
    <a class="nav-link" href="../setup/">
        <i class="fas fa-fw fa-seedling"></i>
        <span>Setup GardenEye</span></a>
</li>

<!-- Divider -->
<hr class="sidebar-divider">

<!-- Heading -->
<div class="sidebar-heading">
    Interface
</div>

<!-- Nav Item - Pages Collapse Menu -->
<li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
        aria-expanded="true" aria-controls="collapseTwo">
        <i class="fas fa-fw fa-globe"></i>
        <span>Environments</span>
    </a>
    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
        <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">Active Environments:</h6>
            <?php 
                if(!is_null($userEnvironments)) {
                    foreach ($userEnvironments as $env) {
                        echo '<a class="collapse-item" href="../dashboard/?environment='.$env["environ_id"].'">'.$env["environ_name"].'</a>';
                    }
                }
            ?>
        </div>
    </div>
</li>

<!-- Divider -->
<hr class="sidebar-divider">


</ul>
<!-- End of Sidebar -->