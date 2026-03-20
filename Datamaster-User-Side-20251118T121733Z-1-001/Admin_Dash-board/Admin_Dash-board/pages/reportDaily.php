<?php 
include("../php/auth_session.php");
include("../php/connection.php");

// ================= USER DETAILS =================
$emailAddress = $_SESSION["firstname"];

$userQuery = "SELECT firstname, surname, email, companyname 
              FROM admin_table 
              WHERE email = '$emailAddress'";

$userResult = mysqli_query($conn, $userQuery);

if($row = mysqli_fetch_assoc($userResult)){
    $firstname = $row['firstname'];
    $email = $row['email'];
    $Enterprise = $row['companyname'];
}

$currentDatetime = date("Y-m-d H:i:s");


// ================= DATE FILTER =================
$selectedDate = $_POST['selectedDate'] ?? date("Y-m-d");


// ================= HOURLY VISITOR DATA =================
$visitorData = [];

$query = "SELECT HOUR(signInTime) AS hour, COUNT(*) AS total
          FROM visitorstbl
          WHERE DATE(signInTime) = '$selectedDate'
          GROUP BY HOUR(signInTime)";

$result = mysqli_query($conn, $query);

while($row = mysqli_fetch_assoc($result)){
    $visitorData[$row['hour']] = $row['total'];
}

$hours = range(0, 23);
$visitTime = [];
$visitCount = [];

foreach ($hours as $hour) {
    $visitTime[] = sprintf("%02d:00", $hour);
    $visitCount[] = $visitorData[$hour] ?? 0;
}

$totalVisitors = array_sum($visitCount);
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Daily Visitor Report</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

<div class="container mt-4">

<h3>Daily Visitor Reporting</h3>

<!-- DATE FILTER -->
<form method="POST" class="mb-3">
    <div class="row">
        <div class="col-md-3">
            <input type="date" name="selectedDate" class="form-control" 
                   value="<?php echo $selectedDate; ?>">
        </div>

        <div class="col-md-2">
            <button class="btn btn-primary">Load Report</button>
        </div>
    </div>
</form>


<!-- SUMMARY -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white p-3">
            <h5>Total Visitors</h5>
            <h2><?php echo $totalVisitors; ?></h2>
        </div>
    </div>
</div>


<!-- GRAPH -->
<div class="card p-3 mb-4">
    <h5>Hourly Visitor Trend</h5>
    <canvas id="dailyChart"></canvas>
</div>


<!-- TABLE -->
<div class="card p-3">
<h5>Daily Time Summary</h5>

<table class="table table-bordered">
<thead class="table-dark">
<tr>
<th>Time Range</th>
<th>Date</th>
<th>Total Visitors</th>
</tr>
</thead>

<tbody>

<?php
$timeRanges = [
"00:00 - 06:00",
"06:01 - 11:59",
"12:00 - 18:00",
"18:01 - 23:59"
];

$total = 0;

foreach ($timeRanges as $range){

$query = "SELECT COUNT(*) as total 
          FROM visitorstbl
          WHERE DATE(signInTime) = '$selectedDate'
          AND TIME(signInTime) BETWEEN 
          SUBSTRING_INDEX('$range',' - ',1)
          AND SUBSTRING_INDEX('$range',' - ',-1)";

$res = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($res);

$count = $row['total'];
$total += $count;

echo "<tr>
<td>$range</td>
<td>$selectedDate</td>
<td>$count</td>
</tr>";
}

echo "<tr class='table-primary'>
<td>Total</td>
<td></td>
<td>$total</td>
</tr>";
?>

</tbody>
</table>

</div>

</div>


<script>
const xValues = <?php echo json_encode($visitTime); ?>;
const yValues = <?php echo json_encode($visitCount); ?>;

new Chart("dailyChart", {
type: "line",
data: {
labels: xValues,
datasets: [{
label: "Visitors",
fill: true,
data: yValues
}]
}
});
</script>


</body>
</html>
