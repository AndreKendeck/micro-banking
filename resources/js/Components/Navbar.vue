<template>
    <nav class="bg-white w-full">
        <div class="flex flew-row w-full p-4 justify-between items-center">
            <router-link
                to="/"
                class="text-xl text-blue-800 border-b p-1 border-blue-800 font-bold tracking-widest"
            >
                Micro-Banking
            </router-link>
            <ul class="flex flex-row space-x-4 justify-end items-center">
                <router-link
                    v-for="(link, index) in links"
                    :key="index"
                    :to="link.url"
                    class="hover:text-blue-800"
                    exact-active-class="text-blue-800"
                    exact
                >
                    {{ link.label }}
                </router-link>
                <Button v-if="isAuthenticated" @click="() => this.logout()" variant="danger"> Logout </Button>
            </ul>
        </div>
    </nav>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import Button from "./Button.vue";
export default {
    data() {
        return {};
    },
    methods: {
        ...mapActions(["logout"]),
    },
    computed: {
        ...mapGetters(["isAuthenticated"]),
        links() {
            if (this.isAuthenticated) {
                return [
                    { label: "Home", url: "/" },
                    { label: "Accounts", url: "/accounts" },
                ];
            }
            return [{ label: "Login", url: "/login" }];
        },
    },
};
</script>
