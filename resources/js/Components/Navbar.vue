<template>
    <nav class="dark:bg-white rounded">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <img class="h-8 w-8" src="/logo.png" alt="Logo" />
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-4">
                            <a
                                v-for="item in menuItems"
                                :key="item.label"
                                :href="item.url"
                                :class="{
                                    'text-white': item.active,
                                    'text-gray-300 hover:text-white':
                                        !item.active,
                                }"
                                class="px-3 py-2 rounded-md text-sm font-medium"
                            >
                                {{ item.label }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="ml-4 flex items-center md:ml-6">
                        <!-- Add your additional navbar items or actions here -->
                    </div>
                </div>
            </div>
        </div>
    </nav>
</template>

<script>
export default {
    data() {
        return {
            menuItems: [
                { label: "Home", url: "/", active: true },
                { label: "About", url: "/about", active: false },
                { label: "Services", url: "/services", active: false },
                { label: "Contact", url: "/contact", active: false },
            ],
        };
    },
};
</script>
