#!/usr/bin/perl
#============================================
#         FILE:  gh_ascrisprB.pl
#        USAGE:  in the usage
#       AUTHOR:  Guihu Zhao
#      COMPANY:  Xiangya Hospital, Central South University
#      VERSION:  1.0.0
#      CREATED:  20181001
#=============================================

use warnings;
use Getopt::Long;
use Pod::Usage;
use File::Basename;

my ( $in, $out, $cas9_types );
GetOptions(
	"in=s"             => \$in,
	"out=s"            => \$out,
	"system=s" => \$system,
	"cas9_types=s" => \$cas9_types,
);

my $usage = <<USAGE;

USAGE

die "$usage" unless ( $in and $out and $cas9_types );
open OUT, ">$out" or die $!;

my $DNASeq = $in;
$DNASeq =~ /\[.*\/.*\]/;
if ( length($&) != 5 ) {
	die("Please check the input sequence!\n");
}

my $WTstr = $` . substr( $&, 1, 1 ) . $';
print $WTstr. "\n";

my $MUTstr = $` . substr( $&, 3, 1 ) . $';
print $MUTstr. "\n";

my $MUTpos = length($`) + 1;
print $MUTpos;

my @lines1 = split(/,/,$cas9_types);

foreach my $item1 (@lines1) {
	chomp $item1;
	my $query = '';
	$query = $item1;
	my $regexp = IUB_to_regexp($query);
	my @locations = match_positions( $regexp, $MUTstr );


	if (@locations) {

		my @outLen = ( 25, 24, 23, 22, 21, 20 );
		my $locBreak = 0;
		foreach my $i (@outLen) {
			my $count    = 1;
			my $startTmp = 0;
			my $endTmp   = 0;
			my $strTmp   = "";
			my $lenTmp = "";
			my $outLen   = $i;
			while ( $count <= @locations ) {
				my $location = $locations[ $count - 1 ];
				$count++;
				if ( $MUTpos < $location ) {
					$startTmp = $location + length($query) - $outLen - 1;
					$lenTmp   = $outLen;
					if ( $startTmp < 0 ) {
						if ($locBreak == 1){
							next;
						}
					$locBreak = 1;
						$startTmp = 0;
						$lenTmp   = $location + length($query) - 1;
					}
					$strTmp = substr( $MUTstr, $startTmp, $lenTmp );
					my $spacerLen = length($strTmp) - length($query);
					print OUT $spacerLen. " bp spacer\t"
				  . $strTmp . "\t"
				  . $query ."\t"
				  . $outLen. "\t"
				  . $location . "\t"
				  . $startTmp . "\n";
				}

			}
			print "\n";
		}
	}

}


#N = A or C or G or T (any)
#B = C or G or T (not A)
#D = A or G or T (not C)
#H = A or C or T (not G)
#V = A or C or G (not T)
#W = A or T (weak)
#S = C or G (strong)
#R = A or G (purine)
#Y = C or T (pyrimidine)
#M = A or C (amino)
#K = G or T (keto)

sub IUB_to_regexp {

	# A subroutine that, given a sequence with IUB ambiguity codes,
	# outputs a translation with IUB codes changed to regular expressions
	# These are the IUB ambiguity codes

	my ($iub) = @_;

	my $regular_expression = '';

	my %iub2character_class = (

		A => 'A',
		C => 'C',
		G => 'G',
		T => 'T',
		R => '[GA]',
		Y => '[CT]',
		M => '[AC]',
		K => '[GT]',
		S => '[GC]',
		W => '[AT]',
		B => '[CGT]',
		D => '[AGT]',
		H => '[ACT]',
		V => '[ACG]',
		N => '[ACGT]',
	);

	$iub =~ s/\^//g;

	# Translate each character in the iub sequence
	for ( my $i = 0 ; $i < length($iub) ; ++$i ) {
		$regular_expression .= $iub2character_class{ substr( $iub, $i, 1 ) };
	}
	return $regular_expression;
}

sub get_file_data {
	# to get data from a file given its filename
	my $input_filename;
	my @filedata;

	chomp( ($input_filename) = @_ );
	print $input_filename. "\n";

	open( INPUTFILENAME, $input_filename ) || die("can not open the file!");
	@filedata = <INPUTFILENAME>;
	close INPUTFILENAME;
	return @filedata;
}

sub match_positions {
	my ( $regexp, $sequence ) = @_;
	use strict;
	my @positions = ();
	while ( $sequence =~ /$regexp/ig ) {
		push( @positions, pos($sequence) - length($&) + 1 )
	}
	return @positions;
}

