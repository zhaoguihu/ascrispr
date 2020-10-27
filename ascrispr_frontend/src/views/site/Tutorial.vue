<template>
    <div class="grid-content">
        <el-row>
            <el-col :span="2"><div class="">&nbsp;</div></el-col>
            <el-col :span="20">
                <div style="font-size: 18px;">
                    <el-row>
                        <p style="font-size: 30px; color: rgb(216, 164, 6);margin-bottom: 0">Tutorial </p>
                    </el-row>

                    <el-row>
                        <el-col :span="8"><div><a href="uploads/AsCRISPR_Tutorial.pdf" style="text-decoration:underline;" target="_blank">PDF version</a></div></el-col>
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
                            <div class="collapse_title_bg_color_Warning" style="font-size: 24px;">&nbsp;&nbsp;About AsCRISPR</div>
                        </template>
                        <el-card class="box-card">
                            <h2 class='tutorial'>About AsCRISPR</h2>
                            <p><span style="font-size:18px"><strong>AsCRISPR</strong> (<strong><u>A</u></strong>llele-<strong><u>s</u></strong>pecific <strong>CRISPR</strong>)
                                is a web tool for designing guide sequences for allele-specific CRISPR genome engineering. It is developed to aid the design of guide sequences during the allele-specific gene engineering of dominant inherited diseases, and can be further exploited in many other versatile applications, such as treating genome imprinting and haploinsufficiency, spatiotemporal loci imaging and immunocompatible manipulations. For dominantly inherited diseases, patients inherit one pathogenic allele causing a disease phenotype, especially in a dominant-negative manner, and one normal allele as well. Treatment strategies typically involve silencing the mutated allele, whereas expression of the wild-type (WT) allele remains unaffected and is sufficient for normal functions to prevent the onset of the disease. Allele-speciﬁc genome editing by CRISPR is a promising means to treat those diseases by selectively targeting the mutated genes or disease-causing single nucleotide polymorphisms (SNPs). Notably, in the era of personalized medicine, naturally occurring SNPs that alter the protospacer adjacent motifs (PAMs) or spacer sequences recognized by CRISPR nucleases would also provide a point of entry for patient-specific gene therapy.</span></p>
                            <!--<p style="font-size:18px">Basically, mutations/SNPs-derived novel PAMs can confer stringent allele-specific targeting, and also guide sequences with mutations/SNPs located especially within their seed sequence can also distinguish between two alleles. <strong>AsCRISPR</strong> is developed to aid the design of guide sequences during the allele-specific gene engineering of dominant inherited diseases, and can be further broadened to help to study the potential functions of SNPs by allele-specific targeting. It can process with either input sequences harboring single-base mutations and insertion-deletion mutations (indels), or heterozygous SNPs deposited in the dbSNP database. Multiple CRISPR nucleases including SpCas9, SaCas9, Cpf1 and their engineered variants are included for users’ choice. <strong>AsCRISPR</strong> can then return the candidate guide sequences that may selectively target one of the alleles, and further evaluate their on-target efficiencies, specificity efficiencies and potential off-targets throughout the genome, and it can also display the restriction enzyme sites which might be disrupted after gene targeting.</p>-->
                            <p style="font-size:18px">Basically, mutations/SNPs-derived novel PAMs can confer stringent allele-specific targeting, and also guide sequences with mutations/SNPs located especially within their seed sequence can also distinguish between two alleles.
                                <strong>AsCRISPR</strong> can process with either user-identified sequences harboring single-base mutations and short insertion-deletion mutations (indels), or heterozygous SNPs deposited in the dbSNP database. Multiple CRISPR nucleases including SpCas9, SaCas9, Cpf1, Cas12b, CasX and their engineered variants are included for users’ choice.
                                <strong>AsCRISPR</strong> can then return the candidate guide sequences that may selectively target one of the alleles, and further evaluate their on-target efficiencies, specificity efficiencies and potential off-targets throughout the genome, and it can also display the restriction enzyme sites that might be disrupted after gene targeting, which is of great use for clonal screening.</p>
                            <p style="font-size:18px">Moreover, <strong>AsCRISPR</strong> analyzed with dominant SNP sites retrieved from ClinVar and OMIM and provided a <strong>Dominant Database</strong> of candidate targetable sgRNAs, which may specifically target the alternative allele for each dominant SNP site. Besides, <strong>AsCRISPR</strong> also established a <strong>Validated Database</strong> that deposited all experimentally validated allele-specific sgRNAs for treating genetic diseases.</p>

                        </el-card>
                    </el-collapse-item>

                </el-collapse>

                <el-collapse v-model="activeNames">
                    <el-collapse-item name="2">
                        <div id="anchor-2" style="font-size: 24px;"></div>
                        <template slot="title" >
                            <div class="collapse_title_bg_color_Warning" style="font-size: 24px;">&nbsp;&nbsp;How to use AsCRISPR?</div>
                        </template>
                        <el-card class="box-card">
                            <p style="font-size:18px"><strong>Input</strong></p>
                            <p style="font-size:18px">Input type: The inputs can be either DNA sequences or SNP ID in the dbSNP database.</p>
                            <p style="font-size:18px">The input DNA sequence requires minimum 63 bp in length, with at least 31 bp flanking the mutation/variation site. The format of input DNA sequence would be: N31[N1/N2]N31, in which N1 denotes the sequences in the wildtype allele and N2 denotes the sequences in the mutated or varied allele. For example:</p>
                            <p style="font-size:18px"><strong class="text-success">Human RHO p.P23H</strong></p>
                            <p style="font-size:18px"><strong class="text-success">TTCTCCAATGCGACGGGTGTGGTACGCAGCC[C/A]CTTCGAGTACCCACAGTACTACCTGGCTGAG</strong></p>
                            <p style="font-size:18px"><strong class="text-success">Human TOR1A p.ΔE302/303</strong></p>
                            <p style="font-size:18px"><strong class="text-success">TGATGAAGACATTGTAAGCAGAGTGGCTGAG[GAG/-]ATGACATTTTTCCCCAAAGAGGAGAGAGTTT</strong></p>
                            <p style="font-size:18px">
                                Alternatively, users can input an SNP ID including “rs”: rs62621675 or rs28937568, and click “Get Sequences”. <strong>AsCRISPR</strong> then displays the SNP site and retrieve its flanking sequences, in the format of N31[N1/N2]N31 for users’ choice and further analysis. For example:
                                </p>

                            <p style="font-size:18px"><strong class="text-success">rs62621675</strong></p>
                            <p style="font-size:18px"><strong class="text-success">ACGGGAGTGTCCTCTCAACTGCTCTGTCGCT[C/G]TGTCTCTAACGACCACATGGGGGAAAGGTTT</strong></p>

                            <!--<p class="text-success" style="font-size:20px">Input:</p>-->
                            <img :src="require('@/assets/img/ascrispr/Tutorial_Picture_1.png')" class="img-responsive align-content-md-center" alt="img">
                            <p style="font-size:20px"><strong>&nbsp;</strong></p>
                            <img :src="require('@/assets/img/ascrispr/Tutorial_Picture_2.png')" class="img-responsive align-content-md-center" alt="img">

                            <p style="font-size:18px">
                                Moreover, when users are in a query with an SNP ID, <strong>AsCRISPR</strong> will also display the SNP sites located at each flanking 31 nucleotides, which would be informative for personalized gene targeting.
                                </p>
                            <img :src="require('@/assets/img/ascrispr/Tutorial_Picture_3.png')" class="img-responsive align-content-md-center" alt="img">
                            <!--<p class="text-success" style="font-size:20px">Output of guide sequences:</p>-->
                            <!--<img :src="require('@/assets/img/ascrispr/Tutorial_Picture_3.1.png')" class="img-responsive align-content-md-center" alt="img">-->
                            <!--<img :src="require('@/assets/img/ascrispr/Tutorial_Picture_3.2.png')" class="img-responsive align-content-md-center" alt="img">-->
                            <!--<img :src="require('@/assets/img/ascrispr/Tutorial_Picture_3.3.png')" class="img-responsive align-content-md-center" alt="img">-->

                            <p style="font-size:18px">So far, <strong>AsCRISPR</strong> has integrated the genomes of Homo sapiens (hg19/GRCh37), Homo sapiens (hg38/GRCh38) and Mus musculus (mm10/GRCm38). <strong>We will definitely add more genomes for analysis in the near future.</strong></p>

                            <p style="font-size:20px"><strong>Searching Criteria</strong></p>
                            <!--<p class="text-success" style="font-size:20px">Output of off-targets:</p>-->
                            <p style="font-size:18px">
                                To identify guide sequences that may discriminate between two alleles,
                                <strong>AsCRISPR</strong> explores both alleles and figures out if (i) mutations/SNPs give rise to novel PAMs, which confer stringent allele-specific targeting, and (ii) mutations/SNPs locate within the seed sequence of guide sequences. The lengths of seed sequences are set as follows:</p>

                            <ul style="font-size:18px">
                                <li>SpCas9, SaCas9 and their variants: 12 bp adjacent to a PAM </li>
                                <li>AsCpf1, LbCpf1 and their variants: 6 bp adjacent to a PAM</li>
                                <li>FnCpf1: 5 bp adjacent to a PAM</li>
                                <li>Cas12b and its variants: 6 bp adjacent to a PAM</li>
                                <li>CasX and its variants: 6 bp adjacent to a PAM</li>
                            </ul>
                            <p style="font-size:18px">** Notably, previous studies have shown that <strong>Cas12b and CasX exhibit greater speciﬁcity and possess broader seed sequence</strong>, therefore the output number of allele-specific guide sequences under the criteria of 6 bp maximum seed sequence will actually be greatly underestimated.</p>

                            <p style="font-size:20px"><strong>Output of Guide Sequences</strong></p>
                            <p style="font-size:18px">By filtering comparison results, <strong>AsCRISPR</strong> finally outputs candidate guide sequences that may target only one allele. The default output length of spacer sequences is set as 20-24 bp for different Cas9; 23 bp for AsCpf1/LbCpf1; 21 bp for FnCpf1; 23 bp for Cas12b; and 20 bp for CasX.</p>
                            <p style="font-size:18px"><strong>AsCRISPR</strong> ranks all guide sequences first by listing those with the same PAM back-to-back for better visualization. Alternatively, users can also freely re-rank their results according to their on-target efficiencies, off-targets or other properties, simply by clicking on the top of each category of the result table.</p>
                            <img :src="require('@/assets/img/ascrispr/Tutorial_Picture_4.png')" class="img-responsive align-content-md-center" alt="img" width="100%">

                            <p style="font-size:18px">To better display the differential points between the two alleles, in the result sheet, single-base mutations/SNPs are labeled in red and turned into lowercase, either located at the novel PAMs or spacer sequences. And for indel mutations, deleted nucleotides are replaced with asterisks (<span style="color: red">*</span>) and labeled in red.</p>
                            <img :src="require('@/assets/img/ascrispr/Tutorial_Picture_5.png')" class="img-responsive align-content-md-center" alt="img" width="100%">

                            <p style="font-size:18px">Notably, guide sequences will be reminded as "Not recommended" if (1) their GC contents are beyond 20%~80%; (2) containing four or more consecutive T, which might terminate the U6 or U3 promoter-drived transcription.</p>
                            <img :src="require('@/assets/img/ascrispr/Tutorial_Picture_6.png')" class="img-responsive align-content-md-center" alt="img" width="100%">
                            <p style="font-size:20px"><strong>&nbsp;</strong></p>
                            <img :src="require('@/assets/img/ascrispr/Tutorial_Picture_7.png')" class="img-responsive align-content-md-center" alt="img" width="100%">

                            <p style="font-size:20px"><strong>On-target Efficiencies</strong></p>
                            <p style="font-size:18px">The on-target efficiencies were calculated with multiple reported algorithms and were normalized to 0-1. For SpCas9, efficiency scores were predicted according to Xu et al., 2015; Doench et al., 2016; Moreno-Mateos et al., 2015; and Listgarden et al., 2018. For SaCas9, efficiency scores were predicted according to Najm et al., 2018. And for Cpf1, efficiency scores were predicted according to Kim et al., 2018. For Cas12b and CasX, efficiency scores were also predicted by DeepCpf1 according to Kim et al., 2018. <strong>Note that Cas12b and CasX may have special efﬁciency scores which are different from those of Cas9 and Cpf1</strong>, due to the lack of proved algorithms. Thus, those output DeepCpf1 scores will very likely not be applicable for them. We will definitely update the scoring system whenever there exists a convincing algorithm (or if you know one) to predict the on-target efficiencies of Cas12b and CasX.</p>
                            <p style="font-size:18px"><strong>Nevertheless, the on-target efficiency scores provided here actually may only be used for on-target activity predictions.</strong> If you have to choose certain guides for allele-specific targeting, you may choose and ignore those efﬁciency scores. However, if there are many speciﬁc guides for choice, you may look at and compare them with multiple properties, in which higher efﬁciency scores may be considered.</p>
                            <p style="font-size:18px">** Notably, the analysis of query sequences that are not existed in any reference genome will not produce on-target efficiencies.</p>

                            <p style="font-size:20px"><strong>Off-targets and Specificity</strong></p>
                            <p style="font-size:18px">The potential off-target sequences throughout the genome are searched by 3-base mismatches maximum. <strong>AsCRISPR</strong> lists the number of off-targets for each guide sequence with 0, 1, 2 or 3 mismatches (0-1-2-3). Clicking on the (0-1-2-3) will reveal more information about the off-target information, including the locations (exon, intron or intergenic region), sequence mismatches and so forth.</p>
                            <img :src="require('@/assets/img/ascrispr/Tutorial_Picture_8.png')" class="img-responsive align-content-md-center" alt="img" width="100%">
                            <p style="font-size:18px">The specificity score measures the uniqueness of a guide sequence in the genome. The higher the specificity score, the lower are off-target effects. Specificity scores were calculated based on Hsu scores by Hsu et al., 2013 and CFD scores by Donech et al., 2016. For Cpf1, Cas12b and CasX, no off-target ranking algorithms were available in the literatures so far, instead we just applied Hsu and CFD scores to their off-targets. We will definitely update the scoring system whenever there exists a convincing algorithm (or if you know one) to predict the off-target effects for Cpf1, Cas12b and CasX.</p>
                            <img :src="require('@/assets/img/ascrispr/Tutorial_Picture_9.png')" class="img-responsive align-content-md-center" alt="img" width="100%">
                            <p style="font-size:18px;color: red">** Note: When processing with the guide sequences from varied/alternative alleles, it is highly possible that one of those off-targets (with only 1-base mismatch) would be exactly the sequence of their corresponding WT/reference alleles. This will compromise the specificity scores of guide sequences from varied/alternative alleles to some extent (see the above Figure).</p>

                            <p style="font-size:20px"><strong>Restriction Enzyme Sites</strong></p>
                            <p style="font-size:18px"><strong>AsCRISPR</strong> can also display the restriction enzyme sites along the spacer sequences, which might be disrupted after gene targeting, and further determine whether those candidate enzymes are specifically targeted either allele. This provides an important tool for RFLP-based characterization and screening for targeted single colonies.</p>
                            <img :src="require('@/assets/img/ascrispr/Tutorial_Picture_10.png')" class="img-responsive align-content-md-center" alt="img" width="100%">

                            <p style="font-size:20px"><strong>Dominant Database</strong></p>
                            <p style="font-size:18px"><strong>AsCRISPR</strong> collected dominant SNP sites from ClinVar and OMIM databases for allele-specific analysis, and generated a <strong>Dominant Database</strong> depositing candidate targetable sgRNAs, which may specifically target the alternative allele for each dominant SNP site. Users can search by Disease name, Gene name or rsID for targetable site.</p>
                            <div style="alignment: center">
                                <img :src="require('@/assets/img/ascrispr/Tutorial_Picture_11.png')" alt="img" width="50%">
                            </div>

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
  import SiteHeader from '@/views/layouts/partials/TheSiteHeader.vue'
  import SiteFooter from '@/views/layouts/partials/TheSiteFooter.vue'

  export default {
    components: {
      SiteHeader,
      SiteFooter
    },
    data() {
      return {
        activeNames: ['1', '2']
      }
    },
    mounted() {
    }

  }
</script>
