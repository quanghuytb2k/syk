<template>
    <div class="d-flex justify-content-between">
        <div class="screen-title">設備一覧</div>
        <a-button type="primary" size="large" ghost @click="$router.push({ name: 'equipment.create' })">
            新規作成
        </a-button>
    </div>
    <div class="content">
        <a-form>
            <a-row :gutter="24">
                <a-col :span="6">
                    <a-form-item>
                        <a-input
                            v-model:value="searchParams.searchKey"
                            placeholder="フリーテキスト検索"
                            :disabled="isLoading"
                            @keyup.enter="getList(1, {...searchParams})"
                        />
                    </a-form-item>
                </a-col>

                <a-col :span="18">
                    <a-form-item label="次メンテ期間">
                        <a-date-picker
                            v-model:value="searchParams.next_maintenance_period_from"
                            :value-format="dateFormat"
                            class="me-4 w-40"/>
                        ~
                        <a-date-picker
                            v-model:value="searchParams.next_maintenance_period_to"
                            :value-format="dateFormat"
                            class="mx-4 w-40"/>
                    </a-form-item>
                </a-col>
            </a-row>

            <a-row :gutter="24">
                <a-col :span="6" v-if="this.$store.state.role.role === 'admin'">
                    <a-form-item label="代理店">
                        <a-select
                            v-model:value="searchParams.agency"
                            :disabled="isLoading"
                            @keyup.enter="getList(1, {...searchParams})"
                            :fieldNames="{label: 'name', value: 'id'}"
                            :options="agencies"/>
                    </a-form-item>
                </a-col>

                <a-col :span="6"
                       v-if="!this.$store.state.role.role.includes('company') && !this.$store.state.role.role.includes('facility')">
                    <a-form-item label="企業">
                        <a-select
                            v-model:value="searchParams.company"
                            :disabled="isLoading"
                            @keyup.enter="getList(1, {...searchParams})"
                            :fieldNames="{label: 'name', value: 'id'}"
                            :options="companies"/>
                    </a-form-item>
                </a-col>

                <a-col :span="6" v-if="!this.$store.state.role.role.includes('facility')">
                    <a-form-item label="施設">
                        <a-select
                            v-model:value="searchParams.facility"
                            :disabled="isLoading"
                            @keyup.enter="getList(1, {...searchParams})"
                            :fieldNames="{label: 'name', value: 'id'}"
                            :options="facilities"/>
                    </a-form-item>
                </a-col>

                <a-col :span="6">
                    <a-form-item label="建屋">
                        <a-select
                            v-model:value="searchParams.building"
                            :disabled="isLoading"
                            @keyup.enter="getList(1, {...searchParams})"
                            :fieldNames="{label: 'name', value: 'id'}"
                            :options="buildings"/>
                    </a-form-item>
                </a-col>
            </a-row>

            <a-row :gutter="24">
                <a-col :span="10">
                    <a-form-item label="区分">
                        <a-tree-select
                            v-model:value="searchParams.equipment_type_id"
                            :tree-data="treeData"
                            tree-checkable
                            placeholder="区分"
                            @keyup.enter="getList(1, {...searchParams})"
                            :loading="isLoading"/>
                    </a-form-item>
                </a-col>

                <a-col :span="10">
                    <a-form-item label="導入区分">
                        <a-radio-group @keyup.enter="getList(1, {...searchParams})"
                                       v-model:value="searchParams.contract_type">
                            <a-radio value="">全て</a-radio>
                            <a-radio value="1">購入</a-radio>
                            <a-radio value="2">リース</a-radio>
                        </a-radio-group>
                    </a-form-item>
                </a-col>

                <a-col :span="4" class="text-end">
                    <a-button type="primary" :loading="isLoading" @click="getList(1, {...searchParams})">
                        <template #icon>
                            <SearchOutlined/>
                        </template>
                        検索
                    </a-button>
                </a-col>

            </a-row>
        </a-form>

        <a-table
            :scroll="{ x: 2000 }"
            :loading="isLoading"
            :dataSource="dataSource"
            :columns="tableColumns"
            :pagination="pagination"
            @change="handleTableChange">
            <template #bodyCell="{column, text, record}">
                <template v-if="column.dataIndex === 'agency_name' && record.agency_id">
                    <router-link :to="{ name: 'agency.edit', params: {agencyId: record.agency?.id }}">
                        {{ record?.agency?.name }}
                    </router-link>
                </template>
                <template v-if="column.dataIndex === 'company_name' && record.company_id">
                    <router-link :to="{ name: 'company.edit', params: {companyId: record?.company?.id }}">
                        {{ record?.company?.name }}
                    </router-link>
                </template>
                <template v-if="column.dataIndex === 'facility_name' && record.facility_id">
                    <router-link :to="{ name: 'facility.edit', params: {facilityId: record?.facility_id }}">
                        {{ record?.facility?.name }}
                    </router-link>
                </template>
                <template v-if="column.dataIndex === 'building_name' && record.building_id">
                    <router-link :to="{ name: 'building.edit', params: {buildingId: record?.building_id }}">
                        {{ record?.building?.name }}
                    </router-link>
                </template>
                <template v-if="column.dataIndex === 'action'">
                    <router-link :to="{ name: 'equipment.edit', params: {equipmentId: record.id }}">詳細</router-link>
                </template>
            </template>
        </a-table>
    </div>
</template>

<script>
import {
    SearchOutlined
} from '@ant-design/icons-vue'
import {CompanyModel} from "@/model/company"
import {FacilityModel} from "@/model/facility"
import {BuildingModel} from "@/model/building"
import {agency} from "@/model/agency";
import {MasterModel} from "@/model/master";
import {EquipmentModel} from "@/model/equipment";

const defaultSelectOption = {
    name: '全て',
    id: null
}
export default {
    components: {
        SearchOutlined
    },
    data() {
        const handleTableChange = (pag, filters, sorter) => {
            this.isLoading = true
            this.getList(pag.current, {...this.searchParams})
            this.isLoading = false
        };

        return {
            isLoading: true,
            handleTableChange,
            dataSource: [],
            pagination: {},
            dateFormat: 'YYYY/MM/DD',
            searchParams: {
                searchKey: null,
                agency: null,
                company: null,
                facility: null,
                building: null,
                contract_type: '',
                next_maintenance_period_from: null,
                next_maintenance_period_to: null,
                equipment_type_id: []
            },
            agencies: [],
            companies: [],
            facilities: [],
            buildings: [],
            treeData: [],
            tableColumns: [
                {
                    title: '代理店名',
                    dataIndex: 'agency_name'
                },
                {
                    title: '企業名',
                    dataIndex: 'company_name'
                },
                {
                    title: '施設名',
                    dataIndex: 'facility_name'
                },
                {
                    title: '建屋名',
                    dataIndex: 'building_name'
                },
                {
                    title: '導入区分',
                    dataIndex: 'contract_type_text'
                },
                {
                    title: 'メーカ',
                    dataIndex: 'maker'
                },
                {
                    title: '型番',
                    dataIndex: 'model'
                },
                {
                    title: '設置日',
                    dataIndex: 'installation_date'
                },
                {
                    title: '次アラート日',
                    dataIndex: 'next_maintenance_period'
                },
                {
                    title: '操作',
                    dataIndex: 'action',
                    fixed: 'right',
                    width: 100,
                }
            ]
        }
    },
    async mounted() {
        this.treeData = await MasterModel.getDivisionTreeView()

        let user = await this.$store.dispatch('auth/me')

        if (this.$store.state.role.role === 'admin') {
            this.agencies = [defaultSelectOption, ...await agency.getAllAgencyNames()]
        } else {
            this.searchParams.agency = user.agency_id
        }

        if (!this.$store.state.role.role.includes('company') && !this.$store.state.role.role.includes('facility')) {
            if (!user.agency_id) {
                this.companies = [defaultSelectOption, ...await CompanyModel.getAllCompanyNames()]
            } else {
                this.companies = [defaultSelectOption, ...await CompanyModel.getCompanyByAgency(user.agency_id)]
            }
        } else {
            this.searchParams.company = user.company_id
        }

        if (!this.$store.state.role.role.includes('facility')) {
            if (user.agency_id || user.company_id) {
                this.facilities = [defaultSelectOption, ...await FacilityModel.getFacilityByParent(user.agency_id, user.company_id)]
            } else {
                this.facilities = [defaultSelectOption, ...await FacilityModel.getAllFacilityNames()]
            }
        } else {
            this.searchParams.facility = user.facility_id
        }

        this.buildings = [defaultSelectOption, ...await BuildingModel.getListBuilding({
            agency_id: user.agency_id,
            company_id: user.company_id,
            facility_id: user.facility_id
        })]

        this.getList(1, this.searchParams)
    },
    methods: {
        getList(page, params = {
            searchKey: null,
            agency: null,
            company: null,
            facility: null,
            building: null,
            contract_type: '',
            next_maintenance_period_from: null,
            next_maintenance_period_to: null,
            equipment_type_id: null
        }) {
            this.isLoading = true
            let searchParams = {page, ...params}
            EquipmentModel.getListEquipment(searchParams)
                .then(res => {
                    this.dataSource = res.data
                    this.pagination = {
                        total: res.total,
                        current: res.current_page,
                        pageSize: res.per_page,
                    }
                    this.isLoading = false
                })
            return true
        }
    }
}
</script>
