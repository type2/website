<? $strVersion = "v0.2";?>
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
<font face="Verdana, Arial" size="2"><b>Choose Period and Logfile</b></font>
<form action="traffic2.php">

<table border="0">
<tr>
	<td valign="top"><font face="Arial" size="1">Logs:</font></td>
	<td><font face="Arial" size="1">
	<input type="checkbox" name="arrLogfileReal[]" 
value="pon.type2.com;lenti.type2.com.1.log" checked>pon.type2.com<br>
	</font></td>
</tr>
<tr>
<td></td><td>day.month.year</td>
</tr>
<tr>
	<td><font face="Arial" size="1">From:</font></td>
	<td><input type="text" name="fmFromDay" size="2" value="01" style="font-size: 8pt; width: 25px;">.
		<input type="text" name="fmFromMonth" size="2" value="<?=date("m",mktime(0,0,0,date("m")-1,date("d"),date("Y")))?>" style="font-size: 8pt; width: 25px;">.
		<input type="text" name="fmFromYear" size="4" value="<?=date("Y",mktime(0,0,0,date("m")-1,date("d"),date("Y")))?>" style="font-size: 8pt; width: 35px;"></td>
</tr>
<tr>
	<td><font face="Arial" size="1">Til:</font></td>
	<td><input type="text" name="fmToDay" size="2" value="01" style="font-size: 8pt; width: 25px;">.
		<input type="text" name="fmToMonth" size="2" value="<?=date("m")?>" style="font-size: 8pt; width: 25px;">.
		<input type="text" name="fmToYear" size="4" value="<?=date("Y")?>" style="font-size: 8pt; width: 35px;"></td>
</tr>
<tr>
	<td></td>
	<td><input type="submit" value="Calculate" style="font-size: 8pt; width: 100px;"></td>
</tr>
</table>
</form>
</font>
<font face="Arial" size="1">
MRTG Total Traffic Generator <?=$strVersion?><br>
by <a href="http://bjorn.swift.st">Björn Patrick Swift</a> &lt;<a href="mailto:bjorn@swift.st">bjorn@swift.st</a>&gt;
</font>
</body>
</html>
