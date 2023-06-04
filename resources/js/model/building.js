import {authApi} from "./authApi";

const path = '/api/building'

export const BuildingModel = {
    /**
     * List Building
     * @param params
     * @returns {Promise<*|undefined>}
     */
    list(params = {
        page: 1,
        searchKey: null,
        directProfit: null,
        agencyId: null,
        companyId: null,
        facilityId: null
    })
    {
        return authApi.post(`${path}/list`, params)
    },

    /**
     * Api get list building type
     * @returns {Promise<*|undefined>}
     */
    listBuildingType()
    {
        return authApi.get(`${path}/list-building-type`)
    },

    /**
     * Api get list construction
     * @returns {Promise<*|undefined>}
     */
    listConstruction()
    {
        return authApi.get(`${path}/list-construction`)
    },

    /**
     * Create building
     * @param params
     * @returns {Promise<*>}
     */
    create(params)
    {
        return authApi.post(`${path}/create`, params)
    },

    /**
     * Update building
     * @param id
     * @param params
     * @returns {Promise<*>}
     */
    update(id, params)
    {
        return authApi.post(`${path}/update/${id}`, params)
    },

    /**
     * Api get detail building
     * @param id
     * @returns {Promise<*|undefined>}
     */
    detail(id)
    {
        return authApi.get(`${path}/detail/${id}`)
    },

    /**
     * API get list building
     * @returns {Promise<*|undefined>}
     */
    getListBuilding(params = null)
    {
        return authApi.post(`${path}/list-building`, params)
    },

    /**
     * API delete building
     * @param id
     * @returns {Promise<*>}
     */
    deleteBuilding(id)
    {
        return authApi.delete(`${path}/delete-building/${id}`)
    }
}
