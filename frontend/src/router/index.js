import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/account/:acc_num',
    name: 'account',
    component: () => import(/* webpackChunkName: "account" */ '../views/Account.vue')
  },
  {
    path: '/payroll-calculator',
    name: 'payroll-calulator',
    component: () => import(/* webpackChunkName: "payroll-calculator" */ '../views/PayrollCalculator.vue')
  },
  {
    path: '/pay-period-calculator',
    name: 'pay-period-calculator',
    component: () => import(/* webpackChunkName: "pay-period-calculator" */ '../views/PayPeriodCalculator.vue')
  },
  {
    path: '/excel-paste',
    name: 'Excel Paste',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "excel-paste" */ '../views/ExcelPaste.vue')
  },
  {
    path: '/export-pdf',
    name: 'Export PDF',
    component: () => import(/* webpackChunkName: "export-pdf" */ '../views/ExportPDF.vue')
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
