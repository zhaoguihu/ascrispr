import fetch from '@/utils/fetch'

export function getInfoDominantDatabase(inputValueDisease, inputValueGeneSymbol, inputValueRSID) {
  return fetch({
    url: '/api/dominantDatabase/getInfoDominantDatabase',
    method: 'post',
    data: {
      inputValueDisease,
      inputValueGeneSymbol,
      inputValueRSID
    }
  })
}

export function getKeyListDominantDatabase() {
  return fetch({
    url: '/api/dominantDatabase/getKeyListDominantDatabase',
    method: 'post',
    data: {

    }
  })
}
