import fetch from '@/utils/fetch'

export function getOfftargetsInfo(params, page = 1, pageSize = 10) {
  return fetch({
    url: '/api/offtargets/getOfftargetsInfo',
    method: 'post',
    data: {
      page,
      pageSize,
      params
    }
  })
}
