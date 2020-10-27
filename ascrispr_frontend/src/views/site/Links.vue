<template>
    <div class="grid-content">
        <el-row>
            <el-col :span="2"><div class="">&nbsp;</div></el-col>
            <el-col :span="20">
                <div style="font-size: 18px;">
                    <el-row>
                        <p style="font-size: 30px; color: rgb(216, 164, 6);margin-bottom: 0">{{$t('links')}} </p>

                    </el-row>

                    <el-row>
                        <el-col :span="16">

                        </el-col>

                        <el-col :span="8"><div></div></el-col>
                    </el-row>
                </div>
                <hr style="border: 2px solid black">
            </el-col>
            <el-col :span="2"><div></div></el-col>

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
                            <div class="collapse_title_bg_color_Warning" style="font-size: 24px;">&nbsp;&nbsp;Links</div>
                        </template>
                        <el-card class="box-card">
                            <el-table :data="tableData1" style="width: 100%" stripe :show-header="false">
                                <el-table-column prop="name" width="180"></el-table-column>
                                <el-table-column prop="content"></el-table-column>
                                <el-table-column prop="link" width="350">
                                    <template slot-scope="scope">
                                        <span v-html="scope.row.link"></span>
                                    </template>
                                </el-table-column>
                            </el-table>
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
    data(){
      return {
        tableData1: [{
          name: 'The Exome Aggregation Consortium (ExAC)',
          content: 'A coalition of investigators seeking to aggregate and harmonize exome sequencing data from a wide variety of large-scale sequencing projects, and to make summary data available for the wider scientific community.',
          link: '<a href="http://exac.broadinstitute.org/" target="_blank" style="text-decoration:underline;">http://exac.broadinstitute.org/</a>'
        }, {
          name: '1000 Genome Project',
          content: 'The 1000 Genomes Project creates the largest public catalogue of human variation and genotype data',
          link: '<a href="https://www.internationalgenome.org/" target="_blank" style="text-decoration:underline;">https://www.internationalgenome.org/</a>'
        }, {
          name: 'CHOPCHOP',
          content: 'A web tool for selecting target sites for CRISPR.',
          link: '<a href="https://chopchop.cbu.uib.no/" target="_blank" style="text-decoration:underline;">https://chopchop.cbu.uib.no/</a>'
        }, {
          name: 'CRISPOR',
          content: 'A web tool for selecting target sites for CRISPR.',
          link: '<a href="http://www.crispor.tefor.net/" target="_blank" style="text-decoration:underline;">http://www.crispor.tefor.net/</a>'
        }, {
          name: 'DeepCpf1',
          content: 'A web tool to predicts Cpf1 activity based on target sequence composition.',
          link: '<a href="http://deepcrispr.info/" target="_blank" style="text-decoration:underline;">http://deepcrispr.info/</a>'
        }, {
          name: 'Cas-OFFinder',
          content: 'A fast and versatile algorithm that searches for potential off-target sites of Cas9 RNA-guided endonucleases.',
          link: '<a href="http://www.rgenome.net/cas-offinder/" target="_blank" style="text-decoration:underline;">http://www.rgenome.net/cas-offinder/</a>'
        }, {
          name: 'ClinVar',
          content: 'Public archive of relationships among sequence variation and human phenotype.',
          link: '<a href="https://www.ncbi.nlm.nih.gov/clinvar" target="_blank" style="text-decoration:underline;">https://www.ncbi.nlm.nih.gov/clinvar</a>'
        }, {
          name: 'OMIM',
          content: 'A knowledgebase of human genes and genetic disorders.',
          link: '<a href="http://www.omim.org/" target="_blank" style="text-decoration:underline;">http://www.omim.org/</a>'
        }, {
          name: 'VarCards',
          content: 'An integrated genetic and clinical database for coding variants in the human genome.',
          link: '<a href="http://varcards.biols.ac.cn/" target="_blank" style="text-decoration:underline;">http://varcards.biols.ac.cn/</a>'
        }, {
          name: 'LoFtool',
          content: 'A gene intolerance score that ranks of genic functional intolerance and consequently susceptibility to disease.',
          link: '<a href="https://www.ncbi.nlm.nih.gov/pubmed/27563026" target="_blank" style="text-decoration:underline;">https://www.ncbi.nlm.nih.gov/pubmed/27563026</a>'
        }, {
          name: 'dbSNP',
          content: 'The Single Nucleotide Polymorphism database (dbSNP) is a public-domain archive for a broad collection of simple genetic polymorphisms.',
          link: '<a href="https://www.ncbi.nlm.nih.gov/snp" target="_blank" style="text-decoration:underline;">https://www.ncbi.nlm.nih.gov/snp</a>'
        }, {
          name: 'SNPedia',
          content: 'A wiki investigating human genetics to share information about the effects of DNA variations.',
          link: '<a href="https://www.snpedia.com/index.php/SNPedia" target="_blank" style="text-decoration:underline;">https://www.snpedia.com/index.php/SNPedia</a>'
        }, {
          name: 'LncRNASNP2',
          content: 'A database of functional SNPs and mutations in human and mouse lncRNAs.',
          link: '<a href="http://bioinfo.life.hust.edu.cn/lncRNASNP" target="_blank" style="text-decoration:underline;">http://bioinfo.life.hust.edu.cn/lncRNASNP</a>'
        }, {
          name: 'GWAS Catalog',
          content: 'The GWAS Catalog delivers a high-quality curated collection of all published genome-wide association studies enabling investigations to identify causal variants, understand disease mechanisms, and establish targets for novel therapies.',
          link: '<a href="http://www.ebi.ac.uk/gwas/" target="_blank" style="text-decoration:underline;">http://www.ebi.ac.uk/gwas/</a>'
        }, {
          name: 'PheGenI',
          content: 'This phenotype-oriented resource, intended for clinicians and epidemiologists interested in following up results from GWAS, can facilitate prioritization of variants to follow up, study design considerations, and generation of biological hypotheses',
          link: '<a href="https://www.ncbi.nlm.nih.gov/gap/phegeni" target="_blank" style="text-decoration:underline;">https://www.ncbi.nlm.nih.gov/gap/phegeni</a>'
        }],

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
        loadingDNAMethylation: false
       }
    },
    methods: {
    }

  }
</script>
