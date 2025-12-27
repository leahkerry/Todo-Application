import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { initializeTheme } from './composables/useAppearance';

// NOTE: added this until NOTEEND
import axios from 'axios';

// Enable credentials (cookies) to be sent with requests
axios.defaults.withCredentials = true;

// Set common headers
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.get('/sanctum/csrf-cookie');

// Get CSRF token from meta tag (if using session auth)
const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.getAttribute('content');
}

// Optional: Add interceptor to handle 401/403 errors
// axios.interceptors.response.use(
//     response => response,
//     error => {
//         if (error.response?.status === 401) {
//             // User is not authenticated - redirect to login
//             window.location.href = '/login';
//         } else if (error.response?.status === 403) {
//             // User is authenticated but not authorized
//             console.error('Forbidden: You do not have permission to perform this action');
//         }
//         return Promise.reject(error);
//     }
// );
//NOTEEND
const appName = import.meta.env.VITE_APP_NAME || 'To-Do List';

// NOTE: added then removed these 4 lines
// import TodoApp from "./pages/TodoApp.vue";
// const app = createApp({});
// app.component("todo-app", TodoApp);
// app.mount("#app");

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...
initializeTheme();
