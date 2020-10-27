<template>
    <div class="grid-content">
        <el-row>
            <el-col :span="2"><div class="">&nbsp;</div></el-col>
            <el-col :span="20">
                <div style="font-size: 18px;">
                    <el-row>
                        <p style="font-size: 30px; color: rgb(216, 164, 6);margin-bottom: 0">{{$t('g.home_title')}} </p>
                    </el-row>

                    <el-row>
                        <el-col :span="8">
                            <div>
                                <ul>
                                    <li>
                                        <strong>{{$t('g.RV_title')}}</strong>
                                    </li>
                                    <li>
                                        <strong>{{$t('g.CV_title')}}</strong>
                                    </li>
                                    <li>
                                        <strong>{{$t('g.DM_title')}}</strong>
                                    </li>
                                    <li>
                                        <strong>{{$t('g.CVA_title')}}</strong>
                                    </li>
                                </ul>
                            </div>
                        </el-col>
                        <el-col :span="8">
                            <div>
                                <ul>
                                    <li>
                                        <strong>{{$t('g.CNV_title')}}</strong>
                                    </li>
                                    <li>
                                        <strong>{{$t('g.GE_title')}}</strong>
                                    </li>
                                    <li>
                                        <strong>{{$t('g.IP_title')}}</strong>
                                    </li>
                                    <li>
                                        <strong>{{$t('g.CGA_title')}}</strong>
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

        <el-row :gutter="20">
            <el-col :span="2"><div class="">&nbsp;</div></el-col>

            <el-col :span="16">

                <div>

                <div style="margin-top: 15px;">
                    <el-input v-bind:placeholder="$t('g.example_placeholder')" v-model="inputValue" class="input-with-select">
                        <el-button slot="append" icon="el-icon-search" @click="goLink()"></el-button>
                    </el-input>
                </div>

                    <a href="javascript:void(0);" v-on:click="getSelfValue" style="text-decoration:underline;">SNCA</a> &nbsp;/&nbsp;
                    <a href="javascript:void(0);" v-on:click="getSelfValue" style="text-decoration:underline;">chr1-20977000-20977900</a>&nbsp;/&nbsp;
                    <a href="javascript:void(0);" v-on:click="getSelfValue" style="text-decoration:underline;">1p36.12</a>

                </div>


                <!--<input type="text" v-model="result2">-->
                <!--<a href="javascript:void(0)" rel="external nofollow"  v-on:click="test2">测试</a>-->
                <!--<br>-->
                <!--<span>{{ result2 }}</span>-->

                <div style="background-color: rgb(217, 237, 247);font-size: 16px;padding-left: 5px">
                <h5>
                    <h4><span v-html="$t('g.disclaimer')"></span><a @click="viewDisclaimer" style="text-decoration:underline;">{{$t('g.viewDisclaimer')}}</a></h4>
                </h5>
                </div>
                <div class=" ">
                    <hr style="border: 2px solid black">

                    <h2>Motivation and knowledge of Gene4PD</h2>

                    <hr style="border: 2px solid black">

                    <span v-html="$t('g.intro')"></span>
                </div>
            </el-col>
            <el-col :span="4">
                <div class=" ">
                    <p><strong>{{$t('g.recent_updates_title')}}</strong></p>

                <div class="">
                    <p>
                        <strong>[11/9/2018]</strong> {{$t('g.recent_updates_13')}}
                    </p>
                    <p>
                        <strong>[20/8/2018]</strong> {{$t('g.recent_updates_12')}}
                    </p>
                    <p>
                        <strong>[22/7/2018]</strong> {{$t('g.recent_updates_11')}}
                    </p>
                    <p>
                        <strong>[26/6/2018]</strong> {{$t('g.recent_updates_10')}}
                    </p>
                    <p>
                        <strong>[15/6/2018]</strong> {{$t('g.recent_updates_9')}}
                    </p>
                    <p>
                        <strong>[25/5/2018]</strong> {{$t('g.recent_updates_8')}}
                    </p>
                    <p>
                        <strong>[18/5/2018]</strong> {{$t('g.recent_updates_7')}}
                    </p>
                    <p>
                        <strong>[25/3/2018]</strong> {{$t('g.recent_updates_6')}}
                    </p>
                    <p>
                        <strong>[5/2/2018]</strong> {{$t('g.recent_updates_5')}}
                    </p>
                    <p>
                        <strong>[20/11/2018]</strong> {{$t('g.recent_updates_4')}}
                    </p>
                    <p>
                        <strong>[15/10/2017]</strong> {{$t('g.recent_updates_3')}}
                    </p>
                    <p>
                        <strong>[10/9/2017]</strong> {{$t('g.recent_updates_2')}}
                    </p>
                    <p>
                        <strong>[8/6/2017]</strong> {{$t('g.recent_updates_1')}}
                    </p>

                </div>

            </div></el-col>
        </el-row>


    </div>

</template>

<style>
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
    components: {},
    mounted() {

    },
    data() {
      return {

        inputValue: null,

      }
    },
    methods: {
      getSelfValue: function(e) {
        this.inputValue = e.currentTarget.innerText
      },

      goLink: function() {
        let searchType = ''
        let reg_region = /^chr[1-9XY]?([0-9])?[-][0-9]+[-][0-9]+$/
        let reg_cytoband = /^[A-Za-z0-9]+[p|q]{1}[0-9]+[.]{1}[0-9]*$/
        if (reg_region.test(this.inputValue)) { // genomic region search
          searchType = 'genomicRegion'
        } else if (reg_cytoband.test(this.inputValue)) { // cytoband search
          searchType = 'cytoband'
        } else { // gene symbol search
          searchType = 'geneSymbol'
        }
        // alert(searchType)
        this.$router.push({ path: '/search', query: { inputValue: this.inputValue, searchType: searchType }})
      },

      viewDisclaimer: function() {
        this.$alert('We integrated the basic information of more than 60 genetic, ' +
          'genomic and clinical database into one single database, named ' +
          'Gene4PD. This database is freely available for academic usage. Of ' +
          'note, licenses are required for non-academic usage for some of the ' +
          'resources, including ' +
          '<a href="https://academic.oup.com/nar/advance-article/doi/10.1093/nar/gky678/5061021" target="_blank" style="text-decoration:underline;">ReVe</a>, ' +
          '<a href="http://cadd.gs.washington.edu/" target="_blank" style="text-decoration:underline;">CADD</a>, ' +
          '<a href="https://www.ncbi.nlm.nih.gov/clinvar" target="_blank" style="text-decoration:underline;">ClinVar</a>,' +
          '<a href="http://cancer.sanger.ac.uk/cosmic" target="_blank" style="text-decoration:underline;">COSMIC</a>,' +
          '<a href="https://icgc.org/" target="_blank" style="text-decoration:underline;">ICGC</a>, ' +
          '<a href="http://www.ebi.ac.uk/interpro/" target="_blank" style="text-decoration:underline;">InterPro</a>,' +
          '<a href="https://cbcl.ics.uci.edu/public_data/DANN/" target="_blank" style="text-decoration:underline;">DANN</a>, ' +
          '<a href="https://omim.org/ target="_blank" style="text-decoration:underline;">OMIM</a>, ' +
          '<a href="http://genetics.bwh.harvard.edu/pph2/" target="_blank" style="text-decoration:underline;">Polyphen-2</a>,' +
          '<a href="https://sites.google.com/site/revelgenomics/" target="_blank" style="text-decoration:underline;">REVEL</a>, ' +
          '<a href="http://genic-intolerance.org/" target="_blank" style="text-decoration:underline;">RVIS</a>, ' +
          '<a href="http://wiki.chasmsoftware.org/index.php/Main_Page" target="_blank" style="text-decoration:underline;">VEST 3.0</a>, and ' +
          '<a href="https://www.intomics.com/inbio/map/#about" target="_blank" style="text-decoration:underline;">InBio Map™</a>. ' +
          'ANNOVAR, CADD, ClinVar, COSMIC, ICGC, InterPro, DANN, ' +
          'OMIM, Polyphen-2, REVE, REVEL, RVIS, VEST 3.0, and InBio Map. ' +
          'Gene4PD does not grant the non-academic usage of those resources, ' +
          'so please contact the original content provider for that purpose. Disclaimer: This resource is intended for purely research purposes. It should not ' +
          'be used for medical or professional advice.', {
          dangerouslyUseHTMLString: true
        });
      }
    }
  }
</script>
