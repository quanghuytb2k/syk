import MasterList from '@/views/master/List.vue'
import store from "@/stores/_loader";

const accessRole = store.state.accessRole.adminRole
export const MasterRoute = [
    {
        path: "/master/list",
        name: "master.list",
        component: MasterList,
        meta: {
            menuKey: "master",
            accessRole
        },
    }
]
