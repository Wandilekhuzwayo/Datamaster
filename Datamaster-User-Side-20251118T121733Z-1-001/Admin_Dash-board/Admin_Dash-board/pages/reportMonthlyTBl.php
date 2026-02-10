<?php
//Call Auth_session 
include("../php/auth_session.php");

//Get Connection
include('../php/connection.php');

//Retrive Yearly ChartData
include('../DataFetchers/monthly_Chart_Retriv.php');


//Select query
if (isset($_POST['search-year'])) { //change this function to search for monthly visit using a selected year
    $searchValue = $_POST['year'];
    $query = "SELECT MONTH(signInTime) as visit_month, COUNT(*) AS visit_count FROM mock_data WHERE YEAR(signInTime) = '" . $_POST['year'] . "' GROUP BY visit_month";
    $result = filterTable($query);
} else {
    $query = "SELECT id, email_phone, person_name, person_surname, person_contact, timein, timeout FROM `questions_table`";
    $result = filterTable($query);
}

function filterTable($query)
{
    //Get Connection
    include('../php/connection.php');

    //Resulting
    $result = mysqli_query($conn, $query);

    return $result;
}

$emailAddres = $_SESSION["firstname"];
$result = mysqli_query($conn, "SELECT  firstname, surname, email, companyname, employeeNo, department, addresses FROM `admin_table` WHERE email ='$emailAddres'");

if ($result) {

    //Get a data from user_table row
    while ($row = mysqli_fetch_assoc($result)) {
        $firstname = $row['firstname'];
        $lastname = $row['surname'];
        $email = $row['email'];
        $Enterprise = $row['companyname'];
        $employeeID = $row['employeeNo'];
        $dapartment = $row['department'];
        $addresses = $row['addresses'];
    }
    $currentTimestamp = time();
    $currentDatetime = date("Y-m-d H:i:s", $currentTimestamp);
    // Format the timestamp as a datetime string
    //echo "Current Datetime: " . $currentDatetime . "<br>";
}

?>

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>Datamaster Monthly Reporting
    </title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">

    <!----css3---->
    <link rel="stylesheet" href="../css/custom.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/styling.css">

    <!-- SLIDER REVOLUTION 4.x CSS SETTINGS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../fontawesome-free/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!--google material icon-->
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
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>

</head>

<body>
    <div class="wrapper" style="min-height:50em;">
        <div class="body-overlay">
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
                                <a href="../pages/add_user.php"><i class="bi bi-person-plus material-icons"></i><span>Add User</span></a>
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
        </div>

        <!-- Page Content  -->
        <div id="content">

            <div class="top-navbar">
                <nav class="navbar navbar-expand-lg">
                    <div class="container-fluid">


                        <i class="bi bi-list material-icons   d-xl-block d-lg-block d-md-mone d-none" id="sidebarCollapse"></i>


                        <!--<a class="navbar-brand" href="#">bukani@sdcreactives.co.za </a>-->
                        <div class="name-container">
                            <input type="text" placeholder="<?php echo $_SESSION['firstname']; ?>" name="display-name">
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
                                            <a href="myprofile.php"><i class="bi bi-person material-icons px-2"></i><span> Profile</span></a>
                                        </li>
                                    </ul>
                                </li>

                            </ul>
                        </div>
                    </div>
                </nav>
            </div>

            <div class="main-content">
                <form>
                    <label for="Intervals">Choose Time Interval:</label>
                    <select name="list" id="list" accesskey="target">
                        <option value='report.php' selected>Choose time interval</option>
                        <option value="reportYearly.php">Yearly</option>
                        <option value="reportMonthly.php">Monthly</option>
                        <option value="reportWeekly.php">Weekly</option>
                        <option value="reportDaily.php">Daily</option>
                    </select>
                    <input class="btn btn-success ng-binding" type=button value="Go" onclick="goToNewPage()" />
                </form>



                <!------------------------------------------------Select-Year-For-It's-Months-To-Be-Retrieved------------------------------------------------>

                <div class="d-sm-flex align-items-center justify-content-between mb-3">
                    <form action="" method="POST" name="search-year">
                        <label for="yearSelect">Select Year:</label>
                        <select class="" id="year" name="year" placeholder=" Select A Year">
                            <?php
                            // Get the distinct years from the database
                            $yearQuery = "SELECT YEAR(SignInTime) AS year
                                            FROM `mock_data`
                                            GROUP BY YEAR(SignInTime)";

                            $yearResult = mysqli_query($conn, $yearQuery);

                            while ($row = mysqli_fetch_assoc($yearResult)) {
                                $selected = ($_POST['year'] ?? '') == $row['year'] ? 'selected' : '';
                                echo "<option value='" . $row['year'] . "' $selected>" . $row['year'] . "</option>";
                            }
                            ?>
                        </select>
                        <input type="submit" name="search-year" class="btn btn-primary" value="Selct" onclick="retrieveDbToChart()">
                    </form>
                </div>
            </div>

            <div class="d-flex justify-content-center">
                <h6 class="m-0 font-weight-bold text-secondary">Monthly Visitors count</h6>
            </div>

            <div class="d-flex justify-content-center">
                <div class="chart-container" class="graphCanvas" style="position: relative; height: 40vh; width: 60vw">
                    <?php
                    // Initialize the arrays outside the if block
                    $visitMonths = [];
                    $visitCounts = [];

                    if (isset($_POST['search-year'])) {
                        // Your existing code for handling form submission
                        // ...
                        $ClientVisitsMonthly = json_decode($acc, true);

                        foreach ($ClientVisitsMonthly as $VsCounts) {
                            $visitMonths[] = $VsCounts['visit_month'];
                            $visitCounts[] = $VsCounts['visit_count'];
                        }
                        // Rest of your code...
                    }

                    // Rest of your code, including the HTML table and other PHP logic...
                    ?>

                    <table id="data-table"  name="data-table" width="90%" cellpadding="0" cellspacing="0" border="0" style="margin-left: 50;">
                        <tr style="background-color:#3ab5e6; color: white; ">
                            <th style="width:25%">Month</th>
                            <th style="width:25%">Visits Count</th>
                        </tr>
                        <?php for ($i = 0; $i < count($visitMonths); $i++) : ?>
                            <tr>
                                <td><?php echo $visitMonths[$i]; ?></td>
                                <td><?php echo $visitCounts[$i]; ?></td>
                            </tr>
                        <?php endfor; ?>
                    </table>

                    <button class="btn btn-success ng-binding" id="downloadbtn" name="downloadbtn" onclick="generateTblPDF()">Download Table</button>
                    
                    <script type="text/javascript">
    // Function to generate and download PDF
    function generateTblPDF() {
        console.log('Button clicked');

        // Create a new jsPDF instance
        window.jsPDF = window.jspdf.jsPDF;
                            const doc = new jsPDF('landscape');

      //

        // Add company logo and details
        const title = 'Reporting: Monthly Graph';
        const companyLogo = "../images/Revamped Logo_page-0001.png";
        const companyName = 'Enterprise: <?php echo $Enterprise ?>';
        const email = 'Email: <?php echo $email ?>';
        const address = 'Address: <?php echo $address ?>';
        const date = 'Date: <?php echo $currentDatetime ?>';

        doc.setFontSize(16);
        doc.addImage(companyLogo, 'PNG', 10, 10, 140, 40);
        doc.text(title, 200, 23);
        doc.setFontSize(12);
        doc.text(companyName, 200, 32);
        doc.text(email, 200, 37);
        doc.text(address, 200, 42);
        doc.text(date, 200, 48);

        // Create a canvas element from the HTML table and render it
        const table = document.getElementById('data-table');
        const canvas = document.createElement('canvas');
        const tableData = new XMLSerializer().serializeToString(table);

        // Set canvas dimensions (adjust as needed)
        canvas.width = 800;
        canvas.height = 400;

        // Convert the table to a canvas
        canvg(canvas, tableData);

        // Convert canvas to base64 image data
        const imageData = canvas.toDataURL('image/png');

        // Add chart image to the PDF
        doc.addImage(imageData, 'PNG', 23, 60, 260, 140);

        // Save the PDF
        doc.save('report.pdf');
    }
</script>


                </div>
                <!-- Second modal dialog -->
                <!--/div--->
            </div>

            <!--footer class="footer" style="position: fixed; bottom: 0;">
            <div class="container-fluid">
                <div class="row">
                    <div class="col ">
                        <p class="copyright d-flex justify-content-center "> &copy Datamaster, 2023 - Privacy Policy
                        </p>
                    </div>
                </div>
            </div>
        </footer-->

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

        </div>
</body>

</html>