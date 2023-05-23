<template>
    <div class="flex flex-col">
        <label class="mb-1" :for="id">{{ label }}</label>
        <input
            :id="id"
            :type="type"
            v-model="value"
            v-on:keyup="() => $emit('changed', value)"
            :class="{
                'border-red-500': hasError,
                'border-gray-400': !hasError,
            }"
            class="border px-3 py-2 rounded-md focus:outline-none focus:ring focus:border-blue-300"
        />
        <div class="flex flex-col space-y-2">
            <small
                v-for="(error, index) in errors"
                :key="index"
                class="text-red-500"
                >{{ error }}</small
            >
        </div>
    </div>
</template>

<script>
export default {
    props: {
        id: {
            type: String,
            required: true,
        },
        label: {
            type: String,
            required: true,
        },
        type: {
            type: String,
            default: "text",
        },
        errors: {
            type: Array,
            default: [],
        },
    },
    data() {
        return {
            value: this.value,
            errors: this.errors
        };
    },
    computed: {
        hasError() {
            return this.errors.length > 0;
        },
        getBorder() {
            return this.hasError
                ? "border-red-500 border px-3 py-2 rounded-md focus:outline-none focus:ring focus:border-blue-300"
                : "border-gray-300 border px-3 py-2 rounded-md focus:outline-none focus:ring focus:border-blue-300";
        },
    },
};
</script>
