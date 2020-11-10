import Vue from "vue";
import VueRouter from "vue-router";
Vue.use(VueRouter);

import store from "./store/index";

const routes = [
    {
        path: "/",
        // redirect: { name: "login" }
        redirect: { name: "dashboard" }
        // component: require("./components/HomeComponent.vue").default
    },
    {
        path: "/set-up",
        name: "set-up",
        component: require("./components/SetupComponent.vue").default
    },
    {
        path: "/login",
        name: "login",
        component: require("./components/LoginComponent.vue").default
    },
    {
        path: "/logout",
        name: "logout"
    },
    {
        path: "/dashboard",
        name: "dashboard",
        component: require("./components/DashboardComponent.vue").default,
        requiresAuth: true
    },
    {
        path: "/category",
        name: "category",
        component: require("./components/CategoryComponent.vue").default,
        requiresAuth: true
    },
    {
        path: "/brand",
        name: "brand",
        component: require("./components/BrandComponent.vue").default,
        requiresAuth: true
    },
    {
        path: "/color",
        name: "color",
        component: require("./components/ColorComponent.vue").default,
        requiresAuth: true
    },
    {
        path: "/size",
        name: "size",
        component: require("./components/SizeComponent.vue").default,
        requiresAuth: true
    },
    {
        path: "/type",
        name: "type",
        component: require("./components/TypeComponent.vue").default,
        requiresAuth: true
    },
    {
        path: "/mail-setting",
        name: "mail-setting",
        component: require("./components/MailSettingComponent.vue").default,
        requiresAuth: true
    },
    {
        path: "/sms-setting",
        name: "sms-setting",
        component: require("./components/SmsSettingComponent.vue").default,
        requiresAuth: true
    },
    {
        path: "/supplier",
        name: "supplier",
        component: require("./components/SupplierComponent.vue").default,
        requiresAuth: true
    },
    {
        path: "/blank",
        name: "blank",
        component: require("./components/BlankComponent.vue").default,
        requiresAuth: true
    }
];

const router = new VueRouter({
    mode: "history",
    routes
});

router.beforeEach((to, from, next) => {
    if (
        to.matched.some(route => route.requiresAuth) &&
        !store.state.auth.isAuthenticated
    ) {
        next({ name: "login" });
        return;
    }

    if (to.path === "/login" && store.state.auth.isAuthenticated) {
        next({ name: "dashboard" });
        return;
    }

    next();
});

export default router;
