<?php
session_start();
include "../connection.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

?>
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
                        <h1 class="h3 mb-0 text-gray-800">Setup Your GardenEye</h1>
                    </div>

                    <!-- Content Row -->

                    <div class="row">
                        <div class="col">
                            <div class="card shadow mb-4">
                                <!-- Card Body -->
                                <div class="card-body">
                                   <p>To setup your GardenEye, you must first have an Environment created. If you do not have one created, go to the <a href="../create/">Create Environment</a> page to start your GardenEye journey. Once you are ready, click the Generate Token button below:</p>
                                   <div class="alert alert-danger" role="alert">
                                    WARNING: This token is only for you! Do not share it with anybody under any circumstances.
                                    </div>
                                    <p>The GardenEye Token allows the GardenEye system and web application to connect, meaning whoever has it is able to send data to this account.</p>
                                    <!-- Button trigger modal -->
                                    <button type="button" class="btn btn-primary generateToken" data-toggle="modal" data-target="#exampleModal">
                                    Generate Token
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Your New Token:</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <span style="font-size: 50px" id="generatedToken"></span>
                                            <p>Open your GardenEye software and enter this token so that it can pair.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
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

    <script src="https://code.jquery.com/jquery-3.6.1.min.js" 
        integrity= 
    "sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" 
        crossorigin="anonymous"> 
    </script>

    <script>
        $(".generateToken").on("click", function(){
            $.getJSON( `../api/authentication/generateToken.php`, function( data ) {
                console.log(data)
                if(data.success == true) {
                    document.getElementById("generatedToken").innerHTML = data.message
                } else {
                    document.getElementById("generatedToken").innerHTML = "Could not generate token."
                }
            });
        });
    </script>

</body>

</html>