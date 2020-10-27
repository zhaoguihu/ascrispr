<template>
    <div class="grid-content">
        <el-row>
            <el-col :span="2"><div class="">&nbsp;</div></el-col>
            <el-col :span="20">
                <div style="font-size: 18px;">
                    <el-row>
                        <p style="font-size: 30px; color: rgb(216, 164, 6);margin-bottom: 0">{{$t('g.gene_detail_title')}} <strong>{{geneSymbol}}</strong></p>
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
            <el-col :span="5">
                <div>&nbsp;</div>
                <div class="buttons">
                    <ul class="list">
                        <li><a href="#anchor-1" rel="external nofollow" >{{$t('g.gene_detail_title1')}}</a></li>
                        <li><a href="#anchor-2" rel="external nofollow" >{{$t('g.gene_detail_title2')}}</a>
                            <ul>
                                <li><a href="#anchor-2-1" rel="external nofollow" >Molecular Function</a></li>
                                <li><a href="#anchor-2-2" rel="external nofollow" >Gene Ontology</a></li>
                                <li><a href="#anchor-2-3" rel="external nofollow" >Domain</a></li>
                                <li><a href="#anchor-2-4" rel="external nofollow" >Protein-Protein Interaction</a></li>
                                <li><a href="#anchor-2-5" rel="external nofollow" >Pathway</a></li>
                            </ul>

                        </li>
                        <li><a href="#anchor-3" rel="external nofollow" >{{$t('g.gene_detail_title3')}}</a>
                            <ul>
                                <li><a href="#anchor-3-1" rel="external nofollow" >OMIM</a></li>
                                <li><a href="#anchor-3-2" rel="external nofollow" >ClinVar</a></li>
                                <li><a href="#anchor-3-3" rel="external nofollow" >denovo-db</a></li>
                                <li><a href="#anchor-3-4" rel="external nofollow" >MGI</a></li>
                                <li><a href="#anchor-3-5" rel="external nofollow" >HPO</a></li>
                            </ul>
                        </li>
                        <li><a href="#anchor-4" rel="external nofollow" >{{$t('g.gene_detail_title4')}}</a>
                            <ul>
                                <li><a href="#anchor-4-1" rel="external nofollow" >Brain Span</a></li>
                                <li><a href="#anchor-4-2" rel="external nofollow" >GTEx</a></li>
                                <li><a href="#anchor-4-3" rel="external nofollow" >Subcellular location</a></li>
                            </ul>
                        </li>
                        <li><a href="#anchor-5" rel="external nofollow" >{{$t('g.gene_detail_title5')}}</a></li>
                        <li><a href="#anchor-6" rel="external nofollow" >{{$t('g.gene_detail_title6')}}</a></li>
                        <li><a href="#anchor-7" rel="external nofollow" >{{$t('g.gene_detail_title7')}}</a></li>
                    </ul>
                </div>
            </el-col>
            <el-col :span="19">
                <el-collapse v-model="activeNames">
                    <el-collapse-item name="1">
                        <div id="anchor-1" style="font-size: 24px;"></div>
                        <template slot="title" >
                            <div class="collapse_title_bg_color_Warning" style="font-size: 24px;">&nbsp;&nbsp;{{$t('g.gene_detail_title1')}}</div>
                        </template>
                        <el-card class="box-card">
                            <el-table :data="tableDataBaseInfo" style="width: 100%" stripe :show-header="false" header-align="left">
                                <el-table-column prop="name" width="180" align="right"></el-table-column>
                                <el-table-column>
                                    <template slot-scope="scope">
                                        <span v-html="scope.row.content"></span>
                                    </template>
                                </el-table-column>
                            </el-table>
                        </el-card>
                    </el-collapse-item>
                    <el-collapse-item name="2">
                        <template slot="title">
                            <div id="anchor-2" class="collapse_title_bg_color_Danger" style="font-size: 24px;">&nbsp;&nbsp;{{$t('g.gene_detail_title2')}}</div>
                        </template>
                        <el-card class="box-card">
                            <el-collapse-item name="21">
                                <template slot="title">
                                    <div id="anchor-21" class="collapse_title_bg_color_Danger" style="font-size: 24px;">&nbsp;&nbsp;Molecular function retrieved from <a href="http://www.uniprot.org/uniprot/" target="_blank" style="text-decoration:underline;">UniProtKB</a></div>
                                </template>
                                <el-table :data="tableDataUniprot" style="width: 100%" stripe :show-header="false" header-align="left">
                                    <el-table-column prop="name" width="180" align="right"></el-table-column>
                                    <el-table-column>
                                        <template slot-scope="scope">
                                            <span v-html="scope.row.content"></span>
                                        </template>
                                    </el-table-column>
                                </el-table>
                            </el-collapse-item>
                        </el-card>

                        <el-card class="box-card">
                            <el-collapse-item name="22">
                                <template slot="title">
                                    <div id="anchor-22" class="collapse_title_bg_color_Danger" style="font-size: 24px;">&nbsp;&nbsp;GO term retrieved from <a href="http://geneontology.org" target="_blank" style="text-decoration:underline;">Gene Ontology Consortium</a></div>
                                </template>
                                <div class="toolbar">

                                    <el-button  plain icon="el-icon-download" @click="download()">导出</el-button>
                                </div>
                                <el-table :data="tableDataGO" :border="true" style="width: 100%"
                                            v-loading="loadingGO" stripe
                                          :default-sort = "{prop: 'go_id', order: 'descending'}">
                                    <el-table-column prop="go_id" label="Accession" fixed width="" sortable></el-table-column>
                                    <el-table-column prop="go_name" label="Name" width=""></el-table-column>
                                    <el-table-column prop="envidence_key" label="Evidence"  width=""></el-table-column>
                                    <el-table-column prop="category" label="Category"  width=""></el-table-column>
                                    <el-table-column label="PubMed"  width="">
                                        <template slot-scope="scope">
                                            <span v-html="scope.row.pubmed_id"></span>
                                        </template>
                                    </el-table-column>
                                    <el-table-column label="Organism"  width="">
                                        <template slot-scope="scope">
                                            <span>Homo sapiens</span>
                                        </template>
                                    </el-table-column>
                                </el-table>
                                <el-row class="page">
                                    <el-col :span="22">
                                        <el-pagination
                                                background
                                                @current-change="paginationGO"
                                                @size-change="sizeChangeGO"
                                                :current-page.sync="current_pageGO"
                                                :page-sizes="[10,20,25,50]"
                                                layout="total,sizes,prev, pager, next"
                                                :page-size.sync="pageSizeGO"
                                                :total="totalGO">
                                        </el-pagination>
                                    </el-col>
                                </el-row>
                            </el-collapse-item>
                        </el-card>

                        <el-card class="box-card">
                            <el-collapse-item name="23">
                                <template slot="title">
                                    <div id="anchor-23" class="collapse_title_bg_color_Danger" style="font-size: 24px;">&nbsp;&nbsp;Domain retrieved from <a href="http://www.ebi.ac.uk/interpro/" target="_blank" style="text-decoration:underline;">InterPro</a></div>
                                </template>
                                <div class="toolbar">

                                    <el-button  plain icon="el-icon-download" @click="download()">导出</el-button>
                                </div>
                                <el-table :data="tableDataInterproHumanProteinDomain" :border="true" style="width: 100%"
                                            v-loading="loadingInterproHumanProteinDomain" stripe
                                          :default-sort = "{prop: 'Entry', order: 'descending'}">
                                    <el-table-column prop="Entry" label="Entry" fixed width="" sortable></el-table-column>
                                    <el-table-column prop="Domain_Name" label="Domain Name" width=""></el-table-column>
                                    <el-table-column label="Domain accession"  width="">
                                        <template slot-scope="scope">
                                            <a :href="'http://www.ebi.ac.uk/interpro/entry/' + scope.row.Domain_accession " target='_blank' style="text-decoration:underline;">  {{scope.row.Domain_accession}}  </a>
                                        </template>
                                    </el-table-column>
                                    <el-table-column prop="Domain_source" label="Domain source"  width=""></el-table-column>
                                    <el-table-column prop="Domain_start" label="Domain start"  width=""></el-table-column>
                                    <el-table-column prop="Domain_end" label="Domain end"  width=""></el-table-column>
                                </el-table>
                                <el-row class="page">
                                    <el-col :span="22">
                                        <el-pagination
                                                background
                                                @current-change="paginationInterproHumanProteinDomain"
                                                @size-change="sizeChangeInterproHumanProteinDomain"
                                                :current-page.sync="current_pageInterproHumanProteinDomain"
                                                :page-sizes="[10,20,25,50]"
                                                layout="total,sizes,prev, pager, next"
                                                :page-size.sync="pageSizeInterproHumanProteinDomain"
                                                :total="totalInterproHumanProteinDomain">
                                        </el-pagination>
                                    </el-col>
                                </el-row>
                            </el-collapse-item>
                        </el-card>

                        <el-card class="box-card">
                            <el-collapse-item name="24">
                                <template slot="title">
                                    <div id="anchor-24" class="collapse_title_bg_color_Danger" style="font-size: 24px;">&nbsp;&nbsp;Protein-Protein interaction retrieved from <a href="https://www.intomics.com/inbio/map/#home" target="_blank" style="text-decoration:underline;">InBio Map</a></div>
                                </template>
                                <div class="toolbar">

                                    <el-button  plain icon="el-icon-download" @click="download()">导出</el-button>
                                </div>
                                <el-table :data="tableDataPPI" :border="true" style="width: 100%"
                                            v-loading="loadingPPI" stripe
                                          :default-sort = "{prop: 'interactor_A', order: 'descending'}">
                                    <el-table-column prop="interactor_A" label="interactor A" fixed width="" sortable></el-table-column>
                                    <el-table-column prop="interactor_B" label="interactor B" width=""></el-table-column>
                                    <el-table-column prop="gene_name_for_interactor_A" label="gene name for interactor A"  width=""></el-table-column>
                                    <el-table-column prop="gene_name_for_interactor_B" label="gene name for interactor B"  width=""></el-table-column>
                                    <el-table-column prop="confidence_score" label="Confidence score"  width=""></el-table-column>
                                </el-table>
                                <el-row class="page">
                                    <el-col :span="22">
                                        <el-pagination
                                                background
                                                @current-change="paginationPPI"
                                                @size-change="sizeChangePPI"
                                                :current-page.sync="current_pagePPI"
                                                :page-sizes="[10,20,25,50]"
                                                layout="total,sizes,prev, pager, next"
                                                :page-size.sync="pageSizePPI"
                                                :total="totalPPI">
                                        </el-pagination>
                                    </el-col>
                                </el-row>
                            </el-collapse-item>
                        </el-card>

                        <el-card class="box-card">
                            <el-collapse-item name="25">
                                <template slot="title">
                                    <div id="anchor-25" class="collapse_title_bg_color_Danger" style="font-size: 24px;">
                                        &nbsp;&nbsp;Pathway retrieved from <a href="https://www.ncbi.nlm.nih.gov/biosystems" target="_blank" style="text-decoration:underline;">BioSystems</a>
                                    </div>
                                </template>
                                <div class="toolbar">

                                    <el-button  plain icon="el-icon-download" @click="download()">导出</el-button>
                                </div>
                                <el-table :data="tableDataBiosystemsHuman" :border="true" style="width: 100%"
                                            v-loading="loadingBiosystemsHuman" stripe
                                          :default-sort = "{prop: 'Gene', order: 'descending'}">
                                    <el-table-column prop="Gene" label="Gene" fixed width="" sortable>
                                        <template slot-scope="scope">
                                            {{geneSymbol}}
                                        </template>
                                    </el-table-column>
                                    <el-table-column label="Pathway" width="">
                                        <template slot-scope="scope">
                                            <a :href="'https://www.ncbi.nlm.nih.gov/biosystems/?term=' + scope.row.bsid " target='_blank' style="text-decoration:underline;">  {{scope.row.name}}  </a>
                                        </template>
                                    </el-table-column>
                                    <el-table-column prop="source" label="Source"  width=""></el-table-column>
                                    <el-table-column label="Organism"  width="">
                                        <template slot-scope="scope">
                                            Homo sapiens
                                        </template>
                                    </el-table-column>
                                    <el-table-column prop="description" label="Description"  width="" show-overflow-tooltip></el-table-column>
                                </el-table>
                                <el-row class="page">
                                    <el-col :span="22">
                                        <el-pagination
                                                background
                                                @current-change="paginationBiosystemsHuman"
                                                @size-change="sizeChangeBiosystemsHuman"
                                                :current-page.sync="current_pageBiosystemsHuman"
                                                :page-sizes="[10,20,25,50]"
                                                layout="total,sizes,prev, pager, next"
                                                :page-size.sync="pageSizeBiosystemsHuman"
                                                :total="totalBiosystemsHuman">
                                        </el-pagination>
                                    </el-col>
                                </el-row>
                            </el-collapse-item>
                        </el-card>

                    </el-collapse-item>
                    <el-collapse-item name="3">
                        <template slot="title">
                            <div ref="porto" class="fl-porto"></div>
                            <div id="anchor-3" class="collapse_title_bg_color_Blue" style="font-size: 24px;">&nbsp;&nbsp;{{$t('g.gene_detail_title3')}}</div>
                        </template>
                        <el-card class="box-card">
                            <el-collapse-item name="31">
                                <template slot="title">
                                    <div id="anchor-31" class="collapse_title_bg_color_Blue" style="font-size: 24px;">
                                        &nbsp;&nbsp;Phenotype retrieved from <a href="https://omim.org" target="_blank" style="text-decoration:underline;">OMIM</a>
                                    </div>
                                </template>
                                <div class="toolbar">

                                    <el-button  plain icon="el-icon-download" @click="download()">导出</el-button>
                                </div>
                                <el-table :data="tableDataOMIM" :border="true" style="width: 100%"
                                            v-loading="loadingOMIM" stripe
                                          :default-sort = "{prop: 'Gene', order: 'descending'}">
                                    <el-table-column prop="Gene" label="Gene" fixed width="" sortable></el-table-column>
                                    <el-table-column label="gene/locus MIM number" width="">
                                        <template slot-scope="scope">
                                            <a :href="'https://omim.org/entry/' + scope.row.gene_locus_MIM_number " target='_blank' style="text-decoration:underline;">  {{scope.row.gene_locus_MIM_number}}  </a>
                                        </template>
                                    </el-table-column>
                                    <el-table-column prop="Phenotype" label="Phenotype"  width=""></el-table-column>
                                    <el-table-column label="Phenotype MIM number"  width="">
                                        <template slot-scope="scope">
                                            <a :href="'https://omim.org/entry/' + scope.row.Phenotype_MIM_number " target='_blank' style="text-decoration:underline;">  {{scope.row.Phenotype_MIM_number}}  </a>
                                        </template>
                                    </el-table-column>
                                    <el-table-column prop="Phenotype_mapping_key" label="Phenotype mapping key"  width=""></el-table-column>
                                    <el-table-column prop="Inheritence" label="Inheritence"  width=""></el-table-column>
                                </el-table>
                                <el-row class="page">
                                    <el-col :span="22">
                                        <el-pagination
                                                background
                                                @current-change="paginationOMIM"
                                                @size-change="sizeChangeOMIM"
                                                :current-page.sync="current_pageOMIM"
                                                :page-sizes="[10,20,25,50]"
                                                layout="total,sizes,prev, pager, next"
                                                :page-size.sync="pageSizeOMIM"
                                                :total="totalOMIM">
                                        </el-pagination>
                                    </el-col>
                                </el-row>
                            </el-collapse-item>
                        </el-card>


                        <el-card class="box-card">
                            <el-collapse-item name="32">
                                <template slot="title">
                                    <div id="anchor-32" class="collapse_title_bg_color_Blue" style="font-size: 24px;">
                                        &nbsp;&nbsp;Clinical variation retrieved from <a href="https://www.ncbi.nlm.nih.gov/clinvar/" target="_blank" style="text-decoration:underline;">ClinVar</a>
                                    </div>
                                </template>
                                <div class="toolbar">

                                    <el-button  plain icon="el-icon-download" @click="download()">导出</el-button>
                                </div>
                                <el-table :data="tableDataClinVar" :border="true" style="width: 100%"
                                            v-loading="loadingClinVar" stripe
                                          :default-sort = "{prop: 'start_pos', order: 'descending'}">
                                    <el-table-column prop="start_pos" label="Location" fixed width="" sortable>
                                        <template slot-scope="scope">
                                            {{scope.row.chromo}}:{{scope.row.start_pos}}
                                        </template>
                                    </el-table-column>
                                    <el-table-column prop="ref_base" label="Ref" width=""></el-table-column>
                                    <el-table-column prop="alt_base" label="Alt"  width=""></el-table-column>
                                    <el-table-column prop="clin_sig" label="Clinic significance"  width=""></el-table-column>
                                    <el-table-column prop="conditions" label="Condition"  width=""></el-table-column>
                                    <el-table-column label="Gene"  width="">
                                        <template slot-scope="scope">
                                            {{geneSymbol}}
                                        </template>
                                    </el-table-column>
                                    <el-table-column label="Origin"  width="">
                                        <template slot-scope="scope">
                                            <div v-if="scope.row.origin === '0'">
                                                unknown
                                            </div>
                                            <div v-else-if="scope.row.origin === '1'">
                                                germline
                                            </div>
                                            <div v-else-if="scope.row.origin === '2'">
                                                somatic
                                            </div>
                                            <div v-else-if="scope.row.origin === '4'">
                                                inherited
                                            </div>
                                            <div v-else-if="scope.row.origin === '8'">
                                                paternal
                                            </div>
                                            <div v-else-if="scope.row.origin === '16'">
                                                maternal
                                            </div>
                                            <div v-else-if="scope.row.origin === '32'">
                                                de-novo
                                            </div>
                                            <div v-else-if="scope.row.origin === '64'">
                                                biparental
                                            </div>
                                            <div v-else-if="scope.row.origin === '128'">
                                                uniparental
                                            </div>
                                            <div v-else-if="scope.row.origin === '256'">
                                                not-tested
                                            </div>
                                            <div v-else-if="scope.row.origin === '512'">
                                                tested-inconclusive
                                            </div>
                                            <div v-else-if="scope.row.origin === '1073741824'">
                                                other
                                            </div>
                                            <div v-else>
                                                -
                                            </div>
                                        </template>
                                    </el-table-column>
                                    <el-table-column prop="accession" label="Accession"  width="">
                                        <template slot-scope="scope">
                                            <a :href="'https://www.ncbi.nlm.nih.gov/clinvar/variation/' + scope.row.accession " target='_blank' style="text-decoration:underline;">  {{scope.row.accession}}  </a>
                                        </template>
                                    </el-table-column>
                                </el-table>
                                <el-row class="page">
                                    <el-col :span="22">
                                        <el-pagination
                                                background
                                                @current-change="paginationClinVar"
                                                @size-change="sizeChangeClinVar"
                                                :current-page.sync="current_pageClinVar"
                                                :page-sizes="[10,20,25,50]"
                                                layout="total,sizes,prev, pager, next"
                                                :page-size.sync="pageSizeClinVar"
                                                :total="totalClinVar">
                                        </el-pagination>
                                    </el-col>
                                </el-row>
                            </el-collapse-item>
                        </el-card>


                        <el-card class="box-card">
                            <el-collapse-item name="33">
                                <template slot="title">
                                    <div id="anchor-33" class="collapse_title_bg_color_Blue" style="font-size: 24px;">
                                        &nbsp;&nbsp;De novo variant retrieved from <a href="http://denovo-db.gs.washington.edu/" target="_blank" style="text-decoration:underline;">denovo-db</a>
                                    </div>
                                </template>
                                <div class="toolbar">

                                    <el-button  plain icon="el-icon-download" @click="download()">导出</el-button>
                                </div>
                                <el-table :data="tableDataDenovoDB" :border="true" style="width: 100%"
                                            v-loading="loadingDenovoDB" stripe
                                          :default-sort = "{prop: 'Location', order: 'descending'}">
                                    <el-table-column prop="Location" label="Location" fixed width="" sortable>
                                        <template slot-scope="scope">
                                            <a :href="'http://denovo-db.gs.washington.edu/denovo-db/QueryVariantServlet?searchBy=Chr.+Position&target=' + scope.row.Chr + ':' + scope.row.Start " target='_blank' style="text-decoration:underline;">  {{scope.row.Chr}}:{{scope.row.Start}}  </a>
                                        </template>
                                    </el-table-column>
                                    <el-table-column prop="Ref" label="Ref" width=""></el-table-column>
                                    <el-table-column prop="Alt" label="Alt"  width=""></el-table-column>
                                    <el-table-column prop="Phenotype" label="Phenotype"  width=""></el-table-column>
                                    <el-table-column prop="Gene_symbol" label="Gene"  width=""></el-table-column>
                                    <el-table-column prop="Region" label="Region"  width=""></el-table-column>
                                    <el-table-column prop="Effect" label="Effect"  width=""></el-table-column>
                                    <el-table-column label="PubMed"  width="">
                                        <template slot-scope="scope">
                                            <a :href="'http://www.ncbi.nlm.nih.gov/pubmed/' + scope.row.Pubmed_id" target='_blank' style="text-decoration:underline;"> {{scope.row.Pubmed_id}} </a>
                                        </template>
                                    </el-table-column>
                                </el-table>
                                <el-row class="page">
                                    <el-col :span="22">
                                        <el-pagination
                                                background
                                                @current-change="paginationDenovoDB"
                                                @size-change="sizeChangeDenovoDB"
                                                :current-page.sync="current_pageDenovoDB"
                                                :page-sizes="[10,20,25,50]"
                                                layout="total,sizes,prev, pager, next"
                                                :page-size.sync="pageSizeDenovoDB"
                                                :total="totalDenovoDB">
                                        </el-pagination>
                                    </el-col>
                                </el-row>
                            </el-collapse-item>
                        </el-card>


                        <el-card class="box-card">
                            <el-collapse-item name="34">
                                <template slot="title">
                                    <div id="anchor-34" class="collapse_title_bg_color_Blue" style="font-size: 24px;">
                                        &nbsp;&nbsp;Mammalian Phenotype retrieved from <a href="http://www.informatics.jax.org/" target="_blank" style="text-decoration:underline;">MGI</a>
                                    </div>
                                </template>
                                <div class="toolbar">

                                    <el-button  plain icon="el-icon-download" @click="download()">导出</el-button>
                                </div>
                                <el-table :data="tableDataMGI" :border="true" style="width: 100%"
                                            v-loading="loadingMGI" stripe
                                          :default-sort = "{prop: 'mouse_symbol', order: 'descending'}">
                                    <el-table-column prop="mouse_symbol" label="Gene of mouse" fixed width="" sortable></el-table-column>
                                    <el-table-column label="MGI ID" width="">
                                        <template slot-scope="scope">
                                            <a :href="'http://www.informatics.jax.org/marker/' + scope.row.mgi_id" target='_blank' style="text-decoration:underline;"> {{scope.row.mgi_id}} </a>
                                        </template>
                                    </el-table-column>
                                    <el-table-column label="Mammalian Phenotype ID"  width="">
                                        <template slot-scope="scope">
                                            <a :href="'http://www.informatics.jax.org/searches/Phat.cgi?id=' + scope.row.mp_id" target='_blank' style="text-decoration:underline;"> {{scope.row.mp_id}} </a>
                                        </template>
                                    </el-table-column>
                                    <el-table-column prop="mp_name" label="Mammalian Phenotype term"  width=""></el-table-column>
                                    <el-table-column prop="mp_define" label="Mammalian Phenotype Definition"  width="" show-overflow-tooltip></el-table-column>
                                </el-table>
                                <el-row class="page">
                                    <el-col :span="22">
                                        <el-pagination
                                                background
                                                @current-change="paginationMGI"
                                                @size-change="sizeChangeMGI"
                                                :current-page.sync="current_pageMGI"
                                                :page-sizes="[10,20,25,50]"
                                                layout="total,sizes,prev, pager, next"
                                                :page-size.sync="pageSizeMGI"
                                                :total="totalMGI">
                                        </el-pagination>
                                    </el-col>
                                </el-row>
                            </el-collapse-item>
                        </el-card>


                        <el-card class="box-card">
                            <el-collapse-item name="35">
                                <template slot="title">
                                    <div id="anchor-35" class="collapse_title_bg_color_Blue" style="font-size: 24px;">
                                        &nbsp;&nbsp;Human phenotype ontology retrieved from <a href="http://human-phenotype-ontology.github.io/" target="_blank" style="text-decoration:underline;">HPO</a>
                                    </div>
                                </template>
                                <div class="toolbar">

                                    <el-button  plain icon="el-icon-download" @click="download()">导出</el-button>
                                </div>
                                <el-table :data="tableDataHPO" :border="true" style="width: 100%"
                                            v-loading="loadingHPO" stripe
                                          :default-sort = "{prop: 'symbol', order: 'descending'}">
                                    <el-table-column prop="symbol" label="Gene" fixed width="" sortable></el-table-column>
                                    <el-table-column prop="hpo_term" label="HPO term" width=""></el-table-column>
                                    <el-table-column label="HPO ID"  width="">
                                        <template slot-scope="scope">
                                            <a :href="'http://compbio.charite.de/hpoweb/showterm?id=' + scope.row.hpo_id" target='_blank' style="text-decoration:underline;"> {{scope.row.hpo_id}} </a>
                                        </template>
                                    </el-table-column>
                                </el-table>
                                <el-row class="page">
                                    <el-col :span="22">
                                        <el-pagination
                                                background
                                                @current-change="paginationHPO"
                                                @size-change="sizeChangeHPO"
                                                :current-page.sync="current_pageHPO"
                                                :page-sizes="[10,20,25,50]"
                                                layout="total,sizes,prev, pager, next"
                                                :page-size.sync="pageSizeHPO"
                                                :total="totalHPO">
                                        </el-pagination>
                                    </el-col>
                                </el-row>
                            </el-collapse-item>
                        </el-card>
                    </el-collapse-item>
                    <el-collapse-item name="4">
                        <template slot="title">
                            <div ref="porto" class="fl-porto"></div>
                            <div id="anchor-4" class="collapse_title_bg_color_Info" style="font-size: 24px;">&nbsp;&nbsp;
                                {{$t('g.gene_detail_title4')}}
                            </div>
                        </template>
                        <el-card class="box-card">
                            <el-collapse-item name="41">
                                <template slot="title">
                                    <div id="anchor-41" class="collapse_title_bg_color_Info" style="font-size: 24px;">
                                        &nbsp;&nbsp;Expression of {{geneSymbol}} retrieved from <a href="http://www.brainspan.org" target="_blank" style="text-decoration:underline;">Brain Span</a>
                                    </div>
                                </template>
                                <highcharts :options="heatmapOptions"></highcharts>
                            </el-collapse-item>
                        </el-card>

                        <el-card class="box-card">
                            <el-collapse-item name="42">
                                <template slot="title">
                                    <div id="anchor-42" class="collapse_title_bg_color_Info" style="font-size: 24px;">
                                        &nbsp;&nbsp;Expression of {{geneSymbol}} in 31 primary tissues retrieved from <a href="http://www.gtexportal.org/" target="_blank" style="text-decoration:underline;">GTEx</a>
                                    </div>
                                </template>
                                <highcharts :options="errorBarOptionsGTEx1"></highcharts>
                            </el-collapse-item>
                        </el-card>

                        <el-card class="box-card">
                            <el-collapse-item name="43">
                                <template slot="title">
                                    <div id="anchor-43" class="collapse_title_bg_color_Info" style="font-size: 24px;">
                                        &nbsp;&nbsp;Expression of {{geneSymbol}} in 53 secondary tissues retrieved from <a href="http://www.gtexportal.org/" target="_blank" style="text-decoration:underline;">GTEx</a>
                                    </div>
                                </template>
                                <highcharts :options="errorBarOptionsGTEx2"></highcharts>
                            </el-collapse-item>
                        </el-card>

                        <el-card class="box-card">
                            <el-collapse-item name="44">
                                <template slot="title">
                                    <div id="anchor-44" class="collapse_title_bg_color_Info" style="font-size: 24px;">
                                        &nbsp;&nbsp;Subcellular location retrieved from <a href="http://www.proteinatlas.org/" target="_blank" style="text-decoration:underline;">The Human Protein Atlas</a>
                                    </div>
                                </template>

                                <el-card class="box-card">
                                    <el-table :data="tableDataSubcellularLocation" style="width: 100%" stripe :show-header="false" header-align="right">
                                        <el-table-column prop="name" width="200" align="right"></el-table-column>
                                        <el-table-column>
                                            <template slot-scope="scope">
                                                <span v-html="scope.row.content"></span>
                                            </template>
                                        </el-table-column>
                                    </el-table>
                                </el-card>

                            </el-collapse-item>
                        </el-card>
                    </el-collapse-item>

                    <el-collapse-item name="5">
                        <template slot="title">
                            <div ref="porto" class="fl-porto"></div>
                            <div id="anchor-5" class="collapse_title_bg_color_Success" style="font-size: 24px;">&nbsp;&nbsp;{{$t('g.gene_detail_title5')}}</div>
                        </template>
                        <el-card class="box-card">
                            <el-collapse-item name="51">
                                <template slot="title">
                                    <div id="anchor-51" class="collapse_title_bg_color_Success" style="font-size: 24px;">
                                        &nbsp;&nbsp;Multiple Sequence Alignment retrieved from <a href="https://www.ncbi.nlm.nih.gov/homologene" target="_blank" style="text-decoration:underline;">HomoloGene</a>
                                    </div>
                                </template>
                                <div class="toolbar">

                                    <el-button  plain icon="el-icon-download" @click="download()">导出</el-button>
                                </div>
                                <el-table :data="tableDataHomoloGene" :border="true" style="width: 100%"
                                           v-loading="loadingHomoloGene" stripe
                                          :default-sort = "{prop: 'gene_symbol', order: 'descending'}">
                                    <el-table-column prop="prot_id" label="Protein" fixed width=""></el-table-column>
                                    <el-table-column prop="gene_symbol" label="Gene" width="" sortable></el-table-column>
                                    <el-table-column prop="organism" label="Organism"  width=""></el-table-column>
                                    <el-table-column prop="AAseq" label="Amino acids sequence"  width="" show-overflow-tooltip></el-table-column>
                                </el-table>
                                <el-row class="page">
                                    <el-col :span="22">
                                        <el-pagination
                                                background
                                                @current-change="paginationHomoloGene"
                                                @size-change="sizeChangeHomoloGene"
                                                :current-page.sync="current_pageHomoloGene"
                                                :page-sizes="[10,20,25,50]"
                                                layout="total,sizes,prev, pager, next"
                                                :page-size.sync="pageSizeHomoloGene"
                                                :total="totalHomoloGene">
                                        </el-pagination>
                                    </el-col>
                                </el-row>
                            </el-collapse-item>
                            <h3>
                                Gene Tree for {{this.geneSymbol}} :
                                <a :href="'http://www.ensembl.org/Homo_sapiens/Gene/Compara_Tree?db=core;g=' + this.ensemblID"
                                   target='_blank' style="text-decoration:underline;">ENSEMBL</a> (if available) &nbsp;&nbsp;&nbsp;
                                <a :href="'http://www.treefam.org/family/' + this.ensemblID + '#tabview=tab1'"
                                   target='_blank' style="text-decoration:underline;">TreeFam</a> (if available)
                            </h3>

                        </el-card>
                    </el-collapse-item>
                    <el-collapse-item name="6">
                        <template slot="title">
                            <div ref="porto" class="fl-porto"></div>
                            <div id="anchor-6" class="collapse_title_bg_color_Warning" style="font-size: 24px;">&nbsp;&nbsp;{{$t('g.gene_detail_title6')}}</div>
                        </template>
                        <el-card class="box-card">
                            <el-collapse-item name="61">
                                <template slot="title">
                                    <div id="anchor-61" class="collapse_title_bg_color_Warning" style="font-size: 24px;">
                                        &nbsp;&nbsp;Number of variants in different populations calculated according to <a href="http://gnomad.broadinstitute.org/" target="_blank" style="text-decoration:underline;">genomAD</a>
                                    </div>
                                </template>
                                <div class="toolbar"><label>Allele frequency</label>
                                    <el-select v-model="alleleFreq" placeholder="" @change="selectChangeFetchDataVariantDistributionByGeneSymbol">
                                        <el-option
                                                v-for="item in alleleFreqOptions"
                                                :key="item.value"
                                                :label="item.label"
                                                :value="item.value">
                                        </el-option>
                                    </el-select><label>( variants in different populations )</label>
                                </div>
                                <br>
                                <el-table :data="tableDataVariantDistribution" :border="true" style="width: 100%"
                                           v-loading="loadingVariantDistribution" stripe
                                          :default-sort = "{prop: 'gene_symbol', order: 'descending'}">
                                    <el-table-column prop="gene_symbol" label="Gene" fixed width="" sortable></el-table-column>
                                    <el-table-column prop="LoF" label="LoF" width=""></el-table-column>
                                    <el-table-column prop="Deleterious" label="Deleterious"  width=""></el-table-column>
                                    <el-table-column prop="Tolerant" label="Tolerant"  width=""></el-table-column>
                                    <el-table-column prop="Synonymous" label="Synonymous"  width=""></el-table-column>
                                    <el-table-column prop="Functional" label="Functional"  width=""></el-table-column>
                                    <el-table-column prop="population" label="Population"  width=""></el-table-column>
                                    <el-table-column prop="population" label="Subject"  width=""></el-table-column>
                                </el-table>
                                <el-row class="page">
                                    <el-col :span="22">
                                        <el-pagination
                                                background
                                                @current-change="paginationVariantDistribution"
                                                @size-change="sizeChangeVariantDistribution"
                                                :current-page.sync="current_pageVariantDistribution"
                                                :page-sizes="[10,20,25,50]"
                                                layout="total,sizes,prev, pager, next"
                                                :page-size.sync="pageSizeVariantDistribution"
                                                :total="totalVariantDistribution">
                                        </el-pagination>
                                    </el-col>
                                </el-row>
                            </el-collapse-item>
                        </el-card>
                    </el-collapse-item>
                    <el-collapse-item name="7">
                        <template slot="title">
                            <div ref="porto" class="fl-porto"></div>
                            <div id="anchor-7" class="collapse_title_bg_color_Danger" style="font-size: 24px;">&nbsp;&nbsp;{{$t('g.gene_detail_title7')}}</div>
                        </template>
                        <el-card class="box-card">
                            <el-collapse-item name="71">
                                <template slot="title">
                                    <div id="anchor-71" class="collapse_title_bg_color_Danger" style="font-size: 24px;">
                                        &nbsp;&nbsp;Drug-gene interaction retrieved from <a href="http://dgidb.genome.wustl.edu/" target="_blank" style="text-decoration:underline;">DGIdb</a>
                                    </div>
                                </template>
                                <div class="toolbar">

                                    <el-button  plain icon="el-icon-download" @click="download()">导出</el-button>
                                </div>
                                <el-table :data="tableDataDGIdb" :border="true" style="width: 100%"
                                           v-loading="loadingDGIdb" stripe
                                          :default-sort = "{prop: 'Gene', order: 'descending'}">
                                    <el-table-column label="Gene" fixed width="" sortable>
                                        <template slot-scope="scope">
                                            <a :href="'http://www.dgidb.org/genes/' + scope.row.Gene" target='_blank' style="text-decoration:underline;"> {{scope.row.Gene}} </a>
                                        </template>
                                    </el-table-column>
                                    <el-table-column prop="Drug" label="Drug" width="">
                                        <template slot-scope="scope">
                                            <a :href="'http://www.dgidb.org/drugs/' + scope.row.Drug" target='_blank' style="text-decoration:underline;"> {{scope.row.Drug}} </a>
                                        </template>

                                    </el-table-column>
                                    <el-table-column prop="Interaction_Type" label="Interaction Type"  width=""></el-table-column>
                                    <el-table-column label="Source"  width="">
                                        <template slot-scope="scope">
                                            <span v-html="scope.row.Source"></span>
                                        </template>
                                    </el-table-column>
                                    <el-table-column label="InteractionId"  width="">
                                        <template slot-scope="scope">
                                            <a :href="'http://www.dgidb.org/interactions/' + scope.row.InteractionId" target='_blank' style="text-decoration:underline;"> {{scope.row.InteractionId}} </a>
                                        </template>
                                    </el-table-column>
                                </el-table>

                                <el-row class="page">
                                    <el-col :span="22">
                                        <el-pagination
                                                background
                                                @current-change="paginationDGIdb"
                                                @size-change="sizeChangeDGIdb"
                                                :current-page.sync="current_pageDGIdb"
                                                :page-sizes="[10,20,25,50]"
                                                layout="total,sizes,prev, pager, next"
                                                :page-size.sync="pageSizeDGIdb"
                                                :total="totalDGIdb">
                                        </el-pagination>
                                    </el-col>
                                </el-row>
                            </el-collapse-item>
                        </el-card>
                    </el-collapse-item>
                </el-collapse>

            </el-col>

        </el-row>
    </div>

</template>

<style>

    .buttons{position:fixed;z-index:100;top:20%;left:0px;padding:15px 2px;border-radius:10px;background-color:#7a7a7a;background-color:#fff}

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
  import {Chart} from 'highcharts-vue'
  import Highcharts from 'highcharts'

  import { getToken } from "@/utils/auth";
  import {
    getInfoGeneInfoHSByGeneSymbol,
    getInfoGeneScoreByGeneSymbol,
    getInfoGene2goHumanByGeneID,
    getInfoPPIByUniprotEntry,
    getInfoBiosystemsHumanByGeneID,
    getInfoClinVarByGeneInfo,
    getInfoDenovoDBByGeneSymbol,
    getInfoMGIByGeneID,
    getInfoHPOByGeneID,
    getInfoHomoloGeneByGeneID,
    getInfoVariantDistributionByGeneSymbol,
    getInfoDGIdbByGeneSymbol,
    getInfoUniprotByUniprotEntry,
    getInfoInterproHumanProteinDomainByUniprotEntry,
    getInfoOMIMByGeneID,
    getInfoSubcellularLocationByEnsemblID,
    getInfoBSAvgExpressionByGeneSymbol,
    getInfoGTEx1ByEnsemblID,
    getInfoGTEx2ByEnsemblID
  } from "@/api/geneDetail";

  import {getRoles }  from "@/api/role";

  import {config} from "./../../config/index";
  import DownloadXls from "@/views/components/DownloadXls";
  import {Tools} from "@/views/utils/Tools";

  export default {
    components: {
      highcharts: Chart,
      DownloadXls
    },
    data() {
      return {

        alleleFreqOptions: [
          {
            value: 0.05,
            label: '<=0.05'
          }, {
            value: 0.01,
            label: '<=0.01'
          }, {
            value: 0.005,
            label: '<=0.005'
          }, {
            value: 0.001,
            label: '<=0.001'
          }, {
            value: 0.0005,
            label: '<=0.0005'
          }, {
            value: 0.0001,
            label: '<=0.0001'
          }, {
            value: 0.00005,
            label: '<=0.00005'
          }, {
            value: 0.00001,
            label: '<=0.00001'
          }
        ],

        bs_ge: [],

        heatmapOptions: {},

        inverted: false,
        tissueErrorBar: [],
        expr: [],
        sd: [],
        errorBarOptionsGTEx1: {},
        errorBarOptionsGTEx2: {},
        tableDataBaseInfo: [],
        tableDataSubcellularLocation: [],

        geneSymbol: '',
        geneID: '',
        geneInfo: this.geneSymbol + ':' + this.geneID,
        alleleFreq: 0.05,
        uniprotEntry: '',
        uniprotEntryID: '',

        ensemblID: '',

        showResult: true,

        activeNames: ['1', '2', '3', '4', '5', '6', '7', '21', '22', '23', '24', '25', '31', '32', '33', '34', '35', '41', '42', '43', '44', '51', '61', '71'],
        inputValue: null,

        current_pageGO: 1,
        pageSizeGO: 10,
        totalGO: 0,
        tableDataGO: [],
        loadingGO: false,

        current_pagePPI: 1,
        pageSizePPI: 10,
        totalPPI: 0,
        tableDataPPI: [],
        loadingPPI: false,

        current_pageBiosystemsHuman: 1,
        pageSizeBiosystemsHuman: 10,
        totalBiosystemsHuman: 0,
        tableDataBiosystemsHuman: [],
        loadingBiosystemsHuman: false,

        current_pageClinVar: 1,
        pageSizeClinVar: 10,
        totalClinVar: 0,
        tableDataClinVar: [],
        loadingClinVar: false,

        current_pageDenovoDB: 1,
        pageSizeDenovoDB: 10,
        totalDenovoDB: 0,
        tableDataDenovoDB: [],
        loadingDenovoDB: false,

        current_pageMGI: 1,
        pageSizeMGI: 10,
        totalMGI: 0,
        tableDataMGI: [],
        loadingMGI: false,

        current_pageHPO: 1,
        pageSizeHPO: 10,
        totalHPO: 0,
        tableDataHPO: [],
        loadingHPO: false,

        current_pageHomoloGene: 1,
        pageSizeHomoloGene: 10,
        totalHomoloGene: 0,
        tableDataHomoloGene: [],
        loadingHomoloGene: false,

        current_pageVariantDistribution: 1,
        pageSizeVariantDistribution: 10,
        totalVariantDistribution: 0,
        tableDataVariantDistribution: [],
        loadingVariantDistribution: false,

        current_pageUniprot: 1,
        pageSizeUniprot: 10,
        totalUniprot: 0,
        tableDataUniprot: [],
        loadingUniprot: false,

        current_pageDGIdb: 1,
        pageSizeDGIdb: 10,
        totalDGIdb: 0,
        tableDataDGIdb: [],
        loadingDGIdb: false,

        current_pageInterproHumanProteinDomain: 1,
        pageSizeInterproHumanProteinDomain: 10,
        totalInterproHumanProteinDomain: 0,
        tableDataInterproHumanProteinDomain: [],
        loadingInterproHumanProteinDomain: false,

        current_pageOMIM: 1,
        pageSizeOMIM: 10,
        totalOMIM: 0,
        tableDataOMIM: [],
        loadingOMIM: false,

        current_pageSubcellularLocation: 1,
        pageSizeSubcellularLocation: 10,
        totalSubcellularLocation: 0,
        loadingSubcellularLocation: false,

        loadingBSAvgExpression: false,

        multiSelect: []
      }
    },
    methods: {
      getParams() {
        this.geneSymbol = this.$route.query.gene_symbol
      },

      fetchDataBaseInfoByGeneSymbol(geneSymbol = this.geneSymbol) {
        getInfoGeneInfoHSByGeneSymbol(geneSymbol)
          .then(response => {
            let result = response.data
            this.tableDataBaseInfo = result
            // console.log(geneSymbol)
            this.geneID = result[0]['gene_id']
            this.uniprotEntry = 'uniprotkb:' + result[0]['uniprot_entry']
            this.geneInfo = this.geneSymbol + ':' + this.geneID
            this.uniprotEntryID = result[0]['uniprot_entry']

            getInfoGeneScoreByGeneSymbol(geneSymbol)
              .then(responseGeneScore => {
                let ensemblID = ''
                let resultGeneScore = responseGeneScore.data
                // console.log(resultGeneScore[0])
                let patt1 = ''
                let dbXrefs = result[0]['dbxrefs'].split('|')
                let mim = ''
                let hgnc = ''
                let ensembl = ''
                dbXrefs.forEach(function(dbXref) {
                  patt1 = new RegExp('MIM')
                  if (patt1.test(dbXref)) {
                    mim = dbXref.split(':')
                    mim = ' | <a href="http://omim.org/entry/' + mim[1] + '" target="_blank" style="text-decoration:underline;">' + dbXref + '</a>'
                  }
                  patt1 = new RegExp('MIM')
                  if (patt1.test(dbXref)) {
                    hgnc = dbXref.split(':')
                    hgnc = ' | <a href="http://www.genenames.org/data/hgnc_data.php?hgnc_id=' + hgnc[1] + '" target="_blank" style="text-decoration:underline;">' + dbXref + '</a>'
                  }
                  patt1 = new RegExp('Ensembl')
                  if (patt1.test(dbXref)) {
                    ensembl = dbXref.split(':')
                    ensemblID = ensembl[1]
                    ensembl = ' | <a href="http://asia.ensembl.org/Homo_sapiens/Gene/Summary?g=' + ensembl[1] + '" target="_blank" style="text-decoration:underline;">' + dbXref + '</a>'
                  }
                })
                let geneCards = ' | <a href="http://www.genecards.org/cgi-bin/carddisp.pl?gene=' + result[0]['symbol'] + '" target="_blank" style="text-decoration:underline;">GeneCards:' + result[0]['symbol'] + '</a>'

                this.ensemblID = ensemblID

                this.tableDataBaseInfo = [{
                  name: 'Official Symbol',
                  content: result[0]['symbol']
                }, {
                  name: 'Official full name',
                  content: result[0]['full_name_from_nomenclature_authority']
                }, {
                  name: 'Location',
                  content: result[0]['map_location']
                }, {
                  name: 'Gene type',
                  content: result[0]['type_of_gene']
                }, {
                  name: 'Synonyms',
                  content: result[0]['synonyms']
                }, {
                  name: 'Quick links',
                  content: '<a href="http://www.ncbi.nlm.nih.gov/gene/?term=' + result[0]['gene_id'] + '" target="_blank" style="text-decoration:underline;">Entrez ID: ' + result[0]['gene_id'] + '</a>' + mim + hgnc + ensembl + geneCards
                }, {
                  name: 'Gene summary',
                  content: result[0]['summary'] + ' retrieved from ' + '<a href="http://www.uniprot.org/uniprot/' + result[0]['uniprot_entry'] + '#function" target="_blank" style="text-decoration:underline;"> uniprot: ' + result[0]['uniprot_entry'] + '</a>'
                }, {
                  name: 'Genic intolerance',
                  content: 'RVIS: ' + resultGeneScore[0]['Petrovski'].replace(/\(/, '\( rank in ') + '\n' +
                    'LoFtool: ' + resultGeneScore[0]['LoFtool'].replace(/\(/, '\( rank in ') + '\n' +
                    'GDI: ' + resultGeneScore[0]['GDI'].replace(/\(/, '\( rank in ') + '\n' +
                    'Episcore: ' + resultGeneScore[0]['Episcore'].replace(/\(/, '\( rank in ') + '\n' +
                    'heptanucleotide context intolerance score: ' + resultGeneScore[0]['Aggarwala'].replace(/\(/, '\( rank in ') + '\n' +
                    'pLi_EXAC: ' + resultGeneScore[0]['pLi_EXAC'].replace(/\(/, '\( rank in ')

                }]

                this.fetchDataGene2goHumanByGeneID(this.geneID, 1, 10)
                this.fetchDataPPIByUniprotEntry(this.uniprotEntry, 1, 10)
                this.fetchDataBiosystemsHumanByGeneID(this.geneID, 1, 10)
                this.fetchDataClinVarByGeneInfo(this.geneInfo, 1, 10)
                this.fetchDataDenovoDBByGeneSymbol(this.geneSymbol, 1, 10)
                this.fetchDataMGIByGeneID(this.geneID, 1, 10)
                this.fetchDataHPOByGeneID(this.geneID, 1, 10)
                this.fetchDataHomoloGeneByGeneID(this.geneID, 1, 10)
                this.fetchDataVariantDistributionByGeneSymbol(this.geneSymbol, this.alleleFreq, 1, 10)
                this.fetchDataDGIdbByGeneSymbol(this.geneSymbol, 1, 10)
                this.fetchDataUniprotByUniprotEntry(this.uniprotEntryID, 1, 10)
                this.fetchDataOMIMByGeneID(this.geneID, 1, 10)
                this.fetchDataSubcellularLocationByEnsemblID(this.ensemblID, 1, 10)
                this.fetchDataBSAvgExpressionByGeneSymbol(this.geneSymbol)
                this.fetchDataGTEx1ByEnsemblID(this.ensemblID)
                this.fetchDataGTEx2ByEnsemblID(this.ensemblID)
                this.fetchDataInterproHumanProteinDomainByUniprotEntry(this.uniprotEntryID, 1, 10)
              })
              .catch(() => {
                console.log('getInfoGeneScoreByGeneSymbol')
              })
          })
          .catch(() => {
            console.log('getInfoGeneInfoHSByGeneSymbol')
          })
      },

      fetchDataGene2goHumanByGeneID(geneID = this.geneID, page = this.current_pageGO, pageSize = this.pageSizeGO) {
        this.loadingGO = true
        getInfoGene2goHumanByGeneID(geneID, page, pageSize)
          .then(responseGene2goHuman => {
            let resultGene2goHuman = responseGene2goHuman.data
            // console.log(resultGene2goHuman.meta['total'])
            let arr = []
            let pubmed_id = ''
            resultGene2goHuman.forEach(function(data) {
              let pubmed_ids = data['pubmed_id'].split('|')
              pubmed_ids.forEach(function(pubmed_id0) {
                if (pubmed_ids.length > 1) {
                  pubmed_id = pubmed_id + ' <a href="http://www.ncbi.nlm.nih.gov/pubmed/' + pubmed_id0 + '" target="_blank" style="text-decoration:underline;">' + pubmed_id0 + '</a> |'
                } else if (pubmed_ids.length === 1 && pubmed_id0 !== '-') {
                  pubmed_id = pubmed_id + ' <a href="http://www.ncbi.nlm.nih.gov/pubmed/' + pubmed_id0 + '" target="_blank" style="text-decoration:underline;">' + pubmed_id0 + '</a> '
                } else {
                  pubmed_id = '-'
                }
              })
              arr.push({ 'go_id': data['go_id'], 'go_name': data['go_name'], 'envidence_key': data['envidence_key'], 'category': data['category'], 'pubmed_id': pubmed_id })
              pubmed_id = ''
            })
            this.tableDataGO = arr
            this.totalGO = responseGene2goHuman.total
            this.loadingGO = false
          })
          .catch(() => {
            console.log('fetchDataGene2goHumanByGeneID')
            this.loadingGO = false
          })
      },

      fetchDataPPIByUniprotEntry(uniprotEntry = this.uniprotEntry, page = this.current_pagePPI, pageSize = this.pageSizePPI) {
        this.loadingPPI = true
        getInfoPPIByUniprotEntry(uniprotEntry, page, pageSize)
          .then(response => {
            let result = response.data
            this.tableDataPPI = result
            this.totalPPI = response.total
            this.loadingPPI = false
          })
          .catch(() => {
            console.log('fetchDataPPIByUniprotEntry')
            this.loadingPPI = false
          });
      },

      fetchDataBiosystemsHumanByGeneID(geneID = this.geneID, page = this.current_pageBiosystemsHuman, pageSize = this.pageSizeBiosystemsHuman) {
        this.loadingBiosystemsHuman = true
        getInfoBiosystemsHumanByGeneID(geneID, page, pageSize)
          .then(response => {
            let result = response.data
            // console.log(result)
            this.tableDataBiosystemsHuman = result
            this.totalBiosystemsHuman = response.total
            this.loadingBiosystemsHuman = false
          })
          .catch(() => {
            console.log('fetchDataBiosystemsHumanByGeneID')
            this.loadingBiosystemsHuman = false
          });
      },


      fetchDataClinVarByGeneInfo(geneInfo = this.geneInfo, page = this.current_pageClinVar, pageSize = this.pageSizeClinVar) {
        this.loadingClinVar = true
        getInfoClinVarByGeneInfo(geneInfo, page, pageSize)
          .then(response => {
            let result = response.data
            // console.log(result)
            this.tableDataClinVar = result
            this.totalClinVar = response.total
            this.loadingClinVar = false
          })
          .catch(() => {
            console.log('fetchDataClinVarByGeneInfo')
            this.loadingClinVar = false
          });
      },

      fetchDataDenovoDBByGeneSymbol(geneSymbol = this.geneSymbol, page = this.current_pageDenovoDB, pageSize = this.pageSizeDenovoDB) {
        this.loadingDenovoDB = true
        getInfoDenovoDBByGeneSymbol(geneSymbol, page, pageSize)
          .then(response => {
            let result = response.data
            // console.log(result)
            this.tableDataDenovoDB = result
            this.totalDenovoDB = response.total
            this.loadingDenovoDB = false
          })
          .catch(() => {
            console.log('fetchDataDenovoDBByGeneSymbol')
            this.loadingDenovoDB = false
          });
      },

      fetchDataMGIByGeneID(geneID = this.geneID, page = this.current_pageMGI, pageSize = this.pageSizeMGI) {
        this.loadingMGI = true
        getInfoMGIByGeneID(geneID, page, pageSize)
          .then(response => {
            let result = response.data
            // console.log(result)
            this.tableDataMGI = result
            this.totalMGI = response.total
            this.loadingMGI = false
          })
          .catch(() => {
            console.log('fetchDataMGIByGeneID')
            this.loadingMGI = false
          });
      },

      fetchDataHPOByGeneID(geneID = this.geneID, page = this.current_pageHPO, pageSize = this.pageSizeHPO) {
        this.loadingHPO = true
        getInfoHPOByGeneID(geneID, page, pageSize)
          .then(response => {
            let result = response.data
            // console.log(result)
            this.tableDataHPO = result
            this.totalHPO = response.total
            this.loadingHPO = false
          })
          .catch(() => {
            console.log('fetchDataHPOByGeneID')
            this.loadingHPO = false
          });
      },
      fetchDataHomoloGeneByGeneID(geneID = this.geneID, page = this.current_pageHomoloGene, pageSize = this.pageSizeHomoloGene) {
        this.loadingHomoloGene = true
        getInfoHomoloGeneByGeneID(geneID, page, pageSize)
          .then(response => {
            // console.log(response)
            let result = response.data
            this.tableDataHomoloGene = result
            // console.log(result)
            this.totalHomoloGene = response.total
            this.loadingHomoloGene = false
          })
          .catch(() => {
            console.log('fetchDataHomoloGeneByGeneID')
            this.loadingHomoloGene = false
          });
      },

      selectChangeFetchDataVariantDistributionByGeneSymbol(alleleFreq) {
        this.fetchDataVariantDistributionByGeneSymbol(this.geneSymbol, alleleFreq, 1, 10)
      },

      fetchDataVariantDistributionByGeneSymbol(geneSymbol = this.geneSymbol, alleleFreq = this.alleleFreq, page = this.current_pageVariantDistribution, pageSize = this.pageSizeVariantDistribution) {
        this.loadingVariantDistribution = true
        getInfoVariantDistributionByGeneSymbol(geneSymbol, alleleFreq, page, pageSize)
          .then(response => {
            let result = response.data
            this.tableDataVariantDistribution = result
            this.totalVariantDistribution = response.total
            this.loadingVariantDistribution = false
          })
          .catch(() => {
            console.log('fetchDataVariantDistributionByGeneSymbol')
            this.loadingVariantDistribution = false
          });
      },


      fetchDataUniprotByUniprotEntry(uniprotEntry = this.uniprotEntryID, page = this.current_pageUniprot, pageSize = this.pageSizeUniprot) {
        this.loadingUniprot = true
        getInfoUniprotByUniprotEntry(uniprotEntry, page, pageSize)
          .then(response => {
            let result = response.data
            let CrossReference_BioGrid = ''
            result[0]['CrossReference_BioGrid'].split(';').forEach(function(val) {
              CrossReference_BioGrid = CrossReference_BioGrid + '<a href="https://thebiogrid.org/' + val + '/summary/homo-sapiens" target="_blank" style="text-decoration:underline;">BioGrid:' + val + '</a>'
            })

            let CrossReference_KEGG = ''
            result[0]['CrossReference_KEGG'].split(';').forEach(function(val) {
              CrossReference_KEGG = CrossReference_KEGG + ' | <a href="http://www.kegg.jp/dbget-bin/www_bget?' + val + '" target="_blank" style="text-decoration:underline;">KEGG:' + val + '</a>'
            })


            let CrossReference_Reactome = ''
            result[0]['CrossReference_Reactome'].split(';').forEach(function(val) {
              CrossReference_Reactome = CrossReference_Reactome + ' | <a href="http://www.reactome.org/content/detail/' + val + '" target="_blank" style="text-decoration:underline;">Reactome:' + val + '</a>'
            })
            let CrossReference_PDB = ''
            result[0]['CrossReference_PDB'].split(';').forEach(function(val) {
              CrossReference_PDB = CrossReference_PDB + ' | <a href="http://www.rcsb.org/pdb/explore/explore.do?structureId=' + val + '" target="_blank" style="text-decoration:underline;">PDB:' + val + '</a>'
            })
            let CrossReference_Pfam = ''
            result[0]['CrossReference_Pfam'].split(';').forEach(function(val) {
              CrossReference_Pfam = CrossReference_Pfam + ' | <a href="http://pfam.xfam.org/family/' + val + '" target="_blank" style="text-decoration:underline;">Pfam:' + val + '</a>'
            })

            this.tableDataUniprot = [{
              name: 'Entry',
              content: result[0]['Entry']
            }, {
              name: 'Entry name',
              content: result[0]['Entry_name']
            }, {
              name: 'Organism',
              content: result[0]['Organism']
            }, {
              name: 'Length',
              content: result[0]['Length']
            }, {
              name: 'Mass',
              content: result[0]['Mass']
            }, {
              name: 'Subunit structure',
              content: result[0]['Subunit_structure_CC']
            }, {
              name: 'Function',
              content: result[0]['Function_CC']
            }, {
              name: 'Tissue specificity',
              content: result[0]['Tissue_specificity']
            }, {
              name: 'Subcellular location',
              content: result[0]['Subcellular_location_CC']
            }, {
              name: 'Catalytic Activity',
              content: result[0]['Catalytic_activity']
            }, {
              name: 'Enzyme Regulation',
              content: result[0]['Enzyme_regulation']
            }, {
              name: 'Domain',
              content: result[0]['Domain_CC']
            }, {
              name: 'Sequence similarities',
              content: result[0]['Sequence_similarities']
            }, {
              name: 'Cross-reference',
              content: CrossReference_BioGrid + CrossReference_KEGG + CrossReference_Reactome + CrossReference_PDB + CrossReference_Pfam
            }]

            this.loadingUniprot = false
          })
          .catch(() => {
            console.log('fetchDataUniprotByUniprotEntry')
            this.loadingUniprot = false
          });
      },


      fetchDataDGIdbByGeneSymbol(geneSymbol = this.geneSymbol, page = this.current_pageDGIdb, pageSize = this.pageSizeDGIdb) {
        this.loadingDGIdb = true
        getInfoDGIdbByGeneSymbol(geneSymbol, page, pageSize)
          .then(response => {
            // console.log(response)
            let arr = []
            response.forEach(function(result) {
              let gene = result['searchTerm']
              let geneLongName =  result['geneLongName']
              let interactions =  result['interactions']
              interactions.forEach(function(val) {
                let drug = val['drugName']
                let sources = ''
                let sourcesTemp = val['sources']
                sourcesTemp.forEach(function(valSources) {
                  sources = sources + '<a href="http://www.dgidb.org/sources/' + valSources + '" target="_blank" style="text-decoration:underline;">' + valSources + '</a>'
                })
                let interactionType = val['interactionTypes'].join(',')
                let interactionId = val['interactionId']

                arr.push({ 'Gene': gene, 'Drug': drug, 'Interaction_Type': interactionType, 'Source': sources, 'InteractionId': interactionId })
              })
            })
            this.tableDataDGIdb = arr
            this.totalDGIdb = arr.length
            this.loadingDGIdb = false
          })
          .catch(() => {
            console.log('fetchDataDGIdbByGeneSymbol')
            this.loadingDGIdb = false
          });
      },

      fetchDataInterproHumanProteinDomainByUniprotEntry(uniprotEntry = this.uniprotEntryID, page = this.current_pageInterproHumanProteinDomain, pageSize = this.pageSizeInterproHumanProteinDomain) {
        this.loadingInterproHumanProteinDomain = true
        getInfoInterproHumanProteinDomainByUniprotEntry(uniprotEntry, page, pageSize)
          .then(response => {
            let result = response.data
            this.tableDataInterproHumanProteinDomain = result
            this.totalInterproHumanProteinDomain = response.total
            this.loadingInterproHumanProteinDomain = false
          })
          .catch(() => {
            console.log('fetchDataInterproHumanProteinDomainByUniprotEntry')
            this.loadingInterproHumanProteinDomain = false
          });
      },

      fetchDataOMIMByGeneID(geneID = this.geneID, page = this.current_pageOMIM, pageSize = this.pageSizeOMIM) {
        this.loadingOMIM = true
        getInfoOMIMByGeneID(geneID, page, pageSize)
          .then(response => {
            // console.log(response)
            let result = response.data
            this.tableDataOMIM = result
            this.totalOMIM = response.total
            this.loadingOMIM = false
          })
          .catch(() => {
            console.log('fetchDataOMIMByGeneID')
            this.loadingOMIM = false
          });
      },

      fetchDataSubcellularLocationByEnsemblID(ensemblID = this.ensemblID, page = this.current_pageSubcellularLocation, pageSize = this.pageSizeSubcellularLocation) {
        this.loadingSubcellularLocation = true
        getInfoSubcellularLocationByEnsemblID(ensemblID, page, pageSize)
          .then(response => {
            let result = response.data
            this.tableDataSubcellularLocation = [{
              name: 'Gene',
              content: result[0]['Gene']
            }, {
              name: 'Validated',
              content: result[0]['Validated']
            }, {
              name: 'Supported',
              content: result[0]['Supported']
            }, {
              name: 'Approved',
              content: result[0]['Approved']
            }, {
              name: 'Single-cell variation intensity',
              content: result[0]['Single_cell_variation_intensity']
            }, {
              name: 'Single-cell variation spatial',
              content: result[0]['Single_cell_variation_spatial']
            }, {
              name: 'Cell cycle dependency',
              content: result[0]['Cell_cycle_dependency']
            }, {
              name: 'GO',
              content: result[0]['GO_id']
            }]

            this.totalSubcellularLocation = response.total
            this.loadingSubcellularLocation = false
          })
          .catch(() => {
            console.log('fetchDataSubcellularLocationByEnsemblID')
            this.loadingSubcellularLocation = false
          });
      },


      fetchDataBSAvgExpressionByGeneSymbol(geneSymbol = this.geneSymbol) {
        this.loadingBSAvgExpression = true
        let tissue = ['A1C', 'AMY', 'CBC', 'DFC', 'HIP', 'IPC', 'ITC', 'M1C', 'MD', 'MFC', 'OFC', 'S1C', 'STC', 'STR', 'V1C', 'VFC']
        let ages = ['8_9pcw', '10_12pcw', '13_15pcw', '16_18pcw', '19_24pcw', '25_38pcw', '0_5mos', '6_18mos', '19mos_5yrs', '6_11yrs', '12_19yrs', '20_40yrs']
        getInfoBSAvgExpressionByGeneSymbol(geneSymbol)
          .then(response => {
            this.bs_ge = response

            let arr = []
            let i = 0
            for (let j = 0; j < 12; j++) {
              for (let k = 0; k < 16; k++) {
                if (this.bs_ge[i] === 'NA') {
                  arr[i] = [j, k, null]
                } else {
                  arr[i] = [j, k, parseFloat(this.bs_ge[i])]
                }
                i++
              }
            }
            this.heatmapOptions = {
              chart: {
                type: 'heatmap',
                marginTop: 40,
                marginBottom: 80,
                plotBorderWidth: 1
              },
              title: {
                text: 'Brain Span avg expression'
              },

              xAxis: {
                categories: ages
              },

              yAxis: {
                categories: tissue,
                title: null

              },

              colorAxis: {
                min: 0,
                minColor: '#FFFFFF',
                maxColor: Highcharts.getOptions().colors[0]
              },
              legend: {
                align: 'right',
                layout: 'vertical',
                margin: 0,
                verticalAlign: 'top',
                y: 25,
                symbolHeight: 280
              },

              tooltip: {
                formatter: function() {
                  return '<b>' +
                    this.series.xAxis.categories[this.point.x] +
                    '</b> sold <br><b>' +
                    this.point.value +
                    '</b> items on <br><b>' +
                    this.series.yAxis.categories[this.point.y] +
                    '</b>';
                }
              },
              series: [{
                name: 'Brain Span',
                borderWidth: 1,
                data: arr,
                // data: [[0, 0, 10], [0, 1, 19], [0, 2, 8], [0, 3, 24], [0, 4, 67], [1, 0, 92], [1, 1, 58], [1, 2, 78], [1, 3, 117], [1, 4, 48], [2, 0, 35], [2, 1, 15], [2, 2, 123], [2, 3, 64]],
                dataLabels: {
                  enabled: true,
                  color: '#000000'
                }
              }]
            }
            this.loadingBSAvgExpression = false
          })
          .catch(() => {
            console.log('fetchDataBSAvgExpressionByGeneSymbol')
            this.loadingBSAvgExpression = false
          });
      },

      fetchDataGTEx1ByEnsemblID(ensemblID = this.ensemblID) {
        getInfoGTEx1ByEnsemblID(ensemblID)
          .then(response => {
            let result = response
            this.tissueErrorBar = response.tissue
            this.expr = response.mean
            this.sd = response.sd

            this.errorBarOptionsGTEx1 = {
              chart: {
                style: {
                  fontFamily: "Helvetica Neue",
                  fontSize: '12px',
                  fontWeight: 'bold',
                },
                //plotBackgroundColor: '#455D7A',
                inverted: false,
                zoomType: 'xy'
              },

              title: {
                useHTML: true,
                text: 'Tissue specific expression retrived from GTEx'
              },

              xAxis: [{
                //categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                categories: this.tissueErrorBar,
                labels: {
                  style: {
                    //color: 'black',//颜色
                    fontSize: '14px'//字体
                  }
                }
              }],

              yAxis: [{ // Secondary yAxis
                title: {
                  text: 'mean RPKM',
                }
              }],

              tooltip: {
                shared: true
              },

              credits: {
                enabled: false // 禁用版权信息
              },

              exporting: {
                enabled: false
              },

              legend: {
                enabled: false // 禁用图例
              },

              series: [{
                name: 'mean RPKM',
                type: 'column',
                color: '#233142',
                data: this.expr,
                //data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4],
                tooltip: {
                  pointFormat: '<span style="font-weight: bold;">{series.name}</span>: <b>{point.y:.2f} </b> '
                }
              }, {
                name: 'expression sd',
                type: 'errorbar',
                color: '#233142',
                data: this.sd,
                //data: [[48, 51], [68, 73], [92, 110], [128, 136], [140, 150], [171, 179], [135, 143], [142, 149], [204, 220], [189, 199], [95, 110], [52, 56]],
                tooltip: {
                  pointFormat: '(±sd : {point.low:.2f} - {point.high:.2f})<br/>'
                }
              }]
            }

          })
          .catch(() => {
            console.log('fetchDataGTEx1ByEnsemblID')
          });
      },

      fetchDataGTEx2ByEnsemblID(ensemblID = this.ensemblID) {
        getInfoGTEx2ByEnsemblID(ensemblID)
          .then(response => {
            let result = response

            this.tissueErrorBar = response.tissue
            this.expr = response.mean
            this.sd = response.sd

            this.errorBarOptionsGTEx2 = {
              chart: {
                height: 1200,
                style: {
                  fontFamily: 'Helvetica Neue',
                  fontSize: '12px',
                  fontWeight: 'bold',
                },
                //plotBackgroundColor: '#455D7A',
                inverted: true,
                zoomType: 'xy'
              },

              title: {
                useHTML: true,
                text: 'Tissue specific expression retrived from GTEx'
              },

              xAxis: [{
                //categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
                categories: this.tissueErrorBar,
                labels: {
                  style: {
                    //color: 'black',//颜色
                    fontSize: '14px'//字体
                  }
                }
              }],

              yAxis: [{ // Secondary yAxis
                title: {
                  text: 'mean RPKM',
                }
              }],

              tooltip: {
                shared: true
              },

              credits: {
                enabled: false // 禁用版权信息
              },

              exporting: {
                enabled: false
              },

              legend: {
                enabled: false // 禁用图例
              },

              series: [{
                name: 'mean RPKM',
                type: 'column',
                color: '#233142',
                data: this.expr,
                //data: [49.9, 71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5, 216.4, 194.1, 95.6, 54.4],
                tooltip: {
                  pointFormat: '<span style="font-weight: bold;">{series.name}</span>: <b>{point.y:.2f} </b> '
                }
              }, {
                name: 'expression sd',
                type: 'errorbar',
                color: '#233142',
                data: this.sd,
                //data: [[48, 51], [68, 73], [92, 110], [128, 136], [140, 150], [171, 179], [135, 143], [142, 149], [204, 220], [189, 199], [95, 110], [52, 56]],
                tooltip: {
                  pointFormat: '(±sd : {point.low:.2f} - {point.high:.2f})<br/>'
                }
              }]
            }
          })
          .catch(() => {
            console.log('fetchDataGTEx2ByEnsemblID')
          });
      },


      // 分页功能
      paginationGO(val) {
        this.current_pageGO = val
        this.fetchDataGene2goHumanByGeneID()
      },
      sizeChangeGO(val) {
        this.pageSizeGO = val
        this.fetchDataGene2goHumanByGeneID()
      },

      // 分页功能
      paginationMGI(val) {
        this.current_pageMGI = val
        this.fetchDataMGIByGeneID()
      },
      sizeChangeMGI(val) {
        this.pageSizeMGI = val
        this.fetchDataMGIByGeneID()
      },

      // 分页功能
      paginationInterproHumanProteinDomain(val) {
        this.current_pageInterproHumanProteinDomain = val;
        this.fetchDataInterproHumanProteinDomainByUniprotEntry();
      },
      sizeChangeInterproHumanProteinDomain(val) {
        this.pageSizeInterproHumanProteinDomain = val
        this.fetchDataInterproHumanProteinDomainByUniprotEntry();
      },

      // 分页功能
      paginationPPI(val) {
        this.current_pagePPI = val;
        this.fetchDataPPIByUniprotEntry();
      },
      sizeChangePPI(val) {
        this.pageSizePPI = val
        this.fetchDataPPIByUniprotEntry();
      },
      // 分页功能
      paginationBiosystemsHuman(val) {
        this.current_pageBiosystemsHuman = val;
        this.fetchDataBiosystemsHumanByGeneID();
      },
      sizeChangeBiosystemsHuman(val) {
        this.pageSizeBiosystemsHuman = val
        this.fetchDataBiosystemsHumanByGeneID();
      },

      // 分页功能
      paginationClinVar(val) {
        this.current_pageClinVar = val;
        this.fetchDataClinVarByGeneInfo();
      },
      sizeChangeClinVar(val) {
        this.pageSizeClinVar = val
        this.fetchDataClinVarByGeneInfo();
      },

      // 分页功能
      paginationDenovoDB(val) {
        this.current_pageDenovoDB = val;
        this.fetchDataDenovoDBByGeneSymbol();
      },
      sizeChangeDenovoDB(val) {
        this.pageSizeDenovoDB = val
        this.fetchDataDenovoDBByGeneSymbol();
      },

      // 分页功能
      paginationHPO(val) {
        this.current_pageHPO = val;
        this.fetchDataHPOByGeneID();
      },
      sizeChangeHPO(val) {
        this.pageSizeHPO = val
        this.fetchDataHPOByGeneID();
      },

      // 分页功能
      paginationSubcellularLocation(val) {
        this.current_pageSubcellularLocation = val;
        this.fetchDataSubcellularLocationByEnsemblID();
      },
      sizeChangeSubcellularLocation(val) {
        this.pageSizeSubcellularLocation = val
        this.fetchDataSubcellularLocationByEnsemblID();
      },

      // 分页功能
      paginationOMIM(val) {
        this.current_pageOMIM = val;
        this.fetchDataOMIMByGeneID();
      },
      sizeChangeOMIM(val) {
        this.pageSizeOMIM = val
        this.fetchDataOMIMByGeneID();
      },
      // 分页功能
      paginationHomoloGene(val) {
        this.current_pageHomoloGene = val;
        this.fetchDataHomoloGeneByGeneID();
      },
      sizeChangeHomoloGene(val) {
        this.pageSizeHomoloGene = val
        this.fetchDataHomoloGeneByGeneID();
      },

      // 分页功能
      paginationDGIdb(val) {
        this.current_pageDGIdb = val;
        this.fetchDataDGIdbByGeneSymbol();
      },
      sizeChangeDGIdb(val) {
        this.pageSizeDGIdb = val
        this.fetchDataDGIdbByGeneSymbol();
      },

      // 分页功能
      paginationUniprot(val) {
        this.current_pageUniprot = val;
        this.fetchDataUniprotByUniprotEntry();
      },
      sizeChangeUniprot(val) {
        this.pageSizeUniprot = val
        this.fetchDataUniprotByUniprotEntry();
      },

      // 分页功能
      paginationVariantDistribution(val) {
        this.current_pageVariantDistribution = val;
        this.fetchDataVariantDistributionByGeneSymbol();
      },
      sizeChangeVariantDistribution(val) {
        this.pageSizeVariantDistribution = val
        this.fetchDataVariantDistributionByGeneSymbol();
      },

      clearFilter() {
        this.$refs.filterTable.clearFilter();
      },
      getGeneSymbolExample: function() {
        this.inputValue = "PINK1\n" +
          "LRRK2\n" +
          "SNCA";
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

    },
    beforeCreate() {
    },
    created() {
      this.getParams()
      this.fetchDataBaseInfoByGeneSymbol(this.geneSymbol)
    }
  }
</script>
