<template>
    <div id="pay-period-calculator-page" class="mt-6">
        <v-container>
            <v-row
                justify="center"
            >
                <v-col>
                    <pay-period-calculator></pay-period-calculator>
                </v-col>
            </v-row>
        </v-container>
        
    </div>
</template>

<script>
import PayPeriodCalculator from '@/components/PayPeriodCalculator.vue'
export default {
    components: { PayPeriodCalculator },
}
</script>