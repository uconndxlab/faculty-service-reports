<template>
    <v-dialog
        v-model="dialogOpen"
        max-width="600"
    >
        <v-card>
            <v-card-title>
                <span class="text-h5 mb-2">Support &amp; Feedback</span>
            </v-card-title>
            <v-card-text>
                <div>If you have any feedback on the usability/functionality of this application, or are looking for help/support, please contact the DX Lab at UConn (dx@uconn.edu).</div>
                <v-btn
                    href="mailto:dx@uconn.edu?subject=VPR%20Faculty%20Services%20Support"
                    class="mt-3"
                    type="primary"
                >
                    Email dx@uconn.edu
                </v-btn>
            </v-card-text>
            <v-divider></v-divider>
            <v-card-actions>
                <v-btn
                    text
                    @click="close()"
                >Close</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
export default {
    data() {
        return {
            dialogOpen: false
        }
    },
    methods: {
        close() {
            this.dialogOpen = false
        },
        open() {
            this.dialogOpen = true
        }
    }
}
</script>