import {authApi} from "./authApi";

const path = '/api/storage';

export const StorageModel = {
    /**
     * upload file by form api
     * @param params
     * @param files
     * @returns {Promise<Result|*>}
     */
    upload(params, files)
    {
        return authApi.postForm(`${path}/upload-file`, params, files)
    },

    /**
     * Get link download file
     * @param id
     * @returns {Promise<*>}
     */
    downloadFile(id)
    {
        return authApi.get(`${path}/download-file/${id}`)
    },

    /**
     * Delete file by id
     * @param fileId
     * @returns {Promise<*|Result>}
     */
    deleteFile(fileId) {
        return authApi.delete(`${path}/delete-file/${fileId}`)
    }
}
