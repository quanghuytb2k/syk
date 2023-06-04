import {authApi} from "./authApi";

const path = '/api/company';

export const CompanyModel = {
    /**
     * Api get list company
     * @param params
     * @returns {Promise<*|undefined>}
     */
    list(params = {
        page: 1,
        searchKey: null,
        agencyId: null,
        listType: null
    }) {
        return authApi.post(`${path}/list`, params);
    },

    /**
     * Api get detail company
     * @param id
     * @returns {Promise<*|undefined>}
     */
    detail(id) {
        return authApi.get(`${path}/detail/${id}`, null);
    },

    /**
     * Api create new company
     * @param params
     * @returns {Promise<*>}
     */
    create(params) {
        return authApi.post(`${path}/create`, params);
    },

    /**
     * Api update company
     * @param params
     * @param id
     * @returns {Promise<*>}
     */
    update(params, id) {
        return authApi.post(`${path}/update/${id}`, params);
    },

    /**
     * API delete company
     * @param id
     * @returns {Promise<*>}
     */
    deleteCompany(id) {
        return authApi.delete(`${path}/delete-company/${id}`)
    },

    /**
     *
     * @param key
     * @returns {*}
     */
    getBusinessType(key) {
        const business_type = {
            10: '特定事業者',
            21: '特定連鎖化事業者_一般企業',
            22: '特定連鎖化事業者_福祉施設',
            23: '特定連鎖化事業者_学校',
            24: '特定連鎖化事業者_その他',
        }

        return business_type[key];
    },

    /**
     * api get all names
     * @returns {Promise<*|undefined>}
     */
    getAllCompanyNames() {
        return authApi.get(`${path}/all-company-name`);
    },

    /**
     *
     * @param agency_id
     * @returns {Promise<*|undefined>}
     */
    getCompanyByAgency(agency_id) {
        return authApi.get(`${path}/get-companies-by-agency/${agency_id}`)
    }
}
