#!/usr/bin/perl
#============================================
#         FILE:  gh_ascrispr_str2fa.pl
#        USAGE:  in the usage
#       AUTHOR:  Guihu Zhao
#      COMPANY:  Xiangya Hospital, Central South University
#      VERSION:  1.0.0
#      CREATED:  20181128
#=============================================

use warnings;
use Getopt::Long;
use Pod::Usage;
use File::Basename;

my ( $in, $out, $strand, $input_types );
GetOptions(
	"in=s"            		=> \$in,
	"out=s"           		=> \$out,
	"strand=s"           	=> \$strand,
	"input_types=s"         => \$input_types,

);

my $usage = <<USAGE;

USAGE

die "$usage" unless ( $in and $out );
open OUT, ">$out" or die $!;

#my @OUT_ARRAY;

my (@inputSeq, @targetingType, @strandArray, @inputSeqPrint);
my ($inputSeq_ref, $targetingType_ref, $strand_ref, $inputSeqPrint_ref);

($inputSeq_ref, $targetingType_ref, $strand_ref, $inputSeqPrint_ref) = parseInputSeq($in);

@inputSeq 		= @$inputSeq_ref;
@targetingType 	= @$targetingType_ref;
@strandArray 	= @$strand_ref;
@inputSeqPrint 	= @$inputSeqPrint_ref;

for ( my $i = 0 ; $i < @inputSeq ; $i++ ) {

	my $seq_WT = $inputSeq[0];
	my $seq_WTRev = $inputSeq[1];

	my $iInputSeq        	= $inputSeq[$i];
	my $iTargetingType 		= $targetingType[$i];
	my $iStrand       		= $strandArray[$i];
	my $iInputSeqPrint   	= $inputSeqPrint[$i];

	if ($iStrand eq $strand){
		print OUT ">$iTargetingType"."_"."$iStrand\n$iInputSeq"."_$seq_WT\n";
	}elsif($strand eq "all"){
		if($iStrand eq "+"){
			print OUT ">$iTargetingType"."_"."$iStrand\n$iInputSeq"."_$seq_WT\n";
		}else{
			print OUT ">$iTargetingType"."_"."$iStrand\n$iInputSeq"."_$seq_WTRev\n";
		}
	}

}

sub parseInputSeq {
	my ($seq) = @_;

	###################################################
	my $DNASeq    = $seq;
	my $DNASeqLen = length($DNASeq);

	my $leftLoc = index( $DNASeq, "[" );

	#print $leftLoc. "\n";

	my $rightLoc = index( $DNASeq, "]" );

	#print $rightLoc. "\n";

	my $sepLoc = index( $DNASeq, "/" );

	#print $sepLoc. "\n";

	my $N1 = substr( $DNASeq, $leftLoc + 1, $sepLoc - $leftLoc - 1 );
	my $N2 = substr( $DNASeq, $sepLoc + 1,  $rightLoc - $sepLoc - 1 );

	#print $N1. "\n";
	#print $N2. "\n";

	if ( ( $N1 eq "-" ) and ( $N2 eq "-" ) ) {
		die("Please check the input sequence!\n");
	}

###################################################
	$DNASeq =~ /\[.*\/.*\]/;

	my $WTstr_print;
	my $WT_rev_comp_str_print;
	my $MUTstr_print;
	my $MUT_rev_comp_str_print;

	my $WTstr;
	my $WT_rev_comp_str;
	my $MUTstr;
	my $MUT_rev_comp_str;

	my $MUTposStartWT;
	my $MUTposEndWT;
	my $MUTposStartWT_rev;
	my $MUTposEndWT_rev;

	my $MUTposStartMut;
	my $MUTposEndMut;
	my $MUTposStartMut_rev;
	my $MUTposEndMut_rev;

	if ( $N1 eq "-" ) {
		my $insertNum = length($N2);
		$WTstr_print            = $` . multipyStr( "2", $insertNum ) . $';
		$WT_rev_comp_str_print  = reverse_complement($WTstr_print);
		$MUTstr_print           = $` . substr( $&, 3, $insertNum ) . $';
		$MUT_rev_comp_str_print = reverse_complement($MUTstr_print);

		# input sequence
		$WTstr            = $` . $';
		$WT_rev_comp_str  = reverse_complement($WTstr);
		$MUTstr           = $` . substr( $&, 3, length($N2) ) . $';
		$MUT_rev_comp_str = reverse_complement($MUTstr);

		$MUTposStartWT     = length($`) + 1;
		$MUTposEndWT       = length($`) + 1;
		$MUTposStartWT_rev = length($') + 1;
		$MUTposEndWT_rev   = length($') + 1;

		$MUTposStartMut     = length($`) + 1;
		$MUTposEndMut       = length($`) + length($N2);
		$MUTposStartMut_rev = length($') + 1;
		$MUTposEndMut_rev   = length($') + length($N2);

	}
	elsif ( $N2 eq "-" ) {
		my $missingtNum = length($N1);
		$WTstr_print            = $` . substr( $&, 1, $missingtNum ) . $';
		$WT_rev_comp_str_print  = reverse_complement($WTstr_print);
		$MUTstr_print           = $` . multipyStr( "3", $missingtNum ) . $';
		$MUT_rev_comp_str_print = reverse_complement($MUTstr_print);

		# input sequence
		$WTstr            = $` . substr( $&, 1, length($N1) ) . $';
		$WT_rev_comp_str  = reverse_complement($WTstr);
		$MUTstr           = $` . $';
		$MUT_rev_comp_str = reverse_complement($MUTstr);

		$MUTposStartWT     = length($`) + 1;
		$MUTposEndWT       = length($`) + length($N1);
		$MUTposStartWT_rev = length($') + 1;
		$MUTposEndWT_rev   = length($') + length($N1);

		$MUTposStartMut     = length($`) + 1;
		$MUTposEndMut       = length($`) + 1;
		$MUTposStartMut_rev = length($') + 1;
		$MUTposEndMut_rev   = length($') + 1;

	}
	else {

		$WTstr_print            = $` . substr( $&, 1, length($N1) ) . $';
		$WT_rev_comp_str_print  = reverse_complement($WTstr_print);
		$MUTstr_print           = $` . substr( $&, length($N1)+1+1, length($N2) ) . $';
		$MUT_rev_comp_str_print = reverse_complement($MUTstr_print);

		# input sequence
		$WTstr            = $` . substr( $&, 1, length($N1) ) . $';
		$WT_rev_comp_str  = reverse_complement($WTstr);
		$MUTstr           = $` . substr( $&, length($N1)+1+1, length($N2) ) . $';
		$MUT_rev_comp_str = reverse_complement($MUTstr);

		$MUTposStartWT     = length($`) + 1;
		$MUTposEndWT       = length($`) + 1;
		$MUTposStartWT_rev = length($') + 1;
		$MUTposEndWT_rev   = length($') + 1;

		$MUTposStartMut     = length($`) + 1;
		$MUTposEndMut       = length($`) + 1;
		$MUTposStartMut_rev = length($') + 1;
		$MUTposEndMut_rev   = length($') + 1;

	}

	#print $WTstr_print. "\n";
	#print $WT_rev_comp_str_print. "\n";
	#print $MUTstr_print. "\n";
	#print $MUT_rev_comp_str_print. "\n";
	#
	#print "\n";
	#
	#print $WTstr. "\n";
	#print $WT_rev_comp_str. "\n";
	#print $MUTstr. "\n";
	#print $MUT_rev_comp_str. "\n";

###################################################
	my @inputSeq = ( $WTstr, $WT_rev_comp_str, $MUTstr, $MUT_rev_comp_str );
#	my @targetingType = ( "WT", "WT", "Varied", "Varied" );
	my @targetingType;

	if($input_types eq "sequence"){
		@targetingType = ( "WT", "WT", "Varied", "Varied" );
	}else{
		@targetingType = ( "Reference", "Reference", "Alternative", "Alternative" );
	}

	my @strandArray       = ( "+",  "-",  "+",  "-" );

	my @inputSeqPrint = (
		$WTstr_print,  $WT_rev_comp_str_print,
		$MUTstr_print, $MUT_rev_comp_str_print
	);

	return (\@inputSeq, \@targetingType, \@strandArray, \@inputSeqPrint);
}

sub multipyStr {
	my ( $seq, $times ) = @_;
	my $outStr = "";
	for ( my $i = 0 ; $i < $times ; $i++ ) {
		$outStr = $seq . $outStr;
	}
	return $outStr;
}


# A subroutine to compute the reverse complement of DNA sequence
sub reverse_complement {
	my $dna = shift;

	# reverse the DNA sequence
	my $revcomp = reverse($dna);

	# complement the reversed DNA sequence
	$revcomp =~ tr/ACGTacgt/TGCAtgca/;
	return $revcomp;
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

