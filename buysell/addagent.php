<?
//DON'T MESS WITH ANY OF THIS!
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

	//ADD A RECORD
	if ($action=="add")
		{
		
		
		//add slashes to input so things don't get fucked up in mySQL	
		$agent = addslashes($agent);
		$notes = addslashes($notes);
		
		$num_rows = 0;
		//make sure there isn't another person by that name
		$sql = "SELECT * FROM agents WHERE agent = '$agent'";
		$output = mysql_query($sql,$link);
		$num_rows = mysql_num_rows($output);
		if ($num_rows > 0)
			{
			print "There is already an agent by that name.  Please try another name.<P>";
			}
		elseif ($agent == "")
			{die ("<P>Please Enter A Name!<P><FORM><INPUT TYPE=\"BUTTON\" VALUE=\"BACK\" onClick=\"history.back()\"></FORM>");}
		elseif ($agentpass == "")
			{die ("<P>Please Enter A Password!<P><FORM><INPUT TYPE=\"BUTTON\" VALUE=\"BACK\" onClick=\"history.back()\"></FORM>");}
		elseif ($agentemail == "")
			{die ("<P>Please Enter an Email Address!<P><FORM><INPUT TYPE=\"BUTTON\" VALUE=\"BACK\" onClick=\"history.back()\"></FORM>");}
		
		else
			//success! Go ahead and add the account.
			{
			
			//handles the input for the database
			if ($linefeeds == "Y")
				{
				$notes = ereg_replace("(\r\n|\n|\r)", "<br>", $notes);
				}
		
			$query = "INSERT INTO agents (agent, agentpass, agenturl, agentemail, notes, agentphone, agentcell, agentfax) values ('$agent', '$agentpass', '$agenturl', '$agentemail', '$notes', '$agentphone', '$agentcell', '$agentfax')";
  			
  			if (!mysql_query ($query, $link) )
				{	
				die (mysql_error());
				}
			print "Your account has been added...";
			Print "<BR>Your login is: $agent";
			Print "<BR>Your password is $agentpass";
			Print "<P>Now, you may <a href=\"./agentadmin.php\">login</a> and manage your properties.";
			}
		}
		else
		{

		print "<table border=0 cellspacing=0 cellpadding=0 width=580><tr><td>";
 		print "<font face=\"arial,ms sans serif\" size=3><b>Create Agent Account</b></font>";
 		print "</td></tr></table><P>";
		Print "<font face=\"arial,ms sans serif\" size=2><P>";
			print "<form name=\"addagent\" action=\"./addagent.php?action=add\" method=post>";
			print "<table width=580 border=0 cellpadding=3>";
			print "<tr><td align=right><font color=red><B>*</b></font>Name:</td><td align=left> <input type=\"text\" name=\"agent\"></td></tr>";
			print "<tr><td align=right><font color=red><B>*</b></font>Password:</td><td align=left> <input type=\"password\" name=\"agentpass\"></td></tr>";
			print "<tr><td align=right><font color=red><B>*</b></font>Email:</td><td align=left> <input type=\"text\" name=\"agentemail\"> ";
			print "<tr height=5><td align=right></td><td align=left></td></tr>";
			
			print "<tr><td align=right>Phone:</td><td align=left> <input type=\"text\" name=\"agentphone\"></td></tr>";
			print "<tr><td align=right>Mobile:</td><td align=left> <input type=\"text\" name=\"agentcell\"></td></tr>";
			print "<tr><td align=right>Fax:</td><td align=left> <input type=\"text\" name=\"agentfax\"></td></tr>";
			
			print "<tr><td align=right>Homepage:</td><td align=left> <input type=\"text\" name=\"agenturl\"></td></tr>";
			print "<tr><td align=right>About you:</td><td align=left> <textarea name=\"notes\" rows=4 cols=80></textarea></td></tr>";
			print "<tr height=5><td align=right></td><td align=left></td></tr>";
			print "<tr><td align=right></td><td align=left><font size=2>(<font color=red><B>*</b></font> Required Field)</font></td></tr>";
			
			print "</table>";

			
			print "<P>";
			print "<input type=submit value=\"SAVE\"></form>";
			print "<font size=2>You can add images to your personal seller homepage once you create an account</font><BR>";
		}	
			


		//print the footer
		print"\r\n<!-- THUS ENDETH THE MAIN CONTENT -->\r\n<!-- HERE BEGINNETH THE FOOTER -->";
		include("./templates/user_bottom.html");
		mysql_close($link);

?>
