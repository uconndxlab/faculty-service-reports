<template>
    <div id="payroll-page" class="mt-6">
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
                class="mt-5 mb-5"
            >
                <v-col>
                    <v-card>
                        <v-row>
                            <v-col
                                md="4"
                            >
                                <v-card-title>Pay Period Assumptions</v-card-title>
                                    <v-card-text>
                                        <div>Report Start Date</div>
                                        <p :class="metadataCSSClass">{{ accountSummaryStartDate }}</p>

                                        <div>Project End</div>
                                        <p :class="metadataCSSClass">{{ projectEnd }}</p>

                                        <div>Encumber Through Date</div>
                                        <p :class="metadataCSSClass">{{ payrollEncumberThroughDate }}
                                            <v-tooltip bottom>
                                                <template v-slot:activator="{ on, attrs }">
                                                    <v-icon
                                                        color="primary"
                                                        v-bind="attrs"
                                                        v-on="on"
                                                    >
                                                        mdi-help-circle
                                                    </v-icon>
                                                </template>
                                                <span>This will either be to project end, or fiscal year end, whichever first.</span>
                                            </v-tooltip>
                                        </p>
                                    </v-card-text>
                            </v-col>
                            <v-col
                                md="3"
                            >
                                <v-card-title>Pay Periods Remaining</v-card-title>

                                <v-card-text>
                                    <div>Total for Report</div>
                                    <p :class="metadataCSSClass">{{ payPeriodsRemainingToEncumber }}</p>

                                    <v-text-field
                                        label="Adjusted for Grads"
                                        v-model="gradPayPeriodsRemaining"
                                        placeholder="6.2"
                                    ></v-text-field>
                                    <v-text-field
                                        label="Adjusted for 9/10 Faculty"
                                        v-model="nineTenFacultyPayPeriodsRemaining"
                                        placeholder="6.2"
                                    ></v-text-field>
                                    <v-btn
                                        color="primary"
                                        @click="payPeriodCalculatorDialog = true"
                                        small
                                    >Pay Period Calculator</v-btn>
                                </v-card-text>
                            </v-col>
                            <v-col
                                md="3"
                                offset-md="1"
                            >
                                <v-card-title>Totals</v-card-title>
                                <v-card-text>
                                    <div>Salary</div>
                                    <p :class="metadataCSSClass">{{ salaryTotal | currency }}</p>

                                    <div>Fringe</div>
                                    <p :class="metadataCSSClass">{{ fringeTotal | currency }}</p>
                                </v-card-text>
                            </v-col>
                        </v-row>
                    </v-card>
                </v-col>
            </v-row>

            <v-row
                justify="center"
            >
                <v-col>
                    <p>PP = Pay Period</p>

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
                            <span v-if="additionalPayPeriodsToEndofGrant(item) === 0">(</span>
                            {{ additionalPayPeriodsToEndofGrant(item) }}
                            <span v-if="additionalPayPeriodsToEndofGrant(item) === 0">)</span>
                        </template>
                        <template v-slot:[`item.additional_to_be_encumbered`]="{ item }">
                            {{ additionalToBeEncumbered(item) | currency }}
                        </template>
                        <template v-slot:[`item.fringe`]="{ item }">
                            <v-checkbox
                                v-model="item.fringe"
                                @change="updateFringe( payrollEntries.indexOf(item), item.fringe )"
                                :ripple="false"
                            ></v-checkbox>
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
        metadataCSSClass: 'text-h6 text--primary',
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
                text: 'PP Currently Encumbered',
                value: 'pay_period_currently_encumbered',
                sortable: false,
                filterable: false
            },
            {
                text: 'Additional PP to End',
                value: 'additional_pay_periods',
                sortable: false,
                filterable: false
            },
            {
                text: 'Additional to be Encumbered',
                value: 'additional_to_be_encumbered',
                sortable: false,
                filterable: false
            },
            {
                text: 'Fringe',
                value: 'fringe',
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
        },
        salaryTotal() {
            return this.payrollEntries.reduce((a, b) => {
                if ( b.fringe === false ) {
                    return a + this.additionalToBeEncumbered(b)
                }
                return a
            }, 0)
        },
        fringeTotal() {
            return this.payrollEntries.reduce((a, b) => {
                if ( b.fringe ) {
                    return a + this.additionalToBeEncumbered(b)
                }
                return a
            }, 0)
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
                    return this.zeroOrGreater(+(
                        this.nineTenFacultyPayPeriodsRemaining
                        - this.payPeriodsCurrentlyEncumbered(item)
                    ).toPrecision(3))
                // Payroll - Post Doctors
                case "5260":
                    return this.zeroOrGreater(+(
                        this.gradPayPeriodsRemaining
                        - this.payPeriodsCurrentlyEncumbered(item)
                    ).toPrecision(3))
            }

            // @TODO Should return the calculation versus the number to grant end or fiscal year end, whichever is first, I think.
            // Payroll is encumbered through June 3rd, 2021 (assuming it is the first pay period end in June) (10,11,12 month employees).  Knowing this, we can probably estimate the end date.
            // 9 month faculty and grads should be encumbered through 5/22.  Seems to be the start of the pay period that ends in June.
            return +(this.payPeriodsRemainingToEncumber - this.payPeriodsCurrentlyEncumbered(item)).toPrecision(3)           
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
        },
        zeroOrGreater( num ) {
            if ( num < 0 ) {
                return 0
            }
            return num
        },
        updateFringe( index, value ) {
            this.$store.commit('UPDATE_SALARY_FRINGE_BOOLEAN', {
                index: index,
                value: value
            })
        }
    },
    mounted() {
        this.calculateEncumberThroughFiscalYearDate()
    }
}
</script>