import {authApi} from "./authApi";

const path = '/api/equipment';

export const EquipmentModel = {
    /**
     *
     * @param params
     * @returns {Promise<*>}
     */
    getListEquipment(params)
    {
        return authApi.post(`${path}/list-equipment`, params)
    },

    /**
     *
     * @param id
     * @returns {Promise<*>}
     */
    getEquipmentDetail(id) {
        return authApi.get(`${path}/equipment-detail/${id}`)
    },

    /**
     *
     * @param params
     * @returns {Promise<Result|*>}
     */
    createEquipment(params) {
        return authApi.post(`${path}/create-equipment`, params)
    },

    /**
     *
     * @param id
     * @param params
     * @returns {Promise<Result|*>}
     */
    updateEquipment(id, params) {
        return authApi.post(`${path}/update-equipment/${id}`, params)
    },

    /**
     *
     * @param params
     * @param files
     * @returns {Promise<*>}
     */
    createMaintainHistory(params, files) {
        return authApi.postForm(`${path}/create-maintain-history`, params, files)
    },

    /**
     *
     * @param params
     * @param equipment_id
     * @returns {Promise<*|undefined>}
     */
    getMaintainHistoryList(params, equipment_id) {
        return authApi.get(`${path}/get-list-maintain-history/${equipment_id}`, params)
    },

    /**
     *
     * @param params
     * @param files
     * @returns {Promise<*>}
     */
    uploadHistoryFiles(params, files) {
        return authApi.postForm(`${path}/upload-maintain-history-files`, params, files)
    },

    /**
     *
     * @param id
     * @returns {Promise<*|undefined>}
     */
    getHistoryDetail(id) {
        return authApi.get(`${path}/get-history-detail/${id}`)
    },

    /**
     *
     * @param params
     * @returns {Promise<*>}
     */
    deleteHistoryFile(params) {
        return authApi.post(`${path}/delete-history-file`, params);
    },

    /**
     *
     * @param params
     * @param id
     * @returns {Promise<*>}
     */
    updateMaintainHistory(params, id) {
        return authApi.post(`${path}/update-maintain-history/${id}`, params);
    },

    /**
     *
     * @param id
     * @returns {Promise<*>}
     */
    deleteEquipment(id) {
        return authApi.delete(`${path}/delete-equipment/${id}`)
    }
}
