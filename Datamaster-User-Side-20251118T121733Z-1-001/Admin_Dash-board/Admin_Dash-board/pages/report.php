<?php
include("../php/auth_session.php");
include("../php/connection.php");

/* ================= FILTERS ================= */
$type = $_POST['report_type'] ?? "daily";
$view = $_POST['view_type'] ?? "table";

/* ================= DATE CONDITIONS ================= */
if($type == "daily"){
    $dateCondition = "DATE(signInTime) = CURDATE()";
}
elseif($type == "monthly"){
    $dateCondition = "MONTH(signInTime) = MONTH(CURDATE()) 
                      AND YEAR(signInTime) = YEAR(CURDATE())";
}
elseif($type == "yearly"){
    $dateCondition = "YEAR(signInTime) = YEAR(CURDATE())";
}
else{
    $dateCondition = "1";
}

/* ================= SEARCH ================= */
$searchCondition = "";

if(isset($_POST['search-info'])){
    $search = mysqli_real_escape_string($conn, $_POST['search']);
    $searchCondition = "AND CONCAT(name,surname,phone_number,email,visitorName) 
                        LIKE '%$search%'";
}

/* ================= MAIN QUERY ================= */
$query = "SELECT * FROM visitorstbl 
          WHERE $dateCondition $searchCondition
          ORDER BY signInTime DESC";

$result = mysqli_query($conn, $query);


/* ================= GRAPH DATA ================= */
$graphQuery = mysqli_query($conn,"
SELECT DATE(signInTime) as day, COUNT(*) as total
FROM visitorstbl
WHERE $dateCondition
GROUP BY DATE(signInTime)
ORDER BY day
");

$days = [];
$totals = [];

while($g = mysqli_fetch_assoc($graphQuery)){
    $days[] = $g['day'];
    $totals[] = $g['total'];
}

/* ================= EXPORT EXCEL ================= */
if(isset($_POST['export_excel'])){
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=visitor_report.xls");

    echo "ID\tName\tSurname\tPhone\tEmail\tVisitor\tSignIn\tSignOut\n";

    $excel = mysqli_query($conn, $query);
    while($row = mysqli_fetch_assoc($excel)){
        echo $row['id']."\t".
             $row['name']."\t".
             $row['surname']."\t".
             $row['phone_number']."\t".
             $row['email']."\t".
             $row['visitorName']."\t".
             $row['signInTime']."\t".
             $row['EvacuatingTime']."\n";
    }
    exit;
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


<!-- ================= CONTENT ================= -->
<div id="content">

<!-- NAVBAR -->
<div class="top-navbar">
<nav class="navbar navbar-expand-lg">

<div class="container-fluid">
<i class="bi bi-list material-icons d-xl-block d-lg-block d-md-none d-none" id="sidebarCollapse"></i>

<div class="name-container">
<input type="text" placeholder="<?php echo $_SESSION['firstname']; ?>">
</div>

<!-- ================= NOTIFICATIONS ================= -->
<?php
$notifQuery = "SELECT visitorName, EvacuatingTime 
               FROM visitorstbl
               WHERE EvacuatingTime IS NOT NULL
               ORDER BY EvacuatingTime DESC
               LIMIT 5";

$notifResult = mysqli_query($conn, $notifQuery);
?>

<div class="dropdown">
<a class="nav-link dropdown-toggle" data-toggle="dropdown">
Notifications
</a>

<ul class="dropdown-menu">
<?php
if(mysqli_num_rows($notifResult) > 0){
while($row = mysqli_fetch_assoc($notifResult)){
echo "<li class='dropdown-item'>";
echo "<b>".$row['visitorName']."</b><br>";
echo "Signed out at ".$row['EvacuatingTime'];
echo "</li>";
}
}else{
echo "<li class='dropdown-item'>No notifications</li>";
}
?>
</ul>
</div>

</div>
</nav>
</div>


<!-- ================= MAIN ================= -->
<div class="main-content p-4">

<h3>Visitor Reporting</h3>

<!-- FILTER -->
<form method="POST" class="row mb-3">

<div class="col-md-3">
<select name="report_type" class="form-control">
<option value="daily">Daily</option>
<option value="monthly">Monthly</option>
<option value="yearly">Yearly</option>
</select>
</div>

<div class="col-md-3">
<select name="view_type" class="form-control">
<option value="table">Table</option>
<option value="graph">Graph</option>
</select>
</div>

<div class="col-md-3">
<button class="btn btn-primary">Generate</button>
</div>

</form>


<!-- SEARCH -->
<form method="POST" class="mb-3">
<div class="input-group">
<input type="text" name="search" class="form-control" placeholder="Search visitors...">
<button name="search-info" class="btn btn-secondary">Search</button>
</div>
</form>


<!-- EXPORT -->
<form method="POST" class="mb-3">
<button name="export_excel" class="btn btn-success">Export Excel</button>
<button onclick="window.print()" class="btn btn-danger">Export PDF</button>
</form>


<!-- TABLE -->
<?php if($view == "table"){ ?>
<div class="table-responsive">
<table class="table table-bordered table-striped">

<thead class="table-dark">
<tr>
<th>ID</th>
<th>Name</th>
<th>Surname</th>
<th>Phone</th>
<th>Email</th>
<th>Visitor</th>
<th>Sign In</th>
<th>Status</th>
</tr>
</thead>

<tbody>
<?php
if(mysqli_num_rows($result) > 0){
while($row = mysqli_fetch_assoc($result)){
?>
<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['surname']; ?></td>
<td><?php echo $row['phone_number']; ?></td>
<td><?php echo $row['email']; ?></td>
<td><?php echo $row['visitorName']; ?></td>
<td><?php echo $row['signInTime']; ?></td>
<td>
<?php
if($row['EvacuatingTime'] == NULL){
echo "<span class='badge bg-warning'>Inside</span>";
}else{
echo "<span class='badge bg-success'>Signed Out</span>";
}
?>
</td>
</tr>
<?php
}
}else{
echo "<tr><td colspan='8'>No visitors found</td></tr>";
}
?>
</tbody>
</table>
</div>
<?php } ?>


<!-- GRAPH -->
<?php if($view == "graph"){ ?>
<canvas id="chart"></canvas>

<script>
new Chart(document.getElementById('chart'), {
type: 'bar',
data: {
labels: <?php echo json_encode($days); ?>,
datasets: [{
label: 'Visitors',
data: <?php echo json_encode($totals); ?>
}]
}
});
</script>
<?php } ?>

</div>
</div>
</div>
<!-- jQuery + Bootstrap -->
<script src="../js/jquery-3.3.1.slim.min.js"></script>
<script src="../js/popper.min.js"></script>
<script src="../js/bootstrap.min.js"></script>
<script src="../js/jquery-3.3.1.min.js"></script>

<script>
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
