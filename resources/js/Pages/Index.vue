<template>
    <div class="container mt-6 px-4 w-full md:mx-auto md:px-0 md:w-2/3 lg:w-1/2 self-center">
        <Heading>Portfolio</Heading>
        <div class="flex flex-col p-4 rounded bg-red-50" v-if="!isAuthenticated">
                <p class="text-red-500">Please login to see your portfolio.</p>
        </div>
        <div v-if="isAuthenticated">
            <EquityCard :user="getUser" />
             <hr>
            <div class="flex flex-col space-y-2 w-full mt-6">
                <AccountCard v-for="(account) in getUser?.accounts" :key="account.id" :account="account" />
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';
import AccountCard from '../Components/AccountCard.vue';
import EquityCard from '../Components/EquityCard.vue';
import Heading from '../Components/Heading.vue';
export default {
    components: {
        AccountCard,
        EquityCard,
        Heading
    },
    computed: {
        ...mapGetters(['isAuthenticated', 'getUser'])
    }
};
</script>
