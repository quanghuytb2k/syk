<template>
    <div class="d-flex justify-content-between">
        <div class="screen-title">図面詳細</div>
    </div>
    <div class="content">
        <a-form :label-col="labelCol">
            <a-row :gutter="24">
                <a-col class="px-3" :span="12">
                    <a-col :span="24">
                        <a-form-item
                            label="施設名"
                            :required="true">
                            <a-select
                                v-model:value="map.facility_id"
                                :fieldNames="{label: 'name', value: 'id'}"
                                :options="listFacility"
                                :disabled="isLoading"
                                :class="[isInvalid('facility_id') ? 'border-danger' : '']"
                            />
                            <span class="text-danger" v-if="isInvalid('facility_id')">
                            {{ invalidMessages('facility_id')[0] }}
                        </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            :required="true"
                            label="図面区分"
                        >
                            <a-select
                                v-model:value="map.drawing_type_id"
                                :fieldNames="{label: 'name', value: 'id'}"
                                :options="listDrawing"
                                :disabled="isLoading"
                                :class="[isInvalid('drawing_type_id') ? 'border-danger' : '']"
                            />
                            <span class="text-danger" v-if="isInvalid('drawing_type_id')">
                            {{ invalidMessages('drawing_type_id')[0] }}
                        </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            :required="true"
                            label="図面番号"
                        >
                            <a-input
                                :type="'number'" :min="1"
                                v-model:value="map.code"
                                :disabled="isLoading"
                            />
                            <span class="text-danger" v-if="isInvalid('code')">
                            {{ invalidMessages('code')[0] }}
                        </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="保管場所">
                            <a-input
                                v-model:value="map.storage_location"
                                :disabled="isLoading"
                            />
                            <span class="text-danger" v-if="isInvalid('storage_location')">
                            {{ invalidMessages('storage_location')[0] }}
                        </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="作成日">
                            <a-date-picker
                                :disabled="isLoading"
                                :value-format="dateFormat"
                                v-model:value="map.created_at"
                            />
                            <span class="text-danger" v-if="isInvalid('created_at')">
                            {{ invalidMessages('created_at')[0] }}
                        </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            :required="true"
                            label="作成者">
                            <a-input
                                v-model:value="map.creator"
                                :disabled="isLoading"
                            />
                            <span class="text-danger" v-if="isInvalid('creator')">
                            {{ invalidMessages('creator')[0] }}
                        </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item label="摘要">
                            <a-textarea
                                v-model:value="map.note"
                                :disabled="isLoading"
                            />
                            <span class="text-danger" v-if="isInvalid('note')">
                            {{ invalidMessages('note')[0] }}
                        </span>
                        </a-form-item>
                    </a-col>
                </a-col>

                <a-col class="px-3" :span="12">
                    <a-col :span="24">
                        <a-form-item
                            label="ファイル添付"
                            :required="true"
                        >
                            <input type="file" ref="upload_file" @change="uploadFile" hidden="hidden"
                                   multiple="multiple"/>
                            <a-button :disabled="isLoading" @click="$refs.upload_file.click()">
                                <UploadOutlined/>
                                Upload
                            </a-button>
                        </a-form-item>
                        <div class="offset-2 ps-2em list-item">
                            <li class="position-relative" v-for="(item, index) in listFile">
                                <a-button :disabled="isLoading" type="link" primary
                                          @click="downloadFile(item.path)">
                                    <PaperClipOutlined class="attack-file"/>
                                    {{ item.name }}
                                </a-button>
                                <a-button :disabled="isLoading" class="delete-file-btn position-absolute end-0 d-none"
                                          @click="deleteFile(item.path)" type="link" primary>
                                    <delete-outlined/>
                                </a-button>
                            </li>
                        </div>
                        <span class="text-danger" v-if="isInvalid('files')">
                            {{ invalidMessages('files')[0] }}
                        </span>
                    </a-col>
                </a-col>
            </a-row>

            <div class="text-center">
                <a-button class="mx-2" :loading="isLoading" @click="$router.push({ name: 'map.list' })"
                          danger>キャンセル
                </a-button>
                <a-button class="mx-2" type="primary" :loading="isLoading" @click="this.visible = true" danger>削除
                </a-button>
                <a-button class="mx-2" type="primary" :loading="isLoading" @click="update">更新</a-button>
            </div>

        </a-form>

        <a-modal
            v-model:visible="visible"
            title="操作確認"
            ok-text="はい"
            cancel-text="いいえ"
            @ok="deleteMap"
            @cancel="this.visible = false"
            :bodyStyle="{ borderRadius: '5px' }"
            :width="400"
            :okButtonProps="{ type: 'danger' }"
        >
            <p>
                <ExclamationCircleOutlined class="larger-icon"/>
                削除してもよろしいでしょうか?
            </p>
        </a-modal>
    </div>
</template>

<script>
import {DeleteOutlined, ExclamationCircleOutlined, PaperClipOutlined, UploadOutlined} from "@ant-design/icons-vue";
import {message} from "ant-design-vue";
import {FacilityModel} from "@/model/facility";
import mapApi from '@/model/map';
import {Confirm} from "notiflix";

export default {
    components: {
        ExclamationCircleOutlined,
        DeleteOutlined,
        UploadOutlined,
        PaperClipOutlined,
    },
    data() {
        return {
            files: null,
            isLoading: true,
            visible: false,
            labelCol: {span: 5},
            listFile: [],
            listFacility: [],
            listDrawing: [],
            map: {},
            dateFormat: 'YYYY/MM/DD',
        }
    },
    methods: {
        update() {
            this.clearError()
            this.isLoading = true
            mapApi.update(this.map.id, this.map).then(res => {
                if (res?.response?.status === 422) {
                    message.error('Update Map Fail!')
                    this.tryGetErrorResponse(res)
                } else {
                    message.success(res.message)
                    this.$router.push({name: 'map.list'})
                }
                this.isLoading = false
            })
        },

        async downloadFile(link) {
            let mapId = this.$router.currentRoute.value.params.mapId
            let param = {
                path: link,
            }
            mapApi.downloadFile(mapId, param)
                .then(res => {
                    if (res.status) {
                        let element = document.createElement('a');
                        element.setAttribute('href', res.data.url);
                        element.setAttribute('download', name);
                        element.style.display = 'none';
                        document.body.appendChild(element);
                        element.click();
                        document.body.removeChild(element);
                    } else {
                        message.error(res.message)
                    }
                })
        },

        getDetail() {
            let mapId = this.$router.currentRoute.value.params.mapId
            mapApi.detail(mapId).then(res => {
                let fileList = []
                if (res?.data?.files) {
                    let files = res?.data?.files
                    files.map(val => {
                        fileList.push({
                            name: val.name,
                            path: val.path
                        })
                    })
                }
                this.listFile = fileList

                if (res && res.success === false) {
                    message.error(res.message)
                    this.$router.push({name: 'map.list'})
                } else {
                    this.map = res.data
                }
                this.isLoading = false
            })
        },

        uploadFile(e) {
            this.isLoading = true
            let mapId = this.$router.currentRoute.value.params.mapId
            let params = {
                id: mapId,
            }
            let files = e.target.files
            mapApi.uploadFile(params, files)
                .then(res => {
                    if (res.status) {
                        this.getDetail()
                        message.success(res.message)
                    } else {
                        message.error(res.message)
                    }
                })
        },

        deleteFile(path) {
            this.isLoading = true
            let mapId = this.$router.currentRoute.value.params.mapId
            let param = {
                path: path
            }
            mapApi.deleteFile(mapId, param)
                .then(res => {
                    if (res.status) {
                        this.getDetail()
                        message.success(res.message)
                    } else {
                        message.error(res.message)
                    }
                })
        },

        deleteMap() {
            this.isLoading = true
            mapApi.deleteMap(this.map.id)
                .then(res => {
                    if (res && res.status === true) {
                        message.success('成功に削除されました。')
                        this.$router.push({name: 'map.list'})
                    } else {
                        message.error(res.message)
                    }
                    this.isLoading = false
                })
        },
    },
    async mounted() {
        this.listDrawing = [defaultSelectOption, ...await mapApi.listDrawingType()]
        let user = await this.$store.dispatch('auth/me')

        if (user.agency_id || user.company_id) {
            this.listFacility = [defaultSelectOption, ...await FacilityModel.getFacilityByParent(user.agency_id, user.company_id)]
        } else {
            this.listFacility = [defaultSelectOption, ...await FacilityModel.getAllFacilityNames()]
        }


        this.getDetail()
    }
    ,

}
</script>

<style scoped lang="scss">
.list-item > li:hover {
    .delete-file-btn {
        display: inline-block !important;
    }
}
</style>
