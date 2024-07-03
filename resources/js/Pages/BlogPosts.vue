<template>
    <div class="container">
      <h1 class="title">Blog Posts</h1>
      <button @click="goToDashboard" class="button back-button">Back to Dashboard</button>
      <div v-for="post in posts" :key="post.id" class="post">
        <h2 class="post-title">{{ post.title }}</h2>
        <p class="post-body">{{ post.body }}</p>
        <small class="post-meta">by {{ post.user.name }} on {{ post.created_at }}</small>
        <div class="post-buttons">
          <button @click="editPost(post.id)" class="button">Edit</button>
          <button @click="deletePost(post.id)" class="button">Delete</button>
        </div>
        <div v-for="comment in post.comments" :key="comment.id" class="comment">
          <p class="comment-body">{{ comment.content }}</p>
          <small class="comment-meta">by {{ comment.user.name }} on {{ comment.created_at }}</small>
          <button @click="deleteComment(comment.id)" class="button">Delete</button>
        </div>
        <form @submit.prevent="addComment(post.id)" class="comment-form">
          <textarea v-model="newComment" placeholder="New Comment" class="textarea"></textarea>
          <button type="submit" class="button">Add Comment</button>
        </form>
      </div>
      <form @submit.prevent="createPost" class="new-post-form">
        <input v-model="newPost.title" placeholder="Title" class="input" />
        <textarea v-model="newPost.content" placeholder="Body" class="textarea"></textarea>
        <button type="submit" class="button">Create Post</button>
      </form>
    </div>
  </template>

  <script>
  import axios from '../requests';
  import { Inertia } from '@inertiajs/inertia';

  export default {
    data() {
      return {
        posts: [],
        newPost: {
          title: '',
          content: ''
        },
        newComment: ''
      };
    },
    methods: {
      fetchBlogPosts() {
        axios.get('/api/blog_posts')
          .then(response => {
            this.posts = response.data;
          })
          .catch(error => {
            console.error('Error fetching blog posts:', error);
          });
      },
      deletePost(postId) {
        axios.delete(`/api/blog_posts/${postId}`)
          .then(() => {
            this.fetchBlogPosts();
          })
          .catch(error => {
            console.error('Error deleting post:', error);
          });
      },
      addComment(postId) {
        axios.post(`/api/blog_posts/${postId}/comments`, { content: this.newComment })
          .then(() => {
            this.fetchBlogPosts();
            this.newComment = '';
          })
          .catch(error => {
            console.error('Error adding comment:', error);
          });
      },
      deleteComment(commentId) {
        axios.delete(`/api/comments/${commentId}`)
          .then(() => {
            this.fetchBlogPosts();
          })
          .catch(error => {
            console.error('Error deleting comment:', error);
          });
      },
      createPost() {
        axios.post('/api/blog_posts', this.newPost)
          .then(() => {
            this.newPost.title = '';
            this.newPost.content = '';
            this.fetchBlogPosts();
          })
          .catch(error => {
            console.error('Error creating post:', error);
          });
      },
      goToDashboard() {
        Inertia.visit('/dashboard');
      }
    },
    mounted() {
      this.fetchBlogPosts();
    }
  };
  </script>

  <style scoped>
  .container {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }

  .title {
    font-size: 24px;
    margin-bottom: 20px;
    text-align: center;
    font-weight: bold;
  }

  .post {
    margin-bottom: 30px;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 10px;
    background-color: #f9f9f9;
  }

  .post-title {
    font-size: 20px;
    font-weight: bold;
  }

  .post-body {
    margin: 10px 0;
  }

  .post-meta {
    font-size: 12px;
    color: #666;
  }

  .post-buttons {
    margin-top: 10px;
  }

  .comment {
    margin-left: 20px;
    padding-left: 10px;
    border-left: 2px solid #ccc;
  }

  .comment-body {
    margin: 5px 0;
  }

  .comment-meta {
    font-size: 12px;
    color: #666;
  }

  .comment-form {
    margin-top: 10px;
  }

  .new-post-form {
    margin-top: 20px;
  }

  .input, .textarea {
    display: block;
    width: 100%;
    padding: 10px;
    margin: 5px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
  }

  .button {
    background-color: #1f2937;
    color: #ffffff;
    padding: 10px 20px;
    margin: 5px 0;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-align: center;
    font-size: 14px;
    transition: background-color 0.3s;
  }

  .button:hover {
    background-color: #4b5563;
  }

  .back-button {
    margin-bottom: 20px;
  }
  </style>
