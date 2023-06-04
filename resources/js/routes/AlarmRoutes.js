import AlarmList from "@/views/alarm/List.vue";

export const AlarmRoutes = [
    {
        path: "/alarm/list",
        name: "alarm.list",
        component: AlarmList,
        meta: {
            menuKey: "alarms",
        },
    },
];
