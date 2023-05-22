<template>
    <div class="relative">
        <button
            @click="isOpen = !isOpen"
            class="flex items-center justify-center px-4 py-2 bg-gray-800 text-white rounded-md focus:outline-none"
        >
            Toggle Dropdown
        </button>
        <div
            v-if="isOpen"
            class="right-0 mt-2 w-48 bg-white rounded-md shadow-lg"
        >
            <ul class="py-2">
                <li class="px-4 py-2 hover:bg-gray-200">Option 1</li>
                <li class="px-4 py-2 hover:bg-gray-200">Option 2</li>
                <li class="px-4 py-2 hover:bg-gray-200">Option 3</li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            isOpen: false,
        };
    },
};
</script>
