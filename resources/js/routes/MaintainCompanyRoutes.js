import MaintainCompanyList from "@/views/maintain-company/List.vue"
import MaintainCompanyCreate from "@/views/maintain-company/Create.vue"
import MaintainCompanyEdit from "@/views/maintain-company/Edit.vue"

export const MaintainCompanyRoutes = [
    {
        path: "/maintain-company/list",
        name: "maintain-company.list",
        component: MaintainCompanyList,
        meta: {
            menuKey: "maintain-company"
        }
    },
    {
        path: "/maintain-company/create",
        name: "maintain-company.create",
        component: MaintainCompanyCreate,
        meta: {
            menuKey: "maintain-company"
        }
    },
    {
        path: "/maintain-company/:maintainId/edit",
        name: "maintain-company.edit",
        component: MaintainCompanyEdit,
        meta: {
            menuKey: "maintain-company"
        }
    }
]
