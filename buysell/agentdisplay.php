<?php
//MESS NOT WITH THIS CODE (UNLESS YOU KNOW WHAT YOU'RE DOING, OF COURSE)
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
			
//set up the page
print "<!-- HERE BEGINNETH THE HEADER -->\r\n";
include("./templates/user_top.html");

print "<TR><TD valign=top align=center><font size=1 face=\"Arial,Helvetica,Geneva,Swiss,SunSans-Regular\">";
			
$result = mysql_query("SELECT * FROM agents WHERE id='$view';",$link);
		while ($a_row =mysql_fetch_array ($result) )
			{
			
			
			//select images connected to a given listing
			$query = "SELECT * FROM agent_tbl_Files WHERE agentnum = $view";
			$result = mysql_query("$query",$link);
			
			while ($image_row =mysql_fetch_array ($result) )
				{
				
				echo "<P> \n";
				
				
				echo "<img src='agent_image.php?Id=$image_row[id_files]' border=0 vspace=4 hspace=4><BR>";
				echo stripslashes($image_row[description]) . "<P>\n";
	
				}
			
			
			print "</font></td><td valign=top><table valign=top>";
			
			
			
			//strip slashes so input appears correctly
			$a_row[agent] = stripslashes ($a_row[agent]);
			$a_row[notes] = stripslashes ($a_row[notes]);
			
			print "<table border=0 cellspacing=0 cellpadding=0 width=580><tr><td valign=top>";
 			print "<font face=\"arial,ms sans serif\" size=3><b>$a_row[agent]</b></font>";
 			print "</td></tr></table><P>";

			
			print "<table border=0 cellpadding=3 align=left>";
			print "<tr><td align=right>Phone:</td><td align=left> $a_row[agentphone]</td></tr>";
			if ($a_row[agentcell] != "") { print "<tr><td align=right>Mobile:</td><td align=left> $a_row[agentcell]</td></tr>";}
			if ($a_row[agentfax] != "") { print "<tr><td align=right>Fax:</td><td align=left> $a_row[agentfax]</td></tr>";}
			print "<tr><td align=right>Email:</td><td align=left><a href=\"mailto:$a_row[agentemail]\">$a_row[agentemail]</a></td></tr>";
			
			print "<tr><td align=right>About Me:</td><td align=left> $a_row[notes]<P></td></tr>";
			print "<tr><td align=right><P></td><td align=left><P></td></tr>";
			if ($a_row[agenturl] != "") { print "<tr><td align=right></td><td align=left><a href=\"$a_row[agenturl]\">View my Homepage</a></td></tr>";}
			print "<tr><td align=right></td><td align=left><a 
href=\"./carview.php?owner=$view\">View my Listings</a></td></tr>";
			
			print "</table>";

			
			print "<P>";
			}
			
	
			
		//print the footer

		print"\r\n<!-- THUS ENDETH THE MAIN CONTENT -->\r\n<!-- HERE BEGINNETH THE FOOTER -->";
		include("./templates/user_bottom.html");
		
		//gots to close the mysql connection
		mysql_close($link);
?>
		
