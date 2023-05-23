<template>
    <div class="container mt-6 px-4 w-full md:mx-auto md:px-0 md:w-2/3 lg:w-2/3 self-center">
        <div class="flex flex-row justify-between items-center">
            <Heading>Accounts</Heading>
            <div>
                <Button variant="default">Create</Button>
            </div>
        </div>
        <div class="p-4 bg-white shadow-md rounded-md">
            <Table :headings="['Type', 'Name', 'Number', 'Opening Balance', 'Closing Balance']">
                <tr @click="() => $router.push({ path: `/accounts/${account.accountNumber}` })" class="text-center border-2 hover:bg-slate-50 cursor-pointer" v-for="account in getAccounts" :key="account.id">
                    <td class="text-gray-800 p-2">{{ account.type }}</td>
                    <td class="text-blue-600 p-2">{{ account.name  }}</td>
                    <td class="text-blue-600 p-2">{{ account.accountNumber }}</td>
                    <td class="text-blue-600 p-2">{{ account.openingBalance  }}</td>
                    <td class="text-blue-600 p-2">{{ account.closingBalance  }}</td>
                </tr>
            </Table>
        </div>
    </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex';
import Heading from '../Components/Heading.vue';
export default {
    components: {
        Heading
    },
    methods: {
        ...mapActions(['getAllAccounts'])
    },
    computed: {
        ...mapGetters(['getAccounts'])
    },
    mounted() {
        this.getAllAccounts()
    }
};
</script>
