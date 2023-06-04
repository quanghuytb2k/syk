import {authApi} from "./authApi";

const path = '/api/agency';

/**
 *
 * @type {{create(*): Promise<*>, update(*, *): Promise<*>, detail(*): Promise<*|undefined>, list(*): Promise<*>, getAllAgencyNames(): Promise<*|undefined>}}
 */
export const agency = {

    /**
     * api get list agency
     * @param params
     * @returns {Promise<Result|*>}
     */
    list(params) {
        let url = `${path}/list`;
        return authApi.post(url, params);
    },

    /**
     * api get detail agency by id
     * @param id
     * @returns {Promise<*>}
     */
    detail(id) {
        return authApi.get(`${path}/detail/${id}`);
    },

    /**
     * api create agency
     * @param params
     * @returns {Promise<Result|*>}
     */
    create(params) {
        return authApi.post(`${path}/create`, params);
    },

    /**
     * api update agency
     * @param id
     * @param params
     * @returns {Promise<Result|*>}
     */
    update(id, params) {
        return authApi.post(`${path}/update/${id}`, params);
    },

    /**
     * api get all names
     * @returns {Promise<*>}
     */
    getAllAgencyNames() {
        return authApi.get(`${path}/all-agency-name`, null);
    },

    /**
     *
     * @param id
     * @returns {Promise<*>}
     */
    deleteAgency(id) {
        return authApi.delete(`${path}/delete-agency/${id}`)
    }
}
