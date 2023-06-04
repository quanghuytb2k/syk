import Home from "@/views/home/Home.vue";
import {UserRoutes} from "./UserRoutes";
import {EnergyContractRoutes} from "./EnergyContractRoutes";
import {BuildingRoutes} from "./BuildingRoutes";
import {AgencyRoutes} from "./AgencyRoutes";
import {CompanyRoutes} from "./CompanyRoutes";
import {FacilityRoutes} from "./FacilityRoutes";
import {AuthRoutes} from "./AuthRoutes";
import {UsageRoute} from "./UsageRoute";
import { createRouter, createWebHistory } from "vue-router";
import BootstrapView from "@/views/Bootstrap.vue";
import store from "@/stores/_loader";
import Error404 from "@/views/errors/404.vue"
import {MasterRoute} from "./MasterRoute";
import {EquipmentRoutes} from "./EquipmentRoutes";
import {MaintainCompanyRoutes} from "./MaintainCompanyRoutes";
import {MapManagementRoutes} from "./MapManagementRoutes";
import {AlarmRoutes} from "./AlarmRoutes";

const routers = createRouter({
    history: createWebHistory(),
    routes: [
        {
            component: BootstrapView,
            children: [
                // Home
                {
                    path: "/",
                    name: "home",
                    component: Home,
                    meta: {
                        menuKey: "home"
                    },
                },
                ...AuthRoutes,
                ...FacilityRoutes,
                ...CompanyRoutes,
                ...AgencyRoutes,
                ...BuildingRoutes,
                ...EnergyContractRoutes,
                ...UserRoutes,
                ...UsageRoute,
                ...MasterRoute,
                ...EquipmentRoutes,
                ...MaintainCompanyRoutes,
                ...MapManagementRoutes,
                ...AlarmRoutes,

                {
                    path: "/:pathMatch(.*)*",
                    name: 'NotFound',
                    component: Error404,
                },
            ],
        },
    ],
});

routers.beforeEach( async (to, from, next) => {
    let isAuthenticate = store.getters["auth/isLogged"];
    let user = store.getters["auth/getUser"]
    if (!isAuthenticate) {
        if (to.meta.auth === false) return next();
        return next({name: "login"});
    } else {
        let role = store.getters["role/getRole"]
        if (!role) {
            role = await store.dispatch('role/setRole')
        }

        let routeAccessRole = to.meta.accessRole ?? []
        if (routeAccessRole.length > 0 && !routeAccessRole.includes(role)) {
            return next({name: "home"});
        }

        if (user && user?.expires_in < Date.now()) {
            await store.dispatch('auth/refreshToken');
        }
        store.commit('route/setRoute', to.meta?.menuKey)
        return next();
    }
});

export default routers;
