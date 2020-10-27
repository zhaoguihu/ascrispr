<template>
    <div class="grid-content">
        <el-row>
            <el-col :span="2"><div class="">&nbsp;</div></el-col>
            <el-col :span="20">
                <div style="font-size: 18px;">
                    <el-row>
                        <p style="font-size: 30px; color: rgb(216, 164, 6);margin-bottom: 0">{{$t('offtargets_title')}} </p>
                    </el-row>
                </div>
            </el-col>
            <el-col :span="2"><div></div></el-col>
        </el-row>

        <el-row :gutter="24">
            <el-col :span="2"><div class="">&nbsp;</div></el-col>
            <el-col :span="20">
                <hr style="border: 2px solid black">
            </el-col>
        </el-row>

        <el-row :gutter="24">
            <el-col :span="2"><div class="">&nbsp;</div></el-col>
            <el-col :span="20">
                <div style="font-size: 20px;">&nbsp;&nbsp;
                    <span v-if="crispr_system==='cas9'">Guide sequence + PAM: {{spacerSeq_original + PAM_original}}</span>
                    <span v-else-if="crispr_system!=='cas9'">PAM + Guide sequence: {{PAM_original + spacerSeq_original}}</span>
                </div>
                <!--<template slot="title" >-->
                    <!--<div class="collapse_title_bg_color_Warning" style="font-size: 20px;">&nbsp;&nbsp;-->
                        <!--<span v-if="isCas9">Guide sequence + PAM: {{this.spacerSeq_original + this.PAM_original}}</span>-->
                        <!--<span v-if="isCpf1">PAM + Guide sequence: {{this.PAM_original + this.spacerSeq_original}}</span>-->
                    <!--</div>-->
                <!--</template>-->
            </el-col>
        </el-row>

        <el-row :gutter="24" v-if="showResult">
            <el-col :span="2"><div class="">&nbsp;</div></el-col>
            <el-col :span="20">

                <el-collapse v-model="activeNames">

                    <el-collapse-item name="1" v-if="showOfftargets">
                        <template slot="title">
                            <div ref="porto"  class="collapse_title_bg_color_Warning" style="font-size: 20px;">&nbsp;&nbsp;<strong>Offtargets information</strong></div>
                        </template>
                        <el-card class="box-card">
                            <div id="datagrid">
                                <div class="toolbar">
                                    <el-button  plain icon="el-icon-download" @click="exportExcel()">Export</el-button>
                                </div>
                                <el-table :data="tableDataOfftargets.slice((current_pageOfftargets-1)*pageSizeOfftargets,current_pageOfftargets*pageSizeOfftargets)" :border="true" style="width: 100%"
                                          v-loading="loadingOfftargets" stripe
                                          :default-sort = "{prop: 'ranking', order: 'ascending'}">
                                    <!--$Ranking_tip = "<span data-toggle='tooltip' title='Ranking' class='glyphicon glyphicon-question-sign'></span>";-->
                                    <!--$AlleleSpecific_tip = "<span data-toggle='tooltip' title='Only one of the alleles can be digested by the candidate restriction enzyme.' class='glyphicon glyphicon-question-sign'></span>";-->

                                    <el-table-column prop="ranking" label="Ranking" :labelClassName="Ranking_tip" sortable :render-header='renderHeader'></el-table-column>
                                    <el-table-column prop="offtargetSeq_for_show" label="Offtarget Seq" :labelClassName="OfftargetSeq_tip" sortable :render-header='renderHeader'>
                                        <template slot-scope="scope">
                                            <span v-html="scope.row.offtargetSeq_for_show"></span>

                                            <div v-if="scope.row.Targeting_Strand_tip !== ''">
                                                <el-tooltip class="item" effect="dark" :content='scope.row.Targeting_Strand_tip' placement="top-start">
                                                    <i class="el-icon-question"></i>
                                                </el-tooltip>
                                            </div>
                                        </template>
                                    </el-table-column>
                                    <el-table-column prop="mismatchPos" label="Mismatch Pos" :labelClassName="MismatchPos_tip" sortable :render-header='renderHeader'></el-table-column>
                                    <el-table-column prop="mismatchCount" label="Mismatch Count" :labelClassName="MismatchCount_tip" sortable :render-header='renderHeader'></el-table-column>
                                    <el-table-column prop="chrom" label="Chrom" :labelClassName="Chromosomelocation_tip" sortable :render-header='renderHeader'></el-table-column>
                                    <el-table-column prop="start" label="Start" :labelClassName="Start_tip" sortable :render-header='renderHeader'></el-table-column>
                                    <el-table-column prop="end" label="End" :labelClassName="End_tip" sortable :render-header='renderHeader'></el-table-column>
                                    <el-table-column prop="strand" label="Strand" :labelClassName="Strand_tip" sortable :render-header='renderHeader'></el-table-column>
                                    <el-table-column prop="locusDesc" label="Locus Desc" :labelClassName="LocusDesc_tip" sortable :render-header='renderHeader'></el-table-column>

                                </el-table>

                                <el-row class="page">
                                    <el-col :span="22">
                                        <el-pagination
                                                background
                                                @current-change="paginationOfftargets"
                                                @size-change="sizeChangeOfftargets"
                                                :current-page.sync="current_pageOfftargets"
                                                :page-sizes="[10,20,25,50]"
                                                layout="total,sizes,prev, pager, next"
                                                :page-size.sync="pageSizeOfftargets"
                                                :total="totalOfftargets">
                                        </el-pagination>
                                    </el-col>
                                </el-row>
                            </div>
                        </el-card>
                    </el-collapse-item>
                </el-collapse>

            </el-col>
            <el-col :span="2"><div class="">&nbsp;</div></el-col>
        </el-row>

    </div>

</template>

<style>

    .buttons{position:fixed;z-index:100;top:60%;left:0px;padding:15px 2px;border-radius:10px;background-color:#7a7a7a;background-color:#fff}

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
  import { getToken } from "@/utils/auth";
  import XLSX from 'xlsx'
  import {
    getOfftargetsInfo
  } from "@/api/offtargetsInfo"

  import {getRoles }  from "@/api/role";

  import {config} from "./../../config/index";
  import DownloadXls from "@/views/components/DownloadXls";
  import {Tools} from "@/views/utils/Tools";

  export default {
    components: {
      DownloadXls
    },
    data() {
      // if (this.Targeting_Strand === 'WT') {
      //   this.Targeting_Strand_tip = 'This sequence is exactly the Varied sequence.'
      // } else if (this.Targeting_Strand === 'Varied') {
      //   this.Targeting_Strand_tip = 'This sequence is exactly the WT sequence.'
      // } else if (this.Targeting_Strand === 'Reference') {
      //   this.Targeting_Strand_tip = 'This sequence is exactly the Alternative sequence.'
      // } else if (this.Targeting_Strand === 'Alternative') {
      //   this.Targeting_Strand_tip = 'This sequence is exactly the Reference sequence.'
      // }

      return {
        // Targeting_Strand_tip: 'This sequence is exactly the Varied sequence.',

        Ranking_tip: 'Ranking',
        OfftargetSeq_tip: 'Off-target Seq',
        MismatchPos_tip: 'Mismatch Pos',
        MismatchCount_tip: 'Mismatch Count',
        Chromosomelocation_tip: 'Chromosome location',
        Start_tip: 'The start position of the off-target sequence.',
        End_tip: 'The end position of the off-target sequence.',
        Strand_tip: 'The strand on which the off-target sequence located.',
        LocusDesc_tip: 'Locus description of the off-targets.',

        type: '1',

        showResult: true,
        showOfftargets: true,

        activeNames: ['1', '2'],
        inputValue: null,

        current_pageOfftargets: 1,
        pageSizeOfftargets: 10,
        totalOfftargets: 0,
        tableDataOfftargets: [],
        tableDataOfftargetsForExportExcel: [],
        loadingOfftargets: false,

        time_stamp: '',
        spacerSeq_original: '',
        PAM_original: '',
        spacerSeq: '',
        PAMSeq: '',
        Crispr_Type: '',
        crispr_system: '',
        Targeting_Strand: 'WT',
        Direction_link: '',
        N1: '',
        N2: '',
        spacerStart: '',
        spacerEnd: '',
        PAMStart: '',
        PAMEnd: '',
        MUTposStart: '',
        MUTposEnd: '',
        PAM_IUB: '',
        seq_name_hide: '',

        seqId: '',
        guideId: '',
        inputType: '',

        ruleForm: {
          rare_variant_extremeN_spinner: 1,
          rare_variant_extremeNsnv_spinner: 2,
          rare_variant_extremeY_07_spinner: 3,
          rare_variant_extremeY_lof_spinner: 5,
          common_variant_range1_spinner: 1,
          common_variant_range2_spinner: 2,
          common_variant_range3_spinner: 3,
          cnv_score_spinner: 5,
          gene_expression_range1_spinner: 1,
          gene_expression_range2_spinner: 2,
          gene_expression_range3_spinner: 3,
          dna_methylation_range1_spinner: 1,
          dna_methylation_range2_spinner: 2,
          dna_methylation_range3_spinner: 3
        },
        rules: {
          name: [
            { required: true, message: '请输入活动名称', trigger: 'blur' },
            { min: 3, max: 5, message: '长度在 3 到 5 个字符', trigger: 'blur' }
          ],
          region: [
            { required: true, message: '请选择活动区域', trigger: 'change' }
          ],
          date1: [
            { type: 'date', required: true, message: '请选择日期', trigger: 'change' }
          ],
          date2: [
            { type: 'date', required: true, message: '请选择时间', trigger: 'change' }
          ],
          type: [
            { type: 'array', required: true, message: '请至少选择一个活动性质', trigger: 'change' }
          ],
          desc: [
            { required: true, message: '请填写活动形式', trigger: 'blur' }
          ]
        }
      }
    },
    methods: {
      exportExcel() {
        let wb = XLSX.utils.book_new()
        let ws = XLSX.utils.json_to_sheet(this.tableDataOfftargetsForExportExcel)
        XLSX.utils.book_append_sheet(wb, ws, "Offtargets")
        XLSX.writeFile(wb, "Offtargets.xlsx")
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
      // renderheader(h, { column, $index }) {
      //   return h('span', {}, [
      //     h('span', {}, column.label.split('/')[0]),
      //     h('br'),
      //     h('span', {}, column.label.split('/')[1])
      //   ])
      // },
      getQuery() {
        if (this.$route.query.spacerSeq_original === undefined) {
          this.spacerSeq_original = ''
        } else {
          this.spacerSeq_original = this.$route.query.spacerSeq_original
        }

        if (this.$route.query.PAM_original === undefined) {
          this.PAM_original = ''
        } else {
          this.PAM_original = this.$route.query.PAM_original
        }

        if (this.$route.query.spacerSeq === undefined) {
          this.spacerSeq = ''
        } else {
          this.spacerSeq = this.$route.query.spacerSeq
        }

        if (this.$route.query.PAMSeq === undefined) {
          this.PAMSeq = ''
        } else {
          this.PAMSeq = this.$route.query.PAMSeq
        }

        if (this.$route.query.Crispr_Type === undefined) {
          this.Crispr_Type = ''
        } else {
          this.Crispr_Type = this.$route.query.Crispr_Type
        }

        if (this.$route.query.crispr_system === undefined) {
          this.crispr_system = ''
        } else {
          this.crispr_system = this.$route.query.crispr_system
        }

        if (this.$route.query.Targeting_Strand === undefined) {
          this.Targeting_Strand = ''
        } else {
          this.Targeting_Strand = this.$route.query.Targeting_Strand
        }

        if (this.$route.query.Direction_link === undefined) {
          this.Direction_link = ''
        } else {
          this.Direction_link = this.$route.query.Direction_link
        }

        if (this.$route.query.N1 === undefined) {
          this.N1 = ''
        } else {
          this.N1 = this.$route.query.N1
        }

        if (this.$route.query.N2 === undefined) {
          this.N2 = ''
        } else {
          this.N2 = this.$route.query.N2
        }

        if (this.$route.query.spacerStart === undefined) {
          this.spacerStart = ''
        } else {
          this.spacerStart = this.$route.query.spacerStart
        }

        if (this.$route.query.spacerEnd === undefined) {
          this.spacerEnd = ''
        } else {
          this.spacerEnd = this.$route.query.spacerEnd
        }

        if (this.$route.query.PAMStart === undefined) {
          this.PAMStart = ''
        } else {
          this.PAMStart = this.$route.query.PAMStart
        }

        if (this.$route.query.PAMEnd === undefined) {
          this.PAMEnd = ''
        } else {
          this.PAMEnd = this.$route.query.PAMEnd
        }

        if (this.$route.query.MUTposStart === undefined) {
          this.MUTposStart = ''
        } else {
          this.MUTposStart = this.$route.query.MUTposStart
        }

        if (this.$route.query.MUTposEnd === undefined) {
          this.MUTposEnd = ''
        } else {
          this.MUTposEnd = this.$route.query.MUTposEnd
        }

        if (this.$route.query.PAM_IUB === undefined) {
          this.PAM_IUB = ''
        } else {
          this.PAM_IUB = this.$route.query.PAM_IUB
        }

        if (this.$route.query.seq_name_hide === undefined) {
          this.seq_name_hide = ''
        } else {
          this.seq_name_hide = this.$route.query.seq_name_hide
        }

        if (this.$route.query.time_stamp === undefined) {
          this.time_stamp = ''
        } else {
          this.time_stamp = this.$route.query.time_stamp
        }

        if (this.$route.query.seqId === undefined) {
          this.seqId = ''
        } else {
          this.seqId = this.$route.query.seqId
        }

        if (this.$route.query.guideId === undefined) {
          this.guideId = ''
        } else {
          this.guideId = this.$route.query.guideId
        }

        if (this.$route.query.inputType === undefined) {
          this.inputType = ''
        } else {
          this.inputType = this.$route.query.inputType
        }

        console.log(this.spacerSeq)
        console.log(this.PAMSeq)
        console.log(this.Crispr_Type)
        console.log(this.crispr_system)

        console.log(this.Targeting_Strand)
        console.log(this.Direction_link)
        console.log(this.N1)
        console.log(this.N2)
        console.log(this.spacerStart)
        console.log(this.spacerEnd)
        console.log(this.PAMStart)
        console.log(this.PAMEnd)
        console.log(this.MUTposStart)
        console.log(this.MUTposEnd)
        console.log(this.PAM_IUB)
        console.log(this.seq_name_hide)
        console.log(this.time_stamp)

        console.log(this.seqId)
        console.log(this.guideId)
        console.log(this.inputType)

      },

      fetchFormData() {
        // this.time_stamp = new Date().getTime()
        let formData = {
          time_stamp: this.time_stamp,
          spacerSeq_original: this.spacerSeq_original,
          PAM_original: this.PAM_original,
          spacerSeq: this.spacerSeq,
          PAMSeq: this.PAMSeq,
          Crispr_Type: this.Crispr_Type,
          crispr_system: this.crispr_system,
          Targeting_Strand: this.Targeting_Strand,
          Direction_link: this.Direction_link,
          N1: this.N1,
          N2: this.N2,
          spacerStart: this.spacerStart,
          spacerEnd: this.spacerEnd,
          PAMStart: this.PAMStart,
          PAMEnd: this.PAMEnd,
          MUTposStart: this.MUTposStart,
          MUTposEnd: this.MUTposEnd,
          PAM_IUB: this.PAM_IUB,
          seq_name_hide: this.seq_name_hide,
          seqId: this.seqId,
          guideId: this.guideId,
          inputType: this.inputType
        }
        return formData
      },

      fetchDataOfftargets(page = this.current_pageOfftargets, pageSize = this.pageSizeOfftargets) {
        this.loadingOfftargets = true
        // geneSymbol = geneSymbol.replace(/\n/g, ',')
        let params = this.fetchFormData()
        getOfftargetsInfo(params, page, pageSize)
          .then(response => {
            let result = response
            console.log(result)
            let j_ranking = 1
            let arr = []
            let arrForExportExcel = []
            for (let key in result) {
              // result[key]
              arr.push({
                'ranking': j_ranking,
                'offtargetSeq_for_show': result[key]['offtargetSeq_for_show'],
                'mismatchPos': result[key]['mismatchPos'],
                'mismatchCount': result[key]['mismatchCount'],
                'chrom': result[key]['chrom'],
                'start': result[key]['start'],
                'end': result[key]['end'],
                'strand': result[key]['strand'],
                'locusDesc': result[key]['locusDesc'],
                'Targeting_Strand_tip': result[key]['Targeting_Strand_tip']

              })

              arrForExportExcel.push({
                'ranking': j_ranking,
                'offtargetSeq_for_show': result[key]['offtargetSeq_for_show'].replace(/<span style='color:red'>/g, '').replace(/<\/span>/g, ''),
                'mismatchPos': result[key]['mismatchPos'],
                'mismatchCount': result[key]['mismatchCount'],
                'chrom': result[key]['chrom'],
                'start': result[key]['start'],
                'end': result[key]['end'],
                'strand': result[key]['strand'],
                'locusDesc': result[key]['locusDesc']
              })
              j_ranking = j_ranking + 1
            }

            arr.sort(function (a, b) {
              var a1 = a.mismatchCount
              var b1 = b.mismatchCount
              return (a1 < b1) ? -1 : (a1 > b1) ? 1 : 0
            })
            arrForExportExcel.sort(function (a, b) {
              var a1 = a.mismatchCount
              var b1 = b.mismatchCount
              return (a1 < b1) ? -1 : (a1 > b1) ? 1 : 0
            })

            for (let k = 0; k < arr.length; k++) {
              arr[k]['ranking'] = k + 1
            }
            for (let k = 0; k < arrForExportExcel.length; k++) {
              arrForExportExcel[k]['ranking'] = k + 1
            }

            this.tableDataOfftargets = arr
            this.tableDataOfftargetsForExportExcel = arrForExportExcel
            this.totalOfftargets = response.length
            this.loadingOfftargets = false
          })
          .catch(() => {
            this.loadingOfftargets = false
          })
      },

      // 分页功能
      paginationOfftargets(val) {
        this.current_pageOfftargets = val
      },
      sizeChangeOfftargets(val) {
        this.current_pageOfftargets = 1
        this.pageSizeOfftargets = val
      },

      clearFilter() {
        this.$refs.filterTable.clearFilter()
      }
    },
    mounted() {
      this.getQuery()
      this.fetchDataOfftargets()
    }

  }
</script>
