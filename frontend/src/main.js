import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import vuetify from './plugins/vuetify'
import globalFilters from './filters'
import VCurrencyField from 'v-currency-field'
import { VTextField } from 'vuetify/lib'

globalFilters()

Vue.config.productionTip = false

Vue.component('v-text-field', VTextField)
Vue.use(VCurrencyField, {
    currency: 'USD',
    locale: 'en',
    decimalLength: 2,
    autoDecimalMode: true,
    min: null,
    max: null,
    defaultValue: 0,
    allowNegative: true
})

new Vue({
    router,
    store,
    vuetify,
    render: h => h(App)
}).$mount('#app')
