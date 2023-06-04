import axios from "@/http-common"

const path = `/api/prefecture`

export const PrefectureModel = {

    /**
     * get all prefecture
     * @returns {*}
     */
    getAllPrefecture()
    {
        return axios.get(`${path}/all`)
    }
}
