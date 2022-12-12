<?
$strVersion = "v0.2";

/*********************************************************
 * MRTG Total Traffic Generator v0.1                     *
 *   - Björn Patrick Swift <bjorn@swift.st>              *
 *                                                       *
 * Comments or corrections: bjorn@swift.st               *
 *********************************************************/

# Define timeframe
$intFromDate     = mktime(0,0,0,$fmFromMonth,$fmFromDay,$fmFromYear);
$intToDate       = mktime(0,0,0,$fmToMonth,$fmToDay,$fmToYear);
$intTotalSeconds = $intToDate - $intFromDate;

# Scale Function
function fncScale($intNumber, $strFormat, $intDem, $intReturn){
	if ($strFormat == "bytes"){
		$arrFormat = array("Bytes","KBytes","MBytes","GBytes","TBytes");
	} else {
		$intNumber = $intNumber * 8;
		$arrFormat = array("bits","Kbits","Mbits","Gbits","Tbits");
	}

	for ($i = 0; $i < sizeof($arrFormat); $i++){
		if (($intNumber / 1024 < 1) | (sizeof($arrFormat) == $i+1)){

			if (!$intReturn)
				return number_format($intNumber,$intDem) ." $arrFormat[$i]";
			elseif ($intReturn == 1)
				return number_format($intNumber,$intDem);
			elseif ($intReturn == 2)
				return "$arrFormat[$i]";

			break;
		} else {
			$intNumber = $intNumber / 1024;
		}
	}
}


# fncGoThroughLog Function - This does all the work...
function fncGoThroughLog($fmLogfileReal){

	# Create the array and define other expressions i want to use
	$arrValues = array();
	$intOkOver = 0;
	$intCounter = 0;
	$intTotalIn = 0;
	$intTotalOut = 0;

	# Get the public values
	global $intFromDate;
	global $intToDate;
	global $intTotalSeconds;

	# Open log file. Make sure that there are no disallowed characters
	# in $strLogfile.
	list($strLogname, $strLogfile) = split(";", $fmLogfileReal);

		if ($strLogfile == eregi_replace("[^a-zA-Z0-9\_\.\-]", "x", $strLogfile))
			$fd = fopen("$strLogfile", "r") or die("Can't open log file");
		else
			die("Invalid path to logfile");

	# Take the values inside the time frame and load them into the array
	while (!feof($fd)) {
		$strLina = fgets($fd, 4096);
		$arrLina = split(" ", $strLina);
	
		if (($arrLina[0] > $intFromDate) & ($arrLina[0] < $intToDate)){
			$arrValues[$intCounter] = array($arrLina[0],$arrLina[1],$arrLina[2]);
			$intCounter ++;
			$intOkOver = 1;
		}
		else if ($intOkOver){
			$arrValues[$intCounter] = array($arrLina[0],$arrLina[1],$arrLina[2]);
			$intCounter ++;
			$intOkOver = 0;
		}
	}

	fclose ($fd);

	# Run though the array and start calculating
	for ($i = sizeof($arrValues)-1; $i >= 0; $i--){
		list($intTime, $intIn, $intOut) = $arrValues[$i];
	
		if ($i == sizeof($arrValues)-1){
			$intLastTime = $intTime;
		}
		else{
			$intRange = $intTime - $intLastTime;
			$intTotalIn += $intRange * $intIn;
			$intTotalOut += $intRange * $intOut;
			$intLastTime = $intTime;
		}
	}

	?><table cellpadding="2" cellspacing="0" border="0" width="300">
	<tr>
		<td colspan="3"><font face="Arial" size="2"><br></a></font></td>
	</tr>
	<tr bgcolor="#cccccc">
		<td width="120"><font face="Verdana" size="2"><b><?=$strLogname?></b></font></td>
		<td colspan="2" width="180" align="right"><font face="Verdana" size="1"><?=date("d.m.Y",$intFromDate)?> - <?=date("d.m.Y",$intToDate)?>&nbsp;&nbsp;</font></td>
	</tr>
	<tr bgcolor="#dddddd">
		<td width="150" align="left"><font face="Arial" size="1">Bits In</font></td>
		<td width="70" align="right"><font face="Verdana" size="1"><?=fncScale($intTotalIn, "bits", 1, 1)?></font></td>
		<td width="80" align="left"><font face="Verdana" size="1"><?=fncScale($intTotalIn, "bits", 1, 2)?></font></td>
	</tr>
	<tr bgcolor="#dddddd">
		<td width="150" align="left"><font face="Arial" size="1">Avg. Bits In</font></td>
		<td width="70" align="right"><font face="Verdana" size="1"><?=fncScale($intTotalIn/$intTotalSeconds, "bits", 1, 1)?></font></td>
		<td width="80" align="left"><font face="Verdana" size="1"><?=fncScale($intTotalIn/$intTotalSeconds, "bits", 1, 2)?></font></td>
	</tr>
	<tr>
	</tr>
	<tr bgcolor="#dddddd">
		<td width="150" align="left"><font face="Arial" size="1">Bits Out</font></td>
		<td width="70" align="right"><font face="Verdana" size="1"><?=fncScale($intTotalOut, "bits", 1, 1)?></font></td>
		<td width="80" align="left"><font face="Verdana" size="1"><?=fncScale($intTotalOut, "bits", 1, 2)?></font></td>
	</tr>
	<tr bgcolor="#dddddd">
		<td width="150" align="left"><font face="Arial" size="1">Avg. Bits Out</font></td>
	<td width="70" align="right"><font face="Verdana" size="1"><?=fncScale($intTotalOut/$intTotalSeconds, "bits", 1, 1)?></font></td>
		<td width="80" align="left"><font face="Verdana" size="1"><?=fncScale($intTotalOut/$intTotalSeconds, "bits", 1, 2)?></font></td>
	</tr>
	<tr>
	</tr>
	<tr bgcolor="#dddddd">
		<td width="150" align="left"><font face="Arial" size="1">Bytes In</font></td>
		<td width="70" align="right"><font face="Verdana" size="1"><?=fncScale($intTotalIn, "bytes", 1, 1)?></font></td>
		<td width="80" align="left"><font face="Verdana" size="1"><?=fncScale($intTotalIn, "bytes", 1, 2)?></font></td>
	</tr>
	<tr bgcolor="#dddddd">
		<td width="150" align="left"><font face="Arial" size="1">Bytes Out</font></td>
		<td width="70" align="right"><font face="Verdana" size="1"><?=fncScale($intTotalOut, "bytes", 1, 1)?></font></td>
		<td width="80" align="left"><font face="Verdana" size="1"><?=fncScale($intTotalOut, "bytes", 1, 2)?></font></td>
	</tr>
	</table><br>

<? }

# If this program comes to your use I'd be happy hear
#        - Bjössi <bjorn@swift.st>

?>
<html>
<head>
	<title>MRTG Total Traffic Generator <?=$strVersion?></title>
<style>
<!--
	a{text-decoration: none}
//-->	
</style>
</head>
<body bgcolor="#eeeeee" link="#555555" alink="#555555" vlink="#555555">
<font face="Verdana, Arial" size="4"><b>Total Traffic</b></font><br>

<?

# Check which logfiles are to be scanned

for ($i = 0;$i < count($arrLogfileReal);$i++){
	echo fncGoThroughLog($arrLogfileReal[$i]);
}

?>

<font face="Arial" size="1"><a href="traffic.php">Back</a></font>
<br><br>
<font face="Arial" size="1">
MRTG Total Traffic Generator <?=$strVersion?><br>
by <a href="http://bjorn.swift.st">Björn Patrick Swift</a> &lt;<a href="mailto:bjorn@swift.st">bjorn@swift.st</a>&gt;
</font>
</body>
</html>
