import '../css/app.css';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import type { DefineComponent } from 'vue';
import { createApp, h } from 'vue';
import { initializeTheme } from './composables/useAppearance';
import axios from 'axios';
const appName = import.meta.env.VITE_APP_NAME || 'To-Do List';

// Enable credentials (cookies) to be sent with requests
axios.defaults.withCredentials = true;

// Set common headers
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Helper to get XSRF token from cookie
function getXSRFToken(): string | null {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; XSRF-TOKEN=`);
    if (parts.length === 2) {
        return decodeURIComponent(parts.pop()?.split(';').shift() || '');
    }
    return null;
}

// Interceptor to automatically add XSRF token to every request
axios.interceptors.request.use(config => {
    const token = getXSRFToken();
    if (token) {
        config.headers['X-XSRF-TOKEN'] = token;
    }
    return config;
});

// Handle auth errors
axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response?.status === 401) {
            window.location.href = '/login';
        } else if (error.response?.status === 419) {
            // CSRF token mismatch - refresh and retry
            console.warn('CSRF token mismatch, refreshing...');
            return axios.get('/sanctum/csrf-cookie').then(() => {
                // Retry the original request
                return axios.request(error.config);
            });
        }
        return Promise.reject(error);
    }
);

createInertiaApp({
    title: (title) => (title ? `${title} - ${appName}` : appName),
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });
        
        app.use(plugin);
        
        // Initialize CSRF cookie before mounting
        axios.get('/sanctum/csrf-cookie').then(() => {
            app.mount(el);
        });
    },
    progress: {
        color: '#4B5563',
    },
});

// This will set light / dark mode on page load...
initializeTheme();
