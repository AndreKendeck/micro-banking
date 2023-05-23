<template>
    <div class="flex flex-col bg-white shadow dark:bg-slate-200 rounded w-full p-6 mb-4">
        <h5 class="font-bold text-gray-400">Equity</h5>
        <h3 class="font-bold text-gray-800 my-2">{{ user.equity }}</h3>
    </div>
</template>
<script>
export default {
    props: {
        user: {
            type: Object,
            required: true,
        },
    },
};
</script>
