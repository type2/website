<?php
//common include file

//mysql database setup
$user = "webmaster";
$password = "rust-free";
$db = "openautoclassifieds";
$server = "localhost";


//OPENREALTY ADMIN PASS AND LOGIN
//DEFAULTS TO MYSQL DATABASE USER/PASS
$openadmin = "buysell-admin";
$openpassword = "rust-free";



//SITE INFORMATION
//use city and state? 'Y' or 'N'
global $use_city_state;
$use_city_state = "Y";



//used mostly for the email a friend function...
//but may come in handy elsewhere.
$baseurl = "http://www.type2.com/buysell";
$yourname = "Type2.com BuySell";
$youremail = "webmaster@type2.com";

//number of listings to list at once:
$listings_per_page = 15;


//AGENT ADMINISTRATION
//use linefeed in description fields 'Y' or 'N'
$linefeeds = "Y";

//maximum number of imges for a given listing
$max_agent_images =1;

//how large can an agent image be? (n bytes)
$max_agent_upload = 200000;


//INDIVIDUAL VEHICLE LISTING OPTIONS
//use email-a-friend option? 'Y' or 'N'
$friendmail = "N";

//maximum numer of images for one vehicle
$max_images = 12;

//max size of vehicle images (in bytes)
$max_prop_upload = 80000;

//available options for vehicles
//each option separated by a ||
//currently supports up to 10 options
$vehiclefeatureoptions = "Air Conditioning||Gas Heater||Rusty Rockers";

//the master list of states and provinces
$stateslist = 
"Australia||NZ||UK||Europe||CA-BC||CA-Alberta||CA-Sas||CA-Manitoba||CA-Ontario||CA-Quebec||CA-NB||CA-NS||Alabama||Alaska||Alberta||Arizona||Arkansas||British 
Columbia||California||Colorado||Connecticut||Delaware||District of Columbia||Florida||Georgia||Hawaii||Idaho||Illinois||Indiana||Iowa||Kansas||Kentucky||Louisiana||Maine||Manitoba||Maryland||Massachusetts||Michigan||Minnesota||Mississippi||Missouri||Montana||Nebraska||Nevada||New Brunswick||Newfoundland and Labrador||New Hampshire||New Jersey||New Mexico||New York||North Carolina||North Dakota||Northwest Territories||Nova Scotia||Nunavut||Ohio||Oklahoma||Ontario||Oregon||Pennsylvania||Prince Edward Island||Quebec||Rhode Island||Saskatchewan||South Carolina||South Dakota||Tennessee||Texas||Utah||Vermont||Virginia||Washington||West Virginia||Wisconsin||Wyoming||Yukon";

//the master list of car types
$cartypeslist = "Parts For Sale||Type 1 For Sale||Type 2 For Sale||Type 3 For Sale||Type 4 For Sale||Porsche For Sale||Other For Sale||Type 1 Wanted||Type 2 Wanted||Type 3 Wanted||Type 4 Wanted||Porsche Wanted||Other Wanted";

//the master list of car makes
$carmakeslist = "Volkswagen||Porsche||Other";
?>
