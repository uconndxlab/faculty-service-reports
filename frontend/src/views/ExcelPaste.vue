<template>
    <div id="excel-paste" class="mt-6">
        <v-container>
            <v-row
                justify="center"
            >
                <v-col>
                    <h1>Excel Paste</h1>

                    <test-data-warning
                        header-message="This is a test tool."
                        body-message="Purpose of this experiment is to simulate the Payroll Data paste functionality into a tool, so we can start working with the data as a drop in to their workflow."
                    ></test-data-warning>

                    <v-textarea @paste="onPaste($event)" placeholder="Paste Here" class="mt-4"></v-textarea>

                    <p v-if="hasProperHeaders()">Proper Header Rows Detected!</p>
                    <p v-else>Incorrect Header Rows.</p>

                    <h2>Pasted Table Data</h2>

                    <v-data-table
                        :headers="standard_table_headers"
                        :items="entries"
                        :hide-default-footer="true"
                    >
                        <template v-slot:[`item.PER_PAY_PERIOD`]="{ item }">
                            {{ item.PER_PAY_PERIOD | currency }}
                        </template>
                    </v-data-table>

                    <v-alert
                        type="warning"
                        v-if="showPayPeriodMissingWarning"
                        class="mb-1 mt-3"
                    >Pay Period Information must be completely filled out before submitting to the payroll section.</v-alert>

                    <v-btn
                        color="primary"
                        class="mt-3 mr-3"
                        v-if="hasProperHeaders()"
                        @click="editPerPayPeriodSalaries()"
                    >
                        Input Pay Period Salaries
                    </v-btn>

                    <v-btn
                        color="primary"
                        class="mt-3" 
                        v-if="hasProperHeaders()"
                        @click="commitToPayroll()">
                        Commit Data to Payroll
                    </v-btn>

                    


                    <v-dialog
                        v-model="editPayPeriodSalariesDialogOpen"
                        width="600"
                    >
                        <v-card>
                            <v-card-title>
                                <span class="text-h5 mb-2">Edit Pay Period Salaries</span>
                            </v-card-title>
                            <v-form
                                ref="edit_pay_period_salaries_form"
                                lazy-validation
                            >
                                <v-card-text>
                                    <v-row>
                                        <v-col
                                            v-for="(e, index) in editEntries"
                                            :key="e.Name + '_' + e.ROLLUP_ID + '_' + index"
                                            md="6"
                                        >
                                            <v-currency-field
                                                v-model="editEntries[index].PER_PAY_PERIOD"
                                                :label="e.Name + ' (' + readableRollup(e.ROLLUP_ID) + ') Per Pay Period'"
                                            ></v-currency-field>
                                        </v-col>
                                    </v-row>
                                </v-card-text>
                                <v-card-actions>
                                    <v-btn
                                        text
                                        @click="savePayPeriodSalaryEdits()"
                                    >Save</v-btn>
                                    <v-btn
                                        text
                                        @click="closeDialogs()"
                                    >Close</v-btn>
                                </v-card-actions>
                            </v-form>
                        </v-card>
                    </v-dialog>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>
import TestDataWarning from '@/components/TestDataWarning.vue'
import { mapMutations } from 'vuex'

export default {
    name: "Excel-Paste",
    components: { TestDataWarning },
    data() {
        return {
            editPayPeriodSalariesDialogOpen: false,
            standard_table_headers: [
                {
                    text: 'ACCOUNT_NBR',
                    align: 'start',
                    value: 'ACCOUNT_NBR'
                },
                {
                    text: 'ROLLUP_ID',
                    value: 'ROLLUP_ID'
                },
                {
                    text: 'FIN_OBJECT_CD',
                    value: 'FIN_OBJECT_CD'
                },
                {
                    text: 'Name',
                    value: 'Name'
                },
                {
                    text: 'OUTSTANDING_ENC',
                    value: 'OUTSTANDING_ENC'
                },
                {
                    text: 'PER_PAY_PERIOD',
                    value: 'PER_PAY_PERIOD'
                },
                {
                    text: 'ACCOUNT_NM',
                    value: 'ACCOUNT_NM'
                },
                {
                    text: 'ACCT_SPVSR_NM',
                    value: 'ACCT_SPVSR_NM'
                },
                {
                    text: 'ORG_NM',
                    value: 'ORG_NM'
                },
                {
                    text: 'ACCT_EFFECT_DT',
                    value: 'ACCT_EFFECT_DT'
                },
                {
                    text: 'ACCT_EXPIRATION_DT',
                    value: 'ACCT_EXPIRATION_DT'
                },
                {
                    text: 'Sponsor',
                    value: 'Sponsor'
                }
            ],
            entries: [],
            editEntries: [],
            paste: {
                content: '',
                excelStructuredData: [],
                excelStructuredDataBody: [],
                excelStructuredDataHeaders: []
            },
            showPayPeriodMissingWarning: false
        }
    },
    methods: {
        ...mapMutations({
            updatePayrollEntries: 'UPDATE_PAYROLL_ENTRIES_FROM_PASTE'
        }),
        onPaste(event) {
            let clipboardData = window.clipboardData || event.clipboardData || event.originalEvent && event.originalEvent.clipboardData

            // From what I can tell, older browsers support the former, and newer browsers support text/plain
            let pastedText = clipboardData.getData('Text') || clipboardData.getData('text/plain')

            if ( clipboardData && pastedText ) {
                this.paste.content = pastedText

                this.getExcelStructuredData()

                if ( this.hasProperHeaders() ) {
                    this.getExcelStructuredDataWithoutHeaders()
                    this.getExcelStructuredDataHeaders()
                    this.createDataTablesItems()
                }
            }
        },
        commitToPayroll() {
            if ( this.entries && Array.isArray(this.entries) && this.entries.length > 0 ) {
                let payroll_entered = true
                this.entries.forEach( (e) => {
                    if ( !e.PER_PAY_PERIOD ) {
                        payroll_entered = false
                    }
                })
                if ( payroll_entered ) {
                    // Success condition
                    this.updatePayrollEntries( this.entries )
                    this.$router.push('/payroll')
                    return true;
                }
            }
            // Fail condition.
            this.openPayPeriodMissingWarning()
            return false;
        },
        editPerPayPeriodSalaries() {
            this.closeDialogs()
            this.closeAlerts()
            if ( Array.isArray(this.entries) && this.entries.length === 0 ) {
                this.clonePayrollEntries()
            }
            this.editEntries = JSON.parse(JSON.stringify(this.entries))
            this.editPayPeriodSalariesDialogOpen = true
        },
        closeDialogs() {
            this.editPayPeriodSalariesDialogOpen = false
        },
        savePayPeriodSalaryEdits() {
            this.entries = this.editEntries
            this.closeDialogs()
        },
        clonePayrollEntries() {
            this.entries = JSON.parse(JSON.stringify(this.dataTablesItems))
        },
        readableRollup(rollup_id) {
            if ( rollup_id === 'SALAR' ) {
                return 'Salary'
            } else if ( rollup_id === 'FRNGB' ) {
                return 'Fringe Benefit'
            }
            return ''
        },

        // https://gist.github.com/torjusb/7d6baf4b68370b4ef42f
        // This function is bonkers, sorry people.
        getExcelStructuredData() {
            if ( !this.paste.content ) {
                this.paste.excelStructuredData = [];
                return false;
            }
            // In plain english, first we need to parse out new lines into rows, however we want to avoid a situation where there were newlines in actual excel content.
            // For this purpose, we find new lines within quotes and replace them with spaces.
            // https://regexr.com/ is your friend, and I am so sorry.
            let rows = this.paste.content.replace(/"((?:[^"]*(?:\r\n|\n\r|\n|\r))+[^"]+)"/mg, function(match, p1) {
                return p1.replace(/""/g, '"').replace(/\r\n|\n\r|\n|\r/g, ' ')
            })

            // Next, we split by the remaining new line characters into rows.
            let row_data = rows.split(/\r\n|\n\r|\n|\r/g)

            // Last, we map the remaining values, separating by tabs.  You might notice that this would all of a sudden not work if there were tabs in multi-line content in excel.
            // We are just going to hope that doesn't happen, and by spec it really shouldn't without us knowing about a different delimiter.
            let row_data_with_cols = row_data.map( (val) => {
                return val.split(/\t/g)
            })
            this.paste.excelStructuredData = row_data_with_cols
            return true;
        },

        hasProperHeaders() {
            if ( Array.isArray(this.paste.excelStructuredData) && this.paste.excelStructuredData.length > 0 ) {
                let a = this.paste.excelStructuredData[0]
                if ( Array.isArray(a) && a.length > 0 ) {

                    // Only return the headers if we have the proper headers from the existing paste workflow, which starts with ACCOUNT_NBR.
                    if ( a[0] === 'ACCOUNT_NBR' ) {
                        return true
                    }
                }
            }
            return false
        },

        getExcelStructuredDataWithoutHeaders() {
            if ( Array.isArray(this.paste.excelStructuredData) && this.paste.excelStructuredData.length > 0 ) {
                let a = Array.from(this.paste.excelStructuredData)
                a.shift()
                this.paste.excelStructuredDataBody = a
                return a
            }
            this.paste.excelStructuredDataBody = []
            return []
        },

        getExcelStructuredDataHeaders() {
            if ( this.hasProperHeaders() ) {
                this.paste.excelStructuredDataHeaders = this.paste.excelStructuredData[0]
                return this.paste.excelStructuredData[0]
            }
            return []
        },

        createDataTablesItems() {
            let data = Array.from(this.paste.excelStructuredDataBody)
            let headers = Array.from(this.paste.excelStructuredDataHeaders)
            let data_tables_objects = data.map( (a) => {
                let b = {}
                headers.forEach( (h, index) => {
                    b[h] = a[index]
                })
                b['PER_PAY_PERIOD'] = 0
                return b
            })
            if ( data_tables_objects ) {
                this.entries = data_tables_objects
                return data_tables_objects
            }
            this.entries = []
            return []
        },
        closeAlerts() {
            this.closePayPeriodMissingWarning()
        },
        togglePayPeriodMissingWarning() {
            this.showPayPeriodMissingWarning = !this.showPayPeriodMissingWarning
        },
        openPayPeriodMissingWarning() {
            this.showPayPeriodMissingWarning = true
        },
        closePayPeriodMissingWarning() {
            this.showPayPeriodMissingWarning = false
        }
    },
    computed: {
    }
}
</script>

<style scoped>
pre {
    text-align: left;
    font-family: monospace;
    margin: 0 auto;
    display:inline-block;
}
table {
    text-align: left;
    margin: 0 auto;
}
</style>