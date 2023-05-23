<template>
    <div
        class="container mt-6 px-4 w-full md:mx-auto md:px-0 md:w-2/3 lg:w-1/2"
    >
        <Heading>Login to account</Heading>
        <div
            class="p-4 bg-white shadow-md rounded-md flex flex-col space-y-4 w-full"
        >
            <Input
                @changed="(value) => updateForm({ key: 'email', value })"
                label="E-mail"
                type="email"
                id="email"
                :errors="getCurrentForm.email.errors"
            />
            <Input
                @changed="(value) => updateForm({ key: 'password', value })"
                label="Password"
                type="password"
                id="password"
                :errors="getCurrentForm.password.errors"
            />
            <div class="w-1/2 self-center">
                <Button @click="() => submitForm()">Login</Button>
            </div>
        </div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from "vuex";
import Heading from "../Components/Heading.vue";
export default {
    components: {
        Heading
    },
    methods: {
        ...mapActions(["updateForm", "login"]),
        submitForm() {
            const { email: { value: email }, password: { value: password } } = this.getCurrentForm;
            this.login( { email, password });
        },
    },
    computed: {
        ...mapGetters(["getCurrentForm"]),
    },
    mounted() {
        this.updateForm({ key: 'email', value: null })
        this.updateForm({ key: 'password', value: null })
    }
};
</script>
