<template>
    <div class="d-flex justify-content-between">
        <div class="screen-title">建屋詳細</div>
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
                                :options="agencies"/>
                            <span class="text-danger" v-if="isInvalid('facility_id')">
                                {{ invalidMessages('facility_id')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24"
                           v-if="!this.$store.state.role.role.includes('company') && !this.$store.state.role.role.includes('facility')">
                        <a-form-item
                            :rules="[{ required:true}]"
                            label="施設">
                            <a-select
                                v-model:value="building.company_id"
                                show-search
                                :loading="isLoading"
                                :fieldNames="{ label: 'name', value: 'id' }"
                                :options="companies"/>
                            <span class="text-danger" v-if="isInvalid('facility_id')">
                                {{ invalidMessages('facility_id')[0] }}
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
                                v-model:value="building_type_value"
                                :options="building_type"
                                :disabled="isLoading"
                                @change="building.building_type_id = parseInt(building_type_value)"/>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="構造区分">
                            <a-select
                                placeholder="構造区分"
                                v-model:value="construction_value"
                                :options="construction"
                                :disabled="isLoading"
                                @change="building.building_construction_type_id = parseInt(construction_value)"/>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item label="区分:">
                            <a-radio-group v-model:value="direction" :disabled="isLoading"
                                           @change="building.is_direct_profit = parseInt(direction)">
                                <a-radio value="0">間接</a-radio>
                                <a-radio value="1">直接</a-radio>
                            </a-radio-group>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">

                        <a-form-item label="ステータス">
                            <a-switch v-model:checked="status" @change="building.status = status === true? 1 : 0"
                                      :disabled="isLoading">
                                <template #checkedChildren>
                                    <CheckOutlined/>
                                </template>
                                <template #unCheckedChildren>
                                    <CloseOutlined/>
                                </template>
                            </a-switch>
                            <span class="label" v-if="building.status">有効</span>
                            <span class="label" v-else>無効</span>
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
                                :class="[isInvalid('floor_count') ? 'border-danger' : '']"
                                :disabled="isLoading"/>
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
                            <a-textarea v-model:value="building.memo" :disabled="isLoading"/>
                        </a-form-item>
                    </a-col>
                </a-col>
            </a-row>

            <div class="text-center">
                <a-button class="mx-2" @click="$router.push({ name: 'building.list' })" :loading="isLoading" danger>
                    キャンセル
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
            @ok="deleteBuilding"
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
    CheckOutlined,
    CloseOutlined, ExclamationCircleOutlined
} from '@ant-design/icons-vue'
import {BuildingModel} from "@/model/building";
import {FacilityModel} from "@/model/facility";
import {agency} from "@/model/agency";
import {CompanyModel} from "@/model/company";
import {message} from 'ant-design-vue';
import {Confirm} from "notiflix";

const defaultSelectOption = {
    name: '全て',
    id: null
}
export default {
    components: {
        ExclamationCircleOutlined,
        CheckOutlined,
        CloseOutlined
    },
    data() {
        return {
            status: false,
            isLoading: true,
            visible: false,
            labelCol: {span: 4},
            direction: null,
            building_type_value: null,
            construction_value: null,
            building: {},
            building_type: [],
            construction: [],
            facilities: [],
            companies: [],
            agencies: [],
        }
    },
    async mounted() {
        let buildingId = this.$router.currentRoute.value.params.buildingId

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

        BuildingModel.detail(buildingId)
            .then(resData => {
                if (resData && resData.success === false) {
                    message.error(resData.message)
                    this.$router.push({name: 'building.list'})
                } else {
                    this.building = resData.data
                    this.direction = `${this.building.is_direct_profit}`
                    this.status = this.building.status === 1
                    if (this.building.building_type_id) this.building_type_value = `${this.building.building_type_id}`
                    if (this.building.building_construction_type_id) this.construction_value = `${this.building.building_construction_type_id}`
                }
                this.isLoading = false
            })

        if (this.$store.state.role.role === 'admin') {
            this.agencies = await agency.getAllAgencyNames()
        }

        let user = await this.$store.dispatch('auth/me')

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
    },
    methods: {
        update() {
            this.clearError()
            this.isLoading = true
            BuildingModel.update(this.building.id, this.building)
                .then(res => {
                    if (res?.response?.status === 422) {
                        message.error('Update Building Fail!')
                        this.tryGetErrorResponse(res)
                    } else {
                        message.success(res.message)
                        this.$router.push({name: 'building.list'})
                    }
                    this.isLoading = false
                })
        },

        deleteBuilding() {
            this.isLoading = true
            BuildingModel.deleteBuilding(this.building.id)
                .then(res => {
                    if (res && res.status === true) {
                        message.success('成功に削除されました。')
                        this.$router.push({name: 'building.list'})
                    } else {
                        message.error('に削除されました。')
                    }
                    this.isLoading = false
                })

        }
    }
}
</script>
<style>
.ant-radio-wrapper {
    padding-left: 30px !important;
}

.label {
    padding: 10px
}
</style>
