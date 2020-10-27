import fetch from '@/utils/fetch'

export function analysis(params) {
  return fetch({
    url: '/api/analysis/analysis',
    method: 'post',
    data: {
      params: params
    }
  })
}

export function uploadFiles(data) {
  return fetch({
    url: '/api/analysis/uploadFiles',
    method: 'post',
    data,
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
}

// export function uploadAdminByImg(data) {
//   return fetch({
//     url: '/api/admin/uploadAvatar',
//     method: 'post',
//     data,
//     headers: {
//       'Content-Type': 'multipart/form-data'
//     }
//   })
// }
//
// export function uploadFiles(data) {
//   alert(data.file)
//   let param = new FormData()
//   param.append('files', data.file)
//   return fetch({
//     method: 'post',
//     url: '/api/analysis/uploadFiles',
//     headers: {'Content-Type': 'multipart/form-data'},
//     data: param
//   })

  // return fetch({
  //   method: 'post',
  //   url: '你的后台接收函数路径',
  //   timeout: 20000,
  //   data: data        // 参数需要是单一的formData形式
  // })
  // }

export function Model (name = '', email = '', role = [], avatar = '', password = '', password_confirmation='') {
  this.name = name
  this.email = email
  this.role = role
  this.avatar = avatar
  this.password = password
  this.password_confirmation = password_confirmation
}

