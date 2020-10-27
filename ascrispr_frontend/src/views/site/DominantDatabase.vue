<template>
    <div class="grid-content">
        <el-row>
            <el-col :span="2"><div class="">&nbsp;</div></el-col>
            <el-col :span="20">
                <div style="font-size: 18px;">
                    <el-row>
                        <p style="font-size: 30px; color: rgb(216, 164, 6);margin-bottom: 0">Dominant Database </p>
                    </el-row>

                    <!--<el-row>-->
                        <!--<el-col :span="16">-->
                            <!--<div>-->
                                <!--<ul>-->
                                    <!--<li>-->
                                        <!--<strong>{{$t('g.search_intro')}}</strong>-->
                                    <!--</li>-->

                                <!--</ul>-->
                            <!--</div>-->
                        <!--</el-col>-->

                        <!--<el-col :span="8"><div></div></el-col>-->
                    <!--</el-row>-->
                </div>
            </el-col>
            <el-col :span="2"><div></div></el-col>

        </el-row>
        <el-row>
            <el-col :span='4'><div class="">&nbsp;</div></el-col>
            <el-col :span='18'>
                <div style='font-size: 18px;'>
                    <el-row>
                    </el-row>
                    <el-row>
                        <!--<span style='font-size: 30px; color: rgb(216, 164, 6);margin-bottom: 0'>{{$t('ascrispr_title')}} </span>-->
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
        <br>
        <el-row :gutter="24">
            <el-col :span="7"><div class="">&nbsp;</div></el-col>
            <el-col :span="15">
                <el-form ref="ruleForm3" :model="ruleForm" :rules="rules2" label-width="100px" style="font-size: 18px" size="large">
                    <el-form-item label="Disease: " prop="type" style="font-size: 18px">
                        <!--<el-input placeholder="disease name" v-model="inputValueDisease" class="input-with-select" style='width:500px;'></el-input>-->
                        <el-autocomplete
                                class="inline-input"
                                v-model="inputValueDisease"
                                :fetch-suggestions="querySearchDiseases"
                                placeholder="disease name"
                                :trigger-on-focus="false"
                                @select="handleSelect"
                                style='width:500px;'
                        ></el-autocomplete>
                        <a href="javascript:void(0);" v-on:click="getDiseaseExample" style="text-decoration:underline;">Example</a>
                    </el-form-item>
                    <el-form-item label="Gene: " prop="type">
                        <!--<el-input placeholder="gene symbol" v-model="inputValueGeneSymbol" class="input-with-select" style='width:500px;'></el-input>-->
                        <el-autocomplete
                                class="inline-input"
                                v-model="inputValueGeneSymbol"
                                :fetch-suggestions="querySearchGenes"
                                placeholder="gene symbol"
                                :trigger-on-focus="false"
                                @select="handleSelect"
                                style='width:500px;'
                        ></el-autocomplete>
                        <a href="javascript:void(0);" v-on:click="getGeneSymbolExample" style="text-decoration:underline;">Example</a>

                    </el-form-item>
                    <el-form-item label="rs ID: " prop="type">
                        <!--<el-input placeholder="rs ID" v-model="inputValueRSID" class="input-with-select" style='width:500px;'></el-input>-->
                        <el-autocomplete
                                class="inline-input"
                                v-model="inputValueRSID"
                                :fetch-suggestions="querySearchRSIDs"
                                placeholder="rs ID"
                                :trigger-on-focus="false"
                                @select="handleSelect"
                                style='width:500px;'
                        ></el-autocomplete>
                        <a href="javascript:void(0);" v-on:click="getRSIDExample" style="text-decoration:underline;">Example</a>
                    </el-form-item>

                    <el-form-item>
                        <el-button ref="submitButton" type="primary" @click="submitForm('ruleForm')">Submit</el-button>
                        <el-button @click="resetForm('ruleForm')">Reset</el-button>
                    </el-form-item>
                </el-form>
            </el-col>
        </el-row>
        <el-row :gutter="24">
            <el-col :span="2"><div class="">&nbsp;</div></el-col>
            <el-col :span="20">
                <hr style="border: 2px solid black">
            </el-col>
        </el-row>
        <br>

        <el-row :gutter="24" v-if="showResult">
            <el-col :span="1">
                <div>&nbsp;</div>
            </el-col>
            <el-col :span="22">
                <el-collapse v-model="activeNames">
                    <el-collapse-item name="1">
                        <div id="anchor-1" style="font-size: 24px;"></div>
                        <template slot="title" >
                            <div class="collapse_title_bg_color_Warning11" style="font-size: 24px;">&nbsp;&nbsp;Results</div>
                        </template>
                        <el-card class="box-card">
                            <div class='toolbar' >
                                <el-button  plain icon='el-icon-download' @click='exportExcel()'>Export</el-button>
                            </div>
                            <el-table :data='tableDataDominantDatabase.slice((current_pageDominantDatabase-1)*pageSizeDominantDatabase,current_pageDominantDatabase*pageSizeDominantDatabase)'
                                      :border='true' style='width: 100%'
                                      @sort-change="sort_change"
                                      :header-cell-style="{background:'#D9EDF7',color:'#606266'}"
                                      v-loading='loadingDominantDatabase' stripe
                                      :default-sort = '{prop: "PAM_show", order: "ascending"}'>
                                <el-table-column prop="ranking" label='Ranking' width="80" align="center" :render-header="renderheader"></el-table-column>
                                <el-table-column prop="PAM_show" label='Cas Type' width="180" :render-header="renderheader" sortable="custom"></el-table-column>
                                <el-table-column prop="PAM" label='PAM' width="100" align="center" :render-header="renderheader" sortable="custom">
                                    <template slot-scope="scope">
                                        <span v-html="scope.row.PAM"></span>
                                    </template>
                                </el-table-column>
                                <el-table-column prop="spacerSequence" label='Spacer Sequence' width="230" align="center">
                                    <template slot-scope="scope">
                                        <span v-html="scope.row.spacerSequence"></span>
                                    </template>
                                </el-table-column>
                                <el-table-column prop="seq" label='Seq[Ref/Alt]' width="230" :render-header="renderheader"></el-table-column>
                                <!--<el-table-column prop="inputSeq" label='inputSeq' width="200" :render-header="renderheader"></el-table-column>-->
                                <!--<el-table-column prop="MUTposStart" label='MUTposStart' width="70" :render-header="renderheader"></el-table-column>-->
                                <!--<el-table-column prop="spacerStart" label='spacerStart' width="70" :render-header="renderheader"></el-table-column>-->
                                <!--<el-table-column prop="spacerEnd" label='spacerEnd' width="70" :render-header="renderheader"></el-table-column>-->
                                <!--<el-table-column prop="PAMStart" label='PAMStart' width="70" :render-header="renderheader"></el-table-column>-->
                                <!--<el-table-column prop="PAMEnd" label='PAMEnd' width="70" :render-header="renderheader"></el-table-column>-->

                                <el-table-column prop="direction" label='Strand' width="70" align="center"></el-table-column>
                                <el-table-column prop="disease" label='Disease' width="280"></el-table-column>
                                <el-table-column prop="pathogenic" label='Pathogenic' width="180"></el-table-column>
                                <el-table-column prop="geneSymbol" label='Gene Symbol' width="120"  align="center" :render-header="renderheader" sortable="custom">
                                    <template slot-scope="scope">
                                        <span v-html="scope.row.geneSymbol"></span>
                                    </template>
                                </el-table-column>
                                <el-table-column prop="rsid" label='rs ID' width="150" align="center" sortable="custom">
                                    <template slot-scope="scope">
                                        <span>{{scope.row.rsid}}:[{{scope.row.Ref}}>{{scope.row.Alt}}]</span>
                                    </template>
                                </el-table-column>
                            </el-table>
                            <el-row class="page">
                                <el-col :span="22">
                                    <el-pagination
                                            background
                                            @current-change="paginationDominantDatabase"
                                            @size-change="sizeChangeDominantDatabase"
                                            :current-page.sync="current_pageDominantDatabase"
                                            :page-sizes="[5, 10,20,25,50]"
                                            layout="total,sizes,prev, pager, next"
                                            prev-text="prev"
                                            next-text="next"
                                            :page-size.sync="pageSizeDominantDatabase"
                                            :total="totalDominantDatabase">
                                    </el-pagination>
                                </el-col>
                            </el-row>
                        </el-card>
                    </el-collapse-item>
                </el-collapse>

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
  import XLSX from 'xlsx'

  import {
    getInfoDominantDatabase,
    getKeyListDominantDatabase
  } from "@/api/dominantDatabase";

  export default {
    data() {
      return {

        loading: '',
        stateDiseases: '',
        stateGene: '',
        stateRSID: '',

        genes: [],

        diseases: [],
        rsids: [],

        inputValueDisease: '',
        inputValueGeneSymbol: '',
        inputValueRSID: '',

        effect: 'All',
        extreme: 'All',
        effectOptions: [
          {
            value: 'All',
            label: 'All'
          }, {
            value: 'frameshift',
            label: 'frameshift'
          }, {
            value: 'nonframeshift',
            label: 'nonframeshift'
          }, {
            value: 'nonsynonymous',
            label: 'nonsynonymous'
          }, {
            value: 'synonymous',
            label: 'synonymous'
          }, {
            value: 'stopgain',
            label: 'stopgain'
          }, {
            value: 'stoploss',
            label: 'stoploss'
          }, {
            value: 'splicing',
            label: 'splicing'
          }
        ],

        extremeOptions: [
          {
            value: 'All',
            label: 'All'
          }, {
            value: 'Y',
            label: 'Y'
          }, {
            value: 'N',
            label: 'N'
          }
        ],
        showResult: false,
        activeNames: ['1'],

        current_pageDominantDatabase: 1,
        pageSizeDominantDatabase: 5,
        totalDominantDatabase: 0,
        tableDataDominantDatabase: [],
        tableDataDominantDatabaseExcel: [],
        loadingDominantDatabase: false,

        current_pageRareGene: 1,
        pageSizeRareGene: 10,
        totalRareGene: 0,
        tableDataRareGene: [],
        loadingRareGene: false,

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
      querySearchDiseases(queryString, cb) {
        var diseases = this.diseases;
        var results = queryString ? diseases.filter(this.createFilterDiseases(queryString)) : diseases;
        cb(results);
      },
      createFilterDiseases(queryString) {
        return (diseases) => {
          return (diseases.value.toLowerCase().indexOf(queryString.toLowerCase()) === 0);
        };
      },

      querySearchGenes(queryString, cb) {
        var genes = this.genes;
        var results = queryString ? genes.filter(this.createFilterGenes(queryString)) : genes;
        cb(results);
      },
      createFilterGenes(queryString) {
        return (genes) => {
          return (genes.value.toLowerCase().indexOf(queryString.toLowerCase()) === 0);
        };
      },

      querySearchRSIDs(queryString, cb) {
        var rsids = this.rsids;
        var results = queryString ? rsids.filter(this.createFilterRSIDs(queryString)) : rsids;
        cb(results);
      },
      createFilterRSIDs(queryString) {
        return (rsids) => {
          return (rsids.value.toLowerCase().indexOf(queryString.toLowerCase()) === 0);
        };
      },
      loadKeylist() {
        this.loading = this.$loading({
          lock: true,
          text: 'loading...',
          spinner: 'el-icon-loading',
          background: 'rgba(0, 0, 0, 0.7)'
        })

        getKeyListDominantDatabase()
          .then(response => {
            console.log('getKeyListDominantDatabase start')

            let arr_diseases = []
            let arr_genes = []
            let resultDiseases = response['diseasesDominantDatabase']
            let resultGenes = response['genesDominantDatabase']

            console.log(resultDiseases)
            console.log(resultGenes)

            let tmp = []
            for (let key in resultDiseases) {
              tmp = resultDiseases[key]['diseases'].split('|')
              for (let i = 0; i < tmp.length; i++) {
                arr_diseases.push({'value': tmp[i]})
              }
            }
            for (let key in resultGenes) {
              arr_genes.push({'value': resultGenes[key]['gene_symbol'].split(':')[0]})
            }

            this.genes = this.deteleObject(arr_genes)
            this.diseases = this.deteleObject(arr_diseases)
            console.log(this.genes)
            console.log(this.diseases)

            // for (let i = 0; i < this.genes.length; i++) {
            //   console.log(this.genes[i]['value'])
            // }
            //
            // for (let i = 0; i < this.diseases.length; i++) {
            //   console.log(this.diseases[i]['value'])
            // }

            console.log('getKeyListDominantDatabase end')
            this.loading.close()
          })
          .catch(() => {
            this.loading.close()
          })

      },
      handleSelect(item) {
        console.log(item);
      },

      deteleObject(obj) {
        var uniques = []
        var stringify = {}
        for (var i = 0; i < obj.length; i++) {
          var keys = Object.keys(obj[i])
          keys.sort(function(a, b) {
            return (Number(a) - Number(b))
          })
          var str = ''
          for (var j = 0; j < keys.length; j++) {
            str += JSON.stringify(keys[j])
            str += JSON.stringify(obj[i][keys[j]])
          }
          if (!stringify.hasOwnProperty(str)) {
            uniques.push(obj[i])
            stringify[str] = true
          }
        }
        return uniques
      },

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
      sort_change(column) {
        console.log(column + '-' + column.prop + '-' + column.order)
        this.tableDataDominantDatabase.sort(this.compareValuesStr(column.prop, column.order))
        // if (column.prop !== 'PAM_show') {
        //   this.tableDataDominantDatabase.sort(this.compareValuesStr(column.prop, column.order))
        // } else {
        //   this.tableDataDominantDatabase.sort(this.compareValuesStr('PAM', column.order))
        // }
      },
      exportExcel() {
        let wb = XLSX.utils.book_new()
        let ws = XLSX.utils.json_to_sheet(this.tableDataDominantDatabaseExcel)
        XLSX.utils.book_append_sheet(wb, ws, 'DominantDatabaseResults')
        XLSX.writeFile(wb, 'DominantDatabaseResults.xlsx')
      },

      submitForm(formName) {
        this.showResult = false
        if (this.inputValueDisease !== '' || this.inputValueGeneSymbol !== '' || this.inputValueRSID !== '') {
          console.log('submitForm start')
          console.log(this.inputValueDisease)
          console.log(this.inputValueGeneSymbol)
          console.log(this.inputValueRSID)
          this.loadingDominantDatabase = true
          this.fetchData()
          this.showResult = true
        } else {
          console.log('submit error!!')
          this.showResult = false
          this.loadingDominantDatabase = false
          return false
        }

        // this.$refs[formName].validate((valid) => {
        //   if (valid) {
        //     this.showResult = true
        //     if (this.inputValueDisease !== '') {
        //       console.log('submitForm start')
        //       console.log(this.inputValueDisease)
        //       console.log(this.inputValueGeneSymbol)
        //       console.log(this.inputValueRSID)
        //       // this.fetchData()
        //     }
        //   } else {
        //     console.log('submit error!!')
        //     return false
        //   }
        // })
      },
      fetchData() {
        console.log('fetchData start')
        this.loadingGeneStatistics = true
        getInfoDominantDatabase(this.inputValueDisease, this.inputValueGeneSymbol, this.inputValueRSID)
          .then(response => {
            console.log('getInfoDominantDatabase start')
            console.log(response)
            let result = response['DominantDatabase']
            console.log(result)
            this.tableDataDominantDatabase = this.modifyData(result)
            console.log(this.tableDataDominantDatabase)
            this.totalDominantDatabase = this.tableDataDominantDatabase.length
            this.loadingDominantDatabase = false
            console.log('getInfoDominantDatabase end')
          })
          .catch(() => {
            this.loadingDominantDatabase = false
          })
      },

      reverse_complement(seq0) {
        seq0 = seq0.toUpperCase()
        let seq = ''
        if (seq0=="A") {
          seq = "T"
        }
        if (seq0=="T") {
          seq = "A"
        }
        if (seq0=="C") {
          seq = "G"
        }
        if (seq0=="G") {
          seq = "C"
        }
        return seq
      },

      modifyData(result) {
        console.log('modifyData start')
        let arr = []
        let arr_excel = []
        let iRanking = 1
        for (let key_in in result) {
          let MUTposStart = result[key_in]['MUTposStart']
          let spacerEnd = result[key_in]['spacerEnd']
          let spacerStart = result[key_in]['spacerStart']
          let PAMEnd = result[key_in]['PAMEnd']
          let PAMStart = result[key_in]['PAMStart']
          let spacerSeq = result[key_in]['spacerSequence']
          let PAMSeq = result[key_in]['PAM']
          let PAM_IUB = result[key_in]['PAM_show'].split(':')[1]
          let inputSeq = result[key_in]['inputSeq']
          let N1_0 = result[key_in]['N1']
          let N2_0 = result[key_in]['N2']
          let direction = result[key_in]['direction']
          let N1 = ''
          let N2 = ''
          if (direction === '-') {
            N1 = this.reverse_complement(N1_0)
            N2 = this.reverse_complement(N2_0)
          } else {
            N1 = N1_0
            N2 = N2_0
          }

          // console.log(MUTposStart)
          // console.log(spacerStart)
          // console.log(spacerEnd)
          // console.log(PAMStart)
          // console.log(PAMEnd)
          // console.log(spacerSeq)
          // console.log(PAMSeq)
          // console.log(PAM_IUB)
          let isPrint = 0
          if (MUTposStart <= spacerEnd && MUTposStart >= spacerStart) {
            let posStart = MUTposStart - spacerStart
            let len = 1
            // console.log('spacerSeq 000')
            // console.log(posStart)
            if (inputSeq === 'GAAACTTGCTTTCCACTTGCTGTACTAAATCAAGAGAAAGCAGATGAATTTACCACATTATAT' && PAMSeq==='AGAG'){
              isPrint = 1
            }
            spacerSeq = this.mark_pos(spacerSeq, posStart, len, isPrint)
            // console.log(spacerSeq)
          }
          if (MUTposStart <= PAMEnd && MUTposStart >= PAMStart) {
            let posStart = MUTposStart - PAMStart
            let len = 1

            let PAM_IUB_letter = PAM_IUB.substr(posStart, 1)
            let PAM_original_letter = PAMSeq.substr(posStart, 1)

            let Narr_tmp = ['A', 'C', 'T', 'G']
            let Rarr_tmp = ['A', 'G']
            let Sarr_tmp = ['G', 'C']
            let Marr_tmp = ['A', 'C']
            let Darr_tmp = ['A', 'G', 'T']
            let Warr_tmp = ['A', 'T']
            let Karr_tmp = ['G', 'T']
            let Yarr_tmp = ['T', 'C']
            let Varr_tmp = ['A', 'G', 'C']

            if (PAM_IUB_letter === 'N' && Narr_tmp.indexOf(N2) !== -1 && Narr_tmp.indexOf(N1) !== -1) {
              continue
            } else if (PAM_IUB_letter === 'R' && Rarr_tmp.indexOf(N2) !== -1 && Rarr_tmp.indexOf(N1) !== -1) {
              continue
            } else if (PAM_IUB_letter === 'S' && Sarr_tmp.indexOf(N2) !== -1 && Sarr_tmp.indexOf(N1) !== -1) {
              continue
            } else if (PAM_IUB_letter === 'M' && Marr_tmp.indexOf(N2) !== -1 && Marr_tmp.indexOf(N1) !== -1) {
              continue
            } else if (PAM_IUB_letter === 'D' && Darr_tmp.indexOf(N2) !== -1 && Darr_tmp.indexOf(N1) !== -1) {
              continue
            } else if (PAM_IUB_letter === 'W' && Warr_tmp.indexOf(N2) !== -1 && Warr_tmp.indexOf(N1) !== -1) {
              continue
            } else if (PAM_IUB_letter === 'K' && Karr_tmp.indexOf(N2) !== -1 && Karr_tmp.indexOf(N1) !== -1) {
              continue
            } else if (PAM_IUB_letter === 'Y' && Yarr_tmp.indexOf(N2) !== -1 && Yarr_tmp.indexOf(N1) !== -1) {
              continue
            } else if (PAM_IUB_letter === 'V' && Varr_tmp.indexOf(N2) !== -1 && Varr_tmp.indexOf(N1) !== -1) {
              continue
            }

            // console.log('PAMSeq 000')
            // console.log(posStart)
            PAMSeq = this.mark_pos(PAMSeq, posStart, len, isPrint)
            // console.log(PAMSeq)
          }
          arr.push({
            'ranking': iRanking,
            'rsid': result[key_in]['rsid'],
            'geneSymbol': result[key_in]['geneSymbol'],
            'location': result[key_in]['location'],
            'Ref': result[key_in]['Ref'],
            'Alt': result[key_in]['Alt'],
            'pathogenic': result[key_in]['pathogenic'],
            'seq': result[key_in]['seq'],
            'disease': result[key_in]['disease'],
            'count': result[key_in]['count'],
            'spacerSequence': spacerSeq,
            'PAM': PAMSeq,
            'PAM_show': result[key_in]['PAM_show'],
            'targetingStrand': result[key_in]['targetingStrand'],
            'direction': result[key_in]['direction'],
            'GCcontent': result[key_in]['GCcontent'],
            'self_complementarity': result[key_in]['self_complementarity'],
            'N1': result[key_in]['N1'],
            'N2': result[key_in]['N2'],
            'outStart': result[key_in]['outStart'],
            'spacerStart': result[key_in]['spacerStart'],
            'spacerEnd': result[key_in]['spacerEnd'],
            'PAMStart': result[key_in]['PAMStart'],
            'PAMEnd': result[key_in]['PAMEnd'],
            'PAMLen': result[key_in]['PAMLen'],
            'MUTposStart': result[key_in]['MUTposStart'],
            'MUTposEnd': result[key_in]['MUTposEnd'],
            'inputSeq': result[key_in]['inputSeq'],
            'str_print': result[key_in]['str_print'],
            'inputSeq_type': result[key_in]['inputSeq_type']
          })
          arr_excel.push({
            'Ranking': iRanking,
            'Cas Type': result[key_in]['PAM_show'],
            'PAM': PAMSeq.replace('<span style=\'color:red\'>', '').replace('</span>', ''),
            'Spacer Sequence': spacerSeq.replace('<span style=\'color:red\'>', '').replace('</span>', ''),
            'Seq[Ref/Alt]': result[key_in]['seq'],
            'Strand': result[key_in]['direction'],
            'Disease': result[key_in]['disease'],
            'Pathogenic': result[key_in]['pathogenic'],
            'Gene Symbol': result[key_in]['geneSymbol'],
            'rs ID': result[key_in]['rsid'] + ':[' + result[key_in]['Ref'] + '>' + result[key_in]['Alt'] + ']'

          })
          iRanking = iRanking + 1
        }
        this.tableDataDominantDatabaseExcel = arr_excel

        console.log('modifyData end')
        return arr
      },

      mark_pos(seq, pos, len, isPrint) {
        let seq0 = seq.toUpperCase()
        let prefixStr = "<span style='color:red'>"
        let postfixStr= "</span>"
        let seq_mark_tmp =  prefixStr + seq0.toLowerCase().substr(pos, len) + postfixStr
        if (isPrint) {
          console.log('mark_pos start')
          console.log(seq0.toLowerCase().substr(pos, len))
        }
        // console.log('mark_pos start')
        // console.log(seq_mark_tmp)
        // console.log(seq_mark_tmp)
        // let seq_mark = seq0.replace(seq0.substr(pos, len), seq_mark_tmp)
        let seq_mark = this.replacePos(seq0, pos, seq_mark_tmp, isPrint)
        // console.log(seq_mark)
        // console.log('mark_pos end')
        return seq_mark
      },

      replacePos(strObj, pos, replacetext, isPrint) {
        if (isPrint) {
          console.log('replacePos start')
          console.log(strObj.substring(pos, strObj.length))
          console.log(strObj.substr(0, pos))
        }
        let str = strObj.substr(0, pos) + replacetext + strObj.substring(pos + 1, strObj.length)
        return str
      },

      resetForm(formName) {
        this.inputValueDisease = ''
        this.inputValueGeneSymbol = ''
        this.inputValueRSID = ''
        this.$refs[formName].resetFields()
        this.showResult = false
      },

      // 分页功能
      paginationDominantDatabase(val) {
        this.current_pageDominantDatabase = val
      },
      sizeChangeDominantDatabase(val) {
        this.pageSizeDominantDatabase = val
      },
      renderheader(h, { column, $index }) {
        return h('span', {}, [
          h('span', {}, column.label.split('/')[0]),
          h('br'),
          h('span', {}, column.label.split('/')[1])
        ])
      },

      clearFilter() {
        this.$refs.filterTable.clearFilter();
      },
      getDiseaseExample: function() {
        this.inputValueDisease = "Alzheimer"
      },
      getGeneSymbolExample: function() {
        this.inputValueGeneSymbol = "PSEN1"
      },
      getRSIDExample: function() {
        this.inputValueRSID = "rs63750526"
      },

      getGenomicRegionExample: function() {
        this.inputValue = "chr1-20977000-20977900\n" +
          "chr1-11872-17369\n" +
          "chr2-166166936-166166938\n" +
          "chr17-61831813-61831815\n" +
          "chr7-103338434-103338434\n" +
          "chr19-15271477-15271477";
      },
      getCytobandExample: function() {
        this.inputValue = "15p12.1\n" +
          "12q12\n" +
          "1p36.12";
      },
      filterHandler(value, row, column) {
        const property = column['property'];
        return row[property] === value;
      }
    },
    mounted() {
      if (this.$route.query.inputValue === undefined) {
        this.inputValue = ''
        this.ruleForm.radioSearchType = 'geneSymbol'
      } else {
        this.inputValue = this.$route.query.inputValue
        this.ruleForm.radioSearchType = this.$route.query.searchType
        this.submitForm('ruleForm')
      }

      this.loadKeylist()
    },
    created() {

    }

  }
</script>
