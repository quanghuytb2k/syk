import {authApi} from "./authApi";
import axios from "@/http-common";
import store from "@/stores/_loader";
import _ from "lodash";

const path = '/api/user'
export default {
    /**
     * api get list
     * @param params
     * @returns {Promise<*>}
     */
    list(params) {
        return authApi.post(`${path}/list`, params);
    },

    // api get list screen data
    listScreenData() {
        return authApi.post(`${path}/list-screen-data`);
    },

    /**
     * api get detail by id
     * @param id
     * @returns {Promise<*|undefined>}
     */
    detail(id) {
        return authApi.get(`${path}/detail/${id}`);
    },

    /**
     * api create
     * @param params
     * @returns {Promise<*>}
     */
    create(params) {
        return authApi.post(`${path}/create`, params);
    },

    /**
     * api update
     * @param id
     * @param params
     * @returns {Promise<*>}
     */
    update(id, params) {
        return authApi.post(`${path}/update/${id}`, params);
    },

    /**
     *
     * @returns {Promise<unknown[]>}
     */
    async getAllRoles() {
        let role = store.getters["role/getRole"]
        let list_role
        await axios.get(`/api/auth/get-all-roles`)
            .then(res => {
                list_role = res.data.data

            })

        if (role && role === 'agency_owner') {
            list_role = list_role.map(val => {
                if (val.name !== 'admin') return val
            })
        }

        if (role && role === 'agency_staff') {
            list_role = list_role.map(val => {
                if (val.name !== 'admin' && val.name !== 'agency_owner') return val
            })
        }

        if (role && role === 'company_owner') {
            list_role = list_role.map(val => {
                if (val.name !== 'admin' && !_.includes(val.name, 'agency')) return val
            })
        }

        if (role && role === 'company_staff') {
            list_role = list_role.map(val => {
                if (val.name !== 'admin' && !_.includes(val.name, 'agency') && val.name !== 'company_owner') return val
            })
        }

        if (role && role === 'facility_staff') {
            list_role = list_role.map(val => {
                if (val.name !== 'admin' && !_.includes(val.name, 'agency') && !_.includes(val.name, 'company')) return val
            })
        }

        return _.compact(list_role)
    },

    deleteUser(id) {
        return authApi.delete(`${path}/delete/${id}`)
    }
}
