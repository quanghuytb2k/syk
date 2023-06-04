import Login from "@/views/auth/Login.vue";
import Register from "@/views/auth/Register.vue";
import RegisterComplete from "@/views/auth/RegisterComplete.vue";
import Activate from "@/views/auth/Activate.vue";
import ChangePassword from "@/views/auth/ChangePassword.vue";
import ForgotPassword from "@/views/auth/ForgotPassword.vue";
import ForgotPasswordRequested from "@/views/auth/ForgotPasswordRequested.vue";
import ResetPassword from "@/views/auth/ResetPassword.vue";
import ResetPasswordCompleted from "@/views/auth/ResetPasswordCompleted.vue";

export const AuthRoutes = [
    {
        path: "/login",
        name: "login",
        component: Login,
        meta: {
            // Default route require authentication. Set to false if no authentication is required.
            auth: false,
        },
    },
    {
        path: "/register",
        name: "register",
        component: Register,
        meta: {
            auth: false,
        },
    },
    {
        path: "/auth/register-complete",
        name: "auth.register-complete",
        component: RegisterComplete,
        meta: {
            auth: false,
        },
    },
    {
        path: "/auth/activate",
        name: "auth.activate",
        component: Activate,
        meta: {
            auth: false,
        },
    },
    {
        path: "/auth/changepass",
        name: "auth.changepass",
        component: ChangePassword,
    },
    {
        path: "/auth/forgotpass",
        name: "auth.forgotpass",
        component: ForgotPassword,
        meta: {
            auth: false,
        },
    },
    {
        path: "/auth/forgotpass-requested",
        name: "auth.forgotpass-requested",
        component: ForgotPasswordRequested,
        meta: {
            auth: false,
        },
    },
    {
        path: "/auth/resetpass",
        name: "auth.resetpass",
        component: ResetPassword,
        meta: {
            auth: false,
        },
    },
    {
        path: "/auth/resetpass-completed",
        name: "auth.resetpass-completed",
        component: ResetPasswordCompleted,
        meta: {
            auth: false,
        },
    },
]
