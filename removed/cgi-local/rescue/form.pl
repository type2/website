#!/usr/local/bin/perl
##############################################################################
# Free For All Link Page        Version 2.2                                  # 
# Copyright 1996 Matt Wright    mattw@worldwidemart.com                      #
# Created 5/14/95               Last Modified 7/17/96                        #
# Scripts Archive at:           http://www.worldwidemart.com/scripts/        #
#
# ReEdited by Dallas Smith      http://www.unc.edu/~sallad
##############################################################################

# Define Variables

$filename = "/httpd/rescue/form.html";
$formurl = "http://www.type2.com/rescue/form.html";
$formcgi = "http://www.type2.com/rescue/form.pl";
$formtitle = "Type 2's Search and Rescue Page";
$database = "/httpd/rescuedata.txt";

# Done
##############################################################################

# Get the input
read(STDIN, $buffer, $ENV{'CONTENT_LENGTH'});

# Split the name-value pairs
@pairs = split(/&/, $buffer);

foreach $pair (@pairs) {
   ($name, $value) = split(/=/, $pair);

   $value =~ tr/+/ /;
   $value =~ s/%([a-fA-F0-9][a-fA-F0-9])/pack("C", hex($1))/eg;
   $value =~ s/<([^>]|\n)*>//g;
   $value =~ s/<//g;
   $value =~ s/>//g;
   $FORM{$name} = $value;
}

if ($FORM{'url'} eq 'http://' || $FORM{'url'} !~ /^(f|ht)tp:\/\/\w+\.\w+/) { 
   &no_url; 
} 
if (!($FORM{'title'})) {
   &no_title;
}

# Enter our tags and sections into an associative array

%sections = ("az","Arizona","fl","Florida","nc","North Carolina",
	     "ny","New York","tx","Texas",
	     "al","Alabama","hi","Hawaii");

# Suck previous form file into one big string
open(FILE,"$filename");
@lines = <FILE>;
close(FILE);

$i=1;
foreach $line (@lines) {    
    if ($line =~ /\<li\>\<a href\=\"([^\"]+)\">([^<]+)<\/a>/) {
        if ($FORM{'url'} eq $1) {
            &repeat_url;
        }
        $i++;
    }
}

# Open Form File to Output
open (FILE,">$filename");

foreach $line (@lines) { # For every line in our data

   if ($line =~ /<!--time-->/) {
      @months = ('January','February','March','April','May','June',
		 'July','August','September','October','November','December');

      @days = ('Sunday','Monday','Tuesday','Wednesday','Thursday',
	       'Friday','Saturday');

      ($sec,$min,$hour,$mday,$mon,$year,$wday) = (localtime(time))[0,1,2,3,4,5,6];
      if ($sec < 10) { $sec = "0$sec"; }
      if ($min < 10) { $min = "0$min"; }
      if ($hour < 10) { $hour = "0$hour"; }
      if ($mday < 10) { $mday = "0$mday"; }
      $date = "on $days[$wday], $months[$mon] $mday, 19$year at $hour:$min:$sec";
      print FILE "<!--time--><b>Last link was added $date</b><hr>\n";
   }
   elsif ($line =~ /<!--number-->/) {
      print FILE "<!--number--><b>There are <i>$i</i> entries on this ";
      print FILE "page.</b><br>\n";
   }
   else {
       print FILE $line;
   }

   foreach $tag ( keys %sections) { # For every tag 
      if ( ($FORM{'section'} eq $sections{$tag}) && 
         ($line =~ /<!--$tag-->/) ) {

         print FILE "<li><a href=\"$FORM{'url'}\">$FORM{'title'}</a>\n"; 
      }
   }
}

close (FILE);

# Return Form File
print "Location: $formurl\n\n";

if ($database ne '') {
    open (DATABASE,">>$database");
    print DATABASE "$FORM{'url'}\n";
    close(DATABASE);
}

sub no_url {
   print "Content-type: text/html\n\n";
   print "<html><head><title>ERROR: No URL</title></head>\n";
   print "<body bgcolor=#FFFFFF text=#000000><center>";
   print "<h1>No Info</h1></center>\n";
   print "You forgot to enter your info.  That form ";  
   print "was invalid.<p>\n";
   print "<form method=POST action=\"$formcgi\">\n";
   print "<input type=hidden name=\"title\" value=\"$FORM{'title'}\">\n";
   print "<input type=hidden name=\"section\""; 
   print "value=\"$FORM{'section'}\">\n";
   print "URL: <input type=text name=\"url\" size=50><p>\n";
   print "<input type=submit> * <input type=reset>\n";
   print "<hr>\n";
   print "<a href=\"$formurl\">$formtitle</a>\n";
   print "</form></body></html>\n";

   exit;
}

sub no_title {
   print "Content-type: text/html\n\n";
   print "<html><head><title>ERROR: No Title</title></head>\n";
   print "<body bgcolor=#FFFFFF text=#000000><center>";
   print "<h1>No Title</h1></center>\n";
   print "You forgot to enter a title you wanted added to the Free for ";
   print "all link page.  Another possible problem is that you title ";
   print "contained illegal characters.<p>\n";
   print "<form method=POST action=\"$formcgi\">\n";
   print "<input type=hidden name=\"url\" value=\"$FORM{'url'}\">\n"; 
   print "<input type=hidden name=\"section\"";
   print "value=\"$FORM{'section'}\">\n";
   print "TITLE: <input type=text name=\"title\" size=50><p>\n";
   print "<input type=submit> * <input type=reset>\n";
   print "<hr>\n";
   print "<a href=\"$formurl\">$formtitle</a>\n";
   print "</form></body></html>\n";

   exit;
}

sub repeat_url {
   print "Content-type: text/html\n\n";
   print "<html><head><title>ERROR: Repeat URL</title></head>\n";
   print "<body bgcolor=#FFFFFF text=#000000><center><h1>Repeat URL</h1></center>\n";
   print "Sorry, this URL is already in the Free For All Link Page.\n";
   print "You cannot add this URL to it again.  Sorry.<p>\n";
   print "<a href=\"$formurl\">$formtitle</a>";
   print "</body></html>\n";

   exit;
}
