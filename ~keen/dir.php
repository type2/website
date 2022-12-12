<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0//EN"
        "http://www.w3.org/TR/REC-html40/strict.dtd">
<html lang="en">
<head>
<title>Recursive Directory Index</title>
<link rel="stylesheet" href="style.css" title="WebAugur's Style" type="text/css" -->
</head>
<body>
<?php
Function fncLinkDir( $dirname )
  {
  /* Get rid of those stupid filenames  */
  /* Was I sleepy when I did this?  Alrighty then.  */
  if ( is_file( $DOCUMENT_ROOT.$REQUEST_URI ) )
    {
        $REQUEST_URI = ereg_replace( $mefile = strrchr( $REQUEST_URI, "/" ), "/", $REQUEST_URI );
    };

  /* Open Current Working Directory for reading.  */
  if( !$dirid = @opendir( $dirname.$recurse ) )
    {
        print " &lt; - Unable to Open Directory";
        return 1;
    };

        /* Read the contents of the directory one by one  */
	while ($entry = @readdir($dirid)) 
          {
          /*  Begin File Exclusion; 
                 for long lists use an array and while loop 
                 to save you some editing time.  PHP 4 races through loops.
           */
              /* Do not list hidden files; begins with .  
                  Why: Indexing . will cause infinite loop and eventually 
                  a stack overfrow.
               */
              if ( strpos( $entry, "." ) === 0 )
               {
                   continue;
               }

              /* Do not list Microsoft Frontpage junk, either.  */
              if ( strpos( $entry, "_vti_" ) === 0 )
               {
                   continue;
               }
           /* End File Exclusion  */

              /* Add this entry to the listing */
              $dirEntries[] = $entry;
          }

          /* Sort listing alphabetically then reset to start */
          sort( $dirEntries );
          reset( $dirEntries );

          /* HTML, Open an unordered list */
          print "<ul>\n";

          /* Walk through the current directory  */
          $i = 0;
          while( $dirEntries[$i] )
            {
              /* Assign a short name  */
              $fileName = $dirname . "/" . $dirEntries[$i];
              $fileNameShort = $dirEntries[$i];

              if( is_dir( $fileName ) )
                /* It is a directory structure  */
                {
                    /* HTML, Open directory list item  */
                    print "\t<li class=\"folder\"><a href=\"$fileName\">$fileNameShort/</a>\n";

                    /* Recurse into subdirectory  */
                    fncLinkDir( $fileName );

                    /* HTML, Close the directory list item  */
                    print "</li>\n";
                }
              else  
                /* It is some type of file */
                { 
                    /* HTML, Create a list item entry.  */
                    print "\t<li class=\"link\"><a href=\"$fileName\">$fileNameShort</li>\n";
                }

              /* Increment the index else we loop forever, doh!  */
              $i ++;
            }

          /* HTML, Close the unordered list  */
          print "</ul>\n";

  /* Wait, we're finished? */
  };

$indexuri = explode("?", $REQUEST_URI);
print "<h1>Index of ".urldecode($indexuri[0])."</h1>\n";
fncLinkDir(".", $recurse, $REQUEST_URI, $DOCUMENT_ROOT);
?>
<hr>
<p><a href="http://www.webaugur.com/wares/autoindex#indexer">Recursive Directory Indexer</a>/1.9.0, Copyright &copy; 2000 David L. Norris.</p>
</body>
</html>