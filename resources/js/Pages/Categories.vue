<template>
    <div class="max-w-3xl mx-auto p-6 bg-gray-100 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">Categories</h1>
            <div>
                <button class="bg-gray-900 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition duration-300 mr-2" @click="goBack">Back to Dashboard</button>
                <a class="bg-gray-900 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition duration-300" href="/blog_posts">Blog Posts</a>
            </div>
        </div>
        <div class="grid gap-6 md:grid-cols-2">
            <ul class="space-y-4">
                <li v-for="category in categories" :key="category.id" class="flex justify-between items-center bg-white p-4 rounded-lg shadow-md">
                    <div class="flex-1">
                        <span v-if="editingCategoryId !== category.id">{{ category.name }}</span>
                        <input v-else v-model="editingCategoryName" class="w-full p-2 border rounded-lg" />
                    </div>
                    <div class="flex space-x-2">
                        <button v-if="editingCategoryId !== category.id" @click="startEditing(category)" class="bg-gray-900 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition duration-300">Edit</button>
                        <button v-else @click="updateCategory(category.id)" class="bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition duration-300">Save</button>
                        <button @click="deleteCategory(category.id)" class="bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition duration-300">Delete</button>
                    </div>
                </li>
            </ul>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <form @submit.prevent="createCategory" class="space-y-4">
                    <input v-model="newCategory.name" placeholder="Category Name" class="w-full p-2 border rounded-lg" />
                    <button type="submit" class="bg-gray-900 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition duration-300 w-full">Create Category</button>
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

