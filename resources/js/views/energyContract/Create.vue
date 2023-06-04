<template>
    <div class="d-flex justify-content-between">
        <div class="screen-title">エネルギー契約登録</div>
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
                                v-model:value="energyContract.co2_convert_coefficient"
                                placeholder="CO2変換変数"
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
                            <input type="file" ref="upload_file" @change="selectFile" hidden="hidden" multiple="multiple"/>
                            <a-button :disabled="isLoading" @click="$refs.upload_file.click()">
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
            </a-row>

            <div class="text-center">
                <a-button class="mx-2" :loading="isLoading" @click="$router.push({ name: 'energy_contract.list' })" danger>キャンセル</a-button>
                <a-button class="mx-2" type="primary" :loading="isLoading" @click="create">登録</a-button>
            </div>

        </a-form>
    </div>
</template>

<script>
import {
    UploadOutlined,
    PaperClipOutlined
} from '@ant-design/icons-vue'
import {EnergyContractModel} from "@/model/energyContract";
import {CompanyModel} from "@/model/company";
import {FacilityModel} from "@/model/facility";
import {BuildingModel} from "@/model/building";
import {agency} from "@/model/agency";
import { message } from 'ant-design-vue';

const defaultSelectOption = {
    name: '全て',
    id: null
}
export default {
    components: {
        UploadOutlined,
        PaperClipOutlined
    },
    data() {
        return {
            isLoading: true,
            labelCol: {span: 5},
            energyContract: {
                energy_type_id: null,
                agency_id: null,
                company_id: null,
                facility_id: null,
                building_id: null,
                memo: null,
                contract_company_name: null,
                co2_convert_coefficient: null
            },
            files: null,
            agencies: [],
            energyTypes: [],
            companies: [],
            facilities: [],
            buildings: [],
            listFile: []
        }
    },
    async mounted() {
        this.energyTypes = [defaultSelectOption, ...await EnergyContractModel.getListEnergyType()]

        let user = await this.$store.dispatch('auth/me')
        if (this.$store.state.role.role === 'admin') {
            this.agencies = await agency.getAllAgencyNames()
        } else {
            this.energyContract.agency_id = user.agency_id
        }

        if (!this.$store.state.role.role.includes('company') && !this.$store.state.role.role.includes('facility')) {
            if (!user.agency_id) {
                this.companies = [defaultSelectOption, ...await CompanyModel.getAllCompanyNames()]
            } else {
                this.companies = [defaultSelectOption, ...await CompanyModel.getCompanyByAgency(user.agency_id)]
            }
        } else {
            this.energyContract.company_id = user.company_id
        }

        if (!this.$store.state.role.role.includes('facility')) {
            if (user.agency_id || user.company_id) {
                this.facilities = [defaultSelectOption, ...await FacilityModel.getFacilityByParent(user.agency_id, user.company_id)]
            } else {
                this.facilities = [defaultSelectOption, ...await FacilityModel.getAllFacilityNames()]
            }
        } else {
            this.energyContract.facility_id = user.facility_id
        }

        this.buildings = [defaultSelectOption, ...await BuildingModel.getListBuilding({
            agency_id: user.agency_id,
            company_id: user.company_id,
            facility_id: user.facility_id
        })]

        this.isLoading = false
    },
    methods: {
        create() {
            this.clearError()
            this.isLoading = true
            EnergyContractModel.create(this.energyContract, this.files)
                .then(res => {
                    if (res?.response?.status === 422) {
                        this.tryGetErrorResponse(res)
                    } else {
                        if (res.success) {
                            message.success(res.message)
                        } else {
                            message.error(res.message)
                        }
                        this.$router.push({name: 'energy_contract.list'})
                    }
                    this.isLoading = false
                })
        },

        selectFile(e) {
            this.files = e.target.files
            this.listFile = []

            for( let i = 0; i < this.files.length; i++ ){
                let file = this.files[i];
                this.listFile.push(file?.name)
            }
        }
    }
}
</script>

<style scoped>
.attack-file {
    color: #00000073;
    margin-bottom: 5px;
}
</style>
