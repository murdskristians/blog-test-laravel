<template>
    <div>
      <h1>Blog Posts</h1>
      <div v-for="post in blogPosts" :key="post.id" class="post">
        <h2>{{ post.title }}</h2>
        <p>{{ post.body }}</p>
        <small>by {{ post.user.name }} on {{ post.created_at }}</small>
        <button @click="editPost(post.id)">Edit</button>
        <button @click="deletePost(post.id)">Delete</button>
        <div v-for="comment in post.comments" :key="comment.id" class="comment">
          <p>{{ comment.content }}</p>
          <small>by {{ comment.user.name }} on {{ comment.created_at }}</small>
          <button @click="deleteComment(comment.id)">Delete</button>
        </div>
        <form @submit.prevent="addComment(post.id)">
          <textarea v-model="newComment"></textarea>
          <button type="submit">Add Comment</button>
        </form>
      </div>
      <form @submit.prevent="createPost">
        <input v-model="newPost.title" placeholder="Title" />
        <textarea v-model="newPost.body" placeholder="Body"></textarea>
        <button type="submit">Create Post</button>
      </form>
    </div>
  </template>

  <script>
  import axios from 'axios';

  export default {
    data() {
      return {
        blogPosts: [],
        newPost: {
          title: '',
          body: ''
        },
        newComment: ''
      };
    },
    methods: {
      fetchBlogPosts() {
        axios.get('/api/blog_posts')
          .then(response => {
            this.blogPosts = response.data;
          });
      },
      createPost() {
        axios.post('/api/blog_posts', this.newPost)
          .then(() => {
            this.fetchBlogPosts();
            this.newPost.title = '';
            this.newPost.body = '';
          });
      },
      editPost(postId) {
        // Implement edit post logic
      },
      deletePost(postId) {
        axios.delete(`/api/blog_posts/${postId}`)
          .then(() => {
            this.fetchBlogPosts();
          });
      },
      addComment(postId) {
        axios.post(`/api/blog_posts/${postId}/comments`, { content: this.newComment })
          .then(() => {
            this.fetchBlogPosts();
            this.newComment = '';
          });
      },
      deleteComment(commentId) {
        axios.delete(`/api/comments/${commentId}`)
          .then(() => {
            this.fetchBlogPosts();
          });
      }
    },
    mounted() {
      this.fetchBlogPosts();
    }
  };
  </script>

  <style>
  /* Add styles for the component */
  </style>
