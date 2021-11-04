<template>
  <v-app>
    <v-navigation-drawer
      app
    >
      <v-list dense>
        <v-list-item
          v-for="nav_item in navigationLinks"
          :key="nav_item.name"
          link
          :to="nav_item.to"
        >
          <v-list-item-icon>
            <v-icon>{{ nav_item.icon }}</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>{{ nav_item.name }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-divider></v-divider>

        <v-subheader class="pl-4">TOOLS</v-subheader>

        <v-list-item
            v-for="tl in tools_links"
            :key="tl.name"
            link
            :to="tl.to"
          >
            <v-list-item-icon>
              <v-icon>{{ tl.icon }}</v-icon>
            </v-list-item-icon>
            <v-list-item-title v-text="tl.name"></v-list-item-title>
          </v-list-item>
      </v-list>
    </v-navigation-drawer>

    <v-app-bar
      app
      color="primary"
      dark
    >
      <div class="d-flex align-center">
        <v-toolbar-title>Faculty Service Reports</v-toolbar-title>
      </div>

      <v-spacer></v-spacer>

      <v-btn
        @click="openSupportFeedbackDialog()"
        text
      >
        <span>Support &amp; Feedback</span>
      </v-btn>
    </v-app-bar>

    <v-main>
      <support-feedback-dialog
        ref="support_feedback_dialog"
      ></support-feedback-dialog>
      <router-view/>
    </v-main>
  </v-app>
</template>

<script>
import { mapGetters } from 'vuex'
import SupportFeedbackDialog from '@/components/SupportFeedbackDialog.vue'

export default {
    name: 'App',
    components: { SupportFeedbackDialog },
    data: () => ({
        tools_links: [
            { to: '/pay-period-calculator', name: 'Pay Period Calculator', icon: 'mdi-currency-usd' },
            { to: '/excel-paste', name: 'Excel Paste', icon: 'mdi-content-paste' },
            { to: '/export-pdf', name: 'Export PDF', icon: 'mdi-file-pdf'}
        ],
    }),
    computed: {
        ...mapGetters({
            account_number: 'getAccountNumber'
        }),
        accountCurrentlyRegistered() {
            return !!this.account_number
        },
        navigationLinks() {
            let links = [
                { to: '/', name: 'Home', icon: 'mdi-home' }
            ]
            if ( this.account_number ) {
                let account_routes = [
                    { to: '/account/' + this.account_number, name: 'Account Summary', icon: 'mdi-view-dashboard' },
                    { to: '/payroll', name: 'Payroll', icon: 'mdi-currency-usd' }
                ]
                links.push(...account_routes)
            } else {
                links.push({ to: '/payroll', name: 'Payroll', icon: 'mdi-currency-usd' })
            }
            return links
        }
    },
    methods: {
        openSupportFeedbackDialog() {
            this.$refs.support_feedback_dialog.open()
        }
    }
}
</script>
