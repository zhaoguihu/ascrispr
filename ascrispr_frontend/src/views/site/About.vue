<template>
    <div class="grid-content">
        <el-row>
            <el-col :span="2"><div class="">&nbsp;</div></el-col>
            <el-col :span="20">
                <div style="font-size: 18px;">
                    <el-row>
                        <p style="font-size: 30px; color: rgb(216, 164, 6);margin-bottom: 0">{{$t('about')}} </p>
                    </el-row>

                    <el-row>
                        <el-col :span="8"><div></div></el-col>
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

        <el-row :gutter="24" v-if="showResult">
            <el-col :span="2">
                <div>&nbsp;</div>
                <div class="buttons">
                </div>
            </el-col>
            <el-col :span="20">
                <el-collapse v-model="activeNames">
                    <el-collapse-item name="1">
                        <div id="anchor-1" style="font-size: 24px;"></div>
                        <template slot="title" >
                            <div class="collapse_title_bg_color_Warning" style="font-size: 24px;">&nbsp;&nbsp;{{$t('about_title1')}}</div>
                        </template>
                        <el-card class="box-card">
                            <el-table :data="tableData" style="width: 100%" stripe :show-header="false">
                                <el-table-column prop="title" width="180"></el-table-column>
                                <el-table-column prop="address"></el-table-column>
                            </el-table>
                            <!--<span v-html="$t('about_intro')"></span>-->
                        </el-card>
                    </el-collapse-item>
                    <el-collapse-item name="2" v-if="showRareVariant">
                        <template slot="title">
                            <div id="anchor-2" class="collapse_title_bg_color_Warning" style="font-size: 24px;">&nbsp;&nbsp;{{$t('about_title2')}}</div>
                        </template>

                        <el-card class="box-card">
                            <el-table :data="tableData2" style="width: 100%" stripe :show-header="true">
                                <el-table-column prop="team_name" width="180" v-bind:label="$t('about_team_name')"></el-table-column>
                                <el-table-column prop="team_email" v-bind:label="$t('about_team_email')"></el-table-column>
                            </el-table>
                        </el-card>

                    </el-collapse-item>
                    <el-collapse-item name="3" v-if="showCommonVariant">
                        <template slot="title">
                            <div ref="porto" class="fl-porto"></div>
                            <div id="anchor-3" class="collapse_title_bg_color_Warning" style="font-size: 24px;">&nbsp;&nbsp;{{$t('about_title3')}} </div>
                        </template>
                        <el-card class="box-card">
                            <span v-html="$t('about_feedback')"></span>

                        </el-card>
                    </el-collapse-item>
                    <el-collapse-item name="4" v-if="showCNV">
                        <template slot="title">
                            <div ref="porto" class="fl-porto"></div>
                            <div id="anchor-4" class="collapse_title_bg_color_Warning" style="font-size: 24px;">&nbsp;&nbsp;{{$t('about_title4')}}</div>
                        </template>
                        <el-card class="box-card">
                            <span v-html="$t('about_term_of_use')"></span>
                        </el-card>
                    </el-collapse-item>
                </el-collapse>
            </el-col>
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
  import {
    getInfoRareVariantByGeneSymbol,
    getInfoCommonVariantByGeneSymbol,
    getInfoCNVByGeneSymbol,
    getInfoDNAMethylationByGeneSymbol,
    getInfoGeneExpressionByGeneSymbol
  } from "@/api/search";

  import {getRoles }  from "@/api/role";

  import {config} from "./../../config/index";
  import DownloadXls from "@/views/components/DownloadXls";
  import {Tools} from "@/views/utils/Tools";

  export default {
    components: {
      // SiteHeader,
      // SiteFooter
      DownloadXls
    },
    data() {
      return {
        tableData: [{
          title: this.$t('about_department_title'),
          address: this.$t('about_department_value')
        }, {
          title: this.$t('about_address_title'),
          address: this.$t('about_address_value')
        }, {
          title: this.$t('about_research_title'),
          address: this.$t('about_research_value')
        }],

        tableData2: [{
          team_name: 'Yu Tang',
          team_email: 'tangyu-sky@163.com'
        }, {
          team_name: 'Jinchen Li',
          team_email: 'my_bio@163.com'
        }, {
          team_name: 'Guihu Zhao',
          team_email: 'zhaoguihu@foxmail.com'
        }],
        showResult: true,
        showRareVariant: true,
        showCommonVariant: true,
        showCNV: true,
        showGeneExpression: true,
        showDNAMethylation: true,
        // searchForm: new SearchModel(),
        // form: new Model(),
        activeNames: ['1', '2', '3', '4', '5', '6'],
        inputValue: null,
        current_pageRareVariant: 1,
        pageSizeRareVariant: 10,
        totalRareVariant: 0,
        tableDataRareVariant: [],
        loadingRareVariant: false,

        current_pageCommonVariant: 1,
        pageSizeCommonVariant: 10,
        totalCommonVariant: 0,
        tableDataCommonVariant: [],
        loadingCommonVariant: false,

        current_pageCNV: 1,
        pageSizeCNV: 10,
        totalCNV: 0,
        tableDataCNV: [],
        loadingCNV: false,

        current_pageGeneExpression: 1,
        pageSizeGeneExpression: 10,
        totalGeneExpression: 0,
        tableDataGeneExpression: [],
        loadingGeneExpression: false,

        current_pageDNAMethylation: 1,
        pageSizeDNAMethylation: 10,
        totalDNAMethylation: 0,
        tableDataDNAMethylation: [],
        loadingDNAMethylation: false,

        Targeting_Strand: '',
        PAMSeq: '',
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

        ruleForm: {
          name: '',
          region: '',
          date1: '',
          date2: '',
          delivery: false,
          type: [],
          radioSearchType: 'geneSymbol',
          resource: '',
          desc: ''
        }
       }
    },
    methods: {
    },
    mounted() {
      if (this.$route.query.Targeting_Strand === undefined) {
        this.Targeting_Strand = ''
      } else {
        this.Targeting_Strand = this.$route.query.Targeting_Strand
      }

      if (this.$route.query.PAMSeq === undefined) {
        this.PAMSeq = ''
      } else {
        this.PAMSeq = this.$route.query.PAMSeq
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

      console.log(this.Targeting_Strand)
      console.log(this.PAMSeq)
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
    },
    beforeCreate() {

    }

  }
</script>
