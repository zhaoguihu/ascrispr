<?php

namespace App\Http\Controllers;

use App\Models\Ascrispr;
use App\Models\DBSNP;
use App\Models\DBSNP38;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\File;
use App\Http\Resources\AscrisprCollection;
use App\Http\Resources\DBSNPCollection;
use App\Http\Resources\DBSNP38Collection;


class AscrisprController extends Controller
{

    private function get_fa_file($file)
    {
        $data = array();
        $fp = fopen($file, "r");
        $line = 1;
        $seq = "";
        if (! $fp) {
            echo "<div id='error'>file open eror : $php_errormsg</div>";
        } else {
            while (($row1 = fgets($fp)) !== false) {
                $str = str_replace(PHP_EOL, '', $row1);
                if ($line==1){
                    $name = $str;
                }else{
                    $seq = $seq.$str;
                }
                $line++;
            }
        }
        $seq_out = $seq;
        return $seq_out;
    }
    // func to read data from .fa file
    private function reform_fa_file($file, $ref, $alt)
    {
        $data = array();
        $fp = fopen($file, "r");
        $line = 1;
        $seq = "";
        if (! $fp) {
            echo "<div id='error'>file open eror : $php_errormsg</div>";
        } else {
            while (($row1 = fgets($fp)) !== false) {
                $str = str_replace(PHP_EOL, '', $row1);
                if ($line==1){
                    $name = $str;
                }else{
                    $seq = $seq.$str;
                }
                $line++;
            }
        }

        $seq_prefix = substr($seq, 0, 31);

        if(strlen($ref)==1 && $ref == "-"){
            $seq_postfix = substr($seq, -32);
        } else {
            $seq_postfix = substr($seq, -31);
        }

//        if ($ref == "-"){
//            $seq_postfix = substr($seq, -30);
//        }elseif ($alt == "-"){
//            $seq_postfix = substr($seq, -29);
//        }else{
//            $seq_postfix = substr($seq, -29);
//        }

        $seq_out = $seq_prefix."[".$ref."/".$alt."]".$seq_postfix;
        return $seq_out;
    }

    private function getInputFile_dbsnp($upload_dir, $rs_id, $genome, $time_stamp)
    {
        $software = '/var/www/ascrispr_backend/public/crisporWebsite-master/bin/Linux/twoBitToFa';
        if ($genome == "hg19"){
            $refSeq = ' /var/www/ascrispr_backend/public/crisporWebsite-master/genomes/hg19/hg19.2bit ';
        }else{
            $refSeq = ' /var/www/ascrispr_backend/public/crisporWebsite-master/genomes/hg38/hg38.2bit ';
        }

        if ($genome == "hg19"){
            $data = DBSNP::where('rs_id', $rs_id)->get();
            $dataResult = new DBSNPCollection($data);
        }else{
            $data = DBSNP38::where('rs_id', $rs_id)->get();
            $dataResult = new DBSNP38Collection($data);
        }

        $data_out = [];
        $i = 0;
        $infos = array();
        foreach ($dataResult as $row) {
            $j = 0;
            // foramt genomic position
            $chr = "chr".$row['chr'];
            $start = $row['start']-32;
            $end = $row['end'] + 31;
            $ref = $row['ref'];
            $alt = $row['alt'];
            $rs_id = $row['rs_id'];

            if ($genome == "hg19"){
                $data1 = DBSNP::where('start', '>', $start+1)->where('start', '<=', $end)->where('chr', $row['chr'])->get();
                $dataResult1 = new DBSNPCollection($data1);
            }else{
                $data1 = DBSNP38::where('start', '>', $start+1)->where('start', '<=', $end)->where('chr', $row['chr'])->get();
                $dataResult1 = new DBSNP38Collection($data1);
            }

//            $command_str = $software." -seq=".$chr." -start=".$start." -end=". $end.$refSeq.$outputFile;
//            exec($command_str);

            foreach ($dataResult1 as $row1) {
                $data_out[$i][$j]['chr'] = "chr".$row1['chr'];
                $data_out[$i][$j]['start'] = $row1['start'];
                $data_out[$i][$j]['end'] = $row1['end'];
                $data_out[$i][$j]['ref'] = $row1['ref'];
                $data_out[$i][$j]['alt'] = $row1['alt'];
                $data_out[$i][$j]['rs_id'] = $row1['rs_id'];

                $j = $j + 1;
            }
            $i = $i + 1;

            if($alt==substr($ref, 0, strlen($alt))){
                continue;
            }
            if($alt==substr($ref, -strlen($alt))){
                continue;
            }

            if($ref==substr($alt, 0, strlen($ref))){
                continue;
            }
            if($ref==substr($alt, -strlen($ref))){
                continue;
            }

            $outputFileName = $chr."_".$start."_".$end.".fa";
            $outputFile = $upload_dir."/".$outputFileName;

            $command_str = $software." -seq=".$chr." -start=".$start." -end=". $end.$refSeq.$outputFile;
            exec($command_str);

            $log_file = '/Ascrispr_log.txt';
            Storage::disk('uploads')->put($log_file, $command_str);

            $input_seq = $this->reform_fa_file($outputFile, $ref, $alt);
            $input_seq_org = $this->get_fa_file($outputFile);

//             $info["seq_name"] = $rs_id.":".$chr."_".$start."_".$end.":[".$ref.">".$alt."]";
            $info["seq_name"] = $rs_id.":"."[".$ref.">".$alt."]";
            $info["input_seq"] = strtoupper($input_seq);

            $info["seq_name_hide"] = $rs_id."_".$ref."_".$alt;
            $info["chr"] = $row['chr'];
            $info["input_seq_org"] = $input_seq_org;
            $info["start"] = $row['start'];
            $info["end"] = $row['end'];
            $info["ref"] = $row['ref'];
            $info["alt"] = $row['alt'];
            $info["rs_id"] = $row['rs_id'];

            $infos[] = $info;

        }

        $dbsnp_list_file = '/dbsnp_list_file.txt';
        $json_string = json_encode($data_out);
        $bool = Storage::disk('uploads')->put($time_stamp.$dbsnp_list_file, $json_string);

        return $infos;
    }

    private function getInputFile_dbsnp_old($upload_dir, $rs_id, $genome, $time_stamp)
    {
        $software = '/var/www/ascrispr_backend/public/crisporWebsite-master/bin/Linux/twoBitToFa';
        if ($genome == "hg19"){
            $refSeq = ' /var/www/ascrispr_backend/public/crisporWebsite-master/genomes/hg19/hg19.2bit ';
        }else{
            $refSeq = ' /var/www/ascrispr_backend/public/crisporWebsite-master/genomes/hg38/hg38.2bit ';
        }

        if ($genome == "hg19"){
            $data = DBSNP::where('rs_id', $rs_id)->get();
            $dataResult = new DBSNPCollection($data);
        }else{
            $data = DBSNP38::where('rs_id', $rs_id)->get();
            $dataResult = new DBSNP38Collection($data);
        }

        $data_out = [];
        $i = 0;
        $infos = array();
        foreach ($dataResult as $row) {
            $j = 0;
            // foramt genomic position
            $chr = "chr".$row['chr'];
            $start = $row['start']-30;
            $end = $row['end'] + 29;
            $ref = $row['ref'];
            $alt = $row['alt'];
            $rs_id = $row['rs_id'];

            if ($genome == "hg19"){
                $data1 = DBSNP::where('start', '>', $start+1)->where('start', '<=', $end)->where('chr', $row['chr'])->get();
                $dataResult1 = new DBSNPCollection($data1);
            }else{
                $data1 = DBSNP38::where('start', '>', $start+1)->where('start', '<=', $end)->where('chr', $row['chr'])->get();
                $dataResult1 = new DBSNP38Collection($data1);
            }

            foreach ($dataResult1 as $row1) {
                $data_out[$i][$j]['chr'] = "chr".$row1['chr'];
                $data_out[$i][$j]['start'] = $row1['start'];
                $data_out[$i][$j]['end'] = $row1['end'];
                $data_out[$i][$j]['ref'] = $row1['ref'];
                $data_out[$i][$j]['alt'] = $row1['alt'];
                $data_out[$i][$j]['rs_id'] = $row1['rs_id'];

                $j = $j + 1;
            }
            $i = $i + 1;

            if($alt==substr($ref, 0, strlen($alt))){
                continue;
            }
            if($alt==substr($ref, -strlen($alt))){
                continue;
            }

            if($ref==substr($alt, 0, strlen($ref))){
                continue;
            }
            if($ref==substr($alt, -strlen($ref))){
                continue;
            }

            $outputFileName = $chr."_".$start."_".$end.".fa";
            $outputFile = $upload_dir."/".$outputFileName;

            $command_str = $software." -seq=".$chr." -start=".$start." -end=". $end.$refSeq.$outputFile;
            exec($command_str);

            $log_file = '/Ascrispr_log.txt';
            Storage::disk('uploads')->put($log_file, $command_str);

            $input_seq = $this->reform_fa_file($outputFile, $ref, $alt);
            $input_seq_org = $this->get_fa_file($outputFile);

//             $info["seq_name"] = $rs_id.":".$chr."_".$start."_".$end.":[".$ref.">".$alt."]";
            $info["seq_name"] = $rs_id.":"."[".$ref.">".$alt."]";
            $info["input_seq"] = strtoupper($input_seq);

            $info["seq_name_hide"] = $rs_id."_".$ref."_".$alt;
            $info["chr"] = $row['chr'];
            $info["input_seq_org"] = $input_seq_org;
            $info["start"] = $row['start'];
            $info["end"] = $row['end'];
            $info["ref"] = $row['ref'];
            $info["alt"] = $row['alt'];
            $info["rs_id"] = $row['rs_id'];

            $infos[] = $info;

        }

        $dbsnp_list_file = '/dbsnp_list_file.txt';
        $json_string = json_encode($data_out);
        $bool = Storage::disk('uploads')->put($time_stamp.$dbsnp_list_file, $json_string);

        return $infos;
    }

    public function plotSequences(Request $request)
    {
        $time_stamp = $request->input('params');

        $upload_dir = public_path().'/uploads/'.$time_stamp;
        $dbsnp_list_file = '/dbsnp_list_file.txt';

//        return $upload_dir.$dbsnp_list_file;

        if(file_exists($upload_dir.$dbsnp_list_file)) {
            $dbsnp_list_tmp = file_get_contents($upload_dir.$dbsnp_list_file);
        }
        $dbsnp_list = json_decode($dbsnp_list_tmp, true);

        return $dbsnp_list;

    }

    public function getSequences(Request $request)
    {
        $params = $request->input('params');
        $time_stamp = $params['time_stamp'];
        $rsID = $params['rsID'];
        $genome = $params['genome'];

        //判断文件夹是否已存在
        if(!Storage::disk('uploads')->exists($time_stamp)){
            Storage::disk('uploads')->makeDirectory($time_stamp);
        }
        $upload_dir = public_path().'/uploads/'.$time_stamp;
        $main_path = public_path();

        $infos = $this->getInputFile_dbsnp($upload_dir, $rsID, $genome, $time_stamp);
        return $infos;

    }

    public function ascrispr(Request $request)
    {
        $params = $request->input('params');

        $time_stamp = $params['time_stamp'];
        $cas9_system_types = $params['cas9_system_types'];
        $cpf1_system_types = $params['cpf1_system_types'];
        $cas12b_system_types = $params['cas12b_system_types'];
        $casx_system_types = $params['casx_system_types'];

        $genome = $params['genome'];
        $input_types = $params['radioInputType'];

        $crispr_system = trim($params['radioType']);

        if($params['offtarget_checked']){
            $offtarget = 'offtarget';
        }else{
            $offtarget = 'noofftarget';
        }

        if ($crispr_system == "cpf1") {
            $PAMs = $cpf1_system_types;
        } else if ($crispr_system == "cas9") {
            $PAMs = $cas9_system_types;
        } else if ($crispr_system == "cas12b") {
            $PAMs = $cas12b_system_types;
        } else if ($crispr_system == "casx") {
            $PAMs = $casx_system_types;
        }

        if(!Storage::disk('uploads')->exists($time_stamp)){
            Storage::disk('uploads')->makeDirectory($time_stamp);
        }
        $upload_dir = public_path().'/uploads/'.$time_stamp;
        $main_path = public_path();

        //sava parameters
        $request_file = '/request_file.txt';
        $json_string = json_encode($params);
        $bool = Storage::disk('uploads')->put($time_stamp.$request_file, $json_string);

        if ($input_types=="sequence"){
            $input_seq = strtoupper($params['inputValue_sequence']);
            $seq_name_hide = "inputFile";

            $seqs_list[0]["seq_name"] = $seq_name_hide;
            $seqs_list[0]["input_seq"] = $input_seq;
        }else{
            $table_seq = $params['table_seq'];
            $input_seqs = json_decode($table_seq,true);
//            $input_seqs = $table_seq;
            $input_seq = '';
            for($i = 0; $i < count($input_seqs); $i++) {
                $input_seq = strtoupper($input_seqs[$i]['input_seq']);
                $seq_name_hide = $input_seqs[$i]['seq_name_hide'];

                $log_file = '/Ascrispr_seq_name_hide_log.txt';
                Storage::disk('uploads')->put($log_file, $seq_name_hide);

                $seqs_list[$i]["seq_name"] = $seq_name_hide;
                $seqs_list[$i]["input_seq"] = $input_seq;
            }
        }

        for($i = 0; $i < count($seqs_list); $i++) {
            $seq_name_hide = $seqs_list[$i]["seq_name"];
            $input_seq = strtoupper($seqs_list[$i]["input_seq"]);

            $out = "$upload_dir/$seq_name_hide.inputFile.fa";

            if(!file_exists($out)){
                if ($crispr_system == "cas9"){
                    $param1 = "-in $input_seq -out $out -strand + -input_types $input_types";
                }else{
                    $param1 = "-in $input_seq -out $out -strand all -input_types $input_types";
                }

                exec("perl /var/www/ascrispr_backend/public/bin/gh_ascrispr_str2fa.pl " . $param1 . " > " . "$upload_dir/gh_ascrispr_str2fa.log 2>&1");
                $log_file = $time_stamp.'/gh_ascrispr_str2fa_log.txt';
                $cmd = "perl /var/www/ascrispr_backend/public/bin/gh_ascrispr_str2fa.pl " . $param1 . " > " . "$upload_dir/gh_ascrispr_str2fa.log 2>&1";
                Storage::disk('uploads')->put($log_file, $cmd);

                // crispor
                $PAMs_array = $PAMs;//explode(",", $PAMs);
                foreach ($PAMs_array as $PAM) {
                    $PAM1 = explode(":", $PAM)[1];
                    $inputFile = $out;
                    $outputFile1 = "$upload_dir/$PAM1.$seq_name_hide.inputFile.o1.txt";
                    $outputFile2 = "$upload_dir/$PAM1.$seq_name_hide.inputFile.o2.txt";

                    if($offtarget == 'offtarget'){
                        $param1 = "$genome $inputFile $outputFile1 -o $outputFile2 -p $PAM1 --mm 3 ";
                    }else{
                        $param1 = "$genome $inputFile $outputFile1 -p $PAM1 --mm 3 ";
                    }
                    exec("/conda/envs/python271/bin/python2.7 $main_path/crisporWebsite-master/crispor.py " . $param1 . " >> " . "$upload_dir/gh_crispor.log 2>&1");
                    $log_file = $time_stamp.'/crispor_log.txt';
                    $cmd = "/conda/envs/python271/bin/python2.7 $main_path/crisporWebsite-master/crispor.py " . $param1 . " > " . "$upload_dir/gh_crispor.log 2>&1";
                    Storage::disk('uploads')->put($log_file, $cmd);
                }
            }
        }

    }

    public function showInfoSequenceByTime(Request $request){

        $time_stamp = $request->input('params');

        //判断文件夹是否已存在
        if(!Storage::disk('uploads')->exists($time_stamp)){
            Storage::disk('uploads')->makeDirectory($time_stamp);
        }
        $upload_dir = public_path().'/uploads/'.$time_stamp;
        $request_file = '/request_file.txt';
        if(file_exists($upload_dir.$request_file)) {
            $params_tmp = file_get_contents($upload_dir.$request_file);
        }
        $params = json_decode($params_tmp, true);

//        $time_stamp = $params['time_stamp'];
        $cas9_system_types = $params['cas9_system_types'];
        $cpf1_system_types = $params['cpf1_system_types'];
        $cas12b_system_types = $params['cas12b_system_types'];
        $casx_system_types = $params['casx_system_types'];

        $genome = $params['genome'];
        $input_types = $params['radioInputType'];

        $crispr_system = trim($params['radioType']);

        if ($crispr_system == "cpf1") {
            $PAMs = $cpf1_system_types;
        } else if ($crispr_system == "cas9") {
            $PAMs = $cas9_system_types;
        } else if ($crispr_system == "cas12b") {
            $PAMs = $cas12b_system_types;
        } else if ($crispr_system == "casx") {
            $PAMs = $casx_system_types;
        }

        $seqs_list = [];
        if ($input_types=="sequence"){
            $input_seq = strtoupper($params['inputValue_sequence']);
            $seq_name_hide = "inputFile";

            $seqs_list[0]["seq_name"] = $seq_name_hide;
            $seqs_list[0]["input_seq"] = $input_seq;
        }else{
            $table_seq = $params['table_seq'];
            $input_seqs = json_decode($table_seq,true);

            $input_seq = '';
            for($i = 0; $i < count($input_seqs); $i++) {
                $input_seq = strtoupper($input_seqs[$i]['input_seq']);
                $seq_name_hide = $input_seqs[$i]['seq_name_hide'];

                $seqs_list[$i]["seq_name"] = $seq_name_hide;
                $seqs_list[$i]["input_seq"] = $input_seq;
            }
        }


        $data_out = [];
        for($i=0; $i<count($seqs_list);$i++) {
            $spacerSeq_TTTT_tip = '';
            $seq_name_hide = $seqs_list[$i]["seq_name"];
            $input_seq = $seqs_list[$i]["input_seq"];

//                if ($input_types == "input_sequence") {
//                    echo "<div class='col-md-12  col-md-pull-3'><h3><span class='label label-success'>Sequence: $input_seq</span></h3></div>";
//                } else {
//                    echo "<div class='col-md-12  col-md-pull-3'><h3><span class='label label-success'>dbSNP:$input_seq</span></h3></div>";
//                }

            $resultsFile = $upload_dir . "/" . $seq_name_hide . ".results.txt";

            $data = $this -> read_data_file($resultsFile);
            foreach ($data as $ele) {
                $Targeting_Strand = $ele[4];
            }

            $j = 0;
            $data = $this -> read_data_file($resultsFile);
            if(sizeof($data)==0){
                $data_out[$i][$j]['spacerSeq_original'] = '';
                $data_out[$i][$j]['PAM_original'] = '';
                $data_out[$i][$j]['Direction_link'] = '';
                $data_out[$i][$j]['N1'] = '';
                $data_out[$i][$j]['N2'] = '';
                $data_out[$i][$j]['spacerStart'] = '';
                $data_out[$i][$j]['spacerEnd'] = '';
                $data_out[$i][$j]['PAMStart'] = '';
                $data_out[$i][$j]['PAMEnd'] = '';
                $data_out[$i][$j]['MUTposStart'] = '';
                $data_out[$i][$j]['MUTposEnd'] = '';
                $data_out[$i][$j]['PAM_IUB'] = '';
                $data_out[$i][$j]['seq_name_hide'] = '';

                $data_out[$i][$j]['spacerSeq'] = '';
                $data_out[$i][$j]['PAMSeq'] = '';
                $data_out[$i][$j]['Crispr_Type'] = '';
                $data_out[$i][$j]['Targeting_Strand'] = '';
                $data_out[$i][$j]['Direction'] = '';
                $data_out[$i][$j]['targetGenomeGeneLocus'] = '';
                $data_out[$i][$j]['GC_content'] = '';
                $data_out[$i][$j]['mitSpecScore'] = '';
                $data_out[$i][$j]['Xu_Score'] = '';
                $data_out[$i][$j]['Doench16_Score'] = '';
                $data_out[$i][$j]['Moreno_Mateos_Score'] = '';
                $data_out[$i][$j]['Azimuth_in_vitro_Score'] = '';
                $data_out[$i][$j]['Najm2018'] = '';
                $data_out[$i][$j]['DeepCpf1'] = '';
                $data_out[$i][$j]['Self_complementarity'] = '';
                $data_out[$i][$j]['offtargetCount'] = '';
                $data_out[$i][$j]['Enzyme_Information'] = '';
                $data_out[$i][$j]['input_seq'] = '';
                $data_out[$i][$j]['crispr_system'] = '';
                $data_out[$i][$j]['input_types'] = '';
                $data_out[$i][$j]['spacerSeq_TTTT_tip'] = '';
                $j = $j + 1;
            }
            foreach ($data as $ele) {

                $rank = $ele[0];
                $spacerSeq_original = $ele[1];
                $spacerSeq = $ele[1];

                $PAM_original = $ele[2];
                $PAMSeq = $ele[2];
                $Crispr_Type = $ele[3];
                $Targeting_Strand = $ele[4];
                $Direction = $ele[5];

                $GC_content = $ele[6];
                $GC_content = $GC_content * 100;

                $GC_content = round($GC_content);

                $Self_complementarity = $ele[20];

                $Enzyme_Information = $ele[7];
                $N1 = $ele[8];
                $N2 = $ele[9];
                $outStart = $ele[10];
                $spacerStart = $ele[11];
                $spacerEnd = $ele[12];
                $PAMStart = $ele[13];
                $PAMEnd = $ele[14];
                $PAMLen = $ele[15];
                $MUTposStart = $ele[16];
                $MUTposEnd = $ele[17];
                $iInputSeq = $ele[18];
                $str_print = $ele[19];

                $inputSeq_type = $ele[21];
                if ($Direction == "+") {
                    $Direction_link = "plus";
                } else {
                    $Direction_link = "minus";
                }

                $tmparray = explode(":", $Crispr_Type);
                if (count($tmparray) > 1) {
                    $PAM_IUB = $tmparray[1];
                }

                $str_for_link = "$PAMSeq:$Targeting_Strand:$Direction_link:$N1:$N2:$spacerStart:$spacerEnd:$PAMStart:$PAMEnd:$MUTposStart:$MUTposEnd:$crispr_system:$PAM_IUB:$time_stamp:$seq_name_hide";
//                    $Enzyme_Information = "<a href='ascrispr/enzymeInformation/$spacerSeq/$PAM_original/$str_for_link' target='_blank'>$Enzyme_Information <span class='glyphicon glyphicon-new-window'></span></a>";

                $isSNP_PAM = FALSE;
                if ($Targeting_Strand == "Varied" || $Targeting_Strand == "Alternative") {
                    if ($inputSeq_type == "SNP") {
                        if ($MUTposStart == $MUTposEnd) {
                            if ($MUTposStart <= $spacerEnd && $MUTposStart >= $spacerStart) {
                                $posStart = $MUTposStart - $spacerStart;
                                $len = 1;
                                $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                            }
                            if ($MUTposStart <= $PAMEnd && $MUTposStart >= $PAMStart) {
                                $posStart = $MUTposStart - $PAMStart;
                                $len = 1;
                                $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                $isSNP_PAM = TRUE;
                            }
                        } else {
                            # 相对位置 变为 绝对位置， 0表示从seq的头部开始
                            # Mutation 在spacer上有四种情况，不可以合并为三种
                            if ($MUTposStart < $spacerStart && $MUTposEnd >= $spacerStart && $MUTposEnd <= $spacerEnd) {
                                $posStart = 0;
                                $len = $MUTposEnd - $spacerStart + 1;
                                $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                            } elseif ($MUTposStart >= $spacerStart && $MUTposEnd <= $spacerEnd) {
                                $posStart = $MUTposStart - $spacerStart;
                                $len = $MUTposEnd - $MUTposStart + 1;
                                $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                            } elseif ($MUTposStart >= $spacerStart && $MUTposStart <= $spacerEnd && $MUTposEnd >= $spacerEnd) {
                                $posStart = $MUTposStart - $spacerStart;
                                $len = $spacerEnd - $MUTposStart + 1;
                                $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                            } elseif ($MUTposStart < $spacerStart && $MUTposEnd >= $spacerEnd) {
                                $posStart = 0;
                                $len = $spacerEnd - $spacerStart + 1;
                                $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                            }

                            # 相对位置 变为 绝对位置， 0表示从seq的头部开始
                            # Mutation 在PAM上有四种情况，不可以合并为三种
                            if ($MUTposStart < $PAMStart && $MUTposEnd >= $PAMStart && $MUTposEnd <= $PAMEnd) {
                                $posStart = 0;
                                $len = $MUTposEnd - $PAMStart + 1;
                                $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                $isSNP_PAM = TRUE;
                            } elseif ($MUTposStart >= $PAMStart && $MUTposEnd <= $PAMEnd) {
                                $posStart = $MUTposStart - $PAMStart;
                                $len = $MUTposEnd - $MUTposStart + 1;
                                $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                $isSNP_PAM = TRUE;
                            } elseif ($MUTposStart >= $PAMStart && $MUTposStart <= $PAMEnd && $MUTposEnd >= $PAMEnd) {
                                $posStart = $MUTposStart - $PAMStart;
                                $len = $PAMEnd - $MUTposStart + 1;
                                $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                $isSNP_PAM = TRUE;
                            } elseif ($MUTposStart < $PAMStart && $MUTposEnd >= $PAMEnd) {
                                $posStart = 0;
                                $len = $PAMEnd - $PAMStart + 1;
                                $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                $isSNP_PAM = TRUE;

                            }
                        }

                    } elseif ($inputSeq_type == "insertion") {
                        if ($MUTposStart == $MUTposEnd) {
                            if ($MUTposStart <= $spacerEnd && $MUTposStart >= $spacerStart) {
                                $posStart = $MUTposStart - $spacerStart;
                                $len = 1;
                                $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                            }
                            if ($MUTposStart <= $PAMEnd && $MUTposStart >= $PAMStart) {
                                $posStart = $MUTposStart - $PAMStart;
                                $len = 1;
                                $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                $isSNP_PAM = TRUE;
                            }
                        } else {

                            # 相对位置 变为 绝对位置， 0表示从seq的头部开始
                            # Mutation 在spacer上有四种情况，不可以合并为三种
                            if ($MUTposStart < $spacerStart && $MUTposEnd >= $spacerStart && $MUTposEnd <= $spacerEnd) {
                                $posStart = 0;
                                $len = $MUTposEnd - $spacerStart + 1;
                                $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                            } elseif ($MUTposStart >= $spacerStart && $MUTposEnd <= $spacerEnd) {
                                $posStart = $MUTposStart - $spacerStart;
                                $len = $MUTposEnd - $MUTposStart + 1;
                                $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                            } elseif ($MUTposStart >= $spacerStart && $MUTposStart <= $spacerEnd && $MUTposEnd >= $spacerEnd) {
                                $posStart = $MUTposStart - $spacerStart;
                                $len = $spacerEnd - $MUTposStart + 1;
                                $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                            } elseif ($MUTposStart < $spacerStart && $MUTposEnd >= $spacerEnd) {
                                $posStart = 0;
                                $len = $spacerEnd - $spacerStart + 1;
                                $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                            }

                            # 相对位置 变为 绝对位置， 0表示从seq的头部开始
                            # Mutation 在PAM上有四种情况，不可以合并为三种
                            if ($MUTposStart < $PAMStart && $MUTposEnd >= $PAMStart && $MUTposEnd <= $PAMEnd) {
                                $posStart = 0;
                                $len = $MUTposEnd - $PAMStart + 1;
                                $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                $isSNP_PAM = TRUE;
                            } elseif ($MUTposStart >= $PAMStart && $MUTposEnd <= $PAMEnd) {
                                $posStart = $MUTposStart - $PAMStart;
                                $len = $MUTposEnd - $MUTposStart + 1;
                                $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                $isSNP_PAM = TRUE;
                            } elseif ($MUTposStart >= $PAMStart && $MUTposStart <= $PAMEnd && $MUTposEnd >= $PAMEnd) {
                                $posStart = $MUTposStart - $PAMStart;
                                $len = $PAMEnd - $MUTposStart + 1;
                                $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                $isSNP_PAM = TRUE;
                            } elseif ($MUTposStart < $PAMStart && $MUTposEnd >= $PAMEnd) {
                                $posStart = 0;
                                $len = $PAMEnd - $PAMStart + 1;
                                $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                $isSNP_PAM = TRUE;
                            }
                        }
                    } elseif ($inputSeq_type == "deletion") {
                        if ($MUTposStart <= $spacerEnd && $MUTposStart >= $spacerStart) {
                            $pos = $MUTposStart - $spacerStart;
                            $spacerSeq = $this -> mark_pos_del($spacerSeq, $pos, $N1);
                        } else if ($MUTposStart <= $PAMEnd && $MUTposStart >= $PAMStart) {
                            $pos = $MUTposStart - $PAMStart;
                            $PAMSeq = $this -> mark_pos_del($PAMSeq, $pos, $N1);
                        }
                    }
                } else {
                    if ($inputSeq_type == "SNP") {
                        if ($MUTposStart == $MUTposEnd) {
                            if ($MUTposStart <= $spacerEnd && $MUTposStart >= $spacerStart) {
                                $posStart = $MUTposStart - $spacerStart;
                                $len = 1;
                                $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                            }
                            if ($MUTposStart <= $PAMEnd && $MUTposStart >= $PAMStart) {
                                $posStart = $MUTposStart - $PAMStart;
                                $len = 1;
                                $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                $isSNP_PAM = TRUE;
                            }
                        } else {
                            # 相对位置 变为 绝对位置， 0表示从seq的头部开始
                            # Mutation 在spacer上有四种情况，不可以合并为三种
                            if ($MUTposStart < $spacerStart && $MUTposEnd >= $spacerStart && $MUTposEnd <= $spacerEnd) {
                                $posStart = 0;
                                $len = $MUTposEnd - $spacerStart + 1;
                                $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                            } elseif ($MUTposStart >= $spacerStart && $MUTposEnd <= $spacerEnd) {
                                $posStart = $MUTposStart - $spacerStart;
                                $len = $MUTposEnd - $MUTposStart + 1;
                                $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                            } elseif ($MUTposStart >= $spacerStart && $MUTposStart <= $spacerEnd && $MUTposEnd >= $spacerEnd) {
                                $posStart = $MUTposStart - $spacerStart;
                                $len = $spacerEnd - $MUTposStart + 1;
                                $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                            } elseif ($MUTposStart < $spacerStart && $MUTposEnd >= $spacerEnd) {
                                $posStart = 0;
                                $len = $spacerEnd - $spacerStart + 1;
                                $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                            }
                            # 相对位置 变为 绝对位置， 0表示从seq的头部开始
                            # Mutation 在PAM上有四种情况，不可以合并为三种
                            if ($MUTposStart < $PAMStart && $MUTposEnd >= $PAMStart && $MUTposEnd <= $PAMEnd) {
                                $posStart = 0;
                                $len = $MUTposEnd - $PAMStart + 1;
                                $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                $isSNP_PAM = TRUE;
                            } elseif ($MUTposStart >= $PAMStart && $MUTposEnd <= $PAMEnd) {
                                $posStart = $MUTposStart - $PAMStart;
                                $len = $MUTposEnd - $MUTposStart + 1;
                                $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                $isSNP_PAM = TRUE;
                            } elseif ($MUTposStart >= $PAMStart && $MUTposStart <= $PAMEnd && $MUTposEnd >= $PAMEnd) {
                                $posStart = $MUTposStart - $PAMStart;
                                $len = $PAMEnd - $MUTposStart + 1;
                                $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                $isSNP_PAM = TRUE;
                            } elseif ($MUTposStart < $PAMStart && $MUTposEnd >= $PAMEnd) {
                                $posStart = 0;
                                $len = $PAMEnd - $PAMStart + 1;
                                $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                $isSNP_PAM = TRUE;
                            }
                        }
                    } elseif ($inputSeq_type == "deletion") {
                        if ($MUTposStart == $MUTposEnd) {
                            if ($MUTposStart <= $spacerEnd && $MUTposStart >= $spacerStart) {
                                $posStart = $MUTposStart - $spacerStart;
                                $len = 1;
                                $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                            }
                            if ($MUTposStart <= $PAMEnd && $MUTposStart >= $PAMStart) {
                                $posStart = $MUTposStart - $PAMStart;
                                $len = 1;
                                $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                $isSNP_PAM = TRUE;
                            }
                        } else {
                            # 相对位置 变为 绝对位置， 0表示从seq的头部开始
                            # Mutation 在spacer上有四种情况，不可以合并为三种
                            if ($MUTposStart < $spacerStart && $MUTposEnd >= $spacerStart && $MUTposEnd <= $spacerEnd) {
                                $posStart = 0;
                                $len = $MUTposEnd - $spacerStart + 1;
                                $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                            } elseif ($MUTposStart >= $spacerStart && $MUTposEnd <= $spacerEnd) {
                                $posStart = $MUTposStart - $spacerStart;
                                $len = $MUTposEnd - $MUTposStart + 1;
                                $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                            } elseif ($MUTposStart >= $spacerStart && $MUTposStart <= $spacerEnd && $MUTposEnd >= $spacerEnd) {
                                $posStart = $MUTposStart - $spacerStart;
                                $len = $spacerEnd - $MUTposStart + 1;
                                $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                            } elseif ($MUTposStart < $spacerStart && $MUTposEnd >= $spacerEnd) {
                                $posStart = 0;
                                $len = $spacerEnd - $spacerStart + 1;
                                $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                            }

                            # 相对位置 变为 绝对位置， 0表示从seq的头部开始
                            # Mutation 在PAM上有四种情况，不可以合并为三种
                            if ($MUTposStart < $PAMStart && $MUTposEnd >= $PAMStart && $MUTposEnd <= $PAMEnd) {
                                $posStart = 0;
                                $len = $MUTposEnd - $PAMStart + 1;
                                $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                $isSNP_PAM = TRUE;
                            } elseif ($MUTposStart >= $PAMStart && $MUTposEnd <= $PAMEnd) {
                                $posStart = $MUTposStart - $PAMStart;
                                $len = $MUTposEnd - $MUTposStart + 1;
                                $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                $isSNP_PAM = TRUE;
                            } elseif ($MUTposStart >= $PAMStart && $MUTposStart <= $PAMEnd && $MUTposEnd >= $PAMEnd) {
                                $posStart = $MUTposStart - $PAMStart;
                                $len = $PAMEnd - $MUTposStart + 1;
                                $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                $isSNP_PAM = TRUE;
                            } elseif ($MUTposStart < $PAMStart && $MUTposEnd >= $PAMEnd) {
                                $posStart = 0;
                                $len = $PAMEnd - $PAMStart + 1;
                                $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                $isSNP_PAM = TRUE;
                            }
                        }
                    } elseif ($inputSeq_type == "insertion") {
                        if ($MUTposStart <= $spacerEnd && $MUTposStart >= $spacerStart) {
                            $pos = $MUTposStart - $spacerStart;
                            $spacerSeq = $this -> mark_pos_del($spacerSeq, $pos, $N2);
                        } else if ($MUTposStart <= $PAMEnd && $MUTposStart >= $PAMStart) {
                            $pos = $MUTposStart - $PAMStart;
                            $PAMSeq = $this -> mark_pos_del($PAMSeq, $pos, $N2);
                        }
                    }
                }
                # 判断  如果突变点在PAM上， type is N R ... 无法确保只切割一条链
//                if ($Targeting_Strand == "Reference" || $Targeting_Strand == "WT") {
                    if (strlen($PAMSeq) > strlen($PAM_original)) {
                        if ($isSNP_PAM) {
                            if(strlen($N1)==1 && strlen($N2)==1){
                                $PAM_IUB_letter = substr($PAM_IUB, $posStart, 1);
                                $PAM_original_letter = substr($PAM_original, $posStart, 1);

//                                $log_file = '/ascrisprByTimeStamp111.txt';
//                                $cmd = $N1 . "\t" . $N2 . "\t" . $Direction . "\t" . $PAMSeq . "\t" . $PAM_original . "\t" . $PAM_IUB_letter . "\t" . $PAM_original_letter . "\t" . $PAM_IUB . "\t" . $PAM_original . "\t" . $posStart;
//                                Storage::disk('uploads')->put($log_file, $cmd);

                                if ($this->isMatched($PAM_IUB, $PAM_original, $posStart, $N1, $N2, $Direction)) {
                                    $log_file = '/ascrisprByTimeStamp.txt';
                                    $cmd = $PAM_IUB_letter . "\t" . $PAM_original_letter . "\t" . $PAM_IUB . "\t" . $PAM_original . "\t" . $posStart;
                                    Storage::disk('uploads')->put($log_file, $cmd);
                                    continue;
                                }
                            }
                        }
                    }
//                }
                # TTTT 提示
                $spacerSeq_TTTT_tip = $this -> mark_pos_tttt($spacerSeq);

                $seqId = $ele[22];
                $guideId = $ele[23];
                $targetSeq = $ele[24];

//                    return $spacerSeq;

                if($ele[25] != 'None'){
                    $mitSpecScore = $ele[25] / 100;
                } else{
                    $mitSpecScore = '--';
                }

                $offtargetCount = $ele[26];
                //  offtargets 0-1-2-3
                $offtargetsInfoFile = $upload_dir . "/" . $PAM_IUB . "." . $seq_name_hide . ".inputFile.o2.txt";
                if (file_exists($offtargetsInfoFile)) {
                    $data_ot = $this -> read_data_file($offtargetsInfoFile);
                    $mismatchCount_num0 = 0;
                    $mismatchCount_num1 = 0;
                    $mismatchCount_num2 = 0;
                    $mismatchCount_num3 = 0;

                    if ($crispr_system=="cas9") {
                        //     cas9
                        $guideSeq =  $spacerSeq_original.$PAM_original;
                    }else{
                        //     cpf1
                        $guideSeq =  $PAM_original.$spacerSeq_original;
                    }

                    foreach ($data_ot as $ele_ot) {
                        $mismatchCount = $ele_ot[5];
                        $guideSeq_ot = $ele_ot[2];

                        $seqId_ot = $ele_ot[0];
                        $guideId_ot = $ele_ot[1];

                        if (strtoupper($guideSeq) == strtoupper($guideSeq_ot) && $seqId == $seqId_ot && $guideId == $guideId_ot) {
                            if ($mismatchCount == "0") {
                                $mismatchCount_num0++;
                            } elseif ($mismatchCount == "1") {
                                $mismatchCount_num1++;
                            } elseif ($mismatchCount == "2") {
                                $mismatchCount_num2++;
                            } elseif ($mismatchCount == "3") {
                                $mismatchCount_num3++;
                            }
                        }
                    }
                    $mismatchCount_num_str = "$mismatchCount_num0-$mismatchCount_num1-$mismatchCount_num2-$mismatchCount_num3";
                } else {
                    $mismatchCount_num_str = '-';
                }


                $Xu_Score = '--';
                $Doench16_Score = '--';
                $Moreno_Mateos_Score = '--';
                $Azimuth_in_vitro_Score = '--';
                $Najm2018 = '--';
                $DeepCpf1 = '--';
                $targetGenomeGeneLocus = '--';


                if ($crispr_system == 'cas9'){
                    $tmparray = explode("SaCas9", $Crispr_Type);
                    if (count($tmparray) == 1) { #SpCas9

                        if ($ele[28] !== 'NotEnoughFlankSeq'){
                            $Doench16_Score = $ele[28] / 100;
                        }
                        if ($ele[29] !== 'NotEnoughFlankSeq'){
                            $Doench16_Old_Score = $ele[29];
                        }
                        if ($ele[30] !== 'NotEnoughFlankSeq'){
                            $Chari_Score = $ele[30];
                        }
                        if ($ele[31] !== 'NotEnoughFlankSeq'){
                            $Xu_Score = $ele[31];
                            $Xu_Score = round(1 / (1 + pow(M_E, -$Xu_Score)), 2);
                        }

                        if ($ele[32] !== 'NotEnoughFlankSeq'){
                            $Doench14_Score = $ele[32];
                        }
                        if ($ele[33] !== 'NotEnoughFlankSeq'){
                            $Wang_Score = $ele[33];
                        }
                        if ($ele[34] !== 'NotEnoughFlankSeq'){
                            $Moreno_Mateos_Score = $ele[34] / 100;
                        }
                        if ($ele[35] !== 'NotEnoughFlankSeq'){
                            $Azimuth_in_vitro_Score = $ele[35] / 100;
                        }
                        if ($ele[36] !== 'NotEnoughFlankSeq'){
                            $CCTop_Score = $ele[36];
                        }
                        if ($ele[37] !== 'NotEnoughFlankSeq'){
                            $Out_of_Frame_Score = $ele[37];
                        }

                        $Najm2018 = '--';

                        $targetGenomeGeneLocusTmp = str_replace(PHP_EOL, '', $ele[39]);
                        // #seqId guideId targetSeq mitSpecScore offtargetCount targetGenomeGeneLocus Najm 2018-Score Out-of-Frame-Score
                    } elseif (count($tmparray) > 1) { # SaCas9

                        $Xu_Score = '--';
                        $Doench16_Score = '--';
                        $Moreno_Mateos_Score = '--';
                        $Azimuth_in_vitro_Score = '--';

                        if ($ele[28] !== 'NotEnoughFlankSeq'){
                            $Najm2018 = $ele[28] / 100;
                        }
                        $Out_of_Frame_Score = $ele[29];

                        $targetGenomeGeneLocusTmp = str_replace(PHP_EOL, '', $ele[31]);
                    }
                }else{
                    if ($ele[28] !== 'NotEnoughFlankSeq'){
                        $DeepCpf1 = round($ele[28]);
                    } else {
                        $DeepCpf1 = '--';
                    }

                    $targetGenomeGeneLocusTmp = str_replace(PHP_EOL, '', $ele[30]);
                }

                if ($targetGenomeGeneLocusTmp != "") {
                    $targetGenomeGeneLocus = $targetGenomeGeneLocusTmp;
                } else {
                    $targetGenomeGeneLocus = "--";
                }

                if($targetGenomeGeneLocusTmp == "targetGenomeGeneLocus"){
                    $targetGenomeGeneLocus = "--";
                }

                if ($offtargetCount == "None" || $offtargetCount == "-1" || $offtargetCount == "--" || $offtargetCount == "") {
                    $offtargetCount = "0-0-0-0";
                } else {
//                        $offtargetCount = "<a href='ascrispr/offtargetsInfo/$spacerSeq_original/$PAM_original/$time_stamp/$str_for_link' target='_blank'>$mismatchCount_num_str </a>";
                    $offtargetCount = $mismatchCount_num_str;
                }

//                    $str_for_link = "$PAMSeq:$Targeting_Strand:$Direction_link:$N1:$N2:$spacerStart:$spacerEnd:$PAMStart:$PAMEnd:$MUTposStart:$MUTposEnd:$crispr_system:$PAM_IUB:$time_stamp:$seq_name_hide";
//                    $Enzyme_Information = "<a href='ascrispr/enzymeInformation/$spacerSeq/$PAM_original/$str_for_link' target='_blank'>$Enzyme_Information </a>";

                $data_out[$i][$j]['spacerSeq_original'] = $spacerSeq_original;
                $data_out[$i][$j]['PAM_original'] = $PAM_original;
                $data_out[$i][$j]['Direction_link'] = $Direction_link;
                $data_out[$i][$j]['N1'] = $N1;
                $data_out[$i][$j]['N2'] = $N2;
                $data_out[$i][$j]['spacerStart'] = $spacerStart;
                $data_out[$i][$j]['spacerEnd'] = $spacerEnd;
                $data_out[$i][$j]['PAMStart'] = $PAMStart;
                $data_out[$i][$j]['PAMEnd'] = $PAMEnd;
                $data_out[$i][$j]['MUTposStart'] = $MUTposStart;
                $data_out[$i][$j]['MUTposEnd'] = $MUTposEnd;
                $data_out[$i][$j]['PAM_IUB'] = $PAM_IUB;
                $data_out[$i][$j]['seq_name_hide'] = $seq_name_hide;

                $data_out[$i][$j]['spacerSeq'] = $spacerSeq;
                $data_out[$i][$j]['PAMSeq'] = $PAMSeq;
                $data_out[$i][$j]['Crispr_Type'] = $Crispr_Type;
                $data_out[$i][$j]['Targeting_Strand'] = $Targeting_Strand;
                $data_out[$i][$j]['Direction'] = $Direction;
                $data_out[$i][$j]['targetGenomeGeneLocus'] = $targetGenomeGeneLocus;
                $data_out[$i][$j]['GC_content'] = $GC_content;
                $data_out[$i][$j]['mitSpecScore'] = $mitSpecScore;
                $data_out[$i][$j]['Xu_Score'] = $Xu_Score;
                $data_out[$i][$j]['Doench16_Score'] = $Doench16_Score;
                $data_out[$i][$j]['Moreno_Mateos_Score'] = $Moreno_Mateos_Score;
                $data_out[$i][$j]['Azimuth_in_vitro_Score'] = $Azimuth_in_vitro_Score;
                $data_out[$i][$j]['Najm2018'] = $Najm2018;
                $data_out[$i][$j]['DeepCpf1'] = $DeepCpf1;
                $data_out[$i][$j]['Self_complementarity'] = $Self_complementarity;
                $data_out[$i][$j]['offtargetCount'] = $offtargetCount;
                $data_out[$i][$j]['Enzyme_Information'] = $Enzyme_Information;
                $data_out[$i][$j]['input_seq'] = $input_seq;
                $data_out[$i][$j]['crispr_system'] = $crispr_system;
                $data_out[$i][$j]['input_types'] = $input_types;
                $data_out[$i][$j]['spacerSeq_TTTT_tip'] = $spacerSeq_TTTT_tip;
                $data_out[$i][$j]['seqId'] = $seqId;
                $data_out[$i][$j]['guideId'] = $guideId;

                $j = $j + 1;

            }
        }
        return $data_out;
//        return '123';
    }

    public function showInfoSequence(Request $request)
    {
        $params = $request->input('params');
        $time_stamp = $params['time_stamp'];
        $cas9_system_types = $params['cas9_system_types'];
        $cpf1_system_types = $params['cpf1_system_types'];
        $cas12b_system_types = $params['cas12b_system_types'];
        $casx_system_types = $params['casx_system_types'];
        $genome = $params['genome'];
        $input_types = $params['radioInputType'];

        $crispr_system = trim($params['radioType']);
        if ($crispr_system == "cpf1") {
            $PAMs = $cpf1_system_types;
        } else if ($crispr_system == "cas9") {
            $PAMs = $cas9_system_types;
        } else if ($crispr_system == "cas12b") {
            $PAMs = $cas12b_system_types;
        } else if ($crispr_system == "casx") {
            $PAMs = $casx_system_types;
        }

        $seedLength = $params['seedLength'];
        if($params['offtarget_checked']){
            $offtarget = 'offtarget';
        }else{
            $offtarget = 'noofftarget';
        }

        if(!Storage::disk('uploads')->exists($time_stamp)){
            Storage::disk('uploads')->makeDirectory($time_stamp);
        }
        $upload_dir = public_path().'/uploads/'.$time_stamp;
        $main_path = public_path();

        $seqs_list = [];
        if ($input_types=="sequence"){
            $input_seq = strtoupper($params['inputValue_sequence']);
            $seq_name_hide = "inputFile";

            $seqs_list[0]["seq_name"] = $seq_name_hide;
            $seqs_list[0]["input_seq"] = $input_seq;
        }else{
            $table_seq = $params['table_seq'];
            $input_seqs = json_decode($table_seq,true);

            $input_seq = '';
            for($i = 0; $i < count($input_seqs); $i++) {
                $input_seq = strtoupper($input_seqs[$i]['input_seq']);
                $seq_name_hide = $input_seqs[$i]['seq_name_hide'];

                $seqs_list[$i]["seq_name"] = $seq_name_hide;
                $seqs_list[$i]["input_seq"] = $input_seq;
            }
        }
        $count_num = 0;
        $PAMs_array_len = 0;
        for ($i = 0; $i < count($seqs_list); $i++) {
            $seq_name_hide = $seqs_list[$i]["seq_name"];
            $input_seq = $seqs_list[$i]["input_seq"];

            # 判断是否在运行
            $PAMs_array = $PAMs;//explode(",", $PAMs);
            $PAMs_array_len = count($PAMs_array);

            foreach ($PAMs_array as $PAM) {
                $PAM1 = explode(":", $PAM)[1];

                $outputFile1 = "$upload_dir/$PAM1.$seq_name_hide.inputFile.o1.txt";
                $outputFile2 = "$upload_dir/$PAM1.$seq_name_hide.inputFile.o2.txt";

                if (file_exists($outputFile1)) {
                    $count_num++;
                }
            }
        }

        $isFinished = FALSE;
        if ($count_num == count($seqs_list) * $PAMs_array_len) {
            $isFinished = TRUE;

        } elseif ($count_num >= 1 && $count_num != count($seqs_list) * $PAMs_array_len) {
            $isFinished = FALSE;
            return 'running';

        } elseif ($count_num == 0) {
            $isFinished = FALSE;
            return 'running';
        }

        $data_out = [];
        if ($isFinished){
            for($i = 0; $i < count($seqs_list); $i++) {
                $seq_name_hide = $seqs_list[$i]["seq_name"];
                $input_seq = $seqs_list[$i]["input_seq"];
                $out = "$upload_dir/$seq_name_hide.results.txt";

                if (!file_exists($out)){
                    $in = $input_seq;
                    $PAMs_str = implode(",", $PAMs);
                    $param1 = " -in " . $in . " -out " . $out . " -crispr_system " . $crispr_system . " -PAMs " . $PAMs_str
                        . " -seq_name ". $seq_name_hide. " -input_types ". $input_types. " -seedLength ". $seedLength. " -is_offtarget ". $offtarget;

                    exec("perl /var/www/ascrispr_backend/public/bin/gh_ascrisprA.pl" . $param1 . " >> " . "$upload_dir/gh_ascrisprA.log 2>&1");
                    $log_file = $time_stamp.'/showInfoSequence_gh_ascrisprA_log.txt';
                    $cmd = "perl /var/www/ascrispr_backend/public/bin/gh_ascrisprA.pl" . $param1 . " >> " . "$upload_dir/gh_ascrisprA.log 2>&1";
                    Storage::disk('uploads')->put($log_file, $cmd);
                }
            }
            for($i=0; $i<count($seqs_list);$i++) {
                $seq_name_hide = $seqs_list[$i]["seq_name"];
                $input_seq = $seqs_list[$i]["input_seq"];
                $resultsFile = $upload_dir . "/" . $seq_name_hide . ".results.txt";

                $data = $this -> read_data_file($resultsFile);
                foreach ($data as $ele) {
                    $Targeting_Strand = $ele[4];
                }
                $j = 0;
                $data = $this -> read_data_file($resultsFile);
                if(sizeof($data)==0){
                    $data_out[$i][$j]['spacerSeq_original'] = '';
                    $data_out[$i][$j]['PAM_original'] = '';
                    $data_out[$i][$j]['Direction_link'] = '';
                    $data_out[$i][$j]['N1'] = '';
                    $data_out[$i][$j]['N2'] = '';
                    $data_out[$i][$j]['spacerStart'] = '';
                    $data_out[$i][$j]['spacerEnd'] = '';
                    $data_out[$i][$j]['PAMStart'] = '';
                    $data_out[$i][$j]['PAMEnd'] = '';
                    $data_out[$i][$j]['MUTposStart'] = '';
                    $data_out[$i][$j]['MUTposEnd'] = '';
                    $data_out[$i][$j]['PAM_IUB'] = '';
                    $data_out[$i][$j]['seq_name_hide'] = '';

                    $data_out[$i][$j]['spacerSeq'] = '';
                    $data_out[$i][$j]['PAMSeq'] = '';
                    $data_out[$i][$j]['Crispr_Type'] = '';
                    $data_out[$i][$j]['Targeting_Strand'] = '';
                    $data_out[$i][$j]['Direction'] = '';
                    $data_out[$i][$j]['targetGenomeGeneLocus'] = '';
                    $data_out[$i][$j]['GC_content'] = '';
                    $data_out[$i][$j]['mitSpecScore'] = '';
                    $data_out[$i][$j]['Xu_Score'] = '';
                    $data_out[$i][$j]['Doench16_Score'] = '';
                    $data_out[$i][$j]['Moreno_Mateos_Score'] = '';
                    $data_out[$i][$j]['Azimuth_in_vitro_Score'] = '';
                    $data_out[$i][$j]['Najm2018'] = '';
                    $data_out[$i][$j]['DeepCpf1'] = '';
                    $data_out[$i][$j]['Self_complementarity'] = '';
                    $data_out[$i][$j]['offtargetCount'] = '';
                    $data_out[$i][$j]['Enzyme_Information'] = '';
                    $data_out[$i][$j]['input_seq'] = '';
                    $data_out[$i][$j]['crispr_system'] = '';
                    $data_out[$i][$j]['input_types'] = '';
                    $data_out[$i][$j]['spacerSeq_TTTT_tip'] = '';
                    $j = $j + 1;
                }
                foreach ($data as $ele) {
                    $rank = $ele[0];
                    $spacerSeq_original = $ele[1];
                    $spacerSeq = $ele[1];
                    $PAM_original = $ele[2];
                    $PAMSeq = $ele[2];
                    $Crispr_Type = $ele[3];
                    $Targeting_Strand = $ele[4];
                    $Direction = $ele[5];
                    $GC_content = $ele[6];
                    $GC_content = $GC_content * 100;
                    $GC_content = round($GC_content);
                    $Self_complementarity = $ele[20];
                    $Enzyme_Information = $ele[7];
                    $N1 = $ele[8];
                    $N2 = $ele[9];
                    $outStart = $ele[10];
                    $spacerStart = $ele[11];
                    $spacerEnd = $ele[12];
                    $PAMStart = $ele[13];
                    $PAMEnd = $ele[14];
                    $PAMLen = $ele[15];
                    $MUTposStart = $ele[16];
                    $MUTposEnd = $ele[17];
                    $iInputSeq = $ele[18];
                    $str_print = $ele[19];

                    $inputSeq_type = $ele[21];
                    if ($Direction == "+") {
                        $Direction_link = "plus";
                    } else {
                        $Direction_link = "minus";
                    }

                    $tmparray = explode(":", $Crispr_Type);
                    if (count($tmparray) > 1) {
                        $PAM_IUB = $tmparray[1];
                    }

                    $str_for_link = "$PAMSeq:$Targeting_Strand:$Direction_link:$N1:$N2:$spacerStart:$spacerEnd:$PAMStart:$PAMEnd:$MUTposStart:$MUTposEnd:$crispr_system:$PAM_IUB:$time_stamp:$seq_name_hide";
//                    $Enzyme_Information = "<a href='ascrispr/enzymeInformation/$spacerSeq/$PAM_original/$str_for_link' target='_blank'>$Enzyme_Information <span class='glyphicon glyphicon-new-window'></span></a>";

                    $isSNP_PAM = FALSE;
                    if ($Targeting_Strand == "Varied" || $Targeting_Strand == "Alternative") {
                        if ($inputSeq_type == "SNP") {
                            if ($MUTposStart == $MUTposEnd) {
                                if ($MUTposStart <= $spacerEnd && $MUTposStart >= $spacerStart) {
                                    $posStart = $MUTposStart - $spacerStart;
                                    $len = 1;
                                    $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                                }
                                if ($MUTposStart <= $PAMEnd && $MUTposStart >= $PAMStart) {
                                    $posStart = $MUTposStart - $PAMStart;
                                    $len = 1;
                                    $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                    $isSNP_PAM = TRUE;
                                }
                            } else {
                                # 相对位置 变为 绝对位置， 0表示从seq的头部开始
                                # Mutation 在spacer上有四种情况，不可以合并为三种
                                if ($MUTposStart < $spacerStart && $MUTposEnd >= $spacerStart && $MUTposEnd <= $spacerEnd) {
                                    $posStart = 0;
                                    $len = $MUTposEnd - $spacerStart + 1;
                                    $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                                } elseif ($MUTposStart >= $spacerStart && $MUTposEnd <= $spacerEnd) {
                                    $posStart = $MUTposStart - $spacerStart;
                                    $len = $MUTposEnd - $MUTposStart + 1;
                                    $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                                } elseif ($MUTposStart >= $spacerStart && $MUTposStart <= $spacerEnd && $MUTposEnd >= $spacerEnd) {
                                    $posStart = $MUTposStart - $spacerStart;
                                    $len = $spacerEnd - $MUTposStart + 1;
                                    $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                                } elseif ($MUTposStart < $spacerStart && $MUTposEnd >= $spacerEnd) {
                                    $posStart = 0;
                                    $len = $spacerEnd - $spacerStart + 1;
                                    $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                                }

                                # 相对位置 变为 绝对位置， 0表示从seq的头部开始
                                # Mutation 在PAM上有四种情况，不可以合并为三种
                                if ($MUTposStart < $PAMStart && $MUTposEnd >= $PAMStart && $MUTposEnd <= $PAMEnd) {
                                    $posStart = 0;
                                    $len = $MUTposEnd - $PAMStart + 1;
                                    $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                    $isSNP_PAM = TRUE;
                                } elseif ($MUTposStart >= $PAMStart && $MUTposEnd <= $PAMEnd) {
                                    $posStart = $MUTposStart - $PAMStart;
                                    $len = $MUTposEnd - $MUTposStart + 1;
                                    $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                    $isSNP_PAM = TRUE;
                                } elseif ($MUTposStart >= $PAMStart && $MUTposStart <= $PAMEnd && $MUTposEnd >= $PAMEnd) {
                                    $posStart = $MUTposStart - $PAMStart;
                                    $len = $PAMEnd - $MUTposStart + 1;
                                    $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                    $isSNP_PAM = TRUE;
                                } elseif ($MUTposStart < $PAMStart && $MUTposEnd >= $PAMEnd) {
                                    $posStart = 0;
                                    $len = $PAMEnd - $PAMStart + 1;
                                    $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                    $isSNP_PAM = TRUE;

                                }
                            }

                        } elseif ($inputSeq_type == "insertion") {
                            if ($MUTposStart == $MUTposEnd) {
                                if ($MUTposStart <= $spacerEnd && $MUTposStart >= $spacerStart) {
                                    $posStart = $MUTposStart - $spacerStart;
                                    $len = 1;
                                    $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                                }
                                if ($MUTposStart <= $PAMEnd && $MUTposStart >= $PAMStart) {
                                    $posStart = $MUTposStart - $PAMStart;
                                    $len = 1;
                                    $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                    $isSNP_PAM = TRUE;
                                }
                            } else {

                                # 相对位置 变为 绝对位置， 0表示从seq的头部开始
                                # Mutation 在spacer上有四种情况，不可以合并为三种
                                if ($MUTposStart < $spacerStart && $MUTposEnd >= $spacerStart && $MUTposEnd <= $spacerEnd) {
                                    $posStart = 0;
                                    $len = $MUTposEnd - $spacerStart + 1;
                                    $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                                } elseif ($MUTposStart >= $spacerStart && $MUTposEnd <= $spacerEnd) {
                                    $posStart = $MUTposStart - $spacerStart;
                                    $len = $MUTposEnd - $MUTposStart + 1;
                                    $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                                } elseif ($MUTposStart >= $spacerStart && $MUTposStart <= $spacerEnd && $MUTposEnd >= $spacerEnd) {
                                    $posStart = $MUTposStart - $spacerStart;
                                    $len = $spacerEnd - $MUTposStart + 1;
                                    $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                                } elseif ($MUTposStart < $spacerStart && $MUTposEnd >= $spacerEnd) {
                                    $posStart = 0;
                                    $len = $spacerEnd - $spacerStart + 1;
                                    $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                                }

                                # 相对位置 变为 绝对位置， 0表示从seq的头部开始
                                # Mutation 在PAM上有四种情况，不可以合并为三种
                                if ($MUTposStart < $PAMStart && $MUTposEnd >= $PAMStart && $MUTposEnd <= $PAMEnd) {
                                    $posStart = 0;
                                    $len = $MUTposEnd - $PAMStart + 1;
                                    $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                    $isSNP_PAM = TRUE;
                                } elseif ($MUTposStart >= $PAMStart && $MUTposEnd <= $PAMEnd) {
                                    $posStart = $MUTposStart - $PAMStart;
                                    $len = $MUTposEnd - $MUTposStart + 1;
                                    $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                    $isSNP_PAM = TRUE;
                                } elseif ($MUTposStart >= $PAMStart && $MUTposStart <= $PAMEnd && $MUTposEnd >= $PAMEnd) {
                                    $posStart = $MUTposStart - $PAMStart;
                                    $len = $PAMEnd - $MUTposStart + 1;
                                    $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                    $isSNP_PAM = TRUE;
                                } elseif ($MUTposStart < $PAMStart && $MUTposEnd >= $PAMEnd) {
                                    $posStart = 0;
                                    $len = $PAMEnd - $PAMStart + 1;
                                    $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                    $isSNP_PAM = TRUE;
                                }
                            }
                        } elseif ($inputSeq_type == "deletion") {
                            if ($MUTposStart <= $spacerEnd && $MUTposStart >= $spacerStart) {
                                $pos = $MUTposStart - $spacerStart;
                                $spacerSeq = $this -> mark_pos_del($spacerSeq, $pos, $N1);
                            } else if ($MUTposStart <= $PAMEnd && $MUTposStart >= $PAMStart) {
                                $pos = $MUTposStart - $PAMStart;
                                $PAMSeq = $this -> mark_pos_del($PAMSeq, $pos, $N1);
                            }
                        }
                    } else {
                        if ($inputSeq_type == "SNP") {
                            if ($MUTposStart == $MUTposEnd) {
                                if ($MUTposStart <= $spacerEnd && $MUTposStart >= $spacerStart) {
                                    $posStart = $MUTposStart - $spacerStart;
                                    $len = 1;
                                    $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                                }
                                if ($MUTposStart <= $PAMEnd && $MUTposStart >= $PAMStart) {
                                    $posStart = $MUTposStart - $PAMStart;
                                    $len = 1;
                                    $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                    $isSNP_PAM = TRUE;
                                }
                            } else {
                                # 相对位置 变为 绝对位置， 0表示从seq的头部开始
                                # Mutation 在spacer上有四种情况，不可以合并为三种
                                if ($MUTposStart < $spacerStart && $MUTposEnd >= $spacerStart && $MUTposEnd <= $spacerEnd) {
                                    $posStart = 0;
                                    $len = $MUTposEnd - $spacerStart + 1;
                                    $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                                } elseif ($MUTposStart >= $spacerStart && $MUTposEnd <= $spacerEnd) {
                                    $posStart = $MUTposStart - $spacerStart;
                                    $len = $MUTposEnd - $MUTposStart + 1;
                                    $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                                } elseif ($MUTposStart >= $spacerStart && $MUTposStart <= $spacerEnd && $MUTposEnd >= $spacerEnd) {
                                    $posStart = $MUTposStart - $spacerStart;
                                    $len = $spacerEnd - $MUTposStart + 1;
                                    $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                                } elseif ($MUTposStart < $spacerStart && $MUTposEnd >= $spacerEnd) {
                                    $posStart = 0;
                                    $len = $spacerEnd - $spacerStart + 1;
                                    $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                                }
                                # 相对位置 变为 绝对位置， 0表示从seq的头部开始
                                # Mutation 在PAM上有四种情况，不可以合并为三种
                                if ($MUTposStart < $PAMStart && $MUTposEnd >= $PAMStart && $MUTposEnd <= $PAMEnd) {
                                    $posStart = 0;
                                    $len = $MUTposEnd - $PAMStart + 1;
                                    $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                    $isSNP_PAM = TRUE;
                                } elseif ($MUTposStart >= $PAMStart && $MUTposEnd <= $PAMEnd) {
                                    $posStart = $MUTposStart - $PAMStart;
                                    $len = $MUTposEnd - $MUTposStart + 1;
                                    $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                    $isSNP_PAM = TRUE;
                                } elseif ($MUTposStart >= $PAMStart && $MUTposStart <= $PAMEnd && $MUTposEnd >= $PAMEnd) {
                                    $posStart = $MUTposStart - $PAMStart;
                                    $len = $PAMEnd - $MUTposStart + 1;
                                    $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                    $isSNP_PAM = TRUE;
                                } elseif ($MUTposStart < $PAMStart && $MUTposEnd >= $PAMEnd) {
                                    $posStart = 0;
                                    $len = $PAMEnd - $PAMStart + 1;
                                    $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                    $isSNP_PAM = TRUE;
                                }
                            }
                        } elseif ($inputSeq_type == "deletion") {
                            if ($MUTposStart == $MUTposEnd) {
                                if ($MUTposStart <= $spacerEnd && $MUTposStart >= $spacerStart) {
                                    $posStart = $MUTposStart - $spacerStart;
                                    $len = 1;
                                    $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                                }
                                if ($MUTposStart <= $PAMEnd && $MUTposStart >= $PAMStart) {
                                    $posStart = $MUTposStart - $PAMStart;
                                    $len = 1;
                                    $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                    $isSNP_PAM = TRUE;
                                }
                            } else {
                                # 相对位置 变为 绝对位置， 0表示从seq的头部开始
                                # Mutation 在spacer上有四种情况，不可以合并为三种
                                if ($MUTposStart < $spacerStart && $MUTposEnd >= $spacerStart && $MUTposEnd <= $spacerEnd) {
                                    $posStart = 0;
                                    $len = $MUTposEnd - $spacerStart + 1;
                                    $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                                } elseif ($MUTposStart >= $spacerStart && $MUTposEnd <= $spacerEnd) {
                                    $posStart = $MUTposStart - $spacerStart;
                                    $len = $MUTposEnd - $MUTposStart + 1;
                                    $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                                } elseif ($MUTposStart >= $spacerStart && $MUTposStart <= $spacerEnd && $MUTposEnd >= $spacerEnd) {
                                    $posStart = $MUTposStart - $spacerStart;
                                    $len = $spacerEnd - $MUTposStart + 1;
                                    $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                                } elseif ($MUTposStart < $spacerStart && $MUTposEnd >= $spacerEnd) {
                                    $posStart = 0;
                                    $len = $spacerEnd - $spacerStart + 1;
                                    $spacerSeq = $this -> mark_pos($spacerSeq, $posStart, $len);
                                }

                                # 相对位置 变为 绝对位置， 0表示从seq的头部开始
                                # Mutation 在PAM上有四种情况，不可以合并为三种
                                if ($MUTposStart < $PAMStart && $MUTposEnd >= $PAMStart && $MUTposEnd <= $PAMEnd) {
                                    $posStart = 0;
                                    $len = $MUTposEnd - $PAMStart + 1;
                                    $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                    $isSNP_PAM = TRUE;
                                } elseif ($MUTposStart >= $PAMStart && $MUTposEnd <= $PAMEnd) {
                                    $posStart = $MUTposStart - $PAMStart;
                                    $len = $MUTposEnd - $MUTposStart + 1;
                                    $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                    $isSNP_PAM = TRUE;
                                } elseif ($MUTposStart >= $PAMStart && $MUTposStart <= $PAMEnd && $MUTposEnd >= $PAMEnd) {
                                    $posStart = $MUTposStart - $PAMStart;
                                    $len = $PAMEnd - $MUTposStart + 1;
                                    $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                    $isSNP_PAM = TRUE;
                                } elseif ($MUTposStart < $PAMStart && $MUTposEnd >= $PAMEnd) {
                                    $posStart = 0;
                                    $len = $PAMEnd - $PAMStart + 1;
                                    $PAMSeq = $this -> mark_pos($PAMSeq, $posStart, $len);
                                    $isSNP_PAM = TRUE;
                                }
                            }
                        } elseif ($inputSeq_type == "insertion") {
                            if ($MUTposStart <= $spacerEnd && $MUTposStart >= $spacerStart) {
                                $pos = $MUTposStart - $spacerStart;
                                $spacerSeq = $this -> mark_pos_del($spacerSeq, $pos, $N2);
                            } else if ($MUTposStart <= $PAMEnd && $MUTposStart >= $PAMStart) {
                                $pos = $MUTposStart - $PAMStart;
                                $PAMSeq = $this -> mark_pos_del($PAMSeq, $pos, $N2);
                            }
                        }
                    }
                    # variant in PAM， type is N R ...
//                    if ($Targeting_Strand == "Reference" || $Targeting_Strand == "WT") {
                        if (strlen($PAMSeq) > strlen($PAM_original)) {
                            if ($isSNP_PAM) {
                                if(strlen($N1)==1 && strlen($N2)==1) {
                                    $PAM_IUB_letter = substr($PAM_IUB, $posStart, 1);
                                    $PAM_original_letter = substr($PAM_original, $posStart, 1);

                                    if ($this->isMatched($PAM_IUB, $PAM_original, $posStart, $N1, $N2, $Direction)) {
                                        continue;
                                    }
                                }
                            }
                        }
//                    }


                    # TTTT
                    $spacerSeq_TTTT_tip = $this -> mark_pos_tttt($spacerSeq);

                    $seqId = $ele[22];
                    $guideId = $ele[23];
                    $targetSeq = $ele[24];

                    if($ele[25] != 'None'){
                        $mitSpecScore = $ele[25] / 100;
                    } else{
                        $mitSpecScore = '--';
                    }

                    $offtargetCount = $ele[26];
                    //  offtargets 0-1-2-3
                    $offtargetsInfoFile = $upload_dir . "/" . $PAM_IUB . "." . $seq_name_hide . ".inputFile.o2.txt";

                    if (file_exists($offtargetsInfoFile)){
                        $data_ot = $this -> read_data_file($offtargetsInfoFile);
                        $mismatchCount_num0 = 0;
                        $mismatchCount_num1 = 0;
                        $mismatchCount_num2 = 0;
                        $mismatchCount_num3 = 0;

                        if ($crispr_system=="cas9") {
                            //     cas9
                            $guideSeq =  $spacerSeq_original.$PAM_original;
                        }else{
                            //     cpf1
                            $guideSeq =  $PAM_original.$spacerSeq_original;
                        }

                        foreach ($data_ot as $ele_ot) {
                            $mismatchCount = $ele_ot[5];
                            $guideSeq_ot = $ele_ot[2];

                            $seqId_ot = $ele_ot[0];
                            $guideId_ot = $ele_ot[1];

                            if (strtoupper($guideSeq) == strtoupper($guideSeq_ot) && $seqId == $seqId_ot && $guideId == $guideId_ot) {
                                if ($mismatchCount == "0") {
                                    $mismatchCount_num0++;
                                } elseif ($mismatchCount == "1") {
                                    $mismatchCount_num1++;
                                } elseif ($mismatchCount == "2") {
                                    $mismatchCount_num2++;
                                } elseif ($mismatchCount == "3") {
                                    $mismatchCount_num3++;
                                }
                            }
                        }
                        $mismatchCount_num_str = "$mismatchCount_num0-$mismatchCount_num1-$mismatchCount_num2-$mismatchCount_num3";
                    } else {
                        $mismatchCount_num_str = '-';
                    }

                    $Xu_Score = '--';
                    $Doench16_Score = '--';
                    $Moreno_Mateos_Score = '--';
                    $Azimuth_in_vitro_Score = '--';
                    $Najm2018 = '--';
                    $DeepCpf1 = '';
                    if ($crispr_system == 'cas9'){
                        $tmparray = explode("SaCas9", $Crispr_Type);
                        if (count($tmparray) == 1) { #SpCas9
                            if ($ele[28] !== 'NotEnoughFlankSeq'){
                                $Doench16_Score = $ele[28] / 100;
                            }
                            if ($ele[29] !== 'NotEnoughFlankSeq'){
                                $Doench16_Old_Score = $ele[29];
                            }
                            if ($ele[30] !== 'NotEnoughFlankSeq'){
                                $Chari_Score = $ele[30];
                            }
                            if ($ele[31] !== 'NotEnoughFlankSeq'){
                                $Xu_Score = $ele[31];
                                $Xu_Score = round(1 / (1 + pow(M_E, -$Xu_Score)), 2);
                            }

                            if ($ele[32] !== 'NotEnoughFlankSeq'){
                                $Doench14_Score = $ele[32];
                            }
                            if ($ele[33] !== 'NotEnoughFlankSeq'){
                                $Wang_Score = $ele[33];
                            }
                            if ($ele[34] !== 'NotEnoughFlankSeq'){
                                $Moreno_Mateos_Score = $ele[34] / 100;
                            }
                            if ($ele[35] !== 'NotEnoughFlankSeq'){
                                $Azimuth_in_vitro_Score = $ele[35] / 100;
                            }
                            if ($ele[36] !== 'NotEnoughFlankSeq'){
                                $CCTop_Score = $ele[36];
                            }
                            if ($ele[37] !== 'NotEnoughFlankSeq'){
                                $Out_of_Frame_Score = $ele[37];
                            }

                            $Najm2018 = '--';
//                            if ($ele[28] !== 'NotEnoughFlankSeq'){
//                                $Doench16_Score = $ele[28] / 100;
//                            }
//
//                            $Doench16_Old_Score = $ele[29];
//                            $Chari_Score = $ele[30];
//
//                            $Xu_Score = $ele[31];
//
//                            $Xu_Score = round(1 / (1 + pow(M_E, -$Xu_Score)), 2);
//
//                            $Doench14_Score = $ele[32];
//                            $Wang_Score = $ele[33];
//                            $Moreno_Mateos_Score = $ele[34] / 100;
//                            $Azimuth_in_vitro_Score = $ele[35] / 100;
//                            $Najm2018 = '--';
//                            $CCTop_Score = $ele[36];
//                            $Out_of_Frame_Score = $ele[37];
//                            $targetGenomeGeneLocus = $ele[39];

                            $targetGenomeGeneLocusTmp = str_replace(PHP_EOL, '', $ele[39]);
                            // #seqId guideId targetSeq mitSpecScore offtargetCount targetGenomeGeneLocus Najm 2018-Score Out-of-Frame-Score
                        } elseif (count($tmparray) > 1) { # SaCas9

                            $Xu_Score = '--';
                            $Doench16_Score = '--';
                            $Moreno_Mateos_Score = '--';
                            $Azimuth_in_vitro_Score = '--';

                            if ($ele[28] !== 'NotEnoughFlankSeq'){
                                $Najm2018 = $ele[28] / 100;
                            }
                            $Out_of_Frame_Score = $ele[29];

                            $targetGenomeGeneLocusTmp = str_replace(PHP_EOL, '', $ele[31]);
                        }

                    }else{
                        if ($ele[28] !== 'NotEnoughFlankSeq'){
                            $DeepCpf1 = round($ele[28]);
                        } else {
                            $DeepCpf1 = '--';
                        }
                        $targetGenomeGeneLocusTmp = str_replace(PHP_EOL, '', $ele[30]);
                    }

                    if ($targetGenomeGeneLocusTmp != "") {
                        $targetGenomeGeneLocus = $targetGenomeGeneLocusTmp;
                    } else {
                        $targetGenomeGeneLocus = "--";
                    }

                    if($targetGenomeGeneLocusTmp == "targetGenomeGeneLocus"){
                        $targetGenomeGeneLocus = "--";
                    }

                    if ($offtargetCount == "None" || $offtargetCount == "-1" || $offtargetCount == "--" || $offtargetCount == "") {
                        $offtargetCount = "0-0-0-0";
                    } else {
                        $offtargetCount = $mismatchCount_num_str;
                    }

                    $data_out[$i][$j]['spacerSeq_original'] = $spacerSeq_original;
                    $data_out[$i][$j]['PAM_original'] = $PAM_original;
                    $data_out[$i][$j]['Direction_link'] = $Direction_link;
                    $data_out[$i][$j]['N1'] = $N1;
                    $data_out[$i][$j]['N2'] = $N2;
                    $data_out[$i][$j]['spacerStart'] = $spacerStart;
                    $data_out[$i][$j]['spacerEnd'] = $spacerEnd;
                    $data_out[$i][$j]['PAMStart'] = $PAMStart;
                    $data_out[$i][$j]['PAMEnd'] = $PAMEnd;
                    $data_out[$i][$j]['MUTposStart'] = $MUTposStart;
                    $data_out[$i][$j]['MUTposEnd'] = $MUTposEnd;
                    $data_out[$i][$j]['PAM_IUB'] = $PAM_IUB;
                    $data_out[$i][$j]['seq_name_hide'] = $seq_name_hide;

                    $data_out[$i][$j]['spacerSeq'] = $spacerSeq;
                    $data_out[$i][$j]['PAMSeq'] = $PAMSeq;
                    $data_out[$i][$j]['Crispr_Type'] = $Crispr_Type;
                    $data_out[$i][$j]['Targeting_Strand'] = $Targeting_Strand;
                    $data_out[$i][$j]['Direction'] = $Direction;
                    $data_out[$i][$j]['targetGenomeGeneLocus'] = $targetGenomeGeneLocus;
                    $data_out[$i][$j]['GC_content'] = $GC_content;
                    $data_out[$i][$j]['mitSpecScore'] = $mitSpecScore;
                    $data_out[$i][$j]['Xu_Score'] = $Xu_Score;
                    $data_out[$i][$j]['Doench16_Score'] = $Doench16_Score;
                    $data_out[$i][$j]['Moreno_Mateos_Score'] = $Moreno_Mateos_Score;
                    $data_out[$i][$j]['Azimuth_in_vitro_Score'] = $Azimuth_in_vitro_Score;
                    $data_out[$i][$j]['Najm2018'] = $Najm2018;
                    $data_out[$i][$j]['DeepCpf1'] = $DeepCpf1;
                    $data_out[$i][$j]['Self_complementarity'] = $Self_complementarity;
                    $data_out[$i][$j]['offtargetCount'] = $offtargetCount;
                    $data_out[$i][$j]['Enzyme_Information'] = $Enzyme_Information;
                    $data_out[$i][$j]['input_seq'] = $input_seq;
                    $data_out[$i][$j]['spacerSeq_TTTT_tip'] = $spacerSeq_TTTT_tip;
                    $data_out[$i][$j]['seqId'] = $seqId;
                    $data_out[$i][$j]['guideId'] = $guideId;

                    $j = $j + 1;

                }
            }
            return $data_out;
        }
    }

// func to read data from flat file
    function read_data_file($file)
    {
        $data = array();
        $fp = fopen($file, "r");
        if (! $fp) {
            echo "<div id='error'>file open eror : $php_errormsg</div>";
        } else {
            while (($row1 = fgets($fp)) !== false) {
                $ele = explode("\t", $row1);
                $data[] = $ele;
            }
        }
        return $data;
    }

// func to read data from flat file
    function read_data_letter($file, $col, $val)
    {
        $data = array();
        $fp = fopen($file, "r");
        if (! $fp) {
            echo "<div id='error'>file open eror : $php_errormsg</div>";
        } else {
            $flag = false;
            while (($row1 = fgets($fp)) !== false) {
                $ele = explode("\t", $row1);

                if (preg_match("/^" . $val . "[0-9A-Za-z\-]+$/i", $ele[$col])) {

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
    function read_data_f($file, $col, $val)
    {
        $data = array();
        $fp = fopen($file, "r");
        if (! $fp) {
            echo "<div id='error'>file open eror : $php_errormsg</div>";
        } else {
            $flag = false;
            while (($row1 = fgets($fp)) !== false) {
                $ele = explode("\t", $row1);

                if ($ele[$col] == $val) {

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

    function export_to_file($file, $variable)
    {
        $fopen = fopen($file, 'wb');
        if (! $fopen) {
            return false;
        }
        fwrite($fopen, "<?php\nreturn " . var_export($variable, true) . ";\n?>");
        fclose($fopen);
        return true;
    }

    function mark_pos_tttt($seq){
        $s = "TTTT";
        $pos = strpos($seq, $s, 0);
        if( $pos !== false){
            $spacerSeq_TTTT_tip = 'Not recommended. TTTT in this guide might terminate the U6 or U3 promoter drived transcription.';
        }else{
            $spacerSeq_TTTT_tip = '';
        }
        return $spacerSeq_TTTT_tip;
    }

    function mark_pos($seq, $pos, $len){
        $seq = strtoupper($seq); # strtolower
        $prefixStr = "<span style='color:red'>";
        $postfixStr= "</span>";

        $seq_mark_tmp =  $prefixStr.strtolower(substr($seq, $pos, $len)).$postfixStr;
        $seq_mark = substr_replace($seq, $seq_mark_tmp, $pos, $len);

        return $seq_mark;
    }

    function mark_pos_long($seq, $posStart, $posEnd){
        $seq = strtoupper($seq); # strtolower
        $prefixStr = "<span style='color:red'>";
        $postfixStr= "</span>";

        $seq_mark_tmp =  $prefixStr.strtolower(substr($seq, $posStart, $posEnd - $posStart + 1)).$postfixStr;
        $seq_mark = substr_replace($seq, $seq_mark_tmp, $posStart, $posEnd - $posStart + 1);

        return $seq_mark;
    }

    function mark_pos_del($seq, $pos, $N){
        $seq = strtoupper($seq); # strtolower
        $prefixStr = "<span style='color:red'>";
        $postfixStr= "</span>";

        $seq_mark_tmp =  $prefixStr.str_repeat("*", strlen($N)).$postfixStr;
        $seq_mark = substr_replace($seq, $seq_mark_tmp, $pos, 0);

        return $seq_mark;
    }

    function reverse_complement($seq0) {
        $seq0 = strtoupper($seq0);
        if ($seq0=="A"){
            $seq = "T";
        }
        if ($seq0=="T"){
            $seq = "A";
        }
        if ($seq0=="C"){
            $seq = "G";
        }
        if ($seq0=="G"){
            $seq = "C";
        }
        return $seq;
    }

    function isMatched($PAM_IUB, $PAM_original, $pos, $N1, $N2, $Direction){
        # returns true if $PAM_IUB_letter (single char) matches $PAM_original_letter (single char)

        $PAM_IUB_letter = substr($PAM_IUB, $pos, 1);
        $PAM_original_letter = substr($PAM_original, $pos, 1);

        if ($Direction == "-"){
            $N1 = $this->reverse_complement($N1);
            $N2 = $this->reverse_complement($N2);
        }

        if($PAM_IUB_letter == "N" && in_array($N2, ["A", "C", "T", "G"]) && in_array($N1, ["A", "C", "T", "G"])){
            return True;
        }elseif($PAM_IUB_letter == "S" && in_array($N2, ["G", "C"]) && in_array($N1, ["G", "C"])){
            return True;
        }elseif($PAM_IUB_letter == "M" && in_array($N2, ["A", "C"]) && in_array($N1, ["A", "C"])){
            return True;
        }elseif($PAM_IUB_letter == "D" && in_array($N2, ["A", "G", "T"]) && in_array($N1, ["A", "G", "T"])){
            return True;
        }elseif($PAM_IUB_letter == "W" && in_array($N2, ["A", "T"]) && in_array($N1, ["A", "T"])){
            return True;
        }elseif($PAM_IUB_letter == "K" && in_array($N2, ["G", "T"]) && in_array($N1, ["G", "T"])){
            return True;
        }elseif($PAM_IUB_letter == "R" && in_array($N2, ["G", "A"]) && in_array($N1, ["G", "A"])){
            return True;
        }elseif($PAM_IUB_letter == "Y" && in_array($N2, ["T", "C"]) && in_array($N1, ["T", "C"])){
            return True;
        }elseif($PAM_IUB_letter == "V" && in_array($N2, ["A", "G", "C"]) && in_array($N1, ["A", "G", "C"])){
            return True;
        }else{
            return False;
        }


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
     * @param  \App\Models\Ascrispr  $ascrispr
     * @return \Illuminate\Http\Response
     */
    public function show(Ascrispr $ascrispr)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ascrispr  $ascrispr
     * @return \Illuminate\Http\Response
     */
    public function edit(Ascrispr $ascrispr)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ascrispr  $ascrispr
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ascrispr $ascrispr)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ascrispr  $ascrispr
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ascrispr $ascrispr)
    {
        //
    }
}
