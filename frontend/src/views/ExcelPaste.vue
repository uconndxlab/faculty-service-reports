<template>
    <div id="excel-paste">
        <h1>Excel Paste</h1>
        <p>Purpose of this experiment is to simulate the Payroll Data paste functionality into a tool, so we can start working with the data as a drop in to their workflow.</p>
        <input type="text" @paste="onPaste" placeholder="Paste Here">
        <p>Pasted Output:</p>
        <pre>{{ pastedContent }}</pre>

        <p>Pasted Table Structure:</p>
        <table v-if="excelStructuredData && excelStructuredData.length > 0">
            <thead>
                <tr>
                    <th v-for="(headerItem, index) in excelStructuredData[0]" :key="headerItem + index">
                        {{ headerItem }}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(row, i) in excelStructuredDataWithoutHeaders" :key="i">
                    <td v-for="entry in row" :key="entry">{{ entry }}</td>
                </tr>
            </tbody>
        </table>

        <p>Pasted Output (Assuming Excel):</p>
        <pre v-if="excelStructuredData && excelStructuredData.length > 0" v-html="excelStructuredData"></pre>

        <div class="spacer"></div>
    </div>
</template>

<script>
export default {
    name: "Excel-Paste",
    data() {
        return {
            pastedContent: ''
        }
    },
    methods: {
        onPaste() {
            let clipboardData = window.clipboardData || event.clipboardData || event.originalEvent && event.originalEvent.clipboardData

            // From what I can tell, older browsers support the former, and newer browsers support text/plain
            let pastedText = clipboardData.getData('Text') || clipboardData.getData('text/plain')

            console.log(pastedText)

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
        excelStructuredDataWithoutHeaders() {
            let a = this.excelStructuredData
            a.shift()
            return a
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

div.spacer {
    height: 300px;
    display: block;
    width: 100%;
}
</style>