<template>
    <div class="container">
      <h1 class="title">Categories</h1>
      <button @click="goToDashboard" class="button back-button">Back to Dashboard</button>
      <ul class="category-list">
        <li v-for="category in categories" :key="category.id" class="category-item">
          {{ category.name }}
        </li>
      </ul>
      <form @submit.prevent="createCategory" class="category-form">
        <input v-model="newCategory.name" placeholder="Category Name" class="input" />
        <button type="submit" class="button">Create Category</button>
      </form>
    </div>
  </template>

  <script>
  import axios from 'axios';
  import { Inertia } from '@inertiajs/inertia';

  export default {
    data() {
      return {
        categories: [],
        newCategory: {
          name: ''
        }
      };
    },
    methods: {
      fetchCategories() {
        axios.get('/api/categories')
          .then(response => {
            this.categories = response.data;
          });
      },
      createCategory() {
        axios.post('/api/categories', this.newCategory)
          .then(() => {
            this.fetchCategories();
            this.newCategory.name = '';
          });
      },
      goToDashboard() {
        Inertia.visit('/dashboard');
      }
    },
    mounted() {
      this.fetchCategories();
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

  .category-list {
    list-style-type: none;
    padding: 0;
  }

  .category-item {
    padding: 10px;
    border-bottom: 1px solid #ccc;
  }

  .category-item:last-child {
    border-bottom: none;
  }

  .category-form {
    margin-top: 20px;
  }

  .input {
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

  .back-button {
    margin-bottom: 20px;
  }
  </style>
