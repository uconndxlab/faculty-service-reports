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
                                :loading="lookup_button_loading"
                                :disabled="lookup_button_loading"
                                @click="navigateToAccount()"
                                class="mt-2"
                            >Lookup
                                <template v-slot:loader>
                                    <span class="custom-loader">
                                        <v-icon light>mdi-cached</v-icon>
                                    </span>
                                </template>
                            </v-btn>
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
        ],
        lookup_button_loading: false
    }),
    computed: {
        ...mapGetters({
            last_account_number: 'getAccountNumber'
        })
    },
    methods: {
        ...mapMutations(['ASSIGN_ACCOUNT_NUMBER']),
        navigateToAccount() {
            this.lookup_button_loading = true
            setTimeout(() => {
                let valid = this.enterAccountNumberFormValid()
                if ( valid ) {
                    this.ASSIGN_ACCOUNT_NUMBER(this.account_number)
                    this.lookup_button_loading = false
                    this.$router.push({
                        name: 'account',
                        params: {
                            acc_num: this.account_number
                        }
                    })
                } else {
                    console.log('Not Valid')
                    this.lookup_button_loading = false
                }
            }, 600);
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

<style scoped>
.custom-loader {
    animation: loader 1s infinite;
    display: flex;
  }
  @-moz-keyframes loader {
    from {
      transform: rotate(0);
    }
    to {
      transform: rotate(360deg);
    }
  }
  @-webkit-keyframes loader {
    from {
      transform: rotate(0);
    }
    to {
      transform: rotate(360deg);
    }
  }
  @-o-keyframes loader {
    from {
      transform: rotate(0);
    }
    to {
      transform: rotate(360deg);
    }
  }
  @keyframes loader {
    from {
      transform: rotate(0);
    }
    to {
      transform: rotate(360deg);
    }
  }
</style>