<?php
//DON'T MESS WITH THIS CODE!
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
//print the header
print "<!-- HERE BEGINNETH THE HEADER -->\r\n";
include("./templates/user_top.html");


?>

						<center>
							
							
							<font face='\"ms' sans serif\" size="4"><b>Search Listings</b></font>
							<P>
							<?php
							//calculates the total number of records
							$result = mysql_query("SELECT * FROM vehicles",$link);
							$num_rows = mysql_num_rows($result);
							?>
							<font face='\"ms' sans serif\" size="3"><a href="./carview.php">Show all vehicles (<?php print "$num_rows"; ?>)</a></font>

							
				
										<form name=pricesearch action="./carview.php">
							<table border=0 cellspacing=3 cellpadding=3>
							<TR><td></td>
								<TD align=center><B>Find me the perfect vehicle:</b></td>
							</tr>
							
							<tr>
								<td ALIGN=right>Minimum Price:</TD><td align=left><input type="text" name="minprice">.00</td>
							</tr>
							<TR>
								<td ALIGN=right>Maximum Price:</TD><td align=left><input type="text" name="maxprice">.00</td>
							</tr>
							<tr>
								<td align=right>City:</td>
								<td align=left>
									<select name="citystate[]" size="6" multiple>
									<?php
									$result = mysql_query("SELECT vehicles.city, state, count(*) AS num_town FROM vehicles GROUP BY city ORDER BY state, city;",$link);
									while ($a_row =mysql_fetch_array ($result) )
										{
										$city = stripslashes($a_row[city]);
										print "<option name=\"citystate\" value=\"$a_row[city]___$a_row[state]\">$city, $a_row[state] ($a_row[num_town])";
										}
									?>
									</select>
								</td>
							</tr>
							
							<tr>
								<td align=right>Type:</td>
								<td align=left>
									<select name="typechoice[]" size="6" multiple>
								<?php
									$result = mysql_query("SELECT type, count(*) AS num_types FROM vehicles GROUP BY type;",$link);
									while ($a_row =mysql_fetch_array ($result) )
										{
										$type = stripslashes($a_row[type]);
										print "<option name=\"typechoice\" value=\"$a_row[type]\">$type ($a_row[num_types])";
										}
									?>
									</select>
								</td>
							</tr>

							<tr>
								<td align=right>Make & Model:</td>
								<td align=left>
									<select name="makemodelchoice[]" size="6" multiple>
								<?php
// was
//							$result = mysql_query("SELECT vehicles.make, model, count(*) AS num_vehs FROM vehicles GROUP BY make ORDER BY make, model;",$link);
// is
									$result = mysql_query("SELECT make, model, count(*) AS num_vehs FROM vehicles GROUP BY make, model;",$link);
									while ($a_row =mysql_fetch_array ($result) )
										{
										$make = stripslashes($a_row[make]);
										$model = stripslashes($a_row[model]);
										print "<option name=\"makemodelchoice\" value=\"$a_row[make]___$a_row[model]\">$make, $a_row[model] ($a_row[num_vehs])";
										}
									?>
									</select>
								</td>
							</tr>
						
							<tr>
								<td align=right>Year:</td>
								<td align=left>
									<select name="yearchoice[]" size="6" multiple>
								<?php
									$result = mysql_query("SELECT year, count(*) AS num_years FROM vehicles GROUP BY year;",$link);
									while ($a_row =mysql_fetch_array ($result) )
										{
										$year = stripslashes($a_row[year]);
										print "<option name=\"yearchoice\" value=\"$a_row[year]\">$year ($a_row[num_years])";
										}
									?>
									</select>
								</td>
							</tr>
	
							<tr>
								<td align=right>Transmission:</td>
								<td align=left>
									<select name="transmissionchoice[]" size="4" multiple>
										<option value="Automatic">Automatic
										<option value="Manual">Manual
										<option value="Other">Other
									</select>
								</td>
							</tr>
							
								
							
							<tr>
								<td align=center></td>
							</tr>
								
  						<tr>
  							<td align=right>
  								Features:
  							</td>
  							<td align=left>
  								<?
  								$featuresarray = explode("||", $vehiclefeatureoptions);
  								while (list($IndexValue, $FeatureItem) = each ($featuresarray))
  								{
  								$realindex = $IndexValue+1; 
								$optnum = "opt".$realindex; 
  								echo "<BR><input type=checkbox name=\"$optnum\" value=\"Y\">$FeatureItem";
  								}
  								?>
  							</td>
  						</tr>
  			
  								
					</table>
							
							
							<input type=submit></form>
				
				</table>
				</center>
				
				
<?php
//print the footer
		print"\r\n<!-- THUS ENDETH THE MAIN CONTENT -->\r\n<!-- HERE BEGINNETH THE FOOTER -->";
		include("./templates/user_bottom.html");

//gots to close the mysql connection
mysql_close($link);
?>
