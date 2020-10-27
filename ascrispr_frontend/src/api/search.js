import fetch from '@/utils/fetch'

export function getGeneSymbolRegionRefgeneHG19ByRegion(region) {
  return fetch({
    url: '/api/regionRefgeneHG19/region',
    method: 'post',
    data: {
      region
    }
  })
}
export function getGeneSymbolRegionCytobandHG19ByCytoband(cytoband) {
  return fetch({
    url: '/api/regionCytobandHG19/cytoband',
    method: 'post',
    data: {
      cytoband
    }
  })
}

export function getInfoGeneStatistics(geneSymbol, page, pageSize) {
  return fetch({
    url: '/api/geneStatistics/geneSymbol',
    method: 'post',
    data: {
      page,
      pageSize,
      geneSymbol
    }
  })
}

export function getInfoRareGene(geneSymbol, page = 1, pageSize = 10) {
  return fetch({
    url: '/api/rareGene/geneSymbol',
    method: 'post',
    data: {
      page,
      pageSize,
      geneSymbol
    }
  })
}

export function getInfoRareVariant(geneSymbol, page = 1, pageSize = 10, effect = 'All', extreme = 'All') {
  return fetch({
    url: '/api/rareVariant/geneSymbol',
    method: 'post',
    data: {
      page,
      pageSize,
      geneSymbol,
      effect,
      extreme
    }
  })
}

export function getInfoCommonVariant(geneSymbol, page = 1, pageSize = 10) {
  return fetch({
    url: '/api/commonVariant/geneSymbol',
    method: 'post',
    data: {
      page,
      pageSize,
      geneSymbol
    }
  })
}

export function getInfoCNV(geneSymbol, page = 1, pageSize = 10) {
  return fetch({
    url: '/api/cnv/geneSymbol',
    method: 'post',
    data: {
      page,
      pageSize,
      geneSymbol
    }
  })
}

export function getInfoDNAMethylation(geneSymbol, page = 1, pageSize = 10) {
  return fetch({
    url: '/api/dnaMethylation/geneSymbol',
    method: 'post',
    data: {
      page,
      pageSize,
      geneSymbol
    }
  })
}

export function getInfoGeneExpression(geneSymbol, page = 1, pageSize = 10) {
  return fetch({
    url: '/api/geneExpression/geneSymbol',
    method: 'post',
    data: {
      page,
      pageSize,
      geneSymbol
    }
  })
}

// export function getInfoGeneStatistics(geneSymbol) {
//   return fetch({
//     url: '/api/geneStatistics/geneSymbol',
//     method: 'post',
//     data: {
//       searchType,
//       inputValue
//     }
//   })
// }
//
// export function getInfoRareVariant(geneSymbol, page = 1, pageSize = 10, effect = 'All', extreme = 'All') {
//   return fetch({
//     url: '/api/rareVariant/geneSymbol',
//     method: 'post',
//     data: {
//       page,
//       pageSize,
//       searchType,
//       inputValue,
//       effect,
//       extreme
//     }
//   })
// }
//
// export function getInfoCommonVariant(geneSymbol, page = 1, pageSize = 10) {
//   return fetch({
//     url: '/api/commonVariant/geneSymbol',
//     method: 'post',
//     data: {
//       page,
//       pageSize,
//       searchType,
//       inputValue
//     }
//   })
// }
//
// export function getInfoCNV(geneSymbol, page = 1, pageSize = 10) {
//   return fetch({
//     url: '/api/cnv/inputValue',
//     method: 'post',
//     data: {
//       page,
//       pageSize,
//       searchType,
//       inputValue
//     }
//   })
// }
//
// export function getInfoDNAMethylation(geneSymbol, page = 1, pageSize = 10) {
//   return fetch({
//     url: '/api/dnaMethylation/inputValue',
//     method: 'post',
//     data: {
//       page,
//       pageSize,
//       searchType,
//       inputValue
//     }
//   })
// }
//
// export function getInfoGeneExpression(geneSymbol, page = 1, pageSize = 10) {
//   return fetch({
//     url: '/api/geneExpression/inputValue',
//     method: 'post',
//     data: {
//       page,
//       pageSize,
//       searchType,
//       inputValue
//     }
//   })
// }
