<html>
<head>
<title>M-codes - 001-299</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFCC">
<p>&nbsp;</p>
<center>
<table border="0" cellpadding="2" cellspacing="0" id="regular paint codes" width=90%>
  <tr> 
      <td bgcolor="#000000" colspan=4><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><b>M-codes 
        001-999</b></font></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr> 
      <td bgcolor="#000000" nowrap><strong><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">M-code</font></strong></td>
    <td bgcolor="#000000">&nbsp;</td>
      <td bgcolor="#000000"><strong><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">Description</font></strong></td>
    <td bgcolor="#000000"><strong><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">remarks</font></strong></td>
  </tr>
  <tr> 



<?PHP
$fcontents = file('mcodes.csv');
$teller=0;

while ( list( $line_num, $line ) = each( $fcontents ) )
{ list( $jaar, $mcode, $year1, $description1, $remark1, $year2, $description2, $remark2, $year3, $description3, $remark3) = split( ';', $line );

 {  if ($teller==0) { $teller=1; $bgcolor="#FFFFDD"; } // bepaal de achtergrondkleur
  else { $teller=0; $bgcolor="#FFFF99";};}

 {  if ($jaar=="2jaar") { 
 print "<TR bgcolor=$bgcolor>";
 print "<TD rowspan=2 align=center valign=middle nowrap><font size=2 face=Arial><b>$mcode</b></font></TD>";
  print "<TD align=right valign=middle nowrap><font size=1 face=Arial>$year1:</font></TD>"; 
 print "<TD><font size=2 face=Arial>$description1</font></TD>";
 print "<TD><font size=2 face=Arial>$remark1</font></TD>";
 print "</TR>";
 print "<TR bgcolor=$bgcolor>";
 print "<TD align=right valign=middle nowrap><font size=1 face=Arial>$year2:</font></TD>"; 
 print "<TD><font size=2 face=Arial>$description2</font></TD>";
 print "<TD><font size=2 face=Arial>$remark2</font></TD>";
 print "</TR>";} // geeft de omschrijving indien er 2 tijdperken zijn voor de m-code ($jaar='2jaar')
 else{
  if ($jaar=="3jaar") { 
 print "<TR bgcolor=$bgcolor>";
 print "<TD rowspan=3 align=center valign=middle nowrap><font size=2 face=Arial><b>$mcode</b></font></TD>";
  print "<TD align=right valign=middle nowrap><font size=1 face=Arial>$year1:</font></TD>"; 
 print "<TD><font size=2 face=Arial>$description1</font></TD>";
 print "<TD><font size=2 face=Arial>$remark1</font></TD>";
 print "</TR>";
 print "<TR bgcolor=$bgcolor>";
 print "<TD align=right valign=middle nowrap><font size=1 face=Arial>$year2:</font></TD>"; 
 print "<TD><font size=2 face=Arial>$description2</font></TD>";
 print "<TD><font size=2 face=Arial>$remark2</font></TD>";
 print "<TR bgcolor=$bgcolor>";
 print "<TD align=right valign=middle nowrap><font size=1 face=Arial>$year3:</font></TD>"; 
 print "<TD><font size=2 face=Arial>$description3</font></TD>";
 print "<TD><font size=2 face=Arial>$remark3</font></TD>";
 print "</TR>";} // geeft de omschrijving indien er 3 tijdperken zijn voor de m-code ($jaar='3jaar')
  else { 
 print "<TR bgcolor=$bgcolor>";
 print "<TD align=center valign=middle nowrap><font size=2 face=Arial><b>$mcode</b></font></TD>";
  print "<TD align=right valign=middle nowrap><font size=1 face=Arial>&nbsp;</font></TD>"; 
 print "<TD><font size=2 face=Arial>$description1</font></TD>";
 print "<TD><font size=2 face=Arial>$remark1</font></TD>";
 print "</TR>";} // geeft de omschrijving voor een gewone m-code
 ;}
 }
 
  // einde 

}
?>
</table>
</center>



<p>&nbsp;</p>
<p><font size="-1" face="Arial, Helvetica, sans-serif">&copy; Vincent Molenaar</font><br>
  <font size="-2" face="Arial, Helvetica, sans-serif">last update: 12.12.2005</font> 
</p>
</BODY>
</HTML>