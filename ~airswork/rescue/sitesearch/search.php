<html>
 <head>
  <title>PHPSiteSearch</title>
  <style>
<!--
BODY { font-family: arial; color: black; background:  white; }
A:LINK    { color: blue;   text-decoration: none; }
A:ACTIVE  { color: red;    text-decoration: underline; }
A:HOVER   { color: red;    text-decoration: underline; }
A:VISITED { color: purple; text-decoration: none; }
-->
  </style>
 </head>
 <body>
<?php
/////////////////////////////////////////////////////
//                                                 //
//                 PHPSiteSearch                   //
//                 Version 1.7.7                   //
//                                                 //
//                 Copyright 2002                  //
//                  David Broker                   //
//            http://php.warpedweb.net/            //
//              All Rights Reserved                //
//                                                 //
//          In using this script you               //
//           agree to the following:               //
//                                                 //
//      This script may be used and modified       //
//              freely as long as this             //
//             copyright remains intact.           //
//                                                 //
//     You may not distibute this script, or       //
//           any modifications of it.              //
//                                                 //
//   A link must be provided on the website that   //
//             uses the script to:                 //
//          http://php.warpedweb.net/              //
//                                                 //
//      Any breaches of these conditions           //
//        will result in legal action.             //
//                                                 //
//      This script is distributed with            //
//        no warrenty, free of charge.             //
//                                                 //
/////////////////////////////////////////////////////

// Variables

// Set your username and password. Leave blank if you don't want any protection.
       $user = "UserName";
       $pass = "Password";

// The full system path to the files you wish to index. DO NOT include trailing slash.
       $file_root = "c:\\html";

// The URL equivilent of the above. DO NOT include trailing slash.
       $http_root = "http://www.example.com";

// The full system path to the index file.
       $index_file = "c:\\html\\sitesearch\\search_index.dat";

// The full system path to the file of words to exclude.
       $exclude_words = "c:\\html\\sitesearch\\exclude_words.txt";

// The full system path to the file of files to exclude.
       $exclude_files = "c:\\html\\sitesearch\\exclude_files.txt";

/*******************************************************\
* ALL VARIALBES BELOW THIS POINT CAN REMAIN AS THEY ARE *
*     AND THE SCRIPT WILL STILL FUNCTION CORRECTLY      *
\*******************************************************/

// Set to true if you wish the time taken to be displayed when searching, false otherwise:

       $show_time_search = true;

// Displays time taken when indexing if true:

       $show_time_index = true;

// The array file file extentions to index. The extentions must be readable (no exe, jpg, gif)
       $include_extentions = array('html','htm','shtml','shtm', 'php', 'php3', 'phtml', 'php4', 'txt',);

// Message variables:
       $err_no_results = "No results were found. Please search again.";
       $err_no_search_db = "Could not find search database<br>Contact the administrator of this site.";
       $err_index = "The index file could not be opened.<br><i>Check the path and permissions and try again.</i>";
       $index_complete = "Your site has been indexed.";
       $index_cleared = "Your site index has been cleared.<br>No search results will be found until you re-index your site.";
       $no_files = "No files in the specified directory were available for indexing.";
       $no_dir = "The directory you specified cannot be found.";
       $err_index_empty = "The index file was found, but it contained no data.";

// Uncomment this line to debug the script.

//       DEFINE("DEBUG", true);

// Sets the version.
       $version = "1.7.7d";

// Sets start time.
$timeparts = explode(" ",microtime());
$starttime = $timeparts[1].substr($timeparts[0],1);

if (isset($query)) s_control($query);
elseif (substr($QUERY_STRING,0,5) == "index") i_control();
else s_print_search_form("");
c_print_footer();


function s_control($q) {
 $orig = $q;
 $result_arr = s_search($q);
 $result_count = sizeof($result_arr); 
 if ($result_count < 1) { 
  echo "<h4 align=\"center\">$GLOBALS[err_no_results]</h4>";
  s_print_search_form($q);
 }
 else {
  echo "
<h1>Search Results</h1>
<h3><i>Results: 1 - $result_count for \"$orig\"</i></h3>
<ul>";

 foreach ($result_arr as $result)
  s_print_title_desc($result);

  echo "</ul>";
  s_print_search_form($orig);
 }
}

function s_search($query) {
// Searches for query in the index file.
// Multiple word search originally contributed by Matthew Furister <matt@agtown.com>
 $query = trim(strtolower(c_strip_chars($query)));
 $search_data = @file($GLOBALS[index_file]) or die("<h4 align=\"center\">$GLOBALS[err_no_search_db]</h4>");
 $pages_found = " ";
 foreach ($search_data as $search_page) {
  $page_arr = explode("|", $search_page);
  $found_count = 0;
  $qry_array = split('[, ]+',trim(strtolower($query)));
  foreach ($qry_array as $qry) {
   if (in_array($qry, $page_arr)) {
    ++$found_count;
    $pages_found .= $page_arr[0] . " ";
   }
  }
  if ($found_count == count($qry_array)) $result_arr[] = $page_arr[0];
 }
 return $result_arr;
}

function s_print_title_desc($file_n) {
 $file = @file($file_n);
 if ($file) {
  $line_complete = implode('', $file);
  eregi("<title>(.*)</title>", $line_complete, $out);
  $title = trim($out[1]);
  if(isset($title)&&strlen($title)>0) $line_complete = str_replace($title, "", $line_complete);
  $line_complete = strip_tags($line_complete);
  $line_complete = trim($line_complete);
  $line_complete = trim(substr($line_complete, 0, 400));

  echo "<li><b><a href=\"$file_n\">";
  if (isset($title)&&strlen($title)>0)
   echo "$title</a></b> <font size=\"-1\">- <i>$file_n</i></font>";
  else
   echo "$file_n</a></b>";
  echo "<br>$line_complete...\n<br>&nbsp;";
 }
 else {
  echo "<li><a href=\"$file_n\"><b>$file_n</b></a><br>...";
 }
}

function s_print_search_form($query) {
// Function to print the search form.
?>
<div align="center"><form method="post">
<h4>Search for:</h4>
<input type="text" name="query" value="<?php echo $query ?>">
<br><input type="submit" value="Search">
</form></div>
<?php
}
function i_control() {
 global $action, $username, $password, $user, $pass;
 if (($user == $username) && ($pass == $password)) {
  if ($action == "clear_index") i_clear_index();
  elseif ($action == "view_index") i_view_index();
  elseif ($action == "index_site") i_index_site();
  i_print_options(); 
 }
 else {
  if (($username == "") && ($password == "")) i_print_logon("");
  else i_print_logon("Invalid username and/or password.");
 }
}

function i_index_site() {
 // Indexes the site & writes it to file.
 if (!isset($GLOBALS[s])) {
  echo "
<br><div align=\"center\">
<h4>You have selected to index your site.</h4>
You can index your site using meta tag \"keywords\" or you can perform a \"full\" index.
<br>Which action do you wish to perform?
<br><br><table border=\"0\"><tr>
<td align=\"center\"><form method=\"post\"><input type=\"hidden\" name=\"index\"><input type=\"hidden\" name=\"action\" value=\"index_site\"><input type=\"hidden\" name=\"s\" value=\"submit_meta\"><input type=\"hidden\" name=\"username\" value=\"$GLOBALS[username]\"><input type=\"hidden\" name=\"password\" value=\"$GLOBALS[password]\"><input type=\"submit\" value=\"Meta Tag Index\"></form></td>
<td align=\"center\"><form method=\"post\"><input type=\"hidden\" name=\"index\"><input type=\"hidden\" name=\"action\" value=\"index_site\"><input type=\"hidden\" name=\"s\" value=\"submit_full\"><input type=\"hidden\" name=\"username\" value=\"$GLOBALS[username]\"><input type=\"hidden\" name=\"password\" value=\"$GLOBALS[password]\"><input type=\"submit\" value=\"&nbsp;Full Index&nbsp;\"></form></td>
</tr></table></div>";
 }
 else {
  if (is_dir($GLOBALS[file_root])) {
   $file_array = i_get_files($GLOBALS[file_root]);
   $file_array = i_strip_extentions($file_array);
   $file_array = i_strip_files($file_array);
   if(is_array($file_array)) {
    set_time_limit(0);
    $fd = @fopen($GLOBALS[index_file], "w") or die("<h3 align=\"center\">$GLOBALS[err_index]</h3>");
    foreach ($file_array as $file) {
     if (($GLOBALS[s] == "submit_full") || (substr($file, -3) == "txt")) $line = i_full_index_file($file);
     elseif ($GLOBALS[s] == "submit_meta")$line = i_meta_index_file($file);
     if (substr_count($line, "|") > 1) fputs($fd, "$line\n");
    }
    fclose($fd);
    echo "<h3 align=\"center\">$GLOBALS[index_complete]</h3>";
   }
   else {
    echo "<h3 align=\"center\">$GLOBALS[no_files]</h3>";
   }
  }
  else {
   echo "<h3 align=\"center\">$GLOBALS[no_dir]</h3>";
  } 
 }
}

function i_full_index_file($file_name) {
 // Retrieves a list of keywords from a file.
 global $http_root, $file_root;
 $file_contents = @file($file_name);
 if ($file_contents) {
  $URL = str_replace($file_root, $http_root , $file_name);
  $keywords = "$URL|";
  $file_contents = implode(" ", $file_contents);
  $file_contents = strip_tags($file_contents);
  $file_contents = strtolower($file_contents);
  $file_contents = str_replace("\n", "", $file_contents);
  $file_contents = c_strip_chars($file_contents);
  $file_contents = str_replace(",", "", $file_contents);
  $file_contents = explode(" ", $file_contents);
  foreach($file_contents as $word) {
   $word = trim($word);
   if ($word != "") {
    $keywords .= "$word|";
   }
  }
 }
 $complete = str_replace("|||", "|", $keywords);
 $complete = str_replace("||", "|", $complete);
 $complete = i_strip_words($complete);
 return $complete;
}

function i_meta_index_file($file) {
 // Indexes a page by it's meta tags.

 global $index_file, $http_root, $file_root;
 $URL = str_replace($file_root, $http_root , $file);
 $mt = @get_meta_tags($file);
 $line = $mt[keywords];
 if ($line) {
  $line = explode(",", $line);
  foreach ($line as $word) {
   $word = trim($word);
   if ($word != "") {
    $keywords .= "$word|";
   }  
  }
  $keyword = str_replace(",", "", $keywords);
  $keywords = c_strip_chars($keywords);
  $keywords = i_strip_words($keywords); 
  $keywords = "$URL|$keywords";
  return $keywords;
 }
 else {
  return "$URL|";
 }
}

function i_get_files($dirname) { 
 // Navigates through the directories recurrsively and retrieves a listing in an array.
 // File permission bit by Abhay Jain <vigya@yahoo.com>

 if($dirname[strlen($dirname)-1] != "/") $dirname.="/"; 
 static $result_array = array(); 
 $mode = fileperms($dirname);
 if(($mode & 0x4000) == 0x4000 && ($mode & 0x00004) == 0x00004) {
  chdir($dirname);
  $handle = @opendir($dirname); 
 }
 if(isset($handle)) {
  while ($file = readdir($handle)) {
   if($file=='.'||$file=='..') continue;
    if(is_dir($dirname.$file)) i_get_files($dirname.$file.'/'); 
    else $result_array[] = $dirname.$file;
  }
  closedir($handle);  
 }
 return $result_array; 
}

function i_strip_extentions($array) {
 // Runs through the extention array and 
 // returns all files with the selected extentions.
 global $include_extentions;
 if(is_array($array)) {
  foreach ($include_extentions as $ext) {
   $str_len = strlen($ext);
   foreach ($array as $file) {
    if (substr($file, -$str_len) == $ext) $result_array[] = $file;
   }
  }
  return $result_array;
 }
 else return $array;
}

function i_strip_files($array) {
 // Reads the exclude file removed unwanted file form the array.
 // Filtering and regex added by:
 // Timo Haberkern <Timo.Haberkern@technical-office.de> 10/10/01
 // Bug: 18/10: Array check.
 // Bug: 21/10/01: Type error, eregi returns int not bool. Causes probs on some systems.

 global $file_root, $exclude_files;
 if(is_array($array)) {
  $exclude = @file($exclude_files);
  if ($exclude) {
	// Create the filter lists
   foreach($exclude as $exc_file) {
	$exc_file = trim($exc_file);
	// Is it a filter?
	if ($exc_file[0] == "/") { 
      $file[] = $exc_file;
    }
    else {
     $filter[] = $exc_file;
    }
   }
  // Do the filtering
   foreach ($array as $act_file) {
    $act_file = str_replace($file_root, "", $act_file);
    $bMatchedFilter = false;
    $bFoundInExcludingList = false;
   // Test the filters first
   if(is_array($filter)) {
    foreach ($filter as $curFilter) {
     if (eregi($curFilter, $act_file)) {
      $bMatchedFilter = true;
      break;
     }
    }
   }
   // Test for excluding
   if ($bMatchedFilter == false) {
   // Test only if the file list is not empty
   if (sizeof($file) != 0) {
    if (in_array($act_file, $file)) {
     $bFoundInExcludingList = true;
     break;
    }
   }
  }
  if (!$bFoundInExcludingList AND !$bMatchedFilter)
   $result_array[] = "$file_root$act_file";
  }
  return $result_array;
 }
 else return $array;
}
else return $array;
}

function i_strip_words($line) {
 $file = @file($GLOBALS['exclude_words']);
 if ($file) {
  foreach ($file as $word) {
   $word = trim($word);
   $word = "|$word|";
   $line = str_replace("$word", "|", $line);  
  }
 }
 return $line;
}
function i_clear_index() {
 // Checks for a confirmation and then clears the index file.
 global $username, $password;
 if ($GLOBALS['s'] == "submit") {
  $fd = @fopen($GLOBALS[index_file], "w") or die("<h4 align=\"center\">$GLOBALS[err_index]</h4>");
  fclose($fd);
  echo "<h4 align=\"center\">$GLOBALS[index_cleared]</h4>";
 }
 else {
  echo "
<br><center><font face=\"arial\">
<b>You have selected to clear your site index.</b>
<br>No search results will be found until you re-index your site.
<br>Are you sure?
<br><form method=\"post\">
<input type=\"hidden\" name=\"action\" value=\"clear_index\">
<input type=\"hidden\" name=\"s\" value=\"submit\">
<input type=\"hidden\" name=\"username\" value=\"$username\">
<input type=\"hidden\" name=\"password\" value=\"$password\">
<input type=\"hidden\" name=\"index\">
<input type=\"submit\" value=\"&nbsp;&nbsp;Yes&nbsp;&nbsp;\"></form>
</font></center>";
 }
}

function i_view_index() {
 // Displays the index file in a table.

 if(file_exists($GLOBALS[index_file])) {
  $file = @file($GLOBALS[index_file]);
  if(is_array($file)) {
   echo "
<h4 align=\"center\">Your index file:</h4>
<table style=\"font-size:75%\" border=\"1\" bordercolor=\"black\" cellpadding=\"2\" cellspacing=\"0\">
 <tr>
  <td nowrap align=\"center\" valign=\"top\"><b>#</b></td>
  <td nowrap align=\"center\" valign=\"top\"><b>URL</b></td>
  <td nowrap align=\"center\" valign=\"top\"><b>Keywords</b></td></tr>";
   foreach ($file as $key => $line) {
    $exp_line = explode("|", $line);
    $key = $key + 1;
    echo "  <td>$key</td>\n";
    foreach ($exp_line as $key => $word) {
     if ($key == 0) { echo "  <td bgcolor=\"#C0C0C0\" nowrap><a href=\"$word\">$word</a></td>\n  "; }
     else { echo "<td nowrap>$word</td>"; }
    }
    echo " \n</tr>\n";
   }
   echo "</table>";
  }
  else { echo "<h4 align=\"center\">$GLOBALS[err_index_empty]</h4>"; }
 }
 else { echo "<h4 align=\"center\">$GLOBALS[err_index]</h4>"; }
}

function i_print_options() {
 // Prints the indexer options.
 global $username, $password;
 echo "
<br><br><form method=\"post\"><table border=\"0\" align=\"center\"><tr><td colspan=\"2\" align=\"left\" valign=\"top\"><font face=\"arial\"><b>Indexer Options:</b></font></td></tr>
<tr><td valign=\"top\"><input type=\"radio\" name=\"action\" value=\"index_site\"><font face=\"arial\" size=\"-1\">Create Site Index</font></td></tr>
<tr><td valign=\"top\"><input type=\"radio\" name=\"action\" value=\"view_index\"><font face=\"arial\" size=\"-1\">View Site Index</font></td></tr>
<tr><td valign=\"top\"><input type=\"radio\" name=\"action\" value=\"clear_index\"><font face=\"arial\" size=\"-1\">Clear Site Index</font></td></tr>
<tr><td colspan=\"2\" align=\"left\" valign=\"top\"><input type=\"hidden\" name=\"username\" value=\"$username\"><input type=\"hidden\" name=\"password\" value=\"$password\"><input type=\"hidden\" name=\"index\"><font face=\"arial\"><input type=\"submit\" value=\"Submit\"</font></td></tr></table></form>";
}

function i_print_logon($msg) {
 // Prints the indexer logon.
 echo "
<form method=\"post\"><center><font face=\"arial\"><b>$msg</b><br><br></font></center>
<table border=\"0\" align=\"center\"><tr><td colspan=\"2\" align=\"center\"><font face=\"arial\"><b>Logon:</b></font></td></tr><tr><td><font face=\"arial\">Username:</font></td><td><input type=\"text\" name=\"username\"></td></tr>
<tr><td><font face=\"arial\">Password:</font></td><td><input type=\"password\" name=\"password\"></td></tr>
<tr><td></td><td><input type=\"hidden\" name=\"index\"><input type=\"submit\" value=\"Logon\"></td></tr></table></form>";
}

function c_strip_chars($line) {
// Strips various characters from $line.
// 
 $line = str_replace(".", " ", $line);
 $line = str_replace("\"", " ", $line);
 $line = str_replace("'", "", $line);
 $line = str_replace("+", " ", $line);
 $line = str_replace("-", " ", $line);
 $line = str_replace("*", " ", $line);
 $line = str_replace("/", " ", $line);
 $line = str_replace("!", " ", $line);
 $line = str_replace("%", " ", $line);
 $line = str_replace(">", " ", $line);
 $line = str_replace("<", " ", $line);
 $line = str_replace("^", " ", $line);
 $line = str_replace("(", " ", $line);
 $line = str_replace(")", " ", $line);
 $line = str_replace("[", " ", $line);
 $line = str_replace("]", " ", $line);
 $line = str_replace("{", " ", $line);
 $line = str_replace("}", " ", $line);
 $line = str_replace("\\", " ", $line);
 $line = str_replace("=", " ", $line);
 $line = str_replace("$", " ", $line);
 $line = str_replace("#", " ", $line);
 $line = str_replace("?", " ", $line);
 $line = str_replace("~", " ", $line);
 $line = str_replace(":", " ", $line);
 $line = str_replace("_", " ", $line);
 $line = str_replace("  ", " ", $line);
 $line = str_replace("&amp;", " ", $line);
 $line = str_replace("&copy;", " ", $line);
 $line = str_replace("&nbsp;", " ", $line);
 $line = str_replace("&quot;", " ", $line);
 $line = str_replace("&uuml;", "ü", $line);
 $line = str_replace("&Uuml;", "Ü", $line);
 $line = str_replace("&", " ", $line);
 $line = str_replace(";", " ", $line);
 $line = str_replace("\n", " ", $line);
 return $line;
}

function c_print_footer() {
// Prints the footer of the page.
echo "
<div align=\"center\">
<br><font size=\"+1\"><b>PHPSiteSearch</b></font>
<br>A script by <a href=\"http://php.warpedweb.net\">David Broker</a>
<br><a href=\"http://php.warpedweb.net/\"><i>http://php.warpedweb.net/</i></a>
<br>Version $GLOBALS[version]</div>";
}

// Display time taken.

if(isset($query)) {
 if($show_time_search) {
  $timeparts = explode(" ",microtime());
  $total_time = ($timeparts[1].substr($timeparts[0],1)) - $starttime;
  echo "<br><center><font size=\"0\"><i>In: ".substr($total_time,0,4)." secs.</i></font><br></center>";
 }
}
elseif (substr($QUERY_STRING,0,5) == "index") {
 if($show_time_index) {
  $timeparts = explode(" ",microtime());
  $total_time = ($timeparts[1].substr($timeparts[0],1)) - $starttime;
  echo "<br><center><font size=\"0\"><i>In: ".substr($total_time,0,4)." secs.</i></font><br></center>";
 }
}


if(DEFINED("DEBUG")) {
echo "\n<table bgcolor=\"white\" border=\"1\" cellpadding=\"3\" cellspacing=\"0\" bordercolor=\"black\" style=\"font-family:arial;color:black;background:white;\">\n<tr><td>\n<h2>Debug:</h2>
<h3>Variables</h3>
<table><tr><td><b>Variable</b></td><td><b>Value</b></td></tr>
<tr><td>HTTP_HOST: </td><td>$HTTP_HOST</td></tr>
<tr><td>SCRIPT_FILENAME: </td><td>$SCRIPT_FILENAME</td></tr>
<tr><td>SCRIPT_NAME: </td><td>$SCRIPT_NAME</td></tr>
<tr><td>PHP_SELF: </td><td>$PHP_SELF</td></tr>
<tr><td>file_root: </td><td>$file_root</td></tr>
<tr><td>http_root: </td><td>$http_root</td></tr>
<tr><td>index_file: </td><td>$index_file</td></tr>
<tr><td>exclude_words: </td><td>$exclude_words</td></tr>
<tr><td>exclude_files: </td><td>$exclude_files</td></tr>
</table>
<h3>Extra Checks</h3>
<b><font color=red>Red</font> = Error, see solution at end of line.
<br><font color=green>Green</font> = No problem.</b><br><br>";
 if(is_dir($file_root)) echo "<b><font color=green>$file_root is a directory.</b></font>";
 else echo "<b><font color=red>$file_root is not a directory.</b></font> <font size=0> Solution: Check \$file_root variable.</font>";
 if(file_exists($index_file)) {
  echo "<br><b><font color=green>$index_file exists.</font>";  
  if(is_readable($index_file)) echo "<li><font color=green>It is readable.</font></b>";  
  else echo "<li><font color=red>It is not readable.</font></b> <font size=0> Solution: Check that $index_file is readable by the webserver.</font>";
  if(is_writable($index_file)) echo "<li><font color=green>It is writable.</font></b>";  
  else echo "<li><font color=red>It is not writable.</font></b> <font size=0> Solution: Check that $index_file is writable by the webserver.</font>";
 } else echo "<br><b><font color=red>$index_file does not exist.</font></b> <font size=0> Solution: Check that \$index_file variable.</font>";
 if(file_exists($exclude_words)) {
  echo "<br><b><font color=green>$$exclude_words exists.</font>";  
  if(is_readable($exclude_words)) echo "<li><font color=green>It is readable.</font></b>";  
  else  echo "<li><font color=red>It is not readable.</font></b> <font size=0> Solution: Check that $exclude_words is readable by the webserver.</font>";
 } else echo "<br><b><font color=red>$exclude_words does not exist.</font></b> <font size=0> Solution: Check that \$exclude_words variable</font>";
 if(file_exists($exclude_files)) {
  echo "<br><b><font color=green>$exclude_files exists.</font>";  
  if(is_readable($exclude_files)) echo "<li><font color=green>It is readable.</font></b>";  
  else echo "<li><font color=red>It is not readable.</font></b> <font size=0>Solution: Check that $exclude_files is readable by the webserver.</font>";
 } else echo "<br><b><font color=red>$exclude_files does not exist.</font></b> <font size=0> Solution: Check \$exclude_files variable.</font>";
 echo "</td></tr></table>";
}

?>
 </body>
</html>