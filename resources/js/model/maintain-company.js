import {authApi} from "./authApi";

const path = '/api/maintain-company';

export const MaintainCompanyModel = {
    /**
     *
     * @param params
     * @returns {Promise<*>}
     */
    list(params = {
        page: 1
    })
    {
        return authApi.post(`${path}/maintain-company-list`, params);
    },

    /**
     *
     * @param id
     * @returns {Promise<*>}
     */
    detail(id) {
        return authApi.get(`${path}/maintain-company-detail/${id}`);
    },

    /**
     *
     * @param params
     * @returns {Promise<Result|*>}
     */
    create(params) {
        return authApi.post(`${path}/create-maintain-company`, params)
    },

    /**
     *
     * @param params
     * @param id
     * @returns {Promise<Result|*>}
     */
    update(id, params) {
        return authApi.post(`${path}/update-maintain-company/${id}`, params)
    },

    /**
     *
     * @returns {Promise<*>}
     */
    getMaintainTypeList() {
        return authApi.get(`${path}/get-type`);
    },

    /**
     *
     * @returns {Promise<*>}
     */
    getMaintainDetailTypeList() {
        return authApi.get(`${path}/get-detail-type`);
    },

    /**
     *
     * @returns {Promise<*|undefined>}
     */
    getAllMaintainCompany() {
        return authApi.get(`${path}/get-all-maintain-company`)
    },

    deleteCompany(id) {
        return authApi.delete(`${path}/maintain-company-delete/${id}`)
    }
}
