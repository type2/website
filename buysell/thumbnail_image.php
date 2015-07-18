<?php php_track_vars?>
<?php




if(!isset($Id))
{
    die("Need 'Id' parameter");
} else
{
    $Id=addslashes($Id);
}

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


$query = "SELECT filetype, bin_data FROM tbl_Files WHERE id_files='$Id';";
$result = mysql_query($query);
	

$query_data = mysql_fetch_array($result);
$bin_data = $query_data[bin_data];
$filetype = $query_data[filetype];



Header("Content-type: $filetype");
echo $bin_data;


?>
