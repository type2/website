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
			
			
	//print the header
	print "<!-- HERE BEGINNETH THE HEADER -->\r\n";		
	include("./templates/user_top.html");
	
	$result = mysql_query("SELECT * FROM agents WHERE agentemail='$email';",$link);
	while ($a_row =mysql_fetch_array ($result) )
		{
		
	
		$subject = "Type2.com BuySell Login and Password";
		$fromName = $yourname;
		$fromAddres = $youremail;
		$message = "Your login name is: $a_row[agent]\r\nYour password is: $a_row[agentpass]\r\n";
		
		$temp = mail($email, $subject, $message, "From: ".$fromName." <".$fromAddress.">") or print "Could not send mail.<BR><BR><P>";
		if ($temp = true) {print "The login information has been sent<P> ";}
		
		}
	
	
	//print the footer
	print"\r\n<!-- THUS ENDETH THE MAIN CONTENT -->\r\n<!-- HERE BEGINNETH THE FOOTER -->";
	include("./templates/user_bottom.html");
	
	//gots to close the mysql connection
	mysql_close($link);
?>
