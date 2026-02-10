<?php
// Call Auth_session on Home
include("../php/auth_session.php");

// Get Connection
include('../php/connection.php');

// Retrive Weekly ChartData
include('../DataFetchers/weekly_Chart_Retriv.php');

// Select query
if (isset($_POST['search-year'])) {

    // change this function to search for monthly visit using a selected year
    $searchValue = $_POST['year'];
    $query = "SELECT MONTH(signInTime) as visit_month, COUNT(*) AS visit_count FROM mock_data WHERE YEAR(signInTime) = '" . $_POST['year'] . "' GROUP BY visit_month";
    $result = filterTable($query);
} else {
    $query = "SELECT id, email_phone, person_name, person_surname, person_contact, timein, timeout FROM `questions_table`";
    $result = filterTable($query);
}

function filterTable($query)
{
    // Get Connection
    include('../php/connection.php');

    // Resulting
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
    <link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
    <script src="https://kit.fontawesome.com/83f97129c2.js" crossorigin="anonymous"></script>
    <script type="text/javascript">
        function goToNewPage() {
            var url = document.getElementById('list').value;
            if (url != 'none') {
                window.location = url;
            }

            function toggleView() {
                console.log("Toggle Select")
                var viewSelect = document.getElementById('view');
                var graphDiv = document.getElementById('graph');
                var tableDiv = document.getElementById('table');
                var downloadPdfButton = document.getElementById("downloadbtn");


                if (viewSelect.value === 'graph') {
                    graphDiv.style.display = 'block';
                    tableDiv.style.display = 'none';
                    downloadPdfButton.style.display = 'block';
                    console.log('Graph');
                } else if (viewSelect.value === 'table') {
                    graphDiv.style.display = 'none';
                    tableDiv.style.display = 'block';
                    downloadPdfButton.style.display = 'nono';
                    console.log('Table');
                }
            }
        }

        
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.5.141/pdf.min.js" integrity="sha512-BagCUdQjQ2Ncd42n5GGuXQn1qwkHL2jCSkxN5+ot9076d5wAI8bcciSooQaI3OG3YLj6L97dKAFaRvhSXVO0/Q==" crossorigin="anonymous" referrerpolicy="no-referrer">
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
                        <button class="d-inline-block d-lg-none ml-auto more-button" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
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

                    <label for="viewSelect">Select View:</label>
                    <select name="view" id="view" onchange="toggleView()">
                        <option value="graph">Graph</option>
                        <option value="table">Table</option>
                    </select>


                    <input class="btn btn-success ng-binding" type=button value="Go" onclick="goToNewPage()" />
                </form>

                <style>
                    .goToNewPageformm {
                        background-color: yellow;
                        list-style-type: none;
                        text-align: center;
                        margin: 0;
                        padding: 0;
                    }

                    .formm li {
                        display: inline-block;
                        font-size: 20px;
                        padding: 20px;
                    }
                </style>

                <div class="d-sm-flex align-items-center justify-content-between mb-3" style="display: flex; align-items: center; justify-content: space-between;">
                    <div class="container">
                        <form action="" method="POST" name="search-year-month">
                            <label for="yearSelect">Select Year & Month:</label>
                            <ul class="formm">
                                <li>
                                    <select class="form-control" id="year" name="year">
                                        <?php
                                        // Get the distinct years from the database
                                        $yearQuery = "SELECT YEAR(SignInTime) AS year
                                FROM `mock_data`
                                GROUP BY YEAR(SignInTime)";

                                        $yearResult = mysqli_query($conn, $yearQuery);
                                        while ($row = mysqli_fetch_assoc($yearResult)) {
                                            $selected = (isset($_POST['year']) && $_POST['year'] == $row['year']) ? 'selected' : '';
                                            echo "<option value='" . $row['year'] . "' $selected>" . $row['year'] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </li>
                                <li>
                                    <select class="form-control" id="month" name="month">
                                        <?php
                                        $selectedMonth = isset($_POST['month']) ? mysqli_real_escape_string($conn, $_POST['month']) : date('n');
                                        // Generate options for all 12 months
                                        for ($month = 1; $month <= 12; $month++) {
                                            $selected = ($selectedMonth == $month) ? 'selected' : '';
                                            $monthName = date('F', mktime(0, 0, 0, $month, 1));
                                            echo "<option value='$month' $selected>$monthName</option>";
                                        }
                                        ?>
                                    </select>
                                </li>

                                <li>
                                    <input type="submit" name="search-year-month" class="btn btn-primary" value="Select">
                                </li>
                            </ul>
                        </form>
                    </div>
                </div>
                <script>
                    // Function to generate and download PDF
                    function generatePDF() {
                        // Create a new jsPDF instance
                        window.jsPDF = window.jspdf.jsPDF;
                        const doc = new jsPDF('landscape');

                        // Add company logo and details
                        const title = 'Reporting: Weekly Graph';
                        const companyLogo = '../images/Logo.jpeg';
                        const companyName = 'Enterprise  : <?php echo $Enterprise ?>';
                        const email = 'Email  : <?php echo $email ?>';
                        const address = 'Address  : <?php echo $address ?>';
                        const date = 'Date  : <?php echo $currentDatetime ?>';

                        doc.setFontSize(16);
        doc.addImage(companyLogo, 'PNG', 10, 10, 140, 40);
        doc.text(title, 200, 23);
        doc.setFontSize(12);
        doc.text(companyName, 200, 32);
        doc.text(email, 200, 37);
        doc.text(address, 200, 42);
        doc.text(date, 200, 48);


                        // Get the canvas element
                        const canvas = document.getElementById('weeklyChart');

                        // Convert canvas to base64 image data
                        const imageData = canvas.toDataURL('image/png');

                        // Add chart image to the PDF
                        doc.addImage(imageData, 'PNG', 23, 65, 260, 140);

                        // Save the PDF
                        // doc.save('chart.pdf');

                        // Get the PDF data as a Blob object
                        const pdfBlob = doc.output('blob');

                        // Create a temporary URL for the PDF blob
                        const url = URL.createObjectURL(pdfBlob);

                        // Open the PDF in a new browser tab
                        window.open(url, '_blank');
                    }

                    function previewPDF() {
                        // Display a loading message while generating the PDF
                        document.getElementById('pdfPreview').innerHTML = '<p>Loading...</p>';

                        // Generate the PDF
                        generatePDF();

                        // Create iframe
                        const iframe = document.createElement('iframe');
                        iframe.width = '100%';
                        iframe.height = '600px';
                        iframe.src = ''; // Add the data URL or file path if needed

                        // Replace the loading message with the iframe
                        document.getElementById('pdfPreview').innerHTML = '';
                        document.getElementById('pdfPreview').appendChild(iframe);

                        // Access the iframe content document
                        const iframeDocument = iframe.contentWindow.document;

                        // Add styles to hide print and download options
                        const style = iframeDocument.createElement('style');
                        style.textContent = `
                                    @media print {
                                        body::before {
                                            content: "Printing is disabled.";
                                            display: block;
                                            position: fixed;
                                            top: 0;
                                            left: 0;
                                            width: 100%;
                                            height: 100%;
                                            background-color: white;
                                            color: black;
                                            text-align: center;
                                            font-size: 24px;
                                        }
                                    }
                                    body::after {
                                        content: "Downloading is disabled.";
                                        display: block;
                                        position: fixed;
                                        top: 0;
                                        left: 0;
                                        width: 100%;
                                        height: 100%;
                                        background-color: white;
                                        color: black;
                                        text-align: center;
                                        font-size: 24px;
                                    }
                                `;
                        iframeDocument.head.appendChild(style);
                    }


                    function downloadPDF() {
                        generatePDF(); // Call the existing generatePDF function
                    }
                </script>
                <div id="downloadPdfButton" style="display: none;">
                            <button onclick="generatePDF()" class="btn btn-success ng-binding">Download PDF</button>
                 </div>

                <div id="graph">
                    <?php
                    if (isset($_POST['search-year-month'])) {
                        $selectedYear = mysqli_real_escape_string($conn, $_POST['year']);
                        $selectedMonth = isset($_POST['month']) ? mysqli_real_escape_string($conn, $_POST['month']) : date('n');

                        // Check if data is available for the selected year and month
                        $checkDataQuery = "SELECT COUNT(*) AS dataCount FROM `mock_data` WHERE YEAR(SignInTime) = $selectedYear AND MONTH(SignInTime) = $selectedMonth";
                        $dataCountResult = mysqli_query($conn, $checkDataQuery);
                        $dataCount = mysqli_fetch_assoc($dataCountResult)['dataCount'];

                        if ($dataCount > 0) {
                            // Data is available, display the graph and "Select View" dropdown
                            echo '<div class="d-flex justify-content-center">
                        <h6 class="m-0 font-weight-bold text-secondary">Weekly Visitors count</h6>
                    </div>
                    <div id="graph" style="display: block;"> <!-- Added id="graph" and set display to block -->
                        <div class="chart-container" style="position: relative; height: 12vh; width: 70vw">
                            <canvas id="weeklyChart" name="weeklyChart" style="width: inherit"></canvas>
                            <!-- Display the "Download PDF" button for the graph -->
                            <button class="btn btn-success ng-binding" id="downloadbtn" name="downloadbtn" onclick="generatePDF()">Download PDF</button>
                        </div>   
                    </div>';

                            // Retrieve and populate graph data
                            $graphDataQuery = "SELECT WEEK(SignInTime) AS week, COUNT(*) AS count
                     FROM `mock_data`
                     WHERE YEAR(SignInTime) = $selectedYear AND MONTH(SignInTime) = $selectedMonth
                     GROUP BY week";

                            $graphDataResult = mysqli_query($conn, $graphDataQuery);

                            $visitWeek = [];
                            $visitCount = [];

                            while ($row = mysqli_fetch_assoc($graphDataResult)) {
                                // Format week numbers as "Week 01", "Week 02", etc.
                                $weekNumber = sprintf("Week %02d", $row['week']);
                                $visitWeek[] = $weekNumber;
                                $visitCount[] = $row['count'];
                            }

                            echo '<script>
                    var x_Values = ' . json_encode($visitWeek) . ';
                    var y_Values = ' . json_encode($visitCount) . ';
                    new Chart("weeklyChart", {
                        type: "line",
                        data: {
                            labels: x_Values,
                            datasets: [{
                                label: "Visitors Count",
                                fill: true,
                                borderWidth: 1,
                                backgroundColor: "rgba(0, 0, 255, 0.5)",
                                borderColor: "rgba(0, 0, 255, 1)",
                                data: y_Values,
                            }],
                        },
                        options: {
                            legend: { display: false },
                            scales: {
                                yAxes: [{ ticks: { min: 0, max: 50 } }]
                            }
                        }
                    });
                </script>

   
                ';
                        } else {
                            // No data available for the selected date
                            echo '<div class="d-flex justify-content-center">No data available for the selected date. Available months for the selected year:</div>';
                        }
                    }
                    ?>
                </div>



                <div id="table" style="display: none;">
                    <div class="container-fluid ng-scope">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                    </div>
                                    <div class="panel-body">


                                        <table class="table table-striped ng-scope ng-table table-hover" id="tableV" name="tableV" style="margin-left: 50;">
                                            <tr style="background-color: #3ab5e6; color: white;">
                                                <th style="width:20%">Weeks</th>
                                                <th style="width:20%">Number of visitors</th>
                                                <th style="width:20%">Business Visit</th>
                                                <th style="width:20%">Personal Visit</th>
                                            </tr>

                                            <?php

                                            $totalBusiness = 0;
                                            $totalPersonal = 0;
                                            $totalVisits = 0;

                                            // Check if data is available for the selected year and month
                                            if (isset($_POST['search-year-month'])) {
                                                $checkDataQuery = "SELECT WEEK(SignInTime) AS week, COUNT(*) AS count,
                                                   
                                                    SUM(CASE WHEN reason_for_visiting = 'business' THEN 1 ELSE 0 END) AS businessVisit,
                                                    SUM(CASE WHEN reason_for_visiting = 'personal' THEN 1 ELSE 0 END) AS personalVisit
                                                    FROM `mock_data`
                                                    WHERE YEAR(SignInTime) = $selectedYear AND MONTH(SignInTime) = $selectedMonth
                                                    GROUP BY week";
                                                $graphDataResult = mysqli_query($conn, $checkDataQuery);

                                                while ($row = mysqli_fetch_assoc($graphDataResult)) {
                                                    $weekNumber = sprintf("Week %02d", $row['week']);
                                                    echo '<tr>';
                                                    echo '<td>' . $weekNumber . '</td>';
                                                    echo '<td>' . $row['count'] . '</td>';

                                                    echo '<td>' . $row['businessVisit'] . '</td>';
                                                    echo '<td>' . $row['personalVisit'] . '</td>';

                                                    $totalBusiness += $row['businessVisit'];
                                                    $totalPersonal += $row['personalVisit'];
                                                    $totalVisits += $row['count'];
                                                }
                                                echo '</tr>';
                                                echo "<td><strong>TOTAL</strong></td>";
                                                echo "<td><strong>" . str_pad($totalVisits, 2, '0', STR_PAD_LEFT) . "</strong></td>";
                                                echo "<td><strong>" . str_pad($totalBusiness, 2, '0', STR_PAD_LEFT) . "</strong></td>";
                                                echo "<td><strong>" . str_pad($totalPersonal, 2, '0', STR_PAD_LEFT) . "</strong></td>";
                                                echo "</tr>";
                                            }


                                            ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>




                    <div class="panel-footer">
                        <div type="'excel'" class="ng-isolate-scope">
                            <a class="ng-excel"><span></span></a>
                        </div>
                        <a href="../php/downloadTableWeekly.php?week=<?php echo $weekNumber; ?>&year=<?php echo $selectedYear; ?>&month=<?php echo $selectedMonth; ?>" class="btn btn-success ng-binding" id="downloadbtn" name="downloadbtn" target="_blank">View PDF Table</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>

    <script>
        function toggleView() {
            console.log("Toggle Select")
            var viewSelect = document.getElementById('view');
            var graphDiv = document.getElementById('graph');
            var tableDiv = document.getElementById('table');
            var downloadPdfButton = document.getElementById("downloadbtn");


            if (viewSelect.value === 'graph') {
                graphDiv.style.display = 'block';
                tableDiv.style.display = 'none';
                downloadPdfButton.style.display = 'block';
                console.log('Graph');
            } else if (viewSelect.value === 'table') {
                graphDiv.style.display = 'none';
                tableDiv.style.display = 'block';
                downloadPdfButton.style.display = 'nono';
                console.log('Table');
            }
        }
        // Call toggleView initially to set the initial view based on the selected option
        toggleView();
    </script>
    </div>
    </div>


    <div class="footer" style="position: fixed; bottom: 0; padding: 1px; margin: auto; ">
        <div class="container-fluid">
            <footer class="py-3 my-4">
                <div class="row">
                    <div class="col">
                        <p class="copyright d-flex justify-content-center"> &copy Datamaster, 2023 - Privacy Policy</p>
                    </div>
                </div>
        </div>
        </footer>
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