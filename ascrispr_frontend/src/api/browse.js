import fetch from '@/utils/fetch'

export function getInfoGeneStatisticsByLetter(letter, page = 1, pageSize = 10) {
  return fetch({
    url: '/api/geneStatistics/letter',
    method: 'post',
    data: {
      page,
      pageSize,
      letter
    }
  })
}

// export function getInfoGeneStatisticsByLetter(rare_variant_extremeN, rare_variant_extremeNsnv, rare_variant_extremeY_07, rare_variant_extremeY_lof, common_variant_range1, , , , , , , , , , ,) {
//   return fetch({
//     url: '/api/geneStatistics/letter',
//     method: 'post',
//     data: {
//       rare_variant_extremeN,
//       rare_variant_extremeNsnv,
//       rare_variant_extremeY_07,
//       rare_variant_extremeY_lof,
//       common_variant_range1,
//       common_variant_range2,
//       common_variant_range3,
//       cnv_score,
//       gene_expression_range1,
//       gene_expression_range2,
//       gene_expression_range3,
//       dna_methylation_range1,
//       dna_methylation_range2,
//       dna_methylation_range3
//     }
//   })
// }
