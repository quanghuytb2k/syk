import {authApi} from "./authApi";

const path = '/api/alarm'

export const AlarmModel = {
    /**
     *
     * @param params
     * @returns {Promise<*|undefined>}
     */
    list(params = {
        page: 1,
        time_start: null,
        time_end: null,
    }) {
        return authApi.post(`${path}/alarm-list`, params)
    },
}
