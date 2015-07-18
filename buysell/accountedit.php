<?php
//DON'T MESS WITH ANY OF THIS!
session_start();
include("common.php");

		$link = mysql_connect ($server, $user, $password);
		if (! $link)
			{
			die ("Couldn't connect to mySQL server");
			}
		if (!mysql_select_db ($db, $link) )
			{
			die ("Coldn't open $db: ".mysql_error() );
			}


if ($current_user == "" AND $agentname == "")
	{

	
	include("./templates/user_top.html");
	print "<P><form action=\"./accountedit.php\" method=\"post\">Please enter your user name and password:<P>";
	print "Login Name: <input type=text name=agentname><P> ";
	print "Password: <input type=password name=agentpassword><P><input type=submit value=\"Log In\"></form><P>";
	
	print "<form action=\"emailpass.php\">Enter your address to get your password emailed to you:<BR><input type=text name=email><P><input type=submit></form><P>";
	include("./templates/user_bottom.html");
	exit;

 } elseif ($agentname != "" OR $current_user != "")
	{


	$sql = "SELECT id 
		FROM agents 
		WHERE agent='$agentname' 
		and agentpass='$agentpassword'";

	$result = mysql_query($sql)
		or die("Couldn't execute query.");

	$num = mysql_numrows($result);

	if ($num == 1) {
		session_register("agentname");
		session_register("agentpassword");
		
		echo "<!-- USER : $agentname -->\r\n";
		print "<!-- Your session ID is ".session_id()."-->\r\n";
		
		session_register("current_user");
		session_register("agent");
		session_register("agenturl");
		session_register("agentemail");
		
		
		//COLLECT INFORMATION ABOUT THE CURRENT USER
		$result = mysql_query("SELECT * FROM agents WHERE agent='$agentname';",$link);
		while ($a_row =mysql_fetch_array ($result) )
		{
			$current_user = $a_row[id];
			$agent = $a_row[agent];
			$agenturl = $a_row[agenturl];
			$agentemail = $a_row[agentemail];
		
		}
		
		
		print "<!-- You are user #$current_user -->\r\n\r\n";
		
		
		

	} else if ($num == 0)  {
		include("./templates/user_top.html");
		echo 'That login/password combination is incorrect.<P>';
		print "<P><form action=\"./accountedit.php\" method=\"post\">Please enter your user name and password:<P>";
		print "Login Name: <input type=text name=agentname><P> ";
		print "Password: <input type=password name=agentpassword><P><input type=submit value=\"Log In\"></form><P>";
		
		print "<form action=\"emailpass.php\">Enter your address to get your password emailed to you:<BR><input type=text name=email><P><input type=submit></form><P>";
		include("./templates/user_bottom.html");
		exit;

	}

}


	//print the header
	print "<!-- HERE BEGINNETH THE HEADER -->\r\n";
	include("./templates/user_top.html");
	
	
	//beginning of status area
	print "<!-- Beginning of Status Area -->";
	print "<table border=1 width=90% cellspacing=1 cellpadding=3><tr align=left><td>";
	
	
	//DELETE AN IMAGE
			
			if ($deleteimage != "")
				{
				$query = "DELETE FROM agent_tbl_Files WHERE ((id_files = $deleteimage) AND (owner='$current_user'))";
				if (!mysql_query ($query, $link) )
					{
					die (mysql_error());
					}
				print "$filename has been removed...";
				
				}


			if ($action == "upload")
				{
				if (isset($binFile) && $binFile != "none")
  					{
   					$data = addslashes(fread(fopen($binFile, "r"), filesize($binFile)));
    				$strDescription = addslashes(nl2br($txtDescription));
    				$sql = "INSERT INTO agent_tbl_Files ";
    				$sql .= "(description, bin_data, filename, filesize, filetype, owner, agentnum) ";
    				$sql .= "VALUES ('$strDescription', '$data', ";
    				$sql .= "'$binFile_name', '$binFile_size', '$binFile_type', '$current_user', '$current_user')";
    	
    				if (!mysql_query ($sql, $link) )
						{
						die (mysql_error());
						}
    	
    				echo "Your image has been added ($binFile_name).";
    
  					}
				}
	
	

	
	
	//MODIFY A RECORD
	if ($modify != "")
		{
		print "editing field $modify...";
			
		//add slashes to input so things don't get fucked up in mySQL	
		$agent = addslashes($agent);
		$notes = addslashes($notes);
		
		//formats the description text, if necessary
		if ($linefeeds == "Y")
			{
			$notes = ereg_replace("(\r\n|\n|\r)", "<br>", $notes);
			}
				
		$query = "UPDATE agents SET agent = '$agent', agentpass = '$agentpass', agenturl = '$agenturl', agentemail = '$agentemail', notes = '$notes', agentphone = '$agentphone', agentcell = '$agentcell', agentfax = '$agentfax' WHERE id='$current_user'";

		if (!mysql_query ($query, $link) )
			{
			die (mysql_error());
			}
		print "Agent #$modify has been updated...";
		}
	
	
		
		
	//THUS ENDS THE STATUS AREA...	
	
		
	//begin main content
	
	
	print "</td></tr></table><P>";
	print "<!-- End of Status Area -->\r\n";
	Print "<center><A HREF=\"./agentdisplay.php?view=$current_user\">view my agent page</a></center><P>";

		$result = mysql_query("SELECT * FROM agents WHERE id='$current_user';",$link);
		while ($a_row =mysql_fetch_array ($result) )
			{
			
				
			//select images connected to a given listing
			$query = "SELECT * FROM agent_tbl_Files WHERE agentnum = $current_user";
			$result = mysql_query("$query",$link);
			$num_images = 0;
			while ($image_row =mysql_fetch_array ($result) )
				{
				
				echo "<P> \n";
				echo "<B>$image_row[filename]</b><BR>\n";
				echo "$image_row[filetype] (Size $image_row[filesize])<P>\n";
				echo "<a href='agent_image.php?Id=$image_row[id_files]' target=\"_new\"><img src='agent_image.php?Id=$image_row[id_files]' border=0 width=100 alt='Click to Enlarge'></a><BR>";
				echo stripslashes($image_row[description]) . "<P>\n";
	
				echo "<a href=\"./accountedit.php?deleteimage=$image_row[id_files]&filename=$image_row[filename]&edit=$current_user\">delete image</a><P><HR><B>";
				$num_images++;
				}
			
			
			
			
			
			
			//strip slashes so input appears correctly
			$a_row[agent] = stripslashes ($a_row[agent]);
			$a_row[notes] = stripslashes ($a_row[notes]);
			
			//format description fields appropriately
			if ($linefeeds == "Y")
				{
				$a_row[notes] = ereg_replace("<br>", "\r\n", $a_row[notes]);
				}
			
			print "<table border=0 cellspacing=0 cellpadding=0 width=580><tr><td>";
 			print "<font face=\"arial,ms sans serif\" size=3><b>Edit Agent</b></font>";
 			print "</td></tr></table><P>";

			print "<form name=\"addagent\" action=\"./accountedit.php?modify=$a_row[id]&edit=$current_user\" method=post>";
			print "<table width=580 border=0 cellpadding=3>";
			print "<tr><td align=right>Name:</td><td align=left> $a_row[agent]</td></tr>";
			print "<tr><td align=right>password:</td><td align=left> <input type=\"text\" name=\"agentpass\" value=\"$a_row[agentpass]\"></td></tr>";
			
			print "<tr><td align=right>Phone:</td><td align=left> <input type=\"text\" name=\"agentphone\" value=\"$a_row[agentphone]\"></td></tr>";
			print "<tr><td align=right>Mobile:</td><td align=left> <input type=\"text\" name=\"agentcell\" value=\"$a_row[agentcell]\"></td></tr>";
			print "<tr><td align=right>Fax:</td><td align=left> <input type=\"text\" name=\"agentfax\" value=\"$a_row[agentfax]\"></td></tr>";
			
			
			print "<tr><td align=right>Homepage:</td><td align=left> <input type=\"text\" name=\"agenturl\" value=\"$a_row[agenturl]\"></td></tr>";
			print "<tr><td align=right>Email:</td><td align=left> <input type=\"text\" name=\"agentemail\" value=\"$a_row[agentemail]\"> ";
			print "<tr><td align=right>Notes:</td><td align=left> <textarea name=\"notes\" rows=4 cols=80>$a_row[notes]</textarea></td></tr>";
			print "</table>";

			
			print "<center><P>";
			print "<input type=submit></form></center>";
			
			
			//
			if ($num_images < $max_agent_images)
				{
				print "<CENTER><B>Manage Images</b></center><P>";
    print "<B>IMPORTANT: This is for a picture of <u>you</u>, or your shop, not of the vehicle or part you are selling!</B><P>";
    print "To work with the pictures of the item you are selling, go <a href=\"agentadmin.php\">here</a>.<P>";
				print "Note: There is a limit of $max_agent_images image(s) of at most $max_agent_upload bytes each per seller.<P>";
				print"<FORM METHOD=\"post\" ACTION=\"./accountedit.php\" ENCTYPE=\"multipart/form-data\">";
				print"<INPUT TYPE=\"hidden\" NAME=\"MAX_FILE_SIZE\" VALUE=\"$max_agent_upload\">";
				print"<INPUT TYPE=\"hidden\" NAME=\"agentnum\" VALUE=\"$a_row[id]\">";
				print"<INPUT TYPE=\"hidden\" NAME=\"edit\" VALUE=\"$a_row[id]\">";
				print"<INPUT TYPE=\"hidden\" NAME=\"action\" VALUE=\"upload\">";
					print"<TABLE BORDER=\"0\" cellpadding=3>";
						print"<TR>";
  					 		print"<TD>Title: </TD>";
 	  						print"<TD><INPUT NAME=\"txtDescription\" COLS=\"50\"></TD>";
	  					print"</TR>";
  						print"<TR>";
				  	 		print"<TD>File: </TD>";
 					  		print"<TD><INPUT TYPE=\"file\" NAME=\"binFile\"></TD>";
	 				 	print"</TR>";
  						print"<TR>";
  	 						print"<TD COLSPAN=\"2\"><INPUT TYPE=\"submit\" VALUE=\"Upload\"></TD>";
 	 					print"</TR>";
					print"</TABLE>";

				print"</FORM>";
				print "</center></td></tr></table>";
				}
			else
				{
				print "<CENTER><B>Maximum number of images added</b></center>";
				}
			
			}
			
			
			

		//print the footer
		print"\r\n<!-- THUS ENDETH THE MAIN CONTENT -->\r\n<!-- HERE BEGINNETH THE FOOTER -->";
		include("./templates/user_bottom.html");
		mysql_close($link);
?>
	
	
	
	

