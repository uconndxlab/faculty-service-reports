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

                    <p v-if="hasProperHeaders">Proper Header Rows Detected!</p>
                    <p v-else>Incorrect Header Rows.</p>

                    <h2>Pasted Table Data</h2>

                    <v-data-table
                        :headers="standard_table_headers"
                        :items="dataTablesItems"
                        :hide-default-footer="true"
                    ></v-data-table>
                </v-col>
            </v-row>
        </v-container>
    </div>
</template>

<script>
import TestDataWarning from '@/components/TestDataWarning.vue'
export default {
    name: "Excel-Paste",
    components: { TestDataWarning },
    data() {
        return {
            pastedContent: '',
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
            ]
        }
    },
    methods: {
        onPaste(event) {
            let clipboardData = window.clipboardData || event.clipboardData || event.originalEvent && event.originalEvent.clipboardData

            // From what I can tell, older browsers support the former, and newer browsers support text/plain
            let pastedText = clipboardData.getData('Text') || clipboardData.getData('text/plain')

            this.pastedContent = pastedText
        }
    },
    computed: {
        // https://gist.github.com/torjusb/7d6baf4b68370b4ef42f
        // This function is bonkers, sorry people.
        excelStructuredData() {
            if ( !this.pastedContent ) {
                return [];
            }
            // In plain english, first we need to parse out new lines into rows, however we want to avoid a situation where there were newlines in actual excel content.
            // For this purpose, we find new lines within quotes and replace them with spaces.
            // https://regexr.com/ is your friend, and I am so sorry.
            let rows = this.pastedContent.replace(/"((?:[^"]*(?:\r\n|\n\r|\n|\r))+[^"]+)"/mg, function(match, p1) {
                return p1.replace(/""/g, '"').replace(/\r\n|\n\r|\n|\r/g, ' ')
            })

            // Next, we split by the remaining new line characters into rows.
            let row_data = rows.split(/\r\n|\n\r|\n|\r/g)

            // Last, we map the remaining values, separating by tabs.  You might notice that this would all of a sudden not work if there were tabs in multi-line content in excel.
            // We are just going to hope that doesn't happen, and by spec it really shouldn't without us knowing about a different delimiter.
            let row_data_with_cols = row_data.map( (val) => {
                return val.split(/\t/g)
            })
            return row_data_with_cols
        },
        hasProperHeaders() {
            if ( Array.isArray(this.excelStructuredData) && this.excelStructuredData.length > 0 ) {
                let a = this.excelStructuredData[0]
                if ( Array.isArray(a) && a.length > 0 ) {

                    // Only return the headers if we have the proper headers from the existing paste workflow, which starts with ACCOUNT_NBR.
                    if ( a[0] === 'ACCOUNT_NBR' ) {
                        return true
                    }
                }
            }
            return false
        },
        excelStructuredDataWithoutHeaders() {
            if ( Array.isArray(this.excelStructuredData) && this.excelStructuredData.length > 0 ) {
                let a = Array.from(this.excelStructuredData)
                a.shift()
                return a
            }
            return []
        },
        excelStructuredDataHeaders() {
            if ( this.hasProperHeaders ) {
                return this.excelStructuredData[0]
            }
            return []
        },
        dataTablesItems() {
            let data = Array.from(this.excelStructuredDataWithoutHeaders)
            let headers = Array.from(this.excelStructuredDataHeaders)
            let data_tables_objects = data.map( (a) => {
                let b = {}
                headers.forEach( (h, index) => {
                    b[h] = a[index]
                })
                return b
            })
            if ( data_tables_objects ) {
                return data_tables_objects
            }
            return []
        }
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