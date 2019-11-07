<template>
  <div class="container">
    <h2 class="form-signin-heading">Register</h2>
    <form class="form-signin" @submit.prevent="register">
      <label for="name">Name</label>
      <div>
          <input class="form-control" id="name" type="text" v-model="name" required autofocus>
      </div>

      <label for="email" >E-Mail Address</label>
      <div>
          <input class="form-control" id="email" type="email" v-model="email" required>
      </div>

      <label for="password">Password</label>
      <div>
          <input class="form-control" id="password" type="password" v-model="password" required>
      </div>

      <label for="password-confirm">Confirm Password</label>
      <div>
          <input class="form-control" id="password-confirm" type="password" v-model="password_confirmation" required>
      </div>

      <div>
          <button class="btn btn-lg btn-primary btn-block" type="submit">Register</button>

          <br>
          <p class="text-danger" v-for="(value, name) in getErrors" :key="name">
            {{ value.join(' ') }}
          </p>
        
      </div>
    </form>
  </div>
</template>

<script>
  export default {
    data(){
      return {
        name : "",
        email : "",
        password : "",
        password_confirmation : "",
        is_admin : null,
        errors: []
      }
    },
    methods: {
      register: function () {
        let data = {
          name: this.name,
          email: this.email,
          password: this.password,
          password_confirmation: this.password_confirmation,
          is_admin: this.is_admin
        }
        this.$store.dispatch('register', data)
       .then(() => this.$router.push('/'))
       .catch(err => {
         console.log(err.response)
         this.errors = err.response.data.errors
         })
      }
    },
    computed: {
      getErrors: function(){
        return this.errors
      }
    }
  }
</script>