<?php
  //Call Auth_session on Home
  include("../php/auth_session.php");

  //Call a connection
  include('../php/connection.php');
  $emailAddres = $_SESSION["firstname"];

  //Hide warning errors
  //error_reporting(E_ERROR | E_PARSE);

  //Create a Query
  $result = mysqli_query($conn, "SELECT  firstname, surname, email, companyname, employeeNo, department, addresses FROM `admin_table` WHERE email ='$emailAddres'");

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
    $servername = "localhost";
    $username = "yourusername";
    $password = "yourpassword";
    $dbname = "yourdatabase";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = ("SELECT u.fname, u.lname, u.mnum, u.email, q.timein 
              FROM `user_table` AS u 
              INNER JOIN `questions_table` AS q 
              ON u.email = q.email_phone 
              WHERE q.timeou
            t = '' ");
    $result = $conn->query($sql);

    $data = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[] = $row['column1'] . " " . $row['column2'];
        }
    }

    $conn->close();
    ?>

    <button id="downloadButton">Download PDF</button>

    <script>
        document.getElementById("downloadButton").addEventListener("click", function() {
            var doc = new jsPDF();
            const companyLogo = '../images/Logo.jpeg';
                                        const companyName = <?php $Enterprise?>;
                                        const companyAddress = <?php $address?>;
                                        const email = <?php $email?>;
                                        const address = <?php $address?>;
                                        const date = <?php $currentDatetime?>;

                                        doc.setFontSize(16);
                                        doc.addImage(companyLogo, 'PNG', 10, 10, 80, 40);
                                        doc.text(companyName, 150, 29);
                                        doc.setFontSize(12);
                                        doc.text(companyAddress, 150, 35);
            var data = <?php echo json_encode($data); ?>;

            var y = 10;
            for (var i = 0; i < data.length; i++) {
                doc.text(10, y, data[i]);
                y += 10;
            }

            doc.save("table_data.pdf");
        });
    </script>
<!DOCTYPE html>
<html>
<head>
    <title>Download Table</title>
    <!-- Include necessary libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
</head>
<body>
    <!-- Your existing button code -->
    <button id="downloadButton">Download PDF</button>

    <script>
        document.getElementById("downloadButton").addEventListener("click", function() {
            var doc = new jsPDF();
            
            const companyLogo = '../images/Revamped Logo_page-0001_591x230.jpeg';
            const companyName = '<?php echo $Enterprise; ?>';
            const companyAddress = '<?php echo $address; ?>';
            const email = '<?php echo $email; ?>';
            const address = '<?php echo $address; ?>';
            const date = '<?php echo $currentDatetime; ?>';

            doc.setFontSize(16);
            doc.addImage(companyLogo, 'PNG', 10, 10, 80, 40);
            doc.text(companyName, 150, 29);
            doc.setFontSize(12);
            doc.text(companyAddress, 150, 35);
            
            var data = <?php echo json_encode($data); ?>;
            
            var y = 50; // Adjust the starting Y-coordinate as needed
            for (var i = 0; i < data.length; i++) {
                doc.text(10, y, data[i]);
                y += 10;
            }

            doc.save("table_data.pdf");
        });
    </script>
</body>
</html>
