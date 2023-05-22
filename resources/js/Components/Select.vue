<template>
    <div>
        <label class="block font-medium mb-1">{{ label }}</label>
        <select
            v-model="selectedOption"
            class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
        >
            <option
                v-for="option in options"
                :key="option.value"
                :value="option.value"
            >
                {{ option.label }}
            </option>
        </select>
    </div>
</template>

<script>
export default {
    props: {
        label: {
            type: String,
            required: true,
        },
        options: {
            type: Array,
            required: true,
        },
        value: {
            type: [String, Number],
            default: "",
        },
    },
    computed: {
        selectedOption: {
            get() {
                return this.value;
            },
            set(newValue) {
                this.$emit("input", newValue);
            },
        },
    },
};
</script>
