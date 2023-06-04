import {authApi} from "./authApi";

const path = `/api/energy-contract`
export const EnergyContractModel = {
    /**
     * Get list contract
     * @param params
     * @returns {Promise<*|undefined>}
     */
    list(params) {
        return authApi.post(`${path}/list-energy-contract`, params)
    },

    /**
     * Get contract by id
     * @param id
     * @returns {Promise<*|undefined>}
     */
    detail(id) {
        return authApi.get(`${path}/contract-detail/${id}`)
    },

    /**
     * Create contract
     * @param params
     * @param files
     * @returns {Promise<*>}
     */
    create(params, files) {
        return authApi.postForm(`${path}/create-contract`, params, files)
    },

    /**
     * Update contract
     * @param id
     * @param params
     * @returns {Promise<*>}
     */
    update(id, params) {
        return authApi.post(`${path}/update-contract/${id}`, params)
    },

    /**
     * Get list energy type
     * @returns {Promise<*|undefined>}
     */
    getListEnergyType()
    {
        return authApi.get(`${path}/list-energy-type`)
    },

    /**
     * Get contract by energy type, company, facility and building
     * @param params
     * @returns {Promise<*|undefined>}
     */
    getContractByCondition(params)
    {
        return authApi.get(`${path}/get-contract`, params)
    },

    /**
     *
     * @param params
     * @param files
     * @returns {Promise<*>}
     */
    uploadFile(params, files) {
        return authApi.postForm(`${path}/upload-file`, params, files)
    },

    /**
     *
     * @param id
     * @param link
     * @returns {Promise<*>}
     */
    deleteFile(id, link) {
        return authApi.postForm(`${path}/delete-file/${id}`, link)
    },

    /**
     *
     * @param id
     * @param link
     * @returns {Promise<*>}
     */
    downloadFile(id, link) {
        return authApi.post(`${path}/download-file/${id}`, link)
    },
}
