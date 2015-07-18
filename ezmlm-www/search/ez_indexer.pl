#!/usr/bin/perl -w
# ===========================================================================
# ez_indexer.pl
# 
# Author: Alessandro Ranellucci <aar@cpan.org>
# Copyright (c) 2005-06.
# 
# see http://ezmlm-www.sourceforge.net
#-----------------------------------------------------------------------
#

use strict;
use warnings;
our $VERSION = 1.2.1;

use Date::Parse 'str2time';
use Email::Simple;
use Getopt::Long;
use Mail::Ezmlm::Archive;
our @Lists = ();

#---------------------------------------------------------
#-- CONFIGURATION
#--
#-- Choose here your search engine. This can be 'kinosearch'
#-- or 'plucene'.
our $Engine = 'kinosearch';
#-- Repeat the following block for each list you want to 
#-- index.
#--
push @Lists, {
	list_dir => '/path/to/list/dir',
	index_dir => '/path/to/index/dir'
};
#--
#-- END CONFIGURATION
#---------------------------------------------------------


# DO NOT TOUCH ANYTHING BELOW THIS LINE
my %opt = ();
usage(1) unless GetOptions( \%opt, qw/verbose|v create|c update|u help|h/ );
usage(0) if $opt{help};
usage(1) unless ($opt{create} or $opt{update});
sub usage {
	my $rv = shift;
	print STDERR <<USAGE;
Usage: ez_indexer.pl COMMAND [OPTS]

COMMAND may be:
    -c, --create         Creates a new index
    -u, --update         Updates an existing index

OPTS may be:
    -h, --help           This usage info
    -v, --verbose        Print verbose information
USAGE
	
	exit($rv);
}

if ($Engine eq 'plucene') {
	eval q{
		use Plucene::Analysis::SimpleAnalyzer;
		use Plucene::Document::DateSerializer;
		use Plucene::Index::Writer;
	};
} elsif ($Engine ne 'kinosearch') {
	die "Invalid search engine '$Engine'\n";
}

LIST: foreach my $list (@Lists) {
	
	# load the right KinoSearch module (for backwards compatibility)
	my $kinosearch_version = '';
	if ($Engine eq 'kinosearch') {
		if ($opt{update} && -e "$list->{index_dir}/kinostats") {
			eval q{
				use Search::Kinosearch::Kindexer;
			};
			$kinosearch_version = 'old';
		} else {
			eval q{
				use KinoSearch::InvIndexer;
				use KinoSearch::Analysis::PolyAnalyzer;
			};
			$kinosearch_version = 'new';
		}
	}
	
	my $archive = Mail::Ezmlm::Archive->new($list->{list_dir})
		or die "Failed to load list archive at $list->{list_dir}\n";
	
	my $writer;
	if ($Engine eq 'kinosearch' && $kinosearch_version eq 'old') {
		$writer = Search::Kinosearch::Kindexer->new(
        	-mainpath       => $list->{index_dir},
        	-enable_datetime => 1,
        	-mode => ($opt{create} ? 'overwrite' : 'update')
        	#-temp_directory => '/tmp',
        );
        if ($opt{create}) {
			for my $field (qw/subject author/) {
				$writer->define_field(
					-name      => $field,
					-lowercase => 1,
					-tokenize  => 1
				);
			}
			$writer->define_field(
				-name      => 'message',
				-lowercase => 1,
				-tokenize  => 1,
				-store     => 0
			);
			$writer->define_field(
				-name      => 'date',
				-lowercase => 1,
				-tokenize  => 0
			);
		}
	} elsif ($Engine eq 'kinosearch' && $kinosearch_version eq 'new') {
		my $analyzer = KinoSearch::Analysis::PolyAnalyzer->new( language => 'en' );
		$writer = KinoSearch::InvIndexer->new(
			invindex => $list->{index_dir},
			create   => $opt{create} ? 1 : 0,
			analyzer => $analyzer,
		);
		$writer->spec_field( name => 'msg_id' );
		$writer->spec_field( name => 'subject', boost => 3 );
		$writer->spec_field( name => 'message', stored => 0 );
		$writer->spec_field( name => 'author' );
		$writer->spec_field( name => 'date' );
	} elsif ($Engine eq 'plucene') {
		my $analyzer = Plucene::Analysis::SimpleAnalyzer->new();
		$writer = Plucene::Index::Writer->new($list->{index_dir}, $analyzer, 1);
	}
	
	# get first message to index
	my $msg_id = 1;
	if ($opt{update} && -e "$list->{index_dir}/_ez_offset") {
		open COUNTER, "<$list->{index_dir}/_ez_offset";
		$msg_id = <COUNTER>;
		close COUNTER;
	}
	
	# get last message to index
	open MAX, "<$list->{list_dir}/archnum";
	my $max_id = <MAX>;
	chomp $max_id;
	close MAX;
	
	if ($max_id-$msg_id >= 0) {
		print "Indexing messages #$msg_id-#$max_id\n" if $opt{verbose};
	} else {
		print "No new messages\n" if $opt{verbose};
		next LIST;
	}
	
	for (; $msg_id <= $max_id; $msg_id++) {
		my $msg = $archive->getmessage($msg_id) or next;
		my $message = Email::Simple->new($msg->{text});
		print "Processing message #$msg_id\n" if $opt{verbose};
		
		if ($Engine eq 'kinosearch' && $kinosearch_version eq 'old') {
			my $doc = $writer->new_doc("$msg_id");
			$doc->set_field(message => $message->body);
			$doc->set_field(subject => $message->header("Subject"));
			$doc->set_field(author => $message->header("From"));
			$doc->set_field(date => $message->header("Date"));
			my @t = localtime(str2time($message->header("Date")));
			$doc->set_datetime( 1900+$t[5], 1+$t[4], @t[3,2,1,0] );
			$writer->add_doc($doc);
		} elsif ($Engine eq 'kinosearch' && $kinosearch_version eq 'new') {
			my $doc = $writer->new_doc;
			$doc->set_value( msg_id => $msg_id );
			$doc->set_value( message => $message->body );
			$doc->set_value( subject => $message->header("Subject") );
			$doc->set_value( author => $message->header("From") );
			$doc->set_value( date => $message->header("Date") );
			$writer->add_doc($doc);
		} elsif ($Engine eq 'plucene') {
			my $doc = Plucene::Document->new;
			$doc->add(Plucene::Document::Field->Keyword(id => "$msg_id"));
			$doc->add(Plucene::Document::Field->Text(message => $message->body));
			$doc->add(Plucene::Document::Field->Text(subject => $message->header("Subject")));
			$doc->add(Plucene::Document::Field->Text(author => $message->header("From")));
			my $t = Plucene::Document::DateSerializer::_to_base_36( str2time($message->header("Date"))*1000 );
			$doc->add(Plucene::Document::Field->Text(date => $t));
			$writer->add_document($doc);
		}
	}
	
	print "Finishing (this may take some time)..." if $opt{verbose};
	if ($Engine eq 'kinosearch' && $kinosearch_version eq 'old') {  
    	$writer->generate;
    	$writer->write_kindex;
    } elsif ($Engine eq 'kinosearch' && $kinosearch_version eq 'new') {
    	$writer->finish;
	} elsif ($Engine eq 'plucene') {
		$writer->optimize;
	}
	print "Done.\n" if $opt{verbose};
	
	
	open COUNTER, ">$list->{index_dir}/_ez_offset";
	print COUNTER $msg_id;
	close COUNTER;
}

1;
