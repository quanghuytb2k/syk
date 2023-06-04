import MapList from "@/views/map/List.vue";
import MapCreate from "@/views/map/Create.vue";
import MapEdit from "@/views/map/Edit.vue";

export const MapManagementRoutes = [
    {
        path: "/map/list",
        name: "map.list",
        component: MapList,
        meta: {
            menuKey: "map_management",
        },
    },
    {
        path: "/map/create",
        name: "map.create",
        component: MapCreate,
        meta: {
            menuKey: "map_management",
        },
    },
    {
        path: "/map/:mapId/edit",
        name: "map.edit",
        component: MapEdit,
        meta: {
            menuKey: "map_management",
        },
    },
]
