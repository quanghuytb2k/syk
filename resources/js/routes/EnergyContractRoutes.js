import EnergyContract from "@/views/energyContract/List.vue";
import EnergyContractCreate from "@/views/energyContract/Create.vue";
import EnergyContractEdit from "@/views/energyContract/Edit.vue";

export const EnergyContractRoutes = [
    {
        path: '/energy-contract/list',
        name: "energy_contract.list",
        component: EnergyContract,
        meta: {
            menuKey: "energy_contract",
        },
    },
    {
        path: '/energy-contract/create',
        name: "energy_contract.create",
        component: EnergyContractCreate,
        meta: {
            menuKey: "energy_contract",
        },
    },
    {
        path: '/energy-contract/:contractId/edit',
        name: "energy_contract.edit",
        component: EnergyContractEdit,
        meta: {
            menuKey: "energy_contract",
        },
    },
]
