import Index from "../Pages/Index.vue";
import Login from "../Pages/Login.vue";
import AccountIndex from "../Pages/AccountIndex.vue";
import Transaction from "../Pages/Transaction.vue";
import AccountView from "../Pages/AccountView.vue";

const routes = [
    {
        path: "/",
        component: Index,
    },
    {
        path: "/login",
        component: Login,
    },
    {
        path: "/accounts",
        component: AccountIndex,
        exact: true,
    },
    {
        path: "/accounts/:accountNumber",
        component: AccountView,
        exact: true,
        props: true,
    },
    {
        path: "/transactions",
        component: Transaction,
    },
];

export default routes;
