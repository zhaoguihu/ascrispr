<?php

namespace App\Http\Controllers;

use App\Models\Offtargets;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class OfftargetsController extends Controller
{
//    public function offtargetsInfo($seq, $pam, $time_stamp, $str_for_link)
    public function getOfftargetsInfo(Request $request)
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

        $seqId = $params['seqId'];
        $guideId = $params['guideId'];

        $inputType = $params['inputType'];

        $Targeting_Strand_tip_tmp = '';
        if($Targeting_Strand == "Varied"){
            $Targeting_Strand_tip_tmp = "This sequence is exactly the WT sequence.";
        }elseif($Targeting_Strand == "Alternative"){
            $Targeting_Strand_tip_tmp = "This sequence is exactly the Reference sequence.";
        }
//        if($Targeting_Strand == "WT"){
//            $Targeting_Strand_tip_tmp = "This sequence is exactly the Varied sequence.";
//        }elseif($Targeting_Strand == "Varied"){
//            $Targeting_Strand_tip_tmp = "This sequence is exactly the WT sequence.";
//        }elseif($Targeting_Strand == "Reference"){
//            $Targeting_Strand_tip_tmp = "This sequence is exactly the Alternative sequence.";
//        }elseif($Targeting_Strand == "Alternative"){
//            $Targeting_Strand_tip_tmp = "This sequence is exactly the Reference sequence.";
//        }
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

        $anotherSeq_spacer = substr($anotherSeq, 0, strlen($anotherSeq)-strlen($PAMSeq_original));

        # bugs
        $offtargetsInfoFile = public_path()."/uploads/".$time_stamp."/".$PAM_IUB.".".$seq_name_hide.".inputFile.o2.txt";

        $i = 0;
        $data_out = [];
        $data = $this->read_data_file($offtargetsInfoFile);
        foreach ($data as $ele) {
            $seqId_ot = $ele[0];
            $guideId_ot	 = $ele[1];
            $guideSeq_ot	 = $ele[2];
            $offtargetSeq	 = $ele[3];
            $mismatchPos_tmp	 = $ele[4];
            $mismatchCount	 = $ele[5];
//            $mitOfftargetScore	 = $ele[6];
//            $cfdOfftargetScore	 = $ele[7];
            $chrom	 = $ele[8];
            $start	 = $ele[9];
            $end	 = $ele[10];
            $strand	 = $ele[11];
            $locusDesc = $ele[12];

//            $offtargetSeq_for_show = $this->mark_mispos($offtargetSeq, $mismatchPos);

            $offtargetSeq_len = strlen($offtargetSeq);
            $pam_len = strlen($PAMSeq_original);
//            $offtargetSeq_tmp = substr($offtargetSeq, 0, $offtargetSeq_len-$pam_len);

//            if ($crispr_system=="cpf1" || $crispr_system=="cas12b") {
//                $offtargetSeq_tmp = substr($offtargetSeq, $pam_len, $offtargetSeq_len-$pam_len);
//            }else{
//                $offtargetSeq_tmp = substr($offtargetSeq, 0, $offtargetSeq_len-$pam_len);
//            }
            if($inputType == 'dbSNP'){
                if ($crispr_system=="cpf1" || $crispr_system=="cas12b") {
                    $offtargetSeq_tmp = substr($offtargetSeq, $pam_len, $offtargetSeq_len-$pam_len);
                }else{
                    $offtargetSeq_tmp = substr($offtargetSeq, 0, $offtargetSeq_len-$pam_len);
                }
            }else{
                if ($crispr_system=="cpf1") {
                    $offtargetSeq_tmp = substr($offtargetSeq, $pam_len, $offtargetSeq_len-$pam_len);
                }else{
                    $offtargetSeq_tmp = substr($offtargetSeq, 0, $offtargetSeq_len-$pam_len);
                }
            }


            $guideSeq_ot_len = strlen($guideSeq_ot);
            if ($crispr_system=="cas9") {
                $guideSeq_ot_tmp = substr($guideSeq_ot, 0, $guideSeq_ot_len-$pam_len);
            }else{
                $guideSeq_ot_tmp = substr($guideSeq_ot, $pam_len, $guideSeq_ot_len-$pam_len);
            }

            $mismatchPos = $this->get_mispos($guideSeq_ot_tmp, $offtargetSeq_tmp, $mismatchPos_tmp);

            $offtargetSeq_for_show = $this->mark_mispos($offtargetSeq_tmp, $mismatchPos);
//            $mitOfftargetScore = sprintf("%.8f", $mitOfftargetScore);
//            if($cfdOfftargetScore=="None"){
//                $cfdOfftargetScore = "--";
//            }else{
//                $cfdOfftargetScore = sprintf("%.8f", $cfdOfftargetScore);
//            }
            if(strtoupper($guideSeq_ot) == strtoupper($guideSeq) && $seqId == $seqId_ot && $guideId == $guideId_ot){
                $Targeting_Strand_tip = '';
                $tmparr = explode(strtoupper($anotherSeq_spacer),strtoupper($offtargetSeq));
                if(count($tmparr)>1){
                    $Targeting_Strand_tip = $Targeting_Strand_tip_tmp;
                }else {
                    $Targeting_Strand_tip = '';
                }

                $data_out[$i]['offtargetSeq_for_show'] = $offtargetSeq_for_show;
                $data_out[$i]['mismatchPos'] = $mismatchPos;
                $data_out[$i]['mismatchCount'] = $mismatchCount;
                $data_out[$i]['chrom'] = $chrom;
                $data_out[$i]['start'] = $start;
                $data_out[$i]['end'] = $end;
                $data_out[$i]['strand'] = $strand;
                $data_out[$i]['locusDesc'] = $locusDesc;
                $data_out[$i]['Targeting_Strand_tip'] = $Targeting_Strand_tip;

                $i = $i + 1;
            }
        }
        return $data_out;
    }

    public function get_mispos($guideSeq_ot_tmp, $otSeqTmp, $misMP){
        $guideSeq_ot = strtoupper($guideSeq_ot_tmp);
        $otSeq = strtoupper($otSeqTmp);

        $guideSeq_ot_arr = str_split($guideSeq_ot);
        $otSeq_arr = str_split($otSeq);

        $misMP_arr = $this->find_pos($misMP, "*");
        $str_tmp = '';
//        for($i=0; $i<count($guideSeq_ot_arr); $i++){
//            if($guideSeq_ot_arr[$i] != $otSeq_arr[$i]){
//                $str_tmp = $str_tmp.'*';
//            } else{
//                $str_tmp = $str_tmp.'.';
//            }
//        }
//        $misMP_out = $str_tmp;
        if(count($misMP_arr) > 3){
            for($i=0; $i<count($guideSeq_ot_arr); $i++){
                if($guideSeq_ot_arr[$i] != $otSeq_arr[$i]){
                    $str_tmp = $str_tmp.'*';
                } else{
                    $str_tmp = $str_tmp.'.';
                }
            }
            $misMP_out = $str_tmp;
        } else{
            $misMP_out = $misMP;
        }
        return $misMP_out;

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


    /*
     *$str 被查找字符串
     *$s 需要查找的字符串
     *$p 开始位置
     */

    public function find_pos($str,$s,$p=0){
        $pos_arr = array();
        $pos=0;
        $idx = 0;
        while($pos<strlen($str)){
            if($idx==0){
                $pos = strpos($str,$s,$pos);
                $idx = $idx + 1;
            }
            else{
                $pos = strpos($str,$s,$pos+1);
            }
            if( $pos !== false){
                $pos_arr[] = $pos;
            }else{
                break;
            }
        }
        return $pos_arr;
    }


    public function mark_mispos($otSeqTmp,$misMP){
        $otSeq = strtoupper($otSeqTmp); # strtolower
        $prefixStr = "<span style='color:red'>";
        $postfixStr= "</span>";

        $misMP_arr = $this->find_pos($misMP, "*");

        for($i=0; $i<count($misMP_arr); $i++){
            $strTmp =  $prefixStr.strtolower(substr($otSeq,$misMP_arr[$i], 1)).$postfixStr;
            $strArr[$misMP_arr[$i]] = $strTmp;
        }
        $tmp = 0;
        for($i=0; $i<count($misMP_arr); $i++){
            if($i==0){
                $strTmp = substr_replace($otSeq,$strArr[$misMP_arr[$i]],$misMP_arr[$i],1);
                $tmp = strlen($strArr[$misMP_arr[$i]])-1;
                $otSeq = $strTmp;
            }else{
                $strTmp = substr_replace($otSeq,$strArr[$misMP_arr[$i]],$misMP_arr[$i] + $tmp,1);
                $tmp = strlen($strArr[$misMP_arr[$i]])+$tmp-1;
                $otSeq = $strTmp;
            }
        }
        return $otSeq;
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
//            $seq_complement .= $seq_class{ 'A' };
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
     * @param  \App\Models\Offtargets  $offtargets
     * @return \Illuminate\Http\Response
     */
    public function show(Offtargets $offtargets)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Offtargets  $offtargets
     * @return \Illuminate\Http\Response
     */
    public function edit(Offtargets $offtargets)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Offtargets  $offtargets
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Offtargets $offtargets)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Offtargets  $offtargets
     * @return \Illuminate\Http\Response
     */
    public function destroy(Offtargets $offtargets)
    {
        //
    }
}
