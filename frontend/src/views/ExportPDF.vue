<template>
    <div id="export-pdf-page" class="mt-6">
        <v-container>
            <v-row
                justify="center"
            >
                <v-col>
                    <h1>Export PDF Tool</h1>

                    <test-data-warning
                        header-message="This is a test tool."
                        body-message="This is purely experimental at the moment, there is no functional use besides testing PDF generation tools at the moment."
                        class="mb-4"
                    ></test-data-warning>

                    <v-btn
                        color="primary"
                        class="mt-4 mb-4"
                        @click="writePDF()"
                    >Generate PDF</v-btn>

                    <div class="preview">
                        <div class="account-monthly-transaction-details">
                            <h2>Account Monthly Transaction Details</h2>

                            <!-- Salary Entries (NOT FRINGE) -->
                            <v-simple-table v-if="accountSalaryEntries" dense class="mb-4">
                                <template v-slot:default>
                                    <thead>
                                        <tr>
                                            <th class="text-left">Object Code</th>
                                            <th class="text-left">Object Description</th>
                                            <th class="text-left">Entry Description</th>
                                            <th class="text-left">Actual</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(tx, index) in accountSalaryEntries"
                                            :key="'sal' + index"
                                        >
                                            <td class="text-left">{{ tx.object_code }}</td>
                                            <td class="text-left">{{ getPayrollLabel(tx.object_code) }}</td>
                                            <td class="text-left">PAY PERIOD - {{ tx.person }}</td>
                                            <td class="text-right">{{ tx.per_pay_period | currency }}</td>
                                        </tr>
                                        <tr
                                            v-for="code in uniqueSalaryObjectCodes"
                                            :key="code"
                                            class="light-background-accent"
                                        >
                                            <td class="text-left" :colspan="wideCellColSpan">Total {{ code }}</td>
                                            <td class="text-right">{{getObjectCodeTotal(code) | currency }}</td>
                                        </tr>
                                        <tr class="light-blue-background-accent">
                                            <td class="text-left" :colspan="wideCellColSpan">Total Salary</td>
                                            <td class="text-right">{{ salaryTotal | currency }}</td>
                                        </tr>
                                    </tbody>
                                </template>
                            </v-simple-table>

                            <!-- Fringe Entries (NOT SALARY) -->
                            <v-simple-table v-if="accountSalaryFringeEntries" dense>
                                <template v-slot:default>
                                    <thead>
                                        <tr>
                                            <th class="text-left">Object Code</th>
                                            <th class="text-left">Object Description</th>
                                            <th class="text-left">Entry Description</th>
                                            <th class="text-left">Actual</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(tx, index) in accountSalaryFringeEntries"
                                            :key="'sal' + index"
                                        >
                                            <td class="text-left">{{ tx.object_code }}</td>
                                            <td class="text-left">{{ getPayrollLabel(tx.object_code) }}</td>
                                            <td class="text-left">PAY PERIOD - {{ tx.person }}</td>
                                            <td class="text-right">{{ tx.per_pay_period | currency }}</td>
                                        </tr>
                                        <tr
                                            v-for="code in uniqueFringeObjectCodes"
                                            :key="code"
                                            class="light-background-accent"
                                        >
                                            <td class="text-left" :colspan="wideCellColSpan">Total {{ code }}</td>
                                            <td class="text-right">{{getObjectCodeTotal(code) | currency }}</td>
                                        </tr>
                                        <tr class="light-blue-background-accent">
                                            <td class="text-left" :colspan="wideCellColSpan">Total Fringe</td>
                                            <td class="text-right">{{ fringeTotal | currency }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-left" :colspan="wideCellColSpan"><strong>Total Direct Costs</strong></td>
                                            <td class="text-right">{{ totalDirectCosts | currency }}</td>
                                        </tr>
                                    </tbody>
                                </template>
                            </v-simple-table>
                        </div>
                    </div>
                </v-col>
            </v-row>
        </v-container>
        
    </div>
</template>

<script>
import { jsPDF } from "jspdf"
import TestDataWarning from '@/components/TestDataWarning.vue'
import { mapGetters } from 'vuex'
import { getPayrollLabelForCode } from '@/lib/payrollCodes'

export default {
    components: { TestDataWarning },
    data: () => ({
        wideCellColSpan: 3
    }),
    computed: {
        ...mapGetters({
            account: 'getAccount'
        }),
        accountSalaryEntries() {
            return this.account.transaction_categories.salary.transactions.filter( (val) => {
                return !val.fringe
            })
        },
        accountSalaryFringeEntries() {
            return this.account.transaction_categories.salary.transactions.filter( (val) => {
                return val.fringe
            })
        },
        uniqueSalaryObjectCodes() {
            return this.accountSalaryEntries.map( (tx) => tx.object_code )
                .filter((val, index, self) => self.indexOf(val) === index)
        },
        salaryTotal() {
            const monthly_values = this.accountSalaryEntries.map( (tx) => tx.per_pay_period )
            return monthly_values.reduce( (a,b) => a+b, 0 )
        },
        uniqueFringeObjectCodes() {
            return this.accountSalaryFringeEntries.map( (tx) => tx.object_code )
                .filter((val, index, self) => self.indexOf(val) === index)
        },
        fringeTotal() {
            const monthly_values = this.accountSalaryFringeEntries.map( (tx) => tx.per_pay_period )
            return monthly_values.reduce( (a,b) => a+b, 0 )
        },
        totalDirectCosts() {
            return this.salaryTotal + this.fringeTotal
        }
    },
    methods: {
        writePDF() {
            const doc = new jsPDF({ 
                orientation: 'landscape'
            })
            console.log(doc)
            // .table( x, y, data, headers, config )
            doc.table(1, 1, this.accountMonthlyTransactionDetailsCreateFlatTableStructure(), [
                {
                    id: 'object_code',
                    name: 'object_code',
                    prompt: 'Object Code',
                    align: 'left',
                    width: 50
                },
                {
                    id: 'object_description',
                    name: 'object_description',
                    prompt: 'Object Description',
                    align: 'left',
                    width: 100
                },
                {
                    id: 'entry_description',
                    name: 'entry_description',
                    prompt: 'Entry Description',
                    align: 'left',
                    width: 110
                },
                {
                    id: 'actual',
                    name: 'actual',
                    prompt: 'Actual',
                    align: 'right',
                    width: 50
                }
            ], {
                fontSize: 8
            })
            doc.save('fsr-test.pdf')
        },
        getPayrollLabel(code) {
            return getPayrollLabelForCode(code)
        },
        getObjectCodeTotal(code) {
            let total = 0
            this.account.transaction_categories.salary.transactions.forEach( (entry) => {
                if ( entry.object_code == code ) {
                    total += entry.per_pay_period
                }
            })
            return total
        },
        accountMonthlyTransactionDetailsCreateFlatTableStructure() {
            let data = []
            this.accountSalaryEntries.forEach( (val) => {
                data.push({
                    object_code: val.object_code,
                    object_description: this.getPayrollLabel(val.object_code),
                    entry_description: 'PAY PERIOD_ - ' +  val.person,
                    actual: this.$options.filters.currency(val.per_pay_period)
                })
            })
            this.uniqueSalaryObjectCodes.forEach( (val) => {
                data.push({
                    object_code: 'Total ' + val,
                    object_description: ' ',
                    entry_description: ' ',
                    actual: this.$options.filters.currency(this.getObjectCodeTotal(val))
                })
            })
            data.push({
                object_code: 'Total Salary',
                object_description: ' ',
                entry_description: ' ',
                actual: this.$options.filters.currency(this.salaryTotal)
            })
            this.accountSalaryFringeEntries.forEach( (val) => {
                data.push({
                    object_code: val.object_code,
                    object_description: this.getPayrollLabel(val.object_code),
                    entry_description: 'PAY PERIOD_ - ' +  val.person,
                    actual: this.$options.filters.currency(val.per_pay_period)
                })
            })
            this.uniqueFringeObjectCodes.forEach( (val) => {
                data.push({
                    object_code: 'Total ' + val,
                    object_description: ' ',
                    entry_description: ' ',
                    actual: this.$options.filters.currency(this.getObjectCodeTotal(val))
                })
            })
            data.push({
                object_code: 'Total Fringe Benefits',
                object_description: ' ',
                entry_description: ' ',
                actual: this.$options.filters.currency(this.fringeTotal)
            })
            data.push({
                object_code: 'Total Direct Costs',
                object_description: ' ',
                entry_description: ' ',
                actual: this.$options.filters.currency(this.totalDirectCosts)
            })
            return data
        }
    }
}
</script>

<style scoped>
.light-background-accent {
    background-color: #eee;
}

.light-blue-background-accent {
    background-color: rgb(202, 206, 252);
}
</style>