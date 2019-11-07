<template>
    <div class="container">
        <form @submit.prevent="post">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" v-model="title" class="form-control" id="title" placeholder="Title">
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <ckeditor :editor="editor" v-model="editorData" :config="editorConfig"></ckeditor>
            </div>
            <button type="submit" class="btn btn-default">Submit</button>
            </form>
    </div>
</template>

<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
  export default {
    data(){
      return {
          title: '',
          editor: ClassicEditor,
          editorData: '<p>Rich-text editor content.</p>',
          editorConfig: {
              // The configuration of the rich-text editor.
          }
      }
    },
    methods: {
      post: function () {
        let data = {
          title: this.title,
          content: this.editorData,
        }
        this.$store.dispatch('addPost', data)
       .then(() => this.$router.push('/'))
       .catch(err => {
         console.log(err.response)
         this.errors = err.response.data.errors
         })
      }
    },
    computed: {
    }
  }
</script>

<style>
.ck-content { height:300px; }
</style>