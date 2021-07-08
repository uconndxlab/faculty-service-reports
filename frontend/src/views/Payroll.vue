<template>
    <div class="payroll-page">
        <v-container>
            <v-row
                justify="center"
            >
                <v-col>
                    <h1 class="text-h3">Payroll</h1>
                </v-col>
            </v-row>

            <v-dialog
                v-model="payPeriodCalculatorDialog"
                width="600"
            >
                <pay-period-calculator :close-button="true" @closeDialogs="closeDialogs"></pay-period-calculator>
            </v-dialog>

            <v-row
                justify="center"
            >
                <v-col md="6">
                    <v-card>
                        <v-card-title>Pay Period Assumptions</v-card-title>
                        <v-card-text>
                            <p>Report Start Date: {{ accountSummaryStartDate }}</p>
                            <p>Project End: {{ projectEnd }}</p>
                            <small>This will either be to project end, or fiscal year end, whichever first.</small>
                            <p>Encumber Through Date: {{ payrollEncumberThroughDate }}</p>
                            <p>Pay Periods Remaining for Report: {{ payPeriodsRemainingToEncumber }}</p>
                            
                            <v-btn @click="calculateEncumberThroughFiscalYearDate">Calculate Fiscal Year Date</v-btn>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col md="6">
                    <v-card>
                        <v-card-title>Pay Periods Remaining</v-card-title>
                        <v-card-text>
                            <v-text-field
                                label="Grads"
                                v-model="gradPayPeriodsRemaining"
                                placeholder="6.2"
                            ></v-text-field>
                            <v-text-field
                                label="9/10 Faculty"
                                v-model="nineTenFacultyPayPeriodsRemaining"
                                placeholder="6.2"
                            ></v-text-field>
                            <v-btn
                                color="primary"
                                @click="payPeriodCalculatorDialog = true"
                                small
                            >Pay Period Calculator</v-btn>
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>

            <v-row
                justify="center"
            >
                <v-col>
                    <v-data-table
                        :headers="payrollEntriesHeaders"
                        :items="payrollEntries"
                        :items-per-page="20"
                        :hide-default-footer="true"
                    >
                        <template v-slot:[`item.outstanding_encum`]="{ item }">
                            {{ item.outstanding_encum | currency }}
                        </template>
                        <template v-slot:[`item.per_pay_period`]="{ item }">
                            {{ item.per_pay_period | currency }}
                        </template>
                        <template v-slot:[`item.pay_period_currently_encumbered`]="{ item }">
                            {{ payPeriodsCurrentlyEncumbered(item) }}
                        </template>
                        <template v-slot:[`item.additional_pay_periods`]="{ item }">
                            {{ additionalPayPeriodsToEndofGrant(item) }}
                        </template>
                        <template v-slot:[`item.additional_to_be_encumbered`]="{ item }">
                            {{ additionalToBeEncumbered(item) | currency }}
                        </template>
                    </v-data-table>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
import PayPeriodCalculator from '@/components/PayPeriodCalculator.vue'
import dayjs from 'dayjs'
import isSameOrBefore from 'dayjs/plugin/isSameOrBefore'
import dayjsBusinessDays from 'dayjs-business-days'

dayjs.extend(dayjsBusinessDays)
dayjs.extend(isSameOrBefore)

export default {
    components: { PayPeriodCalculator },
    data: () => ({
        payPeriodCalculatorDialog: false,
        payrollEntriesHeaders: [
            {
                text: 'Object Code',
                align: 'start',
                value: 'object_code',
            },
            { 
                text: 'Person',
                value: 'person'
            },
            { 
                text: 'Outstanding Encumbrance',
                value: 'outstanding_encum',
                sortable: false,
                filterable: false
            },
            {
                text: 'Per Pay Period',
                value: 'per_pay_period',
                sortable: false,
                filterable: false
            },
            {
                text: 'Pay Period Currently Encumbered',
                value: 'pay_period_currently_encumbered',
                sortable: false,
                filterable: false
            },
            {
                text: 'Additional Pay Periods to End of Grant',
                value: 'additional_pay_periods',
                sortable: false,
                filterable: false
            },
            {
                text: 'Additional to be Encumbered',
                value: 'additional_to_be_encumbered',
                sortable: false,
                filterable: false
            }
        ],
        payrollEncumberThroughDate: ''
    }),
    computed: {
        ...mapGetters({
            payrollEntries: 'getAccountSalaryTransactions',
            accountSummaryStartDate: 'getReportStartDate',
            accountSummaryEndDate: 'getReportEndDate',
            payrollFiscalEncumberThroughDate: 'getFiscalEncumberThroughDatePayroll',
            projectEnd: 'getProjectEndDate'
        }),
        gradPayPeriodsRemaining: {
            get() {
                return this.$store.state.account.pay_periods_remaining.grad
            },
            set(val) {
                this.$store.commit('UPDATE_GRAD_PAY_PERIOD', val)
            }
        },
        nineTenFacultyPayPeriodsRemaining: {
            get() {
                return this.$store.state.account.pay_periods_remaining.nineTenFaculty
            },
            set(val) {
                this.$store.commit('UPDATE_NINE_TEN_FACULTY_PAY_PERIOD', val)
            }
        },
        payPeriodsRemainingToEncumber() {
            let report_start = dayjs(this.accountSummaryStartDate, 'MM/DD/YYYY')
            let encumber_through_date = dayjs(this.payrollEncumberThroughDate, 'MM/DD/YYYY')
            let diff = encumber_through_date.diff(report_start, 'day', true)
            return ( diff / 14 ).toPrecision(3)
        }
    },
    methods: {
        closeDialogs() {
            this.payPeriodCalculatorDialog = false
        },
        payPeriodsCurrentlyEncumbered( item ) {
            return +(item.outstanding_encum / item.per_pay_period).toPrecision(3)
        },
        additionalPayPeriodsToEndofGrant( item ) {
            switch ( item.object_code ) {
                // Regular Payroll - Faculty (can be 9, 10, 11 month)
                case "5111":
                    break;
                // Regular Payroll - Other Professional (12 Month Employees)
                case "5112":
                    break;
                // Payroll - Contractual
                case "5231":
                    break;
                // Payroll - Faculty Summer (can earn 3, 2, or 1 month of pay)
                case "5232":
                    break;
                // Payroll - Student Labor
                case "5240":
                    break;
                // Grads (AY only 8/23 to 5/22, paid in summer on 5231.  Not paid 2 weeks behind.)
                case "5250":
                    return +(this.gradPayPeriodsRemaining - this.payPeriodsCurrentlyEncumbered(item)).toPrecision(3)
                // Payroll - Post Doctors
                case "5260":
                    return +(this.nineTenFacultyPayPeriodsRemaining - this.payPeriodsCurrentlyEncumbered(item)).toPrecision(3)
            }

            // @TODO Should return the calculation versus the number to grant end or fiscal year end, whichever is first, I think.
            // Payroll is encumbered through June 3rd, 2021 (assuming it is the first pay period end in June) (10,11,12 month employees).  Knowing this, we can probably estimate the end date.
            // 9 month faculty and grads should be encumbered through 5/22.  Seems to be the start of the pay period that ends in June.
            return +(this.gradPayPeriodsRemaining - this.payPeriodsCurrentlyEncumbered(item)).toPrecision(3)           
        },
        additionalToBeEncumbered( item ) {
            return +(this.additionalPayPeriodsToEndofGrant(item) * item.per_pay_period)
        },
        calculateEncumberThroughFiscalYearDate() {
            let d = dayjs(this.accountSummaryStartDate, 'MM/DD/YYYY')
            let projectEnd = dayjs(this.projectEnd, 'MM/DD/YYYY')
            let fisc = dayjs(this.payrollFiscalEncumberThroughDate)

            if ( d.isSameOrBefore(fisc) ) {
                // In the correct fiscal year.
                console.log('In the correct fiscal year.')
            } else {
                // Potentially not in the correct fiscal year.
                console.log('Report date is after fiscal, so we need the next year.')
                fisc.add(1, 'year')
            }

            if ( projectEnd.isBefore(fisc) ) {
                this.payrollEncumberThroughDate = projectEnd.format('MM/DD/YYYY')
            } else {
                this.payrollEncumberThroughDate = fisc.format('MM/DD/YYYY')
            }
        }
    },
    mounted() {
        this.calculateEncumberThroughFiscalYearDate()
    }
}
</script>