<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">You're logged in!</div>
                    <div class="p-6">
                        <Link href="/blog_posts" class="bg-gray-900 text-white py-2 px-4 my-2 rounded-lg hover:bg-gray-700 transition duration-300 text-center inline-block">Blog Posts</Link>
                        <Link href="/categories" class="bg-gray-900 text-white py-2 px-4 my-2 rounded-lg hover:bg-gray-700 transition duration-300 text-center inline-block">Categories</Link>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
