import EquipmentList from "@/views/equipment/List.vue"
import EquipmentCreate from "@/views/equipment/Create.vue"
import EquipmentEdit from "@/views/equipment/Edit.vue"
export const EquipmentRoutes = [
    {
        path: '/equipment/list',
        name: "equipment.list",
        component: EquipmentList,
        meta: {
            menuKey: "equipment",
        }
    },
    {
        path: '/equipment/create',
        name: "equipment.create",
        component: EquipmentCreate,
        meta: {
            menuKey: "equipment",
        }
    },
    {
        path: '/equipment/:equipmentId/edit',
        name: "equipment.edit",
        component: EquipmentEdit,
        meta: {
            menuKey: "equipment",
        }
    }
]
