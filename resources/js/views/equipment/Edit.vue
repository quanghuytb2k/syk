<template>
    <div class="d-flex justify-content-between">
        <div class="screen-title">設備詳細</div>
    </div>
    <div class="content">
        <a-form :label-col="labelCol">
            <a-row :gutter="24">
                <a-col class="px-3" :span="12">
                    <a-col :span="24">
                        <a-form-item
                            label="設備名称"
                            :required="true">
                            <a-input
                                :disabled="isLoading"
                                placeholder="設備名称"
                                v-model:value="equipment.name"
                                :class="[isInvalid('name') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('name')">
                                    {{ invalidMessages('name')[0] }}
                                </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            :required="true"
                            label="区分">
                            <a-tree-select
                                v-model:value="equipment.equipment_type_id"
                                :tree-data="treeData"
                                tree-default-expand-all
                                placeholder="区分"
                                :loading="isLoading"
                                :class="[isInvalid('equipment_type_id') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('equipment_type_id')">
                                    {{ invalidMessages('equipment_type_id')[0] }}
                                </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="メーカ">
                            <a-input
                                :disabled="isLoading"
                                v-model:value="equipment.maker"
                                placeholder="メーカ"
                                :class="[isInvalid('maker') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('maker')">
                                    {{ invalidMessages('maker')[0] }}
                                </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="型番">
                            <a-input
                                :disabled="isLoading"
                                v-model:value="equipment.model"
                                placeholder="型番"
                                :class="[isInvalid('model') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('model')">
                                    {{ invalidMessages('model')[0] }}
                                </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="定格(容量)">
                            <a-input
                                :disabled="isLoading"
                                v-model:value="equipment.capacity"
                                placeholder="定格(容量)"
                                :class="[isInvalid('capacity') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('capacity')">
                                    {{ invalidMessages('capacity')[0] }}
                                </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24" v-if="this.$store.state.role.role === 'admin'">
                        <a-form-item
                            :required="true"
                            label="設置代理店">
                            <a-select
                                v-model:value="equipment.agency_id"
                                :loading="isLoading"
                                placeholder="設置代理店"
                                :options="agencies"
                                :fieldNames="{label: 'name', value: 'id'}"
                                :class="[isInvalid('agency_id') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('agency_id')">
                                {{ invalidMessages('agency_id')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24"
                           v-if="!this.$store.state.role.role.includes('company') && !this.$store.state.role.role.includes('facility')">
                        <a-form-item
                            label="設置企業">
                            <a-select
                                :required="true"
                                v-model:value="equipment.company_id"
                                :loading="isLoading"
                                placeholder="設置企業"
                                :options="companies"
                                :fieldNames="{label: 'name', value: 'id'}"
                                :class="[isInvalid('company_id') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('company_id')">
                                {{ invalidMessages('company_id')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24" v-if="!this.$store.state.role.role.includes('facility')">
                        <a-form-item
                            label="設置施設">
                            <a-select
                                v-model:value="equipment.facility_id"
                                :loading="isLoading"
                                placeholder="設置施設"
                                :options="facilities"
                                :fieldNames="{label: 'name', value: 'id'}"
                                :class="[isInvalid('facility_id') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('facility_id')">
                                {{ invalidMessages('facility_id')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="設置建屋">
                            <a-select
                                v-model:value="equipment.building_id"
                                :loading="isLoading"
                                placeholder="設置建屋"
                                :options="buildings"
                                :fieldNames="{label: 'name', value: 'id'}"
                                :class="[isInvalid('building_id') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('building_id')">
                                {{ invalidMessages('building_id')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="場所">
                            <a-input
                                :disabled="isLoading"
                                v-model:value="equipment.installation_detail_area"
                                placeholder="場所"
                                :class="[isInvalid('installation_detail_area') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('installation_detail_area')">
                                    {{ invalidMessages('installation_detail_area')[0] }}
                                </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="設置日">
                            <a-date-picker
                                :disabled="isLoading"
                                v-model:value="equipment.installation_date"
                                :value-format="dateFormat"
                                class="me-4 w-40"
                                :class="[isInvalid('installation_date') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('installation_date')">
                                    {{ invalidMessages('installation_date')[0] }}
                                </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="次アラート日">
                            <a-date-picker
                                :disabled="isLoading"
                                v-model:value="equipment.next_maintenance_period"
                                :value-format="dateFormat"
                                class="me-4 w-40"/>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="点検スパン">
                            <a-select
                                v-model:value="equipment.maintenance_time"
                                :disabled="isLoading"
                                placeholder="点検スパン"
                                :fieldNames="{label: 'name', value: 'id'}"
                                :class="[isInvalid('energy_type_id') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('energy_type_id')">
                                {{ invalidMessages('energy_type_id')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                </a-col>

                <a-col class="px-3" :span="12">

                    <a-col :span="24" class="d-flex ps-2em ms-2">
                        <a-form-item
                            class="w-100"
                            label="メンテ会社名">
                            <a-select
                                :disabled="isLoading"
                                placeholder="点検スパン"
                                :fieldNames="{label: 'name', value: 'id'}"
                                :class="[isInvalid('energy_type_id') ? 'border-danger' : '']"/>
                        </a-form-item>
                        <a-button class="ms-2" type="primary" :loading="isLoading">+</a-button>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item label="導入区分">
                            <a-radio-group v-model:value="equipment.contract_type" :disabled="isLoading">
                                <a-radio value="1">購入</a-radio>
                                <a-radio value="2">リース</a-radio>
                            </a-radio-group>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24" :hidden="equipment.contract_type !== '1'">
                        <div class="ant-row ant-form-item" style="row-gap: 0px;">
                            <div class="ant-col ant-col-5 ant-form-item-label">
                                &ensp;
                            </div>
                            <a-col class="ant-form-item-control company-box pt-4">
                                <a-form :label-col="{span: 4}">
                                    <a-col :span="24">
                                        <a-form-item
                                            label="購入先">
                                            <a-input
                                                :disabled="isLoading"
                                                v-model:value="equipment.equipment_buy_company.retailer"
                                                placeholder="購入先"/>
                                        </a-form-item>
                                    </a-col>

                                    <a-col :span="24">
                                        <a-form-item
                                            label="購入金額">
                                            <a-input
                                                :disabled="isLoading"
                                                v-model:value="equipment.equipment_buy_company.amount"
                                                placeholder="購入金額"/>
                                        </a-form-item>
                                    </a-col>

                                    <a-col :span="24">
                                        <a-form-item label="保証書">
                                            <a-radio-group v-model:value="equipment.equipment_buy_company.warranty"
                                                           :disabled="isLoading">
                                                <a-radio value="1">あり</a-radio>
                                                <a-radio value="2">なし</a-radio>
                                            </a-radio-group>
                                        </a-form-item>
                                    </a-col>

                                    <a-col :span="24">
                                        <a-form-item
                                            label="保証期間">
                                            <a-input
                                                :disabled="isLoading"
                                                v-model:value="equipment.equipment_buy_company.warranty_period"
                                                placeholder="保証期間"/>
                                        </a-form-item>
                                    </a-col>

                                </a-form>
                            </a-col>
                        </div>
                    </a-col>

                    <a-col :span="24" :hidden="equipment.contract_type !== '2'">
                        <div class="ant-row ant-form-item" style="row-gap: 0px;">
                            <div class="ant-col ant-col-5 ant-form-item-label">
                                &ensp;
                            </div>
                            <a-col class="ant-form-item-control company-box pt-4">
                                <a-form :label-col="{span: 5}">
                                    <a-col :span="24">
                                        <a-form-item
                                            label="リース会社">
                                            <a-input
                                                :disabled="isLoading"
                                                v-model:value="equipment.equipment_lease_company.lease_company"
                                                placeholder="リース会社"/>
                                        </a-form-item>
                                    </a-col>

                                    <a-col :span="24">
                                        <a-form-item
                                            label="連絡先">
                                            <a-input
                                                :disabled="isLoading"
                                                v-model:value="equipment.equipment_lease_company.contact_address"
                                                placeholder="連絡先"/>
                                        </a-form-item>
                                    </a-col>

                                    <a-col :span="24">
                                        <a-form-item
                                            :disabled="isLoading"
                                            v-model:value="equipment.equipment_lease_company.prefectures"
                                            label="都道府県">
                                            <a-input
                                                placeholder="都道府県"/>
                                        </a-form-item>
                                    </a-col>

                                    <a-col :span="24">
                                        <a-form-item
                                            label="住所">
                                            <a-input
                                                :disabled="isLoading"
                                                v-model:value="equipment.equipment_lease_company.address"
                                                placeholder="住所"/>
                                        </a-form-item>
                                    </a-col>

                                    <a-col :span="24">
                                        <a-form-item
                                            label="電話番号">
                                            <a-input
                                                :disabled="isLoading"
                                                v-model:value="equipment.equipment_lease_company.phone"
                                                placeholder="電話番号"/>
                                        </a-form-item>
                                    </a-col>

                                    <a-col :span="24">
                                        <a-form-item
                                            label="契約期間">
                                            <a-date-picker
                                                :disabled="isLoading"
                                                v-model:value="equipment.equipment_lease_company.contract_period"
                                                placeholder="契約期間"
                                                :value-format="dateFormat"
                                                class="me-4 w-40"/>
                                        </a-form-item>
                                    </a-col>

                                    <a-col :span="24">
                                        <a-form-item
                                            label="契約金額">
                                            <a-input
                                                :disabled="isLoading"
                                                v-model:value="equipment.equipment_lease_company.amount"
                                                placeholder="契約金額"/>
                                        </a-form-item>
                                    </a-col>

                                </a-form>
                            </a-col>
                        </div>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item label="メモ">
                            <a-textarea v-model:value="equipment.memo" :disabled="isLoading"/>
                        </a-form-item>
                    </a-col>

                </a-col>
            </a-row>

            <a-row :gutter="24">
                <maintain-history
                    :id="$route.params.equipmentId"
                    :isLoading="isLoading"
                />
            </a-row>

            <div class="text-center">
                <a-button class="mx-2" :loading="isLoading" @click="$router.push({ name: 'equipment.list' })" danger>
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
            @ok="deleteEquipment"
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
import {MasterModel} from "@/model/master";
import {CompanyModel} from "@/model/company";
import {FacilityModel} from "@/model/facility";
import {BuildingModel} from "@/model/building";
import {agency} from "@/model/agency";
import {EquipmentModel} from "@/model/equipment";
import dayjs from "dayjs";
import {message} from "ant-design-vue";
import MaintainHistory from "@/views/component/maintain-history/List.vue"
import {ExclamationCircleOutlined} from "@ant-design/icons-vue";

const defaultSelectOption = {
    name: '全て',
    id: null
}
export default {
    components: {
        ExclamationCircleOutlined,
        MaintainHistory
    },
    data() {
        return {
            isLoading: false,
            visible: false,
            labelCol: {span: 5},
            treeData: [],
            dateFormat: 'YYYY/MM/DD',
            agencies: [],
            companies: [],
            facilities: [],
            buildings: [],
            equipment: {
                equipment_buy_company: {},
                equipment_lease_company: {}
            }
        }
    },
    async mounted() {
        this.treeData = await MasterModel.getDivisionTreeView()

        let user = await this.$store.dispatch('auth/me')

        if (this.$store.state.role.role === 'admin') {
            this.agencies = [defaultSelectOption, ...await agency.getAllAgencyNames()]
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
        getDetail() {
            let equipmentId = this.$router.currentRoute.value.params.equipmentId
            EquipmentModel.getEquipmentDetail(equipmentId)
                .then(resData => {
                    if (resData && resData.success === false) {
                        message.error(resData.message)
                        this.$router.push({name: 'equipment.list'})
                    } else {
                        this.equipment = resData.data
                        this.equipment.contract_type = `${this.equipment.contract_type}`
                    }
                    this.isLoading = false
                })
            return true
        },

        update() {
            this.isLoading = true
            this.equipment.installation_date = dayjs(this.equipment.installation_date).format('YYYY-MM-DD');
            this.equipment.next_maintenance_period = dayjs(this.equipment.next_maintenance_period).format('YYYY-MM-DD');
            this.equipment.equipment_lease_company.contract_period = dayjs(this.equipment.equipment_lease_company.contract_period).format('YYYY-MM-DD');
            EquipmentModel.updateEquipment(this.equipment.id, this.equipment)
                .then(res => {
                    if (res?.response?.status === 422) {
                        message.error('Update Equipment Fail!')
                        this.tryGetErrorResponse(res)
                    } else {
                        message.success(res.message)
                        this.$router.push({name: 'equipment.list'})
                    }
                    this.isLoading = false
                })
        },

        deleteEquipment() {
            let equipmentId = this.$router.currentRoute.value.params.equipmentId
            this.isLoading = true
            EquipmentModel.deleteEquipment(equipmentId)
                .then(res => {
                    if (res && res.status === true) {
                        message.success('成功に削除されました。')
                        this.$router.push({name: 'equipment.list'})
                    } else {
                        message.error('に削除されました。')
                    }
                    this.isLoading = false
                })

        },
    }
}
</script>

<style scoped lang="scss">
.company-box {
    background: #D9D9D9;
    border-radius: 5px;
}
</style>
