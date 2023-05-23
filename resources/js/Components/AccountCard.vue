<template>
    <div :class="accountBackgroundColor" class="p-4 shadow rounded w-full">
        <div class="flex flex-row justify-between w-full items-center">
            <div class="flex flex-col">
                <h4 :class="accountTextColor">{{ account?.name }}</h4>
                <small :class="accountTextColor">{{
                    account?.accountNumber
                }}</small>
            </div>
            <h5 :class="accountTypeColor" class="font-bold">
                {{ account?.type }}
            </h5>
        </div>
        <div class="flex flex-row mt-6 items-center justify-between">
            <h4 :class="accountTypeColor" class="font-bold">
                {{ account?.closingBalance }}
            </h4>
            <router-link
                :class="accountTypeLink"
                :to="{ path: `/accounts/${account.accountNumber}` }"
                class="p-1 rounded text-white"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="w-6 h-6"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"
                    />
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                    />
                </svg>
            </router-link>
        </div>
    </div>
</template>
<script>
export default {
    props: {
        account: {
            type: Object,
            required: false,
        },
    },
    computed: {
        accountBackgroundColor() {
            return {
                "bg-purple-600 bg-opacity-25 dark:bg-purple-800":
                    this.account.type === "CREDIT",
                "bg-blue-600 bg-opacity-25 dark:bg-blue-800":
                    this.account.type === "CHEQ",
                "bg-green-600 bg-opacity-25 dark:bg-green-800":
                    this.account.type === "SAVINGS",
            };
        },
        accountTextColor() {
            return {
                "text-purple-800 dark:text-gray-300":
                    this.account.type === "CREDIT",
                "text-blue-800 dark:text-gray-300":
                    this.account.type === "CHEQ",
                "text-green-800 dark:text-gray-300":
                    this.account.type === "SAVINGS",
            };
        },
        accountTypeColor() {
            return {
                "text-purple-900 dark:text-purple-400": this.account.type === "CREDIT",
                "text-blue-800 dark:text-blue-400": this.account.type === "CHEQ",
                "text-green-800 dark:text-green-400": this.account.type === "SAVINGS",
            };
        },

        accountTypeLink() {
            return {
                "bg-purple-600 hover:bg-purple-500":
                    this.account.type === "CREDIT",
                "bg-blue-600 hover:bg-blue-500": this.account.type === "CHEQ",
                "bg-green-600 hover:bg-green-500":
                    this.account.type === "SAVINGS",
            };
        },
    },
};
</script>
