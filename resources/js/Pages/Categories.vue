<template>
    <div class="container">
        <div class="header">
            <h1 class="title">Categories</h1>
            <button class="back-button" @click="goBack">Back to Dashboard</button>
        </div>
        <div class="categories-grid">
            <ul class="category-list">
                <li v-for="category in categories" :key="category.id" class="category-item">
                    <div class="category-name">
                        <span v-if="editingCategoryId !== category.id">{{ category.name }}</span>
                        <input v-else v-model="editingCategoryName" class="input" />
                    </div>
                    <div class="category-actions">
                        <button v-if="editingCategoryId !== category.id" @click="startEditing(category)" class="button">Edit</button>
                        <button v-else @click="updateCategory(category.id)" class="button">Save</button>
                        <button @click="deleteCategory(category.id)" class="button delete-button">Delete</button>
                    </div>
                </li>
            </ul>
            <div class="category-form-container">
                <form @submit.prevent="createCategory" class="category-form">
                    <input v-model="newCategory.name" placeholder="Category Name" class="input" />
                    <button type="submit" class="button">Create Category</button>
                </form>
            </div>
        </div>
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

        const deleteCategory = async (categoryId) => {
            await axios.delete(`/api/categories/${categoryId}`);
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
            deleteCategory,
            goBack,
        };
    },
};
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

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.title {
    font-size: 24px;
    font-weight: bold;
}

.categories-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
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

.category-name {
    flex: 1;
}

.category-actions {
    display: flex;
    gap: 10px;
}

.category-form-container {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.category-form {
    width: 100%;
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

.delete-button {
    background-color: #dc2626;
    color: #ffffff;
}

.delete-button:hover {
    background-color: #b91c1c;
}

.back-button {
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
