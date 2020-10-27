//项目入口
import Vue from 'vue'
import HighchartsVue from 'highcharts-vue'

import loadMap from 'highcharts/modules/map'
import Highcharts from 'highcharts'
import highchartsMore from 'highcharts/highcharts-more'
// Vue.use(HighchartsVue)

highchartsMore(Highcharts)
loadMap(Highcharts)
Vue.use(HighchartsVue, {Highcharts})

import VueClipboard from 'vue-clipboard2';
Vue.use(VueClipboard)

// import FileSaver from 'file-saver'
// import XLSX from 'xlsx'
// Vue.use(FileSaver)
// Vue.use(XLSX)

import 'normalize.css/normalize.css'// A modern alternative to CSS resets

import ElementUI from 'element-ui'
import 'element-ui/lib/theme-chalk/index.css'
import locale from 'element-ui/lib/locale/lang/zh-CN'
import eleLocale from 'element-ui/lib/locale';

import '@/styles/index.scss' // global css
import '@/styles/style.css' // global css
import '@/styles/easy-columns.css' // global css
/**
 * Global CSS imports
 */
import 'vue-tabs-component/docs/resources/tabs-component.css'
import 'vue-multiselect/dist/vue-multiselect.min.css'
import 'vue2-dropzone/dist/vue2Dropzone.css'

import App from './App'
import router from './router'
import store from './store'

import '@/assets/elIconThird/iconfont.css'

import '@/icons' // icon
// import '@/permission' // permission control

// 国际化
import VueI18n from 'vue-i18n'
Vue.use(VueI18n)
import en from './lang/en'
import cn from './lang/zh'
const i18n = new VueI18n({
  locale: 'en',    // 语言标识
  //this.$i18n.locale // 通过切换locale的值来实现语言切换
  messages: {
    'cn': cn,   // 中文语言包
    'en': en    // 英文语言包
  }
  // messages: {
  //   'cn': require('./lang/zh'),   // 中文语言包
  //   'en': require('./lang/en')    // 英文语言包
  // }
})

Vue.use(ElementUI, { locale: 'en' })
eleLocale.i18n((key, value) => i18n.t(key, value))
Vue.config.productionTip = false
// this.$i18n.locale = 'en'
export default i18n

// 配置权限信息
// Vue.prototype.$_has = function (feature) {
//   let resources = [];
//   let permission = true;
//   let routeName = store.state.user.routeName
//   let roles = store.state.user.roles // 当前用户角色
//   // 对admin角色自动显示所有按钮
//   if (Array.indexOf(roles, 'admin') !== -1) {
//     return true
//   }
//   //提取权限数组
//   if (Array.isArray(feature)) {
//   } else {
//     // 判断是否有指定的功能权限
//     // permission = Array.indexOf(routeName,feature) === -1 ? false : true
//     permission = routeName.findIndex(item => item === feature)>=0 ? true : false
//   }
//   return permission;
// }

Vue.directive('has', {
  bind: function (el, binding) {
    if (!Vue.prototype.$_has(binding.value)) {
      el.parentNode.removeChild(el);
    }
  }
});

import Echo from 'laravel-echo'
window.Pusher = require('pusher-js');
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    encrypted: true
});


Vue.directive('click-outside', {
  bind: function (el, binding, vnode) {
    el.event = function (event) {
      // here I check that click was outside the el and his childrens
      if (!(el === event.target || el.contains(event.target))) {
        // and if it did, call method provided in attribute value
        vnode.context[binding.expression](event)
      }
    }
    document.body.addEventListener('click', el.event)
  },
  unbind: function (el) {
    document.body.removeEventListener('click', el.event)
  }
})

// 导入组件
import VDropdown from '@/components/dropdown/VDropdown'
import VDropdownItem from '@/components/dropdown/VDropdownItem'
import VDropdownDivider from '@/components/dropdown/VDropdownDivider'
import VCollapse from '@/components/collapse/VCollapse'
import VCollapseItem from '@/components/collapse/VCollapseItem'

Vue.component('v-dropdown', VDropdown)
Vue.component('v-dropdown-item', VDropdownItem)
Vue.component('v-dropdown-divider', VDropdownDivider)
Vue.component('v-collapse', VCollapse)
Vue.component('v-collapse-item', VCollapseItem)




let vm = new Vue({
  el: '#app',
  router,
  store,
  i18n,
  template: '<App/>',
  components: { App }
})




// 跳转首页  http://front.test/
