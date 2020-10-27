<template>
    <div class='grid-content'>
        <el-row>
            <el-col :span='4'><div class="">&nbsp;</div></el-col>
            <el-col :span='18'>
                <div style='font-size: 18px;'>
                    <el-row>
                    </el-row>
                    <el-row>
                         <span style='font-size: 30px; color: rgb(216, 164, 6);margin-bottom: 0'>{{$t('ascrispr_title')}} </span>
                    </el-row>

                    <el-row>
                        <el-col :span='6'>
                            <div>
                                &nbsp;
                            </div>
                        </el-col>
                        <el-col :span='16'>
                            <div>
                                <a href="http://www.genemed.tech/ascrispr" title="AsCRISPR" rel="home">
                                    <img :src="require('@/assets/img/ascrispr/AsCRISPR_Logo_b2.png')" height="30%" width="50%">
                                </a>
                            </div>
                        </el-col>
                        <el-col :span='2'>
                            <div>

                            </div>
                        </el-col>
                    </el-row>
                </div>
            </el-col>
            <el-col :span='2'><div></div></el-col>
        </el-row>

        <el-row :gutter='24'>
            <el-col :span='4'><div class="">&nbsp;</div></el-col>
            <el-col :span='16'>
                <el-card class='box-card'>
                    <el-form ref='ruleForm' :model='ruleForm' :rules='rules' label-width='100px'>
                        <el-form-item v-bind:label='$t("ascrispr_input_type")'>
                            <el-radio-group v-model='ruleForm.radioInputType' @change='showRadioInputType'>
                                <el-radio label='sequence'>Sequence</el-radio>
                                <el-radio label='dbSNP'>dbSNP</el-radio>
                            </el-radio-group>
                        </el-form-item>
                        <el-form-item v-if='show_sequence_type' prop='inputValue_sequence'>
                            <el-input type='textarea' v-model='ruleForm.inputValue_sequence' :rows=5 style='width:90%;' required @change="inputChanged()"></el-input>
                            <div style='width:90%;'>
                                <a href='javascript:void(0);' v-on:click='getExample1' style='text-decoration:underline;'>Human PINK1 p.G411S</a> /
                                <a href='javascript:void(0);' v-on:click='getExample6' style='text-decoration:underline;'>Human TGFBI p.L527R</a> /
                                <a href='javascript:void(0);' v-on:click='getExample2' style='text-decoration:underline;'>Human RHO p.P23H</a> /
                                <a href='javascript:void(0);' v-on:click='getExample3' style='text-decoration:underline;'>Human LMNA p.G608G</a> /
                                <a href='javascript:void(0);' v-on:click='getExample4' style='text-decoration:underline;'>Human TOR1A p.E303del</a> /
                                <a href='javascript:void(0);' v-on:click='getExample5' style='text-decoration:underline;'>Human COL7A1 c.8068_8084delinsGA</a> /
                                <a href='javascript:void(0);' v-on:click='getExample7' style='text-decoration:underline;'>Mouse CRYGC p.R153fs</a>
                            </div>
                        </el-form-item>

                        <el-form-item v-if='show_dbSNP_type' prop='inputValue_dbSNP'>
                            <el-input type='textarea' v-model='ruleForm.inputValue_dbSNP' :rows=2 style='width:70%;'></el-input>
                            <el-tooltip class='item' effect='dark' content='Retrieve SNP flanking sequences' placement='bottom-start'>
                                <el-button ref='submit_seq' type='success' size='medium' @click='submitGetSequences("ruleForm")'>Get Sequences &nbsp;<i class='el-icon-question'></i></el-button>
                            </el-tooltip>
                            <div>
                                <a href='javascript:void(0);' v-on:click='getExample8' style='text-decoration:underline;'>rs62621675</a> /
                                <a href='javascript:void(0);' v-on:click='getExample9' style='text-decoration:underline;'>rs28937568</a> /
                                <a href='javascript:void(0);' v-on:click='getExample10' style='text-decoration:underline;'>rs1799759</a> /
                                <a href='javascript:void(0);' v-on:click='getExample11' style='text-decoration:underline;'>rs45478900</a>/
                                <a href='javascript:void(0);' v-on:click='getExample12' style='text-decoration:underline;'>rs2893734</a>
                            </div>

                            <el-table ref='multipleTableDBSNP' :data='tableDataInputDBSNP' :border='true' style='width:90%;'
                                      v-loading='loadingInputDBSNP' stripe empty-text="empty"
                                      :default-sort = '{prop: "seq_name", order: "descending"}' @selection-change='handleSelectionChange'>
                                <el-table-column type='selection'></el-table-column>
                                <el-table-column prop='seq_name' label='Name' width='200px'></el-table-column>
                                <el-table-column prop='input_seq' label='Sequences'></el-table-column>
                                <el-table-column prop='seq_name_hide' v-if='isShowColumn'></el-table-column>
                                <el-table-column prop='chr' v-if='isShowColumn'></el-table-column>
                                <el-table-column prop='input_seq_org' v-if='isShowColumn'></el-table-column>

                            </el-table>

                        </el-form-item>

                        <el-form-item label='Genome:'>
                            <el-select v-model='ruleForm.genome' placeholder='please select genome' style='width:500px;'>
                                <el-option value='hg19' label='Homo sapiens (hg19/GRCh37)'></el-option>
                                <el-option value='hg38' label='Homo sapiens (hg38/GRCh38)'></el-option>
                                <el-option value='mm10' label='Mus musculus (mm10/GRCm38)' :disabled="isDisabled_mm10"></el-option>
                            </el-select>
                        </el-form-item>

                        <el-form-item label='Types:'>
                            <el-radio-group v-model='ruleForm.radioType' @change='showRadioType'>
                                <el-radio label='cas9'>Cas9 & Variants</el-radio>
                                <el-radio label='cpf1'>Cpf1 & Variants</el-radio>
                                <el-radio label='cas12b'>Cas12b & Variants</el-radio>
                                <el-radio label='casx'>CasX & Variants</el-radio>
                            </el-radio-group>
                        </el-form-item>

                        <el-form-item label='Subtypes:' v-if='show_cas9_system_types' prop='cas9_system_types'>
                            <el-select v-model='ruleForm.cas9_system_types' placeholder='please select Cas9 types' multiple filterable style='width:500px;'>
                                <el-option value='SpCas9:NGG' label='SpCas9:NGG'></el-option>
                                <el-option value='SpCas9-VRER:NGCG' label='SpCas9-VRER:NGCG'></el-option>
                                <el-option value='SpCas9-EQR:NGAG' label='SpCas9-EQR:NGAG'></el-option>
                                <el-option value='SpCas9-V\(R\)QR:NGA' label='SpCas9-V(R)QR:NGA'></el-option>
                                <el-option value='SaCas9:NNGRRT' label='SaCas9:NNGRRT (R = A or G)'></el-option>
                                <el-option value='SaCas9-KKH:NNNRRT' label='SaCas9-KKH:NNNRRT (R = A or G)'></el-option>
                                <el-option value='NmCas9:NNNNGMTT' label='NmCas9:NNNNGMTT (M = A or C)'></el-option>
                                <el-option value='St1Cas9:NNAGAAW' label='St1Cas9:NNAGAAW (W = A or T)'></el-option>
                                <el-option value='TdCas9:NAAAAC' label='TdCas9:NAAAAC'></el-option>
                                <el-option value='CjCas9:NNNNRYAC' label='CjCas9:NNNNRYAC (R = A or G; Y = C or T )'></el-option>
                                <el-option value='Nme2Cas9:NNNNAC' label='Nme2Cas9:NNNNAC'></el-option>
                                <el-option value='GeoCas9:NNNNCRAA' label='GeoCas9:NNNNCRAA (R = A or G)'></el-option>
                                <el-option value='GeoCas9:NNNNGMAA' label='GeoCas9:NNNNGMAA (M = A or C)'></el-option>
                                <el-option value='St3Cas9:NGGNG' label='St3Cas9:NGGNG'></el-option>
                            </el-select>
                            <el-button type='success' size='medium' @click='selectAllSubtypes'>Select All</el-button>
                            <el-button type='success' size='medium' @click='unselectAllSubtypes'>Unselect All</el-button>
                        </el-form-item>

                        <el-form-item label='Subtypes:' v-if='show_cpf1_system_types' prop='cpf1_system_types'>
                            <el-select v-model='ruleForm.cpf1_system_types' placeholder='please select Cpf1 types' multiple filterable style='width:500px;' @change='selectCpf1Change'>
                                <el-option value='As\(Lb\)Cpf1:TTTV' label='As(Lb)Cpf1: TTTV (V = A or C or G)'></el-option>
                                <el-option value='FnCpf1:TTV' label='FnCpf1: TTV (V = A or C or G)'></el-option>
                                <el-option value='FnCpf1:KYTV' label='FnCpf1: KYTV (K = G or T; Y = C or T; V = A or C or G)'></el-option>
                                <el-option value='AsCpf1-RR:TYCV' label='AsCpf1-RR: TYCV (Y = C or T; V = A or C or G)'></el-option>
                                <el-option value='AsCpf1-RVR:TATV' label='AsCpf1-RVR: TATV (V = A or C or G)'></el-option>
                            </el-select>
                            <el-button type='success' size='medium' @click='selectAllSubtypes'>Select All</el-button>
                            <el-button type='success' size='medium' @click='unselectAllSubtypes'>Unselect All</el-button>
                        </el-form-item>

                        <el-form-item label='Subtypes:' v-if='show_cas12b_system_types' prop='cas12b_system_types'>
                            <el-select v-model='ruleForm.cas12b_system_types' placeholder='please select Cas12b types' multiple filterable style='width:500px;'>
                                <el-option value='AaCas12b:TTN' label='AaCas12b: TTN'></el-option>
                                <el-option value='AkCas12b:TTTN' label='AkCas12b: TTTN'></el-option>
                                <el-option value='BhCas12b:ATTN' label='BhCas12b: ATTN'></el-option>
                            </el-select>
                            <el-button type='success' size='medium' @click='selectAllSubtypes'>Select All</el-button>
                            <el-button type='success' size='medium' @click='unselectAllSubtypes'>Unselect All</el-button>
                        </el-form-item>

                        <el-form-item label='Subtypes:' v-if='show_casx_system_types' prop='casx_system_types'>
                            <el-select v-model='ruleForm.casx_system_types' placeholder='please select CasX types' multiple filterable style='width:500px;'>
                                <el-option value='Dpb\(Plm\)CasX:TTCN' label='Dpb(Plm)CasX: TTCN'></el-option>
                            </el-select>
                            <el-button type='success' size='medium' @click='selectAllSubtypes'>Select All</el-button>
                            <el-button type='success' size='medium' @click='unselectAllSubtypes'>Unselect All</el-button>
                        </el-form-item>

                        <el-form-item label='Seed Length:'>
                            <div style='width:90%;'>
                                <el-slider
                                        v-model='seedLength'
                                        show-input
                                        show-stops
                                        :step='1'
                                        :max='seedLengthMax'
                                        :min='1'>
                                </el-slider>
                            </div>
                        </el-form-item>

                        <el-form-item v-bind:label='$t("ascrispr_offtarget")'>
                            <el-checkbox v-model="offtarget_checked" @change="offtarget_checked_change()">Check for off-target</el-checkbox>
                        </el-form-item>

                        <el-form-item>
                            <el-button ref='submitButton' type='primary' @click='submitForm("ruleForm")'>Submit</el-button>
                            <el-button @click='resetForm("ruleForm")'>Reset</el-button>
                        </el-form-item>
                    </el-form>
                </el-card>
            </el-col>
        </el-row>
        <hr style='border: 2px solid black' v-if='showResult'>

        <!--<el-button  plain icon='el-icon-download' @click='sorttable()'>sorttable</el-button>-->


        <el-row :gutter='24' v-if='showResult'>
            <el-col :span='4'><div class="">&nbsp;</div></el-col>
            <el-col :span='18'>
                <p>Your request was successfully submitted. It will be finished within 10 minutes. Thank you for your interests in using AsCRISPR.</p>
                <p>You can check your results by the following link for later:
                    <router-link tag='a' target='_blank' style='text-decoration:underline;' :to='{path: "/ascrisprByTimeStamp", query:{time_stamp: this.ruleForm.time_stamp}}'>http://www.genemed.tech/ascrispr/ascrisprByTimeStamp?time_stamp={{this.ruleForm.time_stamp}}</router-link>
                </p>
                <strong>* Note: Bookmark the link for later access</strong>
            </el-col>
        </el-row>

        <el-row :gutter='24' v-if='showResult_rs'>
            <el-col :span='4'><div class="">&nbsp;</div></el-col>
            <el-col :span='18'>

                <div class="container-fluid">
                    <h4 id="seqname" style="color: black; word-wrap:break-word;"></h4>
                    <div class="row">
                        <div id="chart_par" class="col">
                            <div id="chart">
                            </div>
                        </div>
                    </div>
                </div>
                <br><br><br>

            </el-col>
        </el-row>

        <el-row :gutter='24' v-if='showNoResult'>
            <el-col :span='4'><div class="">&nbsp;</div></el-col>
            <el-col :span='18'>
                <div style="color: red"><strong>No result</strong></div>
            </el-col>
        </el-row>
        <!--v-if='showResult'-->
        <el-row :gutter='24' v-if='showCas9Result'>
            <el-col :span='24' >
                <el-card class='box-card'>
                    <div v-for='item in tableDataSequence' >
                        <div slot='header' class='collapse_title_bg_color_Warning' style='font-size: 20px;'>&nbsp;&nbsp;
                            <template>
                                <span v-if='ruleForm.radioInputType=="sequence"'>Sequence: {{ruleForm.inputValue_sequence}}</span>
                                <span v-else-if='ruleForm.radioInputType=="dbSNP"'>dbSNP: {{item[0].input_seq}}</span>
                                <!--<span v-html='scope.row.spacerSeq'></span>-->
                            </template>
                        </div>
                        <div class='toolbar' >
                            <el-button  plain icon='el-icon-download' @click='exportExcel()'>Export</el-button>
                        </div>
                        <el-table :data='item.slice((current_pageSequence-1)*pageSizeSequence,current_pageSequence*pageSizeSequence)' :border='true' style='width: 100%'
                                  v-loading='loadingSequence' stripe
                                  :default-sort = '{prop: "ranking", order: "ascending"}'>
                            <el-table-column prop='ranking' label='Ranking' :render-header='renderHeader' :labelClassName='Ranking_tip' sortable></el-table-column>
                            <el-table-column prop='spacerSeq' label='Spacer Sequence' :render-header='renderHeader' :labelClassName='SpacerSequence_tip' sortable>
                                <template slot-scope='scope'>
                                    <span v-html='scope.row.spacerSeq'></span>
                                    <div v-if="scope.row.spacerSeq_TTTT_tip !== ''">
                                        <el-tooltip class="item" effect="dark" :content='scope.row.spacerSeq_TTTT_tip' placement="top-start">
                                            <i class="el-icon-question"></i>
                                        </el-tooltip>
                                    </div>
                                </template>
                            </el-table-column>
                            <el-table-column prop='PAMSeq' label='PAM' :render-header='renderHeader' :labelClassName='PAM_tip' sortable>
                                <template slot-scope='scope'>
                                    <span v-html='scope.row.PAMSeq'></span>
                                </template>
                            </el-table-column>
                            <el-table-column prop='Crispr_Type' :label='crispr_type_label' :render-header='renderHeader' :labelClassName='crispr_type_tip'  sortable></el-table-column>
                            <el-table-column prop='Targeting_Strand' label='Targeting Strand' :render-header='renderHeader' :labelClassName='TargetingStrand_tip'  sortable></el-table-column>
                            <el-table-column prop='Direction' label='Direction' :render-header='renderHeader' :labelClassName='Direction_tip'  sortable></el-table-column>
                            <el-table-column prop='targetGenomeGeneLocus' label='Gene Locus' :render-header='renderHeader' :labelClassName='GeneLocus_tip'  sortable></el-table-column>
                            <el-table-column prop='GC_content' label='GC content (%)' :render-header='renderHeader' :labelClassName='GCcontent_tip'  sortable>
                                <template slot-scope='scope'>
                                    <div v-if='scope.row.GC_content>=80'>
                                        <el-tooltip content='Not recommended. Too high GC content.' placement='top'>
                                            <span style='color:red'>
                                                 {{scope.row.GC_content}}
                                            </span>
                                        </el-tooltip>
                                    </div>
                                    <div v-else-if='scope.row.GC_content<=20'>
                                        <el-tooltip content='Not recommended. Too low GC content.' placement='top'>
                                            <span style='color:red'>
                                                 {{scope.row.GC_content}}
                                            </span>
                                        </el-tooltip>
                                    </div>
                                    <div v-else>{{scope.row.GC_content}}</div>
                                </template>
                            </el-table-column>
                            <el-table-column prop='mitSpecScore' label='Specificity score' :render-header='renderHeader' :labelClassName='Specificityscore_tip'  sortable></el-table-column>
                            <el-table-column prop='Xu_Score' label='Xu 2015 score' :render-header='renderHeader' :labelClassName='Xu2015score_tip'  sortable></el-table-column>
                            <el-table-column prop='Doench16_Score' label='Doench 2016 score' :render-header='renderHeader' :labelClassName='Doench2016score_tip'  sortable></el-table-column>
                            <el-table-column prop='Moreno_Mateos_Score' label='Moreno Mateos 2015 score' :render-header='renderHeader' :labelClassName='MorenoMateos2015score_tip'  sortable></el-table-column>
                            <el-table-column prop='Azimuth_in_vitro_Score' label='Azimuth in vitro score' :render-header='renderHeader' :labelClassName='AzimuthInVitroScore_tip'  sortable></el-table-column>
                            <el-table-column prop='Najm2018' label='Najm 2018 score' :render-header='renderHeader' :labelClassName='Najm2018score_tip'  sortable></el-table-column>
                            <el-table-column prop='Self_complementarity' label='Self-complementarity' :render-header='renderHeader' :labelClassName='SelfComplementarity_tip'  sortable></el-table-column>
                            <el-table-column prop='offtargetCount' label='Off-targets 0-1-2-3' :render-header='renderHeader' :labelClassName='Offtargets_tip'  sortable>
                                <template slot-scope='scope'>
                                    <router-link tag='a' target='_blank' :to='{path:"ascrisprOfftargets",
                                                query:{
                                                inputType: scope.row.inputType,
                                                spacerSeq_original: scope.row.spacerSeq_original,
                                                PAM_original: scope.row.PAM_original,
                                                spacerSeq: scope.row.spacerSeq,
                                                PAMSeq: scope.row.PAMSeq,
                                                Crispr_Type: scope.row.Crispr_Type,
                                                crispr_system: scope.row.crispr_system,
                                                Targeting_Strand: scope.row.Targeting_Strand,
                                                Direction_link: scope.row.Direction_link,
                                                N1: scope.row.N1,
                                                N2: scope.row.N2,
                                                spacerStart: scope.row.spacerStart,
                                                spacerEnd: scope.row.spacerEnd,
                                                PAMStart: scope.row.PAMStart,
                                                PAMEnd: scope.row.PAMEnd,
                                                MUTposStart: scope.row.MUTposStart,
                                                MUTposEnd: scope.row.MUTposEnd,
                                                PAM_IUB: scope.row.PAM_IUB,
                                                seq_name_hide: scope.row.seq_name_hide,
                                                seqId: scope.row.seqId,
                                                guideId: scope.row.guideId,
                                                time_stamp: scope.row.time_stamp}}'>{{scope.row.offtargetCount}}</router-link>
                                    <!--<a :href=''http://www.ncbi.nlm.nih.gov/pubmed/'+ scope.row.Enzyme_Information' target='_blank' style='text-decoration:underline;'><span v-html='scope.row.Enzyme_Information'></span></a>-->
                                </template>
                            </el-table-column>
                            <el-table-column prop='Enzyme_Information' label='Enzyme Information' :render-header='renderHeader' :labelClassName='EnzymeInformation_tip'  sortable>
                                <template slot-scope='scope'>
                                    <router-link tag='a' target='_blank' :to='{path:"ascrisprEnzymesInfoMatched",
                                                query:{
                                                spacerSeq_original: scope.row.spacerSeq_original,
                                                PAM_original: scope.row.PAM_original,
                                                spacerSeq: scope.row.spacerSeq,
                                                PAMSeq: scope.row.PAMSeq,
                                                Crispr_Type: scope.row.Crispr_Type,
                                                crispr_system: scope.row.crispr_system,
                                                Targeting_Strand: scope.row.Targeting_Strand,
                                                Direction_link: scope.row.Direction_link,
                                                N1: scope.row.N1,
                                                N2: scope.row.N2,
                                                spacerStart: scope.row.spacerStart,
                                                spacerEnd: scope.row.spacerEnd,
                                                PAMStart: scope.row.PAMStart,
                                                PAMEnd: scope.row.PAMEnd,
                                                MUTposStart: scope.row.MUTposStart,
                                                MUTposEnd: scope.row.MUTposEnd,
                                                PAM_IUB: scope.row.PAM_IUB,
                                                seq_name_hide: scope.row.seq_name_hide,
                                                seqId: scope.row.seqId,
                                                guideId: scope.row.guideId,
                                                time_stamp: scope.row.time_stamp}}'>{{scope.row.Enzyme_Information}}</router-link>
                                    <!--<a :href=''http://www.ncbi.nlm.nih.gov/pubmed/'+ scope.row.Enzyme_Information' target='_blank' style='text-decoration:underline;'><span v-html='scope.row.Enzyme_Information'></span></a>-->
                                </template>
                            </el-table-column>
                        </el-table>

                        <el-row class='page'>
                            <el-col :span='22'>
                                <el-pagination
                                        background
                                        @current-change='paginationSequence'
                                        @size-change='sizeChangeSequence'
                                        :current-page.sync='current_pageSequence'
                                        :page-sizes='[10,20,25,50]'
                                        layout='total,sizes,prev, pager, next'
                                        prev-text="prev"
                                        next-text="next"
                                        :page-size.sync='pageSizeSequence'
                                        :total='totalSequence'>
                                </el-pagination>
                            </el-col>
                        </el-row>
                    </div>

                </el-card>
            </el-col>
        </el-row>
        <el-row :gutter='24' v-if='showCpf1Result'>
            <el-col :span='24' >
                <el-card class='box-card'>
                    <div v-for='item in tableDataSequence' >
                        <div slot='header' class='collapse_title_bg_color_Warning' style='font-size: 20px;'>&nbsp;&nbsp;
                            <template>
                                <span v-if='ruleForm.radioInputType=="sequence"'>Sequence: {{ruleForm.inputValue_sequence}}</span>
                                <span v-else-if='ruleForm.radioInputType=="dbSNP"'>dbSNP: {{item[0].input_seq}}</span>
                            </template>
                        </div>

                        <div class='toolbar'>
                            <el-button  plain icon='el-icon-download' @click='exportExcel()'>Export</el-button>
                        </div>
                        <el-table :data='item.slice((current_pageSequence-1)*pageSizeSequence,current_pageSequence*pageSizeSequence)' :border='true' style='width: 100%'
                                  v-loading='loadingSequence' stripe
                                  :default-sort = '{prop: "ranking", order: "ascending"}'>
                            <el-table-column prop='ranking' label='Ranking' :render-header='renderHeader' :labelClassName='Ranking_tip' sortable></el-table-column>
                            <el-table-column prop='PAMSeq' label='PAM' :render-header='renderHeader' :labelClassName='PAM_tip' sortable>
                                <template slot-scope='scope'>
                                    <span v-html='scope.row.PAMSeq'></span>
                                </template>
                            </el-table-column>
                            <el-table-column prop='spacerSeq' label='Spacer Sequence' :render-header='renderHeader' :labelClassName='SpacerSequence_tip' sortable>
                                <template slot-scope='scope'>
                                    <span v-html='scope.row.spacerSeq'></span>
                                    <div v-if="scope.row.spacerSeq_TTTT_tip !== ''">
                                        <el-tooltip class="item" effect="dark" :content='scope.row.spacerSeq_TTTT_tip' placement="top-start">
                                            <i class="el-icon-question"></i>
                                        </el-tooltip>
                                    </div>
                                </template>
                            </el-table-column>
                            <el-table-column prop='Crispr_Type' :label='crispr_type_label' :render-header='renderHeader' :labelClassName='crispr_type_tip'  sortable></el-table-column>
                            <el-table-column prop='Targeting_Strand' label='Targeting Strand' :render-header='renderHeader' :labelClassName='TargetingStrand_tip'  sortable></el-table-column>
                            <el-table-column prop='Direction' label='Direction' :render-header='renderHeader' :labelClassName='Direction_tip'  sortable></el-table-column>
                            <el-table-column prop='targetGenomeGeneLocus' label='Gene Locus' :render-header='renderHeader' :labelClassName='GeneLocus_tip'  sortable></el-table-column>
                            <el-table-column prop='GC_content' label='GC content (%)' :render-header='renderHeader' :labelClassName='GCcontent_tip'  sortable>
                                <template slot-scope='scope'>
                                    <div v-if='scope.row.GC_content>=80'>
                                        <el-tooltip content='Not recommended. Too high GC content.' placement='top'>
                                            <span style='color:red'>
                                                 {{scope.row.GC_content}}
                                            </span>
                                        </el-tooltip>
                                    </div>
                                    <div v-else-if='scope.row.GC_content<=20'>
                                        <el-tooltip content='Not recommended. Too low GC content.' placement='top'>
                                            <span style='color:red'>
                                                 {{scope.row.GC_content}}
                                            </span>
                                        </el-tooltip>
                                    </div>
                                    <div v-else>{{scope.row.GC_content}}</div>
                                </template>
                            </el-table-column>
                            <el-table-column prop='DeepCpf1' label='Deep Cpf1' :render-header='renderHeader' :labelClassName='DeepCpf1_tip'  sortable></el-table-column>
                            <el-table-column prop='Self_complementarity' label='Self-complementarity' :render-header='renderHeader' :labelClassName='SelfComplementarity_tip'  sortable></el-table-column>
                            <el-table-column prop='offtargetCount' label='Off-targets 0-1-2-3' :render-header='renderHeader' :labelClassName='Offtargets_tip'  sortable>
                                <template slot-scope='scope'>
                                    <router-link tag='a' target='_blank' :to='{path:"ascrisprOfftargets",
                                                query:{
                                                inputType: scope.row.inputType,
                                                spacerSeq_original: scope.row.spacerSeq_original,
                                                PAM_original: scope.row.PAM_original,
                                                spacerSeq: scope.row.spacerSeq,
                                                PAMSeq: scope.row.PAMSeq,
                                                Crispr_Type: scope.row.Crispr_Type,
                                                crispr_system: scope.row.crispr_system,
                                                Targeting_Strand: scope.row.Targeting_Strand,
                                                Direction_link: scope.row.Direction_link,
                                                N1: scope.row.N1,
                                                N2: scope.row.N2,
                                                spacerStart: scope.row.spacerStart,
                                                spacerEnd: scope.row.spacerEnd,
                                                PAMStart: scope.row.PAMStart,
                                                PAMEnd: scope.row.PAMEnd,
                                                MUTposStart: scope.row.MUTposStart,
                                                MUTposEnd: scope.row.MUTposEnd,
                                                PAM_IUB: scope.row.PAM_IUB,
                                                seq_name_hide: scope.row.seq_name_hide,
                                                seqId: scope.row.seqId,
                                                guideId: scope.row.guideId,
                                                time_stamp: scope.row.time_stamp}}'>{{scope.row.offtargetCount}}</router-link>
                                    <!--<a :href=''http://www.ncbi.nlm.nih.gov/pubmed/'+ scope.row.Enzyme_Information' target='_blank' style='text-decoration:underline;'><span v-html='scope.row.Enzyme_Information'></span></a>-->
                                </template>
                            </el-table-column>
                            <el-table-column prop='Enzyme_Information' label='Enzyme Information' :render-header='renderHeader' :labelClassName='EnzymeInformation_tip'  sortable>
                                <template slot-scope='scope'>
                                    <router-link tag='a' target='_blank' :to='{path:"ascrisprEnzymesInfoMatched",
                                                query:{
                                                spacerSeq_original: scope.row.spacerSeq_original,
                                                PAM_original: scope.row.PAM_original,
                                                spacerSeq: scope.row.spacerSeq,
                                                PAMSeq: scope.row.PAMSeq,
                                                Crispr_Type: scope.row.Crispr_Type,
                                                crispr_system: scope.row.crispr_system,
                                                Targeting_Strand: scope.row.Targeting_Strand,
                                                Direction_link: scope.row.Direction_link,
                                                N1: scope.row.N1,
                                                N2: scope.row.N2,
                                                spacerStart: scope.row.spacerStart,
                                                spacerEnd: scope.row.spacerEnd,
                                                PAMStart: scope.row.PAMStart,
                                                PAMEnd: scope.row.PAMEnd,
                                                MUTposStart: scope.row.MUTposStart,
                                                MUTposEnd: scope.row.MUTposEnd,
                                                PAM_IUB: scope.row.PAM_IUB,
                                                seq_name_hide: scope.row.seq_name_hide,
                                                seqId: scope.row.seqId,
                                                guideId: scope.row.guideId,
                                                time_stamp: scope.row.time_stamp}}'>{{scope.row.Enzyme_Information}}</router-link>
                                    <!--<a :href=''http://www.ncbi.nlm.nih.gov/pubmed/'+ scope.row.Enzyme_Information' target='_blank' style='text-decoration:underline;'><span v-html='scope.row.Enzyme_Information'></span></a>-->
                                </template>
                            </el-table-column>
                        </el-table>

                        <el-row class='page'>
                            <el-col :span='22'>
                                <el-pagination

                                        background
                                        @current-change='paginationSequence'
                                        @size-change='sizeChangeSequence'
                                        :current-page.sync='current_pageSequence'
                                        :page-sizes='[10,20,25,50]'
                                        layout='total,sizes,prev, pager, next'
                                        :page-size.sync='pageSizeSequence'
                                        :total='totalSequence'>
                                </el-pagination>
                            </el-col>
                        </el-row>
                    </div>
                </el-card>

            </el-col>
        </el-row>
    </div>
</template>

<style>

    .buttons{position:fixed;z-index:100;top:62%;left:0px;padding:15px 2px;border-radius:10px;background-color:#7a7a7a;background-color:#fff}

    .collapse_title_bg_color_Success{ background:#67C23A; color:#FFF}
    .collapse_title_bg_color_Warning{ background:#E6A23C; color:#FFF}
    .collapse_title_bg_color_Danger{ background:#F56C6C; color:#FFF}
    .collapse_title_bg_color_Info{ background:#909399; color:#FFF}
    .collapse_title_bg_color_Blue{ background:#409EFF; color:#FFF}

    .el-row {
        margin-bottom: 10px;
    &:last-child {
         margin-bottom: 200px;
     }
    }

    .el-table .cell {
        white-space: pre-line;
    }

    .demo-table-expand {
        font-size: 0;
    }
    .demo-table-expand label {
        width: 90px;
        color: #99a9bf;
    }
    .demo-table-expand .el-form-item {
        margin-right: 0;
        margin-bottom: 0;
        width: 100%;
    }

    path {
        stroke: steelblue;
        stroke-width: 1;
        fill: none;
    }

    .axis {
        shape-rendering: crispEdges;
    }

    .x.axis line {
        stroke: lightgrey;
    }

    .x.axis .minor {
        stroke-opacity: .5;
    }

    .x.axis path {
        display: none;
    }

    .y.axis line, .y.axis path {
        fill: none;
        stroke: #000;
    }

</style>

<script type='text/babel'>

  import * as d3 from 'd3'
  import Vue from 'vue'
  import FileSaver from 'file-saver'
  import XLSX from 'xlsx'
  import {
    getInfoSequence,
    showInfoSequence,
    getSequences,
    plotSequences
  } from '@/api/ascrispr'

  import DownloadXls from '@/views/components/DownloadXls';
  import {Tools} from '@/views/utils/Tools';

  const _import = require('../../router/_import_' + process.env.NODE_ENV)

  export default {
    components: {
      DownloadXls
    },
    data() {

      let validateInputValueDBSNP = (rule, value, callback) => {
        if (value === '') {
          callback(new Error('The input is empty！'))
        } else {
          if (value !== '') {
          }
          callback()
        }
      }

      let validateInputValueSeq = (rule, value, callback) => {
        if (value === '') {
          callback(new Error('The input is empty！'))
        } else {
          if (value !== '') {
            let minLenSeq = 62
            let minLenLeftSeq = 31
            let minLenRightSeq = 31
            let minLenVariants = 5
            let minLenLeftRightVariants = 1

            if (value.indexOf('/') === -1 || value.indexOf('[') === -1 || value.indexOf(']') === -1) {
              callback(new Error('The format of the variants parts is invalid!'))
            }
            let leftVariantsLen = value.indexOf('/') - value.indexOf('[') - 1
            let rightVariantsLen = value.indexOf(']') - value.indexOf('/') - 1

            if (leftVariantsLen < minLenLeftRightVariants || rightVariantsLen < minLenLeftRightVariants) {
              callback(new Error('The format of the variants parts is invalid!'))
            }

            let sequenceLen = value.length
            let variantsLen = value.indexOf(']') - value.indexOf('[') + 1

            if (variantsLen < minLenVariants) {
              callback(new Error('The minimu length of the variants parts is '.concat(minLenVariants).concat('bp!')))
            }

            if (sequenceLen - variantsLen < minLenSeq) {
              callback(new Error('The minimu length of the input sequence is '.concat(minLenSeq).concat('bp (not inclued the variants parts)!')))
            }

            if (value.indexOf('[') < minLenLeftSeq) {
              callback(new Error('The minimu length of the left parts of the input sequence is '.concat(minLenLeftSeq).concat('bp!')))
            }

            if (sequenceLen - variantsLen - value.indexOf('[') < minLenRightSeq) {
              callback(new Error('The minimu length of the right parts of the input sequence is '.concat(minLenRightSeq).concat('bp!')))
            }
          }
          callback()
        }
      }

      return {
        offtarget_checked: false,
        isDisabled_hg19: false,
        isDisabled_hg38: false,
        isDisabled_mm10: false,
        crispr_type_label: 'Cas9 Type',
        crispr_type_tip: 'Cas9 & Variants',
        width: 500,
        height: 600,
        id: '',
        seqeneces_num: 0,
        multipleSelection: [],
        isShowColumn: false,
        intervalid: '',
        loading: '',
        Targeting_Strand: '',
        Ranking_tip: 'Ranking',
        SpacerSequence_tip: 'Spacer Sequence',
        PAM_tip: 'Protospacer Adjacent Motif',
        // Cas9Type_tip: 'Cas9 & Variants',
        // Cpf1Type_tip: 'Cpf1 & Variants',
        DeepCpf1_tip: 'Predicted on-target efficiency of Cpf1, according to Kim et al., 2018. Range: 0-100. The higher the score, the more likely is cleavage of genome DNA.',

        Direction_tip: 'The direction of guide sequence targeting on the user input strand (+) or the complementary reverse strand (-)',
        GeneLocus_tip: 'The targeting site within the genome.',
        GCcontent_tip: 'GC content of candidate sequence excluding PAM. The range from 20%-80% is recommended.',
        Specificityscore_tip: 'Specificity score measures the uniqueness of a guide sequence in the genome. Range: 0-1. The higher the specificity score, the lower are off-target effects.',
        Xu2015score_tip: 'Predicted on-target efficiency of SpCas9, according to Xu et al., 2015. Range: 0-1. The higher the score, the more likely is cleavage of genome DNA.',
        Doench2016score_tip: 'Predicted on-target efficiency of SpCas9, according to Doench et al., 2016. Range: 0-1. The higher the score, the more likely is cleavage of genome DNA.',
        MorenoMateos2015score_tip: 'Predicted on-target efficiency of SpCas9, according to Moreno-Mateos et al., 2015. Range: 0-1. The higher the score, the more likely is cleavage of genome DNA.',
        AzimuthInVitroScore_tip: 'Predicted on-target efficiency of SpCas9, according to Listgarden et al., 2018. Range: 0-1. The higher the score, the more likely is cleavage of genome DNA.',
        Najm2018score_tip: 'Predicted on-target efficiency of SaCas9, according to Najm et al., 2018. Range: 0-1. The higher the score, the more likely is cleavage of genome DNA.',
        SelfComplementarity_tip: 'Number of self-commentary 4 nt stems, according to Thyme et al., 2016. More is worse.',
        Offtargets_tip: 'Summary of mismatches throughout the whole genome.',
        EnzymeInformation_tip: 'Restriction enzyme sites which might be disrupted after gene targeting.',
        TargetingStrand_tip: 'Wildtype Strand or Varied Strand',
        formData: [],

        showResult: false,
        showNoResult: false,
        showResult_rs: false,
        showCas9Result: false,
        showCpf1Result: false,

        show_sequence_type: true,
        show_dbSNP_type: false,
        show_cas9_system_types: true,
        show_cpf1_system_types: false,
        show_cas12b_system_types: false,
        show_casx_system_types: false,

        seedLength: 12,
        seedLengthMax: 12,
        activeNames: ['1', '2'],

        tableDataInputDBSNP: [],
        loadingInputDBSNP: false,

        current_pageSequence: 1,
        pageSizeSequence: 10,
        totalSequence: 0,
        tableDataSequence: [],
        tableDataSequenceForExportExcel: [],
        loadingSequence: false,

        ruleForm: {
          time_stamp: '',
          radioInputType: 'sequence',
          inputValue_sequence: '',
          inputValue_dbSNP: '',
          genome: 'hg19',
          radioType: 'cas9',
          cas9_system_types: ['SpCas9:NGG'],
          cpf1_system_types: ['As\\(Lb\\)Cpf1:TTTV'],
          cas12b_system_types: ['AaCas12b:TTN'],
          casx_system_types: ['Dpb\\(Plm\\)CasX:TTCN']
        },
        rules: {
          inputValue_sequence: [
            { required: true, validator: validateInputValueSeq, trigger: 'change' }
          ],
          inputValue_dbSNP: [
            { required: true, validator: validateInputValueDBSNP, trigger: 'change' }
          ],
          cas9_system_types: [
            { required: true, message: 'please select Cas9 types！', trigger: 'change' }
          ],
          cpf1_system_types: [
            { required: true, message: 'please select Cpf1 types！', trigger: 'change' }
          ],
          cas12b_system_types: [
            { required: true, message: 'please select Cas12b types！', trigger: 'change' }
          ],
          casx_system_types: [
            { required: true, message: 'please select CasX types！', trigger: 'change' }
          ]
        }
      }
    },
    methods: {
      offtarget_checked_change(){
        if (this.offtarget_checked){
          this.$confirm('If you select this option: You will get the off-target information. It will also consume more time cost. Continue?', 'Off-target option', {
            confirmButtonText: 'Confirm',
            cancelButtonText: 'Cancel',
            type: 'warning'
          }).then(() => {
            this.$message({
              type: 'success',
              message: 'Confirmed!'
            });
            this.offtarget_checked = true
          }).catch(() => {
            this.$message({
              type: 'info',
              message: 'Canceled'
            });
            this.offtarget_checked = false
          });
          // this.$alert('If you select this option: You will get the off-target information. It will also consume more time cost. ', 'Off-target option', {
          //   confirmButtonText: 'OK',
          // });
        }
      },
      inputChanged() {
        this.isDisabled_hg38 = false
        this.isDisabled_hg19 = false
        this.isDisabled_mm10 = false
      },
      sorttable() {
        let arr = []
        arr.push({
          'ranking': 1,
          'input_seq': 'input_seq',
          'spacerSeq': 'spacerSeq',
          'PAMSeq': 'bPAMSeq',
          'Crispr_Type': 'bCrispr_Type',
          'crispr_system': 'crispr_system',
          'Targeting_Strand': 'Targeting_Strand',
          'Direction': 'Direction',
          'targetGenomeGeneLocus': 'targetGenomeGeneLocus',
          'GC_content': 'GC_content',
          'mitSpecScore': 'mitSpecScore',
          'Xu_Score': 'Xu_Score',
          'Doench16_Score': 'Doench16_Score',
          'Moreno_Mateos_Score': 'Moreno_Mateos_Score',
          'Azimuth_in_vitro_Score': 'Azimuth_in_vitro_Score',
          'Najm2018': 'Najm2018',
          'DeepCpf1': 'DeepCpf1',
          'Self_complementarity': 'Self_complementarity',
          'offtargetCount': 'offtargetCount',
          'Enzyme_Information': 'Enzyme_Information',
          'spacerSeq_original': 'spacerSeq_original',
          'PAM_original': 'PAM_original',
          'Direction_link': 'Direction_link',
          'N1': 'N1',
          'N2': 'N2',
          'spacerStart': 'spacerStart',
          'spacerEnd': 'spacerEnd',
          'PAMStart': 'PAMStart',
          'PAMEnd': 'PAMEnd',
          'MUTposStart': 'MUTposStart',
          'MUTposEnd': 'MUTposEnd',
          'PAM_IUB': 'PAM_IUB',
          'seq_name_hide': 'seq_name_hide',
          'time_stamp': '123',
          'input_types': 'input_types'
        })
        arr.push({
          'ranking': 2,
          'input_seq': 'input_seq',
          'spacerSeq': 'spacerSeq',
          'PAMSeq': 'aPAMSeq',
          'Crispr_Type': 'bCrispr_Type',
          'crispr_system': 'crispr_system',
          'Targeting_Strand': 'Targeting_Strand',
          'Direction': 'Direction',
          'targetGenomeGeneLocus': 'targetGenomeGeneLocus',
          'GC_content': 'GC_content',
          'mitSpecScore': 'mitSpecScore',
          'Xu_Score': 'Xu_Score',
          'Doench16_Score': 'Doench16_Score',
          'Moreno_Mateos_Score': 'Moreno_Mateos_Score',
          'Azimuth_in_vitro_Score': 'Azimuth_in_vitro_Score',
          'Najm2018': 'Najm2018',
          'DeepCpf1': 'DeepCpf1',
          'Self_complementarity': 'Self_complementarity',
          'offtargetCount': 'offtargetCount',
          'Enzyme_Information': 'Enzyme_Information',
          'spacerSeq_original': 'spacerSeq_original',
          'PAM_original': 'PAM_original',
          'Direction_link': 'Direction_link',
          'N1': 'N1',
          'N2': 'N2',
          'spacerStart': 'spacerStart',
          'spacerEnd': 'spacerEnd',
          'PAMStart': 'PAMStart',
          'PAMEnd': 'PAMEnd',
          'MUTposStart': 'MUTposStart',
          'MUTposEnd': 'MUTposEnd',
          'PAM_IUB': 'PAM_IUB',
          'seq_name_hide': 'seq_name_hide',
          'time_stamp': '123',
          'input_types': 'input_types'
        })
        arr.push({
          'ranking': 3,
          'input_seq': 'input_seq',
          'spacerSeq': 'spacerSeq',
          'PAMSeq': 'bPAMSeq',
          'Crispr_Type': 'aCrispr_Type',
          'crispr_system': 'crispr_system',
          'Targeting_Strand': 'Targeting_Strand',
          'Direction': 'Direction',
          'targetGenomeGeneLocus': 'targetGenomeGeneLocus',
          'GC_content': 'GC_content',
          'mitSpecScore': 'mitSpecScore',
          'Xu_Score': 'Xu_Score',
          'Doench16_Score': 'Doench16_Score',
          'Moreno_Mateos_Score': 'Moreno_Mateos_Score',
          'Azimuth_in_vitro_Score': 'Azimuth_in_vitro_Score',
          'Najm2018': 'Najm2018',
          'DeepCpf1': 'DeepCpf1',
          'Self_complementarity': 'Self_complementarity',
          'offtargetCount': 'offtargetCount',
          'Enzyme_Information': 'Enzyme_Information',
          'spacerSeq_original': 'spacerSeq_original',
          'PAM_original': 'PAM_original',
          'Direction_link': 'Direction_link',
          'N1': 'N1',
          'N2': 'N2',
          'spacerStart': 'spacerStart',
          'spacerEnd': 'spacerEnd',
          'PAMStart': 'PAMStart',
          'PAMEnd': 'PAMEnd',
          'MUTposStart': 'MUTposStart',
          'MUTposEnd': 'MUTposEnd',
          'PAM_IUB': 'PAM_IUB',
          'seq_name_hide': 'seq_name_hide',
          'time_stamp': '123',
          'input_types': 'input_types'
        })
        arr.push({
          'ranking': 4,
          'input_seq': 'input_seq',
          'spacerSeq': 'spacerSeq',
          'PAMSeq': 'cPAMSeq',
          'Crispr_Type': 'bCrispr_Type',
          'crispr_system': 'crispr_system',
          'Targeting_Strand': 'Targeting_Strand',
          'Direction': 'Direction',
          'targetGenomeGeneLocus': 'targetGenomeGeneLocus',
          'GC_content': 'GC_content',
          'mitSpecScore': 'mitSpecScore',
          'Xu_Score': 'Xu_Score',
          'Doench16_Score': 'Doench16_Score',
          'Moreno_Mateos_Score': 'Moreno_Mateos_Score',
          'Azimuth_in_vitro_Score': 'Azimuth_in_vitro_Score',
          'Najm2018': 'Najm2018',
          'DeepCpf1': 'DeepCpf1',
          'Self_complementarity': 'Self_complementarity',
          'offtargetCount': 'offtargetCount',
          'Enzyme_Information': 'Enzyme_Information',
          'spacerSeq_original': 'spacerSeq_original',
          'PAM_original': 'PAM_original',
          'Direction_link': 'Direction_link',
          'N1': 'N1',
          'N2': 'N2',
          'spacerStart': 'spacerStart',
          'spacerEnd': 'spacerEnd',
          'PAMStart': 'PAMStart',
          'PAMEnd': 'PAMEnd',
          'MUTposStart': 'MUTposStart',
          'MUTposEnd': 'MUTposEnd',
          'PAM_IUB': 'PAM_IUB',
          'seq_name_hide': 'seq_name_hide',
          'time_stamp': '123',
          'input_types': 'input_types'
        })

        console.log(arr.sort(function (a, b) {
          var a1 = a.Crispr_Type
          var b1 = b.Crispr_Type
          var a2 = a.PAMSeq
          var b2 = b.PAMSeq
          return (a1 < b1) ? -1 : (a1 > b1) ? 1 : ( (a2 < b2 ) ? -1 : (a2 > b2 ) ? 1 : 0 );
        }))


      },

      exportExcel() {
        let wb = XLSX.utils.book_new()
        for (let i = 0; i < this.tableDataSequenceForExportExcel.length; i++) {
          let ws = XLSX.utils.json_to_sheet(this.tableDataSequenceForExportExcel[i])
          XLSX.utils.book_append_sheet(wb, ws, "output" + i)
        }
        XLSX.writeFile(wb, "ascrispr_results.xlsx")
      },
      goLink: function(data) {
        console.log(data)
        this.$router.push({ path: '/search', query: { inputValue: data, searchType: data }})
      },

      handleSelectionChange(val) {
        this.showResult = false
        this.showNoResult = false
        this.multipleSelection = val
        console.log(this.multipleSelection)
      },

      submitGetSequences(formName) {
        this.$refs[formName].validate((valid) => {
          if (valid) {
            this.ruleForm.time_stamp = new Date().getTime()
            let formData = {
              time_stamp: this.ruleForm.time_stamp,
              rsID: this.ruleForm.inputValue_dbSNP,
              genome: this.ruleForm.genome
            }
            // this.plotSequencesInner()
            getSequences(formData)
              .then(response => {
                this.showResult_rs = true
                let result = response
                this.tableDataInputDBSNP = result
                this.seqeneces_num = result.length
                console.log('getSequence ok')
                console.log(result)
                console.log(result.length)
                console.log(111)

                plotSequences(this.ruleForm.time_stamp)
                  .then(response => {
                    let result = response
                    console.log('plotSequences ok')
                    console.log(result)
                    let arr = []
                    let rs_color = 'red'
                    let rs_y = 90
                    let j = 0
                    for (let i = 0; i < result.length; i++) {
                      for (let key in result[i]) {
                        if (j % 3 === 0) {
                          rs_color = 'red'
                          rs_y = 75
                        } else if (j % 3 === 1) {
                          rs_color = 'green'
                          rs_y = 90
                        } else {
                          rs_color = 'black'
                          rs_y = 105
                        }
                        // arr.push({
                        //   'rs_id': result[i][key]['rs_id'],
                        //   'start': result[i][key]['start'],
                        //   'end': result[i][key]['end'],
                        //   'color': rs_color,
                        //   'ref': result[i][key]['ref'],
                        //   'alt': result[i][key]['alt'],
                        //   'chr': result[i][key]['chr']
                        // })
                        arr.push([result[i][key]['rs_id'],
                          result[i][key]['start'],
                          result[i][key]['end'],
                          rs_color,
                          result[i][key]['ref'],
                          result[i][key]['alt'],
                          result[i][key]['chr'],
                          rs_y])
                        j = j + 1
                      }
                      break
                    }
                    console.log(arr)
                    this.plotSequencesInner(arr)
                  })
              })
              .catch(() => {
                this.$message.error('no Opps! There is an error!')
                console.log(222)
              })
          }
        })
      },
      plotSequencesInner(data) {
        let test_string = 'GGGAGTGTCCTCTCAACTGCTCTGTCGCT[C/G]TGTCTCTAACGACCACATGGGGGAAAGGT'
        let input_seq = this.tableDataInputDBSNP[0]['input_seq']
        let input_seq_org = this.tableDataInputDBSNP[0]['input_seq_org']
        let chr = this.tableDataInputDBSNP[0]['chr']
        let start = this.tableDataInputDBSNP[0]['start']
        let end = parseInt(this.tableDataInputDBSNP[0]['end'])
        let ref = this.tableDataInputDBSNP[0]['ref']
        let alt = this.tableDataInputDBSNP[0]['alt']
        let rs_id = this.tableDataInputDBSNP[0]['rs_id']

        // let input_seq = 'GGGAGTGTCCTCTCAACTGCTCTGTCGCT[C/G]TGTCTCTAACGACCACATGGGGGAAAGGT'
        // let input_seq_org = 'GGGAGTGTCCTCTCAACTGCTCTGTCGCTCTGTCTCTAACGACCACATGGGGGAAAGGT'
        // let chr = 'X'
        // let start = 153295455
        // let end = 153295455
        // let ref = 'C'
        // let alt = 'G'
        // let rs_id = 'rs62621675'

        console.log(input_seq)
        console.log(input_seq_org)
        console.log(chr)
        console.log(start)
        console.log(end)
        console.log(ref)
        console.log(alt)

        let seq_start = start - 32
        let seq_end = end + 31

        let sequence_info = [
          {
            'seq_length': seq_start,
            'name': rs_id,
            'info':
              [
                [chr, seq_start, seq_end, ref, alt]
              ],
            'seq': input_seq_org
          }
        ]

        let margin = {
          top: 20,
          bottom: 20,
          left: 0,
          right: 15
        }
        let w = 1200 - margin.left - margin.right

        let rsList = [
          ['rs_0000001', 153295426, 6, 'green'],
          ['rs_0000002', 153295437, 6, 'red'],
          ['rs_0000003', 153295439, 6, 'green'],
          ['rs_0000004', 1532954411, 'red'],
          ['rs_0000005', 153295443, 5, 'green'],
          ['rs_0000006', 153295445, 6, 'red'],
          ['rs_0000007', 153295447, 6, 'green'],
          ['rs_0000008', 153295449, 6, 'red'],
          ['rs_0000009', 153295451, 6, 'green'],
          ['rs_00000010', 153295454, 6, 'red']
        ]
        rsList = data

        let h = 200 //10 + 15 + viscoords.length * genePx + primer_tiers * tierPx + resSite_tiers * tierPx - margin.top - margin.bottom; //fix me

        // chr7_75933294_75933353.fa
        // let genemin = d3.min(viscoords, (d) => {
        //   return d3.min(d["exons"], (di) => di[1])
        // })
        // let genemax = d3.max(viscoords, (d) => {
        //   return d3.max(d["exons"], (di) => di[2])
        // })
        let genemin = seq_start
        let genemax = seq_end

        console.log(genemin)
        console.log(genemax)

        // let strand = sequence_info[0]["exons"][0][5]
        // console.log(strand)

        let xScale = d3.scaleLinear().domain([genemin+1, genemax+1]).range([0, w])
        let xScale2 = d3.scaleLinear().domain([genemin+1, genemax+1]).range([0, w])
        let xAxis = d3.axisBottom(xScale).ticks(10)
        let xAxisGrid = d3.axisBottom(xScale).ticks(10).tickSize(-h, 0, 0).tickFormat('')
        let sExtM = w / Math.abs(xScale(100) - xScale(1))
        let zoom = d3.zoom().scaleExtent([1, sExtM]).on('zoom', zoomed)

        // d3.select('#chart').selectAll('svg').remove()
        d3.select('#chart').select("svg").remove()
        let svgCh = d3.select('#chart')
          .append('svg:svg')
          .attr("width", w + margin.left + margin.right)
          .attr("height", h + margin.top + margin.bottom) // 画刻度
          .call(zoom)

        let svg = svgCh
          .append('svg:g')
          .attr('transform', 'translate(' + margin.left + ',' + margin.top + ')')

        let expLab = d3.select('div.container-fluid')
          .append('div')
          .attr('class', 'expLab')

        let gX = svg.append('svg:g')
          .attr('class', 'x axis')
          .attr('transform', 'translate(0, ' + h + ')')
          .call(xAxis)

        let gXgrid = svg.append('g')
          .attr('class', 'x grid')
          .attr('transform', 'translate(0,' + h + ')')
          .call(xAxisGrid)

        let focus = svg.append('g')
          .attr('class', 'focus')
          .style('display', 'none')

        let chartBody = svg.append('g')
          .attr('clip-path', 'url(#clip)')

        // 画基因
        let sequence = chartBody.selectAll('sequence')
          .data(sequence_info)
          .enter()
          .append('g')

        let info = sequence.selectAll('info')
          .data((d, i) => {
            d.info.map((x) => x.push(i))
            // alert(d.info)
            return d.info
          })
          .enter()
          .append('line')
          // .attr('x1', (d) => xScale(d[1]))
          // .attr('x2', (d) => xScale(d[2]))
          .attr('y1', (d) => 165)
          .attr('y2', (d) => 165)
          .attr('stroke', 'Navy')
          .attr('stroke-width', 10)

        let seq_length = sequence.selectAll('info2')
          .data(sequence_info)
          .enter()
          .append('line')
          .attr('class', 'seq_length')
          // .attr('x1', (d) => xScale(d.loc + 1000))
          // .attr('x2', (d) => xScale(d.loc + 1000))
          // 153295447
          // .attr('x1', (d) => xScale(153395447+29+29))
          // .attr('x2', (d) => xScale(153395447+29+29))
          .attr('y1', 165)
          .attr('y2', 165)
          .attr('stroke', 'red')
          .attr('stroke-width', 20)
          .on('mouseover', function(d) {
            focus.style('display', null)
              .select('.zoombox')
              .attr('x1', xScale(d['seq_length']))
              .attr('x2', xScale(seq_end))
            let output = '<b>' + d['name'] + '[' + ref + '>' + alt + ']</b>'
            expLab.style('top', ((h - margin.top) / 2 - 100) + 'px')
              .style('left', xScale(d['seq_length'] + 1) + 'px')
              .style('display', 'block')
              .style('font-family', 'arial')
              .style('font-size', '15px')
              .html(output.substring(0, output.length))
          })
          .on('mouseout', function(d) {
            // focus.style('display', 'none')
            // d3.selectAll('.restrictionSite_' + d[0]).attr('stroke-width', 10)
            expLab.style('display', 'none')
          })

        // 画序列
        let r = []
        let i = 1
        let _ref = sequence_info[0]['seq']
        let letter = ''

        for (let _j = 0, _len1 = _ref.length; _j < _len1; _j++) {
          letter = _ref[_j]
          r.push([+seq_start + i, letter])
          i++
        }
        console.log(r)
        let coding = chartBody.selectAll('codingtext')
          .data(r)
          .enter()
          .append('text')
          .attr('class', 'codingtext')
          .text((d) => d[1])
          .attr('x', (d) => xScale(d[0]))
          .attr('y', 150)
          .attr('font-family', 'arial')
          .attr('font-size', '12px')
          .attr('fill', 'black')

        // let expLab = d3.select('div.container-fluid')
        //   .append('div')
        //   .attr('class', 'expLab')

        // rs_id
        var rs_ids = chartBody.selectAll('rs_ids')
          .data(rsList)
          .enter()
          .append('line')
          .attr('class', 'rs_ids')
          .attr('class', (d) => 'rs_ids rs_ids_' + d[0])
          .attr('id', (d) => 'rs_ids_' + d[0] + ':' + d[1])
          .attr('x1', (d) => xScale(d[1]))
          .attr('x2', (d) => xScale(parseInt(d[2])))
          .attr('y1', (d) => d[7])
          .attr('y2', (d) => d[7])
          .attr('stroke-width', 10)
          .attr('height', 10)
          .attr('stroke', (d) => d[3])
          .on('mouseover', function(d) {
            d3.selectAll('.restrictionSite_' + d[0]).attr('stroke-width', 15)
            focus.style('display', null)
              .select('.zoombox')
              .attr('x1', xScale(d[1]))
              .attr('x2', xScale(parseInt(d[2])))
            let output = '<b>' + d[0] + ':[' + d[4] + '>' + d[5] + ']</b>'
            expLab.style('top', ((h - margin.top) / 2 - 10) + 'px')
              .style('left', xScale(d[1]) + 'px')
              .style('display', 'block')
              .style('font-family', 'arial')
              .style('font-size', '15px')
              .html(output.substring(0, output.length))
          })
          .on('mouseout', function(d) {
            // focus.style('display', 'none')
            d3.selectAll('.restrictionSite_' + d[0]).attr('stroke-width', 10)
            expLab.style('display', 'none')
          })

        // LEGEND
        chartBody.append('rect').attr('x', 10).attr('y', 0).attr('stroke', 'Black').attr('stroke-width', 0.5).attr('fill', 'white').attr('width', 105).attr('height', 42)
        chartBody.append('text').text("5' -->  3'").attr('x', 40).attr('y', 13).attr('font-family', 'arial').attr('font-size', '11px').attr('fill', 'black')
        chartBody.append('line').attr('x1', 15).attr('x2', 45).attr('y1', 30).attr('y2', 30).attr('stroke-width', 10).attr('stroke', 'Navy')
        chartBody.append('text').text('Sequence').attr('x', 50).attr('y', 30).attr('font-family', 'arial').attr('font-size', '10px').attr('fill', 'black')

        function getBB(selection) {
          selection.each(function(d) { d.bbox = this.getBBox(); })
        }

        // 基因的名字
        var isoNames = sequence
          .append('text')
          .attr('x', (d, i) => 8)
          .attr('y', (d, i) => i * 25 + 80)
          .attr('font-size', '15px').attr('fill', 'black')
          .text((d) => d.name + '[' + ref + '>' + alt + ']')
          .call(getBB)

        function zoomed() {
          xScale.domain(d3.event.transform.rescaleX(xScale2).domain())
          gX.call(xAxis)
          gXgrid.call(xAxisGrid)

          info.attr('x1', xScale(seq_start+1)).attr('x2', xScale(seq_end+1))
          seq_length.attr('x1', (d) => xScale(start - 0.1)).attr('x2', (d) => xScale(parseInt(end) + 0.5))
          coding.attr('x', (d) => xScale(d[0]))

          rs_ids.attr('x1', (d) => xScale(d[1] - 0.1)).attr('x2', (d) => xScale(parseInt(d[2]) + 0.5))
          expLab.style('display', 'none')
        }

        // AUTO-ZOOM TO REGION
        svgCh.transition().duration(1000).call(zoom.translateBy, 5, 0).on('end', () => {
          svgCh.transition().duration(1000).call(zoom.scaleBy, w)
        })
      },

      complement(s) {
        return { A: 'T', T: 'A', G: 'C', C: 'G' } [s]
      },

      revComp(s) {
        return s.split('').reverse().map(this.complement()).join('')
      },

      renderHeader(h, { column, $index }) {
        return [
          column.label,
          h(
            'el-tooltip',
            {
              props: {
                content: column.labelClassName,
                placement: 'top'
              }
            },
            [
              h('span', {
                class: {
                  'el-icon-question': true
                },
                style: 'color:#409eff;margin-left:5px;'
              })
            ]
          )
        ]
      },
      renderHeader2(h, { column, $index }) {
        if (column.labelClassName) {
          return (
            // <i className='el-icon-question'></i>
            // <el-tooltip className='tooltip' effect='dark' content={column.labelClassName} placement='bottom'>
            //     <span>{column.label}</span>
            // </el-tooltip>
            <el-tooltip className='tooltip' effect='dark' placement='bottom' content={column.labelClassName}>
              <span>{column.label}</span>
                <i className='el-icon-question'></i>
            </el-tooltip>
          )
        }
      },

      fetchFormData() {
        let table_seq_str = JSON.stringify(this.multipleSelection)
        let formData = {
          time_stamp: this.ruleForm.time_stamp,
          radioInputType: this.ruleForm.radioInputType,
          inputValue_sequence: this.ruleForm.inputValue_sequence,
          inputValue_dbSNP: this.ruleForm.inputValue_dbSNP,
          genome: this.ruleForm.genome,
          radioType: this.ruleForm.radioType,
          cas9_system_types: this.ruleForm.cas9_system_types,
          cpf1_system_types: this.ruleForm.cpf1_system_types,
          cas12b_system_types: this.ruleForm.cas12b_system_types,
          casx_system_types: this.ruleForm.casx_system_types,
          table_seq: table_seq_str,
          seedLength: this.seedLength,
          offtarget_checked: this.offtarget_checked
        }
        return formData
      },

      submitForm(formName) {
        console.log(this.multipleSelection)
        this.ruleForm.time_stamp = new Date().getTime()
        this.tableDataSequence = []
        this.$refs[formName].validate((valid) => {
          if (this.ruleForm.radioInputType === 'dbSNP') {
            if (this.multipleSelection.length === 0) {
              valid = false

              this.$message({
                message: 'Please select a sequence!',
                type: 'warning'
              })
              return false
            }
          }
          if (valid) {

            let time_consume = '5'
            if (this.offtarget_checked) {
              time_consume = '10'
            }
            let text_str = '<p>Your request was successfully submitted. It will be finished within ' + time_consume + ' minutes. Thank you for your interests in using AsCRISPR.</p>' + '<p>You can check your results by the following link for later: <br>http://www.genemed.tech/ascrispr/ascrisprByTimeStamp?time_stamp=' + this.ruleForm.time_stamp + '</p>'
            this.$alert(text_str, 'Info', {
              dangerouslyUseHTMLString: true
            }).then(respose => {
              this.loading = this.$loading({
                lock: true,
                text: 'Your task will be finished within ' + time_consume + ' minutes. Running...',
                spinner: 'el-icon-loading',
                background: 'rgba(0, 0, 0, 0.7)'
              })
            })

            this.showResult = true
            this.showNoResult = false
            this.showNoResult = false
            this.showCas9Result = false
            this.showCpf1Result = false

            this.loadingSequence = true
            this.formData = this.fetchFormData()
            console.log(this.formData)

            getInfoSequence(this.formData)
              .then(response => {
                let result = response
                console.log('123123 ok')
                console.log(result)
              })
              .catch(() => {
                this.$message.error('Opps！There is an error')
                console.log('error')
              })
            this.intervalid = setInterval(this.fetchDataSequence, 3000)
          }
        })
      },

      fetchDataSequence() {
        showInfoSequence(this.formData)
          .then(response => {
            let result = response
            console.log('showInfoSequence ok')
            console.log(result)
            console.log(result.length)

            if (result !== 'running') {
              this.showResult = true
              console.log(this.ruleForm.radioType)

              if (this.ruleForm.radioType === 'cas9') {
                this.showCas9Result = true
                this.showCpf1Result = false
              } else if (this.ruleForm.radioType === 'cpf1') {
                this.showCas9Result = false
                this.showCpf1Result = true
              } else {
                this.showCas9Result = false
                this.showCpf1Result = true
              }
              console.log('showInfoSequence test1')
              console.log(this.showResult)
              console.log(this.showNoResult)
              console.log(this.showCas9Result)
              console.log(this.showCpf1Result)

              console.log(this.show_cas9_system_types)
              console.log(this.show_cpf1_system_types)
              console.log(this.show_cas12b_system_types)
              console.log(this.show_casx_system_types)

              console.log('showInfoSequence test2')
              clearInterval(this.intervalid)
              setTimeout(() => {
                this.loading.close()
              }, 0)
              // this.tableDataSequence = result
              let x = 0
              for (let i = 0; i < result.length; i++) {
                let j_ranking = 1
                let arr = []
                let arrForExportExcel = []
                for (let key in result[i]) {
                  if (result[i][key]['input_seq'] !== ''){
                    arr.push({
                      'ranking': j_ranking,
                      'input_seq': result[i][key]['input_seq'],
                      'spacerSeq': result[i][key]['spacerSeq'],
                      'PAMSeq': result[i][key]['PAMSeq'],
                      'Crispr_Type': result[i][key]['Crispr_Type'],
                      'crispr_system': this.ruleForm.radioType,
                      'Targeting_Strand': result[i][key]['Targeting_Strand'],
                      'Direction': result[i][key]['Direction'],
                      'targetGenomeGeneLocus': result[i][key]['targetGenomeGeneLocus'],
                      'GC_content': result[i][key]['GC_content'],
                      'mitSpecScore': result[i][key]['mitSpecScore'],
                      'Xu_Score': result[i][key]['Xu_Score'],
                      'Doench16_Score': result[i][key]['Doench16_Score'],
                      'Moreno_Mateos_Score': result[i][key]['Moreno_Mateos_Score'],
                      'Azimuth_in_vitro_Score': result[i][key]['Azimuth_in_vitro_Score'],
                      'Najm2018': result[i][key]['Najm2018'],
                      'DeepCpf1': result[i][key]['DeepCpf1'],
                      'Self_complementarity': result[i][key]['Self_complementarity'],
                      'offtargetCount': result[i][key]['offtargetCount'],
                      'Enzyme_Information': result[i][key]['Enzyme_Information'],

                      'spacerSeq_original': result[i][key]['spacerSeq_original'],
                      'PAM_original': result[i][key]['PAM_original'],
                      'Direction_link': result[i][key]['Direction_link'],
                      'N1': result[i][key]['N1'],
                      'N2': result[i][key]['N2'],
                      'spacerStart': result[i][key]['spacerStart'],
                      'spacerEnd': result[i][key]['spacerEnd'],
                      'PAMStart': result[i][key]['PAMStart'],
                      'PAMEnd': result[i][key]['PAMEnd'],
                      'MUTposStart': result[i][key]['MUTposStart'],
                      'MUTposEnd': result[i][key]['MUTposEnd'],
                      'PAM_IUB': result[i][key]['PAM_IUB'],
                      'seq_name_hide': result[i][key]['seq_name_hide'],
                      'time_stamp': this.ruleForm.time_stamp,
                      'spacerSeq_TTTT_tip': result[i][key]['spacerSeq_TTTT_tip'],
                      'seqId': result[i][key]['seqId'],
                      'guideId': result[i][key]['guideId'],
                      'inputType': this.ruleForm.radioInputType

                    })

                    if (this.ruleForm.radioType === 'cas9') {
                      arrForExportExcel.push({
                        'Ranking': j_ranking,
                        'SpacerSequence': result[i][key]['spacerSeq'].replace(/<span style='color:red'>/g, '').replace(/<\/span>/g, ''),
                        'PAM': result[i][key]['PAMSeq'].replace(/<span style='color:red'>/g, '').replace(/<\/span>/g, ''),
                        'CrisprType': result[i][key]['Crispr_Type'],
                        'TargetingStrand': result[i][key]['Targeting_Strand'],
                        'Direction': result[i][key]['Direction'],
                        'GeneLocus': result[i][key]['targetGenomeGeneLocus'],
                        'GCcontent': result[i][key]['GC_content'],
                        'SpecificityScore': result[i][key]['mitSpecScore'],
                        'Xu2015Score': result[i][key]['Xu_Score'],
                        'Doench2016Score': result[i][key]['Doench16_Score'],
                        'MorenoMateos2015Score': result[i][key]['Moreno_Mateos_Score'],
                        'AzimuthInVitroScore': result[i][key]['Azimuth_in_vitro_Score'],
                        'Najm2018Score': result[i][key]['Najm2018'],
                        'Self-complementarity': result[i][key]['Self_complementarity'],
                        'Off-targets0-1-2-3': result[i][key]['offtargetCount'],
                        'EnzymeInformation': result[i][key]['Enzyme_Information']
                      })
                    } else {
                      arrForExportExcel.push({
                        'Ranking': j_ranking,
                        'SpacerSequence': result[i][key]['spacerSeq'].replace(/<span style='color:red'>/g, '').replace(/<\/span>/g, ''),
                        'PAM': result[i][key]['PAMSeq'].replace(/<span style='color:red'>/g, '').replace(/<\/span>/g, ''),
                        'Crispr_Type': result[i][key]['Crispr_Type'],
                        'TargetingStrand': result[i][key]['Targeting_Strand'],
                        'Direction': result[i][key]['Direction'],
                        'GeneLocus': result[i][key]['targetGenomeGeneLocus'],
                        'GCcontent': result[i][key]['GC_content'],
                        'DeepCpf1': result[i][key]['DeepCpf1'],
                        'Self-complementarity': result[i][key]['Self_complementarity'],
                        'Off-targets0-1-2-3': result[i][key]['offtargetCount'],
                        'EnzymeInformation': result[i][key]['Enzyme_Information']
                      })
                    }
                    j_ranking = j_ranking + 1
                  }
                }
                if (arr.length > 0) {
                  arr.sort(function (a, b) {
                    var a1 = a.Crispr_Type
                    var b1 = b.Crispr_Type
                    var a2 = a.PAMSeq
                    var b2 = b.PAMSeq
                    return (a1 < b1) ? -1 : (a1 > b1) ? 1 : ( (a2 < b2 ) ? -1 : (a2 > b2 ) ? 1 : 0 );
                  })
                  arrForExportExcel.sort(function (a, b) {
                    var a1 = a.Crispr_Type
                    var b1 = b.Crispr_Type
                    var a2 = a.PAMSeq
                    var b2 = b.PAMSeq
                    return (a1 < b1) ? -1 : (a1 > b1) ? 1 : ( (a2 < b2 ) ? -1 : (a2 > b2 ) ? 1 : 0 );
                  })
                  for (let k = 0; k < arr.length; k++) {
                    arr[k]['ranking'] = k + 1
                  }
                  for (let k = 0; k < arrForExportExcel.length; k++) {
                    arrForExportExcel[k]['Ranking'] = k + 1
                  }
                  this.tableDataSequence[x] = arr
                  this.tableDataSequenceForExportExcel[x] = arrForExportExcel
                  this.totalSequence = arr.length
                  x = x + 1
                }
              }
              console.log(this.tableDataSequence)

              this.loadingSequence = false
              console.log('clearInterval ok')

              if (this.tableDataSequence.length === 0) {
                this.tableDataSequence = []
                this.showNoResult = true
              }
            } else {
              console.log('The job is running!')
              this.loadingSequence = true
            }
          })
          .catch(() => {
            this.loadingSequence = false
            this.$message.error('Opps！There is an error')
            console.log('error')
          })
      },

      resetForm(formName) {
        this.ruleForm.radioInputType = 'sequence'
        this.show_sequence_type = true
        this.show_dbSNP_type = false
        this.ruleForm.radioType = 'cas9'
        this.ruleForm.genome = 'hg19'
        this.ruleForm.cas9_system_types = ['SpCas9:NGG']
        this.ruleForm.cpf1_system_types = ['As\\(Lb\\)Cpf1:TTTV']
        this.ruleForm.cas12b_system_types = ['AaCas12b:TTN']
        this.ruleForm.casx_system_types = ['Dpb\\(Plm\\)CasX:TTCN']
        this.show_cas9_system_types = true
        this.show_cpf1_system_types = false
        this.$refs[formName].resetFields()
        this.showResult = false
        this.showNoResult = false
        this.showResult_rs = false

        this.isDisabled_hg38 = false
        this.isDisabled_hg19 = false
        this.isDisabled_mm10 = false
      },

      // 分页功能
      paginationSequence(val) {
        this.current_pageSequence = val
        // this.fetchDataSequence()
      },
      sizeChangeSequence(val) {
        this.current_pageSequence = 1
        this.pageSizeSequence = val
        // this.fetchDataSequence()
      },

      clearFilter() {
        this.$refs.filterTable.clearFilter()
      },

      showRadioInputType() {
        this.showResult = false
        this.showResult_rs = false
        this.showCas9Result = false
        this.showCpf1Result = false
        // this.resetForm('ruleForm')
        if (this.ruleForm.radioInputType === 'sequence') {
          this.show_sequence_type = true
          this.show_dbSNP_type = false
          this.ruleForm.inputValue_dbSNP = ''
          this.isDisabled_mm10 = false

          this.showResult = false
          this.showNoResult = false
          this.tableDataInputDBSNP = []
          this.TargetingStrand_tip = 'Wildtype Strand or Varied Strand'
        } else {
          this.show_sequence_type = false
          this.show_dbSNP_type = true
          this.ruleForm.inputValue_sequence = ''
          this.isDisabled_mm10 = true

          this.showResult = false
          this.showNoResult = false
          this.TargetingStrand_tip = 'Reference Strand or Alternative Strand'
        }
      },
      unselectAllSubtypes() {
        if (this.ruleForm.radioType === 'cas9') {
          this.ruleForm.cas9_system_types = []
        } else if (this.ruleForm.radioType === 'cpf1') {
          this.ruleForm.cpf1_system_types = []
        } else if (this.ruleForm.radioType === 'cas12b') {
          this.ruleForm.cas12b_system_types = []
        } else if (this.ruleForm.radioType === 'casx') {
          this.ruleForm.casx_system_types = []
        }
      },
      selectAllSubtypes() {
        if (this.ruleForm.radioType === 'cas9') {
          this.ruleForm.cas9_system_types = ['SpCas9:NGG', 'SpCas9-VRER:NGCG', 'SpCas9-EQR:NGAG', 'SpCas9-V\\(R\\)QR:NGA', 'SaCas9:NNGRRT', 'SaCas9-KKH:NNNRRT', 'NmCas9:NNNNGMTT', 'St1Cas9:NNAGAAW', 'TdCas9:NAAAAC', 'CjCas9:NNNNRYAC', 'Nme2Cas9:NNNNAC', 'GeoCas9:NNNNCRAA', 'GeoCas9:NNNNGMAA','St3Cas9:NGGNG']
        } else if (this.ruleForm.radioType === 'cpf1') {
          this.ruleForm.cpf1_system_types = ['As\\(Lb\\)Cpf1:TTTV', 'FnCpf1:TTV', 'FnCpf1:KYTV', 'AsCpf1-RR:TYCV', 'AsCpf1-RVR:TATV']
        } else if (this.ruleForm.radioType === 'cas12b') {
          this.ruleForm.cas12b_system_types = ['AaCas12b:TTN', 'AkCas12b:TTTN', 'BhCas12b:ATTN']
        } else if (this.ruleForm.radioType === 'casx') {
          this.ruleForm.casx_system_types = ['Dpb\\(Plm\\)CasX:TTCN']
        }
      },
      showRadioType() {
        this.showResult = false
        this.showNoResult = false
        this.showCas9Result = false
        this.showCpf1Result = false

        let inputValue_sequence_tmp = this.ruleForm.inputValue_sequence
        let inputValue_dbSNP_tmp = this.ruleForm.inputValue_dbSNP
        this.$refs['ruleForm'].resetFields()
        this.ruleForm.inputValue_sequence = inputValue_sequence_tmp
        this.ruleForm.inputValue_dbSNP = inputValue_dbSNP_tmp

        if (this.ruleForm.radioType === 'cas9') {
          this.show_cas9_system_types = true
          this.show_cpf1_system_types = false
          this.show_cas12b_system_types = false
          this.show_casx_system_types = false

          this.crispr_type_label = 'Cas9 Type'
          this.crispr_type_tip = 'Cas9 & Variants'

          this.seedLength = 12
          this.seedLengthMax = 12

          this.ruleForm.cas9_system_types = ['SpCas9:NGG']
        } else if (this.ruleForm.radioType === 'cpf1') {
          this.show_cas9_system_types = false
          this.show_cpf1_system_types = true
          this.show_cas12b_system_types = false
          this.show_casx_system_types = false

          this.crispr_type_label = 'Cpf1 Type'
          this.crispr_type_tip = 'Cpf1 & Variants'

          this.seedLength = 6
          this.seedLengthMax = 6

          // this.rules.cas9_system_types.
          // this.ruleForm.cas9_system_types = ['SpCas9:NGG']
          this.ruleForm.cpf1_system_types = ['As\\(Lb\\)Cpf1:TTTV']
        } else if (this.ruleForm.radioType === 'cas12b') {
          this.show_cas9_system_types = false
          this.show_cpf1_system_types = false
          this.show_cas12b_system_types = true
          this.show_casx_system_types = false

          this.crispr_type_label = 'Cas12b Type'
          this.crispr_type_tip = 'Cas12b & Variants'

          this.seedLength = 6
          this.seedLengthMax = 6

          this.ruleForm.cas12b_system_types = ['AaCas12b:TTN']
        } else if (this.ruleForm.radioType === 'casx') {
          this.show_cas9_system_types = false
          this.show_cpf1_system_types = false
          this.show_cas12b_system_types = false
          this.show_casx_system_types = true

          this.crispr_type_label = 'CasX Type'
          this.crispr_type_tip = 'CasX & Variants'

          this.seedLength = 6
          this.seedLengthMax = 6

          this.ruleForm.casx_system_types = ['Dpb\\(Plm\\)CasX:TTCN']
        }
      },
      selectCpf1Change() {
        if (this.ruleForm.cpf1_system_types.indexOf('FnCpf1:TTV') !== -1 && this.ruleForm.cpf1_system_types.indexOf('FnCpf1:KYTV') !== -1 && this.ruleForm.cpf1_system_types.length === 2) {
          this.seedLength = 5
          this.seedLengthMax = 5
        } else if (this.ruleForm.cpf1_system_types.indexOf('FnCpf1:TTV') !== -1 && this.ruleForm.cpf1_system_types.length === 1) {
          this.seedLength = 5
          this.seedLengthMax = 5
        } else if (this.ruleForm.cpf1_system_types.indexOf('FnCpf1:KYTV') !== -1 && this.ruleForm.cpf1_system_types.length === 1) {
          this.seedLength = 5
          this.seedLengthMax = 5
        } else {
          this.seedLength = 6
          this.seedLengthMax = 6
        }
      },

      getExample1: function() {
        this.ruleForm.inputValue_sequence = 'CAGCAGCTGGTACGTGGATCGGGGCGGAAAC[G/A]GCTGTCTGATGGCCCCAGAGGTGAGTCCCGA'
        // this.isDisabled_hg38 = false
        // this.isDisabled_hg19 = false
        // this.isDisabled_mm10 = true

        this.ruleForm.genome = 'hg19'
      },
      getExample2: function() {
        this.ruleForm.inputValue_sequence = 'TTCTCCAATGCGACGGGTGTGGTACGCAGCC[C/A]CTTCGAGTACCCACAGTACTACCTGGCTGAG'
        // this.isDisabled_hg38 = false
        // this.isDisabled_hg19 = false
        // this.isDisabled_mm10 = true

        this.ruleForm.genome = 'hg19'
      },
      getExample3: function() {
        this.ruleForm.inputValue_sequence = 'CATCTGCCAGCGGCTCAGGAGCCCAGGTGGG[C/T]GGACCCATCTCCTCTGGCTCTTCTGCCTCCA'
        // this.isDisabled_hg38 = false
        // this.isDisabled_hg19 = false
        // this.isDisabled_mm10 = true

        this.ruleForm.genome = 'hg19'
      },
      getExample4: function() {
        this.ruleForm.inputValue_sequence = 'TGATGAAGACATTGTAAGCAGAGTGGCTGAG[GAG/-]ATGACATTTTTCCCCAAAGAGGAGAGAGTTT'
        // this.isDisabled_hg38 = false
        // this.isDisabled_hg19 = false
        // this.isDisabled_mm10 = true

        this.ruleForm.genome = 'hg19'
      },
      getExample5: function() {
        this.ruleForm.inputValue_sequence = 'TGTTCCCAGGGTGACCGAGGCTTTGACGGGC[AGCCAGGCCCCAAGGGT/GA]GACCAGGGCGAGAAAGGGGAGCGGGTGAGTT'
        // this.isDisabled_hg38 = false
        // this.isDisabled_hg19 = false
        // this.isDisabled_mm10 = true

        this.ruleForm.genome = 'hg19'
      },
      getExample6: function() {
        this.ruleForm.inputValue_sequence = 'ATGCTGGTAGCTGCCATCCAGTCTGCAGGAC[T/G]GACGGAGACCCTCAACCGGGAAGGAGTCTAC'
        // this.isDisabled_hg38 = false
        // this.isDisabled_hg19 = false
        // this.isDisabled_mm10 = true

        this.ruleForm.genome = 'hg19'
      },
      getExample7: function() {
        this.ruleForm.inputValue_sequence = 'CAGTATCTGCTGAGGCCTCAAGAGTACCGGC[G/-]CTTCCAGGACTGGGGCTCTGTAGATGCTAAG'
        // this.isDisabled_hg38 = true
        // this.isDisabled_hg19 = true
        // this.isDisabled_mm10 = false

        this.ruleForm.genome = 'mm10'
      },
      getExample8: function() {
        this.ruleForm.inputValue_dbSNP = 'rs62621675'
      },
      getExample9: function() {
        this.ruleForm.inputValue_dbSNP = 'rs28937568'
      },
      getExample10: function() {
        this.ruleForm.inputValue_dbSNP = 'rs1799759'
      },
      getExample11: function() {
        this.ruleForm.inputValue_dbSNP = 'rs45478900'
      },
      getExample12: function() {
        this.ruleForm.inputValue_dbSNP = 'rs2893734'
      },

      filterHandler(value, row, column) {
        const property = column['property']
        return row[property] === value
      },

      uuid() {
        function s4() {
          return Math.floor((1 + Math.random()) * 0x10000)
            .toString(16)
            .substring(1)
        }
        return (
          s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4()
        )
      }
    },
    mounted() {
      // this.plotSequencesInner()
    },
    created() {
    }

  }
</script>
