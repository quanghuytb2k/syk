import axios from '@/http-common'
import store from "@/stores/_loader";
import routers from "@/routes";
export const authApi = {
    /**
     *
     * @param url
     * @param params
     * @returns {Promise<*>}
     */
    async get(url, params = null) {
        let option = this.getOptions()
        let query = ``
        if (params !== null) {
            Object.entries(params).forEach(entry => {
                const [key, val] = entry
                if (val && val !== '')
                    query += `${key}=${val}&`
            })
        }

        let resData = await axios.get(`${url}?${query}`, option).catch(errors => {
            return this.customHandleErrors(errors)
        })

        if (resData?.status === 200)
            return resData.data.data
    },

    /**
     *
     * @param url
     * @param params
     * @param check
     * @returns {Promise<Result|any>}
     */
    async post(url, params, check = true) {
        let option = this.getOptions()
        if (params) {
            Object.entries(params).forEach(entry => {
                const [key, val] = entry
                if ((typeof val != "boolean" && typeof val != "number" && (!val || val === '')) && check)
                    delete params[key]
            })
        }

        let resData = await axios.post(url, params, option).catch(errors => this.customHandleErrors(errors))

        if (resData?.status === 200)
            return resData.data.data
        else
            return resData
    },

    /**
     *
     * @param url
     * @returns {Promise<*>}
     */
    async delete(url) {
        let option = this.getOptions()
        let resData = await axios.delete(url, option).catch(errors => this.customHandleErrors(errors))

        if (resData?.status === 200)
            return resData.data.data
        else
            return resData
    },

    /**
     *
     * @param url
     * @param params
     * @param files
     * @returns {Promise<Result|any>}
     */
    async postForm(url, params, files) {
        let user = JSON.parse(localStorage.getItem('user'))
        let formData = new FormData();
        if (params !== null) {
            Object.entries(params).forEach(entry => {
                const [key, val] = entry
                if (val)
                    formData.append(key, val)
            })
        }
        if (files) {
            for( let i = 0; i < files.length; i++ ){
                let file = files[i];

                formData.append('files[' + i + ']', file);
            }
        }

        let resData = await axios.post(url, formData, {
            headers: {
                "Authorization" : `Bearer ${user?.access_token}`,
                'Content-Type' : 'multipart/form-data'
            }
        }).catch(errors => this.customHandleErrors(errors))

        if (resData?.status === 200)
            return resData.data.data
        else
            return resData
    },

    /**
     *
     * @returns {{headers: {Authorization: string}}}
     */
    getOptions() {
        let user = JSON.parse(localStorage.getItem('user'))

        return {
            headers: {
                "Authorization" : `Bearer ${user?.access_token}`
            }
        }
    },

    /**
     *
     * @param errors
     * @returns {*}
     */
    customHandleErrors(errors) {
        if (errors?.response?.status === 401) {
            store.commit('auth/removeUser')
            routers.push({name: 'login'})
        } else {
            return errors
        }
    }
}
