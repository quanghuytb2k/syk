import {authApi} from "./authApi";

const path = '/api/facility';
export const FacilityModel = {

    /**
     * api get list facility
     * @param params
     * @returns {Promise<*|undefined>}
     */
    list(params = {
        page: 1,
        searchKey: null,
        prefectureId: null,
        agencyId: null,
        companyId: null
    }) {
        return authApi.post(`${path}/list`, params);
    },

    /**
     * api get detail facility by id
     * @param id
     * @returns {Promise<*|undefined>}
     */
    detail(id) {
        return authApi.get(`${path}/detail/${id}`);
    },

    /**
     * api create facility
     * @param params
     * @returns {Promise<*>}
     */
    create(params) {
        return authApi.post(`${path}/create`, params);
    },

    /**
     * api update facility
     * @param id
     * @param params
     * @returns {Promise<*>}
     */
    update(id, params) {
        return authApi.post(`${path}/update/${id}`, params);
    },

    /**
     * api get all names
     * @returns {Promise<*|undefined>}
     */
    getAllFacilityNames() {
        return authApi.get(`${path}/all-facility-name`);
    },

    /**
     *
     * @param agencyId
     * @param companyId
     * @returns {Promise<*|undefined>}
     */
    getFacilityByParent(agencyId, companyId) {
        return authApi.get(`${path}/filter-facility-by-parent/${agencyId ?? 0}/${companyId ?? 0}`)
    },

    deleteFacility(id) {
        return authApi.delete(`${path}/delete-facility/${id}`)
    }
}
