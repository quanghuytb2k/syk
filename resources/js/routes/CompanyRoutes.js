import CompanyList from "@/views/company/List.vue";
import CompanyCreate from "@/views/company/Create.vue";
import CompanyEdit from "@/views/company/Edit.vue";
import store from "@/stores/_loader";

const accessRole = store.state.accessRole.agencyRole
export const CompanyRoutes = [
    {
        path: "/company/list/:id?",
        name: "company.list",
        component: CompanyList,
        meta: {
            menuKey: "company",
            accessRole
        },
    },
    {
        path: "/company/create",
        name: "company.create",
        component: CompanyCreate,
        meta: {
            menuKey: "company",
            accessRole
        },
    },
    {
        path: "/company/:companyId?/edit",
        name: "company.edit",
        component: CompanyEdit,
        meta: {
            menuKey: "company",
            accessRole
        },
    },
]
