import FacilityList from "@/views/facility/List.vue";
import FacilityCreate from "@/views/facility/Create.vue";
import FacilityEdit from "@/views/facility/Edit.vue";
import store from "@/stores/_loader";

const accessRole = store.state.accessRole.companyRole
export const FacilityRoutes = [
    {
        path: "/facility/list",
        name: "facility.list",
        component: FacilityList,
        meta: {
            menuKey: "facility",
            accessRole
        },
    },
    {
        path: "/facility/create",
        name: "facility.create",
        component: FacilityCreate,
        meta: {
            menuKey: "facility",
            accessRole
        },
    },
    {
        path: "/facility/:facilityId/edit",
        name: "facility.edit",
        component: FacilityEdit,
        meta: {
            menuKey: "facility",
            accessRole
        },
    },
]
