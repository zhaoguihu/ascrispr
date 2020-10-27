import fetch from '@/utils/fetch'

export function getInfoValidatedDatabase() {
  return fetch({
    url: '/api/validatedDatabase/getInfoValidatedDatabase',
    method: 'post',
    data: {
    }
  })
}

