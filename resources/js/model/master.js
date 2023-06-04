import {authApi} from "./authApi";

const path = '/api/master';

export const MasterModel = {
    /**
     *
     * @returns {Promise<*>}
     */
    getListMasterTable()
    {
        return authApi.get(`${path}/get-list-master-table`);
    },

    /**
     *
     * @param table
     * @returns {Promise<Result|*>}
     */
    getMasterTable(table)
    {
        return authApi.post(`${path}/get-master-table`, {table})
    },

    /**
     *
     * @param params
     * @returns {Promise<Result|*>}
     */
    updateRecordMasterTable(params)
    {
        return authApi.post(`${path}/update-record-master-table`, params, false)
    },

    /**
     *
     * @param params
     * @returns {Promise<Result|*>}
     */
    createRecordMasterTable(params)
    {
        return authApi.post(`${path}/create-record-master-table`, params)
    },

    /**
     *
     * @param params
     * @returns {Promise<Result|*>}
     */
    deleteRecordMasterTable(params)
    {
        return authApi.post(`${path}/delete-record-master-table`, params)
    },

    /**
     *
     * @returns {Promise<*>}
     */
    getDivisionTreeView()
    {
        return authApi.get(`${path}/division-tree-view`)
    }
}
