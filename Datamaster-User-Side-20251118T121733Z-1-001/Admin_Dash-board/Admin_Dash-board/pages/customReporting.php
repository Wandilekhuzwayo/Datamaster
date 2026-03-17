<?php
// Start session and check authentication
include("../php/auth_session.php");

// Database connection
include('../php/connection.php');
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<title>Datamaster Weekly Reporting</title>

<link rel="stylesheet" href="../css/bootstrap.min.css">
<link rel="stylesheet" href="../css/custom.css">
<link rel="stylesheet" href="../css/styles.css">
<link rel="stylesheet" href="../css/styling.css">
<link rel="stylesheet" href="../css/admi.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" />
<link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
</head>

<body>
<div class="wrapper" style="min-height:50em;">
<div class="body-overlay"></div>

<!-- Sidebar -->
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
<!-- Content -->
<div id="content">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
<div class="container-fluid">
<i class="bi bi-list" id="sidebarCollapse"></i>
<div class="name-container">
<input type="text" value="<?php echo $_SESSION['firstname']; ?>" readonly>
</div>

<ul class="nav navbar-nav ml-auto">

<!-- Notifications -->
<li class="dropdown nav-item">
<?php
$notif_sql = "SELECT visitorName, EvacuatingTime 
FROM visitorstbl
WHERE EvacuatingTime IS NOT NULL
ORDER BY EvacuatingTime DESC LIMIT 5";

$notif_res = mysqli_query($conn, $notif_sql);
?>

<a href="#" class="nav-link" data-toggle="dropdown">
<span class="material-icons">notifications</span>
<span class="notification"><?php echo mysqli_num_rows($notif_res); ?></span>
</a>

<ul class="dropdown-menu">
<?php
if(mysqli_num_rows($notif_res) > 0){
while($item = mysqli_fetch_assoc($notif_res)){
echo "<li>".htmlspecialchars($item['visitorName'])."</li>";
echo "<li>Signed out at ".htmlspecialchars($item['EvacuatingTime'])."</li>";
}
}else{
echo "<li>No active visitors</li>";
}
?>
</ul>
</li>

</ul>
</div>
</nav>

<!-- Report Form -->
<div class="card-body mb-3">
<form method="post">
<div class="row">

<div class="col-md-4">
<label>From Date</label>
<input type="date" name="fromDate" class="form-control"
value="<?php echo $_POST['fromDate'] ?? ''; ?>" required>
</div>

<div class="col-md-4">
<label>To Date</label>
<input type="date" name="toDate" class="form-control"
value="<?php echo $_POST['toDate'] ?? ''; ?>" required>
</div>

<div class="col-md-4 d-flex align-items-end">
<input type="submit" 
       class="btn btn-primary w-100" 
       style="height:30px; font-weight:600;" 
       value="Generate Report">
</div>

</div>
</form>
</div>

<!-- Report Table -->
<div class="container-fluid">
<table class="table table-striped table-hover">

<thead style="background:#3e3e3e;color:white;">
<tr>
<th>Date</th>
<th>Access Granted</th>
<th>Visitor Name</th>
</tr>
</thead>

<tbody>
<?php
if(isset($_POST['fromDate'], $_POST['toDate'])){

$fromDate = mysqli_real_escape_string($conn, $_POST['fromDate']);
$toDate = mysqli_real_escape_string($conn, $_POST['toDate']);

$_SESSION['fromDate'] = $fromDate;
$_SESSION['toDate'] = $toDate;

/* FIXED QUERY */
$query = "
SELECT 
DATE(signInTime) AS visit_date,
COUNT(*) AS visit_count,
visitorName
FROM visitorstbl
WHERE DATE(signInTime) BETWEEN '$fromDate' AND '$toDate'
GROUP BY visitorName, visit_date
ORDER BY visit_date
";

$result = mysqli_query($conn, $query);

if($result && mysqli_num_rows($result) > 0){
while($row = mysqli_fetch_assoc($result)){
echo "<tr>";
echo "<td>".htmlspecialchars($row['visit_date'])."</td>";
echo "<td>".htmlspecialchars($row['visit_count'])."</td>";
echo "<td>".htmlspecialchars($row['visitorName'])."</td>";
echo "</tr>";
}
}else{
echo "<tr><td colspan='3'>No visits found</td></tr>";
}
}
?>
</tbody>
</table>

<a href="../pages/custom_Download.php" class="btn btn-success">Download PDF</a>

</div>

</div>
</div>

<script>
$('#sidebarCollapse').click(function(){
$('#sidebar').toggleClass('active');
$('#content').toggleClass('active');
});
</script>

</body>
</html>