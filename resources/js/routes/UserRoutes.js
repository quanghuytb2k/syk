import UserList from "@/views/user/List.vue";
import UserCreate from "@/views/user/Create.vue";
import UserEdit from "@/views/user/Edit.vue";
import store from "@/stores/_loader";

const accessRole = store.state.accessRole.userManagementRole
export const UserRoutes = [
    {
        path: "/user/list/:id?",
        name: "user.list",
        component: UserList,
        meta: {
            menuKey: "user",
            accessRole
        },
    },
    {
        path: "/user/create",
        name: "user.create",
        component: UserCreate,
        meta: {
            menuKey: "user",
            accessRole
        },
    },
    {
        path: "/user/edit/:id",
        name: "user.edit",
        component: UserEdit,
        meta: {
            menuKey: "user",
            accessRole
        },
    }
]
