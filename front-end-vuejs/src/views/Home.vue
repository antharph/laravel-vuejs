<template>
  <div class="container">
    <!-- <img alt="Vue logo" src="../assets/logo.png"> -->
    <h3>Post</h3>
    <div v-for="(p,i) in getPosts" :key="i">
      <table class="table table-bordered post">
        <tr>
          <th width="150">Title</th>
          <td>{{p.title}}</td>
        </tr>
        <tr>
          <th>Slug</th>
          <td>{{p.slug}}</td>
        </tr>
        <tr>
          <th>Content</th>
          <td><span v-html="p.content"></span></td>
        </tr>
        <tr>
          <th>Published Date</th>
          <td>{{p.created_at}}</td>
        </tr>
      </table>
    </div>
    
  </div>
</template>

<script>
// @ is an alias to /src
import HelloWorld from '@/components/HelloWorld.vue'

export default {
  name: 'home',
  components: {
    // HelloWorld
  },
  data(){
    return {
      posts: []
    }
  },
  mounted: function(){
        this.$store.dispatch('posts',)
       .then(resp => {
          console.log(resp)
          this.posts = resp.data.data
       })
       .catch(err => {
       })
  },
  computed: {
    getPosts: function(){
      return this.posts
    }
  }
}
</script>

<style>
table.post td{
  text-align: left;
}
</style>
