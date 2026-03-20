<?php 
// Call Auth_session
include("../php/auth_session.php");

// Get Connection
include('../php/connection.php');

// SEARCH & DEFAULT QUERY
if (isset($_POST['search-info'])) {
    $searchValue = $_POST['search'];

    $query = "
        SELECT *
        FROM visitorstbl
        WHERE CONCAT(
            name,
            surname,
            number,
            email,
            visitorName,
            signInTime,
            EvacuatingTime
        ) LIKE '%$searchValue%'
        ORDER BY signInTime DESC
    ";
} else {
    // HISTORY = visitors who signed out
    $query = "
        SELECT *
        FROM visitorstbl
        WHERE EvacuatingTime IS NOT NULL
        ORDER BY signInTime DESC
    ";
}

$result = mysqli_query($conn, $query);
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Datamaster History</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="../css/bootstrap.min.css">
<link rel="stylesheet" href="../css/custom.css">
<link rel="stylesheet" href="../css/styles.css">
<link rel="stylesheet" href="../css/styling.css">
<link rel="stylesheet" href="../css/admi.css">
<link rel="stylesheet" href="../fontawesome-free/css/all.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
</head>

<body>

<div class="wrapper" style="min-height:50em;">
<div class="body-overlay"></div>

<!-- SIDEBAR -->
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

<!-- CONTENT -->
<div id="content">

<!-- TOP NAV -->
<div class="top-navbar">
<nav class="navbar navbar-expand-lg">
<div class="container-fluid">

<i class="bi bi-list material-icons" id="sidebarCollapse"></i>

<div class="name-container">
<input type="text" placeholder="<?php echo $_SESSION['firstname']; ?>">
</div>

<ul class="nav navbar-nav ml-auto">

<!-- NOTIFICATIONS -->
<li class="dropdown nav-item">
<?php
$notifSQL = "
    SELECT *
    FROM visitorstbl
    WHERE EvacuatingTime IS NOT NULL
    ORDER BY EvacuatingTime DESC
";
$notifRes = mysqli_query($conn, $notifSQL);
?>
<a href="#" class="nav-link" data-toggle="dropdown" id="notifications">
<span class="material-icons">notifications</span>
<span class="notification"><?php echo mysqli_num_rows($notifRes); ?></span>
</a>

<ul class="dropdown-menu">
<?php while ($n = mysqli_fetch_assoc($notifRes)) { ?>
<li><?php echo $n['visitorName']; ?></li>
<li>Signed out at <?php echo $n['EvacuatingTime']; ?></li>
<hr>
<?php } ?>
</ul>
</li>

</ul>
</div>
</nav>
</div>

<!-- MAIN CONTENT -->
<div class="main-content">
<div class="d-sm-flex justify-content-between mb-3">
<h1 class="h3">History</h1>

<form method="POST">
<input type="text" class="control-search" name="search" placeholder="Search Here">
<button type="submit" class="btn btn-primary" name="search-info">
<i class="fa fa-search"></i>
</button>
</form>
</div>

<div class="container-fluid">
<table class="table table-striped table-hover">
<thead style="background:#3e3e3e;color:white;">
<tr>
<th>No</th>
<th>Email</th>
<th>Name</th>
<th>Surname</th>
<th>Contact</th>
<th>Time In</th>
<th>Time Out</th>
</tr>
</thead>

<tbody>
<?php if (mysqli_num_rows($result) > 0) {
$no = 1;
while ($row = mysqli_fetch_assoc($result)) { ?>
<tr>
<td><?php echo $no++; ?></td>
<td><?php echo $row['email']; ?></td>
<td><?php echo ucwords($row['name']); ?></td>
<td><?php echo ucwords($row['surname']); ?></td>
<td><?php echo $row['phone_number']; ?></td>
<td><?php echo $row['signInTime']; ?></td>
<td><?php echo $row['EvacuatingTime']; ?></td>
</tr>
<?php } } else { ?>
<tr><td colspan="7">No history found</td></tr>
<?php } ?>
</tbody>
</table>

<div class="panel-footer">
<a href="../php/downloadHistory.php" class="btn btn-success">Download PDF</a>
<a href="../php/downloadHistoryXLS.php" class="btn btn-success">Download XLS</a>
</div>

</div>
</div>

</div>
</div>

<script src="../js/jquery-3.3.1.min.js"></script>
<script src="../js/bootstrap.min.js"></script>

<script>
$('#sidebarCollapse').on('click', function () {
$('#sidebar').toggleClass('active');
$('#content').toggleClass('active');
});
</script>

</body>
</html>
