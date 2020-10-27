<template>
    <div class="grid-content">
        <el-row>
            <el-col :span="2"><div class="">&nbsp;</div></el-col>
            <el-col :span="20">
                <div style="font-size: 18px;">
                    <el-row>
                        <p style="font-size: 30px; color: rgb(216, 164, 6);margin-bottom: 0">{{$t('download_title0')}} </p>

                    </el-row>

                    <el-row>
                        <el-col :span="16">
                            <!--<div>-->
                            <!--<ul>-->
                            <!--<li>-->
                            <!--<strong>{{$t('g.data_source_title')}}</strong>-->
                            <!--</li>-->

                            <!--</ul>-->
                            <!--</div>-->
                        </el-col>

                        <el-col :span="8"><div></div></el-col>
                    </el-row>
                </div>
                <!--<hr style="border: 2px solid black">-->
            </el-col>
            <el-col :span="2"><div></div></el-col>

        </el-row>
        <el-row :gutter="24" v-if="showResult">
            <el-col :span="3">
                <div>&nbsp;</div>
                <div class="buttons">
                </div>
            </el-col>
            <el-col :span="17">
                <!--<div style="background-color: rgb(217, 237, 247);font-size: 16px;padding-left: 5px">-->
                    <!--<h5>-->
                        <!--<h3>In order to guarantee website security, please log in with the public user name and password to download the files.</h3>-->
                        <!--<div>FTP username: genemedftp </div>-->
                        <!--<div>FTP password: genemed@csu.edu.cnftp </div>-->
                    <!--</h5>-->
                <!--</div>-->
                <el-collapse v-model="activeNames">
                    <el-collapse-item name="1">
                        <hr style="border: 2px solid black">
                        <div id="anchor-1" style="font-size: 24px;"></div>
                        <template slot="title" >
                            <div class="collapse_title_bg_color_Success1" style="font-size: 24px;">&nbsp;&nbsp;Release version 1.0</div>
                        </template>
                        <el-card class="box-card">
                            <el-table :data="tableData1" stripe :show-header="true">
                                <el-table-column prop="link" min-width="15%" label="Dataset">
                                    <template slot-scope="scope">
                                        <span v-html="scope.row.link"></span>
                                    </template>
                                </el-table-column>
                                <el-table-column prop="content" label="Description" min-width="40%"></el-table-column>
                                <el-table-column prop="format" label="Format" min-width="8%" ></el-table-column>
                                <el-table-column prop="size" label="Size" min-width="8%" ></el-table-column>
                            </el-table>
                        </el-card>
                    </el-collapse-item>

                </el-collapse>


            </el-col>
            <el-col :span="4">
                <div>&nbsp;</div>
                <div class="buttons">
                </div>
            </el-col>
        </el-row>
        <br><br><br><br><br>

    </div>

</template>

<style>
    .app-main[data-v-c096de38] {
        margin: 0px;
    }
    .app-main[data-v-6380e6d9] {
        margin: 0px;
    }

    .app-main {
        margin: 0;
    }

    .el-main {
        padding: 0;
        overflow-x: hidden;
        overflow-y: auto;
    }
    /*.buttons{position:fixed;z-index:100;top:60%;left:0px;padding:15px 2px;border-radius:10px;background-color:#7a7a7a;background-color:#fff}*/

    /*.collapse_title_bg_color_Success{ background:#FFFFFF; color:#000000}*/
    /*.collapse_title_bg_color_Warning{ background:#E6A23C; color:#FFF}*/
    /*.collapse_title_bg_color_Danger{ background:#F56C6C; color:#FFF}*/
    /*.collapse_title_bg_color_Info{ background:#909399; color:#FFF}*/
    /*.collapse_title_bg_color_Blue{ background:#409EFF; color:#FFF}*/

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

  export default {
    components: {
    },
    data(){
      return {
        tableData1: [{
            content: 'genomeCards_chr1.txt.gz',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr1.txt.gz" target="_blank" style="text-decoration:underline;">genomeCards_chr1</a>',
            format: '.gz',
            size: '55G'
        },{
          content: 'genomeCards_chr1.txt.gz.tbi',
          link: '<a href="ftp://47.244.176.212/genomeCards_chr1.txt.gz.tbi" target="_blank" style="text-decoration:underline;">genomeCards_chr1</a>',
          format: '.tbi',
          size: '216K'
        },{
            content: 'genomeCards_chr2.txt.gz',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr2.txt.gz" target="_blank" style="text-decoration:underline;">genomeCards_chr2</a>',
            format: '.gz',
            size: '59G'
        },{
            content: 'genomeCards_chr2.txt.gz.tbi',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr2.txt.gz.tbi" target="_blank" style="text-decoration:underline;">genomeCards_chr2</a>',
            format: '.tbi',
            size: '229K'
        },{
            content: 'genomeCards_chr3.txt.gz',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr3.txt.gz" target="_blank" style="text-decoration:underline;">genomeCards_chr3</a>',
            format: '.gz',
            size: '48G'
        },{
            content: 'genomeCards_chr3.txt.gz.tbi',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr3.txt.gz.tbi" target="_blank" style="text-decoration:underline;">genomeCards_chr3</a>',
            format: '.tbi',
            size: '187K'
        },{
            content: 'genomeCards_chr4.txt.gz',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr4.txt.gz" target="_blank" style="text-decoration:underline;">genomeCards_chr4</a>',
            format: '.gz',
            size: '46G'
        },{
            content: 'genomeCards_chr4.txt.gz.tbi',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr4.txt.gz.tbi" target="_blank" style="text-decoration:underline;">genomeCards_chr4</a>',
            format: '.tbi',
            size: '180K'
        },{
            content: 'genomeCards_chr5.txt.gz',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr5.txt.gz" target="_blank" style="text-decoration:underline;">genomeCards_chr5</a>',
            format: '.gz',
            size: '44G'
        },{
            content: 'genomeCards_chr5.txt.gz.tbi',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr5.txt.gz.tbi" target="_blank" style="text-decoration:underline;">genomeCards_chr5</a>',
            format: '.tbi',
            size: '170K'
        },{
            content: 'genomeCards_chr6.txt.gz',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr6.txt.gz" target="_blank" style="text-decoration:underline;">genomeCards_chr6</a>',
            format: '.gz',
            size: '41G'
        },{
            content: 'genomeCards_chr6.txt.gz.tbi',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr6.txt.gz.tbi" target="_blank" style="text-decoration:underline;">genomeCards_chr6</a>',
            format: '.tbi',
            size: '161K'
        },{
            content: 'genomeCards_chr7.txt.gz',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr7.txt.gz" target="_blank" style="text-decoration:underline;">genomeCards_chr7</a>',
            format: '.gz',
            size: '38G'
        },{
            content: 'genomeCards_chr7.txt.gz.tbi',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr7.txt.gz.tbi" target="_blank" style="text-decoration:underline;">genomeCards_chr7</a>',
            format: '.tbi',
            size: '150K'
        },{
            content: 'genomeCards_chr8.txt.gz',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr8.txt.gz" target="_blank" style="text-decoration:underline;">genomeCards_chr8</a>',
            format: '.gz',
            size: '35G'
        },{
            content: 'genomeCards_chr8.txt.gz.tbi',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr8.txt.gz.tbi" target="_blank" style="text-decoration:underline;">genomeCards_chr8</a>',
            format: '.tbi',
            size: '138K'
        },{
            content: 'genomeCards_chr9.txt.gz',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr9.txt.gz" target="_blank" style="text-decoration:underline;">genomeCards_chr9</a>',
            format: '.gz',
            size: '29G'
        },{
            content: 'genomeCards_chr9.txt.gz.tbi',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr9.txt.gz.tbi" target="_blank" style="text-decoration:underline;">genomeCards_chr9</a>',
            format: '.tbi',
            size: '117K'
        },{
            content: 'genomeCards_chr10.txt.gz',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr10.txt.gz" target="_blank" style="text-decoration:underline;">genomeCards_chr10</a>',
            format: '.gz',
            size: '24G'
        },{
            content: 'genomeCards_chr10.txt.gz.tbi',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr10.txt.gz.tbi" target="_blank" style="text-decoration:underline;">genomeCards_chr10</a>',
            format: '.tbi',
            size: '126K'
        },{
            content: 'genomeCards_chr11.txt.gz',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr11.txt.gz" target="_blank" style="text-decoration:underline;">genomeCards_chr11</a>',
            format: '.gz',
            size: '32G'
        },{
            content: 'genomeCards_chr11.txt.gz.tbi',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr11.txt.gz.tbi" target="_blank" style="text-decoration:underline;">genomeCards_chr11</a>',
            format: '.tbi',
            size: '126K'
        },{
            content: 'genomeCards_chr12.txt.gz',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr12.txt.gz" target="_blank" style="text-decoration:underline;">genomeCards_chr12</a>',
            format: '.gz',
            size: '32G'
        },{
            content: 'genomeCards_chr12.txt.gz.tbi',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr12.txt.gz.tbi" target="_blank" style="text-decoration:underline;">genomeCards_chr12</a>',
            format: '.tbi',
            size: '125K'
        },{
            content: 'genomeCards_chr13.txt.gz',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr13.txt.gz" target="_blank" style="text-decoration:underline;">genomeCards_chr13</a>',
            format: '.gz',
            size: '24G'
        },{
            content: 'genomeCards_chr13.txt.gz.tbi',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr13.txt.gz.tbi" target="_blank" style="text-decoration:underline;">genomeCards_chr13</a>',
            format: '.tbi',
            size: '91K'
        },{
            content: 'genomeCards_chr14.txt.gz',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr14.txt.gz" target="_blank" style="text-decoration:underline;">genomeCards_chr14</a>',
            format: '.gz',
            size: '22G'
        },{
            content: 'genomeCards_chr14.txt.gz.tbi',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr14.txt.gz.tbi" target="_blank" style="text-decoration:underline;">genomeCards_chr14</a>',
            format: '.tbi',
            size: '85K'
        },{
            content: 'genomeCards_chr15.txt.gz',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr15.txt.gz" target="_blank" style="text-decoration:underline;">genomeCards_chr15</a>',
            format: '.gz',
            size: '20G'
        },{
            content: 'genomeCards_chr15.txt.gz.tbi',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr15.txt.gz.tbi" target="_blank" style="text-decoration:underline;">genomeCards_chr15</a>',
            format: '.tbi',
            size: '79K'
        },{
            content: 'genomeCards_chr16.txt.gz',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr16.txt.gz" target="_blank" style="text-decoration:underline;">genomeCards_chr16</a>',
            format: '.gz',
            size: '20G'
        },{
            content: 'genomeCards_chr16.txt.gz.tbi',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr16.txt.gz.tbi" target="_blank" style="text-decoration:underline;">genomeCards_chr16</a>',
            format: '.tbi',
            size: '77K'
        },{
            content: 'genomeCards_chr17.txt.gz',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr17.txt.gz" target="_blank" style="text-decoration:underline;">genomeCards_chr17</a>',
            format: '.gz',
            size: '19G'
        },{
            content: 'genomeCards_chr17.txt.gz.tbi',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr17.txt.gz.tbi" target="_blank" style="text-decoration:underline;">genomeCards_chr17</a>',
            format: '.tbi',
            size: '76K'
        },{
            content: 'genomeCards_chr18.txt.gz',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr18.txt.gz" target="_blank" style="text-decoration:underline;">genomeCards_chr18</a>',
            format: '.gz',
            size: '19G'
        },{
            content: 'genomeCards_chr18.txt.gz.tbi',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr18.txt.gz.tbi" target="_blank" style="text-decoration:underline;">genomeCards_chr18</a>',
            format: '.tbi',
            size: '73K'
        },{
            content: 'genomeCards_chr19.txt.gz',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr19.txt.gz" target="_blank" style="text-decoration:underline;">genomeCards_chr19</a>',
            format: '.gz',
            size: '13G'
        },{
            content: 'genomeCards_chr19.txt.gz.tbi',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr19.txt.gz.tbi" target="_blank" style="text-decoration:underline;">genomeCards_chr19</a>',
            format: '.tbi',
            size: '53K'
        },{
            content: 'genomeCards_chr20.txt.gz',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr20.txt.gz" target="_blank" style="text-decoration:underline;">genomeCards_chr20</a>',
            format: '.gz',
            size: '15G'
        },{
            content: 'genomeCards_chr20.txt.gz.tbi',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr20.txt.gz.tbi" target="_blank" style="text-decoration:underline;">genomeCards_chr20</a>',
            format: '.tbi',
            size: '57K'
        },{
            content: 'genomeCards_chr21.txt.gz',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr21.txt.gz" target="_blank" style="text-decoration:underline;">genomeCards_chr21</a>',
            format: '.gz',
            size: '8.5G'
        },{
            content: 'genomeCards_chr21.txt.gz.tbi',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr21.txt.gz.tbi" target="_blank" style="text-decoration:underline;">genomeCards_chr21</a>',
            format: '.tbi',
            size: '35K'
        },{
            content: 'genomeCards_chr22.txt.gz',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr22.txt.gz" target="_blank" style="text-decoration:underline;">genomeCards_chr22</a>',
            format: '.gz',
            size: '8.4G'
        },{
            content: 'genomeCards_chr22.txt.gz.tbi',
            link: '<a href="ftp://47.244.176.212/genomeCards_chr22.txt.gz.tbi" target="_blank" style="text-decoration:underline;">genomeCards_chr22</a>',
            format: '.tbi',
            size: '34K'
        },{
            content: 'genomeCards_chrX.txt.gz',
            link: '<a href="ftp://47.244.176.212/genomeCards_chrX.txt.gz" target="_blank" style="text-decoration:underline;">genomeCards_chrX</a>',
            format: '.gz',
            size: '25G'
        },{
            content: 'genomeCards_chrX.txt.gz.tbi',
            link: '<a href="ftp://47.244.176.212/genomeCards_chrX.txt.gz.tbi" target="_blank" style="text-decoration:underline;">genomeCards_chrX</a>',
            format: '.tbi',
            size: '144K'
        },{
            content: 'genomeCards_chrY.txt.gz',
            link: '<a href="ftp://47.244.176.212/genomeCards_chrY.txt.gz" target="_blank" style="text-decoration:underline;">genomeCards_chrY</a>',
            format: '.gz',
            size: '3.2G'
        },{
            content: 'genomeCards_chrY.txt.gz.tbi',
            link: '<a href="ftp://47.244.176.212/genomeCards_chrY.txt.gz.tbi" target="_blank" style="text-decoration:underline;">genomeCards_chrY</a>',
            format: '.tbi',
            size: '17K'
        }],

        //   , {
        //   content: 'Summary of studies regarding to DNMs by WES or WGS.',
        //   link: '<a href="uploads/summary_of_studies_regarding_to_DNMs_by_WES_or_WGS.docx" target="_blank" style="text-decoration:underline;">Summary of studies regarding to DNMs by WES or WGS</a>',
        //   format: '.docx',
        //   size: '27.5KB'
        // }
        showResult: true,

        // searchForm: new SearchModel(),
        // form: new Model(),
        activeNames: ['1', '2', '3', '4', '5', '6', '7', '8'],
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

    },
    mounted() {
    },
    beforeCreate() {

    }

  }
</script>
