<template>
    <div class="grid-content">
        <el-row>
            <el-col :span="2"><div class="">&nbsp;</div></el-col>
            <el-col :span="20">
                <div style="font-size: 18px;">
                    <el-row>
                        <p style="font-size: 30px; color: rgb(216, 164, 6);margin-bottom: 0">References </p>
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
                            <div class="collapse_title_bg_color_Warning" style="font-size: 24px;">&nbsp;&nbsp;Studies that employed allele-specific CRISPR:</div>
                        </template>
                        <el-card class="box-card">
                            <ol>

                                <P>
                                    <strong>
                                        <li>Wu, J.J., et al., Allele-specific genome targeting in the development of precision medicine. Theranostics, 2020. 10(7): p. 3118-3137.</li>
                                    </strong>
                                </P>


                                <P>
                                    <strong>
                                        <li>Burnight, E.R., et al., Using CRISPR-Cas9 to Generate
                                            Gene-Corrected Autologous iPSCs for the Treatment of Inherited
                                            Retinal Degeneration. Mol Ther, 2017. 25(9): p. 1999-2013.</li>
                                    </strong>
                                </P>

                                <P>
                                    <strong>
                                        <li>Capon, S.J., et al., Utilising polymorphisms to achieve
                                            allele-specific genome editing in zebrafish. Biol Open, 2017.
                                            6(1): p. 125-131.</li>
                                    </strong>
                                </P>

                                <P>
                                    <strong>
                                        <li>Christie, K.A., et al., Towards personalised allele-specific
                                            CRISPR gene editing to treat autosomal dominant disorders. Sci
                                            Rep, 2017. 7(1): p. 16174.</li>
                                    </strong>
                                </P>

                                <P>
                                    <strong>
                                        <li>Courtney, D.G., et al., CRISPR/Cas9 DNA cleavage at
                                            SNP-derived PAM enables both in vitro and in vivo KRT12
                                            mutation-specific targeting. Gene Ther, 2016. 23(1): p. 108-112.

                                        </li>
                                    </strong>
                                </P>

                                <P>
                                    <strong>
                                        <li>Fujita, T., M. Yuno, and H. Fujii, Allele-specific locus
                                            binding and genome editing by CRISPR at the p16INK4a locus. Sci
                                            Rep, 2016. 6: p. 30485.</li>
                                    </strong>
                                </P>

                                <P>
                                    <strong>
                                        <li>Giannelli, S.G., et al., Cas9/sgRNA selective targeting of
                                            the P23H Rhodopsin mutant allele for treating retinitis
                                            pigmentosa by intravitreal AAV9.PHP.B-based delivery. Hum Mol
                                            Genet, 2018. 27(5): p. 761-779.</li>
                                    </strong>
                                </P>

                                <P>
                                    <strong>
                                        <li>Shin, J.W., et al., Permanent inactivation of Huntington's
                                            disease mutation by personalized allele-specific CRISPR/Cas9.
                                            Hum Mol Genet, 2016. 25(20): p. 4566-4576.</li>
                                    </strong>
                                </P>

                                <P>
                                    <strong>
                                        <li>Koo, T., et al., Selective disruption of an oncogenic mutant
                                            allele by CRISPR/Cas9 induces efficient tumor regression.
                                            Nucleic Acids Res, 2017. 45(13): p. 7897-7908.</li>
                                    </strong>
                                </P>

                                <P>
                                    <strong>
                                        <li>Monteys, A.M., et al., CRISPR/Cas9 Editing of the Mutant
                                            Huntingtin Allele In Vitro and In Vivo. Mol Ther, 2017. 25(1):
                                            p. 12-23.</li>
                                    </strong>
                                </P>

                                <P>
                                    <strong>
                                        <li>Shinkuma, S., Z. Guo, and A.M. Christiano, Site-specific
                                            genome editing for correction of induced pluripotent stem cells
                                            derived from dominant dystrophic epidermolysis bullosa. Proc
                                            Natl Acad Sci U S A, 2016. 113(20): p. 5676-5681.</li>
                                    </strong>
                                </P>

                                <P>
                                    <strong>
                                        <li>Smith, C., et al., Efficient and allele-specific genome
                                            editing of disease loci in human iPSCs. Mol Ther, 2015. 23(3):
                                            p. 570-577.</li>
                                    </strong>
                                </P>

                                <P>
                                    <strong>
                                        <li>Wu, Y., et al., Correction of a genetic disease in mouse via
                                            use of CRISPR-Cas9. Cell Stem Cell, 2013. 13(6): p. 659-662.</li>
                                    </strong>
                                </P>

                                <P>
                                    <strong>
                                        <li>Yamamoto, Y., et al., Allele-specific ablation rescues
                                            electrophysiological abnormalities in a human iPS cell model of
                                            long-QT syndrome with a CALM2 mutation. Hum Mol Genet, 2017.
                                            26(9): p. 1670-1677.</li>
                                    </strong>
                                </P>

                                <P>
                                    <strong>
                                        <li>Yoshimi, K., et al., Allele-specific genome editing and
                                            correction of disease-associated phenotypes in rats using the
                                            CRISPR-Cas platform. Nat Commun, 2014. 5: p. 4240.</li>
                                    </strong>
                                </P>

                                <P>
                                    <strong>
                                        <li>Li, Y., et al., Exploiting the CRISPR/Cas9 PAM Constraint for
                                            Single-Nucleotide Resolution Interventions. PLoS One, 2016.
                                            11(1): p. e0144970.</li>
                                    </strong>
                                </P>

                                <P>
                                    <strong>
                                        <li>Li P., et al., Allele-Speciﬁc CRISPR-Cas9 Genome Editing of
                                            the Single-Base P23H Mutation for Rhodopsin-Associated Dominant
                                            Retinitis Pigmentosa. The CRISPR Journal, 2018. 1(1): p. 55-64.

                                        </li>
                                    </strong>
                                </P>
                                <P>
                                    <strong>
                                        <li>Gao, X., et al., Treatment of autosomal dominant hearing loss
                                            by in vivo delivery of genome editing agents. Nature, 2018.
                                            553(7687): p. 217-221.</li>
                                    </strong>
                                </P>
                                <P>
                                    <strong>
                                        <li>Matharu, N., et al., CRISPR-mediated activation of a promoter or enhancer rescues obesity caused by haploinsufficiency. Science, 2019. 363(6424). pii: eaau0629.</li>
                                    </strong>
                                </P>

                                <P>
                                    <strong>
                                        <li>Xu, H., et al., Targeted disruption of HLA genes via CRISPR-Cas9 generates iPSCs with enhanced immune compatibility. Cell Stem Cell, 2019. 24(4): p. 566-578.e7</li>
                                    </strong>
                                </P>

                                <P>
                                    <strong>
                                        <li>ARG de Vries., et al., Allele-specific genome editing using CRISPR is associated with loss of heterozygosity. Nucleic Acids Res, 2018. 47(3): p.1362–1372</li>
                                    </strong>
                                </P>

                                <P>
                                    <strong>
                                        <li>Rabai, A., et al., Allele-specific CRISPR/Cas9 correction of a heterozygous DNM2 mutation rescues centronuclear myopathy cell phenotypes. Mol Ther Nucleic Acids, 2019. 16: p. 246-256.</li>
                                    </strong>
                                </P>

                                <P>
                                    <strong>
                                        <li>Chao, T., et al., Precise and rapid validation of candidate gene by allele specific knockout with CRISPR/Cas9 in wild mice. Front Genet, 2019. 10: 124.</li>
                                    </strong>
                                </P>

                                <P>
                                    <strong>
                                        <li>Gyorgy, B., et al., Allele-specific gene editing prevents deafness in a model of dominant progressive hearing loss. Nat Med, 2019. 25(7): p. 1123-1130.</li>
                                    </strong>
                                </P>
                                <P>
                                    <strong>
                                        <li>Gao, Q. Q., et al., Selective targeting of the oncogenic KRAS G12S mutant allele by CRISPR/Cas9 induces efficient tumor regression. Theranostics, 2020. 10(11): p. 5137-5153.</li>
                                    </strong>
                                </P>

                            </ol>
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
        activeNames: ['1']
      }
    },
    mounted() {
    }
  }
</script>
