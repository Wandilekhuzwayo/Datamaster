<?php
//Call Auth_session on Home
include("../php/auth_session.php");

//Call a connection
include('../php/connection.php');

//Call a unique variable from progress page
//$email = $_GET['email'];

//Do a query for current visitors
$count = mysqli_query($conn, "SELECT COUNT(timeout) AS total FROM `questions_table` AS q INNER JOIN `user_table` AS u ON q.email_phone = u.email  WHERE q.timeout = '" . " " . "' ");

$row = mysqli_fetch_assoc($count);
$total = $row['total'];

//Do a query for today's visitors in
$todayin = mysqli_query($conn, "SELECT COUNT(*) AS todayin FROM `user_table` ");

$row1 = mysqli_fetch_assoc($todayin);
$total2 = $row1['todayin'];

//Do a query for today's visitors out
$todayout = mysqli_query($conn, "SELECT COUNT(*) AS todayout FROM `questions_table` WHERE timeout > 0 ");

$row2 = mysqli_fetch_assoc($todayout);
$total3 = $row2['todayout'];


//Do a query for Line graph
$query = mysqli_query($conn, "SELECT * FROM account");
$chart_data = '';
while ($rows = mysqli_fetch_array($query)) {
    $chart_data .= "{ year:'" . $rows["year"] . "', profit:" . $rows["profit"] . "}, ";
}
$chart_data = substr($chart_data, 0, -2);
//----------------------------------------------------------------------------------------//
//Start the Session
/* session_start();

   //Start the connection
   include('connection.php');
 
   //Take value from html form
   $email = $_POST['search'];
   $type = $_POST['comType'];*/

if (isset($_POST['profileLnk'])) {

    //Get mobile or email from database
    $queryProfile = mysqli_query($conn, "SELECT mnum, email FROM `user_table` WHERE mnum LIKE '%{$email}%' OR email LIKE '%{$email}%'");

    $rows = mysqli_num_rows($query);

    //final execution
    if ($rows) {
        echo ("<script LANGUAGE='JavaScript'>
       Swal.fire({
         icon: 'success',
         text: 'This Info Corresponds',
         confirmButtonText: 'OK',
         confirmButtonColor: '#3085d6',
           
       }).then(function(){
         window.location.href='http://localhost/DataMaster/Admin_Dash-board/pages/popUpProfile.php?retrievedEmail=$email';
       });
       </script>");
    } else {
        echo ("<script LANGUAGE='JavaScript'>
       Swal.fire({
         icon: 'error',
         text: 'This Info Provided Is Wrong!',
         confirmButtonText: 'OK',
         confirmButtonColor: '#3085d6',
 
       }).then(function(){
         window.location.href='http://localhost/DataMaster/Datamaster-User-Side/Retrieve.html';
       });
       </script>");
    }
}
//----------------------------------------------------------------------------------------//
?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>Datamaster Dashbaord</title>
    <!--Creating line graph with Morris.js-->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">

    <!----css3---->
    <link rel="stylesheet" href="../css/custom.css">
    <link rel="stylesheet" href="../css/styles.css">

    <!-- SLIDER REVOLUTION 4.x CSS SETTINGS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../fontawesome-free/css/all.css">

    <!--google material icon-->
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin.css">
    <script src="https://kit.fontawesome.com/83f97129c2.js" crossorigin="anonymous"></script>

</head>

<body>

    <div class="wrapper" style="min-height:50em;">


        <div class="body-overlay"></div>
        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3><img src="../images/Logo Icon.png" alt="Logo"><span>Datamaster</span></h3>
            </div>
            <ul class="list-unstyled components">
                <li class="active">
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
                            <a href="../pages/activeVisitors.php"><i class="bi bi-radioactive material-icons"></i><span>Active Visitors</span></a>
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
                        <li>
                            <a href="../pages/customReporting.php"><i class="bi bi-file-bar-graph material-icons"></i><span>Custom Report</span></a>
                        </li>
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


                        <i class="bi bi-list material-icons   d-xl-block d-lg-block d-md-mone d-none" id="sidebarCollapse"></i>


                        <!--a class="navbar-brand" href="#">bukani@sdcreactives.co.za </a-->
                        <div class="name-container">
                            <input type="text" placeholder="<?php echo $_SESSION["firstname"]; ?>" name="display-name">
                        </div>


                        <button class="d-inline-block d-lg-none ml-auto more-button" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="bi bi-list material-icons"></span>
                        </button>

                        <div class="collapse navbar-collapse d-lg-block d-xl-block d-sm-none d-md-none d-none" id="navbarSupportedContent">
                            <ul class="nav navbar-nav ml-auto">
                                <li class="dropdown nav-item ">
                                    <!--Query for fetch from the table-->
                                    <?php $sql = "SELECT * FROM `questions_table`  WHERE status='0' AND timeout > 0 ORDER BY id DESC";
                                    $res = mysqli_query($conn, $sql); ?>
                                    <a href="#" class="nav-link" data-toggle="dropdown" id="notifications">
                                        <span class="material-icons">notifications</span>
                                        <span class="notification"><?php echo mysqli_num_rows($res); ?></span>
                                    </a>
                                    <ul class="dropdown-menu">
                                        <?php
                                        if (mysqli_num_rows($res) > 0) {
                                            foreach ($res as $item) {
                                        ?>
                                                <li><?php echo $item["email_phone"] ?></li>
                                                <li><?php echo 'signed out at ', $item["timeout"]; ?></li>
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
                                            <a href="myprofile.php" id="profilebtn"><i class="bi bi-person material-icons px-2"></i><span> Profile</span></a>
                                        </li>
                                    </ul>
                                </li>

                            </ul>
                        </div>
                    </div>
                </nav>
            </div>

            <div class="main-content">
                <div class="d-sm-flex align-items-center justify-content-between mb-3">
                    <h1 class="h3 mb-0 ">Dashboard</h1>

                </div>
                <style>
                    .row :hover {
                        transform: translate3d(5px, 5deg);
                    }
                </style>

                <!-- Modal HTML -->
                <div class="row ng-scope">

                    <div class="col-lg-4 col-md-6">
                        <div style="cursor: pointer;" class="panel widget bg-green">
                            <div class="row row-table">
                                <div class="col-xs-4 text-center bg-green-dark pv-lg">
                                    <em class="fa fa-plus-square-o fa-3x"></em>
                                </div>
                                <div class="col-xs-8 pv-lg">
                                    <div class="h2 mt0 ng-binding"><?php echo $total2; ?></div>
                                    <div class="text-uppercase ng-binding">TODAY VISITORS IN</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div style="cursor: pointer;" class="panel widget bg-blue">
                            <div class="row row-table">
                                <div class="col-xs-4 text-center bg-blue-dark pv-lg">
                                    <em class="fa fa-check-square-o fa-3x"></em>
                                </div>
                                <div class="col-xs-8 pv-lg">
                                    <div class="h2 mt0 ng-binding"><?php echo $total; ?></div>
                                    <div class="text-uppercase ng-binding">TODAY VISITORS CURRENT</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!------------------------------------------------------------------------------------------->
                    <div class="col-lg-4 col-md-6">
                        <div style="cursor: pointer;" class="panel widget bg-blue">
                            <div class="row row-table">
                                <div class="col-xs-4 text-center bg-blue-dark pv-lg">
                                    <em class="fa fa-clock-o fa-3x"></em>
                                    <?php date_default_timezone_set('Africa/Johannesburg') ?>
                                </div>
                                <div class="col-xs-8 pv-lg">
                                    <div class="h2 mt0 ng-binding">
                                        <now format="d" class="h4 mt0"><?php echo date('d'); ?></now>
                                        <now format="mmm" class="h4 mt0"><?php echo date('F'); ?></now>

                                        <!--now format="EEEE" class="text-uppercase"><? php // echo date('l'); 
                                                                                        ?></now-->
                                    </div>
                                    <div class="text-lowercase ng-binding">
                                    <now format="eeee" class="text-uppercase"><?php echo date('l,'); ?></now>

                                        <now format="h:mm" class="h6 mt0"><?php echo date('h:i A'); ?></now>
                                       
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!------------------------------------------------------------------------------------------->




                    <div class="col-lg-4 col-md-6">
                        <div ng-click="toVisitorsReport('TODAY_VISITORS_OUT')" style="cursor: pointer;" class="panel widget bg-purple">
                            <div class="row row-table">
                                <div class="col-xs-4 text-center bg-purple-dark pv-lg">
                                    <em class='fa fa-minus-square-o fa-3x'></em>
                                </div>
                                <div class="col-xs-8 pv-lg">
                                    <div class="h2 mt0 ng-binding"><?php echo $total3; ?></div>
                                    <div class="text-uppercase ng-binding">TODAY VISITORS OUT</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="panel widget bg-gray-light">
                            <div class="row row-table">
                                <div class="col-xs-4 text-center bg-gray-dark pv-lg">
                                    <em class="fa fa-calendar fa-3x"></em>
                                </div>
                                <div class="col-xs-8 pv-lg">
                                    <div class="h2 mt0 ng-binding">0</div>
                                    <div class="text-uppercase ng-binding">MONTHLY VISIT</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6">
                        <div class="panel widget bg-purple">
                            <div class="row row-table">
                                <div class="col-xs-4 text-center bg-purple-dark pv-lg">
                                    <em class="fa fa-comment fa-3x"></em>
                                </div>
                                <div class="col-xs-8 pv-lg">
                                    <div class="h2 mt0 ng-binding">0</div>
                                    <div class="text-uppercase ng-binding">MONTHLY SMS COUNTS</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="footer" style="position: fixed; bottom: 0; padding: 1px; margin: auto; ">
                <div class="container-fluid">
                <footer class="py-3 my-4">
               <div class="row">
                    <div class="col">
                <p class="copyright d-flex justify-content-center"> &copy  Datamaster, 2023 - Privacy Policy</p>
                    </div>
               </div>
            </div>
            </footer>
        </div>
            </div>
            
        </div>
       

        <!-- Second modal dialog -->
    </div>
 

    <!-- jQuery for the norifications -->
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
    <!-- /jQuery for the norifications -->

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="../js/jquery-3.3.1.slim.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery-3.3.1.min.js"></script>


    <script type="text/javascript">
        $(document).ready(function() {
            $('#sidebarCollapse').on('click', function() {
                $('#sidebar').toggleClass('active');
                $('#content').toggleClass('active');
            });

            $('.more-button,.body-overlay').on('click', function() {
                $('#sidebar,.body-overlay').toggleClass('show-nav');
            });

        });
    </script>
</body>

</html>

<script>
    /*Morris.Line({
    element : 'chart',
    data:[<?php echo $chart_data; ?>],
    xkey:'year',
    ykeys:['profit'],
    labels:['Profit'],
    hideHover:'auto',
    stacked:true
  });*/
</script>