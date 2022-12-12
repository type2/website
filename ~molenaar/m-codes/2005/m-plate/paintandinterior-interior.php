<html>
<head>
<title>paint and upholstery codes - upholstery codes</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFCC">
<p><b><font size="2" face="Arial, Helvetica, sans-serif">Upholstery codes</font></b></p>

<table>
	<tr>
		<td><img src="jpg/t2upholstery.jpg" width="555" height="66" border="0"></td>
	</tr>
	<tr>
		
    <td><font size="1" face="Arial, Helvetica, sans-serif">Upholstery codes from Dutch 
      "VW-Kleinbus" brochure 08/71.</font></td>
	</tr>
</table>

<p><font size="2" face="Arial, Helvetica, sans-serif">
These are the codes that identify the interior that was used. Both material and colour. The side panels were always made of vinyl, even if
the seats have cloth upholstery.</font><br>

<font size="2" face="Arial, Helvetica, sans-serif"><b>Note:</b> 1968-1971 Models with a special paint intruction ('<i>Sonderlackierung</i>') have no upholstery code.
These cars have a regular interior that was used in that year. This is beige (.&nbsp;.&nbsp;.&nbsp;.&nbsp;36) for 1968 and dark beige
(.&nbsp;.&nbsp;.&nbsp;.&nbsp;50) for 1969-1971. Microbuses had an interior matching the special colour that was ordered. By ordering
an M-code a different colour was available.<br></font></p>

<p>&nbsp; 

<center>
<table border="0" cellpadding="2" cellspacing="0" id="upholstery codes" width=90%>
  <tr> 
    <td nowrap colspan="2" bgcolor="#000000"><strong><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">M-plate 
      code&nbsp;</font></strong></td>
	<td nowrap bgcolor="#000000"><strong><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">Material&nbsp;</font></strong></td>
    <td nowrap bgcolor="#000000"><strong><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">German 
      name</font></strong></td>
    <td nowrap bgcolor="#000000"><strong><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">English 
      name</font></strong></td>
    <td nowrap bgcolor="#000000"><strong><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">remarks</font></strong></td>
  </tr>
  <tr> 



<?PHP
$fcontents = file('coloursinterior.csv');
$teller=0;

while ( list( $line_num, $line ) = each( $fcontents ) )
{ list( $code, $material, $namegerman, $nameenglish, $remarks) = split( ';', $line );

 {  if ($teller==0) { $teller=1; $bgcolor="#FFFFDD"; } // bepaal de achtergrondkleur
  else { $teller=0; $bgcolor="#FFFF99";};}


 print "<TR bgcolor=$bgcolor>";
 print "<TD nowrap align=right><font size=2 face=Arial><b>.&nbsp;.&nbsp;.&nbsp;.</b></font></TD>";
 print "<TD nowrap align=left><font size=2 face=Arial><b>$code</b></font></TD>";
 print "<TD nowrap><font size=1 face=Arial>$material</font></TD>";
 print "<TD nowrap><font size=2 face=Arial>$namegerman</font></TD>";
 print "<TD nowrap><font size=2 face=Arial>$nameenglish</font></TD>";
 print "<TD><font size=2 face=Arial>$remarks</font></TD>";
 print "</TR>";
 }
 
  // einde 


?>
</TABLE><br>
</center>



<p>&nbsp;</p>
<p><font size="-1" face="Arial, Helvetica, sans-serif">&copy; Vincent Molenaar</font><br>
  <font size="-2" face="Arial, Helvetica, sans-serif">last update: 27.07.2005</font> 
</p>
</BODY>
</HTML>