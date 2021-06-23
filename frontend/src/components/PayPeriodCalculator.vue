<template>
    <div class="payroll-calculator">
        <v-card>
            <v-card-title class="text-h6">Pay Period Calculator</v-card-title>
            <v-card-subtitle>Calculator counts workdays Monday through Friday from the start date through and including the end date, and converts it to a number of pay-cycles. If you use the dates and the bi-weekly stipend, it does the multiplication and gives you the actual gross dollar amount that will be paid.</v-card-subtitle>
            <v-card-text>
                <v-text-field
                    v-model="payPeriodCalculateStartDate"
                    placeholder="MM/DD/YYYY"
                    name="Pay Period Calculate Start Date"
                    label="Start Date"
                ></v-text-field>
                <v-text-field
                    v-model="payPeriodCalculateEndDate"
                    placeholder="MM/DD/YYYY"
                    name="Pay Period Calculate End Date"
                    label="End Date"
                ></v-text-field>
                <v-text-field
                    v-model="biWeeklyStipend"
                    placeholder="0.00"
                    name="Pay Period Calculate Biweekly Stipend"
                    label="Bi-weekly Stipend"
                >
                    <v-icon
                        slot="prepend"
                    >mdi-currency-usd</v-icon>
                </v-text-field>

                <p>Difference in Business Days: {{ businessDaysDifference }}</p>

                <p>Pay Periods: {{ payPeriodsRemaining }}</p>

                <p>Adjusted Offer Amount: {{ adjustedOfferAmount | currency }}</p>
            </v-card-text>
            <v-divider v-if="closeButton"></v-divider>
            <v-card-actions v-if="closeButton">
                <v-btn
                    text
                    @click="closeDialogs()"
                >Close</v-btn>
            </v-card-actions>
        </v-card>
    </div>
</template>

<script>
/* eslint-disable no-unused-vars */
import dayjs from 'dayjs'
import dayjsBusinessDays from 'dayjs-business-days'

dayjs.extend(dayjsBusinessDays)

export default {
    data: () => ({
        payPeriodCalculateStartDate: '',
        payPeriodCalculateEndDate: '',
        biWeeklyStipend: 0.00
    }),
    props: ['closeButton'],
    computed: {
        businessDaysDifference() {
            if ( this.payPeriodCalculateStartDate.length > 8 && this.payPeriodCalculateEndDate.length > 8 ) {
                let start = dayjs(this.payPeriodCalculateStartDate, 'MM/DD/YYYY')
                let end = dayjs(this.payPeriodCalculateEndDate, 'MM/DD/YYYY')
                if ( start.isValid() && end.isValid() ) {
                    end = end.add(1, 'day') // Includes the date given, not just flat diff.
                    let difference = end.businessDiff(start)
                    return difference
                }
            }
            return 0
        },
        payPeriodsRemaining() {
            return this.businessDaysDifference / 10.0
        },
        adjustedOfferAmount() {
            if ( this.payPeriodsRemaining && this.biWeeklyStipend ) {
                return this.payPeriodsRemaining * this.biWeeklyStipend
            }
            return 0
        }
    },
    methods: {
        closeDialogs() {
            this.$emit('closeDialogs')
        }
    }
}
</script>