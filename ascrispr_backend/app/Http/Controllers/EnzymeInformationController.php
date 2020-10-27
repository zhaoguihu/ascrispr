<?php

namespace App\Http\Controllers;

use App\Models\EnzymeInformation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class EnzymeInformationController extends Controller
{

    public function getEnzymesInfo(Request $request)
    {
//        $seq, $PAM, $str_for_link
        $params = $request->input('params');

        $time_stamp = $params['time_stamp'];
        $spacerSeq_original = $params['spacerSeq_original'];
        $PAMSeq_original = $params['PAM_original'];
        $spacerSeq = $params['spacerSeq'];
        $PAMSeq = $params['PAMSeq'];
        $Crispr_Type = $params['Crispr_Type'];
        $crispr_system = $params['crispr_system'];
        $Targeting_Strand = $params['Targeting_Strand'];
        $Direction = $params['Direction_link'];
        $N1 = $params['N1'];
        $N2 = $params['N2'];
        $spacerStart = $params['spacerStart'];
        $spacerEnd = $params['spacerEnd'];
        $PAMStart = $params['PAMStart'];
        $PAMEnd = $params['PAMEnd'];
        $MUTposStart = $params['MUTposStart'];
        $MUTposEnd = $params['MUTposEnd'];
        $PAM_IUB = $params['PAM_IUB'];
        $seq_name_hide = $params['seq_name_hide'];

        $file_name = "enzymesInformation.txt";

        //判断文件夹是否已存在
        if(!Storage::disk('uploads')->exists($time_stamp)){
            Storage::disk('uploads')->makeDirectory($time_stamp);
        }
        $upload_dir = public_path().'/uploads/'.$time_stamp;

        // annotate server side
        $enzymesInfoFile = public_path().'/uploads/'.$file_name;
        $out = "$upload_dir/matchedEnzymes.txt";

        // $path = $main_path . "uploads/ ";
        $plFilePath = "do_annovar/ ";
        $param1 = " -enzymesInfo " . $enzymesInfoFile . " -seq " . $spacerSeq_original.$PAMSeq_original . " -out " . $out;
        exec("perl /var/www/ascrispr_backend/public/bin/gh_matchSingleSeq.pl " . $param1 . " > " . "$upload_dir/log.log 2>&1");

        // echo "<p class='keyword'>$file_name and perl /lustre/inspur/software/annovar/bin/gh_matchSingleSeq.pl "
        // . " $param1 </p>";

        $resultsFile = $out;

        if ($crispr_system=="cas9") {
            //     cas9
            $guideSeq_title =  $spacerSeq_original." + ".$PAMSeq_original;
            $guideSeq =  $spacerSeq_original.$PAMSeq_original;
            $star = $spacerStart;
            $end = $PAMEnd;
            $anotherSeq = $this->getAnotherSeq($guideSeq, $star, $end, $MUTposStart, $MUTposEnd, $N1, $N2, $Targeting_Strand, $Direction);
        }else{
            //     cpf1
            $guideSeq_title =  $PAMSeq_original." + ".$spacerSeq_original;
            $guideSeq =  $PAMSeq_original.$spacerSeq_original;

            $star = $PAMStart;
            $end = $spacerEnd;

            $anotherSeq = $this->getAnotherSeq($guideSeq, $star, $end, $MUTposStart, $MUTposEnd, $N1, $N2, $Targeting_Strand, $Direction);
        }

        $data_out = [];
        $i = 0;
        $data = $this->read_data_file($resultsFile);
        foreach ($data as $ele) {
            $enzyme = $ele[0];
            $recognition_sequence = $ele[1];
            $supplier  = $ele[2];
            $incubation_temperature  = $ele[3];
            $recommended_buffer  = $ele[4];
            $enzyme_loc  = $ele[5];

            if ($crispr_system=="cas9"){
                //             cas9
                $MUTposStart_relative = $MUTposStart - $spacerStart;
            }else{
                //             cpf1
                $MUTposStart_relative = $MUTposStart - $PAMStart;
            }

            $regexp = $this->IUB_to_regexp($recognition_sequence);
            $positions_anotherSeq = $this->match_positions ($regexp, $anotherSeq);

            if (count($positions_anotherSeq)>0){
                $is_allele_specific = FALSE;
//             $positions_guideSeq = match_positions ($regexp, $guideSeq);

//             //             WT and Varied seqences are cover by current enzyme.
//             if($N1 == "-"){

//             }elseif ($N2 == "-"){

//             }else{

//                 if($MUTposStart_relative>=$enzyme_loc && $MUTposStart_relative <= $enzyme_loc + strlen($recognition_sequence)){
//                     //              MUTpos(WT or Varied) is covered by enzyme
//                     $enzyme_allele_for_mut = substr($recognition_sequence, $MUTposStart_relative - $enzyme_loc, 1);

//                     if($Targeting_Strand == "WT"){
//                         if($N1==$enzyme_allele_for_mut){

//                         }else {

//                         }

//                     }else{
//                         if($N2==$enzyme_allele_for_mut){

//                         }else {

//                         }
//                     }

//                 }else {

//                 }
//             }

            }else {
                $is_allele_specific = TRUE;
            }

            if($is_allele_specific){
                if($Targeting_Strand == "WT"){
                    $allele_specific = "Yes; WT Strand Specific";
                }elseif($Targeting_Strand == "Varied"){
                    $allele_specific = "Yes; Varied Strand Specific";
                }elseif($Targeting_Strand == "Reference"){
                    $allele_specific = "Yes; Reference Strand Specific";
                }elseif($Targeting_Strand == "Alternative"){
                    $allele_specific = "Yes; Alternative Strand Specific";
                }

            }else {
                $allele_specific = "No";
            }

            $data_out[$i]['enzyme'] = $enzyme;
            $data_out[$i]['recognition_sequence'] = $recognition_sequence;
            $data_out[$i]['supplier'] = $supplier;
            $data_out[$i]['incubation_temperature'] = $incubation_temperature;
            $data_out[$i]['recommended_buffer'] = $recommended_buffer;
            $data_out[$i]['allele_specific'] = $allele_specific;

            $i = $i + 1;

        }
        return $data_out;
    }

// func to read data from flat file
    public function read_data_file($file)
    {
        $data = array();
        $fp = fopen($file, "r");
        if (! $fp) {
            echo "<div id='error'>file open eror : $php_errormsg-$file</div>";
        } else {
            while (($row1 = fgets($fp)) !== false) {
                $ele = explode("\t", $row1);
                $data[] = $ele;
            }
        }
        return $data;
    }

// func to read data from flat file
    public function read_data_letter($file, $col, $val)
    {
        $data = array();
        $fp = fopen($file, "r");
        if (! $fp) {
            echo "<div id='error'>file open eror : $php_errormsg</div>";
        } else {
            $flag = false;
            while (($row1 = fgets($fp)) !== false) {
                $ele = explode("\t", $row1);

                if (preg_match("/^".$val."[0-9A-Za-z\-]+$/i", $ele[$col])){

                    $data[] = $ele;
                    $flag = true;
                    continue;
                }
                if ($flag)
                    break;
            }
        }
        return $data;
    }


// func to read data from flat file
    public function read_data_f($file, $col, $val)
    {
        $data = array();
        $fp = fopen($file, "r");
        if (! $fp) {
            echo "<div id='error'>file open eror : $php_errormsg</div>";
        } else {
            $flag = false;
            while (($row1 = fgets($fp)) !== false) {
                $ele = explode("\t", $row1);

                if ($ele[$col] == $val){

                    $data[] = $ele;
                    $flag = true;
                    continue;
                }
                if ($flag)
                    break;
            }
        }
        return $data;
    }

    public function export_to_file($file, $variable) {
        $fopen = fopen($file, 'wb');
        if (!$fopen) {
            return false;
        }
        fwrite($fopen, "<?php\nreturn ".var_export($variable, true).";\n?>");
        fclose($fopen);
        return true;
    }

    public function getAnotherSeq($guideSeq, $star, $end, $MUTposStart, $MUTposEnd, $N1, $N2, $Targeting_Strand, $Direction){

        $N1_inner = $N1;
        $N2_inner = $N2;
        if($Direction == "minus"){
            if($N1 !=='-'){
                $N1_inner = $this->str_complement($N1);
            }
            if($N2 !=='-'){
                $N2_inner = $this->str_complement($N2);
            }
        }

        if($N1 == "-"){
            if($Targeting_Strand=="WT"){
                //         insertion
                if($MUTposStart<$star && $MUTposEnd >$star && $MUTposEnd <= $end){
                    $posStart = 0;
                    $posEnd = 0;

                }elseif($MUTposStart>=$star && $MUTposEnd <= $end){
                    $posStart = $MUTposStart - $star;
                    $posEnd = 0;

                }elseif($MUTposStart<$end && $MUTposEnd >$end && $MUTposStart >= $star){
                    $posStart = $MUTposStart - $star;
                    $posEnd = 0;
                }

                $type = 1;
                $N = $N2_inner;
                $anotherSeq = $this->modify_str_pos($guideSeq, $posStart, $posEnd, $type, $N);
            }else{
                //         deletion
                if($MUTposStart<$star && $MUTposEnd >$star && $MUTposEnd <= $end){
                    $posStart = 0;
                    $posEnd = $MUTposEnd - $star;

                }elseif($MUTposStart>=$star && $MUTposEnd <= $end){
                    $posStart = $MUTposStart - $star;
                    $posEnd = $MUTposEnd - $star;

                }elseif($MUTposStart<$end && $MUTposEnd >$end && $MUTposStart >= $star){
                    $posStart = $MUTposStart - $star;
                    $posEnd = $end - $star;
                }

                $type = 2;
                $N = "";
                $anotherSeq = $this->modify_str_pos($guideSeq, $posStart, $posEnd, $type, $N);
            }

        }elseif ($N2 == "-"){
            if($Targeting_Strand=="WT"){
                //         deletion
                if($MUTposStart<$star && $MUTposEnd >$star && $MUTposEnd <= $end){
                    $posStart = 0;
                    $posEnd = $MUTposEnd - $star;

                }elseif($MUTposStart>=$star && $MUTposEnd <= $end){
                    $posStart = $MUTposStart - $star;
                    $posEnd = $MUTposEnd - $star;

                }elseif($MUTposStart<$end && $MUTposEnd >$end && $MUTposStart >= $star){
                    $posStart = $MUTposStart - $star;
                    $posEnd = $end - $star;
                }

                $type = 2;
                $N = "";
                $anotherSeq = $this->modify_str_pos($guideSeq, $posStart, $posEnd, $type, $N);
            }else{
                //         insertion
                if($MUTposStart<$star && $MUTposEnd >$star && $MUTposEnd <= $end){
                    $posStart = 0;
                    $posEnd = 0;

                }elseif($MUTposStart>=$star && $MUTposEnd <= $end){
                    $posStart = $MUTposStart - $star;
                    $posEnd = 0;

                }elseif($MUTposStart<$end && $MUTposEnd >$end && $MUTposStart >= $star){
                    $posStart = $MUTposStart - $star;
                    $posEnd = 0;
                }

                $type = 1;
                $N = $N1_inner;
                $anotherSeq = $this->modify_str_pos($guideSeq, $posStart, $posEnd, $type, $N);
            }
        }else{
            //         SNP
            $posStart = $MUTposStart - $star;
            $posEnd = 0;
            $type = 3;

            if($Targeting_Strand=="WT"){
                $N = $N2_inner;
            }else{
                $N = $N1_inner;
            }

            $anotherSeq = $this->modify_str_pos($guideSeq, $posStart, $posEnd, $type, $N);

        }

        return $anotherSeq;
    }


// $type: 0:insertion, gt0: del or modify
    public function modify_str_pos($seq, $posStart, $posEnd, $type, $N){
        $seq = strtoupper($seq);

        if ($type==1){
            //         insertion
            $seq_replace = substr_replace($seq, $N, $posStart, 0);

        }elseif ($type==2){
            //         deletion
            $seq_replace = substr_replace($seq, "", $posStart, $posEnd - $posStart + 1);

        }elseif ($type==3){
            //         SNP
            $seq_replace = substr_replace($seq, $N, $posStart, 1);
        }

        return $seq_replace;
    }


    public function match_positions($regexp, $sequence){
        $pattern = "/$regexp/";
        preg_match_all($pattern,$sequence,$positions,PREG_OFFSET_CAPTURE);

//     print_r($positions);
        // print_r($flags);
        // print(count($positions,1));
        // print(count($positions[0]));

        return $positions[0];
    }



    public function IUB_to_regexp ($seq){

        # A subroutine that, given a sequence with IUB ambiguity codes,
        # outputs a translation with IUB codes changed to regular expressions
        # These are the IUB ambiguity codes
        $iub = $seq;
        $regular_expression = '';
        $iub2character_class = array(
            'A' => 'A',
            'C' => 'C',
            'G' => 'G',
            'T' => 'T',
            'R' => '[GA]',
            'Y' => '[CT]',
            'M' => '[AC]',
            'K' => '[GT]',
            'S' => '[GC]',
            'W' => '[AT]',
            'B' => '[CGT]',
            'D' => '[AGT]',
            'H' => '[ACT]',
            'V' => '[ACG]',
            'N' => '[ACGT]',
        );

        # Translate each character in the iub sequence
        for ( $i = 0 ; $i < strlen($iub) ; ++$i ) {
            $regular_expression .= $iub2character_class{ substr( $iub, $i, 1 ) };
        }

        return $regular_expression;
    }


    public function str_complement ($seq){

        $seq_complement = '';
        $seq_class = array(
            'A' => 'T',
            'C' => 'G',
            'G' => 'C',
            'T' => 'A',
            'a' => 't',
            'c' => 'g',
            'g' => 'c',
            't' => 'a',
        );

        # Translate each character in the iub sequence
        for ( $i = 0 ; $i < strlen($seq) ; ++$i ) {
            $seq_complement .= $seq_class{ substr( $seq, $i, 1 ) };
        }

        return $seq_complement;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EnzymeInformation  $enzymeInformation
     * @return \Illuminate\Http\Response
     */
    public function show(EnzymeInformation $enzymeInformation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EnzymeInformation  $enzymeInformation
     * @return \Illuminate\Http\Response
     */
    public function edit(EnzymeInformation $enzymeInformation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EnzymeInformation  $enzymeInformation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EnzymeInformation $enzymeInformation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EnzymeInformation  $enzymeInformation
     * @return \Illuminate\Http\Response
     */
    public function destroy(EnzymeInformation $enzymeInformation)
    {
        //
    }
}
