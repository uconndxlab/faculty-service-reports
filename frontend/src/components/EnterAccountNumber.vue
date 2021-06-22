<template>
    <v-container>
        <v-row justify="center">
            <v-col md="6">
                <v-card>
                    <v-card-title>Account Lookup</v-card-title>
                    <v-card-text>
                        <v-form
                            ref="enter_account_number_form"
                            lazy-validation
                        >
                            <v-text-field
                                label="KFS Account Number"
                                placeholder="123456789"
                                v-model="account_number"
                                :rules="account_number_validation_rules"
                                validate-on-blur
                            ></v-text-field>

                            <v-btn
                                color="primary"
                                @click="navigateToAccount()"
                                class="mt-2"
                            >Lookup</v-btn>
                        </v-form>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
import { mapMutations, mapGetters } from 'vuex'

export default {
    data: () => ({
        account_number: '',
        account_number_validation_rules: [
            v => !!v || 'Account Number is required',
            v => (v && v.length > 6) || 'Please Enter a valid Account Number'
        ]
    }),
    computed: {
        ...mapGetters({
            last_account_number: 'getAccountNumber'
        })
    },
    methods: {
        ...mapMutations(['ASSIGN_ACCOUNT_NUMBER']),
        navigateToAccount() {
            let valid = this.enterAccountNumberFormValid()
            if ( valid ) {
                this.ASSIGN_ACCOUNT_NUMBER(this.account_number)
                this.$router.push({
                    name: 'account',
                    params: {
                        acc_num: this.account_number
                    }
                })
            } else {
                console.log('Not Valid')
            }
        },
        enterAccountNumberFormValid() {
            if ( this.$refs && this.$refs.enter_account_number_form ) {
                return this.$refs.enter_account_number_form.validate()
            }
            return false
        }
    },
    mounted() {
        if ( this.last_account_number ) {
            this.account_number = this.last_account_number
        }
    }
}
</script>