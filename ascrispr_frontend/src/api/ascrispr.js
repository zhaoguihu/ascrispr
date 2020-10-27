import fetch from '@/utils/fetch'

export function getSequences(params) {
  return fetch({
    url: '/api/ascrispr/getSequences',
    method: 'post',
    data: {
      params: params
    }
  })
}

export function plotSequences(time_stamp) {
  return fetch({
    url: '/api/ascrispr/plotSequences',
    method: 'post',
    data: {
      params: time_stamp
    }
  })
}

export function getInfoSequence(params) {
  return fetch({
    url: '/api/ascrispr/ascrispr',
    method: 'post',
    data: {
      params: params
    }
  })
}

export function showInfoSequence(params) {
  return fetch({
    url: '/api/ascrispr/showInfoSequence',
    method: 'post',
    data: {
      params: params
    }
  })
}

export function showInfoSequenceByTime(time_stamp) {
  return fetch({
    url: '/api/ascrispr/showInfoSequenceByTime',
    method: 'post',
    data: {
      params: time_stamp
    }
  })
}
