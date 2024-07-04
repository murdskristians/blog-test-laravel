<template>
    <div class="container">
        <div class="back-button-container">
            <button @click="goBack" class="back-button">Back to Dashboard</button>
        </div>
        <h1 class="title">Blog Posts</h1>
        <div class="columns">
            <div class="column is-three-quarters">
                <div v-for="post in blogPosts" :key="post.id" class="post">
                    <h2>{{ post.title }}</h2>
                    <p>{{ post.content }}</p>
                    <small>by {{ post.user.name }} on {{ new Date(post.created_at).toLocaleString() }}</small>
                    <button @click="deletePost(post.id)" class="delete-button">Delete</button>
                    <div v-for="comment in post.comments" :key="comment.id" class="comment">
                        <p v-if="!comment.editing">{{ comment.content }}</p>
                        <input v-if="comment.editing" v-model="comment.content" />
                        <small>by {{ comment.user.name }} on {{ new Date(comment.created_at).toLocaleString() }}</small>
                        <button v-if="comment.user.id === user.id" @click="editComment(comment)" class="edit-button">Edit</button>
                        <button v-if="comment.editing" @click="updateComment(comment)" class="save-button">Save</button>
                        <button v-if="comment.user.id === user.id" @click="deleteComment(comment.id)" class="delete-button">Delete</button>
                    </div>
                    <form @submit.prevent="addComment(post.id)">
                        <textarea v-model="newComment"></textarea>
                        <button type="submit">Add Comment</button>
                    </form>
                </div>
            </div>
            <div class="column">
                <h2 class="title">Add New Blog Post</h2>
                <form @submit.prevent="createPost">
                    <input v-model="newPost.title" placeholder="Title" />
                    <textarea v-model="newPost.content" placeholder="Body"></textarea>
                    <button type="submit">Create Post</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from '../requests';

export default {
    data() {
        return {
            blogPosts: [],
            newPost: {
                title: '',
                content: ''
            },
            newComment: '',
            user: {
                id: 1 // You might need to get this dynamically
            }
        };
    },
    methods: {
        fetchBlogPosts() {
            axios.get('/blog_posts')
                .then(response => {
                    this.blogPosts = response.data;
                })
                .catch(error => {
                    console.error('Error fetching blog posts:', error);
                });
        },
        createPost() {
            axios.post('/blog_posts', this.newPost)
                .then(() => {
                    this.newPost.title = '';
                    this.newPost.content = '';
                    this.fetchBlogPosts();
                })
                .catch(error => {
                    console.error('Error creating post:', error);
                });
        },
        deletePost(postId) {
            axios.delete(`/blog_posts/${postId}`)
                .then(() => {
                    this.fetchBlogPosts();
                })
                .catch(error => {
                    console.error('Error deleting post:', error);
                });
        },
        addComment(postId) {
            axios.post(`/blog_posts/${postId}/comments`, { content: this.newComment })
                .then(() => {
                    this.newComment = '';
                    this.fetchBlogPosts();
                })
                .catch(error => {
                    console.error('Error adding comment:', error);
                });
        },
        editComment(comment) {
            comment.editing = true;
        },
        updateComment(comment) {
            axios.put(`/comments/${comment.id}`, { content: comment.content })
                .then(() => {
                    comment.editing = false;
                    this.fetchBlogPosts();
                })
                .catch(error => {
                    console.error('Error updating comment:', error);
                });
        },
        deleteComment(commentId) {
            axios.delete(`/comments/${commentId}`)
                .then(() => {
                    this.fetchBlogPosts();
                })
                .catch(error => {
                    console.error('Error deleting comment:', error);
                });
        },
        goBack() {
            this.$inertia.get('/dashboard');
        }
    },
    mounted() {
        this.fetchBlogPosts();
    }
};
</script>

<style scoped>
.container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.back-button-container {
    text-align: right;
}

.back-button {
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

.back-button:hover {
    background-color: #4b5563;
}

.title {
    font-size: 24px;
    margin-bottom: 20px;
    text-align: center;
    font-weight: bold;
}

.columns {
    display: flex;
    gap: 20px;
}

.column {
    flex: 1;
}

.post, .comment {
    background-color: #ffffff;
    padding: 20px;
    margin-bottom: 10px;
    border-radius: 5px;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
}

.delete-button, .edit-button, .save-button {
    background-color: #ef4444;
    color: #ffffff;
    padding: 5px 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-top: 10px;
    font-size: 12px;
    transition: background-color 0.3s;
}

.edit-button {
    background-color: #3b82f6;
}

.save-button {
    background-color: #10b981;
}

.delete-button:hover {
    background-color: #dc2626;
}

.edit-button:hover {
    background-color: #2563eb;
}

.save-button:hover {
    background-color: #059669;
}

textarea {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
}

button[type="submit"] {
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

button[type="submit"]:hover {
    background-color: #4b5563;
}
</style>
