<?php
include("common.php");

//set up SQL connection
	$link = mysql_connect ($server, $user, $password);
		if (! $link)
			{
			die ("Couldn't connect to mySQL server");
			}
		if (!mysql_select_db ($db, $link) )
			{
			die ("Coldn't open $db: ".mysql_error() );
			}
print "<!-- HERE BEGINNETH THE HEADER -->\r\n";
include("./templates/user_top.html");
?>


<table border=0 cellspacing=0>
<tr><td width=120 valign=top>
<!-- left navigation bar -->

<!-- begin feature code-->
<center>


<B><font face=\"ms sans serif\" size=2>Featured Listings:</font></b>


<?php
//EXAMPLE CODE:

//handles the listing of featured properties
$result = mysql_query("SELECT * FROM vehicles WHERE featured = 'Y';",$link);
while ($a_row =mysql_fetch_array ($result) )
	{
	//add commas to price
	$a_row[price] = number_format ($a_row[price]);
	
	print "<P>";
	print "<a href=\"./carview.php?view=$a_row[id]\"><b>$a_row[title]</b></a><BR>";
	//select images connected to a given listing
				$query = "SELECT * FROM tbl_Files WHERE prop_num = $a_row[id] LIMIT 1";
				$output = mysql_query("$query",$link);
				
				
				$count = 0;
				while ($image_row =mysql_fetch_array ($output) )
					{
					
					
					print "<a href=\"carview.php?view=$a_row[id]\"><img src='image.php?Id=$image_row[id_files]' border=1 width=100 alt=\"Photo\"></a><br>";
					$count++;
					}
				
				
				if ($count == 0)
					{
					print "<a href=\"./carview.php?view=$a_row[id]\"><img src=\"./images/nophoto.gif\" border=1 width=100 alt=\"View Listing\"></a><br>";
					}	
				
	
 	print "$a_row[year] $a_row[model] $a_row[make]<BR>";
  	print "\$$a_row[price]<BR>";
  	print "<P>";
  	

	
	}
//END OF EXAMPLE
?>

</font></center>
</td>
<!-- End feature code -->
<td width=30> </td>
<td valign=top>
<!-- End Header -->


<!-- begin main content -->

<table border="0" cellpadding="0" cellspacing="0" width="600" bordercolordark="#C0C0C0" bordercolorlight="#808080">
  <tr>
    <td align="center" <A HREF="carview.php"><IMG SRC="neon.jpg" WIDTH=288 HEIGHT=216 ALT="Bus Image" BORDER=0></A><br><font color="#800080"><big><a href="carview.php">Browse All Listings</a><br><a href="browse.php">Search Listings</a><br></TD>
  </tr>
    <td valign="top" width="450"><blockquote>
      <p align="center">&nbsp;</p>
      <p align="center"><big><font color="#800080" face="MS Sans Serif,sans-serif,Arial"><strong>Welcome to<br>
      Air-Cooled Buy &amp; Sell Online!</strong></font></big></p>
      <p align="center"><font face="MS Sans Serif,sans-serif,Arial">Aircooled vehicles and parts for sale by private 
parties. A service of www.type2.com</font></p>
    </blockquote>
    </td>
  </tr>
</table>


<!-- end main content -->
		</table>
		
<?php		
//print the footer
		print"\r\n<!-- THUS ENDETH THE MAIN CONTENT -->\r\n<!-- HERE BEGINNETH THE FOOTER -->";
		include("./templates/user_bottom.html");
		
//gots to close the mysql connection
	mysql_close($link);
?>
