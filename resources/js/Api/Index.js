import axios from "axios";

const api = axios.create({
    headers: {
        "Content-Type": "application/json",
        Accept: "application/json",
        "X-Requested-With": "XMLHttpRequest",
        Authorization: `Bearer ${localStorage.getItem("authToken")}`,
    },
});

export default api;
