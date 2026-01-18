import axios from "axios";

const api = axios.create({
    baseURL: process.env.REACT_APP_API_URL || "http://localhost:8000/api",
    headers: { "Content-Type": "application/json" },
    withCredentials: true, // if using cookie-based auth
});

// request interceptor: attach token if present
api.interceptors.request.use((config) => {
    const token = localStorage.getItem("token"); // or use Auth context
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

export default api;
