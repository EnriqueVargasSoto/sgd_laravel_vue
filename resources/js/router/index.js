import { createRouter, createWebHistory, createWebHashHistory } from 'vue-router';
import Home from '../views/Home.vue';
import Dashboard from '../views/dashboards/Dashboard.vue';
import IndexPermisos from '../views/roles-permisos/permisos/Index.vue';
import IndexRoles from '../views/roles-permisos/roles/Index.vue';
import IndexUsuarios from '../views/users/Index.vue';

const routes = [
    { path: '/', redirect: '/dashboard' },
    {
        path: '/dashboard', // 📌 Asegúrate de que la ruta sea correcta
        name: 'dashboard',
        component: Dashboard//DashboardAnalytics
    },
    {
        path: '/usuarios', // 📌 Asegúrate de que la ruta sea correcta
        name: 'usuarios',
        component: IndexUsuarios//DashboardAnalytics
    },
    {
        path: '/roles-permisos/roles', // 📌 Asegúrate de que la ruta sea correcta
        name: 'roles',
        component: IndexRoles//DashboardAnalytics
    },
    {
        path: '/roles-permisos/permisos', // 📌 Asegúrate de que la ruta sea correcta
        name: 'permisos',
        component: IndexPermisos//DashboardAnalytics
    }
];

const router = createRouter({
    history: createWebHashHistory(),//createWebHistory(),
    routes,
});

export default router;
