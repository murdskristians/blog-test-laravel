<template>
    <div class="container">
        <h1 class="title">Categories</h1>
        <ul class="category-list">
            <li v-for="category in categories" :key="category.id" class="category-item">
                <span v-if="editingCategoryId !== category.id">{{ category.name }}</span>
                <input v-else v-model="editingCategoryName" class="input" />
                <button v-if="editingCategoryId !== category.id" @click="startEditing(category)" class="button">Edit</button>
                <button v-else @click="updateCategory(category.id)" class="button">Save</button>
            </li>
        </ul>
        <form @submit.prevent="createCategory" class="category-form">
            <input v-model="newCategory.name" placeholder="Category Name" class="input" />
            <button type="submit" class="button">Create Category</button>
        </form>
        <button class="back-button" @click="goBack">Back to Dashboard</button>
    </div>
</template>

<script>
import axios from 'axios';
import { ref, onMounted } from 'vue';

export default {
    setup() {
        const categories = ref([]);
        const newCategory = ref({ name: '' });
        const editingCategoryId = ref(null);
        const editingCategoryName = ref('');

        const fetchCategories = async () => {
            const response = await axios.get('/api/categories');
            categories.value = response.data;
        };

        const createCategory = async () => {
            await axios.post('/api/categories', newCategory.value);
            newCategory.value.name = '';
            fetchCategories();
        };

        const startEditing = (category) => {
            editingCategoryId.value = category.id;
            editingCategoryName.value = category.name;
        };

        const updateCategory = async (categoryId) => {
            await axios.put(`/api/categories/${categoryId}`, { name: editingCategoryName.value });
            editingCategoryId.value = null;
            editingCategoryName.value = '';
            fetchCategories();
        };

        const goBack = () => {
            window.history.back();
        };

        onMounted(fetchCategories);

        return {
            categories,
            newCategory,
            createCategory,
            editingCategoryId,
            editingCategoryName,
            startEditing,
            updateCategory,
            goBack,
        };
    },
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
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.category-item:last-child {
    border-bottom: none;
}

.category-form {
    margin-top: 20px;
}

.input {
    display: block;
    width: calc(100% - 100px);
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
    margin-left: 10px;
}

.button:hover {
    background-color: #4b5563;
}

.back-button {
    margin-top: 20px;
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

.back-button:hover {
    background-color: #4b5563;
}
</style>
