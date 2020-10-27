#!/usr/bin/perl
#============================================
#         FILE:  gh_ascrisprA.pl
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

my ( $in, $out, $crispr_system, $PAMs,$seq_name,$input_types,$seedLength,$is_offtarget );
GetOptions(
	"in=s"            => \$in,
	"out=s"           => \$out,
	"crispr_system=s" => \$crispr_system,
	"PAMs=s"     => \$PAMs,
	"seq_name=s"     => \$seq_name,
	"input_types=s"     => \$input_types,
	"seedLength=i" => \$seedLength,
	"is_offtarget=s"            => \$is_offtarget,
);

my $usage = <<USAGE;

USAGE

die "$usage" unless ( $in and $out and $crispr_system and $PAMs );
open OUT, ">$out" or die $!;

my($file, $dir, $ext) = fileparse($out);

my $out_offtarget = $dir."offtargetFile.txt";
open OUT_OT, ">$out_offtarget" or die $!;

my @OUT_ARRAY;
my @OUT_ARRAY2;

$seedLength ||= 12;

###################################################
my $DNASeq    = $in;
my $DNASeqLen = length($DNASeq);
my $leftLoc = index( $DNASeq, "[" );
my $rightLoc = index( $DNASeq, "]" );
my $sepLoc = index( $DNASeq, "/" );
my $N1 = substr( $DNASeq, $leftLoc + 1, $sepLoc - $leftLoc - 1 );
my $N2 = substr( $DNASeq, $sepLoc + 1,  $rightLoc - $sepLoc - 1 );

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

my $inputSeq_type;

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

	$inputSeq_type = "insertion";

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

	$inputSeq_type = "deletion";
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
	$MUTposEndWT       = length($`) + length($N1);
	$MUTposStartWT_rev = length($') + 1;
	$MUTposEndWT_rev   = length($') + length($N1);

	$MUTposStartMut     = length($`) + 1;
	$MUTposEndMut       = length($`) + length($N2);
	$MUTposStartMut_rev = length($') + 1;
	$MUTposEndMut_rev   = length($') + length($N2);

	$inputSeq_type = "SNP";
}

print $WTstr_print. "\n";
print $WT_rev_comp_str_print. "\n";
print $MUTstr_print. "\n";
print $MUT_rev_comp_str_print. "\n";

print "\n";

print $WTstr. "\n";
print $WT_rev_comp_str. "\n";
print $MUTstr. "\n";
print $MUT_rev_comp_str. "\n";

###################################################
my $MUTpos = length($`) + length($N1);

###################################################
my @inputSeq = ( $WTstr, $WT_rev_comp_str, $MUTstr, $MUT_rev_comp_str );

my @targetingStrand;
if($input_types eq "sequence"){
	@targetingStrand = ( "WT", "WT", "Varied", "Varied" );
}else{
	@targetingStrand = ( "Reference", "Reference", "Alternative", "Alternative" );
}

my @direction       = ( "+",  "-",  "+",   "-" );

###################################################
my @PAMs_arr = split( /,/, $PAMs );
foreach my $item1 (@PAMs_arr) {
	chomp $item1;
	my $PAM_typeOut = $item1;
	my @PAM_info    = split( /:/, $item1 );
	my $PAM1        = $PAM_info[1];

	#  FnCpf1
	my $PAM0        = $PAM_info[0];
	if ($PAM0 eq "FnCpf1"){
		@outLen = (21);
	}else{
		if ( $crispr_system eq "cas9" ) {
			@outLen = (20);
			if ($PAM1 eq "NNNNGMTT" || $PAM1 eq "NNNNCRAA" || $PAM1 eq "NNNNGMAA" || $PAM1 eq "NNNNRYAC"){
				@outLen = (22);
			}
			if ($PAM1 eq "NNNNAC"){
				@outLen = (24);
			}
		}
		elsif ($crispr_system eq "cpf1") {
			@outLen = (23);
			if ($PAM1 eq "KYTV" || $PAM1 eq "TTV"){
				@outLen = (21);
			}
			if ($PAM1 eq "TTCN"){
				@outLen = (20);
			}
		}
		elsif ($crispr_system eq "cas12b") {
			@outLen = (23);
		}
		elsif ($crispr_system eq "casx") {
			@outLen = (20);
		}

	}

	my $regexp = IUB_to_regexp($PAM1);

	for ( my $i = 0 ; $i <= @inputSeq - 1 ; $i++ ) {
		my $iInputSeq        = $inputSeq[$i];
		my $iTargetingStrand = $targetingStrand[$i];
		my $iDirection       = $direction[$i];

		my $MUTposStart;
		my $MUTposEnd;
		my $str_print;
		if ( $iTargetingStrand eq "WT" or $iTargetingStrand eq "Reference") {
			if ( $iDirection eq "+" ) {
				$MUTposStart = $MUTposStartWT;
				$MUTposEnd   = $MUTposEndWT;

				$str_print = $WTstr_print;
			}
			else {
				$MUTposStart = $MUTposStartWT_rev;
				$MUTposEnd   = $MUTposEndWT_rev;

				$str_print = $WT_rev_comp_str_print;
			}
		}
		else {
			if ( $iDirection eq "+" ) {
				$MUTposStart = $MUTposStartMut;
				$MUTposEnd   = $MUTposEndMut;

				$str_print = $MUTstr_print;
			}
			else {
				$MUTposStart = $MUTposStartMut_rev;
				$MUTposEnd   = $MUTposEndMut_rev;

				$str_print = $MUT_rev_comp_str_print;
			}

		}

		my @locations = match_positions( $regexp, $iInputSeq );

		if (@locations) {
			my $locBreak = 0;

			foreach my $i (@outLen) {
				my $count          = 1;
				my $spacerStart    = 0;
				my $outStart       = 0;
				my $spacerEnd      = 0;
				my $spacerSequence = "";
				my $spacerLen      = "";
				my $outLen         = $i;

				while ( $count <= @locations ) {
					my $location = $locations[ $count - 1 ];
					$count++;

					my $PAM;
					my $PAMStart;
					my $PAMEnd;
					my $PAMLen = length($PAM1);

					my $whetherPrint = 0;

					if ( $crispr_system eq "cas9" ) {    # cas9
						$spacerStart = $location - $outLen;
						$outStart    = $location - $seedLength;
						$spacerEnd = $location - 1;

						if ( $spacerStart <= 0 ) {
							next;
						}

						$spacerSequence =
						  substr( $iInputSeq, $spacerStart - 1, $outLen );
						$spacerLen = length($spacerSequence);

						$PAMStart = $location;
						$PAMEnd   = $location + $PAMLen - 1;

						$PAM = substr( $iInputSeq, $PAMStart - 1, $PAMLen );

						if (   $MUTposStart <= $outStart
							&& $MUTposEnd >= $PAMEnd )
						{
							$whetherPrint = 1;
						}

						if (   $MUTposStart >= $outStart
							&& $MUTposStart <= $PAMEnd )
						{
							$whetherPrint = 1;
						}

						if ( $MUTposEnd >= $outStart && $MUTposEnd <= $PAMEnd )
						{
							$whetherPrint = 1;
						}

					}
					else {    # cpf1
						$spacerStart = $location + $PAMLen;
						$outStart    = $location + $PAMLen + $seedLength - 1;
						$spacerEnd = $location + $PAMLen + $outLen - 1;

						if ( $spacerEnd > $DNASeqLen ) {
							next;
						}

						$spacerSequence =
						  substr( $iInputSeq, $spacerStart - 1, $outLen );
						$spacerLen = length($spacerSequence);

						$PAMStart = $location;
						$PAMEnd   = $location + $PAMLen - 1;

						$PAM = substr( $iInputSeq, $PAMStart - 1, $PAMLen );

						if (   $MUTposStart <= $PAMStart
							&& $MUTposEnd >= $outStart )
						{
							$whetherPrint = 1;
						}

						if (   $MUTposStart >= $PAMStart
							&& $MUTposStart <= $outStart )
						{
							$whetherPrint = 1;
						}

						if ( $MUTposEnd >= $PAMStart && $MUTposEnd <= $outStart )
						{
							$whetherPrint = 1;
						}

					}

					my $GCcontent = getGCcontent($spacerSequence);
					my $self_complementarity = getSelfComplementarity($spacerSequence);

					if ($whetherPrint) {

						my $OUT_str = $count . "\t"
							  . $spacerSequence . "\t"
							  . $PAM . "\t"
							  . $PAM_typeOut . "\t"
							  . $iTargetingStrand . "\t"
							  . $iDirection . "\t"
							  . $GCcontent . "\t"
							  . "Enzyme Information \t"
							  . $N1 . "\t"
							  . $N2 . "\t"
							  . $outStart . "\t"
							  . $spacerStart . "\t"
							  . $spacerEnd . "\t"
							  . $PAMStart . "\t"
							  . $PAMEnd . "\t"
							  . $PAMLen . "\t"
							  . $MUTposStart . "\t"
							  . $MUTposEnd . "\t"
							  . $iInputSeq . "\t"
							  . $str_print . "\t"
							  . $self_complementarity . "\t"
							  . $inputSeq_type;

						push @OUT_ARRAY, $OUT_str;
						print " ok ".$OUT_str;
					}

					my $OUT_str2 = $count . "\t"
						  . $spacerSequence . "\t"
						  . $PAM . "\t"
						  . $PAM_typeOut . "\t"
						  . $iTargetingStrand . "\t"
						  . $iDirection . "\t"
						  . $GCcontent . "\t"
						  . "Enzyme Information \t"
						  . $N1 . "\t"
						  . $N2 . "\t"
						  . $outStart . "\t"
						  . $spacerStart . "\t"
						  . $spacerEnd . "\t"
						  . $PAMStart . "\t"
						  . $PAMEnd . "\t"
						  . $PAMLen . "\t"
						  . $MUTposStart . "\t"
						  . $MUTposEnd . "\t"
						  . $iInputSeq . "\t"
						  . $str_print . "\t"
						  . $self_complementarity . "\t"
						  . $inputSeq_type. "  mysequence \n";
					print $OUT_str2;
				}
				print "\n";
			}
		}
	}
}

#  TG[G/-]G
#  [-/AGG]AG
my @skip_list;
for ( my $i = 0 ; $i < @OUT_ARRAY ; $i++ ) {
	my @line_out = split( "\t", $OUT_ARRAY[$i] );

	for ( my $j = $i+1 ; $j < @OUT_ARRAY ; $j++ ) {
		my @line_in = split( "\t", $OUT_ARRAY[$j] );

		if ($line_out[8] eq "-" || $line_out[9] eq "-"){
			if ($line_out[1] eq $line_in[1] && $line_out[3] eq $line_in[3]){
				push @skip_list, $i;
				push @skip_list, $j;
			}
		}
	}
}

my $whetherOut = 1;
for ( my $i = 0 ; $i < @OUT_ARRAY ; $i++ ) {
	$whetherOut = 1;
	for ( my $j = 0; $j < @skip_list ; $j++ ) {
		if ($skip_list[$j] eq $i){
			$whetherOut = 0;
			last;
		}
	}
	if ($whetherOut){
		push @OUT_ARRAY2, $OUT_ARRAY[$i];
	}
}

my $gene_locus_o1;
foreach my $item1 (@PAMs_arr) {
	chomp $item1;
	my $PAM_typeOut = $item1;
	my @PAM_info    = split( /:/, $item1 );
	my $PAM1        = $PAM_info[1];

	my $o1 = $dir."$PAM1.$seq_name.inputFile.o1.txt";
	my $ot = $dir."$PAM1.$seq_name.inputFile.o2.txt";
	my @o1_data = get_file_data($o1);

	if($gene_locus_o1 eq ""){
		foreach my $o1_data_item (@o1_data) {
			chomp $o1_data_item;
			my @line_crispor = split( "\t", $o1_data_item );

			$gene_locus_o1  = $line_crispor[5];
			if($gene_locus_o1 ne "" && $gene_locus_o1 ne "targetGenomeGeneLocus"){
				last;
			}
		}
	}

	foreach my $OUT_ARRAY2_item (@OUT_ARRAY2) {
		chomp $OUT_ARRAY2_item;
		my @line_my = split( "\t", $OUT_ARRAY2_item );
		my $seq_my;

		if ( $crispr_system eq "cas9" ) {
			$seq_my = $line_my[1].$line_my[2];
		}
		else {
			$seq_my = $line_my[2].$line_my[1];
		}

		my $targetingStrand_my = $line_my[4];
		my $direction_my       = $line_my[5];

		my @PAM_info_my    = split( /:/, $line_my[3] );
		my $PAM1_my        = $PAM_info_my[1];

#		my seq and o1
		foreach my $o1_data_item (@o1_data) {
			chomp $o1_data_item;
			my @line_crispor = split( "\t", $o1_data_item );
			my @tmp    = split( /_/, $line_crispor[0] );
			my $targetingStrand_o1  = $tmp[0];
			my $seq_crispor = $line_crispor[2];

			# $PAM1 eq $PAM1_my
			if($seq_my eq substr($seq_crispor, 0, length($seq_my)+1) && $targetingStrand_o1 eq $targetingStrand_my && $PAM1 eq $PAM1_my){
				my $str = $OUT_ARRAY2_item;
				print OUT $str."\t".$o1_data_item."\t".$PAM1."\t".$gene_locus_o1."\n";
				last;
			}
		}
		if($is_offtarget eq 'offtarget'){
			my @ot_data = get_file_data($ot);
			foreach my $ot_data_item (@ot_data) {
				chomp $ot_data_item;
				my @line_crispor_ot = split( "\t", $ot_data_item );
				my $seq_crispor_ot = $line_crispor_ot[2];
				my @tmp    = split( /_/, $line_crispor_ot[0] ); #$seqId
				my $targetingStrand_ot  = $tmp[0];

				if($seq_my eq substr($seq_crispor_ot, 0, length($seq_my)+1) && $targetingStrand_ot eq $targetingStrand_my){
					print OUT_OT $ot_data_item."\t".$PAM1."\n";
					print $seq_my."\t".substr($seq_crispor_ot, 0, length($seq_my)+1)."\t\n";
					print $targetingStrand_my."\t".$targetingStrand_ot."\t\n";
				}
			}
		}
	}
}

###################################################
sub multipyStr {
	my ( $seq, $times ) = @_;
	my $outStr = "";
	for ( my $i = 0 ; $i < $times ; $i++ ) {
		$outStr = $seq . $outStr;
	}
	return $outStr;
}

sub getGCcontent {
	my ($seq) = @_;
	my $GC    = $seq =~ tr/GC/GC/;    # counting G or C
	my $AT    = $seq =~ tr/AT/AT/;    # counting A or T
	my $len   = $GC + $AT;
	my $GC_cont = $len ? sprintf( "%.3f", $GC / $len ) : 0;
	return $GC_cont;
}

sub getSelfComplementarity{
	my ($seq) = @_;
	my $seq_rv = reverse_complement($seq);
	my $backbone_regions = reverse_complement("AGGCTAGTCCGT");

	my $L = length($seq)-4-1;
	my $folding = 0;

	for (my $i=0; $i< length($seq)-4;$i++){

		if (getGCcontent(substr($seq, $i, 4)) >= 0.5){

			my $seq_tmp = substr($seq, $i, 4);
			my $seq_rv_tmp = substr($seq_rv, 0, $L-$i);

			if ($seq_rv_tmp =~/$seq_tmp/ or $backbone_regions =~/$seq_tmp/){
				$folding += 1;
			}
		}
	}
	my $score;
	$score += $folding * 1;
	return $score;
}

sub reverse_complement {
	my $dna = shift;
	my $revcomp = reverse($dna);
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

sub IUB_to_regexp {
	my ($iub) = @_;
	my $regular_expression = '';
	my %iub2character_class = (
		A => 'A',
		C => 'C',
		G => 'G',
		T => 'T',
		R => '[GA]',     #R(G or A)
		Y => '[CT]',
		M => '[AC]',
		K => '[GT]',
		S => '[GC]',
		W => '[AT]',
		B => '[CGT]',
		D => '[AGT]',
		H => '[ACT]',
		V => '[ACG]',
		N => '[A|C|G|T]',
	);

	$iub =~ s/\^//g;
	for ( my $i = 0 ; $i < length($iub) ; ++$i ) {
		$regular_expression .= $iub2character_class{ substr( $iub, $i, 1 ) };
	}
	return $regular_expression;
}

sub get_file_data {
	my $input_filename;
	my @filedata;
	chomp( ($input_filename) = @_ );
	open( INPUTFILENAME, $input_filename ) || die("can not open the file!");
	@filedata = <INPUTFILENAME>;
	close INPUTFILENAME;
	return @filedata;
}

sub match_positions_old {
	my ( $regexp, $sequence ) = @_;
	print $regexp."\t".$sequence." \n";
	use strict;
	my @positions = ();
	my $p;
	while ( $sequence =~ /($regexp)/ig ) {
		push( @positions, pos($sequence) - length($&) + 1 );
		$p = pos($sequence) - length($&) + 1;
		print $p."\t".$-[0]."\t".$+[0]."\t".$&." \n";
	}
	return @positions;
}

sub match_positions {
	my ( $regexp, $sequence ) = @_;
	print $regexp."\t".$sequence." \n";
	use strict;
	my @positions = ();
	my $p=0;
	my $seq_sub;
	for(my $i=0;$i<length($sequence);){
		$seq_sub = substr($sequence, $i, length($sequence));
		if($seq_sub =~ /$regexp/i){
			$p = length($`)+$i+1;
			push( @positions, $p );
			$i = $p;
			print $p."\t".$&." \n";
		}else{
			$i++;
		}
	}
	return @positions;
}
