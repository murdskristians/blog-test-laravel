import axios from 'axios';

const instance = axios.create({
    baseURL: '/api',
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
        'Content-Type': 'application/json',
    },
});

instance.interceptors.request.use(config => {
    const token = document.head.querySelector('meta[name="csrf-token"]');
    if (token) {
        config.headers['X-CSRF-TOKEN'] = token.content;
    }
    return config;
});


axios.defaults.baseURL = 'http://127.0.0.1:8000/api';
export default axios;
