<template>
    <a-button type="primary" size="large" ghost @click="() => {visibleModal = true}" :loading="isLoading">
        新規登録
    </a-button>
    <a-modal v-model:visible="visibleModal" width="800px" title="メンテ履歴更新" centered>
        <template #footer>
            <a-button key="back" :loading="isLoading" @click="() => {visibleModal = false}">戻る</a-button>
            <a-button key="submit" type="primary" :loading="isLoading" @click="createMaintainHistory">登録</a-button>
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
                            <input type="file" ref="upload_file_create" @change="selectFile" hidden="hidden" multiple="multiple"/>
                            <a-button class="w-40" @click="$refs.upload_file_create.click()">
                                <UploadOutlined/>
                                Upload
                            </a-button>
                        </a-form-item>
                        <div class="offset-2 ps-2em">
                            <li v-for="(item, index) in listFile">
                                <PaperClipOutlined class="attack-file"/> {{item}}
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
import {PaperClipOutlined, UploadOutlined} from "@ant-design/icons-vue";
import dayjs from "dayjs";
import {EquipmentModel} from "@/model/equipment";
import {message} from "ant-design-vue";

export default {
    props: ['id', 'isLoading', 'getList', 'maintainCompanies'],
    components: {
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
            listFile: [],
            maintain_history: {
                equipment_id : this.id,
                date: dayjs(),
                money: null,
                maintenance_person_name: null,
                content: null,
                files: [],
                next_maintenance_date: dayjs(),
                next_alarm_date: dayjs(),
                maintain_company_id: null
            }
        }
    },
    watch: {
        isLoading: function (newVal, oldVal) {
            this.isLoading = newVal
        }
    },
    methods: {
        createMaintainHistory()
        {
            this.maintain_history.date = dayjs(this.maintain_history.date).format('YYYY-MM-DD')
            this.maintain_history.next_maintenance_date = dayjs(this.maintain_history.next_maintenance_date).format('YYYY-MM-DD')
            this.maintain_history.next_alarm_date = dayjs(this.maintain_history.next_alarm_date).format('YYYY-MM-DD')
            EquipmentModel.createMaintainHistory(this.maintain_history, this.files)
                .then(res => {
                    if (res?.response?.status === 422) {
                        this.tryGetErrorResponse(res)
                    } else {
                        if (res.success) {
                            message.success(res.message)
                            this.getList(1)
                        } else {
                            message.error(res.message)
                        }
                        this.visibleModal = false;
                    }
                })
        },

        selectFile(e)
        {
            this.files = e.target.files
            this.listFile = []

            for( let i = 0; i < this.files.length; i++ ) {
                let file = this.files[i];
                this.listFile.push(file?.name)
            }
        }
    }
}
</script>
