import BuildingList from "@/views/building/List.vue";
import BuildingCreate from "@/views/building/Create.vue";
import BuildingEdit from "@/views/building/Edit.vue";

export const BuildingRoutes = [
    {
        path: "/building/list",
        name: "building.list",
        component: BuildingList,
        meta: {
            menuKey: "building",
        },
    },
    {
        path: "/building/create",
        name: "building.create",
        component: BuildingCreate,
        meta: {
            menuKey: "building",
        },
    },
    {
        path: "/building/:buildingId/edit",
        name: "building.edit",
        component: BuildingEdit,
        meta: {
            menuKey: "building",
        },
    },
]
