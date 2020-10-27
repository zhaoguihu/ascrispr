<template>
    <div class="grid-content">
        <el-row>
            <el-col :span="2"><div class="">&nbsp;</div></el-col>
            <el-col :span="20">
                <div style="font-size: 18px;">
                    <el-row>
                        <p style="font-size: 30px; color: rgb(216, 164, 6);margin-bottom: 0">Validated Database </p>

                    </el-row>

                    <el-row>
                        <el-col :span="16">
                            <div>
                                <ul>
                                    <li>
                                        <strong>Experimentally validated sgRNAs</strong>
                                    </li>

                                </ul>
                            </div>
                        </el-col>

                        <el-col :span="8"><div></div></el-col>
                    </el-row>
                </div>
                <hr style="border: 2px solid black">
            </el-col>
            <el-col :span="2"><div></div></el-col>

        </el-row>
        <el-row :gutter="24" v-if="showResult">
            <el-col :span="1">
                <div>&nbsp;</div>
                <div class="buttons">
                </div>
            </el-col>
            <el-col :span="22">
                <el-collapse v-model="activeNames">
                    <el-collapse-item name="1">
                        <div id="anchor-1" style="font-size: 24px;"></div>
                        <template slot="title" >
                            <div class="collapse_title_bg_color_Success11" style="font-size: 24px;">&nbsp;&nbsp;</div>
                        </template>
                        <el-card class="box-card">
                            <el-table :data='tableDataValidatedDatabase.slice((current_pageValidatedDatabase-1)*pageSizeValidatedDatabase,current_pageValidatedDatabase*pageSizeValidatedDatabase)'
                                      :border='true' style='width: 100%'
                                      @sort-change="sort_change_variant"
                                      :header-cell-style="{background:'#D9EDF7',color:'#606266'}"
                                      v-loading='loadingValidatedDatabase' stripe
                                      :default-sort = "{prop: 'disease_types', order: 'ascending'}"
                                      >
                                <el-table-column prop="disease_types" label='Disease/Types' width="120" :render-header="renderheader" sortable="custom"></el-table-column>
                                <el-table-column prop="targeted_genes" label='Targeted/Genes' width="100" :render-header="renderheader" sortable="custom"></el-table-column>
                                <el-table-column prop="protein" label='Variants/(Protein)' width="100" :render-header="renderheader"></el-table-column>
                                <el-table-column prop="genetics" label='Variants/(Genetics)' width="100" :render-header="renderheader"></el-table-column>
                                <el-table-column prop="locations" label='Variant/Locations' width="100" :render-header="renderheader"></el-table-column>
                                <el-table-column prop="cas_nucleases" label='Cas Nucleases' width="100"></el-table-column>
                                <el-table-column prop="pam" label='PAM' width="80"></el-table-column>
                                <el-table-column prop="sequence_with_PAM" label='sgRNA sequence with PAM/(variants labeled in lowercase)' width="250" :render-header="renderheader">
                                    <template slot-scope="scope">
                                        <span v-html="scope.row.sequence_with_PAM"></span>
                                    </template>
                                </el-table-column>
                                <el-table-column prop="targeting_specificity" label='Targeting Specificity' width="280"></el-table-column>
                                <el-table-column prop="functional_outcomes" label='Functional Outcomes' width="280"></el-table-column>
                                <el-table-column prop="pmid" label='References/(PMID)' width="100" :render-header="renderheader">
                                    <template slot-scope="scope">
                                        <a :href="'http://www.ncbi.nlm.nih.gov/pubmed/'+ scope.row.pmid" target='_blank' style="text-decoration:underline;">{{ scope.row.pmid }}</a>
                                    </template>
                                </el-table-column>
                            </el-table>
                            <el-row class="page">
                                <el-col :span="22">
                                    <el-pagination
                                            background
                                            @current-change="paginationValidatedDatabase"
                                            @size-change="sizeChangeValidatedDatabase"
                                            :current-page.sync="current_pageValidatedDatabase"
                                            :page-sizes="[5, 10,20,25,50]"
                                            layout="total,sizes,prev, pager, next"
                                            prev-text="prev"
                                            next-text="next"
                                            :page-size.sync="pageSizeValidatedDatabase"
                                            :total="totalValidatedDatabase">
                                    </el-pagination>
                                </el-col>
                            </el-row>

                            <span>Updated: 07/07/2020</span>
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
  import {
    getInfoValidatedDatabase
  } from "@/api/validatedDatabase";

  export default {
    components: {
    },
    data() {
      return {
        tableData1: [{
          disease_types: 'Retinitis pigmentosa (RP)',
          targeted_genes: 'RHO',
          protein: 'P23H',
          genetics: 'c.68C > A',
          locations: '-3 nt PAM',
          cas_nucleases: 'SaCas9',
          pam: 'NNGRRT',
          sequence_with_PAM: 'GACGGGTGTGGTACGCAGCCaCTTCGAGT',
          targeting_specificity: 'Indel formation was detected in the mutant His allele only',
          functional_outcomes: 'Delivered to both patient iPSCs in vitro and pig retina in vivo, and created a frameshift or premature stop that would prevent P23H transcription',
          pmid: '28619647'
        }],
        showResult: true,
        activeNames: ['1'],
        inputValue: null,

        current_pageValidatedDatabase: 1,
        pageSizeValidatedDatabase: 5,
        totalValidatedDatabase: 0,
        tableDataValidatedDatabase: [],
        loadingValidatedDatabase: false
      }
    },
    methods: {
      compareValuesStr(key, order = 'ascending') {
        console.log('compareValuesStr start')
        return function(a, b) {
          if (!a.hasOwnProperty(key) || !b.hasOwnProperty(key)) {
            // property doesn't exist on either object
            return 0
          }

          const varA = (typeof a[key] === 'string') ? a[key].toUpperCase().trim() : a[key].trim()
          const varB = (typeof b[key] === 'string') ? b[key].toUpperCase().trim() : b[key].trim()

          let comparison = 0
          if (varA > varB) {
            comparison = 1
          } else if (varA < varB) {
            comparison = -1
          }
          return (
            (order === 'descending') ? (comparison * -1) : comparison
          )
        }
      },
      sort_change_variant(column) {
        console.log(column + '-' + column.prop + '-' + column.order)
        this.tableDataValidatedDatabase.sort(this.compareValuesStr(column.prop, column.order))
        // if (column.prop !== 'Chr') {
        //   this.tableDataVariant.sort(this.compareValuesStr(column.prop, column.order))
        // } else {
        //   this.tableDataVariant.sort(this.compareValuesByLocation(column.prop, column.order))
        // }
      },
      fetchData() {
        console.log('fetchData start')
        this.loadingGeneStatistics = true
        getInfoValidatedDatabase()
          .then(response => {
            console.log('getInfoValidatedDatabase start')
            console.log(response)
            let result = response['ValidatedDatabase']
            // this.tableDataValidatedDatabase.sort(this.compareValuesStr('disease_types', 'ascending'))
            this.tableDataValidatedDatabase = result
            this.tableDataValidatedDatabase.sort(function (a, b) {
              var a1 = a.disease_types
              var b1 = b.disease_types
              var a2 = a.targeted_genes
              var b2 = b.targeted_genes
              return (a1 > b1) ? 1 : (a1 < b1) ? -1 : ( (a2 > b2 ) ? 1 : (a2 < b2 ) ? -1 : 0 )
            })
            this.totalValidatedDatabase = result.length
            this.loadingValidatedDatabase = false
            console.log('getInfoValidatedDatabase end')
          })
          .catch(() => {
            this.loadingValidatedDatabase = false
          })
      },
      // 分页功能
      paginationValidatedDatabase(val) {
        this.current_pageValidatedDatabase = val
      },
      sizeChangeValidatedDatabase(val) {
        this.pageSizeValidatedDatabase = val
      },
      renderheader(h, { column, $index }) {
        return h('span', {}, [
          h('span', {}, column.label.split('/')[0]),
          h('br'),
          h('span', {}, column.label.split('/')[1])
        ])
      }
    },
    mounted() {
      this.fetchData()
    }

  }
</script>
