<template>
    <div class="container">
      <h1 class="title">Blog Posts</h1>
      <div class="form-container">
        <input v-model="newTitle" placeholder="Title" class="input" />
        <textarea v-model="newContent" placeholder="Body" class="textarea"></textarea>
        <button @click="addPost" class="button">Create Post</button>
      </div>
      <div v-for="post in blogPosts" :key="post.id" class="post">
        <h2 class="post-title">{{ post.title }}</h2>
        <p class="post-content">{{ post.content }}</p>
        <small class="post-meta">by {{ post.user.name }} on {{ post.created_at }}</small>
        <button @click="deletePost(post.id)" class="button delete-button">Delete</button>
        <div v-for="comment in post.comments" :key="comment.id" class="comment">
          <p class="comment-content">{{ comment.content }}</p>
          <small class="comment-meta">by {{ comment.user.name }} on {{ comment.created_at }}</small>
          <button @click="deleteComment(comment.id)" class="button delete-button">Delete</button>
        </div>
        <div class="form-container">
          <textarea v-model="newComment" placeholder="New Comment" class="textarea"></textarea>
          <button @click="addComment(post.id)" class="button">Add Comment</button>
        </div>
      </div>
      <button @click="$router.push('/dashboard')" class="button back-button">Back to Dashboard</button>
    </div>
  </template>

  <script setup>
  import { ref, onMounted } from 'vue';
  import axios from '../requests';

  const blogPosts = ref([]);
  const newTitle = ref('');
  const newContent = ref('');
  const newComment = ref('');

  const fetchBlogPosts = async () => {
    try {
      const response = await axios.get('/blog_posts');
      blogPosts.value = response.data;
    } catch (error) {
      console.error('Error fetching blog posts:', error);
    }
  };

  const addPost = async () => {
    try {
      await axios.post('/blog_posts', {
        title: newTitle.value,
        content: newContent.value,
      });
      newTitle.value = '';
      newContent.value = '';
      fetchBlogPosts();
    } catch (error) {
      console.error('Error adding post:', error);
    }
  };

  const deletePost = async (postId) => {
    try {
      await axios.delete(`/blog_posts/${postId}`);
      fetchBlogPosts();
    } catch (error) {
      console.error('Error deleting post:', error);
    }
  };

  const addComment = async (postId) => {
    try {
      await axios.post(`/blog_posts/${postId}/comments`, {
        content: newComment.value,
      });
      newComment.value = '';
      fetchBlogPosts();
    } catch (error) {
      console.error('Error adding comment:', error);
    }
  };

  const deleteComment = async (commentId) => {
    try {
      await axios.delete(`/comments/${commentId}`);
      fetchBlogPosts();
    } catch (error) {
      console.error('Error deleting comment:', error);
    }
  };

  onMounted(fetchBlogPosts);
  </script>

  <style scoped>
  .container {
    max-width: 800px;
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

  .form-container {
    margin-bottom: 20px;
  }

  .input,
  .textarea {
    display: block;
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
  }

  .button {
    background-color: #1f2937;
    color: #ffffff;
    padding: 10px 20px;
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

  .delete-button {
    background-color: #e3342f;
  }

  .delete-button:hover {
    background-color: #cc1f1a;
  }

  .back-button {
    margin-top: 20px;
  }

  .post {
    margin-bottom: 20px;
  }

  .post-title {
    font-size: 20px;
    font-weight: bold;
  }

  .post-content {
    margin-bottom: 10px;
  }

  .post-meta,
  .comment-meta {
    font-size: 12px;
    color: #888;
  }

  .comment {
    margin-left: 20px;
    margin-top: 10px;
  }
  </style>
