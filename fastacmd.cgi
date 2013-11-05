#!/usr/bin/perl

## Set environment, where the BLAST programs are installed
$ENV{PATH} = "/path/to/blast/bin:$ENV{PATH}";

## Use things
use strict;
use warnings;
use CGI;
use File::Spec;

## Define variables
my $cgi_o = CGI->new();
my $db_root = "/path/to/blast/db";
my $err;
my $out;

## Fetch variables from PHP exec() command
my $db = $ARGV[0];
my $id = $ARGV[1];
my $posfrom = $ARGV[2];
my $posto = $ARGV[3];
my $st = $ARGV[4];

## Check that the DB and ID are defined
## This is already checked by fastacmd.php, but we do it again just in case
unless (defined $db) {
	$err = "The database parameter, <code>-d</code> was not specified.";
}
else {
	$db = File::Spec->catfile($db_root, $db);
}
unless (defined $id) {
	$err = "The search by ID parameter, <code>-s</code> was not specified.";
}
else {
	if ($id =~ m/[;|>|<|'|"|\`|:|\/|\\|*|?|!|&]/) {
	$err = "The search by ID parameter, <code>-s</code>, contains a non-valid character.";
	}
}

## Declare command. Use nice to reduce server load
my $fastacmd = "nice -n 19 fastacmd -d " . $db . " -s " . $id;

## If positions are defined, check if they are digits
if (defined $posfrom && defined $posto && $posfrom =~ m/./ && $posto =~ m/./) {
	if ($posfrom !~ m/^\d+$/) {
		$err = "ERROR: Start position is not an integer.";
	}
	elsif ($posto !~ m/^\d+$/) {
		$err = "ERROR: End position is not an integer."
	}
	else {
		$fastacmd .= " -L " . $posfrom . "," . $posto;
	}
}

## If starting position is defined, check if it contains the correct value (+ or -, and not anything else)
if (defined $st && $st =~ m/./) {
	if ($st !~ m/^[\+|-]$/) {
		$err = "ERROR: Strand can only be + or -."
	}
	elsif ($st =~ m/-/) {
		$fastacmd .= " -S 2";
	}
	else {
		$fastacmd .= " -S 1";
	}
}

## Catches all errors
if (defined $err) {
    $out = "ERR\n";
    $out .= $err."\n";
    $out .= $fastacmd;
}
## If no errors are caught, assign the output from fastacmd to the $out variable
else {
    $out .= `$fastacmd`;

	if ($out !~ m/^>/ && $out =~ m/.+/) {
		$out = "ERR\n";
		$out .= "Command execution error\n";
		$out .= $fastacmd;
	}
	elsif ($out !~ m/^>/) {
		$out = "ERR\n";
		$out .= "No sequence was found with the ID <code>$id</code>\n";
		$out .= $fastacmd;
	}
}

## Print is used, because we are going to send the raw output back to the PHP script (function is called by PHP exec)
print $out;
