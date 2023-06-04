<template>
    <div class="d-flex justify-content-between">
        <div class="screen-title">使用状況詳細</div>
    </div>
    <div class="content">
        <a-form :label-col="labelCol">
            <a-row :gutter="24">
                <a-col class="px-3" :span="12">

                    <a-col :span="24">
                        <a-form-item
                            label="エネルギー種類"
                            :rules="[{ required:true}]">
                            <a-select
                                :open="false"
                                placeholder="エネルギー種類"
                                v-model:value="energy_type_id"
                                :loading="isLoading"
                                :fieldNames="{label: 'name', value: 'id'}"
                                :options="energyTypes"/>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24" v-if="!this.$store.state.role.role.includes('company') && !this.$store.state.role.role.includes('facility')">
                        <a-form-item
                            label="企業"
                            :rules="[{ required:true}]">
                            <a-select
                                placeholder="企業"
                                :options="companies"
                                :loading="isLoading"
                                :fieldNames="{label: 'name', value: 'id'}"
                                :open="false"
                                v-model:value="usage.company_id"/>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24" v-if="!this.$store.state.role.role.includes('facility')">
                        <a-form-item
                            label="施設"
                            :rules="[{ required:true}]">
                            <a-select
                                placeholder="施設"
                                :options="facilities"
                                :loading="isLoading"
                                :fieldNames="{label: 'name', value: 'id'}"
                                :open="false"
                                v-model:value="usage.facility_id"/>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="建屋"
                            :rules="[{ required:true}]">
                            <a-select
                                placeholder="建屋"
                                :options="buildings"
                                :loading="isLoading"
                                :fieldNames="{label: 'name', value: 'id'}"
                                v-model:value="usage.building_id"
                                :open="false"/>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="C02変換変数">
                            <a-input :readonly="true" v-model:value="usage.amount"/>
                        </a-form-item>
                    </a-col>


                </a-col>

                <a-col class="px-3" :span="12">

                    <a-col :span="24">
                        <a-form-item
                            label="使用期間"
                            :rules="[{ required:true}]">
                            <a-date-picker
                                :format="dateFormat"
                                :loading="isLoading"
                                v-model:value="usage.usage_from_date"
                                class="me-4 w-25"/>
                            ~
                            <a-date-picker
                                :format="dateFormat"
                                :loading="isLoading"
                                v-model:value="usage.usage_to_date"
                                class="ms-4 w-25"
                                :class="[isInvalid('usage_to_date') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('usage_to_date')">
                                {{ invalidMessages('usage_to_date')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item label="請求日">
                            <a-date-picker
                                placeholder="請求日"
                                class="w-25"
                                :loading="isLoading"
                                :format="dateFormat"
                                v-model:value="usage.billing_date"
                                :class="[isInvalid('billing_date') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('billing_date')">
                                {{ invalidMessages('billing_date')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item label="支払い日">
                            <a-date-picker
                                placeholder="支払い日"
                                class="w-25"
                                :loading="isLoading"
                                :format="dateFormat"
                                v-model:value="usage.invoice_date"
                                :class="[isInvalid('invoice_date') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('invoice_date')">
                                {{ invalidMessages('invoice_date')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="使用量"
                            :rules="[{ required:true}]">
                            <a-input
                                class="w-25"
                                placeholder="使用量"
                                :loading="isLoading"
                                @change="handleChangeUsedAmount"
                                v-model:value="usedAmount"
                                :class="[isInvalid('converted_co2_amount') ? 'border-danger' : '']"/>
                            kW
                            <span class="text-danger" v-if="isInvalid('converted_co2_amount')">
                                {{ invalidMessages('converted_co2_amount')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="総額:"
                            :rules="[{ required:true}]">
                            <a-input
                                class="w-25"
                                placeholder="総額:"
                                :loading="isLoading"
                                v-model:value="usage.money"
                                :class="[isInvalid('money') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('money')">
                                {{ invalidMessages('money')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="ファイル添付">
                            <input type="file" ref="upload_file" @change="uploadFile" hidden="hidden"
                                   multiple="multiple"/>
                            <a-button :disabled="isLoading" class="w-25" @click="$refs.upload_file.click()">
                                <UploadOutlined/>
                                Upload
                            </a-button>
                        </a-form-item>
                        <div class="offset-2 ps-2em list-item">
                            <li class="position-relative" v-for="(item, index) in fileList">
                                <a-button :disabled="isLoading" type="link" primary
                                          @click="downloadFile(item.path, item.name)">
                                    <PaperClipOutlined class="attack-file"/>
                                    {{ item.name }}
                                </a-button>
                                <a-button :disabled="isLoading" class="delete-file-btn position-absolute end-0 d-none"
                                          @click="deleteFile(item.path)" type="link" primary>
                                    <delete-outlined/>
                                </a-button>
                            </li>
                        </div>
                    </a-col>

                </a-col>
            </a-row>

            <div class="text-center">
                <a-button class="mx-2" :loading="isLoading" @click="$router.push({ name: 'usage.list' })" danger>キャンセル
                </a-button>
                <a-button class="mx-2" type="primary" :loading="isLoading" @click="this.visible = true" danger>削除
                </a-button>
                <a-button class="mx-2" type="primary" :loading="isLoading" @click="updateUsage">更新</a-button>
            </div>

        </a-form>

        <a-modal
            v-model:visible="visible"
            title="操作確認"
            ok-text="はい"
            cancel-text="いいえ"
            @ok="deleteUsage"
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
import {
    UploadOutlined,
    PaperClipOutlined,
    DeleteOutlined, ExclamationCircleOutlined
} from '@ant-design/icons-vue'
import dayjs from "dayjs";
import {CompanyModel} from "@/model/company"
import {FacilityModel} from "@/model/facility"
import {BuildingModel} from "@/model/building"
import {EnergyContractModel} from "@/model/energyContract";
import {UsageModel} from "@/model/usage";
import {message} from "ant-design-vue";
import {StorageModel} from "@/model/storage";

const defaultSelectOption = {
    name: '全て',
    id: null
}

export default {
    components: {
        ExclamationCircleOutlined,
        UploadOutlined,
        PaperClipOutlined,
        DeleteOutlined
    },
    data() {
        return {
            isLoading: true,
            visible: false,
            labelCol: {span: 5},
            dateFormat: 'YYYY/MM/DD',
            energyTypes: [],
            companies: [],
            facilities: [],
            buildings: [],
            energy_type_id: null,
            usage: {},
            fileList: [],
            usedAmount: 0
        }
    },
    async mounted() {
        let user = await this.$store.dispatch('auth/me');

        [
            this.energyTypes,
            this.companies,
            this.facilities,
            this.buildings
        ] = await Promise.all([
            [defaultSelectOption, ...await EnergyContractModel.getListEnergyType()],
            [defaultSelectOption, ...await CompanyModel.getCompanyByAgency(user.agency_id)],
            [defaultSelectOption, ...await FacilityModel.getFacilityByParent(user.agency_id, user.company_id)],
            [defaultSelectOption, ...await BuildingModel.getListBuilding({
                agency_id: user.agency_id,
                company_id: user.company_id,
                facility_id: user.facility_id
            })]
        ])

        this.getDetail()
    },
    methods: {
        updateUsage() {
            this.clearError()
            this.isLoading = true
            UsageModel.update(this.usage.id, this.usage)
                .then(res => {
                    if (res?.response?.status === 422) {
                        message.error('Update Usage Fail!')
                        this.tryGetErrorResponse(res)
                    } else {
                        message.success(res.message)
                        this.$router.push({name: 'usage.list'})
                    }
                    this.isLoading = false
                })
        },

        async getDetail() {
            let user = await this.$store.dispatch('auth/me')
            let usageId = this.$router.currentRoute.value.params.usageId
            UsageModel.getDetail(usageId)
                .then(resData => {
                    let fileList = []
                    if (resData?.data?.files) {
                        let files = resData?.data?.files
                        files.map(val => {
                            fileList.push({
                                name: val.name,
                                path: val.path
                            })
                        })
                    }
                    this.fileList = fileList

                    if (resData && resData.success === false) {
                        message.error(resData.message)
                        this.$router.push({name: 'usage.list'})
                    } else {
                        if (resData.data.agency_id === user.agency_id || resData.data.company_id === user.company_id) {
                            this.usage = resData.data
                            this.energy_type_id = resData.data.contract?.energy_type?.id
                            this.usage.usage_from_date = dayjs(`${this.usage.usage_from_date}`)
                            this.usage.usage_to_date = dayjs(`${this.usage.usage_to_date}`)
                            this.usage.billing_date = dayjs(`${this.usage.billing_date}`)
                            this.usage.invoice_date = dayjs(`${this.usage.invoice_date}`)
                            this.usedAmount = this.usage.converted_co2_amount / this.usage.amount
                        } else {
                            message.error(`you don't have permission to access`)
                            this.$router.push({name: 'usage.list'})
                        }
                    }
                    this.isLoading = false
                })
        },

        handleChangeUsedAmount(event) {
            this.usage.converted_co2_amount = event.target.value * this.usage.amount
        },
        async downloadFile(link) {
            let usageId = this.$router.currentRoute.value.params.usageId
            let param = {
                path: link,
            }
            UsageModel.downloadFile(usageId, param)
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

        uploadFile(e) {
            this.isLoading = true
            let usageId = this.$router.currentRoute.value.params.usageId
            let params = {
                id: usageId,
            }
            let files = e.target.files
            UsageModel.uploadFile(params, files)
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
            let usageId = this.$router.currentRoute.value.params.usageId
            let param = {
                path: path
            }
            UsageModel.deleteFile(usageId, param)
                .then(res => {
                    if (res.status) {
                        this.getDetail()
                        message.success(res.message)
                    } else {
                        message.error(res.message)
                    }
                })
        },

        deleteUsage() {
            this.isLoading = true
            UsageModel.deleteUsage(this.$router.currentRoute.value.params.usageId)
                .then(res => {
                    if (res && res.status === true) {
                        message.success('成功に削除されました。')
                        this.$router.push({name: 'usage.list'})
                    } else {
                        message.error('に削除されました。')
                    }
                    this.isLoading = false
                })
        }
    }
}
</script>

<style scoped lang="scss">
.list-item > li:hover {
    .delete-file-btn {
        display: inline-block !important;
    }
}
</style>
