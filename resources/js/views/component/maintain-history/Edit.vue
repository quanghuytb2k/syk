<template>
    <a-button type="link" size="large" ghost @click="() => {visibleModal = true}" :loading="isLoading">
        詳細
    </a-button>
    <a-modal v-model:visible="visibleModal" width="800px" title="メンテ履歴更新" centered>
        <template #footer>
            <a-button type="primary" :loading="isLoading" danger>削除</a-button>
            <a-button key="back" :loading="isLoading" @click="() => {visibleModal = false}">戻る</a-button>
            <a-button key="submit" type="primary" :loading="isLoading" @click="updateMaintainHistory">登録</a-button>
        </template>
        <a-form :label-col="labelCol">
            <a-row :glutter="24">
                <a-col class="px-3" :span="12">
                    <a-col :span="24">
                        <a-form-item
                            label="実施日"
                            :rules="[{ required:true}]">
                            <a-date-picker
                                placeholder="実施日"
                                :value-format="dateFormat"
                                v-model:value="maintain_history.date"
                                :class="[isInvalid('date') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('date')">
                                {{ invalidMessages('date')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="金額">
                            <a-input
                                placeholder="金額"
                                v-model:value="maintain_history.money"
                                :class="[isInvalid('money') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('money')">
                                {{ invalidMessages('money')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="次メンテ日">
                            <a-date-picker
                                placeholder="次メンテ日"
                                :value-format="dateFormat"
                                v-model:value="maintain_history.next_maintenance_date"
                                :class="[isInvalid('next_maintance_date') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('next_maintance_date')">
                                {{ invalidMessages('next_maintance_date')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="次アラーム日">
                            <a-date-picker
                                placeholder="次アラーム日"
                                :value-format="dateFormat"
                                v-model:value="maintain_history.next_alarm_date"
                                :class="[isInvalid('next_alarm_date') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('next_alarm_date')">
                                {{ invalidMessages('next_alarm_date')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="ファイル添付">
                            <input type="file" ref="upload_file" @change="uploadFile" hidden="hidden" multiple="multiple"/>
                            <a-button :disabled="isLoading" @click="$refs.upload_file.click()">
                                <UploadOutlined/>
                                Upload
                            </a-button>
                        </a-form-item>
                        <div class="offset-2 ps-2em list-item">
                            <li class="position-relative" v-for="(item, index) in fileList">
                                <a-button :disabled="isLoading" type="link" primary @click="downloadFile(item.path, item.name)"><PaperClipOutlined class="attack-file"/> {{item.name}}</a-button>
                                <a-button :disabled="isLoading" class="delete-file-btn position-absolute d-none" @click="deleteFile(index)" type="link" primary><delete-outlined/></a-button>
                            </li>
                        </div>
                    </a-col>
                </a-col>

                <a-col class="px-3" :span="12">
                    <a-col :span="24">
                        <a-form-item
                            label="メンテ会社名"
                            :rules="[{ required:true}]">
                            <a-select
                                placeholder="メンテ会社名"
                                :disabled="isLoading"
                                :fieldNames="{label: 'name', value: 'id'}"
                                :options="$props.maintainCompanies"
                                v-model:value="maintain_history.maintain_company_id"
                                :class="[isInvalid('maintain_company_id') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('maintain_company_id')">
                                {{ invalidMessages('maintain_company_id')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="工事分類">
                            <a-select
                                placeholder="工事分類"
                                :disabled="isLoading"
                                :fieldNames="{label: 'name', value: 'id'}"
                                :options="$props.maintainCompanies"
                                :class="[isInvalid('maintain_company_id') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('maintain_company_id')">
                                {{ invalidMessages('maintain_company_id')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="担当者">
                            <a-input
                                placeholder="担当者"
                                :disabled="isLoading"
                                v-model:value="maintain_history.maintenance_person_name"
                                :class="[isInvalid('maintenance_person_name') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('maintenance_person_name')">
                                {{ invalidMessages('maintenance_person_name')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item label="メンテ内容">
                            <a-textarea v-model:value="maintain_history.content"/>
                        </a-form-item>
                    </a-col>

                </a-col>
            </a-row>
        </a-form>
    </a-modal>
</template>
<script>
import {DeleteOutlined, PaperClipOutlined, UploadOutlined} from "@ant-design/icons-vue";
import dayjs from "dayjs";
import {message} from "ant-design-vue";
import {EquipmentModel} from "@/model/equipment";

export default {
    props: ['id', 'isLoading', 'getList', 'maintainCompanies', 'maintainHistory'],
    components: {
        DeleteOutlined,
        PaperClipOutlined,
        UploadOutlined
    },
    data() {
        return {
            labelCol: {span: 8},
            isLoading: this.isLoading,
            visibleModal: false,
            dateFormat: 'YYYY/MM/DD',
            files: null,
            fileList: [],
            maintain_history: {}
        }
    },
    mounted() {
        this.maintain_history = this.maintainHistory
        let fileList = []
        if (this.maintain_history.files) {
            let files = this.maintain_history.files
            files.map(val => {
                fileList.push({
                    name: val.name,
                    path: val.path
                })
            })
        }
        this.fileList = fileList
    },
    watch: {
        isLoading: function (newVal, oldVal) {
            this.isLoading = newVal
        }
    },
    methods: {
        getDetail() {
            EquipmentModel.getHistoryDetail(this.maintain_history.id)
                .then(res => {
                    this.maintain_history = res
                    let fileList = []
                    if (this.maintain_history.files) {
                        let files = this.maintain_history.files
                        files.map(val => {
                            fileList.push({
                                name: val.name,
                                path: val.path
                            })
                        })
                    }
                    this.fileList = fileList
                })
            this.isLoading = false
        },

        uploadFile(e)
        {
            this.isLoading = true
            let params = {id: this.maintain_history.id}
            let files = e.target.files
            EquipmentModel.uploadHistoryFiles(params, files)
                .then(res => {
                    if (res.success) {
                        this.getDetail()
                        message.success(res.message)
                    } else {
                        message.error(res.message)
                    }
                })
        },

        deleteFile(index)
        {
            EquipmentModel.deleteHistoryFile({
                id: this.maintain_history.id,
                index
            }).then(res => {
                if (res.success) {
                    this.getDetail()
                    message.success(res.message)
                } else {
                    message.error(res.message)
                }
            })
        },

        updateMaintainHistory()
        {
            EquipmentModel.updateMaintainHistory(this.maintain_history, this.maintain_history.id)
                .then(res => {
                    if (res?.response?.status === 422) {
                        this.tryGetErrorResponse(res)
                    } else {
                        if (res.success) {
                            message.success(res.message)
                        } else {
                            message.error(res.message)
                        }
                        this.visibleModal = false;
                    }
                })
        }
    }
}
</script>
<style scoped lang="scss">
.list-item>li:hover {
    .delete-file-btn {
        display: inline-block!important;
    }
}

.delete-file-btn {
    left: -10%;
    top: 0;
}
</style>
