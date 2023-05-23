<template>
    <div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th v-for="(heading,index) in headings" :key="index" 
                    class="py-2 px-4 bg-blue-800 text-white">{{ heading }}</th>
                </tr>
            </thead>
            <tbody>
                <slot></slot>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    props: {
        headings: {
            type: Array,
            default: [],
        },
    },
};
</script>
