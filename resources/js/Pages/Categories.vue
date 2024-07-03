<template>
    <div>
      <h1>Categories</h1>
      <ul>
        <li v-for="category in categories" :key="category.id">
          {{ category.name }}
        </li>
      </ul>
      <form @submit.prevent="createCategory">
        <input v-model="newCategory.name" placeholder="Category Name" />
        <button type="submit">Create Category</button>
      </form>
    </div>
  </template>

  <script>
  import axios from 'axios';

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
      }
    },
    mounted() {
      this.fetchCategories();
    }
  };
  </script>

  <style>
  /* Add styles for the component */
  </style>
