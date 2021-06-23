<template>
    <div class="payroll-calculator">
        <v-container>
            <v-row
                justify="center"
            >
                <v-col>
                    <h1 class="text-h3">Payroll Calculator</h1>
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
                <v-col md="6" offset-md="6">
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
        ]
    }),
    computed: {
        ...mapGetters({
            payrollEntries: 'getAccountSalaryTransactions'
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
            // Grads
            if ( item.object_code === "5250" ) {
                return +(this.gradPayPeriodsRemaining - this.payPeriodsCurrentlyEncumbered(item)).toPrecision(3)
            }
            // Payroll - Post Doctors
            else if ( item.object_code === "5260" ) {
                return +(this.nineTenFacultyPayPeriodsRemaining - this.payPeriodsCurrentlyEncumbered(item)).toPrecision(3)
            }
            // @TODO Should return the calculation versus the number to grant end or fiscal year end, whichever is first, I think.
            return +(this.gradPayPeriodsRemaining - this.payPeriodsCurrentlyEncumbered(item)).toPrecision(3)
        },
        additionalToBeEncumbered( item ) {
            return +(this.additionalPayPeriodsToEndofGrant(item) * item.per_pay_period)
        }
    }
}
</script>