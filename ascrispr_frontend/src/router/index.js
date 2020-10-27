import Vue from 'vue'
import Router from 'vue-router'
const _import = require('./_import_' + process.env.NODE_ENV)

Vue.use(Router)

/* Layout */
import Layout from '../views/layout/Layout'

export const constantRouterMap = [
  {
    path: '/',
    component: Layout,
    redirect: '/ascrispr',
    name: 'ascrispr',
    hidden: true,
    children: [
      {
        path: '/ascrispr',
        component: _import('site/Ascrispr')
      },
      {
        path: '/dominantdb',
        component: _import('site/DominantDatabase')
      },
      {
        path: '/validateddb',
        component: _import('site/ValidatedDatabase')
      },
      {
        path: '/tutorial',
        component: _import('site/Tutorial')
      },
      {
        path: '/references',
        component: _import('site/References')
      },
      {
        path: '/links',
        component: _import('site/Links')
      },
      {
        path: '/about',
        component: _import('site/About')
      },
      {
        path: '/ascrisprEnzymesInfoMatched',
        component: _import('site/AscrisprEnzymesInfoMatched')
      },
      {
        path: '/ascrisprOfftargets',
        component: _import('site/AscrisprOfftargets')
      },
      {
        path: '/ascrisprByTimeStamp',
        component: _import('site/AscrisprByTimeStamp')
      },
      {
        path: '/download',
        component: _import('site/Download')
      }
    ]
  },
  {
    path: '/s',
    component: _import('site/GeneDetail'),
    name: 'geneDetail',
    hidden: true
  },

  {
    path: '/404',
    component: _import('404'),
    hidden: true
  },

  {
    path: '*',
    redirect: '/404',
    hidden: true
  }
]

export default new Router({
  base: 'ascrispr', // production
  // base: '', // development
  mode: 'history',
  // scrollBehavior: (to, from, savedPosition) => ({
  //   y: 0
  // }),
  scrollBehavior(to, from, savedPosition) {
    console.log('to', to)
    if (to.hash) {
      return {
        selector: to.hash
      }
    }
  },
  routes: constantRouterMap
})

export const asyncRouterMap = [
  {
    path: '*',
    redirect: '/404',
    hidden: true
  }
]
