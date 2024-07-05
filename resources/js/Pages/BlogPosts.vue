<template>
    <div class="container">
        <div class="back-button-container">
            <button @click="goBack" class="back-button">Back to Dashboard</button>
            <a class="button" href="/categories">Categories</a>
        </div>

        <h1 class="title">Blog Posts</h1>
        <div class="columns">
            <div class="column is-three-quarters">
                <div v-for="post in blogPosts" :key="post.id" class="post">
                    <div v-if="post.editing">
                        <input v-model="post.title" />
                        <textarea v-model="post.content"></textarea>
                        <div>
                            <label>Select Categories: </label>
                            <select v-model="post.selectedCategory" @change="addCategoryToPost(post)" class="blog-post_category_selector">
                                <option value="" disabled selected>Select category</option>
                                <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                            </select>
                        </div>
                        <button @click="updatePost(post)" class="save-button">Save</button>
                        <button @click="cancelEdit(post)" class="cancel-button">Cancel</button>
                        <div v-if="post.categories.length">
                            <h3>Selected Categories:</h3>
                            <ul>
                                <li class="blog-post_selected_category" v-for="category in post.categories" :key="category.id">
                                    {{ category.name }}
                                    <button class="blog-post_selected_category_delete_btn" type="button" @click="removeCategoryFromPost(post, category.id)">Remove</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div v-else>
                        <h2>{{ post.title }}</h2>
                        <p>{{ post.content }}</p>
                        <small>by {{ post.user.name }} on {{ new Date(post.created_at).toLocaleString() }}</small>
                        <div class="blog-post_categories">Categories: {{ post.categories.map(category => category.name).join(', ') }}</div>
                        <button v-if="post.user.id === user.id" @click="startEdit(post)" class="edit-button">Edit</button>
                        <button v-if="post.user.id === user.id" @click="deletePost(post.id)" class="delete-button">Delete</button>
                    </div>

                    <div v-for="comment in post.comments" :key="comment.id" class="comment">
                        <div v-if="comment.editing">
                            <input v-model="comment.content" />
                            <button @click="updateComment(comment)" class="save-button">Save</button>
                        </div>
                        <div v-else>
                            <p>{{ comment.content }}</p>
                            <small>by {{ comment.user.name }} on {{ new Date(comment.created_at).toLocaleString() }}</small>
                            <button v-if="comment.user.id === user.id" @click="editComment(comment)" class="edit-button">Edit</button>
                            <button v-if="comment.user.id === user.id" @click="deleteComment(comment.id)" class="delete-button">Delete</button>
                        </div>
                    </div>
                    <form @submit.prevent="addComment(post.id)">
                        <textarea v-model="post.newComment"></textarea>
                        <button type="submit">Add Comment</button>
                    </form>
                </div>
            </div>

            <div class="column">
                <h2 class="title">Add New Blog Post</h2>
                <form @submit.prevent="createPost">
                    <input v-model="newPost.title" placeholder="Title" />
                    <textarea v-model="newPost.content" placeholder="Body"></textarea>
                    <div>
                        <label>Select Categories: </label>
                        <select v-model="selectedCategory" @change="addCategory" class="blog-post_category_selector">
                            <option value="" disabled selected>Select category</option>
                            <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name }}</option>
                        </select>
                    </div>
                    <button type="submit" class="blog-post_create-post-btn">Create Post</button>
                    <div v-if="newPost.categories.length">
                        <h3>Selected Categories:</h3>
                        <ul>
                            <li class="blog-post_selected_category" v-for="category in newPost.categories" :key="category.id">
                                {{ category.name }}
                                <button class="blog-post_selected_category_delete_btn" type="button" @click="removeCategory(category.id)">Remove</button>
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

axios.defaults.baseURL = 'http://127.0.0.1:8000';  // Change this to your Laravel app's URL
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
                categories: [] // Initialize as an empty array
            },
            user: {
                id: null
            }
        };
    },
    methods: {
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
                                    editing: false // Add the editing property
                                };
                            });
                            post.newComment = ''; // Add a newComment property to each post
                            post.editing = false; // Add the editing property for posts
                            return post;
                        }).reverse(); // Reverse the order of the posts
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
                user_id: this.user.id // Ensure user_id is included
            };

            console.log('Payload:', newPostData); // Log the payload for debugging

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
                axios.post(`/api/blog_posts/${postId}/comments`, { content: post.newComment })
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
        this.fetchCategories(); // Fetch categories when component is mounted
    }
};
</script>

<style scoped>
.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
}

.title {
    font-size: 24px;
    margin-bottom: 20px;
    text-align: center;
    font-weight: bold;
}

.columns {
    display: flex;
    justify-content: space-between;
}

.column {
    flex: 1;
    margin-right: 20px;
}

.column:last-child {
    margin-right: 0;
}

.post, .comment {
    background-color: #f9f9f9;
    padding: 20px;
    margin-bottom: 10px;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.comment {
    margin-left: 20px;
}

textarea, input[type="text"] {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
}

button {
    background-color: #1f2937;
    color: #ffffff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-right: 10px;
    font-size: 14px;
}

button:hover {
    background-color: #4b5563;
}

.back-button-container {
    text-align: right;
}

.back-button {
    background-color: #1f2937;
    color: #ffffff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
}

.back-button:hover {
    background-color: #4b5563;
}

.delete-button {
    background-color: #e3342f;
}

.delete-button:hover {
    background-color: #cc1f1a;
}

.save-button {
    background-color: #38c172;
}

.save-button:hover {
    background-color: #2fa360;
}

.edit-button {
    background-color: #ffed4a;
    color: #1f2937;
    margin-left: 30px;
}

.edit-button:hover {
    background-color: #fce96a;
}

.blog_post_categories {
    padding: 30px 0 10px;
}

.blog-post_delete-button{
    margin: 0 0 30px 0;
}

.button {
    background-color: #1f2937;
    color: #ffffff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s;
}

.button:hover {
    background-color: #4b5563;
}

.blog-post_categories {
    margin: 20px 0 30px 0;
}

.blog-post_create-post-btn {
    margin-bottom: 30px;
}

.blog-post_selected_category {
    margin-top: 10px;
}

.blog-post_selected_category_delete_btn {
    margin-left: 30px;
}
</style>
