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

            <v-row
                justify="center"
            >
                <v-col>
                    <vue-excel-editor v-model="payrollEntries">
                        <vue-excel-column field="object_code" label="Object Code" />
                        <vue-excel-column field="person" label="Person" />
                        <vue-excel-column field="outstanding_encum" label="Outstanding Encum" :change="onOutstandingEcumChange" />
                        <vue-excel-column field="per_pay_period" label="Per Pay Period" :change="onPerPayPeriodChange" />
                        <vue-excel-column field="pay_period_currently_encumbered" label="Pay Period Currently Encumbered" readonly />
                        <vue-excel-column field="additional_pay_periods" label="Additional Pay Periods to Grant End" readonly />
                        <vue-excel-column field="additional_to_be_encumbered" label="Additional to Encumber" readonly />
                    </vue-excel-editor>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>
// import { mapGetters } from 'vuex'

export default {
    data: () => ({
        payrollEntries: [
            {
                object_code: '5250',
                person: 'Person A',
                outstanding_encum: 3063.19,
                per_pay_period: 1458.66,
                pay_period_currently_encumbered: 2.1,
                additional_pay_periods: 0.00,
                additional_to_be_encumbered: 0.00
            },
            {
                object_code: '5250',
                person: 'Person A',
                outstanding_encum: 3063.19,
                per_pay_period: 1458.66,
                pay_period_currently_encumbered: 2.1,
                additional_pay_periods: 0.00,
                additional_to_be_encumbered: 0.00
            },
            {
                object_code: '5250',
                person: 'Person A',
                outstanding_encum: 3063.19,
                per_pay_period: 1458.66,
                pay_period_currently_encumbered: 2.1,
                additional_pay_periods: 0.00,
                additional_to_be_encumbered: 0.00
            },
            {
                object_code: '5250',
                person: 'Person A',
                outstanding_encum: 3063.19,
                per_pay_period: 1458.66,
                pay_period_currently_encumbered: 2.1,
                additional_pay_periods: 0.00,
                additional_to_be_encumbered: 0.00
            }
        ]
    }),
    methods: {
        onOutstandingEcumChange(newVal, oldVal, row) {
            row.pay_period_currently_encumbered = +(newVal / row.per_pay_period).toPrecision(3)
        },
        onPerPayPeriodChange(newVal, oldVal, row) {
            row.pay_period_currently_encumbered = +(row.outstanding_encum / newVal).toPrecision(3)
        }
    }
}
</script>