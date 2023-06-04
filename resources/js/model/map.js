import {authApi} from "./authApi";

const path = `/api/map`

export default {
    /**
     * Get list map
     * @param params
     * @returns {Promise<*|undefined>}
     */
    list(params) {
        return authApi.post(`${path}/list-map`, params)
    },

    /**
     * Create map
     * @param params
     * @param files
     * @returns {Promise<*>}
     */
    create(params, files) {
        return authApi.postForm(`${path}/create-map`, params, files)
    },

    /**
     * Update map
     * @param id
     * @param params
     * @returns {Promise<*>}
     */
    update(id, params) {
        return authApi.post(`${path}/update-map/${id}`, params)
    },

    detail(id) {
        return authApi.get(`${path}/detail-map/${id}`)
    },

    listDrawingType() {
        return authApi.get(`${path}/list-drawing-type`)
    },
    /**
     *
     * @param id
     * @param link
     * @returns {Promise<Result|*>}
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
        return authApi.postForm(`${path}/delete-file/${id}`, link, null)
    },

    deleteMap(id) {
        return authApi.delete(`${path}/delete-map/${id}`)
    }
};
