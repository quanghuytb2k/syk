<template>
    <div class="d-flex justify-content-between">
        <div class="screen-title">使用状況登録</div>
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
                                placeholder="エネルギー種類"
                                v-model:value="energy_type_id"
                                :loading="initPageData"
                                :fieldNames="{label: 'name', value: 'id'}"
                                :options="energyTypes"
                                show-search
                                @change="handleChangeContractParams"
                                :filter-option="filterOption"/>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24" v-if="!this.$store.state.role.role.includes('company') && !this.$store.state.role.role.includes('facility')">
                        <a-form-item
                            label="企業"
                            :rules="[{ required:true}]">
                            <a-select
                                placeholder="企業"
                                :options="companies"
                                :loading="initPageData"
                                :fieldNames="{label: 'name', value: 'id'}"
                                v-model:value="usage.company_id"
                                show-search
                                @change="handleChangeContractParams"
                                :filter-option="filterOption"/>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24" v-if="!this.$store.state.role.role.includes('facility')">
                        <a-form-item
                            label="施設">
                            <a-select
                                placeholder="施設"
                                :options="facilities"
                                :loading="initPageData"
                                :fieldNames="{label: 'name', value: 'id'}"
                                v-model:value="usage.facility_id"
                                show-search
                                @change="handleChangeContractParams"
                                :filter-option="filterOption"/>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="建屋">
                            <a-select
                                placeholder="建屋"
                                :options="buildings"
                                :loading="initPageData"
                                :rules="[{ required:true}]"
                                :required="true"
                                :fieldNames="{label: 'name', value: 'id'}"
                                v-model:value="usage.building_id"
                                show-search
                                @change="handleChangeContractParams"
                                :filter-option="filterOption"/>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="C02変換変数">
                            <a-input :readonly="true" v-model:value="usage.amount"/>
                        </a-form-item>
                        <a-button type="primary" class="ms-2" @click="getContact" :loading="initPageData">契約情報から取得</a-button>
                    </a-col>

                </a-col>

                <a-col class="px-3" :span="12">

                    <a-col :span="24">
                        <a-form-item
                            label="使用期間"
                            :rules="[{ required:true}]">
                            <a-date-picker
                                :value-format="dateFormat"
                                class="me-4 w-40"
                                :disabled="checkHaveContract"
                                v-model:value="usage.usage_from_date"/>
                            ~
                            <a-date-picker
                                :value-format="dateFormat"
                                class="ms-4 w-40"
                                :disabled="checkHaveContract"
                                v-model:value="usage.usage_to_date"
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
                                class="w-40"
                                :disabled="checkHaveContract"
                                :value-format="dateFormat"
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
                                class="w-40"
                                :disabled="checkHaveContract"
                                :value-format="dateFormat"
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
                                class="w-40"
                                placeholder="使用量"
                                :disabled="checkHaveContract"
                                @change="handleChangeUsedAmount"
                                :class="[isInvalid('converted_co2_amount') ? 'border-danger' : '']"/> kW
                            <span class="text-danger" v-if="isInvalid('converted_co2_amount')">
                                {{ invalidMessages('converted_co2_amount')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="総額"
                            :rules="[{ required:true}]">
                            <a-input
                                class="w-40"
                                placeholder="総額"
                                :disabled="checkHaveContract"
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
                            <input type="file" ref="upload_file" @change="selectFile" hidden="hidden" multiple="multiple"/>
                            <a-button :disabled="checkHaveContract" class="w-40" @click="$refs.upload_file.click()">
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
                <a-button
                    class="mx-2"
                    :disabled="checkHaveContract"
                    :loading="initPageData"
                    @click="$router.push({ name: 'usage.list' })"
                    danger>
                    キャンセル
                </a-button>
                <a-button
                    class="mx-2"
                    type="primary"
                    :disabled="checkHaveContract"
                    :loading="initPageData"
                    @click="create">
                    登録
                </a-button>
            </div>

        </a-form>
    </div>
</template>

<script>
import {
    UploadOutlined,
    PaperClipOutlined
} from '@ant-design/icons-vue'
import dayjs from 'dayjs';
import {CompanyModel} from "@/model/company"
import {FacilityModel} from "@/model/facility"
import {BuildingModel} from "@/model/building"
import {EnergyContractModel} from "@/model/energyContract";
import {UsageModel} from "@/model/usage";
import { message } from 'ant-design-vue';

const currentDay = dayjs()
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
        const filterOption = (input, option) => {
            return option.name.toLowerCase().indexOf(input.toLowerCase()) >= 0;
        };
        return {
            filterOption,
            initPageData: true,
            checkHaveContract: true,
            files: null,
            listFile: [],
            labelCol: {span: 5},
            dateFormat: 'YYYY/MM/DD',
            usage: {
                company_id: null,
                facility_id: null,
                building_id: null,
                usage_from_date: currentDay,
                usage_to_date: currentDay,
                billing_date: currentDay,
                invoice_date: currentDay,
                amount: 0,
                money: null,
                converted_co2_amount: 0,
                energy_contract_id: null,
                agency_id: null
            },
            energyTypes: [],
            companies: [],
            facilities: [],
            buildings: [],
            energy_type_id: null
        }
    },
    async mounted() {
        this.energyTypes = [defaultSelectOption, ...await EnergyContractModel.getListEnergyType()]
        let user = await this.$store.dispatch('auth/me')

        this.usage.agency_id = user.agency_id

        if (!this.$store.state.role.role.includes('company') && !this.$store.state.role.role.includes('facility')) {
            if (!user.agency_id) {
                this.companies = [defaultSelectOption, ...await CompanyModel.getAllCompanyNames()]
            } else {
                this.companies = [defaultSelectOption, ...await CompanyModel.getCompanyByAgency(user.agency_id)]
            }
        } else {
            this.usage.company_id = user.company_id
        }

        if (!this.$store.state.role.role.includes('facility')) {
            if (user.agency_id || user.company_id) {
                this.facilities = [defaultSelectOption, ...await FacilityModel.getFacilityByParent(user.agency_id, user.company_id)]
            } else {
                this.facilities = [defaultSelectOption, ...await FacilityModel.getAllFacilityNames()]
            }
        } else {
            this.usage.facility_id = user.facility_id
        }

        this.buildings = [defaultSelectOption, ...await BuildingModel.getListBuilding({
            agency_id: user.agency_id,
            company_id: user.company_id,
            facility_id: user.facility_id
        })]

        this.initPageData = false
    },
    methods: {
        selectFile(e) {
            this.files = e.target.files
            this.listFile = []

            for( let i = 0; i < this.files.length; i++ ) {
                let file = this.files[i];
                this.listFile.push(file?.name)
            }
        },

        getContact() {
            this.initPageData = true
            EnergyContractModel.getContractByCondition({
                energy_type: this.energy_type_id,
                company_id: this.usage.company_id,
                facility_id: this.usage.facility_id,
                building_id: this.usage.building_id,
            }).then((res) => {
                if (res) {
                    this.usage.amount = res.co2_convert_coefficient
                    this.usage.energy_contract_id = res.id
                    this.usage.agency_id = res.agency_id
                    this.checkHaveContract = false
                    message.success('Get contract successfully')
                } else {
                    message.error('Get contract fail')
                }
                this.initPageData = false
            })
        },

        handleChangeContractParams()
        {
            if (!this.checkHaveContract)
                this.usage.amount = 0
                this.usage.converted_co2_amount = 0
                this.usage.energy_contract_id = null
                this.usage.agency_id = null
                this.checkHaveContract = true
        },

        handleChangeUsedAmount(event)
        {
            this.usage.converted_co2_amount = event.target.value * this.usage.amount
        },

        async create() {
            this.clearError()
            this.initPageData = true
            this.usage.usage_from_date = dayjs(this.usage.usage_from_date).format('YYYY-MM-DD');
            this.usage.usage_to_date = dayjs(this.usage.usage_to_date).format('YYYY-MM-DD');
            this.usage.billing_date = dayjs(this.usage.billing_date).format('YYYY-MM-DD');
            this.usage.invoice_date = dayjs(this.usage.invoice_date).format('YYYY-MM-DD');

            UsageModel.create(this.usage, this.files)
                .then(res => {
                    if (res?.response?.status === 422) {
                        this.tryGetErrorResponse(res)
                    } else {
                        if (res.success) {
                            message.success(res.message)
                        } else {
                            message.error(res.message)
                        }
                        this.$router.push({name: 'usage.list'})
                    }
                    this.initPageData = false
                })
        }
    }
}
</script>

<style scoped lang="scss">
.w-40 {
    width: 40%;
}
</style>
