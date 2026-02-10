<?php
// Function to get the current script name to possibly highlight active link or handle paths
$current_page = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container-fluid">
    <a class="navbar-brand" href="../../index.html" style="cursor: pointer;">
      <img src="./Images/Logo.png" alt="DataMaster Logo" width="30" height="24" class="d-inline-block align-text-top">
      Datamaster
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'Dashboard.php') ? 'active' : ''; ?>" aria-current="page" href="Dashboard.php">Home</a>
        </li>
      </ul>
      <div class="d-flex">
        <button class="btn btn-outline-light" type="button" onclick="history.back()">Back</button>
      </div>
    </div>
  </div>
</nav>
