import axios from "axios";

export default axios.create({
    baseUrl: import.meta.env.VITE_APP_URL,
    headers: {
        "Content-Type": "application/json",
        "X-Requested-With": "XMLHttpRequest",
    }
});
