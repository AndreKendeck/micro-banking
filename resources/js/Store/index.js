import api from "../Api/Index";

const store = {
    state: {
        authenticated: false,
        accounts: [],
        user: null,
        currentForm: {},
        account: {},
        currentTransaction: {},
        systemMessage: null,
    },
    getters: {
        isAuthenticated: (state) => state.authenticated,
        getAccountByNumber: (state) => (accountNumber) => {
            const account = state.accounts.find( account => account.accountNumber === accountNumber );
            return account;
        },
        getCurrentForm: (state) => state.currentForm,
        getUser: (state) => state.user,
        getSystemMessage: (state) => state.systemMessage,
        getAccounts: (state) => state.accounts,
    },
    mutations: {
        setAuthenticated: (state, authenticated) =>
            (state.authenticated = authenticated),
        setAccounts: (state, accounts) => (state.accounts = accounts),
        setUser: (state, user) => (state.user = user),
        updateForm: (state, { key, value, errors = [] }) => {
            state.currentForm = {
                ...state.currentForm,
                [key]: { value, errors },
            };
        },
        resetForm: (state) => (state.currentForm = {}),
        setAccount: (state, account) => (state.account = account),
        setTransaction: (state, transaction) =>
            (state.transaction = transaction),
        setErrorMessage: (state, message) =>
            (state.systemMessage = { text: message, type: "ERROR" }),
        setSuccessMessage: (state, message) =>
            (state.systemMessage = { text: message, type: "SUCCESS" }),
        clearSystemMessage: (state) => (state.systemMessage = null),
    },
    actions: {
        clearSystemMessage({ commit }) {
            commit("clearSystemMessage");
        },
        login({ commit, state }, { email, password }) {
            if (state.authenticated) return;
            api.post("/api/login", { email, password })
                .then((successResponse) => {
                    const { token, user } = successResponse.data;
                    localStorage.setItem("authToken", token);
                    api.defaults.headers.common.Authorization = `Bearer ${token}`;
                    commit("setUser", user);
                    commit("setAuthenticated", true);
                    commit("resetForm");
                    window.location = "/";
                })
                .catch((failedResponse) => {
                    const { errors } = failedResponse.response.data;
                    for (let errorKey in errors) {
                        commit("updateForm", {
                            key: errorKey,
                            value: state.currentForm[errorKey].value,
                            errors: errors[errorKey],
                        });
                    }
                });
        },
        authCheck({ commit }) {
            api.get("/api/users/current")
                .then((successResponse) => {
                    commit("setAuthenticated", true);
                    commit("setUser", successResponse.data);
                })
                .catch((failedResponse) => {
                    commit("setAuthenticated", false);
                    commit("setUser", {});
                    commit("setErrorMessage", "You are not logged in");
                });
        },
        updateForm({ commit }, { key, value }) {
            commit("updateForm", { key, value });
        },
        logout({ commit }) {
            api.post("/api/logout")
                .then((successResponse) => {
                    commit("setAuthenticated", false);
                    commit("setUser", {});
                })
                .catch((failedResponse) => {
                    const { message } = failedResponse.response.data;
                    commit("setErrorMessage", message);
                });
        },
        getAllAccounts({ commit }) {
            api.get("/api/accounts")
                .then((successResponse) => {
                    commit("setAccounts", successResponse.data);
                })
                .catch((failedResponse) => {
                    const { message } = failedResponse.response.data;
                    commit("setErrorMessage", message);
                });
        },
        getTransactions({ commit }) {
            
        }
    },
};

export default store;
