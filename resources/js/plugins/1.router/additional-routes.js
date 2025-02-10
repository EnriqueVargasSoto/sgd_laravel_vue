/* const emailRouteComponent = () => import('@/pages/apps/email/index.vue')
 */
// 👉 Redirects
export const redirects = [
  // ℹ️ We are redirecting to different pages based on role.
  // NOTE: Role is just for UI purposes. ACL is based on abilities.
  {
    path: '/',
    name: 'index',
    redirect: to => {
      // TODO: Get type from backend
      const userData = useCookie('userData')
      const userRole = userData.value?.role
      if (userRole === 'admin')
        return { name: 'dashboard' }
      if (userRole === 'client')
        return { name: 'usuarios' }

      return { name: 'login', query: to.query }
    },
  },
  /* {
    path: '/pages/user-profile',
    name: 'pages-user-profile',
    redirect: () => ({ name: 'pages-user-profile-tab', params: { tab: 'profile' } }),
  },
  {
    path: '/pages/account-settings',
    name: 'pages-account-settings',
    redirect: () => ({ name: 'pages-account-settings-tab', params: { tab: 'account' } }),
  }, */
]
//export const routes = [
  // Email filter
  /* {
    path: '/apps/email/filter/:filter',
    name: 'apps-email-filter',
    component: emailRouteComponent,
    meta: {
      navActiveLink: 'apps-email',
      layoutWrapperClasses: 'layout-content-height-fixed',
    },
  }, */

  // Email label
  /* {
    path: '/apps/email/label/:label',
    name: 'apps-email-label',
    component: emailRouteComponent,
    meta: {
      // contentClass: 'email-application',
      navActiveLink: 'apps-email',
      layoutWrapperClasses: 'layout-content-height-fixed',
    },
  },
  {
    path: '/dashboards/logistics',
    name: 'dashboards-logistics',
    component: () => import('@/pages/apps/logistics/dashboard.vue'),
  },
  {
    path: '/dashboards/academy',
    name: 'dashboards-academy',
    component: () => import('@/pages/apps/academy/dashboard.vue'),
  },
  {
    path: '/apps/ecommerce/dashboard',
    name: 'apps-ecommerce-dashboard',
    component: () => import('@/pages/dashboards/ecommerce.vue'),
  }, */
//]
export const routes = [
    { path: '/', redirect: '/dashboard' },
    {
        path: '/dashboard', // 📌 Asegúrate de que la ruta sea correcta
        name: 'dashboard',
        component: () => import('@/views/dashboards/Dashboard.vue'),//Dashboard,//DashboardAnalytics
        meta: { requiresAuth: true } // Ruta protegida
    },
    {
        path: '/modulos', // 📌 Asegúrate de que la ruta sea correcta
        name: 'modulos',
        component: () => import('@/views/modulos/Index.vue'),//DashboardAnalytics
        meta: { requiresAuth: true } // Ruta protegida
    },
    {
        path: '/usuarios', // 📌 Asegúrate de que la ruta sea correcta
        name: 'usuarios',
        component: () => import('@/views/users/Index.vue'),//DashboardAnalytics
        meta: { requiresAuth: true } // Ruta protegida
    },
    {
        path: '/roles-permisos/roles', // 📌 Asegúrate de que la ruta sea correcta
        name: 'roles',
        component: () => import('@/views/roles-permisos/roles/Index.vue'),//DashboardAnalytics
        meta: { requiresAuth: true } // Ruta protegida
    },
    {
        path: '/roles-permisos/permisos', // 📌 Asegúrate de que la ruta sea correcta
        name: 'permisos',
        component: () => import('@/views/roles-permisos/permisos/Index.vue'),//DashboardAnalytics
        meta: { requiresAuth: true } // Ruta protegida
    },
    {
        path: '/login', // 📌 Asegúrate de que la ruta sea correcta
        name: 'login',
        meta: {
            layout: 'blank',
/*             public: true, */
            unauthenticatedOnly: true,
        },
        component: () => import('@/views/login/Index.vue')//DashboardAnalytics
    }
];
