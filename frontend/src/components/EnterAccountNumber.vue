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
                            @submit.prevent="navigateToAccount()"
                        >
                            <p class="mb-3">Submit to retrieve account details for the given KFS account.</p>
                            <v-text-field
                                label="KFS Account Number"
                                placeholder="123456789"
                                v-model="account_number"
                                :rules="account_number_validation_rules"
                                validate-on-blur
                            ></v-text-field>

                            <v-menu
                                ref="report_date_menu"
                                v-model="report_date_menu_open"
                                :close-on-content-click="false"
                                :return-value.sync="report_date"
                                transition="scale-transition"
                                offset-y
                                max-width="290px"
                                min-width="auto"
                            >
                                <template v-slot:activator="{ on, attrs }">
                                    <v-text-field
                                        v-model="report_date"
                                        label="Report Date"
                                        prepend-icon="mdi-calendar"
                                        readonly
                                        v-bind="attrs"
                                        v-on="on"
                                    ></v-text-field>
                                </template>

                                <v-date-picker
                                    v-model="report_date"
                                    type="month"
                                    class="mt-4"
                                    min="2020-01"
                                    :max="maximumDate"
                                >
                                    <v-spacer></v-spacer>
                                    <v-btn
                                        text
                                        color="primary"
                                        @click="report_date_menu_open = false"
                                    >
                                        Cancel
                                    </v-btn>
                                    <v-btn
                                        text
                                        color="primary"
                                        @click="$refs.report_date_menu.save(report_date)"
                                    >
                                        OK
                                    </v-btn>
                                </v-date-picker>
                            </v-menu>

                            <v-btn
                                color="primary"
                                :loading="lookup_button_loading"
                                :disabled="lookup_button_loading"
                                class="mt-2"
                                type="submit"
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
        report_date: '2021-01',
        report_date_menu_open: false,
        account_number_validation_rules: [
            v => !!v || 'Account Number is required',
            v => (v && v.length > 6) || 'Please Enter a valid Account Number'
        ],
        lookup_button_loading: false
    }),
    computed: {
        ...mapGetters({
            last_account_number: 'getAccountNumber'
        }),
        maximumDate() {
            let d = new Date()
            let m = d.getMonth() + 1
            if ( m < 10 ) {
                m = '0' + m
            } else {
                m = `${m}`
            }
            return `${d.getFullYear()}-${m}`
        }
    },
    methods: {
        ...mapMutations(['ASSIGN_ACCOUNT_NUMBER', 'ASSIGN_REPORT_DATE']),
        navigateToAccount() {
            this.lookup_button_loading = true
            setTimeout(() => {
                let valid = this.enterAccountNumberFormValid()
                if ( valid ) {
                    this.ASSIGN_ACCOUNT_NUMBER(this.account_number)
                    this.ASSIGN_REPORT_DATE(this.report_date)
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