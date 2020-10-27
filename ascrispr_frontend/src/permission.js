import router from './router'
import store from './store'
import NProgress from 'nprogress' // Progress 进度条
import 'nprogress/nprogress.css'// Progress 进度条样式
import { getToken } from '@/utils/auth' // 验权

//权限控制，全部通过
next();
// const whiteList = ['/login', '/bind', '/gene4denovo', '/gene4denovo/home/home', '/gene4denovo/home/search', '/gene4denovo/api/admin', '/gene4denovo/api/CNV']
// router.beforeEach((to, from, next) => {
//   NProgress.start()
//   if (getToken()) {
//     if (to.path === '/login') {
//       next({ path: '/gene4denovo/home' })  // 如果已经登录了 就无需再输入密码
//     } else {
//       if (store.getters.roles.length === 0) {
//         store.dispatch('GetInfo').then(res => {
//           const roles = res.data.role
//           store.dispatch('GenerateRoutes', { roles }).then(() => {
//             router.addRoutes(store.getters.addRouters)
//             next({ ...to })
//           })
//         })
//       } else {
//         next()
//       }
//     }
//   } else {
//     if (whiteList.indexOf(to.path) !== -1) {
//       next()
//     } else {
//       next('/login')
//       NProgress.done()
//     }
//   }
// })

router.afterEach(() => {
  NProgress.done() // 结束Progress
})
