<template>
   <div class="container">

      <form class="form-signin" @submit.prevent="login">
        <h2 class="form-signin-heading">Please sign in</h2>
        <label for="inputEmail" class="sr-only">Email address</label>
        <input required v-model="email" type="email" class="form-control" placeholder="Email address" autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input required v-model="password" type="password" id="inputPassword" class="form-control" placeholder="Password">
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>

        <br>
        <p class="text-danger" v-for="(value, name) in getErrorMessage" :key="name">
        {{ value }}
        </p>

      </form>

    </div> <!-- /container -->
</template>

<script>
  export default {
    data(){
      return {
        email : "",
        password : "",
        errorMessage: ""
      }
    },
    methods: {
      login: function () {
        let email = this.email 
        let password = this.password
        this.$store.dispatch('login', { email, password })
       .then(() => {
           this.$router.push('/')
        //    this.$router.go('/')
       })
       .catch(err => {
           console.log(JSON.stringify(err.response))
           this.errorMessage = err.response.data.errors;
       })
      }
    },
    computed: {
        getErrorMessage: function(){
           return this.errorMessage;
        }
    }
  }
</script>

<style>

</style>