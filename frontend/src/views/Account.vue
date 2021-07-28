<template>
    <div id="account" class="mt-6">
        <v-container>
            <v-row
                justify="center"
            >
                <v-col>
                    <h1 class="text-h3">Account Summary</h1>
                </v-col>
            </v-row>

            <v-row
                justify="center"
            >
                <v-col sm="12" v-if="fake">
                    <test-data-warning></test-data-warning>
                </v-col>
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
                        v-if="account_summary_details"
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
import TestDataWarning from '@/components/TestDataWarning.vue'

export default {
    data: () => ({
        fake: true
    }),
    components: {
        AccountSummary,
        TestDataWarning
    },
    computed: {
        ...mapGetters({
            account_number: 'getAccountNumber',
            account_summary_details: 'getAccountSummary',
            account_summary_start_date: 'getReportStartDate',
            account_summary_end_date: 'getReportEndDate'
        }),
        dateRangeText() {
            let s = dayjs( this.account_summary_start_date )
            let date_string = s.format(`MMMM YYYY `)
            date_string += `(${this.account_summary_start_date} - ${this.account_summary_end_date})`
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