<?php
//Call Auth_session on Home
include("../php/auth_session.php");

//Call a connection
include('../php/connection.php');

if(isset($_SESSION["firstname"])) {
    $email = $_SESSION['firstname'];

    // Prepared statement to prevent SQL Injection
    $query = "SELECT * FROM admin_table WHERE email = ?";
    
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if(mysqli_num_rows($result) != 1) {
        die('Oops, we experienced a technical error. Please try again later.');
    } else {  
        while ($row = mysqli_fetch_assoc($result)) {
            $Name = $row["firstname"];
            $surname = $row["lastname"];
            $emailAddress = $row["email"];
            $employeeID = $row["employeeNo"];
            $department = $row["department"];
        }
    }
} else {
    die("Error with the session, please login again.");
}
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

	<!--google material icon-->
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons"
    rel="stylesheet">
    <link rel="stylesheet" href="../css/admi.css">
    <!--link rel="stylesheet" href="profile.css"-->
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
       
    <li  class="">
                <a href="../pages/add_user.php"><i class="bi bi-person-rolodex"></i><span>Add User</span></a>
    </li>
            
    <li>
        <a href="../php/signout.php"><i class="bi bi-box-arrow-left material-icons"></i><span>Sign Out</span></a>
    </li>
 </ul>
       
    </nav-->
    <!-- Page Content  -->
    <div id="content">
    
     <div class="top-navbar">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">

                <i class="bi bi-list material-icons   d-xl-block d-lg-block d-md-mone d-none" id="sidebarCollapse"></i>

                
             <!--a class="navbar-brand" href="#">bukani@sdcreactives.co.za </a-->
             <div class="name-container">
                <input type="text" placeholder="<?php echo $_SESSION["firstname"]; ?>" name="display-name">
                <input type="text" placeholder="<?php echo $_SESSION["surname"]; ?>" name="display-name"-->
            
              </div>  
                
                <button class="d-inline-block d-lg-none ml-auto more-button" type="button" data-toggle="collapse"
                data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
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
                                    <a href="../pages/popUpProfile.php" ><i class="bi bi-person material-icons px-2" name="profileLnk" id="profileLnk" ></i><span> Profile</span></a>
                                </li>
                            </ul>
                        </li>
                        
                    </ul>
                </div>
            </div>
        </nav>
    </div>

    <div class="card-body">     
    <div class="">
    <div class="d-sm-flex align-items-center justify-content-between mb-3">
    <!-- Main -->
    <div class="main">
        <h2>Admin Profile</h2>
        <div class="">
            <div class="card-body">
                
                <?php
     include('../php/connection.php');
    //Call a unique variable from progress page
      //$emsailAddress = $_GET['email'];
   $emailAddres = $_SESSION["firstname"];

   //Hide warning errors
   //error_reporting(E_ERROR | E_PARSE);
 
   //Create a Query
   $result = mysqli_query($conn, "SELECT  firstname, surname, email, companyname, employeeNo, department FROM `admin_table` WHERE email ='$emailAddres'");
 
   if($result) {
    echo("<script LANGUAGE='JavaScript'>
    Swal.fire({
      icon: 'error',
      text: 'Synced',
      confirmButtonText: 'OK',
      confirmButtonColor: '#3085d6',

    }).then(function(){
      window.location.href='http://localhost/DataMaster/';
    });
    </script>");
     //Get a data from user_table row
     while($row = mysqli_fetch_assoc($result)) {
       $firstname = $row['firstname'];
       $lastname = $row['surname'];
       $email = $row['email'];
       $Enterprise = $row['companyname'];
       $employeeID = $row['employeeNo'];
       $dapartment = $row['department'];
      
       echo '<div class="wrappe r">
       <div class="form">
       <!--i class="fa fa-pen fa-xs profile"></i-->
             <div class="name">
                 <label>Name :</label>
                 <label name="name">'.$firstname.'</label>
             </div>
             <div class="surname">
                 <label>Surname :</label>
                 <label name="surname">'.$lastname.'</label>
             </div>
             <div class="emailAddress">
                 <label>Email :</label>
                 <label name="email">'.$email.'</label>
             </div>
             <div class="Enterprise">
                 <label>Enterprise Name :</label>
                 <label name="email">'.$Enterprise.'</label>
             </div>
             <div class="EmployeeID">
                 <label>Employee ID :</label>
                 <label name="employeeid">'.$employeeID.'</label>
             </div>
             <div class="Department">
                 <label>Departmet :</label>
                 <label name="department">'.$dapartment.'</label>
             </div>
             <div class="end">
             </div>
           </div>
           <form action="proFile.php?uniqueEmail='.$email.'" method="post" autocoplete="off" class="form-right" class="requires-validation" novalidate>';
     }
   }else
   {
    echo("<script LANGUAGE='JavaScript'>
    Swal.fire({
      icon: 'error',
      text: 'Opps there seems to be an error please try again later',
      confirmButtonText: 'OK',
      confirmButtonColor: '#3085d6',

    }).then(function(){
      window.location.href='http://localhost/DataMaster/Datamaster/pages/index.php';
    });
    </script>");
}
?>
</div>
        </div>
                
                <footer class="footer" style="position: fixed; bottom: 0;">
                    <div class="container-fluid">
                      <div class="row">
                    <div class="col ">
                     <p class="copyright d-flex justify-content-center "> &copy  Datamaster, 2023 - Privacy Policy
                        </p>
                    </div>
                      </div>
                        </div>
                </footer>

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
   <script src="../js/barchart.js"></script>
  
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
</body>
</html>

