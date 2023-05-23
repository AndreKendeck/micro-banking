<template>
    <button :class="colorClass" class="w-full px-2 py-1 rounded" :disabled="disabled" @click="$emit('click')">
        <slot></slot>
    </button>
</template>

<script>
export default {
    props: {
        variant: {
            type: String,
            default: "default",
        },
        disabled: {
            type: Boolean,
            default: false,
        },
    },
    computed: {
        colorClass() {
            switch (this.variant) {
                case "default":
                    return "bg-blue-500 hover:bg-blue-600 text-white";
                case "success":
                    return "bg-green-500 hover:bg-green-600 text-white";
                case "warning":
                    return "bg-green-500 hover:bg-green-600 text-white";
                case "danger":
                    return "bg-red-500 hover:bg-red-600 text-white";
                default:
                    return "bg-blue-500 hover:bg-blue-600 text-white";
            }
        },
    },
};
</script>
