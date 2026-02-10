<?php 
  // Call Auth_session on Home
  include("../php/auth_session.php");

  // Get Connection
  include ('../php/connection.php');

  // Retrieve Yearly ChartData
  include('../php/Yearly_Chart_Retriv.php');

  // Select query
  if(isset($_POST['search-year'])){
    $searchYear = $_POST['year'];
    $query = "SELECT MONTH(signInTime) as visit_month, COUNT(*) AS visit_count FROM mock_data WHERE YEAR(signInTime) = '$searchYear' GROUP BY visit_month";
    $result = filterTable($query);
  }
  else {
    $query = "SELECT id, email_phone, person_name, person_surname, person_contact, timein, timeout FROM `questions_table`";
    $result = filterTable($query);
  }

  function filterTable($query) {
    // Get Connection
    include ('../php/connection.php');

    // Resulting
    $result = mysqli_query($conn, $query);

    return $result;
  }

?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <title>Datamaster Monthly Reporting</title>
    
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

    <!-- Google Material Icon -->
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

      function retrieveDbToChart() {
        $(document).ready(function() {
          $('#sidebarCollapse').on('click', function() {
            $('#sidebar').toggleClass('active');
          });
        });

        var year = <?php echo json_encode($searchYear); ?>;
        var monthData = <?php echo json_encode(mysqli_fetch_all($result, MYSQLI_ASSOC)); ?>;
        
        var months = [];
        var visitCounts = [];
        
        monthData.forEach(function(data) {
          months.push(data.visit_month);
          visitCounts.push(data.visit_count);
        });

        var ctx = document.getElementById('monthlyChart').getContext('2d');
        var monthlyChart = new Chart(ctx, {
          type: 'line',
          data: {
            labels: months,
            datasets: [{
              label: 'Monthly Visits',
              data: visitCounts,
              backgroundColor: 'rgba(0, 123, 255, 0.2)',
              borderColor: 'rgba(0, 123, 255, 1)',
              borderWidth: 1,
              pointBorderColor: 'rgba(0, 123, 255, 1)',
              pointBackgroundColor: 'rgba(0, 123, 255, 1)',
              pointRadius: 3,
              pointHoverRadius: 5,
              pointHitRadius: 10,
              pointHoverBackgroundColor: 'rgba(0, 123, 255, 1)',
              pointHoverBorderColor: 'rgba(0, 123, 255, 1)',
              pointHoverBorderWidth: 2,
              tension: 0.4,
            }]
          },
          options: {
            responsive: true,
            scales: {
              y: {
                beginAtZero: true,
              }
            }
          }
        });
      }
    </script>
  </head>
  <body>
    <div class="wrapper">
      <!-- Sidebar -->
      <nav id="sidebar" class="active">
        <ul class="list-unstyled components">
          <li>
            <a href="home.php">Home</a>
          </li>
          <li class="active">
            <a href="#reportSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Reports</a>
            <ul class="collapse list-unstyled" id="reportSubmenu">
              <li>
                <a href="#">Monthly</a>
              </li>
              <li>
                <a href="#">Yearly</a>
              </li>
            </ul>
          </li>
          <li>
            <a href="#manageSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Manage</a>
            <ul class="collapse list-unstyled" id="manageSubmenu">
              <li>
                <a href="#">Users</a>
              </li>
              <li>
                <a href="#">Customers</a>
              </li>
            </ul>
          </li>
        </ul>
      </nav>

      <!-- Content -->
      <div id="content">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
          <div class="container-fluid">
            <button type="button" id="sidebarCollapse" class="btn btn-info">
              <i class="fas fa-align-left"></i>
              <span>Toggle Sidebar</span>
            </button>
          </div>
        </nav>

        <form method="POST" action="">
          <div class="container mt-5">
            <div class="row">
              <div class="col-4">
                <div class="form-group">
                  <select class="form-control" id="year" name="year">
                    <?php
                      $yearQuery = "SELECT DISTINCT YEAR(signInTime) AS year FROM mock_data ORDER BY year DESC";
                      $yearResult = filterTable($yearQuery);
                      while($row = mysqli_fetch_array($yearResult)):
                    ?>
                    <option value="<?php echo $row['year']; ?>" <?php if($searchYear == $row['year']) echo 'selected'; ?>><?php echo $row['year']; ?></option>
                    <?php endwhile; ?>
                  </select>
                </div>
              </div>
              <div class="col-2">
                <button type="submit" class="btn btn-primary" name="search-year">Search</button>
              </div>
            </div>
          </div>
        </form>

        <div class="container mt-5">
          <div class="row">
            <div class="col-12">
              <canvas id="monthlyChart"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="../js/bootstrap.bundle.min.js"></script>
    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../js/jquery.min.js"></script>
    <script src="../js/script.js"></script>
    <script src="../js/admi.js"></script>
    <script src="../js/Chart.min.js"></script>
  </body>
</html>
