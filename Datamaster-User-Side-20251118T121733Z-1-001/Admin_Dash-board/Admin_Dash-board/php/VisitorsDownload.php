<?php 
  //Call Auth_session on Home
  include("../php/auth_session.php");

  //Get Connection
  include ('../php/connection.php');

  //Retrive Weekly ChartData
  include('../DataFetchers/weekly_Chart_Retriv.php');

?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
        <title>Datamaster Weekly Reporting
		</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.5.141/pdf.min.js"  
    integrity="sha512-BagCUdQjQ2Ncd42n5GGuXQn1qwkHL2jCSkxN5+ot9076d5wAI8bcciSooQaI3OG3YLj6L97dKAFaRvhSXVO0/Q==" 
    crossorigin="anonymous" 
    referrerpolicy="no-referrer">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/addimage.min.js"></script>

    <script src="cdnjs.cloudflare.com/ajax/libsjspdf/2.4.0jspdf.debug.js"></script>
</head>
<body>
  <script>
    function downloadTableFromDB() {
  // Create a new jsPDF instance
  const doc = new jsPDF('');

  // Add company logo and details
  const companyLogo = '../images/Logo.jpeg';
  const companyName = 'SD Creatives Dev';
  const companyAddress = 'Durban 4000';

  doc.setFontSize(16);
  doc.addImage(companyLogo, 'PNG', 10, 10, 80, 40);
  doc.text(companyName, 150, 29);
  doc.setFontSize(12);
  doc.text(companyAddress, 150, 35);

  // Get the table from the database
  const tableData = fetchTableFromDB(); // Implement your logic to fetch the table from the database

  // Define table title style
  const tableTitleStyle = {
    textColor: 'white',
    fillColor: 'blue',
    fontStyle: 'bold',
    fontSize: 12
  };

  // Set the table title style
  doc.setTextColor(tableTitleStyle.textColor);
  doc.setFillColor(tableTitleStyle.fillColor);
  doc.setFontStyle(tableTitleStyle.fontStyle);
  doc.setFontSize(tableTitleStyle.fontSize);

  // Add the table title
  doc.text(15, 60, 'Table Title'); // Replace 'Table Title' with your actual table title

  // Convert table data to CSV format
  const csvData = convertTableToCSV(tableData);

  // Add the CSV content to the PDF
  doc.setTextColor('black'); // Reset text color to black for table content
  doc.setFontSize(12);
  doc.text(15, 70, csvData); // Adjust the position based on your requirements

  // Save the PDF
  doc.save('table.pdf');
}

function fetchTableFromDB() {
  // Implement your logic to fetch the table data from the database
  return new Promise((resolve, reject) => {
    // Connect to the database
    connection.connect((error) => {
      if (error) {
        console.error('Error connecting to the database:', error);
        reject(error);
      } else {
        // Execute the query to fetch the table data
        const query = "SELECT u.fname, u.lname, u.mnum, u.email, q.timein FROM `user_table` AS u INNER JOIN `questions_table` AS q ON u.email = q.email_phone WHERE q.timeout = ''";
        // Replace 'your_table' with your actual table name
        connection.query(query, (error, results) => {
          if (error) {
            console.error('Error while fetching table data:', error);
            reject(error);
          } else {
            // Close the database connection
            connection.end();

            // Resolve with the fetched table data
            resolve(results);
          }
        });
      }
    });
  });
}

function convertTableToCSV(tableData) {
  // Implement your logic to convert the table data to CSV format
  let csvContent = 'First Name, Last Name, Mobile Number, Email, Time In\n';
  for (const row of tableData) {
    csvContent += `${row.fname}, ${row.lname}, ${row.mnum}, ${row.email}, ${row.timein}\n`;
  }
  return csvContent;
}

 </script>
</body>
