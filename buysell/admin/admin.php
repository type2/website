<?php
//DON'T MESS WITH ANY OF THIS!
session_start();
include("../common.php");



 //login authorization code
if ($admin_user != $openadmin)
    {
	print "<P><form action=\"./admin.php\" method=\"post\">Please enter your user name and password:<P>";
	print "Login Name: <input type=text name=admin_user><P> ";
	print "Password: <input type=password name=admin_password><P><input type=submit value=\"Log In\"></form><P>";
	die("Enter the correct username");
	}
if ($admin_password != $openpassword)
    {
    print "<P><form action=\"./admin.php\" method=\"post\">Please enter your user name and password:<P>";
	print "Login Name: <input type=text name=admin_user><P> ";
	print "Password: <input type=password name=admin_password><P><input type=submit value=\"Log In\"></form><P>";
    die("Enter the correct password");
    }
    
session_register("admin_user");
session_register("admin_password");
  
  //setup the database
  $link = mysql_connect ($server, $user, $password);
		if (! $link)
			{
			die ("Couldn't connect to mySQL server");
			}
		if (!mysql_select_db ($db, $link) )
			{
			die ("Coldn't open $db: ".mysql_error() );
			}
			
	//print the header
	include("./admin_upper.html");


	

	//ERRORS AND STATUS DISPLAY IN THE BOX
	
	//DELETE A RECORD
	if ($delete != "")
		{


		
		$query = "DELETE FROM vehicles WHERE id = '$delete'";
		if (!mysql_query ($query, $link) )
			{
			die (mysql_error());
			}
		print "Listing #$delete has been removed...";
		
		$query = "DELETE FROM tbl_Files WHERE prop_num = '$delete'";
			if (!mysql_query ($query, $link) )
				{
				die (mysql_error());
				}
			print "Images for property #$delete have also been removed...";
		}
	

	//DELETE AN IMAGE
			
			if ($deleteimage != "")
				{
				$query = "DELETE FROM tbl_Files WHERE id_files = $deleteimage";
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
    				$sql = "INSERT INTO tbl_Files ";
    				$sql .= "(description, bin_data, filename, filesize, filetype, owner, prop_num) ";
    				$sql .= "VALUES ('$strDescription', '$data', ";
    				$sql .= "'$binFile_name', '$binFile_size', '$binFile_type', '$current_user', '$propnum')";
    	
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
		$title = addslashes($title);
		$address = addslashes($address);
		$city = addslashes($city);
		$previewdesc = addslashes($previewdesc);
		$fulldesc = addslashes($fulldesc);
		$type = addslashes($type);
		$transmission = addslashes($transmission);
		$color = addslashes($color);
		$doors = addslashes($doors);
		$stereo = addslashes($stereo);
		$notes = addslashes($notes);
		
		
		//strip extra characters out of the price, miles, and year
		$price = ereg_replace("[^[:alnum:]]","",$price);
		$year = ereg_replace("[^[:alnum:]]","",$year);
		$miles = ereg_replace("[^[:alnum:]]","",$miles);



  			
  		

		//formats the description text, if necessary
		if ($linefeeds == "Y")
			{
			$previewdesc = ereg_replace("(\r\n|\n|\r)", "<br>", $previewdesc);
			$fulldesc = ereg_replace("(\r\n|\n|\r)", "<br>", $fulldesc);
			}
			
		
		$query = "UPDATE vehicles set title = '$title', city = '$city', state = '$state', zip = '$zip', price = '$price', fulldesc = '$fulldesc', type = '$type', featured = '$featured', agent = '$agent', notes = '$notes', owner = '$owner', model = '$model', make = '$make', year = '$year', drivetrain = '$drivetrain', transmission = '$transmission', body = '$body', doors = '$doors', features = '$features', stereo = '$stereo', color = '$color', miles = '$miles', opt1 = '$opt1', opt2 = '$opt2', opt3 = '$opt3', opt4 = '$opt4', opt5 = '$opt5', opt6 = '$opt6', opt7 = '$opt7', opt8 = 'opt8', opt9 = '$opt9', opt10 = '$opt10' WHERE id='$modify'";

		if (!mysql_query ($query, $link) )
			{
			die (mysql_error());
			}
		print "Listing #$modify has been updated...";
		}
	
	//ADD A RECORD
	if ($action=="add")
		{
		$dberror = "";
		

		
		//add slashes to input so things don't get fucked up in mySQL	
		$title = addslashes($title);
		$address = addslashes($address);
		$city = addslashes($city);
		$fulldesc = addslashes($fulldesc);
		$type = addslashes($type);
		$transmission = addslashes($transmission);
		$color = addslashes($color);
		$doors = addslashes($doors);
		$stereo = addslashes($stereo);
		$notes = addslashes($notes);
		
		
		//strip extra characters out of the price, miles, and year
		$price = ereg_replace("[^[:alnum:]]","",$price);
		$year = ereg_replace("[^[:alnum:]]","",$year);
		$miles = ereg_replace("[^[:alnum:]]","",$miles);

		//formats the description text, if necessary
		if ($linefeeds == "Y")
			{
			$previewdesc = ereg_replace("(\r\n|\n|\r)", "<br>", $previewdesc);
			$fulldesc = ereg_replace("(\r\n|\n|\r)", "<br>", $fulldesc);
			}
		
		$query = "INSERT INTO vehicles (title, city, state, zip, price, fulldesc, type, featured, dateposted, agent, notes, owner, model, make, year, drivetrain, transmission, body, doors, features, stereo, color, miles, opt1, opt2, opt3, opt4, opt5, opt6, opt7, opt8, opt9, opt10) values ('$title', '$city', '$state', '$zip', '$price', '$fulldesc', '$type', '$featured', '$dateposted', '$agent', '$notes', '$owner', '$model', '$make', '$year', '$drivetrain', '$transmission', '$body', '$doors', '$features', '$stereo', '$color', '$miles', '$opt1', '$opt2', '$opt3', '$opt4', '$opt5', '$opt6', '$opt7', '$opt8', '$opt9', '$opt10')";
  
		if (!mysql_query ($query, $link) )
			{
			die (mysql_error());
			}
		print "Your listing has been added...";
		}
		
		
	//THUS ENDS THE STATUS AREA...	
	?>
	
	
	</font></td></tr>
	</table><P>

<?php
	// THIS IS THE MAIN PROGRAM LOGIC
	
	if ($edit != "")
		//show a specific listing
		{

		session_register("propnum");
		
		print "<center><a href=\"../carview.php?view=$edit\">Preview Listing</a><BR></center>";
		
		$result = mysql_query("SELECT * FROM vehicles WHERE id='$edit';",$link);
		while ($a_row =mysql_fetch_array ($result) )
			{
			$propnum = $a_row[id];
			
			
			//strip slashes so input appears correctly
			$a_row[title] = stripslashes($a_row[title]);
			$a_row[city] = stripslashes($a_row[city]);
			$a_row[previewdesc] = stripslashes($a_row[previewdesc]);
			$a_row[fulldesc] = stripslashes($a_row[fulldesc]);
			$a_row[type] = stripslashes($a_row[type]);
			$a_row[transmission] = stripslashes($a_row[transmission]);
			$a_row[color] = stripslashes($a_row[color]);
			$a_row[doors] = stripslashes($a_row[doors]);
			$a_row[stereo] = stripslashes($a_row[stereo]);
			$a_row[notes] = stripslashes($a_row[notes]);
			$a_row[seller] = stripslashes($a_row[seller]);
			
			// added 6 Dec 2002 MCR	
			$a_row[dateposted] = stripslashes($a_row[dateposted]);
			// added 6 Dec 2002 MCR	

			//format description fields appropriately
			if ($linefeeds == "Y")
				{
				$a_row[previewdesc] = ereg_replace("<br>", "\r\n", $a_row[previewdesc]);
				$a_row[fulldesc] = ereg_replace("<br>", "\r\n", $a_row[fulldesc]);
				}
			
			
			print "<P><form name=\"addlisting\" action=\"./admin.php?modify=$a_row[id]&edit=$a_row[id]\" method=\"post\"><table width=580 border=0 cellpadding=3>";
			
			
			
			print "<table width=580 border=0 cellpadding=3>";
			print "<TR><TD width=120 valign=top align=center><font size=1 face=\"Arial,Helvetica,Geneva,Swiss,SunSans-Regular\">";
			
			//select images connected to a given listing
			$query = "SELECT * FROM tbl_Files WHERE prop_num = $propnum";
			$result = mysql_query("$query",$link);
			$num_images = 0;
			while ($image_row =mysql_fetch_array ($result) )
				{
				
				echo "<P> \n";
				echo "<B>$image_row[filename]</b><BR>\n";
				echo "$image_row[filetype] (Size $image_row[filesize])<P>\n";
				echo "<a href='../image.php?Id=$image_row[id_files]' target=\"_new\"><img src='../image.php?Id=$image_row[id_files]' border=0 width=100 alt='Click to Enlarge'></a><BR>";
				echo stripslashes($image_row[description]) . "<P>\n";
	
				echo "<a href=\"./admin.php?deleteimage=$image_row[id_files]&filename=$image_row[filename]&edit=$propnum\">delete image</a><P><HR><B>";
				$num_images++;
				}
			print "<table border=0 cellspacing=0 cellpadding=0 width=580><tr><td>";
			// changed 19 Mar 2003 MCR	
 			//print "<font face=\"arial,ms sans serif\" size=3><b>Add Listing</b></font>";
 			print "<font face=\"arial,ms sans serif\" size=3><b>Modify Listing</b></font>";
			// changed 19 Mar 2003 MCR	
 			print "</td></tr></table><P>";

			// removed 19 Mar 2003 MCR
			// seems to be spurious and may possibly be the cause of duplicate ads when you
			// try to make an ad "featured"
			//print "<form name=\"addlisting\" action=\"./admin.php?action=add\" method=post>";
			// removed 19 Mar 2003 MCR

			print "<table width=580 border=0 cellpadding=3>";

			// added 6 Dec 2002 MCR	
			print "<tr><td align=right>Date posted:</td><td align=left>$a_row[dateposted]</td></tr>";
			// added 6 Dec 2002 MCR	

			print "<tr><td align=right>Featured:</td><td align=left>";
			print "<input type=checkbox name=featured value=\"Y\" ";
			if ($a_row[featured] == "Y") {print "CHECKED";}
			print ">\r\n</td></tr>";
			
			print "<tr><td align=right>Ad Title:</td><td align=left> <input type=\"text\" name=\"title\" value=\"$a_row[title]\"></td></tr>";
			
			//is the $use_city_state option selected?
			if ($use_city_state == "Y") {
			print "<TR><TD> </td><td align=left><B>Seller Location</b></td></tr>";
			print "<tr><td align=right>City:</td><td align=left> <input type=\"text\" name=\"city\" value=\"$a_row[city]\"></td></tr>";
			print "<tr><td align=right>State:</td><td align=left>  ";
			print "<SELECT NAME=\"state\"> ";
			print "<option value=\"$a_row[state]\">$a_row[state]";
			print "<option value=\"\">-----";
			//deal with states
			$statesarray = explode("||", $stateslist);
  			while (list($IndexValue, $stateselect) = each ($statesarray))
  				{
  				
  				echo "<option value=\"$stateselect\">$stateselect";
  				}
 
			print "</select>";
			print "</td></tr>";
			print "<tr><td align=right>Zip:</td><td align=left> <input type=\"text\" name=\"zip\" value=\"$a_row[zip]\"></td></tr>";
			}
			
			print "<TR><TD> </td><td align=left><B>Car Information</b></td></tr>";
			
			print "<tr><td align=right>Make:</td><td align=left>";
			print "<SELECT NAME=\"make\">";
			print "<option value=\"$a_row[make]\">$a_row[make]";
			print "<option value=\"\">-----";
			//deal with car makes
			$carmakesarray = explode("||", $carmakeslist);
  			while (list($IndexValue, $makeselect) = each ($carmakesarray))
  				{
  				
  				echo "<BR><option value=\"$makeselect\">$makeselect";
  				}
			Print "</select></td></tr>";
			
			
			print "<tr><td align=right>Model:</td><td align=left> <input type=\"text\" name=\"model\"  value=\"$a_row[model]\"></td></tr>";
			
			
			
			
			print "<tr><td align=right>Doors:</td><td align=left> <input type=\"text\" name=\"doors\" value=\"$a_row[doors]\"></td></tr>";
			print "<tr><td align=right>Color:</td><td align=left> <input type=\"text\" name=\"color\" value=\"$a_row[color]\"></td></tr>";
			print "<tr><td align=right>Mileage:</td><td align=left> <input type=\"text\" name=\"miles\" value=\"$a_row[miles]\"></td></tr>";
			
			print "<tr><td align=right>Year:</td><td align=left> <input type=\"text\" name=\"year\" value=\"$a_row[year]\"></td></tr>";
			print "<tr><td align=right>Condition:</td><td align=left> <input type=\"text\" name=\"body\" value=\"$a_row[body]\"></td></tr>";
			
			
			print "<tr><td align=right>Transmission:</td><td align=left>";
			print "<SELECT NAME=\"transmission\"> ";
			print "<option value=\"$a_row[transmission]\">$a_row[transmission]";
			print "<option value=\"\">-----";
			print "<option value=\"Automatic\">Automatic";
			print "<option value=\"Manual\">Manual";
			print "<option value=\"\">-----";
			print "<option value=\"Other\">Other";
			print "</SELECT> ";
			print "</td></tr>";
			
			
			print "<tr><td align=right>Drive Train</td><td align=left>";
			print "<SELECT NAME=\"drivetrain\"> ";
			print "<option value=\"$a_row[drivetrain]\">$a_row[drivetrain]";
			print "<option value=\"\">-----";
			print "<option value=\"Front Wheel Drive\">Front Wheel Drive";
			print "<option value=\"Rear Wheel Drive\">Rear Wheel Drive";
			print "<option value=\"All Wheel Drive\">All Wheel Drive";
			print "<option value=\"Four Wheel Drive\">Four Wheel Drive";
			print "<option value=\"\">-----";
			print "<option value=\"Other\">Other";
			print "</SELECT> ";
			print "</td></tr>";
			
			
			print "<tr><td align=right>Stereo</td><td align=left> <input type=\"text\" name=\"stereo\" value=\"$a_row[stereo]\"></td></tr>";
			
			
			
			print "<tr><td align=right>Price:</td><td align=left> <input type=\"text\" name=\"price\" value=\"$a_row[price]\">.00</td></tr>";
			
			print "<tr><td align=right>Description:</td><td align=left> <textarea name=\"fulldesc\" rows=4 cols=80>$a_row[fulldesc]</textarea></td></tr>";
			
			print "<tr><td align=right>Type:</td><td align=left>  ";
			print "<SELECT NAME=\"type\"> ";
			print "<option value=\"$a_row[type]\">$a_row[type]";
			print "<option value=\"\">-----";
			//deal with car types
			$cartypesarray = explode("||", $cartypeslist);
  			while (list($IndexValue, $typeselect) = each ($cartypesarray))
  				{
  				
  				echo "<BR><option value=\"$typeselect\">$typeselect";
  				}
			Print "</select></td></tr>";
			
			//deal with features
			print "<tr><td align=right>Features:</td><td align=left>";
			$featuresarray = explode("||", $vehiclefeatureoptions);
  			while (list($IndexValue, $FeatureItem) = each ($featuresarray))
  				{
  				$realindex = $IndexValue+1; 
				$optnum = "opt".$realindex; 
  				echo "<BR><input type=checkbox name=\"$optnum\" value=\"Y\" ";
  				if ($a_row[$optnum] == 'Y') {echo "CHECKED";}
  				print ">$FeatureItem";
  				}
  			print "</td></tr>";
			
			
			
			print "<tr><td align=right>Seller:</td><td align=left> <input type=text value=\"$a_row[agent]\" name=agent></td></tr>";
			print "<tr><td align=right>Owner Number:</td><td align=left> <input type=text value=\"$a_row[owner]\" name=owner></td></tr>";		
			print "<tr><td align=right>Notes:<BR><font size=1>(Not visible to users)</font></td><td align=left> <textarea name=\"notes\" rows=4 cols=80>$a_row[notes]</textarea></td></tr>";
			

			print "<TR height=10><td></td><Td></td>";
			print "<tr><td></td><td align=left></td>";
			print "</table>";
			

		
			
			print "<P>";
			print "<input type=submit value=\"Update\"></form>";
			
			
			if ($num_images < $max_images)
				{
				print "<CENTER><B>Manage Images</b></center><P>";
				print "Note: There is a limit of $max_images images of at most $max_prop_upload bytes each per ad.<P>";
				print"<FORM METHOD=\"post\" ACTION=\"admin.php\" ENCTYPE=\"multipart/form-data\">";
				print"<INPUT TYPE=\"hidden\" NAME=\"MAX_FILE_SIZE\" VALUE=\"$max_prop_upload\">";
				print"<INPUT TYPE=\"hidden\" NAME=\"edit\" VALUE=\"$propnum\">";
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
		elseif ($action == "addlisting")
			//add a listing to the directory
			{
			
			
					
			
			print "<table border=0 cellspacing=0 cellpadding=0 width=580><tr><td>";
 			print "<font face=\"arial,ms sans serif\" size=3><b>Add Listing</b></font>";
 			print "</td></tr></table><P>";

			print "<form name=\"addlisting\" action=\"./admin.php?action=add\" method=post>";
			print "<table width=580 border=0 cellpadding=3>";
			print "<tr><td align=right>ad title:</td><td align=left> <input type=\"text\" name=\"title\"></td></tr>";
			
			//active only if $use_city_state option is turned on
			if ($use_city_state == "Y") {
			print "<TR><TD> </td><td align=left><B>Seller Location</b></td></tr>";
			print "<tr><td align=right>City:</td><td align=left> <input type=\"text\" name=\"city\"></td></tr>";
			print "<tr><td align=right>State:</td><td align=left>  ";
			print "<SELECT NAME=\"state\"> ";
			
			//deal with states
			$statesarray = explode("||", $stateslist);
  			while (list($IndexValue, $stateselect) = each ($statesarray))
  				{
  				
  				echo "<option value=\"$stateselect\">$stateselect";
  				}
			print "</select>";
			print "</td></tr>";
			print "<tr><td align=right>Zip:</td><td align=left> <input type=\"text\" name=\"zip\"></td></tr>";
			}
			
			print "<TR><TD> </td><td align=left><B>Car Information</b></td></tr>";
			
			
			print "<tr><td align=right>Manufacturer:</td><td align=left>";
			print "<SELECT NAME=\"make\">";
			//deal with car makes
			$carmakesarray = explode("||", $carmakeslist);
  			while (list($IndexValue, $makeselect) = each ($carmakesarray))
  				{
  				
  				echo "<BR><option value=\"$makeselect\">$makeselect";
  				}
			Print "</select></td></tr>";
			
			print "<tr><td align=right>Model:</td><td align=left> <input type=\"text\" name=\"model\"></td></tr>";
			
			
			
			print "<tr><td align=right>Doors:</td><td align=left> <input type=\"text\" name=\"doors\"></td></tr>";
			print "<tr><td align=right>Color:</td><td align=left> <input type=\"text\" name=\"color\"></td></tr>";
			print "<tr><td align=right>Mileage:</td><td align=left> <input type=\"text\" name=\"miles\"></td></tr>";
			
			print "<tr><td align=right>Year:</td><td align=left> <input type=\"text\" name=\"year\"></td></tr>";
			print "<tr><td align=right>Condition:</td><td align=left> <input type=\"text\" name=\"body\"></td></tr>";
			
			
			print "<tr><td align=right>Transmission:</td><td align=left>";
			print "<SELECT NAME=\"transmission\"> ";
			print "<option value=\"Automatic\">Automatic";
			print "<option value=\"Manual\">Manual";
			print "<option value=\"\">-----";
			print "<option value=\"Other\">Other";
			print "</SELECT> ";
			print "</td></tr>";
			
			
			print "<tr><td align=right>Drive Train</td><td align=left>";
			print "<SELECT NAME=\"drivetrain\"> ";
			print "<option value=\"Front Wheel Drive\">Front Wheel Drive";
			print "<option value=\"Rear Wheel Drive\">Rear Wheel Drive";
			print "<option value=\"All Wheel Drive\">All Wheel Drive";
			print "<option value=\"Four Wheel Drive\">Four Wheel Drive";
			print "<option value=\"\">-----";
			print "<option value=\"Other\">Other";
			print "</SELECT> ";
			print "</td></tr>";
			
			print "<tr><td align=right>Stereo</td><td align=left> <input type=\"text\" name=\"stereo\"></td></tr>";
			
			
			
			print "<tr><td align=right>Price:</td><td align=left> <input type=\"text\" name=\"price\">.00</td></tr>";
			print "<tr><td align=right>Description:</td><td align=left> <textarea name=\"fulldesc\" rows=4 cols=80></textarea></td></tr>";
			
			print "<tr><td align=right>Type:</td><td align=left>  ";
			print "<SELECT NAME=\"type\"> ";
			//deal with car types
			$cartypesarray = explode("||", $cartypeslist);
  			while (list($IndexValue, $typeselect) = each ($cartypesarray))
  				{
  				
  				echo "<BR><option value=\"$typeselect\">$typeselect";
  				}
			Print "</select></td></tr>";

			
			
			//deal with features
			print "<tr><td align=right>Features:</td><td align=left>";
			$featuresarray = explode("||", $vehiclefeatureoptions);
  			while (list($IndexValue, $FeatureItem) = each ($featuresarray))
  				{
  				$realindex = $IndexValue+1; 
				$optnum = "opt".$realindex; 
  				echo "<BR><input type=checkbox name=\"$optnum\" value=\"Y\">$FeatureItem";
  				}
  			print "</td></tr>";
  			
			
			print "<tr><td align=right>Seller:</td><td align=left><input type=text name=\"agent\"></td></tr>";
			print "<tr><td align=right>Seller Number:</td><td align=left><input type=text name=\"owner\"></td></tr>";				
			
			
			print "<tr><td align=right>Notes:<BR><font size=1>(Not visible to users)</font></td><td align=left> <textarea name=\"notes\" rows=4 cols=80></textarea></td></tr>";
			

			print "<TR height=10><td></td><Td></td>";
			print "<tr><td></td><td align=left></td>";
			print "</table>";
			

			
			print "<P>";
			print "<input type=submit></form>";
			print "<P><font size=1>Once you add a listing, you may attach images.<P></font>";
			}
		
		else
			//show all listings
			{
			
			
			$sql = "SELECT * FROM vehicles";
			$result = mysql_query($sql)
			or die("Couldn't execute query.");

			
			$num_rows = mysql_num_rows($result);
			
			Print "<table border=0 cellspacing=0 cellpadding = 2 width=580><TR><TD align=left>";
			
			
			
			if ($cur_page == "") {$cur_page = 0;}
			$page_num = $cur_page + 1;
			$total_num_page = ceil($num_rows/$listings_per_page);
			print "<Center>";
		
		
		
			if ($total_num_page != 0)
				{
				Print "This is page $page_num of $total_num_page<BR>";
		
				$prevpage = $cur_page-1;
				$nextpage = $cur_page+1;
				if ($page_num != 1){print "<a 
				href=\"./admin.php?cur_page=$prevpage&sort_str=$sort_str\">Previous 
				Page</a>     ";}
				if ($page_num != $total_num_page){print "  
				<a href=\"./admin.php?cur_page=$nextpage&sort_str=$sort_str\">Next Page</a>     ";}
				}
			
			// added 6 Dec 2002 MCR	
//			print "<br><a href=\"./admin.php?cur_page=$cur_page&sort_str=ORDER+BY+dateposted\"> 
			print "<br><a href=\"./admin.php?cur_page=$cur_page&sort_str=ORDER+BY+id\"> 
			Sort: Posted First</a>";
//			print "<br><a href=\"./admin.php?cur_page=$cur_page&sort_str=ORDER+BY+dateposted+DESC\"> 
			print "<br><a href=\"./admin.php?cur_page=$cur_page&sort_str=ORDER+BY+id+DESC\"> 
			Sort: Posted Last</a>";
			// added 6 Dec 2002 MCR	
			
			print "</td></tr></table>";
		
			// added 6 Dec 2002 MCR	
			if ($sort_str == "") { $sort_str = "ORDER BY id DESC"; }
			// added 6 Dec 2002 MCR	

			$limit_str = "LIMIT ". $cur_page * $listings_per_page .",$listings_per_page";

			
			$result = mysql_query("SELECT * FROM vehicles 
				$sort_str $limit_str  ",$link);

			while ($a_row =mysql_fetch_array ($result) )
				{
				
				//strip slashes so input appears correctly
				$a_row[$title] = stripslashes($a_row[$title]);
				$a_row[$address] = stripslashes($a_row[$address]);
				$a_row[city] = stripslashes($a_row[city]);
				$a_row[fulldesc] = stripslashes($a_row[fulldesc]);
				$a_row[type] = stripslashes($a_row[type]);
				$a_row[transmission] = stripslashes($a_row[transmission]);
				$a_row[color] = stripslashes($a_row[color]);
				$a_row[doors] = stripslashes($a_row[doors]);
				$a_row[stereo] = stripslashes($a_row[stereo]);
				$a_row[notes] = stripslashes($a_row[notes]);
				
				// added 6 Dec 2002 MCR	
				$a_row[dateposted] = stripslashes($a_row[dateposted]);
				// added 6 Dec 2002 MCR	

				print "<P><table width=580 border=0 cellpadding=3>";
				// added 6 Dec 2002 MCR	
//				print "<tr bgcolor=\"black\"><td align=right width=150><B><font size=3 color=\"white\">listing number: $a_row[id]</b></font></td><td align=center bgcolor=\"#CCCCCC\" width=310> <B> <a href=\"./admin.php?edit=$a_row[id]\">modify listing</a></b></td><td width=120 align=middle bgcolor=\"#CCCCCC\"><a href=\"./admin.php?delete=$a_row[id]\">delete listing</a></td></tr>";	
				// changed 27 Jan 2002 MCR	
//		print "<tr bgcolor=\"black\"><td align=right width=150><B><font size=3 color=\"white\">listing number: $a_row[id]</b></font></td><td align=right bgcolor=\"#CCCCCC\" width=100>$a_row[dateposted]</td><td align=center bgcolor=\"#CCCCCC\" width=210> <B> <a href=\"./admin.php?edit=$a_row[id]\">modify listing</a></b></td><td width=120 align=middle bgcolor=\"#CCCCCC\"><a href=\"./admin.php?delete=$a_row[id]\">delete listing</a></td></tr>";	
				print "<tr bgcolor=\"black\"><td align=right width=150><B><font size=3 color=\"white\">listing number: $a_row[id]</b></font></td><td align=right bgcolor=\"#CCCCCC\" width=100>$a_row[dateposted]</td><td align=center bgcolor=\"#CCCCCC\" width=210> <B> <a href=\"./admin.php?edit=$a_row[id]\">modify listing</a></b></td><td width=120 align=middle bgcolor=\"#CCCCCC\"><a href=\"./admin.php?delete=$a_row[id]&sort_str=$sort_str\">delete listing</a></td></tr>";	
				// changed 27 Jan 2002 MCR	
				// added 6 Dec 2002 MCR
				print "<tr><td align=center valign=middle>";
				
				
				//select images connected to a given listing
						$count = 0;
						$query = "SELECT * FROM tbl_Files WHERE prop_num = $a_row[id] LIMIT 1";
						$output = mysql_query("$query",$link);
						while ($image_row =mysql_fetch_array ($output) )
							{
				
							echo "<a href=\"./admin.php?edit=$a_row[id]\"><img src='../image.php?Id=$image_row[id_files]' width=100 border=1></a><BR>";
							$count++;
	
				
							}
						print "</font>";
						
						if ($count == 0)
     					 	{
     					 	print "<a href=\"./admin.php?edit=$a_row[id]\"><img src=\"../images/nophoto.gif\" border=1></a>";
     					 	}
     					 	
				
				// added 6 Dec 2002 MCR	
//				print "</td><td><B>$a_row[title]</b><P>$a_row[previewdesc]</td><td></td>";
				print "</td><td colspan=2><B>$a_row[title]</b><P>$a_row[previewdesc]</td><td></td>";
				// added 6 Dec 2002 MCR	
				print "</table>\r\n\r\n";
			}
		}
	

include("./admin_lower.html");

//gots to close the mysql connection
mysql_close($link);
?>
