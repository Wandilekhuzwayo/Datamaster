<?php 
  // Call Auth_session on Home
  include("../php/auth_session.php");

  // Get Connection
  include ('../php/connection.php');

  // Retrive custom_Retriv
  include('../DataFetchers/VisitFetchTBL/custom_Retriv.php');
  if (isset($_POST['custom_report'])) {
    // Get the selected year & month from the form
    $fromDate = $_POST['fromDate'];
    $toDate = $_POST['toDate'];

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Retrieve data from the database for the specified date range
        $query = "
            SELECT
                DATE(signInTime) AS visit_date,
                COUNT(*) AS visit_count,
                visitorsname 
            FROM
                mock_data
            WHERE
                signInTime >= :fromDate AND vacuatingTime <= :toDate
            GROUP BY
                visitorsname, visit_date
            ORDER BY
                visit_date
        ";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':fromDate', $fromDate);
        $stmt->bindParam(':toDate', $toDate);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        // Handle database connection errors
        echo "Error: " . $e->getMessage();
    }
}
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

            <div class="main-content">
            <div class="d-sm-flex align-items-center justify-content-between mb-3">
                <h1 class="h3 mb-0 ">Add User</h1>

            </div>

            <div class="card-body">
                        <form action="../php/Auth.php" class="row form-signin needs-validation " method="post" novalidate autocomplete="off">
                            <div class="col-md-6 mb-2">
                                <label for="firstname" class="form-label">First Name:</label>
                                <input type="text" class="form-control" name="firstname" id="firstname" placeholder="eg.John" required>
                            </div>
                            <div class="col-md-6 mb-2">  
                                <label for="lastname" class="form-label">Last Name:</label>
                                <input type="text" class="form-control" name="lastname" id="lastname" placeholder="eg.Smith" required>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="email" class="form-label">Email:</label>
                                <input type="text" class="form-control" name="email" id="email" placeholder="johnsmith@domain.com"required>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="lastname" class="form-label">Company Name:</label>
                                <input type="text" class="form-control" name="companyname" id="companyname" placeholder="eg.SD Creatives" required>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="firstname" class="form-label">Employee Number:</label>
                                <input type="text" class="form-control" name="employeeno" id="employeeno" placeholder="eg.02001" required>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label for="lastname" class="form-label">Department:</label>
                                <input type="text" class="form-control" name="department" id="department" placeholder="eg.IT Department" required>
                            </div>
                            <div class="col-md-6 mb-2">
                                 <label for="password" class="form-label">Password:</label>
                                 <input type="password" class="form-control" name="password" id="password" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="conf-password" class="form-label">Re-Type Password:</label>
                                <input type="password" class="form-control" name="conf-password" id="conf-password" required>
                            <div class="invalid-feedback">Passwords do not match.</div>
                </div>

        
            <div class="col-md-12 mb-3">
            <div class="form-check">
                <input class="form-check-input me-2" type="checkbox" value="" id="form2Example3c" required>
                <label class="form-check-label" for="form2Example3">
                    Creating an account means you're okay with our <a href="#!">Terms of service</a>
                </label>
            </div>
        </div>

          <div class="col-md-2 mb-3">
            <input type="submit" value="Register" name="signup" class="btn px-5 btn-info">
        </div>
         </form>
     </div>

     <script>
    // JavaScript for password matching validation
    const password = document.getElementById("password");
    const confirmPassword = document.getElementById("conf-password");
    const alertMessage = confirmPassword.nextElementSibling;
    const form = document.querySelector('form');

    function validatePassword() {
        if (password.value !== confirmPassword.value) {
            confirmPassword.setCustomValidity("invalid");
            alertMessage.style.display = "block";
        } else {
            confirmPassword.setCustomValidity("");
            alertMessage.style.display = "none";
        }
    }

    function onSubmit(event) {
        validatePassword();
        if (password.value !== confirmPassword.value) {
            event.preventDefault(); // Prevent form submission if passwords don't match
        }
    }

    password.addEventListener("input", validatePassword);
    confirmPassword.addEventListener("input", validatePassword);

    form.addEventListener('submit', onSubmit);
</script>

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
