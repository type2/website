<?php
session_start();
include("../common.php");

 //login authorization code
if ($admin_user != $openadmin)
    {
	print "<P><form action=\"./configurator.php\" method=\"post\">Please enter your user name and password:<P>";
	print "Login Name: <input type=text name=admin_user><P> ";
	print "Password: <input type=text name=admin_password><P><input type=submit value=\"Log In\"></form><P>";
	die("Enter the correct username");
	}
if ($admin_password != $openpassword)
    {
    print "<P><form action=\"./configurator.php\" method=\"post\">Please enter your user name and password:<P>";
	print "Login Name: <input type=text name=admin_user><P> ";
	print "Password: <input type=text name=admin_password><P><input type=submit value=\"Log In\"></form><P>";
    die("Enter the correct password");
    }
    
session_register("admin_user");
session_register("admin_password");


//set up SQL connection
	print "Connecting to DB....<BR>";
	$link = mysql_connect ("localhost", $user, $password);
		if (! $link)
			{
			die ("Couldn't connect to mySQL server");
			}
		if (!mysql_select_db ($db, $link) )
			{
			die ("Coldn't open $db: ".mysql_error() );
			}
	Print "Connected!<BR>";
		
		//build tables:
		
		print "Adding table vehicles:<BR>";
		$querystring = "CREATE TABLE vehicles (id int(11) NOT NULL auto_increment,title varchar(250),city varchar(50),state varchar(20),zip varchar(20),price int(11),fulldesc text NOT NULL,type varchar(20),featured char(1),dateposted date,agent varchar(30), notes mediumtext NOT NULL,owner int(11) DEFAULT '0' NOT NULL,model varchar(30) NOT NULL, make varchar(30) NOT NULL,year year(4) DEFAULT '0000' NOT NULL,drivetrain varchar(30) NOT NULL,transmission varchar(30) NOT NULL,body varchar(30) NOT NULL,doors int(11) DEFAULT '0' NOT NULL,features text NOT NULL,stereo varchar(30) NOT NULL,color varchar(30) NOT NULL,miles int(11) DEFAULT '0' NOT NULL,opt1 char(1) DEFAULT 'N' NOT NULL,opt2 char(1) DEFAULT 'N' NOT NULL,opt3 char(1) DEFAULT 'N' NOT NULL,opt4 char(1) DEFAULT 'N' NOT NULL,opt5 char(1) DEFAULT 'N' NOT NULL,opt6 char(1) DEFAULT 'N' NOT NULL,opt7 char(1) DEFAULT 'N' NOT NULL,opt8 char(1) DEFAULT 'N' NOT NULL,opt9 char(1) DEFAULT 'N' NOT NULL,opt10 char(1) DEFAULT 'N' NOT NULL,PRIMARY KEY (id));"; 
		print $querystring;
		print "<BR>";
		$result = mysql_query("$querystring");
		if (!$result) print mysql_error();
		print "Created table in $db<BR>";
		
		
		print "Adding table agents:<BR>";
		$querystring = "CREATE TABLE agents (id int NOT NULL AUTO_INCREMENT, PRIMARY KEY (id), agent VARCHAR (30), agentpass VARCHAR (10), agenturl VARCHAR (70), agentemail VARCHAR (70), notes TEXT, agentphone VARCHAR(30), agentcell VARCHAR(30), agentfax VARCHAR (30) );"; 
		print $querystring;
		print "<BR>";
		$result = mysql_query("$querystring");
		if (!$result) print mysql_error();
		print "Created table in $db<BR>";
		
		print "Adding table agent_tbl_Files:<BR>";
		$querystring = "CREATE TABLE agent_tbl_Files (id_files int not null auto_increment, PRIMARY KEY (id_files), bin_data longblob not null, description tinytext, filename VARCHAR (50), filesize VARCHAR (50), filetype VARCHAR (50), agentnum int, owner int );"; 
		print $querystring;
		print "<BR>";
		$result = mysql_query("$querystring");
		if (!$result) print mysql_error();
		print "Created table in $db<BR>";
		
		
		print "Adding table tbl_Files:<BR>";
		$querystring = "CREATE TABLE tbl_Files (id_files int not null auto_increment, PRIMARY KEY (id_files), bin_data longblob not null, description tinytext, filename VARCHAR (50), filesize VARCHAR (50), filetype VARCHAR (50), prop_num int, owner int);"; 
		print $querystring;
		print "<BR>";
		$result = mysql_query("$querystring");
		if (!$result) print mysql_error();
		print "Created table in $db<BR>";
		
	
	
		print "<P><B>Installation Succeeded! (If you saw any errors, you might want to check the readme)</b><P>";
		
		Print "<a href=\"../index.php\">Load OpenAutoClassifies</a>";
		
?>