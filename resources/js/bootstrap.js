/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from "axios";
import Vue from "vue";
import VueRouter from "vue-router";
import App from "./Components/App.vue";
import Dropdown from "./Components/Dropdown.vue";
import Input from "./Components/Input.vue";
import Navbar from "./Components/Navbar.vue";
import Select from "./Components/Select.vue";

window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

Vue.use(VueRouter);
Vue.component("Dropdown", Dropdown);
Vue.component("Input", Input);
Vue.component("Navbar", Navbar);
Vue.component("Select", Select);


new Vue({
    render: (h) => h(App),
}).$mount("#app");
