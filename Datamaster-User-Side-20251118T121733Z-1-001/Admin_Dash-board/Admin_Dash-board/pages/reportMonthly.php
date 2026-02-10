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
$result = mysqli_query($conn, "SELECT  firstname, surname, email, companyname, employeeNo, department FROM `admin_table` WHERE email ='$emailAddres'");

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
    <script src="https://kit.fontawesome.com/83f97129c2.js" crossorigin="anonymous"></script>
    <script type="text/javascript">
        function goToNewPage() {
            var url = document.getElementById('list').value;
            if (url != 'none') {
                window.location = url;
            }

            toggleView();
        }

        function toggleView() {
            var viewSelect = document.getElementById('view');
            var graphDiv = document.getElementById('graph');
            var tableDiv = document.getElementById('table');
            var downloadPdfButton = document.getElementById('downloadPdfButton');

            if (viewSelect && downloadPdfButton ) {
                console.log('toggleView called');
                if (viewSelect.value === 'graph') {
                    graphDiv.style.display = 'block';
                    tableDiv.style.display = 'none';
                    downloadPdfButton.style.display = 'block';
                    console.log('graph');
                } else if (viewSelect.value === 'table') {
                    graphDiv.style.display = 'none';
                    tableDiv.style.display = 'block';
                    downloadPdfButton.style.display = 'none';
                    console.log('table');
                }
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
                            <!-- <li>
                                <a href="../pages/customReporting.php"><i class="bi bi-file-bar-graph material-icons"></i><span>Custom Report</span></a>
                            </li> -->
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
                    <label for="Intervals">Report Option:</label>
                    <select name="view" id="view" onchange="toggleView()">
                        <option value="graph">Graph</option>
                        <option value="table">Table</option>
                    </select>
                    <input class="btn btn-success ng-binding" type="button" value="Go" onclick="goToNewPage()" />
                </form>
                <!------------------------------------------------Select-Year-For-It's-Months-To-Be-Retrieved------------------------------------------------>

                <?php
                // Initialize the arrays outside the if block
                $visitMonths = [];
                $visitCounts = [];

                if (isset($_POST['search-year'])) {
                    $ClientVisitsMonthly = json_decode($acc, true);

                    foreach ($ClientVisitsMonthly as $VsCounts) {
                        $visitMonths[] = $VsCounts['visit_month'];
                        $visitCounts[] = $VsCounts['visit_count'];
                    }
                }
                ?>

                <div class="d-sm-flex align-items-center justify-content-between mb-3">
                    <form action="" method="POST" name="search-year">
                        <label for="yearSelect">Select Year:</label>
                        <select class="" id="year" name="year" placeholder="Select A Year">
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

                        <input type="submit" name="search-year" class="btn btn-primary" value="Select">
                    </form>
                </div>

                <div id="graph">
                    <!-- Graph content here -->
                    <div class="d-flex justify-content-center">
                        <div class="chart-container" class="graphCanvas" style="position: relative; height: 40vh; width: 60vw">

                            <div class="d-flex justify-content-center">
                                <h6 class="m-0 font-weight-bold text-secondary">Report Visitors Count Graph</h6>
                            </div>
                            <canvas id="monthlyChart" name="monthlyChart" style="width:inherit"></canvas>

                                            <script>
                    var xValues = <?php echo json_encode($visitMonths); ?>;
                    var yValues = <?php echo json_encode($visitCounts); ?>;
                    var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];

                    var formattedData = [];
                    months.forEach(function (month) {
                        var index = xValues.indexOf(months.indexOf(month) + 1);
                        if (index !== -1) {
                            formattedData.push(yValues[index]);
                        } else {
                            formattedData.push(0);
                        }
                    });

                    new Chart("monthlyChart", {
                        type: "line",
                        data: {
                            labels: months,
                            datasets: [{
                                label: "Visitors",
                                data: formattedData,
                                backgroundColor: "rgba(0, 0, 255, 0.5)",
                                borderColor: "rgba(0, 0, 255, 1)",
                                borderWidth: 1,
                                fill: true,
                            }],
                            responsive: true,
                            maintainAspectRatio: false,
                        },
                        options: {
                            legend: {
                                display: false
                            },
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        min: 0,
                                        max: 50
                                        
                                    }
                                }],
                            },
                        },
                    });
                </script>

                            <!-- Display the "Download PDF" button for the graph -->
                            <button class="btn btn-success ng-binding" id="downloadbtn" name="downloadbtn" onclick="generatePDF()">Download PDF</button>
                        </div>
                    </div>
                </div>
            


            <div id="table">
                <div class="container-fluid ng-scope">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <!-- <h5>YEAR REPORT</h5> -->
                                    </div>
                                    <div class="d-flex justify-content-center">
    <h6 class="m-0 font-weight-bold text-secondary">Monthly Report Table (<?php echo isset($_POST['search-year']) ? $_POST['year'] : 'Select Year'; ?>)</h6>
</div>
<div class="panel-body">
    <table class="table table-striped ng-scope ng-table table-hover" id="tableV" name="tableV" style="margin-left: 50;">
        <tr style="background-color: #3ab5e6; color: white;">
            <th style="width:20%">MONTHS</th>
            <th style="width:20%">BUSINESS APPOINTMENT</th>
            <th style="width:20%">PERSONAL VISIT</th>
        </tr>

        <?php

        $totalBusiness = 0;
        $totalPersonal = 0;

        if (isset($_POST['search-year'])) {
            $selectedYear = $_POST['year'];

            // Query to get data based on the selected year
            $dataQuery = "SELECT MONTH(SignInTime) AS month,
                                SUM(CASE WHEN reason_for_visiting = 'business' THEN 1 ELSE 0 END) AS business_count,
                                SUM(CASE WHEN reason_for_visiting = 'personal' THEN 1 ELSE 0 END) AS personal_count
                                FROM `mock_data`
                                WHERE YEAR(SignInTime) = $selectedYear
                                GROUP BY MONTH(SignInTime)";

            $result = mysqli_query($conn, $dataQuery);

            if ($result && mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>";
                    echo "<td>" . date('F', mktime(0, 0, 0, $row['month'], 1)) . "</td>";
                    echo "<td>" . str_pad($row['business_count'], 2, '0', STR_PAD_LEFT) . "</td>";
                    echo "<td>" . str_pad($row['personal_count'], 2, '0', STR_PAD_LEFT) . "</td>";
                    echo "</tr>";

                    // Update totals

                    $totalBusiness += $row['business_count'];
                    $totalPersonal += $row['personal_count'];
                }

                // Display totals row
                echo "<tr>";
                echo "<td><strong>TOTAL</strong></td>";
                echo "<td><strong>" . str_pad($totalBusiness, 2, '0', STR_PAD_LEFT) . "</strong></td>";
                echo "<td><strong>" . str_pad($totalPersonal, 2, '0', STR_PAD_LEFT) . "</strong></td>";
                echo "</tr>";
            } else {
                echo "<tr><td colspan='5'>No data available for the selected year.</td></tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Please select a year to display data.</td></tr>";
        }
        ?>
    </table>
</div>


                <div class="panel-footer">
                    <div type="'excel'" class="ng-isolate-scope">
                        <a class="ng-excel"><span></span></a>
                    </div>
                    <a href="../php/downloadTableMonthly.php?months=<?php echo json_encode($visitMonths); ?>&counts=<?php echo json_encode($visitCounts); ?>" class="btn btn-success ng-binding" id="downloadbtn"
                     name="downloadbtn" target="_blank">View PDF Table</a>
                                <a href="../php/downloadTableMonthly.php" class="btn btn-success ng-binding" id="downloadbtn" name="downloadbtn">Download PDF</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <script type="text/javascript">
                // Function to generate and download PDF
                function generatePDF() {
                    console.log('Button clicked');
                    // ... (your PDF generation code here)
                    // Create a new jsPDF instance
                    window.jsPDF = window.jspdf.jsPDF;
                    const doc = new jsPDF('landscape');

                    // Add company logo and details
                    const title = 'Reporting: Monthly Graph';
                                    const companyLogo = '../images/Logo.jpeg';
                                    const companyName = 'Enterprise  : <?php echo $Enterprise?>';
                                    const email = 'Email  : <?php echo $email?>';
                                    const address = 'Address  : <?php echo $address?>';
                                    const date = 'Date  : <?php echo $currentDatetime ?>';

                                    doc.setFontSize(14);
                                    doc.addImage(companyLogo, 'PNG', 13, 5, 78, 30);
                                    doc.text(title, 210, 17);
                                    doc.setFontSize(11);
                                    doc.text(companyName, 210, 22);
                                    doc.text(email, 210, 26);
                                    doc.text(address, 210, 31);
                                    doc.text(date, 210, 37);

                    // Get the canvas element
                    const canvas = document.getElementById('monthlyChart');
                    // Convert canvas to base64 image data
                    const imageData = canvas.toDataURL('image/png');

                    // Add chart image to the PDF
                    doc.addImage(imageData, 'PNG', 23, 60, 260, 140);
                    //doc.addImage(imageData, 'PNG', 10, 50, 190, 100);

                    // Save the PDF
                    //doc.save('chart.pdf');
                    // Get the PDF data as a Blob object
                    const pdfBlob = doc.output('blob');

                    // Create a temporary URL for the PDF blob
                    const url = URL.createObjectURL(pdfBlob);

                    // Open the PDF in a new browser tab
                    window.open(url, '_blank');
                }

                function redirectToAnotherPage() {
                    // Replace 'another_page.html' with the URL of the page you want to redirect to.
                    window.location.href = 'reportMonthlyTBl.php';
                }
            </script>
        </div>

    </div>
    <!-- <div class="footer" style="position: fixed; bottom: 0; padding: 1px; margin: auto; ">
        <div class="container-fluid">
            <footer class="py-3 my-4">
                <div class="row">
                    <div class="col">
                        <p class="copyright d-flex justify-content-center"> &copy Datamaster, 2023 - Privacy Policy</p>
                    </div>
                </div>
        </div>
        </footer> -->
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
 
    <script>
        function toggleView() {
            var viewSelect = document.getElementById('view');
            var graphDiv = document.getElementById('graph');
            var tableDiv = document.getElementById('table');
            var downloadPdfButton = document.getElementById('downloadPdfButton');

            if (viewSelect) {
                console.log('toggleView called');
                if (viewSelect.value === 'graph') {
                    graphDiv.style.display = 'block';
                    tableDiv.style.display = 'none';
                    downloadPdfButton.style.display = 'block';
                    console.log('graph');
                } else if (viewSelect.value === 'table') {
                    graphDiv.style.display = 'none';
                    tableDiv.style.display = 'block';
                    downloadPdfButton.style.display = 'none';
                    console.log('table');
                }

            }
        }
        // Call toggleView initially to set the initial view based on the selected option
        toggleView();
    </script>

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