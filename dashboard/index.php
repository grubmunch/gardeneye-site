<?php
session_start();
include "../connection.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

if(!isset($_SESSION["logged_in"])) {
    die("Must be logged in to view this page.");
}

$userEnv = null;
$currentEnv = null;
$environmentId = 0;

if(isset($_SESSION["id"])) {
    $read = $conn->prepare('SELECT * FROM environments WHERE user_id=? ');
    $read->bind_param('i', $_SESSION['id']);
    $read->execute();
    $result = $read->get_result();
    if ($result->num_rows != 0) {
        while ($row = $result->fetch_assoc()) {
            $userEnv[] = $row;
        }
    }
}

if(!is_null($userEnv)) {
    if(sizeof($userEnv) > 0) {
        $environmentId = $userEnv[0]["environ_id"];
        $currentEnv = $userEnv[0];
    }
    if(isset($_GET['environment'])) {
        foreach($userEnv as $env) {
            if ($env["environ_id"] == $_GET["environment"]) {
                $environmentId = $env["environ_id"];
                $currentEnv = $env;
            }
        }
    }
} else {
    header("Location: ../create/?warning=true");
}

if(isset($_GET["read_notification"])) {
    $notificationId = $conn->real_escape_string($_GET["read_notification"]);
    $update = $conn->prepare('UPDATE notifications SET unread=0 WHERE id=? AND environ_id=? AND user_id=?');
    $update->bind_param('iii', $notificationId, $environmentId, $_SESSION['id']);
    if($update->execute()) {

    } else {
        die("could not update notification refresh");
    }
}
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
                        <h1 class="h3 mb-0 text-gray-800">Dashboard: <?php echo $currentEnv["environ_name"]; ?></h1>
                        <a href="../create/" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fa-sm text-white-50"></i> Create New Environment</a>
                    </div>
                    <!-- Content Row -->

                    <div class="row">

                        <!-- Area Chart -->
                        <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Humidity Overview</h6>
                                    <div id="humidityDatepicker" style="width:180px;" class="input-group date" data-date-format="mm-dd-yyyy"> 
                                        <input class="form-control" type="text" readonly /> 
                                        <span class="input-group-addon"> 
                                            <i class="fas fa-calendar"></i> 
                                        </span> 
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div id="humidityMsg"></div>
                                    <div class="chart-area">
                                        <canvas id="humidityChartGraph"></canvas>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>

                        <!-- Pie Chart -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Statistics for Today</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                         <!-- Area Chart -->
                         <div class="col-xl-8 col-lg-7">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Temperature Overview</h6>
                                    <div id="temperatureDatepicker" style="width:180px;" class="input-group date" data-date-format="mm-dd-yyyy"> 
                                        <input class="form-control" type="text" readonly /> 
                                        <span class="input-group-addon"> 
                                            <i class="fas fa-calendar"></i> 
                                        </span> 
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                <div id="temperatureMsg"></div>
                                    <div class="chart-area">
                                        <canvas id="temperatureChartGraph"></canvas>
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js@^3"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@^2"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@^1"></script>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" 
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" 
        crossorigin="anonymous"> 
    </script> 

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"> 
    </script> 

    <script>

    

    let originalData = null;

    let humidityData = []
    let temperatureData = []

    function refreshData(chart, type, id, date) {
        
        $.getJSON( `../api/data/viewData.php?environ_id=${id}&date=${date}`, function( data ) {
            console.log(data)
            if(data.success == true) {
                humidityData = []
                temperatureData = []
            
                if(type=="humidity") {
                    document.getElementById("humidityMsg").innerHTML = "";
                    document.getElementById("humidityChartGraph").style.display="unset";

                    for (let d of data.message) {
                        humidityData.push({x: d["datetime"], y: d["humidity"]})
                    }
                    chart.data.datasets[0].data = humidityData
                } else {
                    document.getElementById("temperatureMsg").innerHTML = "";
                    document.getElementById("temperatureChartGraph").style.display="unset";
                    for (let d of data.message) {
                        temperatureData.push({x: d["datetime"], y: d["temperature"]})
                    }
                    chart.data.datasets[0].data = temperatureData
                }
                chart.update()
            } else {
                if(type=="humidity") {
                    document.getElementById("humidityMsg").innerHTML = "No data for this date!";
                    document.getElementById("humidityChartGraph").style.display="none";
                } else {
                    document.getElementById("temperatureMsg").innerHTML = "No data for this date!";
                    document.getElementById("temperatureChartGraph").style.display="none";
                }
            }
        });
    };

    let humidityGraphData = {
        datasets: [{
            label: 'Humidity',
            borderColor: 'rgb(51, 153, 255)',

            data: humidityData,
            },
        ]
    };

    let temperatureGraphData = {
        datasets: [{
            label: 'Temperature',
            borderColor: 'rgb(255, 153, 0)',

            data: temperatureData,
            },
        ]
    };

    const humidityConfig = {
        type: 'line',
        data: humidityGraphData,
        options: {
            responsive: true,
            scales: {
                x: {
                    type: 'time',
                },
                y: {
                    ticks: {
                        callback: (label) => `${label}%`,
                    },
                    min: 0,
                    max: 100
                },
            },
            layout: {
                padding: {
                    bottom: 30
                }
            }
        }
    }

    const temperatureConfig = {
        type: 'line',
        data: temperatureGraphData,
        options: {
            responsive: true,
            scales: {
                x: {
                    type: 'time',
                },
                y: {
                    ticks: {
                        callback: (label) => `${label}°C`,
                    },
                },
            },
            layout: {
                padding: {
                    bottom: 30
                }
            }
        }
    }

    const humChart = new Chart(
    document.getElementById('humidityChartGraph'),
    humidityConfig
    );

    const tempChart = new Chart(
    document.getElementById('temperatureChartGraph'),
    temperatureConfig
    );

    let formatDate = (date) => {
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        const localDate = `${year}-${month}-${day}`;
        return localDate;
    };

    refreshData(humChart, "humidity", <?php echo $environmentId;?>, formatDate(new Date()));
    refreshData(tempChart, "temperature", <?php echo $environmentId;?>, formatDate(new Date()));

    $(function () { 
        $("#humidityDatepicker").datepicker({
            setDate: new Date(),
            dateFormat: 'YYYY-mm-dd',
        }).on("changeDate", function (e) {
            var dateObj = $(this).datepicker('getDate');
            refreshData(humChart, "humidity", <?php echo $environmentId;?>, formatDate(dateObj));
        });

        $('#humidityDatepicker').datepicker('update', new Date());

        $("#temperatureDatepicker").datepicker({
            setDate: new Date(),
            dateFormat: 'YYYY-mm-dd',
        }).on("changeDate", function (e) {
            var dateObj = $(this).datepicker('getDate');
            refreshData(tempChart, "temperature", <?php echo $environmentId;?>, formatDate(dateObj));
        });

        $('#temperatureDatepicker').datepicker('update', new Date());

    }); 

    </script>
    <script src="../assets/js/demo/chart-pie-demo.js"></script>

</body>

</html>