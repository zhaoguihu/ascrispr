<template>
    <div class="grid-content">
        <el-row>
            <el-col :span="4"><div class="">&nbsp;</div></el-col>
            <el-col :span="18">
                <div style="font-size: 18px;">
                    <el-row>
                        <p style="font-size: 30px; color: rgb(216, 164, 6);margin-bottom: 0">{{$t('ascrispr_title')}} </p>
                    </el-row>
                    <el-row>
                        <el-col :span="16">
                            <div>
                                <ul>
                                    <li>
                                        <strong>{{$t('ascrispr_intro')}}</strong>
                                    </li>

                                </ul>
                            </div>
                        </el-col>
                        <el-col :span="8"><div></div></el-col>
                    </el-row>
                </div>
            </el-col>
            <el-col :span="2"><div></div></el-col>
        </el-row>

        <hr style="border: 2px solid black" v-if="showResult">
        <el-row :gutter="24" >
            <el-col :span="24" >
                <el-card class="box-card" v-if="showCas9Result">
                    <div v-for="item in tableDataSequence" >
                        <div slot="header" class="collapse_title_bg_color_Warning" style="font-size: 20px;">&nbsp;&nbsp;
                            <template>
                                <span v-if="ruleForm.radioInputType=='sequence'">Sequence: {{item[0].input_seq}}</span>
                                <span v-else-if="ruleForm.radioInputType=='dbSNP'">dbSNP: {{item[0].input_seq}}</span>
                                <!--<span v-html="scope.row.spacerSeq"></span>-->
                            </template>
                        </div>
                        <div class="toolbar" >
                            <el-button  plain icon="el-icon-download" @click="exportExcel()">Export</el-button>
                        </div>
                        <el-table :data="item.slice((current_pageSequence-1)*pageSizeSequence,current_pageSequence*pageSizeSequence)" :border="true" style="width: 100%"
                                  v-loading="loadingSequence" stripe
                                  :default-sort = '{prop: "ranking", order: "ascending"}'>
                            <el-table-column prop="ranking" label="Ranking" :render-header="renderHeader" :labelClassName="Ranking_tip" sortable></el-table-column>
                            <el-table-column prop="spacerSeq" label="Spacer Sequence" :render-header="renderHeader" :labelClassName="SpacerSequence_tip" sortable>
                                <template slot-scope="scope">
                                    <span v-html="scope.row.spacerSeq"></span>
                                    <div v-if="scope.row.spacerSeq_TTTT_tip !== ''">
                                        <el-tooltip class="item" effect="dark" :content='scope.row.spacerSeq_TTTT_tip' placement="top-start">
                                            <i class="el-icon-question"></i>
                                        </el-tooltip>
                                    </div>
                                </template>
                            </el-table-column>
                            <el-table-column prop="PAMSeq" label="PAM" :render-header="renderHeader" :labelClassName="PAM_tip" sortable>
                                <template slot-scope="scope">
                                    <span v-html="scope.row.PAMSeq"></span>
                                </template>
                            </el-table-column>
                            <el-table-column prop="Crispr_Type" :label='crispr_type_label' :render-header="renderHeader" :labelClassName="crispr_type_tip" sortable></el-table-column>
                            <el-table-column prop="Targeting_Strand" label="Targeting Strand" :render-header="renderHeader" :labelClassName="TargetingStrand_tip" sortable></el-table-column>
                            <el-table-column prop="Direction" label="Direction" :render-header="renderHeader" :labelClassName="Direction_tip" sortable></el-table-column>
                            <el-table-column prop="targetGenomeGeneLocus" label="Gene Locus" :render-header="renderHeader" :labelClassName="GeneLocus_tip" sortable></el-table-column>
                            <el-table-column prop="GC_content" label="GC content (%)" :render-header="renderHeader" :labelClassName="GCcontent_tip" sortable>
                                <template slot-scope="scope">
                                    <div v-if="scope.row.GC_content>=80">
                                        <el-tooltip content="Not recommended. Too high GC content." placement="top">
                                            <span style='color:red'>
                                                 {{scope.row.GC_content}}
                                            </span>
                                        </el-tooltip>
                                    </div>
                                    <div v-else-if="scope.row.GC_content<=20">
                                        <el-tooltip content="Not recommended. Too low GC content." placement="top">
                                            <span style='color:red'>
                                                 {{scope.row.GC_content}}
                                            </span>
                                        </el-tooltip>
                                    </div>
                                    <div v-else>{{scope.row.GC_content}}</div>
                                </template>
                            </el-table-column>
                            <el-table-column prop="mitSpecScore" label="Specificity score" :render-header="renderHeader" :labelClassName="Specificityscore_tip" sortable></el-table-column>
                            <el-table-column prop="Xu_Score" label="Xu 2015 score" :render-header="renderHeader" :labelClassName="Xu2015score_tip" sortable></el-table-column>
                            <el-table-column prop="Doench16_Score" label="Doench 2016 score" :render-header="renderHeader" :labelClassName="Doench2016score_tip" sortable></el-table-column>
                            <el-table-column prop="Moreno_Mateos_Score" label="Moreno Mateos 2015 score" :render-header="renderHeader" :labelClassName="MorenoMateos2015score_tip" sortable></el-table-column>
                            <el-table-column prop="Azimuth_in_vitro_Score" label="Azimuth in vitro score" :render-header="renderHeader" :labelClassName="AzimuthInVitroScore_tip" sortable></el-table-column>
                            <el-table-column prop="Najm2018" label="Najm 2018 score" :render-header="renderHeader" :labelClassName="Najm2018score_tip" sortable></el-table-column>
                            <el-table-column prop="Self_complementarity" label="Self-complementarity" :render-header="renderHeader" :labelClassName="SelfComplementarity_tip" sortable></el-table-column>
                            <el-table-column prop="offtargetCount" label="Off-targets 0-1-2-3" :render-header="renderHeader" :labelClassName="Offtargets_tip" sortable>
                                <template slot-scope="scope">
                                    <router-link tag="a" target="_blank" :to="{path:'ascrisprOfftargets',
                                                query:{
                                                inputType: scope.row.input_types,
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
                                                time_stamp: scope.row.time_stamp}}">{{scope.row.offtargetCount}}</router-link>
                                    <!--<a :href="'http://www.ncbi.nlm.nih.gov/pubmed/'+ scope.row.Enzyme_Information" target='_blank' style="text-decoration:underline;"><span v-html="scope.row.Enzyme_Information"></span></a>-->
                                </template>
                            </el-table-column>
                            <el-table-column prop="Enzyme_Information" label="Enzyme Information" :render-header="renderHeader" :labelClassName="EnzymeInformation_tip" sortable>
                                <template slot-scope="scope">
                                    <router-link tag="a" target="_blank" :to="{path:'ascrisprEnzymesInfoMatched',
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
                                                time_stamp: scope.row.time_stamp}}">{{scope.row.Enzyme_Information}}</router-link>
                                    <!--<a :href="'http://www.ncbi.nlm.nih.gov/pubmed/'+ scope.row.Enzyme_Information" target='_blank' style="text-decoration:underline;"><span v-html="scope.row.Enzyme_Information"></span></a>-->
                                </template>
                            </el-table-column>
                        </el-table>

                        <el-row class="page">
                            <el-col :span="22">
                                <el-pagination
                                        background
                                        @current-change="paginationSequence"
                                        @size-change="sizeChangeSequence"
                                        :current-page.sync="current_pageSequence"
                                        :page-sizes="[10,20,25,50]"
                                        layout="total,sizes,prev, pager, next"
                                        :page-size.sync="pageSizeSequence"
                                        :total="totalSequence">
                                </el-pagination>
                            </el-col>
                        </el-row>
                    </div>

                </el-card>

                <el-card class="box-card" v-if="showCpf1Result">
                    <div v-for="item in tableDataSequence" >
                        <div slot="header" class="collapse_title_bg_color_Warning" style="font-size: 20px;">&nbsp;&nbsp;
                            <template>
                                <span v-if="ruleForm.radioInputType=='sequence'">Sequence: {{item[0].input_seq}}</span>
                                <span v-else-if="ruleForm.radioInputType=='dbSNP'">dbSNP: {{item[0].input_seq}}</span>
                            </template>
                        </div>

                        <div class="toolbar">
                            <el-button  plain icon="el-icon-download" @click="exportExcel()">Export</el-button>
                        </div>
                        <el-table :data="item.slice((current_pageSequence-1)*pageSizeSequence,current_pageSequence*pageSizeSequence)" :border="true" style="width: 100%"
                                  v-loading="loadingSequence" stripe
                                  :default-sort = '{prop: "ranking", order: "ascending"}'>
                            <el-table-column prop="ranking" label="Ranking" :render-header="renderHeader" :labelClassName="Ranking_tip" sortable></el-table-column>
                            <el-table-column prop="PAMSeq" label="PAM" :render-header="renderHeader" :labelClassName="PAM_tip" sortable>
                                <template slot-scope="scope">
                                    <span v-html="scope.row.PAMSeq"></span>
                                </template>
                            </el-table-column>
                            <el-table-column prop="spacerSeq" label="Spacer Sequence" :render-header="renderHeader" :labelClassName="SpacerSequence_tip" sortable>
                                <template slot-scope="scope">
                                    <span v-html="scope.row.spacerSeq"></span>
                                    <div v-if="scope.row.spacerSeq_TTTT_tip !== ''">
                                        <el-tooltip class="item" effect="dark" :content='scope.row.spacerSeq_TTTT_tip' placement="top-start">
                                            <i class="el-icon-question"></i>
                                        </el-tooltip>
                                    </div>
                                </template>
                            </el-table-column>
                            <el-table-column prop="Crispr_Type" :label='crispr_type_label' :render-header="renderHeader" :labelClassName="crispr_type_tip" sortable></el-table-column>
                            <el-table-column prop="Targeting_Strand" label="Targeting Strand" :render-header="renderHeader" :labelClassName="TargetingStrand_tip" sortable></el-table-column>
                            <el-table-column prop="Direction" label="Direction" :render-header="renderHeader" :labelClassName="Direction_tip" sortable></el-table-column>
                            <el-table-column prop="targetGenomeGeneLocus" label="Gene Locus" :render-header="renderHeader" :labelClassName="GeneLocus_tip" sortable></el-table-column>
                            <el-table-column prop="GC_content" label="GC content (%)" :render-header="renderHeader" :labelClassName="GCcontent_tip" sortable>
                                <template slot-scope="scope">
                                    <div v-if="scope.row.GC_content>=80">
                                        <el-tooltip content="Not recommended. Too high GC content." placement="top">
                                            <span style='color:red'>
                                                 {{scope.row.GC_content}}
                                            </span>
                                        </el-tooltip>
                                    </div>
                                    <div v-else-if="scope.row.GC_content<=20">
                                        <el-tooltip content="Not recommended. Too low GC content." placement="top">
                                            <span style='color:red'>
                                                 {{scope.row.GC_content}}
                                            </span>
                                        </el-tooltip>
                                    </div>
                                    <div v-else>{{scope.row.GC_content}}</div>
                                </template>
                            </el-table-column>
                            <el-table-column prop="DeepCpf1" label="Deep Cpf1" :render-header="renderHeader" :labelClassName="DeepCpf1_tip" sortable></el-table-column>
                            <el-table-column prop="Self_complementarity" label="Self-complementarity" :render-header="renderHeader" :labelClassName="SelfComplementarity_tip" sortable></el-table-column>
                            <el-table-column prop="offtargetCount" label="Off-targets 0-1-2-3" :render-header="renderHeader" :labelClassName="Offtargets_tip" sortable>
                                <template slot-scope="scope">
                                    <router-link tag="a" target="_blank" :to="{path:'ascrisprOfftargets',
                                                query:{
                                                inputType: scope.row.input_types,
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
                                                time_stamp: scope.row.time_stamp}}">{{scope.row.offtargetCount}}</router-link>
                                    <!--<a :href="'http://www.ncbi.nlm.nih.gov/pubmed/'+ scope.row.Enzyme_Information" target='_blank' style="text-decoration:underline;"><span v-html="scope.row.Enzyme_Information"></span></a>-->
                                </template>
                            </el-table-column>
                            <el-table-column prop="Enzyme_Information" label="Enzyme Information" :render-header="renderHeader" :labelClassName="EnzymeInformation_tip" sortable>
                                <template slot-scope="scope">
                                    <router-link tag="a" target="_blank" :to="{path:'ascrisprEnzymesInfoMatched',
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
                                                time_stamp: scope.row.time_stamp}}">{{scope.row.Enzyme_Information}}</router-link>
                                    <!--<a :href="'http://www.ncbi.nlm.nih.gov/pubmed/'+ scope.row.Enzyme_Information" target='_blank' style="text-decoration:underline;"><span v-html="scope.row.Enzyme_Information"></span></a>-->
                                </template>
                            </el-table-column>
                        </el-table>

                        <el-row class="page">
                            <el-col :span="22">
                                <el-pagination
                                        background
                                        @current-change="paginationSequence"
                                        @size-change="sizeChangeSequence"
                                        :current-page.sync="current_pageSequence"
                                        :page-sizes="[10,20,25,50]"
                                        layout="total,sizes,prev, pager, next"
                                        :page-size.sync="pageSizeSequence"
                                        :total="totalSequence">
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

</style>

<script type="text/babel">

  import Vue from 'vue'
  import FileSaver from 'file-saver'
  import XLSX from 'xlsx'
  import {
    showInfoSequenceByTime
  } from "@/api/ascrispr"

  import DownloadXls from "@/views/components/DownloadXls";
  import {Tools} from "@/views/utils/Tools";

  const _import = require('../../router/_import_' + process.env.NODE_ENV)

  export default {
    components: {
      DownloadXls
    },
    data() {

      return {
        crispr_type_label: '',
        crispr_type_tip: '',
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

        showResult: true,
        showCas9Result: false,
        showCpf1Result: false,

        show_sequence_type: true,
        show_dbSNP_type: false,
        show_cas9_system_types: true,
        show_cpf1_system_types: false,

        activeNames: ['1', '2'],

        tableDataInputDBSNP: [],
        loadingInputDBSNP: false,

        current_pageSequence: 1,
        pageSizeSequence: 10,
        totalSequence: 0,
        tableDataSequence: [],
        tableDataSequenceForExportExcel: [],
        loadingSequence: false,
        time_stamp: '',
        ruleForm: {
          radioInputType: 'sequence',
          inputValue_sequence: '',
          inputValue_dbSNP: '',
          genome: 'hg19',
          radioType: 'cas9',
          cas9_system_types: ['SpCas9:NGG'],
          cpf1_system_types: ['As\\(Lb\\)Cpf1:TTTV']
        },
        rules: {
          cas9_system_types: [
            { required: true, message: 'please select Cas9 types！', trigger: 'change' }
          ],
          cpf1_system_types: [
            { required: true, message: 'please select Cpf1 types！', trigger: 'change' }
          ]
        }
      }
    },
    methods: {
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

      sortByMultipleKey(keys) {
        return function(a, b) {
          if (keys.length == 0) return 0; // force to equal if keys run out
          let key = keys[0]; // take out the first key
          if (a[key] < b[key]) return -1; // will be 1 if DESC
          else if (a[key] > b[key]) return 1; // will be -1 if DESC
          else return this.sortByMultipleKey(keys.slice(1))(a, b);
        }
      },

      printObject(o) {
        var out = ''
        for (var p in o) {
          out += p + ': ' + o[p] + o[p][1]['Crispr_Type'] + '\n'
        }
        alert(out)
      },

      submitForm() {
        console.log(this.time_stamp)
        this.loadingSequence = true
        showInfoSequenceByTime(this.time_stamp)
          .then(response => {
            let result = response
            console.log(result)
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
                    'crispr_system': result[i][key]['crispr_system'],
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
                    'time_stamp': this.time_stamp,
                    'input_types': result[i][key]['input_types'],
                    'spacerSeq_TTTT_tip': result[i][key]['spacerSeq_TTTT_tip'],
                    'seqId': result[i][key]['seqId'],
                    'guideId': result[i][key]['guideId']
                  })

                  if (this.ruleForm.radioType === 'cas9') {
                    arrForExportExcel.push({
                      'Ranking': j_ranking,
                      'SpacerSequence': result[i][key]['spacerSeq'].replace('<span style=\'color:red\'>', '').replace('</span>', ''),
                      'PAM': result[i][key]['PAMSeq'].replace('<span style=\'color:red\'>', '').replace('</span>', ''),
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

                  if (result[i][key]['crispr_system'] === 'cas9') {
                    this.showCas9Result = true
                    this.showCpf1Result = false

                    this.crispr_type_label = 'Cas9 Type'
                    this.crispr_type_tip = 'Cas9 & Variants'
                  } else if (result[i][key]['crispr_system'] === 'cpf1') {
                    this.showCas9Result = false
                    this.showCpf1Result = true

                    this.crispr_type_label = 'Cpf1 Type'
                    this.crispr_type_tip = 'Cpf1 & Variants'
                  } else if (result[i][key]['crispr_system'] === 'cas12b') {
                    this.showCas9Result = false
                    this.showCpf1Result = true

                    this.crispr_type_label = 'Cas12b Type'
                    this.crispr_type_tip = 'Cas12b & Variants'
                  } else if (result[i][key]['crispr_system'] === 'casx') {
                    this.showCas9Result = false
                    this.showCpf1Result = true

                    this.crispr_type_label = 'CasX Type'
                    this.crispr_type_tip = 'CasX & Variants'
                  }

                  if (result[i][key]['input_types'] === 'sequence') {
                    this.ruleForm.radioInputType = 'sequence'
                    this.TargetingStrand_tip = 'Wildtype Strand or Varied Strand'
                  } else {
                    this.ruleForm.radioInputType = 'dbSNP'
                    this.TargetingStrand_tip = 'Reference Strand or Alternative Strand'
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
          })
          .catch(() => {
            this.$message.error('123 Opps！There is an error')
            console.log('error')
          })
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
        if (this.ruleForm.radioInputType === 'sequence') {
          this.show_sequence_type = true
          this.show_dbSNP_type = false
          this.ruleForm.inputValue_dbSNP = ''
        } else {
          this.show_sequence_type = false
          this.show_dbSNP_type = true
          this.ruleForm.inputValue_sequence = ''
        }
      },

      showRadioType() {
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

          this.ruleForm.casx_system_types = ['DpbCasX:TTCN']
        }
      },

      // showRadioType() {
      //   if (this.ruleForm.radioType === 'cas9') {
      //     this.show_cas9_system_types = true
      //     this.show_cpf1_system_types = false
      //
      //     this.ruleForm.cpf1_system_types = ['As\(Lb\)Cpf1:TTTV']
      //   } else {
      //     this.show_cas9_system_types = false
      //     this.show_cpf1_system_types = true
      //
      //     this.ruleForm.cas9_system_types = ['SpCas9:NGG']
      //   }
      // },

      filterHandler(value, row, column) {
        const property = column['property']
        return row[property] === value
      }
    },
    mounted() {
      if (this.$route.query.time_stamp === undefined) {
        this.time_stamp = ''
      } else {
        this.time_stamp = this.$route.query.time_stamp
      }
      console.log(this.time_stamp)

      this.submitForm()
    },
    created() {

    }

  }
</script>
