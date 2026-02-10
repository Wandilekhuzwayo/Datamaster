<?php 
// Call Auth_session on Home
include("../php/auth_session.php");

// Get Connection
include('../php/connection.php');

// Select query
if(isset($_POST['search-info'])){
  $searchValue = $_POST['search'];
  $query = "SELECT fname, lname, mnum, email, email_phone, timein FROM `user_table` AS u INNER JOIN `questions_table` AS q ON u.email = q.email_phone 
  WHERE CONCAT(`fname`, `lname`, `mnum`, `email`, `email_phone`, `timein`) LIKE '%".$searchValue."%'";
  $result = filterTable($query);
}
else {
  $query = "SELECT id, firstname, surname, email, employeeNo, companyname, department FROM admin_table";
  $result = filterTable($query);
}

function filterTable($query) {
  // Get Connection
  include('../php/connection.php');

  // Execute query
  $result = mysqli_query($conn, $query);

  return $result;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Datamaster Active Visitors</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
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
</head>
<body>
    <div class="wrapper" style="min-height: 50em;">
        <div class="body-overlay"></div>
        <nav id="sidebar">
        <div class="sidebar-header">
            <h3><img src="../images/Logo Icon.png" alt="Logo"><span>Datamaster</span></h3>
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
                        <a href="../pages/add_clients.php"><i class="bi bi-person-plus material-icons"></i><span>Add Clients</span></a>
                    </li>
                    <li>
                        <a href="../pages/viewUsers.php"><i class="bi bi-person-workspace material-icons"></i> <span>View clients</span></a>
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
        <li>
            <a href="../pages/customReporting.php"><i class="bi bi-file-bar-graph material-icons"></i><span>Custom Report</span></a>
        </li>
    </ul>
    </li>

    <li class="dropdown">
            <a href="#homeSubmenu4" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
          <i class="bi bi-clipboard-check"></i><span>Users</span></a>
             <ul class="collapse list-unstyled menu" id="homeSubmenu4">
        <li>
            <a href="../pages/add_user.php"><i class="bi bi-person-rolodex" class="dashboard"></i><span>Add User</span></a>
        </li>
        <li>
            <a href="../pages/view_Admins.php"><i class="bi bi-card-checklist"></i><span>View Users</span></a>
        </li>
    </ul>
    </li>
       
    <!--li  class="">
                <a href="../pages/add_user.php"><i class="bi bi-person-rolodex"></i><span>Add User</span></a>
</li-->
            
    <li>
        <a href="../php/signout.php"><i class="bi bi-box-arrow-left material-icons"></i><span>Sign Out</span></a>
    </li>
 </ul>
       
    </nav>
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
                                <li class="dropdown nav-item">
                                    <!-- Query for fetch from the table -->
                                    <?php 
                                        $sql = "SELECT * FROM `admin_table` ORDER BY id DESC";
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
                                            <a href="popUpProFile.php"><i class="bi bi-person material-icons px-2"></i><span>Profile</span></a>
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
                    <h1 class="h3 mb-0 ">View Users(Admin`s)</h1>
                    <form action="activeVisitors.php" method="POST">
                        <input type="text" class="control-search" name="search" placeholder="Search Here"/>
                        <button type="submit" class="btn btn-primary" name="search-info"><i class="fa fa-search" aria-hidden="true"></i></button>
                    </form>
                </div>

                <!-- Modal HTML -->
                <div class="container-fluid ng-scope">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                </div>
                                <div class="panel-body">
                                    <div class="">
                                        <table class="table table-striped ng-scope ng-table table-hover">
                                            <thead style="background-color: #3e3e3e;">
                                                <tr>
                                                    <th>ID.</th>
                                                    <th>Name</th>
                                                    <th>Surname</th>
                                                    <th>Email</th>
                                                    <th>Employee ID</th>
                                                    <th>Company Name</th>
                                                    <th>Department</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (mysqli_num_rows($result) > 0) {
                                                    $no = 1;
                                                    while ($data = mysqli_fetch_assoc($result)) {
                                                ?>
                                                        <tr>
                                                            <td><?php echo $no; ?></td>
                                                            <td><?php echo ucwords(strtolower($data['firstname'])); ?></td>
                                                            <td><?php echo ucwords(strtolower($data['surname'])); ?></td>
                                                            <td><?php echo ucwords(strtolower($data['email'])); ?></td>
                                                            <td><?php echo $data['employeeNo']; ?></td>
                                                            <td><?php echo ucwords(strtolower($data['companyname'])); ?></td>
                                                            <td><?php echo ucwords(strtolower($data['department'])); ?></td>
                                                        </tr>
                                                <?php
                                                        $no++;
                                                    }
                                                } else {
                                                ?>
                                                    <script type="text/javascript">
                                                        // Deactivating or unclick
                                                        getElementById('pdfdownloadbtn').disabled = true;
                                                        dwnldbtn = document.getElementById('pdfdownloadbtn').
                                                        dwnldbtn.disabled = true;

                                                        Swal.fire({
                                                            icon: 'error',
                                                            title: 'Error',
                                                            text: 'There is no valid information to download',
                                                            confirmButtonText: 'OK',
                                                            confirmButtonColor: '#3085d6',
                                                        }).then(function() {
                                                            window.location.href='../Admin_Dash-board/pages/index.php';
                                                        });
                                                    </script>
                                                    <tr>
                                                        <td colspan="8">No Data Found</td>
                                                    </tr>
                                                <?php 
                                                } ?>
                                            </tbody>
                                        </table>
                                        <div class="ng-scope">
                                            <div class="ng-scope"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-footer">
                                    <div type="'excel'" class="ng-isolate-scope"><a class="ng-excel"><span></span></a></div>
                                    <a href="../php/downloadVisitorsPDF.php" style="margin-left: 7px" id="pdfdownloadbtn" class="btn btn-success ng-binding">Download PDF</a>  
                                    <a href="../php/downloadVisitors.php" style="margin-left: 7px" class="btn btn-success ng-binding">Download XLS</a>   
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
        </div>
    </div>
    <!-- jQuery for the notifications -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#notifications").on("click", function () {
                $.ajax({
                    url: "../php/readNotifications.php",
                    success: function (res) {
                        console.log(res);
                    }
                });
            });
        });
    </script>
    <!-- /jQuery for the notifications -->

    <!-- jQuery and other scripts -->
    <script src="../js/jquery-3.3.1.slim.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/barchart.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $('#sidebarCollapse').on('click', function () {
                $('#sidebar').toggleClass('active');
                $('#content').toggleClass('active');
            });

            $('.more-button, .body-overlay').on('click', function () {
                $('#sidebar, .body-overlay').toggleClass('show-nav');
            });
        });
    </script>

    <script>

    </script>
</body>
</html>
