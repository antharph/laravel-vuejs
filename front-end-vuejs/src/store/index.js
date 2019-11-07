import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'

const token = localStorage.getItem('token')
if (token) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
}

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    status: '',
    token: localStorage.getItem('token') || '',
    user : {}
  },
  mutations: {
    auth_request(state){
      state.status = 'loading'
    },
    auth_success(state, token, user){
      state.status = 'success'
      state.token = token
      state.user = user
    },
    auth_error(state){
      state.status = 'error'
    },
    logout(state){
      state.status = ''
      state.token = ''
    }
  },
  actions: {
    login({commit}, user){
        return new Promise((resolve, reject) => {
          commit('auth_request')
          axios({url: this.getters.baseUrl + '/api/login', data: user, method: 'POST' })
          .then(resp => {
            console.log(resp);
            const token = resp.data.token
            const user = resp.data.user
            localStorage.setItem('token', token)
            axios.defaults.headers.common['Authorization'] = token
            commit('auth_success', token, user)
            resolve(resp)
          })
          .catch(err => {
            commit('auth_error')
            localStorage.removeItem('token')
            reject(err)
          })
        })
    },
    register({commit}, user){
      return new Promise((resolve, reject) => {
        axios({url: this.getters.baseUrl + '/api/register', data: user, method: 'POST' })
        .then(resp => {
          resolve(resp)
        })
        .catch(err => {
          reject(err)
        })
      })
    },
    logout({commit}){
      return new Promise((resolve, reject) => {
        commit('logout')
        localStorage.removeItem('token')
        delete axios.defaults.headers.common['Authorization']
        resolve()
      })
    },
    posts(){
      return new Promise((resolve, reject) => {
        axios({url: this.getters.baseUrl + '/api/posts', method: 'GET' })
        .then(resp => {
          // console.log(resp);
          // const data = resp.data
          resolve(resp)
        })
        .catch(err => {
          reject(err)
        })
      })
    },
    addPost({commit}, post){
      return new Promise((resolve, reject) => {
        axios({url: this.getters.baseUrl + '/api/posts', data: post, method: 'POST' })
        .then(resp => {
          resolve(resp)
        })
        .catch(err => {
          reject(err)
        })
      })
    },
  },
  modules: {
  },
  getters : {
    isLoggedIn: state => !!state.token,
    authStatus: state => state.status,
    baseUrl: state => 'http://local.ligph.com:8081'
  }
})
