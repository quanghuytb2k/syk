<template>
    <div class="d-flex justify-content-between">
        <div class="screen-title">エネルギー契約詳細</div>
    </div>
    <div class="content">
        <a-form :label-col="labelCol">
            <a-row :gutter="24">
                <a-col class="px-3" :span="12">
                    <a-col :span="24">
                        <a-form-item
                            label="エネルギー種類"
                            :required="true">
                            <a-select
                                placeholder="エネルギー種類"
                                v-model:value="energyContract.energy_type_id"
                                :disabled="isLoading"
                                :fieldNames="{label: 'name', value: 'id'}"
                                :options="energyTypes"
                                :class="[isInvalid('energy_type_id') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('energy_type_id')">
                                {{ invalidMessages('energy_type_id')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24" v-if="this.$store.state.role.role === 'admin'">
                        <a-form-item
                            label="代理店"
                            :required="true">
                            <a-select
                                placeholder="代理店"
                                v-model:value="energyContract.agency_id"
                                :disabled="isLoading"
                                :fieldNames="{label: 'name', value: 'id'}"
                                :options="agencies"
                                :class="[isInvalid('agency_id') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('agency_id')">
                                {{ invalidMessages('agency_id')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24" v-if="!this.$store.state.role.role.includes('company') && !this.$store.state.role.role.includes('facility')">
                        <a-form-item
                            label="企業"
                            :required="true">
                            <a-select
                                placeholder="企業"
                                v-model:value="energyContract.company_id"
                                :disabled="isLoading"
                                :fieldNames="{label: 'name', value: 'id'}"
                                :options="companies"
                                :class="[isInvalid('company_id') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('company_id')">
                                {{ invalidMessages('company_id')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24" v-if="!this.$store.state.role.role.includes('facility')">
                        <a-form-item
                            label="施設">
                            <a-select
                                placeholder="施設"
                                v-model:value="energyContract.facility_id"
                                :disabled="isLoading"
                                :fieldNames="{label: 'name', value: 'id'}"
                                :options="facilities"
                                :class="[isInvalid('facility_id') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('facility_id')">
                                {{ invalidMessages('facility_id')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="建屋">
                            <a-select
                                placeholder="建屋"
                                v-model:value="energyContract.building_id"
                                :disabled="isLoading"
                                :fieldNames="{label: 'name', value: 'id'}"
                                :options="buildings"
                                :class="[isInvalid('building_id') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('building_id')">
                                {{ invalidMessages('building_id')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item label="メモ">
                            <a-textarea v-model:value="energyContract.memo" :disabled="isLoading"/>
                        </a-form-item>
                    </a-col>
                </a-col>

                <a-col class="px-3" :span="12">
                    <a-col :span="24">
                        <a-form-item
                            label="契約先"
                            :required="true">
                            <a-input
                                v-model:value="energyContract.contract_company_name"
                                placeholder="契約先"
                                :disabled="isLoading"
                                :class="[isInvalid('contract_company_name') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('contract_company_name')">
                                {{ invalidMessages('contract_company_name')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="CO2変換変数"
                            :required="true">
                            <a-input
                                placeholder="CO2変換変数"
                                v-model:value="energyContract.co2_convert_coefficient"
                                :disabled="isLoading"
                                :class="[isInvalid('co2_convert_coefficient') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('co2_convert_coefficient')">
                                {{ invalidMessages('co2_convert_coefficient')[0] }}
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
                                <a-button :disabled="isLoading" type="link" primary @click="downloadFile(item.path)"><PaperClipOutlined class="attack-file"/> {{item.name}}</a-button>
                                <a-button :disabled="isLoading" class="delete-file-btn position-absolute end-0 d-none" @click="deleteFile(item.path)" type="link" primary><delete-outlined/></a-button>
                            </li>
                        </div>
                    </a-col>
                </a-col>
            </a-row>

            <div class="text-center">
                <a-button class="mx-2" :loading="isLoading" @click="$router.push({ name: 'energy_contract.list' })" danger>キャンセル</a-button>
                <a-button class="mx-2" type="primary" :loading="isLoading" danger>削除</a-button>
                <a-button class="mx-2" type="primary" :loading="isLoading" @click="update">更新</a-button>
            </div>

        </a-form>
    </div>
</template>

<script>
import {
    UploadOutlined,
    PaperClipOutlined,
    DeleteOutlined
} from '@ant-design/icons-vue'
import {EnergyContractModel} from "@/model/energyContract";
import {CompanyModel} from "@/model/company";
import {FacilityModel} from "@/model/facility";
import {BuildingModel} from "@/model/building";
import {agency} from "@/model/agency";
import {StorageModel} from "@/model/storage";
import { message } from 'ant-design-vue';

const defaultSelectOption = {
    name: '全て',
    id: null
}
export default {
    components: {
        UploadOutlined,
        PaperClipOutlined,
        DeleteOutlined
    },
    data() {
        return {
            isLoading: true,
            labelCol: {span: 5},
            energyContract: {},
            agencies: [],
            energyTypes: [],
            companies: [],
            facilities: [],
            buildings: [],
            fileList: []
        }
    },
    async mounted() {
        this.energyTypes = [defaultSelectOption, ...await EnergyContractModel.getListEnergyType()]
        let user = await this.$store.dispatch('auth/me')

        if (this.$store.state.role.role === 'admin') {
            this.agencies = await agency.getAllAgencyNames()
        }

        if (!user.agency_id) {
            this.companies = [defaultSelectOption, ...await CompanyModel.getAllCompanyNames()]
        } else {
            this.companies = [defaultSelectOption, ...await CompanyModel.getCompanyByAgency(user.agency_id)]
        }

        if (user.agency_id || user.company_id) {
            this.facilities = [defaultSelectOption, ...await FacilityModel.getFacilityByParent(user.agency_id, user.company_id)]
        } else {
            this.facilities = [defaultSelectOption, ...await FacilityModel.getAllFacilityNames()]
        }

        this.buildings = [defaultSelectOption, ...await BuildingModel.getListBuilding({
            agency_id: user.agency_id,
            company_id: user.company_id,
            facility_id: user.facility_id
        })]

        this.getDetail()
    },
    methods: {
        getDetail()
        {
            let contractId = this.$router.currentRoute.value.params.contractId
            EnergyContractModel.detail(contractId)
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

                    if (resData && resData.success === false)
                    {
                        message.error(resData.message)
                        this.$router.push({name: 'energy_contract.list'})
                    }else {
                        this.energyContract = resData.data
                    }
                    this.isLoading = false
                })
        },

        update()
        {
            this.clearError()
            this.isLoading = true
            EnergyContractModel.update(this.energyContract.id, this.energyContract)
                .then(res => {
                    if (res?.response?.status === 422) {
                        message.error('Update Energy Contract Fail!')
                        this.tryGetErrorResponse(res)
                    } else {
                        message.success(res.message)
                        this.$router.push({name: 'energy_contract.list'})
                    }
                    this.isLoading = false
                })
        },

        async downloadFile(link) {
            let param = {
                path: link,
            }
            EnergyContractModel.downloadFile(this.energyContract.id, param)
                .then(resData => {
                    if (resData.status) {
                        let element = document.createElement('a');
                        element.setAttribute('href', resData.data.url);
                        element.setAttribute('download', name);

                        element.style.display = 'none';
                        document.body.appendChild(element);

                        element.click();

                        document.body.removeChild(element);
                    } else {
                        message.error(resData.message)
                    }
                })
        },

        uploadFile(e) {
            this.isLoading = true
            let energyContractId = this.energyContract.id
            let params = {
                id: energyContractId,
            }
            let file = e.target.files;
            EnergyContractModel.uploadFile(params, file).then(res => {
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
            let energyContractId = this.energyContract.id
            let param = {
                path: path
            }
            EnergyContractModel.deleteFile(energyContractId, param)
                .then(res => {
                    if (res.status) {
                        this.getDetail()
                        message.success(res.message)
                    } else {
                        message.error(res.message)
                    }
                })
        },
    }
}
</script>

<style scoped lang="scss">
.list-item>li:hover {
    .delete-file-btn {
        display: inline-block!important;
    }
}
</style>
