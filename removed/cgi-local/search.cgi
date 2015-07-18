#!/usr/bin/perl
#
# January 11, 1999:  Added a README, and some minor changes.
# February 14, 1998: Uses "templates", and handles previews for different
#                    file formats (html, pdf, txt)
# November 17, 1997: Better abstractions.
# November 11, 1997: rewrote "head" bits in perl.  Uses GET instead of POST.
# Spring (?), 1997:  First release.  Simplistic interface to Swish.

# The user should only need to modify this line:

$template_file = "/home/httpd/example.html";

use CGI qw(:standard);

## BEGINING of template processing:

# Read the template.

$template = "";

if (!open(INPUT, "< $template_file")) 
  {
    print STDERR "Can't open $template_file as input.\n";
    exit(0);
  }
else 
  { 
    while (<INPUT>) 
      { 
	$template .= $_; 
      } 
    close (INPUT);
  }

# Parse the template for categories.

while ($template =~ m/<!--\s+index\s/) 
  {
    $template =~ s/(\<\!\--\s+index\s+)(\S+)(\s+)(\S+)(\s+)(\S+)(\s+)(\S.*)(\s+-->)//i;
    push @category, $8;
    push @category_type, $2;
    push @category_file, $6;
    push @category_total, 0;
    $_ = $4;
    if (m/UNSELECTED/i)
      {
	push @category_selection, 0; # false (0) == UNSELECTED
      }
    else
      {
	push @category_selection, 1; # true (1) == SELECTED
      }
  }

# Parse the template for replacement rules.

while ($template =~ m/<!--\s+replacement-rules\s/) 
  {
    $template =~ s/(\<\!\--\s+replacement-rules\s+)(\S+)(\s+)(\S+)(\s+-->)//i;
    push @replace, $2;
    push @replacement, $4;
  }

# Parse the template for page size (number of results per page)

$template =~ s/(<!--\s+page-size\s+)(\d+)(\s+-->)//i;
$page_size = $2;

# Parse the template for the maximum number of matches to find.

$template =~ s/(<!--\s+maximum-matches\s+)(\d+)(\s+-->)//i;
$maximum_matches = $2;

# Parse the template for the path to this script.

$template =~ s/(<!--\s+search-cgi\s+)(\S+)(\s+-->)//i;
$searchcgi = $2;

# Parse the template for the swish command.

$template =~ s/(<!--\s+swish-command\s+)(\S+)(\s+-->)//i;
$swish = $2;

# The template is divided into several sections described in the README;
# the next regular expressions splice the template into these sections.

$template =~ s/<!--\s+start of document head\s+-->.*?<!--\s+end of document head\s+-->//s;
$document_head = $&;
$template =~ s/<!-- start of document tail -->.*?<!-- end of document tail -->//s;
$document_tail = $&;

$template =~ s/<!-- start of search summary head -->.*?<!-- end of search summary head -->//s;
$search_summary_head = $&;
$template =~ s/<!-- start of search summary tail -->.*?<!-- end of search summary tail -->//s;
$search_summary_tail = $&;
$template =~ s/<!-- start of search summary body -->.*?<!-- end of search summary body -->//s;
$search_summary_body = $&;

$template =~ s/<!-- start of search results head -->.*?<!-- end of search results head -->//s;
$search_results_head = $&;
$template =~ s/<!-- start of search results head2 -->.*?<!-- end of search results head2 -->//s;
$search_results_head2 = $&;
$template =~ s/<!-- start of search results html -->.*?<!-- end of search results html -->//s;
$search_results_html = $&;
$template =~ s/<!-- start of search results pdf -->.*?<!-- end of search results pdf -->//s;
$search_results_pdf = $&;
$template =~ s/<!-- start of search results calendar -->.*?<!-- end of search results calendar -->//s;
$search_results_calendar = $&;
$template =~ s/<!-- start of search results tail -->.*?<!-- end of search results tail -->//s;
$search_results_tail = $&;

$template =~ s/<!-- start of search box head -->.*?<!-- end of search box head -->//s;
$search_box_head = $&;
$template =~ s/<!-- start of search box tail -->.*?<!-- end of search box tail -->//s;
$search_box_tail = $&;
$template =~ s/<!-- start of search box body1 -->.*?<!-- end of search box body1 -->//s;
$search_box_body_A = $&;
$template =~ s/<!-- start of search box body -->.*?<!-- end of search box body -->//s;
$search_box_body_B = $&;

## END of template processing.

print header;
print $document_head;

## BEGINING of search results processing

if (param()) 
  {
    $i=0;
    @scope = param("scope");
    foreach $category_entry (@category) 
      {
	$category_selection[$i] = 0; # unselected
	foreach $scope_entry (@scope) 
	  {
	    if ($category_entry eq $scope_entry) 
	      { 
		$category_selection[$i] = 1; # selected
	      }
	  }
	$i++;
      }
    
    if (param("start")) 
      {
	$start = param("start");
	$end = param("end");
      } 
    else 
      {
	$start = 1;
	$end = $page_size;
      }
    
    $words = param("words");

    if (@category) 
      {
	my ($file, $command, $return_value, 
	    $results_complete, $results_partial);
    
	print $search_summary_head;

	$i=0;
	foreach $category_entry (@category) 
	  {
	    if ($category_selection[$i] == 1) 
	      {
		$results_partial = "";
		
		# "swish-e" defines the normal swish search.
		if ($category_type[$i] =~ m/swish\-e/i) 
		  {
		    $file = "-f " . $category_file[$i];
		    $file =~ s/\,/ /g; # commas -> spaces
		    $command = "$swish -m $maximum_matches $file -w \"" .
		      $words . "\"";
		    # DEBUGGING::
		    # print $command;
		    $return_value = `$command`;
		    $results_partial .= &swish_parse($return_value, $start, $end);
		    $category_total[$i] = $total;
		  }
		
		# "calendar" defines the calendar backend, a mysql example.
		if ($category_type[$i] =~ m/calendar/i) 
		  {
		    $results_partial .= &process_calendar($category_file[$i], $words, 
						$start, $end);
		    $category_total[$i] = $total;
		  }

		$results_partial .= $search_results_tail;

		$results_partial_heading = $search_results_head;
		if ($start > 1) 
		  {
		    $newstart = $start - $page_size;
		    $newend = $newstart + $page_size - 1;
		    $previous_page = "<a href=\"$searchcgi\?start=$newstart" . 
		      "&end=$newend&words=" . &url_escape("$words") . 
		      "&scope=" . &url_escape("$category_entry") . "\">Previous Page</a>";
		    $results_partial_heading =~ s/Previous Page/$previous_page/;
		  }
		if ($end < $category_total[$i]) 
		  {
		    $newstart = $start + $page_size;
		    $newend = $newstart + $page_size - 1;
		    $next_page = "<a href=\"$searchcgi\?start=$newstart" .
		      "&end=$newend&words=" . &url_escape("$words") . 
		      "&scope=" . &url_escape("$category_entry") . "\">Next Page</a>";
		    $results_partial_heading =~ s/Next Page/$next_page/;
		  }
		$results_partial_heading =~ s/Entry Title/$category_entry/g;
		if ($category_total[$i] != 0) 
		  {
		    $results_complete = $results_complete . $results_partial_heading . $results_partial;
		  }
	      }
	    $i++;
	  }
	
	$i=0;
	foreach $category_entry (@category) 
	  {
	    if ($category_selection[$i] == 1) 
	      {
		$results_search_summary = $search_summary_body;
		if ($category_total[$i] == 0) 
		  {
		    $results_search_summary =~ s/Entry Title/$category_entry/g;
		    $results_search_summary =~ s/Entry Range//;
		  } 
		else 
		  {
		    $results_search_summary =~ s/Entry Title/<a href\=\"\#$category_entry\">$category_entry<\/a>/g;
		    if ($start <= $category_total[$i]) 
		      {
			if ($end > $category_total[$i]) 
			  {
			    $results_search_summary =~ s/Entry Range/$start - $category_total[$i]/;
			  } 
			else 
			  {
			    $results_search_summary =~ s/Entry Range/$start - $end/;
			  }
		      }
		  }
		$results_search_summary =~ s/Entry Total/$category_total[$i] Total/;
		print $results_search_summary;
	      }
	    $i++;
	  }
	print $search_summary_tail;
	print $results_complete;
      }
  }

# Print the search box.

$search_box_head =~ s/DEFAULT\_VALUE/$words/;
print $search_box_head;
$i=0;
foreach $category_entry (@category) 
  {
    if ($i==0) 
      {
	$search_box = $search_box_body_A;
      } 
    else 
      {
	$search_box = $search_box_body_B;
      }
    if ($category_selection[$i] == 0) 
      {  
	$search_box =~ s/CHECKED//i;
      }
    $search_box =~ s/Entry Title/$category_entry/g;
    print $search_box;
    $i++;
  }
print $search_box_tail;
print $document_tail;

exit(0);

### END OF MAIN PROCEDURE


### Use the replacement rules to manipulate the links.

sub link_replacement 
  {
    my($link, $replace_this, $with_this, $i);
    $link = $_[0];
    $i=0;
    foreach $replace_this (@replace) 
      {
	$with_this = $replacement[$i];
	$link =~ s/$replace_this/$with_this/i;
	$i++;
      }
    return($link);
  }

### Create a result entry for an html or text file.

sub process_html 
  {
    my($link, $title, $size, $score, $preview, $rvalue, $number);
    $link  = $_[0];
    $title = $_[1];
    $size  = $_[2];
    $score = $_[3];
    $number = $_[4];
    $preview = &head($link);
    
    # Kill html tags.  Change these expressions to your preference.
    $preview =~ s|<title>.*?</title>||gsi;
    $preview =~ s|<h2>.*?</h2>||gsi;
    $preview =~ s|<h3>.*?</h3>||gsi;
    $preview =~ s|<h1>.*?</h1>||gsi;
    $preview =~ s|<i>.*?</i>||gsi;
    $preview =~ s|<b>.*?</b>||gsi;
    $preview =~ s|< .*? >||gsx;
    $preview =~ s|\W*\n|\n|gsx;
    $preview =~ s|< .*? $||gsx;
    $preview =~ s|^.*? >||gsx;
    $preview = substr($preview, 0, 320);
    
    # Why don't people title their work?
    if ($title eq "") {$title="Untitled";}
    
    # Sometimes there is stray garbage in the size & score.
    $size =~ s|\D||gsx;
    $score =~ s|\D||gsx;
    
    $link = &link_replacement($link);
    
    $rvalue = $search_results_html;
    
    $rvalue =~ s/REPLACE LINK/$link/i;
    $rvalue =~ s/REPLACE TITLE/$title/i;
    $rvalue =~ s/REPLACE NUMBER/$number/i;
    $rvalue =~ s/REPLACE SIZE/$size/i;
    $rvalue =~ s/REPLACE SCORE/$score/i;
    $rvalue =~ s/REPLACE PREVIEW/$preview/i;

    return ($rvalue);
  }

### Create a result entry for a PDF file.

sub process_pdf 
  {
    
    my($link, $title, $size, $score, $preview, $rvalue, $number);
    my($pdfpreview, $pdftitle, $pdfauthor, $pdfpages, $filename);

    $link  = $_[0];
    $title = $_[1];
    $size  = $_[2];
    $score = $_[3];
    $number = $_[4];
    $preview = &head($link);
    
    $preview = &head($link);
    
    $preview =~ s|(<pdf>)(.*?)(</pdf>)||si;
    $pdfpreview = $2;
    
    $pdfpreview =~ s|(<title>)(.*?)(</title>)||si;
    $pdftitle = $2;
    
    $pdfpreview =~ s|(<author>)(.*?)(</author>)||si;
    $pdfauthor = $2;
    
    $pdfpreview =~ s|(<pages>)(.*?)(</pages>)||si;
    $pdfpages = $2;
    
    $filename = $link;
    
    while ($filename =~ m/\//) 
      {  
	$filename =~ s/^.*?\///gsx;
      }
    $filename =~ s/\.txt$//;

    # Sometimes there is stray garbage in the size & score.
    $size =~ s|\D||gsx;
    $score =~ s|\D||gsx;
    
    # Why don't people title their work?
    if ($title eq "") 
      { 
	$title="Untitled"; 
      }

    $link = &link_replacement($link);
    $link =~ s/\.pdf\.txt$/\.pdf/i;
    
    $rvalue = $search_results_pdf;
    
    $rvalue =~ s/REPLACE LINK/$link/i;
    $rvalue =~ s/REPLACE NUMBER/$number/i;
    $rvalue =~ s/REPLACE TITLE/$pdftitle/i;
    $rvalue =~ s/REPLACE FILENAME/$filename/i;
    $rvalue =~ s/REPLACE AUTHOR/$pdfauthor/i;
    $rvalue =~ s/REPLACE PAGES/$pdfpages/i;
    $rvalue =~ s/REPLACE SIZE/$size/i;
    $rvalue =~ s/REPLACE SCORE/$score/i;
    
    return ($rvalue);
  }

sub swish_parse 
  {
    my ($input, $out, $i, $error, $skip, $size, $remainder);
    my ($score, $size, $title, $link, $remainder);
    my ($line, $start, $end);
    
    $input  = $_[0];
    $start  = $_[1];
    $end    = $_[2];
    
    $i=0;
    $error=0;  # if there's an error, we don't find anything.
    
    $size="null";
    $remainder="null";
    $out="";
    
    # Keep reading until we've read it all.
    while ($input ne "") 
      {
	# Split off a line each time through the loop
	($line, $input) = split ('\n', $input, 2);
	
	$skip=1;
    
	# Don't split off any more info unless the info
	# is actually there!!!
	
	if (($line ne "") && ($line ne ".")) 
	  {
	    ($score, $link, $remainder) = split(' ', $line, 3);
	    $size = $remainder;
	    
	    if (($score ne "") || ($score ne "#"))
	      {
		($title, $size) = split('" ', $remainder, 2);
		$title =~ s|\"||osx;
	      }
	    else 
	      {
		$skip=0;
	      }
	  }
	else 
	  {
	    $skip=0;
	  }
    
	if (($score eq "err:") && ($error == 0)) 
	  {
	    $error = 1;
	    $out .= "Sorry, nothing was found.\n";
	  }
	
	# If we DO have a valid entry.  Make a preview and add it to
	# the list.
    
	if ((not($score eq "#")) && (not($score eq "search")) 
	    && (not($score eq "err:"))
	    && ($error == 0) && ($skip==1)) 
	  {
	    $i = $i+1;
	    if (($i <= $end) && ($i >= $start))
	      {
		if ($link =~ /\.pdf\.txt$/i) 
		  { 
		    $out .= &process_pdf($link, $title, $size, $score, $i);
		  }
		else 
		  { 
		    $out .= &process_html($link, $title, $size, $score, $i); 
		  }
	      }
	  }
      }
    $total = $i;
    return ($out);
  }

sub head 
  {
    my($houtput);
    
    $houtput = "";
    
    if (!open(input, "< $_[0]")) 
      {
	# You can handle this how you like - this is the case when a
	# file is not readable because of the world read permission,
	# yet it has been included in the index.  Either take it out
	# of the index or get your permissions striaghtened out!
	
	# I prefer that people just tell me about it.       
	
	$houtput = "Can't open $_[0].\n Please notify $swishiadmin.\n";
      }
    else 
      { 
	read input, $houtput, 2000; 
      }
    close (input);
    return ($houtput);
}

sub url_escape 
  {
    my ($temp);
    $temp = $_[0];
    $temp =~ s/\s/\+/g;
    return($temp);
  }

# This is used exclusively for the database search.

sub term_search 
  {
    $findme = $_[0];
    $_ = $_[1];
    if ($findme eq "") 
      { 
	return(1); 
      }
    @terms = split /\W/, $findme;
    foreach $term (@terms) 
      { 
	if (/$term/is) 
	  { 
	    return(1); 
	  } 
      }
    return(0);
  }

# Returns a series of filled out $search_results_calendar entries.

sub process_calendar 
  {
    use Mysql;
    
    my ($rvalue, $calendarlink, $QueryLimit, $q, $r);
    my ($terms, $start, $end);

    %ntomonth = 
      (
       "0", "All Months",
       "1", "January",
       "2", "February",
       "3", "March",
       "4", "April",
       "5", "May",
       "6", "June",
       "7", "July",
       "8", "August",
       "9", "September",
       "10", "October",
       "11", "November",
       "12", "December");
    
    $terms = $_[1];
    $start = $_[2];
    $end = $_[3];
    
    $calendarlink = "http://agprogram.tamu.edu/calendar.cgi?county=All+" .
      "Counties&month=REPLACE_MONTH&year=REPLACE_YEAR&keywords=&categorya" .
	"=Research&categorya=Extension&categorya=Academic&Find=Find&." .
	  "cgifields=categorya\#REPLACE_DAY";

    $QueryLimit = 50;

    if (!($dbh = Mysql->Connect($host,"Calendar"))) 
      { 
	print "$Mysql::db_errstr";
	die $Mysql::db_errstr;
      }
    
    $total=0;
    $rvalue = "";
    if ($sth = $dbh->Query("SELECT fromdate, todate, timespan, 
        name, description, location, address, city, state, url, contact, 
        contactemail, contactphone, sourcename, sourceorg, sourcedept, 
        sourcetitle, sourceemail, sourcephone, status, received, Cresearch, 
        Cextension, Cacademic, Tfielddays, Tcountyevents, Tstateevents, 
        Tinternalconf, Texternalconf, Tcommoditymeeting, Tlegislative
        FROM Events ORDER BY fromdate, todate LIMIT $QueryLimit")) 
      {
	while (@record = $sth->FetchRow) 
	  {
	    if (&term_search($terms, "$record[4]" . " $record[3]") == 1) 
	      {
		($year, $month, $day) = split(/-/, $record[0], 3);
		$month = int($month);
		
		$month = $ntomonth{$month};
		$total++;
		$q = $calendarlink;
		$q =~ s/REPLACE_MONTH/$month/;
		$q =~ s/REPLACE_YEAR/$year/;
		$q =~ s/REPLACE_DAY/$month$day/;
		
		$r = $search_results_calendar;
		$r =~ s/REPLACE NUMBER/$total/;
		$r =~ s/REPLACE LINK/$q/;
		$r =~ s/REPLACE TITLE/$record[3]/;
		$r =~ s/REPLACE DESCRIPTION/$record[4]/;
		$r =~ s/REPLACE LOCATION/$record[5]/;
		$r =~ s/REPLACE LOCATION/$record[5]/;
		$r =~ s/REPLACE DATE/ $month2 $day, $year/;
		if (($total >= $start) && ($total <= $end)) 
		  {
		    $rvalue .= $r;
		  }
	      }
	  }
      }
    return ($rvalue);
  }
