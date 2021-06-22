<template>
    <div class="export-pdf-page">
        <v-container>
            <v-row
                justify="center"
            >
                <v-col>
                    <h1>Export PDF Tool</h1>

                    <v-btn
                        type="primary"
                        @click="writePDF()"
                    >Generate PDF</v-btn>

                    <div class="preview">
                        <ul id="buckets_list">
                            <li
                                v-for="(buck, i) in buckets"
                                :key="i"
                            >
                                Name: {{ buck.name }}; Price: ${{ buck.price }}
                            </li>
                        </ul>
                        <ul id="riley_list">
                            <li
                                v-for="(ri, j) in riley"
                                :key="j"
                            >
                                Riley: {{ ri.jonsey }}
                            </li>
                        </ul>
                        <ul id="get_after_it">
                            <li
                                v-for="(gai, k) in get_after_it"
                                :key="k"
                            >
                                Wayne: {{ gai.wayne }}
                            </li>
                        </ul>
                    </div>
                </v-col>
            </v-row>
        </v-container>
        
    </div>
</template>

<script>
import { jsPDF } from "jspdf"

export default {
    data: () => ({
        buckets: [
            { name: 'BK', price: "12.89" },
            { name: 'KB', price: "14.90" },
            { name: 'BBKK', price: "22.70" },
            { name: 'JK', price: "0.80" },
            { name: 'JK', price: "0.80" },
            { name: 'JK', price: "0.80" },
            { name: 'JK', price: "0.80" },
            { name: 'JK', price: "0.80" },
            { name: 'JK', price: "0.80" },
            { name: 'JK', price: "0.80" },
            { name: 'JK', price: "0.80" },
            { name: 'JK', price: "0.80" },
            { name: 'JK', price: "0.80" },
            { name: 'JK', price: "0.80" },
            { name: 'JK', price: "0.80" },
            { name: 'JK', price: "0.80" },
            { name: 'JK', price: "0.80" },
            { name: 'JK', price: "0.80" },
            { name: 'JK', price: "0.80" },
            { name: 'JK', price: "0.80" },
            { name: 'JK', price: "0.80" },
            { name: 'JK', price: "0.80" },
            { name: 'JK', price: "0.80" },
            { name: 'JK', price: "0.80" },
            { name: 'JK', price: "0.80" },
            { name: 'JK', price: "0.80" },
            { name: 'JK', price: "0.80" },
            { name: 'JK', price: "0.80" },
            { name: 'JK', price: "0.80" },
            { name: 'JK', price: "0.80" },
            { name: 'JK', price: "0.80" },
            { name: 'JK', price: "0.80" },
            { name: 'JK', price: "0.80" },
            { name: 'JK', price: "0.80" },
            { name: 'JK', price: "0.80" },
            { name: 'JK', price: "0.80" },
            { name: 'JK', price: "0.80" },
            { name: 'JK', price: "0.80" }
        ],
        riley: [
            { jonsey: 'Abc123' },
            { jonsey: 'cca21' },
            { jonsey: 'faaafaa' }
        ],
        get_after_it: [
            { wayne: 'how are ya now' },
            { wayne: 'good n you' },
            { wayne: 'not so bad' },
            { wayne: 'chorin' }
        ]
    }),
    methods: {
        writePDF() {
            const doc = new jsPDF({ 
                orientation: 'landscape'
            })
            doc.table(1, 1, this.buckets, [{ id: 'name', name: 'name', prompt: 'name', width: 65, align: "center" }, { id: 'price', name: 'price', prompt: 'price', width: 65, align: "center" }])
            doc.addPage('', 'l')
            doc.table(1, 1, this.riley, [{ id: 'jonsey', name: 'jonsey', prompt: 'jonsey', width: 65, align: "center" }])
            doc.save('report.pdf')
        }
    }
}
</script>