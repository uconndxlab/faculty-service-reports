/* eslint-disable no-unused-labels,no-unused-vars */
import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const initialAccount = () => {
  return {
    number: '',
    pay_periods_remaining: {
      grad: 0,
      nineTenFaculty: 0
    },
    date: {
      start: '01/01/2021',
      end: '01/31/2021'
    },
    project_end: '01/01/2022',
    transaction_categories: {
      salary: {
        name: 'Salary',
        budget: 0,
        actual: 0,
        encumbrance: 0,
        available_balance: 0,
        additional_encumbrance: 0,
        transactions: []
      },
      fringe_benefits: {
        name: 'Fringe Benefits',
        budget: 0,
        actual: 0,
        encumbrance: 0,
        available_balance: 0,
        additional_encumbrance: 0,
        transactions: []
      },
      contractuals_commodities: {
        name: 'Contractuals/Commodities',
        budget: 0,
        actual: 0,
        encumbrance: 0,
        available_balance: 0,
        additional_encumbrance: 0,
        transactions: []
      },
      travel_domestic: {
        name: 'Travel - Domestic',
        budget: 0,
        actual: 0,
        encumbrance: 0,
        available_balance: 0,
        additional_encumbrance: 0,
        transactions: []
      },
      equipment_gt_5000: {
        name: 'Equipment > $5,000',
        budget: 0,
        actual: 0,
        encumbrance: 0,
        available_balance: 0,
        additional_encumbrance: 0,
        transactions: []
      },
      indirect_cost: {
        name: 'Indirect Cost',
        budget: 0,
        actual: 0,
        encumbrance: 0,
        available_balance: 0,
        additional_encumbrance: 0,
        transactions: []
      }
    }
  }
}

const seedAccount = () => {
  return {
    number: '',
    pay_periods_remaining: {
      grad: 9.6,
      nineTenFaculty: 2.1
    },
    date: {
      start: '01/01/2021',
      end: '01/31/2021'
    },
    project_end: '01/01/2022',
    transaction_categories: {
      salary: {
        name: 'Salary',
        budget: 61015.00,
        actual: 55777.47,
        encumbrance: 53002.41,
        available_balance: 47764.88,
        additional_encumbrance: 0,
        transactions: [
          {
            object_code: '5250',
            person: 'Person A',
            outstanding_encum: 3063.19,
            per_pay_period: 1458.66,
            pay_period_currently_encumbered: 3.1,
            additional_pay_periods: 0.00,
            additional_to_be_encumbered: 0.00,
            fringe: false
          },
          {
            object_code: '5260',
            person: 'Person B',
            outstanding_encum: 2675.76,
            per_pay_period: 668.94,
            pay_period_currently_encumbered: 0,
            additional_pay_periods: 0.00,
            additional_to_be_encumbered: 0.00,
            fringe: false
          },
          {
            object_code: '5250',
            person: 'Person A',
            outstanding_encum: 474.81,
            per_pay_period: 226.09,
            pay_period_currently_encumbered: 2.1,
            additional_pay_periods: 0.00,
            additional_to_be_encumbered: 0.00,
            fringe: true
          },
          {
            object_code: '5260',
            person: 'Person B',
            outstanding_encum: 414.72,
            per_pay_period: 103.69,
            pay_period_currently_encumbered: 2.1,
            additional_pay_periods: 0.00,
            additional_to_be_encumbered: 0.00,
            fringe: true
          }
        ]
      },
      fringe_benefits: {
        name: 'Fringe Benefits',
        budget: 22174.82,
        actual: 18223.18,
        encumbrance: 13883.78,
        available_balance: 9932.14,
        additional_encumbrance: 0,
        transactions: []
      },
      contractuals_commodities: {
        name: 'Contractuals/Commodities',
        budget: 138186.00,
        actual: 60113.69,
        encumbrance: 8654.17,
        available_balance: 69418.14,
        additional_encumbrance: 0,
        transactions: []
      },
      travel_domestic: {
        name: 'Travel - Domestic',
        budget: 2413.00,
        actual: 0,
        encumbrance: 0,
        available_balance: 2413.00,
        additional_encumbrance: 0,
        transactions: []
      },
      equipment_gt_5000: {
        name: 'Equipment > $5,000',
        budget: 30000.00,
        actual: 29994.95,
        encumbrance: 0,
        available_balance: 5.05,
        additional_encumbrance: 30000.00,
        transactions: []
      },
      indirect_cost: {
        name: 'Indirect Cost',
        budget: 136511.18,
        actual: 81809.78,
        encumbrance: 0,
        available_balance: 54701.40,
        additional_encumbrance: 0,
        transactions: []
      }
    }
  }
}

const initialState = () => {
  return {
    // account: initialAccount()
    account: seedAccount(),
    fiscal_encumber_through_dates: {
      payroll: '06/03/2021',
      nine_month_payroll: '05/22/2021'
    },
    pasted: {
      content: ''
    }
  }
}

export default new Vuex.Store({
  state: initialState(),
  mutations: {
    ASSIGN_ACCOUNT_NUMBER(state, val) {
      state.account.number = val
    },
    UPDATE_GRAD_PAY_PERIOD(state, val) {
      state.account.pay_periods_remaining.grad = val
    },
    UPDATE_NINE_TEN_FACULTY_PAY_PERIOD(state, val) {
      state.account.pay_periods_remaining.nineTenFaculty = val
    },
    UPDATE_SALARY_FRINGE_BOOLEAN(state, val) {
      if ( val.index && val.value ) {
        state.account.transaction_categories.salary.transactions[val.index].fringe = val.value
      }
    },
    UPDATE_PAYROLL_ENTRIES_FROM_PASTE(state, val) {
      if ( Array.isArray(val) && val.length > 0 && val[0].ACCOUNT_NBR ) {
        let new_vals = val.map( (a) => {
          return {
            object_code: a.FIN_OBJECT_CD,
            person: a.Name,
            outstanding_encum: a.OUTSTANDING_ENC,
            per_pay_period: a.PER_PAY_PERIOD,
            pay_period_currently_encumbered: 0,
            additional_pay_periods: 0.00,
            additional_to_be_encumbered: 0.00,
            fringe: a.ROLLUP_ID === 'SALAR' ? false : true
          }
        })
        state.account.transaction_categories.salary.transactions = new_vals
      }
    },
    UPDATE_ACCOUNT_END_DATE_FROM_PASTE(state, val) {
      if ( Array.isArray(val) && val.length > 0 && val[0].ACCT_EXPIRATION_DT ) {
        // state.account.project_end = 
      }
    },
    UPDATE_PASTED_CONTENT(state, val) {
      state.pasted.content = val
    }
  },
  getters: {
    getAccountNumber(state) {
      return state.account.number
    },
    getAccount(state) {
      return state.account
    },
    getAccountSummary(state) {
      const { ...sal_partial_state } = state.account.transaction_categories.salary
      const { ...fringe_partial_state } = state.account.transaction_categories.fringe_benefits
      const { ...contract_partial_state } = state.account.transaction_categories.contractuals_commodities
      const { ...travel_domestic_partial_state } = state.account.transaction_categories.travel_domestic
      const { ...eq5000_partial_state } = state.account.transaction_categories.equipment_gt_5000
      const { ...indirect_partial_state } = state.account.transaction_categories.indirect_cost

      delete sal_partial_state.transactions
      delete fringe_partial_state.transactions
      delete contract_partial_state.transactions
      delete travel_domestic_partial_state.transactions
      delete eq5000_partial_state.transactions
      delete indirect_partial_state.transactions

      return [
        sal_partial_state,
        fringe_partial_state,
        contract_partial_state,
        travel_domestic_partial_state,
        eq5000_partial_state,
        indirect_partial_state
      ]
    },
    getAccountSalaryTransactions(state) {
      return state.account.transaction_categories.salary.transactions
    },
    getReportStartDate(state) {
      return state.account.date.start
    },
    getReportEndDate(state) {
      return state.account.date.end
    },
    getFiscalEncumberThroughDatePayroll(state) {
      return state.fiscal_encumber_through_dates.payroll
    },
    getProjectEndDate(state) {
      return state.account.project_end
    },
    getFiscalEncumberThroughDateNineMonthPayroll(state) {
      return state.fiscal_encumber_through_dates.nine_month_payroll
    },
    getPastedContent(state) {
      return state.pasted.content
    }
  },
  actions: {
  },
  modules: {
  }
})
