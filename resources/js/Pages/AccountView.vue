<template>
    <div
        class="container mt-6 px-4 w-full md:mx-auto md:px-0 md:w-11/12 lg:w-2/3 self-center"
    >
        <div class="flex flex-col space-y-4">
            <Heading>Account: {{ account.accountNumber }}</Heading>
            <div
                class="p-4 bg-white shadow-md rounded-md flex flex-row justify-between items-center"
            >
                <h4 class="text-lg">My Account</h4>
                <div class="flex flex-row space-x-2 w-1/2 items-center">
                    <Select
                        label="Transaction Display Type"
                        :options="getTransactionTypes"
                        @changed="
                            (val) => {
                                audit = val;
                                getTransactions();
                            }
                        "
                    />
                    <Select
                        label="Month"
                        @changed="
                            (value) => {
                                month = value;
                                getTransactions();
                            }
                        "
                        :options="availableMonths"
                    />
                    <Select
                        label="Year"
                        @changed="
                            (value) => {
                                year = value;
                                getTransactions();
                            }
                        "
                        :options="getAvailableYears"
                    />
                </div>
            </div>
            <div class="p-4 bg-white shadow-md rounded-md">
                <Table :headings="getHeadings">
                    <tr v-for="(transaction, dateKey) in transactions">
                        <td class="p-3 text-center font-bold text-gray-500">
                            {{ audit ? transaction.createdAt : dateKey }}
                        </td>
                        <td class="p-3 text-center font-bold text-gray-500">
                            {{ audit ? transaction.type :  transaction.openingBalance }}
                        </td>
                        <td class="p-3 text-center font-bold text-gray-500">
                            {{ audit ? transaction.amount : transaction.totalDebits }}
                        </td>
                        <td class="p-3 text-center font-bold text-gray-500">
                            {{ audit ? transaction.description : transaction.totalCredits }}
                        </td>
                        <td class="p-3 text-center font-bold text-gray-500">
                            {{ audit ? transaction.openingBalance : transaction.closingBalance }}
                        </td>
                        <td class="p-3 text-center font-bold text-gray-500">
                            {{ audit ? transaction.runningBalance :  transaction.netMovement }}
                        </td>
                    </tr>
                </Table>
            </div>
        </div>
    </div>
</template>

<script>
import { mapGetters } from "vuex";
import Heading from "../Components/Heading.vue";
import Dropdown from "../Components/Dropdown.vue";
import api from "../Api/Index";
export default {
    components: {
        Heading,
        Dropdown,
    },
    data() {
        return {
            availableMonths: [],
            month: 5,
            year: 2023,
            transactions: [],
            audit: false,
        };
    },
    methods: {
        ...mapGetters(["getAccountByNumber"]),
        getTransactions() {
            const { month, year, audit } = this;
            const { accountNumber } = this.$route.params;
            api.get(`/api/accounts/${accountNumber}/transactions/`, {
                params: {
                    month,
                    year,
                    audit,
                },
            }).then(({ data }) => {
                this.transactions = data;
            });
        },
    },
    computed: {
        accountNumber() {
            // return this.
        },
        accountTransactions() {
            const { accountNumber } = this.$route.params;
            return this.$store.getters.getAccountByNumber(accountNumber);
        },
        account() {
            const { accountNumber } = this.$route.params;
            return this.getAccountByNumber(accountNumber);
        },
        getHeadings() {
            if (this.audit) {
                return [
                    "Date",
                    "Type",
                    "Amount",
                    "Description",
                    "Opening Balance",
                    "Closing Balance",
                ];
            }
            return [
                "Date",
                "Opening Balance",
                "Total Debits",
                "Total Credits",
                "Closing Balance",
                "NET Movement",
            ];
        },
        getAvailableYears() {
            return [
                {
                    label: "2023",
                    value: 2023,
                },
                {
                    label: "2022",
                    value: 2022,
                },
            ];
        },
        getTransactionTypes() {
            return [
                {
                    label: "Grouped",
                    value: false,
                },
                {
                    label: "Individiual",
                    value: true,
                },
            ];
        },
    },
    mounted() {
        api.get("/api/months").then(
            ({ data }) => (this.availableMonths = data)
        );
    },
};
</script>
