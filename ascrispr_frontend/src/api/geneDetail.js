import fetch from '@/utils/fetch'

export function getInfoGeneInfoHSByGeneSymbol(geneSymbol, page = 1, pageSize = 10) {
  return fetch({
    url: '/api/geneInfoHS/geneSymbol',
    method: 'post',
    data: {
      page,
      pageSize,
      geneSymbol
    }
  })
}

export function getInfoGeneScoreByGeneSymbol(geneSymbol, page = 1, pageSize = 10) {
  return fetch({
    url: '/api/geneScore/geneSymbol',
    method: 'post',
    data: {
      page,
      pageSize,
      geneSymbol
    }
  })
}

export function getInfoGene2goHumanByGeneID(geneID, page = 1, pageSize = 10) {
  return fetch({
    url: '/api/gene2goHuman/geneID',
    method: 'post',
    data: {
      page,
      pageSize,
      geneID
    }
  })
}

export function getInfoPPIByUniprotEntry(uniprotEntry, page = 1, pageSize = 10) {
  return fetch({
    url: '/api/PPI/uniprotEntry',
    method: 'post',
    data: {
      page,
      pageSize,
      uniprotEntry
    }
  })
}

export function getInfoBiosystemsHumanByGeneID(geneID, page = 1, pageSize = 10) {
  return fetch({
    url: '/api/biosystemsHuman/geneID',
    method: 'post',
    data: {
      page,
      pageSize,
      geneID
    }
  })
}


export function getInfoClinVarByGeneInfo(geneInfo, page = 1, pageSize = 10) {
  return fetch({
    url: '/api/clinVar/geneInfo',
    method: 'post',
    data: {
      page,
      pageSize,
      geneInfo
    }
  })
}

export function getInfoDenovoDBByGeneSymbol(geneSymbol, page = 1, pageSize = 10) {
  return fetch({
    url: '/api/denovoDB/geneSymbol',
    method: 'post',
    data: {
      page,
      pageSize,
      geneSymbol
    }
  })
}

export function getInfoMGIByGeneID(geneID, page = 1, pageSize = 10) {
  return fetch({
    url: '/api/mgi/geneID',
    method: 'post',
    data: {
      page,
      pageSize,
      geneID
    }
  })
}


export function getInfoHPOByGeneID(geneID, page = 1, pageSize = 10) {
  return fetch({
    url: '/api/hpo/geneID',
    method: 'post',
    data: {
      page,
      pageSize,
      geneID
    }
  })
}


export function getInfoHomoloGeneByGeneID(geneID, page = 1, pageSize = 10) {
  return fetch({
    url: '/api/homoloGene/geneID',
    method: 'post',
    data: {
      page,
      pageSize,
      geneID
    }
  })
}

export function getInfoVariantDistributionByGeneSymbol(geneSymbol, alleleFreq, page = 1, pageSize = 10) {
  return fetch({
    url: '/api/variantDistribution/geneSymbol',
    method: 'post',
    data: {
      page,
      pageSize,
      geneSymbol,
      alleleFreq
    }
  })
}

export function getInfoDGIdbByGeneSymbol(geneSymbol, page = 1, pageSize = 10) {
  return fetch({
    url: '/api/DGIdb/geneSymbol',
    method: 'post',
    data: {
      page,
      pageSize,
      geneSymbol
    }
  })
}

export function getInfoUniprotByUniprotEntry(uniprotEntry, page = 1, pageSize = 10) {
  return fetch({
    url: '/api/uniprot/uniprotEntry',
    method: 'post',
    data: {
      page,
      pageSize,
      uniprotEntry
    }
  })
}


export function getInfoInterproHumanProteinDomainByUniprotEntry(uniprotEntry, page = 1, pageSize = 10) {
  return fetch({
    url: '/api/interproHumanProteinDomain/uniprotEntry',
    method: 'post',
    data: {
      page,
      pageSize,
      uniprotEntry
    }
  })
}


export function getInfoOMIMByGeneID(geneID, page = 1, pageSize = 10) {
  return fetch({
    url: '/api/omim/geneID',
    method: 'post',
    data: {
      page,
      pageSize,
      geneID
    }
  })
}

export function getInfoSubcellularLocationByEnsemblID(ensemblID, page = 1, pageSize = 10) {
  return fetch({
    url: '/api/subcellularLocation/ensemblID',
    method: 'post',
    data: {
      page,
      pageSize,
      ensemblID
    }
  })
}


export function getInfoBSAvgExpressionByGeneSymbol(geneSymbol) {
  return fetch({
    url: '/api/BSAvgExpression/geneSymbol',
    method: 'post',
    data: {
      geneSymbol
    }
  })
}



export function getInfoGTEx1ByEnsemblID(ensemblID) {
  return fetch({
    url: '/api/GTEx1/ensemblID',
    method: 'post',
    data: {
      ensemblID
    }
  })
}


export function getInfoGTEx2ByEnsemblID(ensemblID) {
  return fetch({
    url: '/api/GTEx2/ensemblID',
    method: 'post',
    data: {
      ensemblID
    }
  })
}

