import fetch from '@/utils/fetch'

export function getEnzymesInfo(params, page = 1, pageSize = 10) {
  return fetch({
    url: '/api/enzymeInformation/getEnzymesInfo',
    method: 'post',
    data: {
      page,
      pageSize,
      params
    }
  })
}
