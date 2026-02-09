<?php 
  //Call Auth_session on Home
  include("../php/auth_session.php");

  //Get Connection
  include ('../php/connection.php');

  //Retrive Daily ChartData
  include('../DataFetchers/daily_Chart_Retriv.php');



  function filterTable($query) {
    //Get Connection
    include ('../php/connection.php');

    //Resulting
    $result = mysqli_query($conn, $query);

    return $result;
  }
  //Procesing Selection
  //include('../php/process_query.php');
  $emailAddres = $_SESSION["firstname"];
  $result = mysqli_query($conn, "SELECT  firstname, surname, email, companyname, employeeNo, department FROM admin_table WHERE email ='$emailAddres'");

  if($result) {
 
    //Get a data from user_table row
    while($row = mysqli_fetch_assoc($result)) {
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


// Query the database to fetch data
// Database connection
$host = 'localhost';
$dbname = 'datamaster';
$username = 'root';
$password = 'LautaroWarh7';

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if Year, Month, Week are set or set default values
$selectedYear = isset($_POST['year']) ? $_POST['year'] : date('Y');
$selectedMonth = isset($_POST['month']) ? $_POST['month'] : date('n');
$selectedWeek = isset($_POST['week']) ? $_POST['week'] : '';

// Query to populate the table based on selected year, month, and week
$tableQuery = "SELECT 
    DAYNAME(timein) AS Day_of_Week,
    DATE(timein) AS Date,
    SUM(CASE WHEN reason_visit = 'business' THEN 1 ELSE 0 END) AS Business_Visits,
    SUM(CASE WHEN reason_visit = 'personal' THEN 1 ELSE 0 END) AS Personal_Visits
    FROM questions_table
    WHERE 1=1";

if ($selectedYear !== '') {
    $tableQuery .= " AND YEAR(timein) = $selectedYear";
}

if ($selectedMonth !== '') {
    $tableQuery .= " AND MONTH(timein) = $selectedMonth";
}

if ($selectedWeek !== '') {
    $tableQuery .= " AND WEEK(timein, 3) = $selectedWeek";
}

$tableQuery .= " GROUP BY Day_of_Week, Date ORDER BY Date";

$result = mysqli_query($conn, $tableQuery);

?>




<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <title>Datamaster Daily Reporting
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
        <link href="https://fonts.googleapis.com/css2?family=Material+Icons"
      rel="stylesheet">
      <link rel="stylesheet" href="../css/admi.css">
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
                
    <style>
                .goToNewPageformm
                {
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
                    
                    <button class="d-inline-block d-lg-none ml-auto more-button" type="button" data-toggle="collapse"
                    data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="bi bi-list material-icons"></span>
                    </button>

                    <div class="collapse navbar-collapse d-lg-block d-xl-block d-sm-none d-md-none d-none" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto">   
                            <li class="dropdown nav-item ">
                                <!--Query for fetch from the table-->
                                <?php $sql = "SELECT * FROM questions_table  WHERE status='0' AND timeout > 0 ORDER BY id DESC";
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
                                        <a href="myprofile.php"><i class="bi bi-person material-icons px-2" ></i><span> Profile</span></a>
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

                <style>
    /* Style to make the date input smaller */
    input[type="date"] {
        width: 130px; /* Set the width of the input */
        padding: 5px; /* Adjust padding to change the input field size */
        font-size: 15px; /* Set the font size */
    }
</style>


<div class="d-sm-flex align-items-center justify-content-between mb-3" style="display: flex; align-items: center; justify-content: space-between;">
    <form action="" method="POST" name="search-date" id="search-date">
        <ul class="formm">
        <label for="selectedDate">Select Date:</label>
        <li>
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selectedDate'])) {
            $selectedDate = $_POST['selectedDate'];
            echo '<input type="date" name="selectedDate" id="selectedDate" onchange="this.form.submit()" value="' . $selectedDate . '">';
        } else {
            echo '<input type="date" name="selectedDate" id="selectedDate" onchange="this.form.submit()">';
        }
        ?>
    </li>
            <?php
        if (isset($_POST['selectedDate'])) {
            $selectedDate = $_POST['selectedDate'];
            $dateLabel = date("l, F j, Y", strtotime($selectedDate));

            $visitorCountQuery = "SELECT HOUR(timein) AS hour, COUNT(*) AS visitor_count
                FROM questions_table
                WHERE DATE(timein) = '$selectedDate'
                GROUP BY HOUR(timein)";

            $visitorCountResult = mysqli_query($conn, $visitorCountQuery);
            $visitorData = [];

            while ($row = mysqli_fetch_assoc($visitorCountResult)) {
                $visitorData[$row['hour']] = $row['visitor_count'];
            }

            $visitTime = [];
            $visitCount = [];
            $hours = range(0, 23); // 24 hours in a day

            foreach ($hours as $hour) {
                $visitTime[] = sprintf("%02d:00", $hour);
                $visitCount[] = $visitorData[$hour] ?? 0;
            }
        }
        $visitorCount = isset($visitCount) ? array_sum($visitCount) : 0;
        ?>
        </ul>
    </form>
</div>

<div id="graph" style="display: block;">
<div class="d-flex justify-content-center">
        <?php
        $dateLabel = isset($_POST['selectedDate']) ? date("l, F j, Y", strtotime($_POST['selectedDate'])) : 'No Date Selected';
        $visitorCount = isset($visitCount) ? array_sum($visitCount) : 0;
        ?>
        <h6 class="m-0 font-weight-bold text-secondary" style="display: <?php echo (isset($_POST['selectedDate']) && $visitorCount > 0) ? 'block' : 'none'; ?>">
            Daily Report Graph (<?php echo $dateLabel; ?>)
        </h6>
        <h6 class="m-0 font-weight-bold text-secondary" style="display: <?php echo (isset($_POST['selectedDate']) && $visitorCount == 0) ? 'block' : 'none'; ?>">
            No visitors for <?php echo $dateLabel; ?>
        </h6>
    </div>

    <div class="d-flex justify-content-right">
        <div class="chart-container" style="position: relative; height:12vh; width:70vw">
            <canvas id="dailyChart" name="dailyChart" style="width:inherit"></canvas>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
            <script>
              var x_Values = <?php echo json_encode($visitTime); ?>;
var y_Values = <?php echo json_encode($visitCount); ?>;
new Chart("dailyChart", {
    type: "line",
    data: {
        labels: x_Values,
        datasets: [{
            label: 'Number of Visitors',
            fill: true,
            backgroundColor: "rgba(0, 0, 255, 0.5)",
            borderColor: "rgba(0,0,255,0.1)",
            data: y_Values,
        }],
        responsive: true,
        maintainAspectRatio: false,
    },
    options: {
        legend: { display: false },
        scales: {
            yAxes: [{
                scaleLabel: {
                    display: true,
                    labelString: 'Number of Visitors'
                },
                ticks: {
                    min: 0,
                    max: 5,
                    stepSize: 1 // Increment by 1
                }
            }],
            xAxes: [{
                scaleLabel: {
                    display: true,
                    labelString: 'Time'
                },
                ticks: {
                    minRotation: 80, // Rotates the x-axis labels by 80 degrees
                    maxRotation: 90,
                    autoSkip: false // Disable automatic skipping of labels
                }
            }]
        }
    }
});

                                                function generatePDF() {
                                 <?php if (isset($_POST['selectedDate']) && $visitorCount > 0) { ?>
                                    // Create a new jsPDF instance
                                    window.jsPDF = window.jspdf.jsPDF;
                                    const doc = new jsPDF('landscape');

                                    // Add company logo and details
                                    const title = 'Reporting: Daily Graph';
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

                                    const canvas = document.getElementById('dailyChart');
                                    const imageData = canvas.toDataURL('image/png');
                                    doc.addImage(imageData, 'PNG', 23, 65, 260, 140);

                                   

                                    const pdfBlob = doc.output('blob');
                                    const url = URL.createObjectURL(pdfBlob);
                                    window.open(url, '_blank');
                                    <?php } ?>

                                }

                                        </script>
                 <button onclick="generatePDF()" class="btn btn-success ng-binding" style="display: <?php echo (isset($_POST['selectedDate']) && $visitorCount > 0) ? 'block' : 'none'; ?>">Download PDF</button>
            </script>
        </div>
    </div>
</div>



<?php
if (isset($_POST['selectedDate'])) {
    $selectedDate = $_POST['selectedDate'];

    // Parse the selected date
    $dateComponents = getdate(strtotime($selectedDate));
    $selectedYear = $dateComponents['year'];
    $selectedMonth = $dateComponents['mon'];
    $selectedDay = $dateComponents['mday'];

    // Fetch visitor details for the selected date
    $query = "SELECT timein, reason_visit FROM questions_table 
              WHERE YEAR(timein) = $selectedYear 
              AND MONTH(timein) = $selectedMonth 
              AND DAY(timein) = $selectedDay";

    $result = mysqli_query($conn, $query);

    // Initialize visitor count for business and personal visits
    $businessVisitors = 0;
    $personalVisitors = 0;

    // Loop through the query results to count visitors
    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['reason_visit'] === 'business') {
            $businessVisitors++;
        } elseif ($row['reason_visit'] === 'personal') {
            $personalVisitors++;
        }
    }
}
?>
<div id="table" style="display: block;">
    <div class="container-fluid ng-scope">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="d-flex justify-content-center">
                        <?php
                        $dateLabel = isset($_POST['selectedDate']) ? date("l, F j, Y", strtotime($_POST['selectedDate'])) : 'No Date Selected';
                        $visitorCount = isset($visitCount) ? array_sum($visitCount) : 0;
                        ?>
                        <h6 class="m-0 font-weight-bold text-secondary">Daily Report Table (<?php echo $dateLabel; ?>) <?php echo $visitorCount > 0 ? '' : 'No Visitors'; ?></h6>
                    </div>
                    <div class="panel-heading"></div>
                    <div class="panel-body">
                        <table class="table table-striped ng-scope ng-table table-hover" id="tableV" name="tableV" style="margin-left: 50;">
                            <tr style="background-color: #3ab5e6; color: white;">
                                <th style="width:20%">HOURS</th>
                                <th style="width:20%">DATE</th>
                                <th style="width:20%">BUSINESS APPOINTMENT</th>
                                <th style="width:20%">PERSONAL VISIT</th>
                            </tr>
                            <?php
                                $timeRanges = [
                                    "00:00 - 06:00",
                                    "06:01 - 11:59",
                                    "12:00 - 18:00",
                                    "18:01 - 23:59"
                                ];

                                $totalBusinessVisits = 0;
                                $totalPersonalVisits = 0;
                                $totalVisitors = 0;
                                $selectedDate = '';

                                    if (isset($_GET['date']) && $_GET['date'] !== '') {
                                        $selectedDate = $_GET['date'];
                                    }


                                foreach ($timeRanges as $timeRange) {

    $query = "SELECT reason_visit FROM questions_table 
              WHERE DATE_FORMAT(timein, '%H:%i') >= SUBSTRING_INDEX('$timeRange', ' - ', 1)
              AND DATE_FORMAT(timein, '%H:%i') <= SUBSTRING_INDEX('$timeRange', ' - ', -1)";

    if ($selectedDate !== '') {
        $query .= " AND DATE(timein) = '$selectedDate'";
    }

if ($selectedDate !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate)) {
    die("Invalid date format");
}

    $result = mysqli_query($conn, $query);

    $businessVisitors = 0;
    $personalVisitors = 0;

    while ($row = mysqli_fetch_assoc($result)) {
        if ($row['reason_visit'] === 'business') {
            $businessVisitors++;
            $totalBusinessVisits++;
        } elseif ($row['reason_visit'] === 'personal') {
            $personalVisitors++;
            $totalPersonalVisits++;
        }
    }

    $totalVisitors += $businessVisitors + $personalVisitors;

    echo "<tr>";
    echo "<td>$timeRange</td>";
    echo "<td>" . ($selectedDate !== '' ? $selectedDate : 'All Dates') . "</td>";
    echo "<td>$businessVisitors</td>";
    echo "<td>$personalVisitors</td>";
    echo "</tr>";
}

                                echo "<tr>";
                                echo "<td>Total</td>";
                                echo "<td></td>";
                                echo "<td><strong>$totalBusinessVisits</strong></td>";
                                echo "<td><strong>$totalPersonalVisits</strong></td>";
                                echo "</tr>";

                                // Display the total number of visitors.
                                echo "<tr>";
                                echo "<td><strong >Total Number Of Visitors  $totalVisitors</strong></td>";
                                echo "<td></td>";
                                echo "<td colspan='2'><strong></strong></td>";
                                echo "</tr>";
                            ?>
                        </table>
                    </div>
                    <div class="panel-footer">
                        <div type="'excel'" class="ng-isolate-scope">
                            <a class="ng-excel"><span></span></a>
                        </div>
                        <!-- <a href="../php/downloadTableDaily.php" class="btn btn-success ng-binding" id="downloadbtn" name="downloadbtn">Download PDF</a> -->
                        <a href="../php/downloadTableDaily.php?date=<?php echo $selectedDate; ?>" class="btn btn-success ng-binding" id="downloadbtn" name="downloadbtn" target="_blank">View PDF Table</a>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



     
     <script>
            function toggleView() {
                var viewSelect = document.getElementById('view');
                var graphDiv = document.getElementById('graph');
                var tableDiv = document.getElementById('table');

                if (viewSelect.value === 'graph') {
                    graphDiv.style.display = 'block';
                    tableDiv.style.display = 'none';
                    downloadPdfButton.style.display = 'block';
                } else if (viewSelect.value === 'table') {
                    graphDiv.style.display = 'none';
                    tableDiv.style.display = 'block';
                    downloadPdfButton.style.display = 'none';
                }
            }

            // Call toggleView initially to set the initial view based on the selected option
            toggleView();

        </script>
     <!-- <div class="footer" style="position: fixed; bottom: 0; padding: 1px; margin: auto; ">
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
            </div> -->

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