<template>
    <div class="d-flex justify-content-between">
        <div class="screen-title">図面作成</div>
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
                                :options="listFacility"
                                :fieldNames="{label: 'name', value: 'id'}"
                                :disabled="isLoading"
                                v-model:value="map.facility_id"
                            />
                            <span class="text-danger" v-if="isInvalid('facility_id')">
                                {{ invalidMessages('facility_id')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="図面区分"
                            :required="true"
                        >
                            <a-select
                                v-model:value="map.drawing_type_id"
                                :options="listDrawing"
                                :fieldNames="{label: 'name', value: 'id'}"
                                :disabled="isLoading"
                            />
                            <span class="text-danger" v-if="isInvalid('drawing_type_id')">
                                {{ invalidMessages('drawing_type_id')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>
*
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
                                v-model:value="map.created_at"
                                :disabled="isLoading"
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
                                :disabled="isLoading"
                                v-model:value="map.note"
                            />
                        </a-form-item>
                        <span class="text-danger" v-if="isInvalid('note')">
                            {{ invalidMessages('note')[0] }}
                        </span>
                    </a-col>
                </a-col>

                <a-col class="px-3" :span="12">
                    <a-col :span="24">
                        <a-form-item
                            :required="true"
                            label="ファイル添付">
                            <input type="file" ref="upload_file" @change="selectFile" hidden="hidden"/>
                            <a-button :disabled="isLoading" @click="$refs.upload_file.click()">
                                <UploadOutlined/>
                                Upload
                            </a-button>
                        </a-form-item>
                        <div class="offset-2 ps-2em">
                            <li v-for="(item, index) in listFile">
                                <PaperClipOutlined class="attack-file"/>
                                {{ item }}
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
                <a-button class="mx-2" type="primary" :loading="isLoading" @click="create">登録</a-button>
            </div>

        </a-form>
    </div>
</template>

<script>
import {PaperClipOutlined, UploadOutlined} from "@ant-design/icons-vue";
import {FacilityModel} from "@/model/facility";
import mapApi from '@/model/map';
import {message} from "ant-design-vue";

const defaultSelectOption = {
    name: '全て',
    id: null
}
export default {
    components: {
        UploadOutlined,
        PaperClipOutlined,
    },
    data() {
        return {
            files: null,
            isLoading: false,
            labelCol: {span: 5},
            listFile: [],
            listDrawing: [],
            drawingType: null,
            listFacility: [],
            facility: null,
            map: {
                facility_id: null,
                drawing_type_id: null,
                code: null,
                storage_location: null,
                created_at: null,
                creator: null,
                note: null,
            }
        }
    },
    methods: {
        async getListDrawingType() {
            this.loading = true
            let user = await this.$store.dispatch('auth/me')

            if (user.agency_id || user.company_id) {
                this.listFacility = [defaultSelectOption, ...await FacilityModel.getFacilityByParent(user.agency_id, user.company_id)]
            } else {
                this.listFacility = [defaultSelectOption, ...await FacilityModel.getAllFacilityNames()]
            }
        },

        async getListFacility() {
            this.loading = true
            this.listDrawing = [defaultSelectOption, ...await mapApi.listDrawingType()]
        },
        selectFile(e) {
            this.files = e.target.files
            this.listFile = []

            for (let i = 0; i < this.files.length; i++) {
                let file = this.files[i];
                this.listFile.push(file?.name)
            }
        },

        create() {
            this.clearError()
            this.isLoading = true
            mapApi.create(this.map, this.files).then(res => {
                if (res?.response?.status === 422) {
                    this.tryGetErrorResponse(res)
                } else {
                    if (res.success) {
                        message.error('Create Map Fail!')
                        message.success(res.message)
                    } else {
                        message.error(res.message)
                    }
                    this.$router.push({name: 'map.list'})
                }
                this.isLoading = false
            })
        }
    },
    async mounted() {
        this.getListDrawingType();
        this.getListFacility();
    },

}
</script>
