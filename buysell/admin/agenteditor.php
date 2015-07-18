<?php
//DON'T MESS WITH ANY OF THIS!
session_start();
include("../common.php");

 //login authorization code
if ($admin_user != $openadmin)
    {
	print "<P><form action=\"./agenteditor.php\" method=\"post\">Please enter your user name and password:<P>";
	print "Login Name: <input type=text name=admin_user><P> ";
	print "Password: <input type=password name=admin_password><P><input type=submit value=\"Log In\"></form><P>";
	die("Enter the correct username");
	}
if ($admin_password != $openpassword)
    {
    print "<P><form action=\"./agenteditor.php\" method=\"post\">Please enter your user name and password:<P>";
	print "Login Name: <input type=text name=admin_user><P> ";
	print "Password: <input type=password name=admin_password><P><input type=submit value=\"Log In\"></form><P>";
    die("Enter the correct password");
    }
    
session_register("admin_user");
session_register("admin_password");



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
include("./admin_upper.html");
?>


		<table border=0 cellspacing=0 cellpadding=0 width=580>
 			<tr>
 				<td>
 					<font face="arial,ms sans serif" size=3><b>Edit BuySell Users</b></font>
 				</td>
 			</tr>
		</table>
	<P>
	<table width=580 border=1 cellspacing=1 cellpadding=3>
	<tr align=left><td>
	
	<?
	
	//DELETE AN IMAGE
			
			if ($deleteimage != "")
				{
				$query = "DELETE FROM agent_tbl_Files WHERE (id_files = $deleteimage)";
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
    				$sql .= "'$binFile_name', '$binFile_size', '$binFile_type', '$agentnum', '$agentnum')";
    	
    				if (!mysql_query ($sql, $link) )
						{
						die (mysql_error());
						}
    	
    				echo "Your image has been added ($binFile_name).";
    
  					}
				}
	
	
	//DELETE A RECORD
	if ($delete != "")
		{

		
		$query = "DELETE FROM agents WHERE id = '$delete'";
		if (!mysql_query ($query, $link) )
			{
			die (mysql_error());
			}
		print "Agent #$delete has been removed...";
		
		$query = "DELETE FROM agent_tbl_Files WHERE (owner = $delete)";
				if (!mysql_query ($query, $link) )
					{
					die (mysql_error());
					}
				print "Image for agent #$delete has also been removed...";
		
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
				
		$query = "UPDATE agents SET agent = '$agent', agentpass = '$agentpass', agenturl = '$agenturl', agentemail = '$agentemail', notes = '$notes', agentphone = '$agentphone', agentcell = '$agentcell', agentfax = '$agentfax' WHERE id='$modify'";

		if (!mysql_query ($query, $link) )
			{
			die (mysql_error());
			}
		print "Agent #$modify has been updated...";
		}
	
	//ADD A RECORD
	if ($action=="add")
		{

		
		//add slashes to input so things don't get fucked up in mySQL	
		$agent = addslashes($agent);
		$notes = addslashes($notes);
		
		
		$query = "INSERT INTO agents (agent, agentpass, agenturl, agentemail, notes, agentphone, agentcell, agentfax) values ('$agent', '$agentpass', '$agenturl', '$agentemail', '$notes', '$agentphone', '$agentcell', '$agentfax')";
  
  		
  
		if (!mysql_query ($query, $link) )
			{
			die (mysql_error());
			}
		print "The agent has been added...";
		}
		
		
	//THUS ENDS THE STATUS AREA...	
	?>
	
	
	</td></tr>
	</table><P>

	
	
	
	
	
	<?
	if ($edit != "")
		{
		
	
		
		
		$result = mysql_query("SELECT * FROM agents WHERE id='$edit';",$link);
		while ($a_row =mysql_fetch_array ($result) )
			{
			
			
			
				
			//select images connected to a given listing
			$query = "SELECT * FROM agent_tbl_Files WHERE agentnum = $edit";
			$result = mysql_query("$query",$link);
			$num_images = 0;
			while ($image_row =mysql_fetch_array ($result) )
				{
				
				echo "<P> \n";
				echo "<B>$image_row[filename]</b><BR>\n";
				echo "$image_row[filetype] (Size $image_row[filesize])<P>\n";
				echo "<a href='../agent_image.php?Id=$image_row[id_files]' target=\"_new\"><img src='../agent_image.php?Id=$image_row[id_files]' border=0 width=100 alt='Click to Enlarge'></a><BR>";
				echo stripslashes($image_row[description]) . "<P>\n";
	
				echo "<a href=\"./agenteditor.php?deleteimage=$image_row[id_files]&filename=$image_row[filename]&edit=$edit\">delete image</a><P><HR><B>";
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

			print "<form name=\"addagent\" action=\"./agenteditor.php?modify=$a_row[id]&edit=$a_row[id]\" method=post>";
			print "<table width=580 border=0 cellpadding=3>";
			print "<tr><td align=right>Name:</td><td align=left> <input type=\"text\" name=\"agent\" value=\"$a_row[agent]\"></td></tr>";
			print "<tr><td align=right>Password:</td><td align=left> <input type=\"text\" name=\"agentpass\" value=\"$a_row[agentpass]\"></td></tr>";
			
			print "<tr><td align=right>Phone:</td><td align=left> <input type=\"text\" name=\"agentphone\" value=\"$a_row[agentphone]\"></td></tr>";
			print "<tr><td align=right>Mobile:</td><td align=left> <input type=\"text\" name=\"agentcell\" value=\"$a_row[agentcell]\"></td></tr>";
			print "<tr><td align=right>Fax:</td><td align=left> <input type=\"text\" name=\"agentfax\" value=\"$a_row[agentfax]\"></td></tr>";
			
			
			print "<tr><td align=right>Homepage:</td><td align=left> <input type=\"text\" name=\"agenturl\" value=\"$a_row[agenturl]\"></td></tr>";
			print "<tr><td align=right>Email:</td><td align=left> <input type=\"text\" name=\"agentemail\" value=\"$a_row[agentemail]\"> ";
			print "<tr><td align=right>Notes:</td><td align=left> <textarea name=\"notes\" rows=4 cols=80>$a_row[notes]</textarea></td></tr>";
			print "</table>";

			
			print "<P>";
			print "<input type=submit></form>";
			
			
			
			if ($num_images < $max_agent_images)
				{
				print "<CENTER><B>Manage Images</b></center><P>";
				print "<B>IMPORTANT: This is for a picture of <u>you</u>, or your shop, not of the vehicle or part you are selling!</B><P>";
				print "To work with the pictures of the item you are selling, go <a href=\"agentadmin.php\">here</a>.<P>";
    print "Note: There is a limit of $max_agent_images image(s) of at most $max_agent_upload bytes each per ad.<P>";
				print"<FORM METHOD=\"post\" ACTION=\"agenteditor.php\" ENCTYPE=\"multipart/form-data\">";
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
		}		
		
		
		
	
	elseif ($action == "addagent")
		{
			
		print "<table border=0 cellspacing=0 cellpadding=0 width=580><tr><td>";
 		print "<font face=\"arial,ms sans serif\" size=3><b>Add Agent</b></font>";
 		print "</td></tr></table><P>";

			print "<form name=\"addagent\" action=\"./agenteditor.php?action=add\" method=post>";
			print "<table width=580 border=0 cellpadding=3>";
			print "<tr><td align=right>Name:</td><td align=left> <input type=\"text\" name=\"agent\"></td></tr>";
			print "<tr><td align=right>Password:</td><td align=left> <input type=\"text\" name=\"agentpass\"></td></tr>";
			
			print "<tr><td align=right>Phone:</td><td align=left> <input type=\"text\" name=\"agentphone\"></td></tr>";
			print "<tr><td align=right>Mobile:</td><td align=left> <input type=\"text\" name=\"agentcell\"></td></tr>";
			print "<tr><td align=right>Fax:</td><td align=left> <input type=\"text\" name=\"agentfax\"></td></tr>";
			
			print "<tr><td align=right>Homepage:</td><td align=left> <input type=\"text\" name=\"agenturl\"></td></tr>";
			print "<tr><td align=right>Email:</td><td align=left> <input type=\"text\" name=\"agentemail\"> ";
			print "<tr><td align=right>Notes:</td><td align=left> <textarea name=\"notes\" rows=4 cols=80></textarea></td></tr>";
			print "</table>";

			
			print "<P>";
			print "<input type=submit></form>";
		}
	
	
	//show all listings
			else
			{				
			$result = mysql_query("SELECT * FROM agents",$link);
			
			$num_rows = mysql_num_rows($result);
			Print "<table border=0 cellspacing=0 cellpadding = 2 width=580><TR><TD align=left>There are currently <B>$num_rows agents</b> listed.<BR>";
			
			
			
			if ($cur_page == "") {$cur_page = 0;}
			$page_num = $cur_page + 1;
			$total_num_page = ceil($num_rows/10);
		
			print "<Center>";
		
		
		
			if ($total_num_page != 0)
				{
				Print "This is page $page_num of $total_num_page<BR>";
		
				$prevpage = $cur_page-1;
				$nextpage = $cur_page+1;
				if ($page_num != 1){print "<a href=\"./agenteditor.php?cur_page=$prevpage\">Previous Page</a>     ";}
				if ($page_num != $total_num_page){print "  <a href=\"./agenteditor.php?cur_page=$nextpage\">Next Page</a>     ";}
				}
			
			print "</td></tr></table>";
		
			$limit_str = "LIMIT ". $cur_page * 10 .",10";
			
			$result = mysql_query("SELECT * FROM agents $limit_str",$link);
			
			while ($a_row =mysql_fetch_array ($result) )
				{
				
				//strip slashes so input appears correctly
				$a_row[agent] = stripslashes ($a_row[agent]);
				$a_row[notes] = stripslashes($a_row[notes]);

				
				print "<P><table width=580 border=0 cellpadding=3>";
				print "<tr bgcolor=\"black\"><td align=right width=150><B><font size=3 color=\"white\">agent number: $a_row[id]</b></font></td><td align=center bgcolor=\"#CCCCCC\" width=310> <B> <a href=\"./agenteditor.php?edit=$a_row[id]\">modify agent</a></b></td><td width=120 align=middle bgcolor=\"#CCCCCC\"><a href=\"./agenteditor.php?delete=$a_row[id]\">delete agent</a></td></tr>";	
				print "<tr><td align=center valign=middle>$a_row[agent]";
				print "</td><td>$a_row[notes]</td><td></td>";
			
				print "</table>\r\n\r\n";
	
				}
			}
	

include("./admin_lower.html");

//gots to close the mysql connection
mysql_close($link);
?>
	
	
	
	

