<template>
    <div>
        <Message />
        <Navbar />
        <div class="flex flex-col">
            <router-view></router-view>
        </div>
    </div>
</template>
<script>
import Message from "./Message.vue";
import Navbar from "./Navbar.vue";
import { mapActions } from "vuex";
export default {
    methods: {
        ...mapActions(["authCheck"]),
    },
    mounted() {
        this.authCheck();
    },
};
</script>
