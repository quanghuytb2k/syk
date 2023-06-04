import AgencyList from "@/views/agency/List.vue";
import AgencyCreate from "@/views/agency/Create.vue";
import AgencyEdit from "@/views/agency/Edit.vue";
import store from "@/stores/_loader";

const accessRole = store.state.accessRole.adminRole

export const AgencyRoutes = [
    {
        path: "/agency/list",
        name: "agency.list",
        component: AgencyList,
        meta: {
            menuKey: "agencies",
            accessRole
        },
    },
    {
        path: "/agency/create",
        name: "agency.create",
        component: AgencyCreate,
        meta: {
            menuKey: "agencies",
            accessRole
        },
    },
    {
        path: "/agency/:agencyId/edit",
        name: "agency.edit",
        component: AgencyEdit,
        meta: {
            menuKey: "agencies",
            accessRole
        },
    },
]
