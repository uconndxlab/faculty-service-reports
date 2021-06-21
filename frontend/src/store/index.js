/* eslint-disable no-unused-labels */
import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

const initialState = () => {
  return {
    account_number: ''
  }
}

export default new Vuex.Store({
  state: initialState(),
  mutations: {
    ASSIGN_ACCOUNT_NUMBER(state, val) {
      state.account_number = val
    }
  },
  getters: {
    getAccountNumber(state) {
      return state.account_number
    }
  },
  actions: {
  },
  modules: {
  }
})
