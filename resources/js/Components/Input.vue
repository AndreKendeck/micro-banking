<template>
    <div class="flex flex-col">
        <label class="mb-1" :for="id">{{ label }}</label>
        <input
            :id="id"
            v-model="value"
            :type="type"
            :placeholder="placeholder"
            class="border border-gray-300 px-3 py-2 rounded-md focus:outline-none focus:ring focus:border-blue-300"
        />
        <div class="flex flex-col space-y-2">
            <small
                v-for="(error, index) in errors"
                :key="index"
                class="text-red-500 text-sm"
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
        value: {
            type: [String, Number],
            required: true,
        },
        type: {
            type: String,
            default: "text",
        },
        placeholder: {
            type: String,
            default: "",
        },
        errors: {
            type: Array,
            default: [],
        },
    },
};
</script>
