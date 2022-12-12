<html>
<head>
<title>destination codes</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFCC">
<b><font size="2" face="Arial, Helvetica, sans-serif">Destination codes</font></b><font size="2" face="Arial, Helvetica, sans-serif"> 
<p><font size="2" face="Arial, Helvetica, sans-serif">This code shows where the 
  bus was transported to after it left the factory. It only gives information about
  the destination, not to any export-market specifications, for this purpose the
  M-codes were used.</font></p>
  
<p><font size="2" face="Arial, Helvetica, sans-serif">If the destination code 
  is a number it means it was destined for the West-German market. Letters represent 
  an export market. Until March 1969 the space for the destination code was left 
  empty if the bus was made for West-Germany.</p>
 
<table width=100% border="0" cellpadding="2" cellspacing="5">
  <tr> 
    <td colspan=2 nowrap bgcolor="#000000"><strong><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">Destination codes - West-Germany (numbers)</font></strong></td>
  </tr>
  <tr> 
    <td colspan=2 nowrap bgcolor="#FFFFCC"><strong><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">&nbsp;</td>
  </tr>
<tr>
<td width="240" bgcolor="#FFFFDD" valign="top">
<p><font size="2" face="Arial, Helvetica, sans-serif">
  Each number represents a wholesale dealer which distributed the cars among the 
  other VW-dealerships in the area. In 1976 this distribution system was changed. 
  The large number of wholesale dealers was brought back to a smaller number of 
  distribution centres. If the destination is '888' it means the bus had no destination 
  and it probably indicates that the bus was built without someone ordering it first. 
  The '888' is particulary seen on the 'Silberfish' special salescampaign buses in 1978/1979. 
  Special customers inside West-Germany had their own destination code such as 
  the German Mail (903) and the German Army (906). The destination codes for the 
  West-German market were introduced in March 1969. Until then the space for the 
  destination code was left blank if the bus was meant to be sold in West-Germany.
  </font></p>
</td>
<td valign="top">
<table border="0" cellpadding="2" cellspacing="0" id="upholstery codes" width=90%>
  <tr> 
    <td width="90" bgcolor="#000000"><strong><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">M-plate 
      code&nbsp;</font></strong></td>
	<td nowrap bgcolor="#000000"><strong><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">Destination&nbsp;</font></strong></td>
  </tr>
  <tr> 
<TR bgcolor="#FFFFDD">
<TD nowrap align=center><font size=2 face=Arial><b>001-106</b></font></TD>
<TD nowrap><font size=1 face=Arial>wholesale dealers (March 1969 - December 1975)</font></TD>
</TR>

<TR bgcolor="#FFFF99">
<TD nowrap align=center><font size=2 face=Arial><b>121-146</b></font></TD>
<TD nowrap><font size=1 face=Arial>distribution centers (January 1976 onwards)</font></TD>
</TR>

<?PHP
$fcontents = file('destinationsgermany.csv');
$teller=0;

while ( list( $line_num, $line ) = each( $fcontents ) )
{ list( $code, $destination, $destinationgerman) = split( ';', $line );

 {  if ($teller==0) { $teller=1; $bgcolor="#FFFFDD"; } // bepaal de achtergrondkleur
  else { $teller=0; $bgcolor="#FFFF99";};}


 print "<TR bgcolor=$bgcolor>";
 print "<TD nowrap align=center><font size=2 face=Arial><b>$code</b></font></TD>";
 print "<TD nowrap><font size=1 face=Arial>$destination</font></TD>";
  print "</TR>";
 }
 
  // einde 
?>
</TABLE>
</td>
</tr>
</table>

<p>&nbsp;</p>

<table width=100% border="0" cellpadding="2" cellspacing="5">
  <tr> 
    <td colspan=2 nowrap bgcolor="#000000"><strong><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">Destination codes - Export (letters)</font></strong></td>
  </tr>
<tr> 
    <td colspan=2 nowrap bgcolor="#FFFFCC"><strong><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">&nbsp;</td>
  </tr>
<tr>
<td width="240" bgcolor="#FFFFDD" valign="top">
<p><font size="2" face="Arial, Helvetica, sans-serif">
Export destinations are identified by two letters. In most cases one country 
has one destination code. For large countries more codes were reserved to specify 
a specific region or city.</font></p>
</td>
<td valign="top">
<table border="0" cellpadding="2" cellspacing="0" id="destination codes - export" width=90%>
  <tr> 
    <td width="90" bgcolor="#000000"><strong><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">M-plate 
      code&nbsp;</font></strong></td>
	<td nowrap bgcolor="#000000"><strong><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">Destination&nbsp;</font></strong></td>
  </tr>



<?PHP
$fcontents = file('destinationsexport.csv');
$teller=0;

while ( list( $line_num, $line ) = each( $fcontents ) )
{ list( $code, $destination, $destinationgerman) = split( ';', $line );

 {  if ($teller==0) { $teller=1; $bgcolor="#FFFFDD"; } // bepaal de achtergrondkleur
  else { $teller=0; $bgcolor="#FFFF99";};}


 print "<TR bgcolor=$bgcolor>";
 print "<TD nowrap align=center><font size=2 face=Arial><b>$code</b></font></TD>";
 print "<TD nowrap><font size=1 face=Arial>$destination</font></TD>";
  print "</TR>";
 }
 
  // einde 


?>
</TABLE>
</td>
</tr>
</table>

<p>&nbsp;</p>
<p><font size="-1" face="Arial, Helvetica, sans-serif">&copy; Vincent Molenaar</font><br>
  <font size="-2" face="Arial, Helvetica, sans-serif">last update: 12.12.2005</font> 
</p>
</BODY>
</HTML>