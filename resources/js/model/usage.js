import {authApi} from "./authApi";

const path = '/api/energy-usage';

export const UsageModel = {
    /**
     * Get list usage
     * @param params
     * @returns {Promise<*>}
     */
    listUsage(params) {
        return authApi.post(`${path}/list-energy-usage`, params)
    },

    /**
     * Create usage
     * @param params
     * @param files
     * @returns {Promise<Result|*>}
     */
    create(params, files) {
        return authApi.postForm(`${path}/create-usage`, params, files)
    },

    /**
     *
     * @param id
     * @param params
     * @returns {Promise<Result|*>}
     */
    update(id, params)
    {
        return authApi.post(`${path}/update-usage/${id}`, params)
    },

    /**
     * Get detail usage
     * @param id
     * @returns {Promise<*>}
     */
    getDetail(id) {
        return authApi.get(`${path}/energy-usage-detail/${id}`)
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
    /**
     *
     * @param params
     * @param files
     * @returns {Promise<Result|*>}
     */
    uploadFile(params, files) {
        return authApi.postForm(`${path}/upload-file`, params, files)
    },

    /**
     *
     * @param id
     * @param link
     * @returns {Promise<Result|*>}
     */
    deleteFile(id, link) {
        return authApi.postForm(`${path}/delete-file/${id}`, link)
    },

    deleteUsage(id) {
        return authApi.delete(`${path}/delete-usage/${id}`)
    }
}
