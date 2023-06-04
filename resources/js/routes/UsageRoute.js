import UsageList from "@/views/usage/List.vue"
import UsageCreate from "@/views/usage/Create.vue"
import UsageEdit from "@/views/usage/Edit.vue"

export const UsageRoute = [
    {
        path: "/usage/list",
        name: "usage.list",
        component: UsageList,
        meta: {
            menuKey: "usage",
        },
    },
    {
        path: "/usage/create",
        name: "usage.create",
        component: UsageCreate,
        meta: {
            menuKey: "usage",
        },
    },
    {
        path: "/usage/:usageId/edit",
        name: "usage.edit",
        component: UsageEdit,
        meta: {
            menuKey: "usage",
        },
    },
]
