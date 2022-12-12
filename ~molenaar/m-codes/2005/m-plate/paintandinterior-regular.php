<html>
<head>
<title>paint and upholstery codes - regular codes</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFCC">
<table>
<tr>
	<td align=left valign="top">
		<p><b><font size="2" face="Arial, Helvetica, sans-serif">Regular paint codes</font></b></p>
		<p><font size="2" face="Arial, Helvetica, sans-serif">
		The paint codes from the regular sales programme and from sales campaign models 
		are listed in this section. For vehicles that had special paint instructions look 
		in the special paint code section.</font></p>
		<p><font size="2" face="Arial, Helvetica, sans-serif">Jump to the list for <a href="#1974-1979">1974-1979 models</a>.
		</font></p>
	</td>
	<td width=204> <img src="jpg/t2a_colors.jpg" width="204" height="224" border="0"> 
    </td>
</tr>
<tr>
	<td>&nbsp;</td>
	<td align=left valign="top"><font size="1" face="Arial, Helvetica, sans-serif">Top: 
      4242.. Lotus white L282<br>
      Bottom: 0202.. Savanna beige L620</font></td>
</tr>
</table>
<br>


<center>
<table border="0" cellpadding="2" cellspacing="0" id="regular paint codes" width=90%>
  <tr> 
    <td bgcolor="#000000" colspan=6><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><b>1968-1973 
      models</b></font></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
	<td>&nbsp;</td>
  </tr>
  <tr> 
    <td bgcolor="#000000"><strong><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">M-plate 
      code&nbsp;</font></strong></td>
    <td bgcolor="#000000">&nbsp;</td>
    <td bgcolor="#000000"><strong><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">German 
      name</font></strong></td>
    <td bgcolor="#000000"><strong><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">English 
      name</font></strong></td>
    <td bgcolor="#000000"><strong><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">VW-paint 
      code&nbsp;</font></strong></td>
    <td bgcolor="#000000"><strong><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">Remarks</font></strong></td>
  </tr>
  <tr> 



<?PHP
$fcontents = file('colours68-73.csv');
$teller=0;

while ( list( $line_num, $line ) = each( $fcontents ) )
{ list( $roof, $code, $lcodebody, $lcoderoof, $germanbody, $germanroof, $englishbody, $englishroof, $remarks) = split( ';', $line );

 {  if ($teller==0) { $teller=1; $bgcolor="#FFFFDD"; } // bepaal de achtergrondkleur
  else { $teller=0; $bgcolor="#FFFF99";};}

 {  if ($roof=="roof") { 
 print "<TR bgcolor=$bgcolor>";
 print "<TD rowspan=2 nowrap><font size=2 face=Arial><b>$code</b></font></TD>";
 print "<TD nowrap><font size=1 face=Arial>body:</font></TD>";
 print "<TD nowrap><font size=2 face=Arial>$germanbody</font></TD>";
 print "<TD nowrap><font size=2 face=Arial>$englishbody</font></TD>";
 print "<TD nowrap><font size=2 face=Arial>$lcodebody</font></TD>";
 print "<TD rowspan=2><font size=2 face=Arial>$remarks</font></TD>";
 print "</TR>";
 print "<TR bgcolor=$bgcolor>";
 print "<TD><font size=1 face=Arial>roof:</font></TD>";
 print "<TD><font size=2 face=Arial>$germanroof</font></TD>";
 print "<TD><font size=2 face=Arial>$englishroof</font></TD>";
 print "<TD><font size=2 face=Arial>$lcoderoof</font></TD>";
 ;} // geeft de omschrijving indien een dakkleur van toepassing is (modellen 1968-1970)
 else{
  if ($roof=="upper") { 
 print "<TR bgcolor=$bgcolor>";
 print "<TD rowspan=2 nowrap><font size=2 face=Arial><b>$code</b></font></TD>";
 print "<TD nowrap><font size=1 face=Arial>lower half:</font></TD>";
 print "<TD nowrap><font size=2 face=Arial>$germanbody</font></TD>";
 print "<TD nowrap><font size=2 face=Arial>$englishbody</font></TD>";
 print "<TD nowrap><font size=2 face=Arial>$lcodebody</font></TD>";
 print "<TD rowspan=2><font size=2 face=Arial>$remarks</font></TD>";
 print "</TR>";
 print "<TR bgcolor=$bgcolor>";
 print "<TD><font size=1 face=Arial>upper half:</font></TD>";
 print "<TD><font size=2 face=Arial>$germanroof</font></TD>";
 print "<TD><font size=2 face=Arial>$englishroof</font></TD>";
 print "<TD><font size=2 face=Arial>$lcoderoof</font></TD>";
 print "</TR>";} // geeft de omschrijving indien de scheiding van de twee kleuren 10cm onder de ramen ligt (modellen 1971-1973)

  else { 
 print "<TR bgcolor=$bgcolor>";
 print "<TD nowrap><font size=2 face=Arial><b>$code</b></font></TD>";
 print "<TD nowrap>&nbsp;</TD>";
 print "<TD nowrap><font size=2 face=Arial>$germanbody</font></TD>";
 print "<TD nowrap><font size=2 face=Arial>$englishbody</font></TD>";
 print "<TD nowrap><font size=2 face=Arial>$lcodebody</font></TD>";
 print "<TD><font size=2 face=Arial>$remarks</font></TD>";
 print "</TR>";} // geeft de omschrijving als 1 kleur is gebruikt voor de hele koets
 ;}
 }
 
  // einde 

}
?>
</TABLE><br>
</center>
<hr noshade width="100%">
<p>
<a name="1974-1979">
<table width="100%">
  <tr> 
    <td align=left valign="top"><font size="2" face="Arial, Helvetica, sans-serif"><b>Colour 
      codes for 1974-1979 models</b></font> <p> <font size="2" face="Arial, Helvetica, sans-serif">With 
        the start of model year 1974 all colour codes from the regular sales programm 
        were replaced by codes with letters. The codes starting with '48' and 
        '49' are special sales campaign colours.</font> 
      <p> </td>
    <td width=204 colspan=2> <p><img src="jpg/t2b_colors.jpg" width="204" height="185" border="0"></td>
  </tr>
  <tr> 
    <td></td>
    <td width=102><font size="1" face="Arial, Helvetica, sans-serif">E9D9..</font></td>
    <td width=102><font size="1" face="Arial, Helvetica, sans-serif">T1D9..</font></td>
  </tr>
</table>
<br>
<center>
<table border="0" cellpadding="2" cellspacing="0" id="regular paint codes" width=90%>
  <tr> 
    <td bgcolor="#000000" colspan=6><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><b>1974-1979 
      models</b></font></td>
  </tr>
  <tr> 
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
	<td>&nbsp;</td>
  </tr>
  <tr> 
    <td bgcolor="#000000"><strong><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">M-plate 
      code&nbsp;</font></strong></td>
    <td bgcolor="#000000">&nbsp;</td>
    <td bgcolor="#000000"><strong><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">German 
      name</font></strong></td>
    <td bgcolor="#000000"><strong><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">English 
      name</font></strong></td>
    <td bgcolor="#000000"><strong><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">VW-paint 
      code&nbsp;</font></strong></td>
    <td bgcolor="#000000"><strong><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif">Remarks</font></strong></td>
  </tr>
  <tr> 



<?PHP
$fcontents = file('colours74-79.csv');
$teller=0;

while ( list( $line_num, $line ) = each( $fcontents ) )
{ list( $roof, $code, $lcodebody, $lcoderoof, $germanbody, $germanroof, $englishbody, $englishroof, $remarks) = split( ';', $line );

 {  if ($teller==0) { $teller=1; $bgcolor="#FFFFDD"; } // bepaal de achtergrondkleur
  else { $teller=0; $bgcolor="#FFFF99";};}

 {  if ($roof=="roof") { 
 print "<TR bgcolor=$bgcolor>";
 print "<TD nowrap><font size=2 face=Arial><b>$code</b></font></TD>";
 print "<TD nowrap><font size=1 face=Arial>body:</font><br><font size=1 face=Arial>roof:</font></TD>"; 
 print "<TD nowrap><font size=2 face=Arial>$germanbody<br><font size=2 face=Arial>$germanroof</font></TD>";
 print "<TD nowrap><font size=2 face=Arial>$englishbody<br><font size=2 face=Arial>$englishroof</font></TD>";
 print "<TD nowrap><font size=2 face=Arial>$lcodebody<br>$lcoderoof</font></TD>";
 print "<TD><font size=2 face=Arial>$remarks</font></TD>";
 print "</TR>";} // geeft de omschrijving indien een dakkleur van toepassing is (modellen 1968-1970)
 else{
  if ($roof=="upper") { 
 print "<TR bgcolor=$bgcolor>";
 print "<TD nowrap><font size=2 face=Arial><b>$code</b></font></TD>";
  print "<TD nowrap><font size=1 face=Arial>lower half:</font><br><font size=1 face=Arial>upper half:</font></TD>"; 
 print "<TD nowrap><font size=2 face=Arial>$germanbody<br><font size=2 face=Arial>$germanroof</font></TD>";
 print "<TD nowrap><font size=2 face=Arial>$englishbody<br>$englishroof</font></TD>";
 print "<TD nowrap><font size=2 face=Arial>$lcodebody<br>$lcoderoof</font></TD>";
 print "<TD><font size=2 face=Arial>$remarks</font></TD>";
 print "</TR>";} // geeft de omschrijving indien de scheiding van de twee kleuren 10cm onder de ramen ligt (modellen 1971-1973)

  else { 
 print "<TR bgcolor=$bgcolor>";
 print "<TD nowrap><font size=2 face=Arial><b>$code</b></font></TD>";
 print "<TD nowrap>&nbsp;</TD>";
 print "<TD nowrap><font size=2 face=Arial>$germanbody</font></TD>";
 print "<TD nowrap><font size=2 face=Arial>$englishbody</font></TD>";
 print "<TD nowrap><font size=2 face=Arial>$lcodebody</font></TD>";
 print "<TD><font size=2 face=Arial>$remarks</font></TD>";
 print "</TR>";} // geeft de omschrijving als 1 kleur is gebruikt voor de hele koets
 ;}
 }
 
  // einde 

}
?>
</TABLE><br>
</center>



<p>&nbsp;</p>
<p><font size="-1" face="Arial, Helvetica, sans-serif">&copy; Vincent Molenaar</font><br>
  <font size="-2" face="Arial, Helvetica, sans-serif">last update: 14.12.2005</font> 
</p>
</BODY>
</HTML>