<template>
    <div id="account">
        <v-container>
            <v-row
                justify="center"
            >
                <v-col md="7">
                    <v-card>
                        <v-card-title class="text-h6">Account: {{ account_number }}</v-card-title>
                        <v-card-text>
                            Data provided from VPR records on KFS transactions.
                        </v-card-text>
                    </v-card>
                </v-col>

                <v-col
                    md="5"
                >
                    <v-card>
                        <v-card-title>
                            <v-icon
                                left
                            >mdi-calendar</v-icon>
                            <span class="text-h6">Summary Date Range</span>
                        </v-card-title>
                        <v-card-text>
                            {{dateRangeText}}
                        </v-card-text>
                    </v-card>
                </v-col>

                <v-col sm="12">
                    <account-summary
                        :summary-details="account_summary_details"
                    ></account-summary>
                </v-col>
            </v-row>
        </v-container>
        
    </div>
</template>

<script>
import { mapGetters, mapMutations } from 'vuex'
import dayjs from 'dayjs'
import AccountSummary from '@/components/AccountSummary.vue'

export default {
    data: () => ({
        account_summary_details: [
            {
                category: 'Salary',
                budget: 61015.00,
                actual: 55777.47,
                encumbrance: 53002.51,
                available_balance: 47764.88,
                additional_encumbrance: 0
            },
            {
                category: 'Fringe Benefits',
                budget: 61015.00,
                actual: 55777.47,
                encumbrance: 53002.51,
                available_balance: 47764.88,
                additional_encumbrance: 0
            },
            {
                category: 'Contractuals/Commodities',
                budget: 61015.00,
                actual: 55777.47,
                encumbrance: 53002.51,
                available_balance: 47764.88,
                additional_encumbrance: 43222
            },
            {
                category: 'Travel - Domestic',
                budget: 61015.00,
                actual: 55777.47,
                encumbrance: 53002.51,
                available_balance: 47764.88,
                additional_encumbrance: 0
            },
            {
                category: 'Equipment > $5,000',
                budget: 61015.00,
                actual: 55777.47,
                encumbrance: 53002.51,
                available_balance: 47764.88,
                additional_encumbrance: 0
            },
            {
                category: 'Indirect Cost',
                budget: 61015.00,
                actual: 55777.47,
                encumbrance: 53002.51,
                available_balance: 47764.88,
                additional_encumbrance: 0
            },
        ]
    }),
    components: {
        AccountSummary
    },
    computed: {
        ...mapGetters({
            account_number: 'getAccountNumber'
        }),
        dateRangeText() {
            let d = dayjs()
            let date_string = d.format('MMMM YYYY (M/01/YYYY - M/DD/YYYY)')
            return date_string
        }
    },
    methods: {
        ...mapMutations(['ASSIGN_ACCOUNT_NUMBER'])
    },
    mounted() {
        if ( !this.account_number ) {
            this.ASSIGN_ACCOUNT_NUMBER(this.$route.params.acc_num)
        }
    }
}
</script>