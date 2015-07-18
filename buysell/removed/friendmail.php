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
			
//set up the page
print "<!-- HERE BEGINNETH THE HEADER -->\r\n";
include("./templates/user_top.html");

	if ($listing != "")
		{
		if ($action == "mail")
			{
			$subject = "Auto listing from $sender";
			$fromName = $yourname;
			$fromAddres = $youremail;
			$message = "Your friend, $sender, has sent along the following real estate link\r\n$baseurl/carview.php?view=$listing\r\n\r\n$comment";
			$message = stripslashes($message);
			$temp = mail($to, $subject, $message, "From: ".$fromName." <".$fromAddress.">") or print "Could not send mail.<BR><HR><BR><P>";
			if ($temp = true) {print "The listing has been sent<P><a href=\"$baseurl/carview.php?view=$listing\">Return to listing $listing</a>   ";}
			}
		else
			{
			print "<B>Send listing $listing to a friend...</b><P>";
			print "<form name=\"mailman\" action=\"./friendmail.php?listing=$listing&action=mail\" method=\"post\"> ";
			print "<table border=0 cellpadding=0 cellspacing=0>";
			print "<tr><td width=120 align=center>Friend's Email:</td><td align=left><input type=text name=\"to\"></td></tr>";
			print "<tr><td width=120 align=center>Your name:</td><td align=left><input type=text name=\"sender\"></td></tr>";
			print "<tr><td width=120 align=center>Your message:</td><td align=left><textarea name=\"comment\" cols=60 rows=4></textarea></td></tr>";
			print "<tr><td></td><td align=middle><input type=submit value=\"Send\"></td></tr>";
			print "</table></form>";
			}
		}

		//print the footer

		print"\r\n<!-- THUS ENDETH THE MAIN CONTENT -->\r\n<!-- HERE BEGINNETH THE FOOTER -->";
		include("./templates/user_bottom.html");
		
		//gots to close the mysql connection
		mysql_close($link);
?>
