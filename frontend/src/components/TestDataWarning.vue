<template>
    <v-card elevation="2" class="test-data-warning">
        <v-card-title class="text-subtitle-1">{{ display_header_message }}</v-card-title>
        <v-card-text>
            <span class="text-body-2">{{ display_body_message }}</span>
        </v-card-text>
    </v-card>
</template>

<script>
export default {
    name: 'test-data-warning',
    props: ['headerMessage', 'bodyMessage'],
    computed: {
        display_header_message() {
            if ( this.headerMessage ) {
                return this.headerMessage
            }
            return 'This page is using fake data.'
        },
        display_body_message() {
            if ( this.bodyMessage ) {
                return this.bodyMessage
            }
            return 'This is not yet hooked up to a live set, so we are displaying some test data here.'
        }
    }
}
</script>

<style scoped>
.test-data-warning {
    border: 3px solid #FFEB3B;
    background-color: #FFF9C4;
}
/* .test-data-warning {
    border: 3px solid #EF5350;
    background-color: #FFEBEE;
} */
</style>