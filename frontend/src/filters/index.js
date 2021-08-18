import Vue from 'vue'

export default () => {
    Vue.filter('currency', function(value) {
        if ( !value ) {
            return '$0'
        }
        const formatter = new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD',
            minimumFractionDigits: 2
        })
        return formatter.format(value)
    })
}
