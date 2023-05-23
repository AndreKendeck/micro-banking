/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import Vue from "vue";
import VueRouter from "vue-router";
import App from "./Components/App.vue";
import Dropdown from "./Components/Dropdown.vue";
import Input from "./Components/Input.vue";
import Navbar from "./Components/Navbar.vue";
import Select from "./Components/Select.vue";
import Button from "./Components/Button.vue";
import Message from "./Components/Message.vue";
import Table from "./Components/Table.vue";
import routes from "./Routes";
import store from "./Store";
import Vuex from "vuex";

Vue.use(VueRouter);
Vue.use(Vuex);

Vue.component("Dropdown", Dropdown);
Vue.component("Input", Input);
Vue.component("Navbar", Navbar);
Vue.component("Select", Select);
Vue.component("Button", Button);
Vue.component("Message", Message);
Vue.component("Table", Table);

const router = new VueRouter({
    routes,
    mode: "history",
});

new Vue({
    render: (h) => h(App),
    router: router,
    store: new Vuex.Store(store),
}).$mount("#app");
