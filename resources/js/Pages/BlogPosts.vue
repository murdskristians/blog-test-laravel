<template>
    <div class="max-w-7xl mx-auto p-6">
        <div class="flex justify-end mb-6">
            <button @click="goBack" class="bg-gray-900 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition duration-300 mr-2">Back to Dashboard</button>
            <a class="bg-gray-900 text-white py-2 px-4 rounded-lg hover:bg-gray-700 transition duration-300" href="/categories">Categories</a>
        </div>

        <h1 class="text-2xl font-bold text-center mb-6">Blog Posts</h1>

        <div class="flex justify-center mb-6">
            <input v-model="searchKeyword" @input="searchBlogPosts" class="w-full max-w-xl p-3 border-2 border-gray-300 rounded-full focus:border-blue-500 focus:shadow-outline transition duration-300" placeholder="Search blog posts..." />
        </div>

        <div class="flex flex-wrap -mx-4">
            <div class="w-full lg:w-3/4 px-4 mb-6 lg:mb-0">
                <div v-for="post in blogPosts" :key="post.id" class="bg-white shadow-md rounded-lg p-6 mb-6">
                    <div v-if="post.editing">
                        <input v-model="post.title" class="w-full p-2 mb-4 border rounded" />
                        <textarea v-model="post.content" class="w-full p-2 mb-4 border rounded"></textarea>
                        <div class="mb-4">
                            <label class="block mb-2">Select Categories:</label>
                            <select v-model="post.selectedCategory" @change="addCategoryToPost(post)" class="w-full p-2 border rounded">
                                <option value="" disabled selected>Select category</option>
                                <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                            </select>
                        </div>
                        <button @click="updatePost(post)" class="bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition duration-300 mr-2">Save</button>
                        <button @click="cancelEdit(post)" class="bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition duration-300">Cancel</button>
                        <div v-if="post.categories.length" class="mt-4">
                            <h3 class="font-semibold mb-2">Selected Categories:</h3>
                            <ul>
                                <li v-for="category in post.categories" :key="category.id" class="flex justify-between items-center mb-2">
                                    {{ category.name }}
                                    <button @click="removeCategoryFromPost(post, category.id)" class="bg-red-500 text-white py-1 px-2 rounded-lg hover:bg-red-700 transition duration-300">Remove</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div v-else>
                        <h2 class="text-xl font-semibold">{{ post.title }}</h2>
                        <p>{{ post.content }}</p>
                        <small class="text-gray-600">by {{ post.user.name }} on {{ new Date(post.created_at).toLocaleString() }}</small>
                        <div class="mt-2">Categories: {{ post.categories.map(category => category.name).join(', ') }}</div>
                        <div class="mt-4">
                            <button v-if="post.user.id === user.id" @click="startEdit(post)" class="bg-yellow-500 text-white py-2 px-4 rounded-lg hover:bg-yellow-700 transition duration-300 mr-2">Edit</button>
                            <button v-if="post.user.id === user.id" @click="deletePost(post.id)" class="bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition duration-300">Delete</button>
                        </div>
                    </div>

                    <div v-for="comment in post.comments" :key="comment.id" class="bg-gray-100 p-4 mt-4 rounded-lg">
                        <div v-if="comment.editing">
                            <input v-model="comment.content" class="w-full p-2 mb-4 border rounded" />
                            <button @click="updateComment(comment)" class="bg-green-500 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition duration-300 mr-2">Save</button>
                        </div>
                        <div v-else>
                            <p>{{ comment.content }}</p>
                            <small class="text-gray-600">by {{ comment.user.name }} on {{ new Date(comment.created_at).toLocaleString() }}</small>
                            <div class="mt-2">
                                <button v-if="comment.user.id === user.id" @click="editComment(comment)" class="bg-yellow-500 text-white py-2 px-4 rounded-lg hover:bg-yellow-700 transition duration-300 mr-2">Edit</button>
                                <button v-if="comment.user.id === user.id" @click="deleteComment(comment.id)" class="bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition duration-300">Delete</button>
                            </div>
                        </div>
                    </div>
                    <form @submit.prevent="addComment(post.id)" class="mt-4">
                        <textarea v-model="post.newComment" class="w-full p-2 mb-4 border rounded" placeholder="Add a comment..."></textarea>
                        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-300">Add Comment</button>
                    </form>
                </div>
            </div>

            <div class="w-full lg:w-1/4 px-4">
                <h2 class="text-xl font-bold mb-4">Add New Blog Post</h2>
                <form @submit.prevent="createPost" class="bg-white shadow-md rounded-lg p-6">
                    <input v-model="newPost.title" class="w-full p-2 mb-4 border rounded" placeholder="Title" />
                    <textarea v-model="newPost.content" class="w-full p-2 mb-4 border rounded" placeholder="Body"></textarea>
                    <div class="mb-4">
                        <label class="block mb-2">Select Categories:</label>
                        <select v-model="selectedCategory" @change="addCategory" class="w-full p-2 border rounded">
                            <option value="" disabled selected>Select category</option>
                            <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                        </select>
                    </div>
                    <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-300">Create Post</button>
                    <div v-if="newPost.categories.length" class="mt-4">
                        <h3 class="font-semibold mb-2">Selected Categories:</h3>
                        <ul>
                            <li v-for="category in newPost.categories" :key="category.id" class="flex justify-between items-center mb-2">
                                {{ category.name }}
                                <button @click="removeCategory(category.id)" class="bg-red-500 text-white py-1 px-2 rounded-lg hover:bg-red-700 transition duration-300">Remove</button>
                            </li>
                        </ul>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from '../requests';

axios.defaults.baseURL = 'http://127.0.0.1:8000';
axios.defaults.withCredentials = true;
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.head.querySelector('meta[name="csrf-token"]').content;

export default {
    data() {
        return {
            blogPosts: [],
            categories: [],
            selectedCategory: null,
            newPost: {
                title: '',
                content: '',
                categories: []
            },
            user: {
                id: null
            },
            searchKeyword: ''
        };
    },
    methods: {
        searchBlogPosts() {
            if (this.searchKeyword.trim() === '') {
                this.fetchBlogPosts();
                return;
            }

            axios.get('/api/blog_posts/search', { params: { keyword: this.searchKeyword } })
                .then(response => {
                    this.blogPosts = response.data.map(post => {
                        post.comments = post.comments.map(comment => {
                            return {
                                ...comment,
                                editing: false
                            };
                        });
                        post.newComment = '';
                        post.editing = false;
                        return post;
                    }).reverse();
                })
                .catch(error => {
                    console.error('Error searching blog posts:', error);
                });
        },
        async fetchUser() {
            try {
                const response = await axios.get('/api/user');
                this.user.id = response.data.id;
            } catch (error) {
                console.error('Error fetching user data:', error);
            }
        },
        fetchBlogPosts() {
            axios.get('/api/blog_posts')
                .then(response => {
                    if (Array.isArray(response.data)) {
                        this.blogPosts = response.data.map(post => {
                            post.comments = post.comments.map(comment => {
                                return {
                                    ...comment,
                                    editing: false
                                };
                            });
                            post.newComment = '';
                            post.editing = false;
                            return post;
                        }).reverse();
                    } else {
                        console.error('Unexpected response structure:', response.data);
                    }
                })
                .catch(error => {
                    console.error('Error fetching blog posts:', error);
                });
        },
        fetchCategories() {
            axios.get('/api/categories')
                .then(response => {
                    this.categories = response.data;
                })
                .catch(error => {
                    console.error('Error fetching categories:', error);
                });
        },
        createPost() {
            const newPostData = {
                title: this.newPost.title,
                content: this.newPost.content,
                categories: this.newPost.categories.map(c => c.id),
                user_id: this.user.id
            };

            console.log('Payload:', newPostData);

            axios.post('/api/blog_posts', newPostData)
                .then(() => {
                    this.newPost.title = '';
                    this.newPost.content = '';
                    this.newPost.categories = [];
                    this.selectedCategory = null;
                    this.fetchBlogPosts();
                })
                .catch(error => {
                    console.error('Error creating post:', error);
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
        startEdit(post) {
            post.editing = true;
            post.selectedCategory = null;
        },
        cancelEdit(post) {
            post.editing = false;
            this.fetchBlogPosts();
        },
        updatePost(post) {
            const updatedPostData = {
                title: post.title,
                content: post.content,
                categories: post.categories.map(c => c.id)
            };

            axios.put(`/api/blog_posts/${post.id}`, updatedPostData)
                .then(() => {
                    post.editing = false;
                    this.fetchBlogPosts();
                })
                .catch(error => {
                    console.error('Error updating post:', error);
                });
        },
        addCategoryToPost(post) {
            const category = this.categories.find(cat => cat.id === post.selectedCategory);
            if (category && !post.categories.find(cat => cat.id === category.id)) {
                post.categories.push(category);
            }
        },
        removeCategoryFromPost(post, categoryId) {
            post.categories = post.categories.filter(cat => cat.id !== categoryId);
        },
        addComment(postId) {
            const post = this.blogPosts.find(p => p.id === postId);

            if (post.newComment.trim() !== '') {
                axios.post(`/api/blog_posts/${postId}/comments`, {
                    content: post.newComment,
                    blog_post_id: postId  // Include blog_post_id in the request payload
                })
                .then(() => {
                    post.newComment = '';
                    this.fetchBlogPosts();
                })
                .catch(error => {
                    console.error('Error adding comment:', error);
                });
            }
        },
        editComment(comment) {
            comment.editing = true;
        },
        updateComment(comment) {
            axios.put(`/api/comments/${comment.id}`, { content: comment.content })
                .then(() => {
                    comment.editing = false;
                    this.fetchBlogPosts();
                })
                .catch(error => {
                    console.error('Error updating comment:', error);
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
        goBack() {
            this.$inertia.get('/dashboard');
        },
        addCategory() {
            const category = this.categories.find(cat => cat.id === this.selectedCategory);
            if (category && !this.newPost.categories.find(cat => cat.id === category.id)) {
                this.newPost.categories.push(category);
            }
        },
        removeCategory(categoryId) {
            this.newPost.categories = this.newPost.categories.filter(cat => cat.id !== categoryId);
        }
    },
    created() {
        this.fetchUser().then(() => {
            console.log(this.user);
            console.log(this.user.id);
        });
    },
    mounted() {
        this.fetchBlogPosts();
        this.fetchCategories();
    }
};
</script>
