<?php
$_head_profile_attr = '';
if ( bb_is_profile() ) {
	global $self;
	if ( !$self ) {
		$_head_profile_attr = ' profile="http://www.w3.org/2006/03/hcard"';
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"<?php bb_language_attributes( '1.1' ); ?>>
<head<?php echo $_head_profile_attr; ?>>
	<meta http-equiv="X-UA-Compatible" content="IE=8" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php bb_title() ?></title>
	<link rel="stylesheet" href="<?php bb_stylesheet_uri(); ?>" type="text/css" />
<?php if ( 'rtl' == bb_get_option( 'text_direction' ) ) : ?>
	<link rel="stylesheet" href="<?php bb_stylesheet_uri( 'rtl' ); ?>" type="text/css" />
<?php endif; ?>

<?php bb_feed_head(); ?>

<?php bb_head(); ?>

</head>


<body>
<br>
<center>
<div style="font-size:12pt; font-weight:bold">
<a href="http://type2.com/s">Home</a> / <a href="http://type2.com/cgi-bin/search.cgi">Search Mail Archives</a> / <a href="http://type2.com/s/?page_id=222">Blog</a> / <a href="http://type2.com/s/?page_id=821">BartnoTek</a> / <a href="http://type2.com/s/?page_id=830">Live Chat</a> / <a href="http://type2.com/s/?page_id=29">Technical Articles</a> / <a href="http://type2.com/forums">Forums</a><br><br>
</div>
<div style="font-size:10pt">
These forums are for those who prefer an alternative to mailing lists.<br>They follow the same standards as the mailing list in regards to content, meaning that all post
s should contain vw bus content.<br>The only exception to this is under the Web Site forum where topics regarding the site and it's functionality may take place.
</div>
</center>
<br>

<body id="<?php bb_location(); ?>">
	<div id="wrapper">
		<div id="header" role="banner">
			<h1><a href="<?php bb_uri(); ?>"><?php bb_option('name'); ?></a></h1>
			<?php if ( bb_get_option('description') ) : ?><p class="description"><?php bb_option('description'); ?></p><?php endif; ?>

<?php if ( !in_array( bb_get_location(), array( 'login-page', 'register-page' ) ) ) login_form(); ?>

			<div class="search">
<?php search_form(); ?>
			</div>
		</div>
		<div id="main">

<?php if ( bb_is_profile() ) profile_menu(); ?>
