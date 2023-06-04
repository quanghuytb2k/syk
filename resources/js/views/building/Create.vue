<template>
    <div class="d-flex justify-content-between">
        <div class="screen-title">建屋登録</div>
    </div>
    <div class="content">
        <a-form :label-col="labelCol">
            <a-row :gutter="24">
                <a-col class="px-3" :span="12">

                    <a-col :span="24" v-if="this.$store.state.role.role === 'admin'">
                        <a-form-item
                            :rules="[{ required:true}]"
                            label="代理店">
                            <a-select
                                v-model:value="building.agency_id"
                                show-search
                                :loading="isLoading"
                                :fieldNames="{ label: 'name', value: 'id' }"
                                :options="agencies"/>
                            <span class="text-danger" v-if="isInvalid('agency_id')">
                                {{ invalidMessages('agency_id')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24"
                           v-if="!this.$store.state.role.role.includes('company') && !this.$store.state.role.role.includes('facility')">
                        <a-form-item
                            :rules="[{ required:true}]"
                            label="企業">
                            <a-select
                                show-search
                                :loading="isLoading"
                                :fieldNames="{ label: 'name', value: 'id' }"
                                :options="companies"
                                v-model:value="building.company_id"
                                :class="[isInvalid('company_id') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('company_id')">
                                    {{ invalidMessages('company_id')[0] }}
                                </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24" v-if="!this.$store.state.role.role.includes('facility')">
                        <a-form-item
                            :rules="[{ required:true}]"
                            label="施設">
                            <a-select
                                v-model:value="building.facility_id"
                                show-search
                                :loading="isLoading"
                                :fieldNames="{ label: 'name', value: 'id' }"
                                :options="facilities"/>
                            <span class="text-danger" v-if="isInvalid('facility_id')">
                                                                {{ invalidMessages('facility_id')[0] }}
                                                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            :rules="[{ required:true}]"
                            label="建屋名">
                            <a-input
                                v-model:value="building.name"
                                :class="[isInvalid('name') ? 'border-danger' : '']"
                                :disabled="isLoading"/>
                            <span class="text-danger" v-if="isInvalid('name')">
                                    {{ invalidMessages('name')[0] }}
                                </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="建屋区分">
                            <a-select
                                placeholder="建屋区分"
                                v-model:value="building.building_type_id"
                                :options="building_type"/>
                            <span class="text-danger" v-if="isInvalid('building_type_id')">
                                {{ invalidMessages('building_type_id')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="構造区分">
                            <a-select
                                placeholder="構造区分"
                                v-model:value="building.building_construction_type_id"
                                :options="construction"/>
                            <span class="text-danger" v-if="isInvalid('building_construction_type_id')">
                                {{ invalidMessages('building_construction_type_id')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item label="区分:">
                            <a-radio-group v-model:value="building.is_direct_profit">
                                <a-radio value="0">間接</a-radio>
                                <a-radio value="1">直接</a-radio>
                            </a-radio-group>
                        </a-form-item>
                    </a-col>
                </a-col>

                <a-col class="px-3" :span="12">
                    <a-col :span="24">
                        <a-form-item
                            :rules="[{ required:true}]"
                            label="階数">
                            <a-input
                                v-model:value="building.floor_count"
                                :class="[isInvalid('floor_count') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('floor_count')">
                                    {{ invalidMessages('floor_count')[0] }}
                                </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="建屋面積">
                            <a-input
                                v-model:value="building.build_area_square"
                                :class="[isInvalid('build_area_square') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('build_area_square')">
                                    {{ invalidMessages('build_area_square')[0] }}
                                </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="敷地面積">
                            <a-input
                                v-model:value="building.area_square"
                                :class="[isInvalid('area_square') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('area_square')">
                                    {{ invalidMessages('area_square')[0] }}
                                </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item label="メモ">
                            <a-textarea v-model:value="building.memo"/>
                        </a-form-item>
                    </a-col>
                </a-col>
            </a-row>

            <div class="text-center">
                <a-button class="mx-2" :loading="isLoading" @click="$router.push({ name: 'building.list' })" danger>
                    キャンセル
                </a-button>
                <a-button class="mx-2" type="primary" :loading="isLoading" @click="create">登録</a-button>
            </div>

        </a-form>
    </div>
</template>

<script>
import {BuildingModel} from "@/model/building";
import {FacilityModel} from "@/model/facility";
import {CompanyModel} from "@/model/company";
import {message} from 'ant-design-vue';
import {agency} from "@/model/agency";

const defaultSelectOption = {
    name: '全て',
    id: null
}
export default {
    data() {
        return {
            isLoading: false,
            labelCol: {span: 4},
            building_type: [],
            construction: [],
            building: {
                name: '',
                agency_id: null,
                company_id: null,
                facility_id: null,
                is_direct_profit: '1',
                floor_count: '',
                memo: '',
                build_area_square: null,
                area_square: null,
                building_type_id: null,
                building_construction_type_id: null
            },
            facilities: [],
            companies: [],
            agencies: [],
        }
    },
    async mounted() {
        BuildingModel.listBuildingType()
            .then(res => {
                Object.entries(res).forEach(entry => {
                    const [key, val] = entry;
                    this.building_type.push({
                        label: val,
                        value: key
                    })
                })
            })

        BuildingModel.listConstruction()
            .then(res => {
                Object.entries(res).forEach(entry => {
                    const [key, val] = entry;
                    this.construction.push({
                        label: val,
                        value: key
                    })
                })
            })

        let user = await this.$store.dispatch('auth/me')
        if (this.$store.state.role.role === 'admin') {
            this.agencies = await agency.getAllAgencyNames()
        } else {
            this.building.agency_id = user.agency_id
        }

        if (!this.$store.state.role.role.includes('company') && !this.$store.state.role.role.includes('facility')) {
            if (!user.agency_id) {
                this.companies = [defaultSelectOption, ...await CompanyModel.getAllCompanyNames()]
            } else {
                this.companies = [defaultSelectOption, ...await CompanyModel.getCompanyByAgency(user.agency_id)]
            }
        } else {
            this.building.company_id = user.company_id
        }

        if (!this.$store.state.role.role.includes('facility')) {
            if (user.agency_id || user.company_id) {
                this.facilities = [defaultSelectOption, ...await FacilityModel.getFacilityByParent(user.agency_id, user.company_id)]
            } else {
                this.facilities = [defaultSelectOption, ...await FacilityModel.getAllFacilityNames()]
            }
        } else {
            this.building.facility_id = user.facility_id
        }
    },
    methods: {
        async create() {
            this.clearError()
            this.isLoading = true
            this.building.is_direct_profit = parseInt(this.building.is_direct_profit)
            BuildingModel.create(this.building)
                .then(res => {
                    if (res?.response?.status === 422) {
                        message.error('Create Building Fail!')
                        this.tryGetErrorResponse(res)
                    } else {
                        message.success(res.message)
                        this.$router.push({name: 'building.list'})
                    }
                    this.isLoading = false
                })
        }
    },
}
</script>
<style>
.ant-radio-wrapper {
    padding-left: 30px !important;
}
</style>
