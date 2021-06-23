/* eslint-disable no-unused-labels,no-unused-vars */
import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const initialAccount = () => {
  return {
    number: '',
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
    transaction_categories: {
      salary: {
        name: 'Salary',
        budget: 61015.00,
        actual: 55777.47,
        encumbrance: 53002.41,
        available_balance: 47764.88,
        additional_encumbrance: 0,
        transactions: []
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
    account: seedAccount()
  }
}

export default new Vuex.Store({
  state: initialState(),
  mutations: {
    ASSIGN_ACCOUNT_NUMBER(state, val) {
      state.account.number = val
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
    }
  },
  actions: {
  },
  modules: {
  }
})
