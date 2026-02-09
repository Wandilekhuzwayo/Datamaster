<div class="container-fluid ng-scope">
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <!-- Add any heading content here if needed -->
                </div>
                <div class="panel-body">
                    <table class="table table-striped ng-scope ng-table table-hover" id="tableV" name="tableV" style="margin-left: 50; display: none;">
                        <tr style="background-color: #3ab5e6; color: white;">
                            <th style="width:25%">Year</th>
                            <th style="width:25%">Number of Visitors</th>
                        </tr>

                        <?php
                        // Include your database connection code here
                        $conn = mysqli_connect("localhost", "root", "", "datamaster");

                        // Check for database connection errors
                        if (!$conn) {
                            die("Connection failed: " . mysqli_connect_error());
                        }

                        // Fetch years and visitor counts from the database
                        $yearQuery = "SELECT YEAR(SignInTime) AS year, COUNT(*) AS visitorCount
                                      FROM `mock_data`
                                      GROUP BY YEAR(SignInTime)
                                      ORDER BY YEAR(SignInTime)";

                        $yearResult = mysqli_query($conn, $yearQuery);

                        while ($row = mysqli_fetch_assoc($yearResult)) {
                            echo "<tr>";
                            echo "<td>" . $row['year'] . "</td>";
                            echo "<td>" . $row['visitorCount'] . "</td>";
                            echo "</tr>";
                        }

                        // Close the database connection
                        mysqli_close($conn);
                        ?>
                    </table>
                </div>
                <div class="panel-footer">
                    <div type="'excel'" class="ng-isolate-scope">
                        <a class="ng-excel"><span></span></a>
                    </div>
                    <a href="../php/downloadTableYearly.php" class="btn btn-success ng-binding" id="downloadbtn" name="downloadbtn">Download PDF</a>
                </div>
            </div>
        </div>
    </div>
</div>
