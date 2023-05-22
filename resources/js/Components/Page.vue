<template>
    <div class="container mx-auto">
        <h1 class="text-3xl font-bold mb-4">{{ pageTitle }}</h1>
        <div class="p-4 bg-white shadow-md rounded-md">
            <!-- Your page content goes here -->
            <slot></slot>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        pageTitle: {
            type: String,
            required: true,
        },
    },
};
</script>
