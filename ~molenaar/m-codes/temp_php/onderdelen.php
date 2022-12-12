<html><body bgcolor=white link=navy vlink=navy><font size="2" face="Arial"><center>

<center><font size="4"><b>VW-Onderdelenlijst</b></font><br>
<p>

De onderdelen die in deze lijst staan vermeld zijn op dit moment, of waren tot voor kort, leverbaar bij de VW dealer. Deze
lijst is opgesteld omdat de dealer een 'duur' imago heeft en veel mensen niet op het idee zullen komen hier onderdelen
te halen voor hun luchtgekoelde VW. De artikelen die nog leverbaar zijn blijken in veel gevallen inderdaad wat hoog geprijsd
te zijn, maar er zijn echter ook onderdelen met een veel redelijker prijs waarvan je nooit had gedacht dat de dealer
dit nog zou leveren. Deze lijst is samengesteld met als doel de Luchtgekoelde VW rijder te informeren over:<p>

<TABLE border=0 width=400 cellpadding=2 cellspacing=0 style="font-size:13px;">
<tr><td>
<ul>
<li>de verkrijgbaarheid van onderdelen bij de VW-dealer
<li>de prijzen bij de VW-dealer
<li>onderdeelnummers voor tijdsbesparing aan de balie.<br>
(scheelt zoekwerk voor de magazijnmedewerker)
</td></tr>
</ul>
</td></tr>
</table>





korte uitleg bij deze lijst en VW-artikelnrs:<br>

<TABLE border=0 width=700 cellpadding=2 cellspacing=0 style="font-size:13px;">
<tr><td valign=top><b>artikelnummer: </b></td><td>De VW artikelnummers zijn opgebouwd uit een aantal groepen cijfers.
De eerste groep geeft aan op welk model VW het onderdeel voor het eerst is toegepast. De tweede groep geeft aan
tot welke deel van de auto het onderdeel behoort. De derde groep cijfers is het onderdeelnummer. Een toevoeging achter
het artikelnummer geeft aan dat dit een gewijzigd onderdeel is of dat er een bepaalde variant van het onderdeel wordt
bedoeld (sommige onderdelen zijn bijvoorbeeld in verschillende kleuren leverbaar)<br>
voorbeeld:<p>

<dl>
<dd>

<TABLE border=0 width=400 cellpadding=2 cellspacing=0 style="font-size:13px;">
<tr><td colspan="2" align="left"><b>"211 853 601 E 90D"<b><p></td></tr>

<tr><td><b>211</b></td><td> - VW type 211 (dichte bestelbus)</td></tr>
<tr><td><b>853</b></td><td> - groep 8: 'carrosserie', subgroep 53</td></tr>
<tr><td><b>601</b></td><td> - onderdeelnummer voor VW logo</td></tr>
<tr><td><b>E</b></td><td> - wijzigingscode voor onderdeel</td></tr>
<tr><td><b>90D</b></td><td> - variant: kleurcode '90D' (='pastelwit')</td></tr>
</table>
</dl>
<p>
</td></tr>

<tr><td valign=top><b>omschrijving: </b></td><td>dit is de letterlijke omschrijving zoals deze in de catalogus staat.</td></tr>
<tr><td valign=top><b>toelichting: </b></td><td> de omschrijving in de catalogus laat helaas wel eens wat te wensen over.
De omschrijvingen zijn vaak onduidelijk en in bepaalde gevallen zitten er zelfs fouten in. In de toelichting wordt voor zover nodig
het onderdeel nader omschreven.<br>
<b>LET OP:</b> De vermelde jaartallen zijn modeljaren en staan dus los van de productiedatum!</td></tr>
<tr><td valign=top><b>prijs: </b></td><td>De vermelde prijzen zijn exclusief BTW. De bedragen zijn doorgegeven door diverse VW-enthousiastelingen na aanschaf van een bepaald artikel of na het opvragen van een prijs.</td></tr>
<tr><td valign=top><b>datum: </b></td><td>hier staan het jaar en de maand vermeld wanneer deze prijs werd gehanteerd</td.</tr>
</table>
<p>





<form action="http://www.type2.com/m-codes/temp_php/onderdelen.php?" method="POST">
<select name="zoekcode">
<option value="1">1 - motor</option>
<option value="2">2 - brandstoftank en leidingen, uitlaat</option>
<option value="3">3 - versnellingsbak</option>
<option value="4">4 - vooras, differentieel, stuurinrichting</option>
<option value="5">5 - achteras</option>
<option value="6">6 - wielen en remmen</option>
<option value="7">7 - handrem en pedalen</option>
<option value="8">8 - carrosserie</option>
<option value="9">9 - electrische uitrusting</option>
<option value="0">0 - toebehoren</option>
</option>
</select>
<input type="submit" name="Submit" value="Submit">
</form>
<br>


<?PHP

if ($zoekcode==1) { print "<font size=4><b>groep 1 - motor</b></font><p>"; }
if ($zoekcode==2) { print "<font size=4><b>groep 2 - brandstoftank en leidingen, uitlaat</b></font><p>"; }
if ($zoekcode==3) { print "<font size=4><b>groep 3 - versnellingsbak</b></font><p>"; }
if ($zoekcode==4) { print "<font size=4><b>groep 4 - vooras, differentieel, stuurinrichting</b></font><p>"; }
if ($zoekcode==5) { print "<font size=4><b>groep 5 - achteras</b></font><p>"; }
if ($zoekcode==6) { print "<font size=4><b>groep 6 - wielen en remmen</b></font><p>"; }
if ($zoekcode==7) { print "<font size=4><b>groep 7 - handrem en pedalen</b></font><p>"; }
if ($zoekcode==8) { print "<font size=4><b>groep 8 - carrosserie</b></font><p>"; }
if ($zoekcode==9) { print "<font size=4><b>groep 9 - electrische uitrusting</b></font><p>"; }
if ($zoekcode==0) { print "<font size=4><b>groep 0 - toebehoren</b></font><p>"; }

?>

<TABLE border=0 width=700 cellpadding=4 cellspacing=0 style="font-size:13px;">

<?PHP
$fcontents = file('onderdeels.csv');
$teller=0;

while ( list( $line_num, $line ) = each( $fcontents ) )
{
 list( $nr1, $nr2, $nr3, $nr4, $nr5, $model, $etka, $toelichting, $vervangen, $vervangendoor, $datumprijs, $prijs) = split( ';', $line );

 $start = substr($nr2, 0, 1); // pak het eerste cijfer van de groepcode
 $zoek = substr($zoekcode, 0, 1); // pak het eerste cijfer van de zoekcode



 if ($start==$zoek)
 {

  if ($teller==0) { $teller=1; $bgcolor="#DFDFFF"; } // bepaal de achtergrondkleur
  else { $teller=0; $bgcolor="#EAEAFF";}

 print "<TR bgcolor=$bgcolor valign=top><TD><b>$nr1 $nr2 $nr3 $nr4 $nr5</b><br><font size=1>$vervangen</font><br><font size=1>$vervangendoor</font></TD><!--dit is een luchtlist bestand, kopieren van deze lijst zonder toestemming van de auteur is niet toegestaan!-->";
 print "<TD><font size=1>omschrijving:</font> <b>$etka</b><br><font size=1>toelichting:</font><!--auteur: Vincent Molenaar, molenari@hotmail.com--> $toelichting<br><font size=1> prijs:</font> &euro;<b> $prijs</b> ($datumprijs)</TD></TR>\n";

 } // einde if nr2

}
?>

</TABLE><br>
Deze lijst is gemaakt voor de Luchtlist. Kopieren van deze lijst zonder toestemming van de auteur is niet toegestaan.<p>


<b>Disclaimer</b><p>

De auteur van deze lijst en de Luchtlist hebben beiden geen enkele relatie met het merk 'Volkswagen'
of de importeur van dit merk in Nederland of België. Deze lijst is informatief bedoeld. Voor de adressen
van particuliere bedrijven van Luchtgekoelde VW producten wordt verwezen naar de
bedrijvenlijst <b>("link bedrijvenlijst opnemen")</b>.<p>

Uit deze lijst kunnen geen rechten ontleend worden. Het is mogelijk dat de prijzen inmiddels zijn veranderd of dat een product
niet meer leverbaar is. De grootste zorg is aan de gegevens op deze lijst besteed, toch is het niet uitgesloten dat er
onjuistheden in de lijst zitten. Zorg er daarom voor dat je zeker weet dat je het goede onderdeel besteld. Het is namelijk
mogelijk dat een artikel niet geruild kan worden.<p>


<font size=2>laatste wijziging: 23 december, 2002</font><br>
</BODY></HTML>