<?php
// Call Auth_session on Home
include("../php/auth_session.php");

// Get Connection
include('../php/connection.php');

// Retrive custom_Retriv
include('../DataFetchers/VisitFetchTBL/custom_Retriv.php');

?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>Datamaster Weekly Reporting</title>
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
	    
    <!-- CSS Styles -->
    <link rel="stylesheet" href="../css/custom.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/styling.css">

    <!-- SLIDER REVOLUTION 4.x CSS SETTINGS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../fontawesome-free/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../css/admi.css">
    <script src="https://kit.fontawesome.com/83f97129c2.js" crossorigin="anonymous"></script>
    <script type="text/javascript">
        function goToNewPage() {
            var url = document.getElementById('list').value;
            if (url != 'none') {
                window.location = url;
            }
        }
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script> 
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.5.141/pdf.min.js"  
    integrity="sha512-BagCUdQjQ2Ncd42n5GGuXQn1qwkHL2jCSkxN5+ot9076d5wAI8bcciSooQaI3OG3YLj6L97dKAFaRvhSXVO0/Q==" 
    crossorigin="anonymous" 
    referrerpolicy="no-referrer">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/addimage.min.js"></script>
    <script src="cdnjs.cloudflare.com/ajax/libsjspdf/2.4.0jspdf.debug.js"></script>
    <script src="../js/jquery-3.3.1.slim.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery-3.3.1.min.js"></script>
</head>
  
<body>
    <div class="wrapper" style="min-height:50em;">
        <div class="body-overlay"></div>
        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3><img src="../images/Logo Icon.png" alt="Logo"><span> Datamaster</span></h3>
            </div>
            <ul class="list-unstyled components">
        <li  class="active">
            <a href="../pages/index.php" class="dashboard"><i class="bi bi-speedometer2 material-icons"></i><span>Dashboard</span></a>
        </li>
    
            <div class="small-screen navbar-display">
            
            </div>
        
            <li class="dropdown">
                <a href="#homeSubmenu1" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="bi bi-people material-icons"></i><span>Manage Users</span></a>
                <ul class="collapse list-unstyled menu" id="homeSubmenu1">
                    <li>
                        <a href="../pages/add_user.php"><i class="bi bi-person-plus material-icons"></i><span>Add Users</span></a>
                    </li>
                    <li>
                        <a href="../pages/viewUsers.php"><i class="bi bi-person-workspace material-icons"></i> <span>View Users</span></a>
                    </li>
                   
                </ul>
            </li>
            
            <li class="dropdown">
                <a href="#pageSubmenu2" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                <i class="bi bi-pen material-icons"></i><span>Manage Visitors</span></a>
                    <ul class="collapse list-unstyled menu" id="pageSubmenu2">
                        <li>
                            <a href="../pages/activeVisitors.php"><i class="bi bi-radioactive material-icons" ></i><span>Active Visitors</span></a>
                        </li>
                        <li>
                            <a href="../pages/visitorsHistory.php"><i class="bi bi-clock-history material-icons"></i><span>History</span></a>
                        </li>
                        <li>
                            <a href="../pages/visitorContact.php"><i class="bi bi-person-lines-fill material-icons"></i><span>Registered Visitors</span></a>
                        </li>
                    </ul>
            </li>


            <li class="dropdown">
            <a href="#homeSubmenu3" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
          <i class="bi bi-graph-up-arrow"></i><span>Reporting</span></a>
             <ul class="collapse list-unstyled menu" id="homeSubmenu3">
        <li>
            <a href="../pages/report.php"><i class="bi bi-calendar material-icons" class="dashboard"></i><span>Time Interval Reports</span></a>
        </li>
        <!--li>
            <a href="../pages/customReporting.php"><i class="bi bi-file-bar-graph material-icons"></i><span>Custom Report</span></a>
        </li-->
    </ul>
    </li>

            
    <li>
        <a href="../php/signout.php"><i class="bi bi-box-arrow-left material-icons"></i><span>Sign Out</span></a>
    </li>
 </ul>
        </nav>

        <!-- Page Content  -->
        <div id="content">
            <div class="top-navbar">
                <nav class="navbar navbar-expand-lg">
                    <div class="container-fluid">
                        <i class="bi bi-list material-icons d-xl-block d-lg-block d-md-mone d-none" id="sidebarCollapse"></i>
                        <div class="name-container">
                            <input type="text" placeholder="<?php echo $_SESSION['firstname']; ?>" name="display-name">
                        </div>  
                        <button class="d-inline-block d-lg-none ml-auto more-button" type="button" data-toggle="collapse"
                        data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="bi bi-list material-icons"></span>
                        </button>
                        <div class="collapse navbar-collapse d-lg-block d-xl-block d-sm-none d-md-none d-none" id="navbarSupportedContent">
                            <ul class="nav navbar-nav ml-auto">   
                                <li class="dropdown nav-item ">
                                    <!--Query for fetch from the table-->
                                    <?php 
                                    $sql = "SELECT * FROM `questions_table`  WHERE status='0' AND timeout > 0 ORDER BY id DESC";
                                    $res = mysqli_query($conn, $sql);
                                    ?>
                                    <a href="#" class="nav-link" data-toggle="dropdown" id="notifications">
                                        <span class="material-icons">notifications</span>
                                        <span class="notification"><?php echo mysqli_num_rows($res); ?></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <?php
                                        if (mysqli_num_rows($res) > 0) {
                                            foreach ($res as $item) {
                                                ?>
                                                <li><?php echo $item["email_phone"]?></li>
                                                <li><?php echo 'signed out at ',$item["timeout"]; ?></li>
                                            <?php }
                                        } ?>
                                    </ul>
                                </li>
                                <li class="dropdown nav-item">
                                    <a class="nav-link" href="#" data-toggle="dropdown">
                                        <span class="material-icons">person</span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="../pages/forgotPassoword.html"><i class="bi bi-unlock material-icons px-2"></i><span>Change Password</span></a>
                                        </li>
                                        <li>
                                            <a href="popUpProFile.php"><i class="bi bi-person material-icons px-2"></i><span> Profile</span></a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>

   
          
    <div class="d-sm-flex align-items-center justify-content-between mb-3" style="display: flex; align-items: center; justify-content: space-between;">
    <div class='card-body'> 
    <form class="formm" id="custom_report"  method="post" name="custom_report" >
        <div class="row">
                <div class="col-md-4">
                  <label for="fromDate">From Date:</label>
                     <input type="date" id="fromDate" name="fromDate" id="fromDate" class="form-control" value="<?php if(isset($_GET['fromDate'])){echo $_GET['fromDate'];} ?>" required>
                </div>
            
                <div class="col-md-4">
                 <label for="toDate">To Date:</label>
                    <input type="date" id="toDate" name="toDate" id="toDate" class="form-control" value="<?php if(isset($_GET['toDate'])){echo $_GET['toDate'];} ?>" required>
                </div>

             <div class="col-md-4">
                    <label>Select time frame</label><br>
                 <input type="submit" name="custom_report" class="btn btn-primary" value="Select">
                 <!--button type="submit" name="submit" class="btn btn-primary">Select</button-->
            </div>
    </div>
 </form>
</div>
</div>
                
                
        <div class="container-fluid ng-scope">
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading"></div>
                        <div class="panel-body">
                            <div class="">
                                <table class="table table-striped ng-scope ng-table table-hover">
                                    <thead style="background-color: #3e3e3e;">
                                        <tr>
                                            <th>Date</th>
                                            <th>Access Granted</th>
                                            <th>Visitor Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    if (isset($_POST['fromDate']) && isset($_POST['toDate'])) 
                                    {
                                        $fromDate = $_POST['fromDate'];
                                        $toDate = $_POST['toDate'];
                                       
                                        
                                        //session_start();
                                        $_SESSION['fromDate'] = $fromDate;
                                        $_SESSION['toDate'] =  $toDate;
 
                                        try         
                                        {
                                            // Prepare the query with placeholders
                                             $query = "
                                            SELECT
                                                DATE(signInTime) AS visit_date,
                                                COUNT(*) AS visit_count,
                                                visitorsname 
                                            FROM
                                                mock_data
                                            WHERE
                                                signInTime BETWEEN '$fromDate' AND '$toDate'
                                            GROUP BY
                                                visitorsname, visit_date
                                            ORDER BY
                                                visit_date
                                            ";

                                            $query_run = mysqli_query($conn, $query);

                                            if(mysqli_num_rows($query_run) > 0)
                                                {
                                                    foreach($query_run as $row)
                                                        {
                                                            ?>
                                                            <tr>
                                                            <td><?= $row['visit_date'] ?></td>
                                                            <td><?= $row['visit_count'] ?></td>
                                                            <td><?= $row['visitorsname'] ?></td>
                                                            </tr>
                                                            <?php       
                                                        }                                        
                                                } }                                           
                                 catch(PDOException $e) 
                                 {
                                    // Handle database connection errors
                                    echo "Error: " . $e->getMessage();
                                }
                            }
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <a href="../pages/custom_Download.php" style="margin-left: 7px" id="pdfdownloadbtn" class="btn btn-success ng-binding">Download PDF</a>  
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
   
         <!---   <img src="../images/Site-Stats.gif" --->
         </div>
                
                <div class="panel-footer">
                    <footer class="footer" style=" bottom: 0;">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col">
                                    <p class="copyright d-flex justify-content-center">
                                        &copy; Datamaster, 2023 - Privacy Policy
                                    </p>
                                </div>
                            </div>
                        </div>
                    </footer>
                </div>
            </div>  
        </div>
        <!-- Second modal dialog -->
        <!--/div--->

        <!-- jQuery for the notifications -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                $("#notifications").on("click", function() {
                    $.ajax({
                        url: "../php/readNotifications.php",
                        success: function(res) {
                            console.log(res);
                        }
                    });
                });
            });
        </script>
        <!-- /jQuery for the notifications -->

        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script type="text/javascript">
            $(document).ready(function () {
                $('#sidebarCollapse').on('click', function () {
                    $('#sidebar').toggleClass('active');
                    $('#content').toggleClass('active');
                });

                $('.more-button,.body-overlay').on('click', function () {
                    $('#sidebar,.body-overlay').toggleClass('show-nav');
                });
            });
        </script> 
    </div>
</body> 
</html>