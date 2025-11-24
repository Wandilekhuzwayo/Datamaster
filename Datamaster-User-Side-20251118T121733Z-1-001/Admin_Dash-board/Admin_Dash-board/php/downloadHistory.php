<?php 
 //Call a connection
 include('../php/connection.php');

 //declare a variable
 $export = '';

 //call TCPDF class
 require_once('../tcpdf/tcpdf.php');

 $obj_pdf = new TCPDF('p', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
 
 $obj_pdf->SetCreator(PDF_CREATOR);

 $obj_pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);

 $obj_pdf->SetHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_DATA));

 $obj_pdf->SetMargins(PDF_MARGIN_LEFT, '5', PDF_MARGIN_RIGHT);
 $obj_pdf->setPrintHeader(false);
 $obj_pdf->setPrintFooter(false);
 $obj_pdf->SetAutoPageBreak(TRUE, 10);
 $obj_pdf->SetFont('helvetica', '', 12);
 $obj_pdf->AddPage();

 //Select query
 $query = "SELECT email_phone, person_name, person_surname, person_contact, timein, timeout FROM `questions_table`";
 $res = mysqli_query($conn, $query);
if(mysqli_num_rows($res) > 0)
{
$no=1;
$export .= '
<table width="107%" cellpadding="1" cellspacing="1" border="1"> 
<tr> 
<th>ID</th>
<th>Email Address</th> 
<th>Name</th> 
<th>Surname</th> 
<th>Contact</th>
<th>Time In</th>
<th>Time Out</th>

</tr>
';
while($row = mysqli_fetch_array($res))
{
$export .= '
<tr>
<td>'.$no.'</td> 
<td>'.$row["email_phone"].'</td> 
<td>'.$row["person_name"].'</td> 
<td>'.$row["person_surname"].'</td> 
<td>'.$row["person_contact"].'</td>   
<td>'.$row["timein"].'</td> 
<td>'.$row["timeout"].'</td> 
</tr>
';
$no++;
}
$export .= '</table>';
$obj_pdf->writeHTML($export);

$obj_pdf->Output("history.pdf");;
echo $export;
}
?>