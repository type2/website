<?
if ((isset($_POST['send'])) && ($_POST['to'] !== "err")) {

switch ($_POST['to']) {
	case "josh":
		$to = "keen@atlasta.net";
		break;
	case "dan":
		$to = "keen@atlasta.net";
		break;
	default:
		$to = "keen@type2.com";
		break;
}
	/* hacked in by keen@type2.com 11/27/05 */
	$ip = $_SERVER['REMOTE_ADDR'];
        $script = $_SERVER['SCRIPT_FILENAME'];
	$hdr = "X-type2-info: $ip $script \r\nFrom: ".$_POST['from']." <".$_POST['email'].">\r\n ";

/*        $hdr = "From: ".$_POST['from']." <".$_POST['email'].">\r\n";*/


	mail ($to, $_POST['subj'], $_POST['msg'], $hdr);
	$sent = "<div class=\"note\">Your message has been sent to $to</div>";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>WetWesties - Contact</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript">
<!--
if (navigator.appName == 'Netscape') {
  document.write('<link href="style-ns.css" rel="stylesheet" type="text/css">')}
else {
  document.write('<link href="style.css" rel="stylesheet" type="text/css">')}
//-->
</script>
</head>
<body>
<table width="100%" border="0" align="center" cellpadding="10" cellspacing="0">
  <tr align="center">
    <td height="145" colspan="2"><img src="images/vwbanner-e.gif" width="555" height="184" alt="WetWesties - A Pacific Northwest Camping Society"></td>
  </tr>
  <tr>
    <td width="145" height="564" align="center" valign="top" class="link">
      <p><a href="index.php"><img src="images/about.gif" alt="About our Group" width="141" height="81" border="0"><br>
        About our Group</a></p>
      <p> <a href="join.htm"><img src="images/join.gif" alt="How to Join" width="90" height="111" border="0"><br>
        How to Join</a></p>
      <p><a href="calendar.htm"><img src="images/calendar.gif" alt="Calendar" width="140" height="113" border="0"><br>
        Calendar</a></p>
      <p><a href="links.htm"><img src="images/links.gif" alt="Links" width="145" height="93" border="0"><br>
        Links</a></p>
      <p><i><font color="#FF0000">Contact</font></i></p>
    </td>
    <td width="100%" valign="top" class="body"><form 
action="contact-1.php" method="post" name="wwcontact" id="wwcontact">
        <p>Please use this form to send messages to the web guys and list administrator.</p>
        <?php if(isset($sent)){echo $sent;} ?>
		<table width="503" border="0" cellspacing="0" cellpadding="5">
          <tr> 
            <td width="50" valign="top" class="club">To:</td>
            <td width="436"> 
              <?php if($_POST['to'] == "err"){ echo "<div class=\"note\">Please Select A Recipient</div>"; } ?>
              <select name="to" id="to">
                <option value="err">Select One</option>
                <optgroup label="WebMechanix"> 
				<option value="josh">Josh Gibbs</option>
				<option value="james">James Arnott</option>
				</optgroup>
				<optgroup label="Mechanix Emeritus">
                <option value="dan">Dan Simmons</option>
                </optgroup>
                <optgroup label="List Administrator"> 
                <option value="jim">Jim Arnott</option>
                </optgroup>
              </select></td>
          </tr>
          <tr> 
            <td valign="top" class="club">From:</td>
            <td><input name="from" type="text" id="from" value="<?php if($_POST['to'] == "err"){echo $_POST['from']; } ?>" size="25"></td>
          </tr>
          <tr> 
            <td valign="top" class="club">Email:</td>
            <td><input name="email" type="text" id="email" value="<?php if($_POST['to'] == "err"){echo $_POST['email']; } ?>" size="25"></td>
          </tr>
          <tr> 
            <td valign="top" class="club">Subj:</td>
            <td><input name="subj" type="text" id="subj2" size="25" value="<?php if($_POST['to'] == "err"){echo $_POST['subj']; } ?>"></td>
          </tr>
          <tr> 
            <td valign="top" class="club">Msg:</td>
            <td><textarea name="msg" cols="40" rows="5" id="textarea"><?php if($_POST['to'] == "err"){echo $_POST['msg']; } ?></textarea></td>
          </tr>
          <tr>
            <td valign="top" class="club">&nbsp;</td>
            <td><input name="send" type="submit" id="send" value="  Send  "></td>
          </tr>
        </table>
        </form>
		
		<BR>
      <table width="50%" border="0">
        <tr> 
          <td width="35%" valign="top">WebMechanix:</td>
          <td width="65%" valign="top"><a href="mailto:gibbsjj@hotmail.com">Josh 
            Gibbs</a> - P-mail with any questions about our site.</td>
        </tr>
        <tr> 
          <td width="35%" valign="top">&nbsp;</td>
          <td width="65%" valign="top"><a href="mailto:wetwesties@badgopher.com">James 
            Arnott</a> </td>
        </tr>
        <tr> 
          <td width="35%" valign="top">Mechanix Emeritus:</td>
          <td width="65%" valign="top"><a href="mailto:vw_bus_74@yahoo.com">Dan 
            Simmons</a></td>
        </tr>
        <tr> 
          <td width="35%" valign="top">&nbsp;</td>
          <td width="65%" valign="top">Cris Torlasco (the original!)</td>
        </tr>
        <tr> 
          <td width="35%" valign="top">List Administrator:</td>
          <td width="65%" valign="top"><a href="mailto:jrasite@eoni.com">Jim Arnott</a> 
            P-mail with any questions about our discussion forum.</td>
        </tr>
      </table>
		
		

    </td>
  </tr>
  <tr>
    <td height="0"></td>
    <td></td>
  </tr>
  <tr>
    <td height="28"><img src="images/spacer.gif" alt="" width="145" height="8"></td>
    <td></td>
  </tr>
</table>
<hr align="center" width="80%" noshade>
<div align="right">
  <table width="195" border="0" align="left" cellspacing="0" cellpadding="2">
    <tr>
      <td width="50"><img src="images/vw.gif" alt="VW Logo" width="50" height="50" align="top"></td>
      <td width="138" class="club">Officially Licensed<br>
        Volkswagen Club<br>
        Website </td>
    </tr>
  </table>
  This website is not affiliated with or endorsed by <br>
  Volkswagen AG or Volkswagen of America, Inc. <br>
  All Volkswagen trademarks herein are used <br>
  under license from VWoA.<br>
  <br>
</div>
</body>
</html>
