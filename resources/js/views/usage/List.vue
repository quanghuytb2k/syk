<template>
    <div class="d-flex justify-content-between">
        <div class="screen-title">使用状況一覧</div>
        <a-button type="primary" size="large" ghost @click="$router.push({ name: 'usage.create' })">
            新規作成
        </a-button>
    </div>
    <div class="content">
        <a-form>
            <a-row :gutter="24">
                <a-col :span="5" v-if="this.$store.state.role.role === 'admin'">
                    <a-form-item label="代理店:">
                        <a-select
                            v-model:value="searchParams.agency"
                            :disabled="isLoading"
                            @keyup.enter="getList(1, {...searchParams})"
                            :fieldNames="{label: 'name', value: 'id'}"
                            :options="agencies"/>
                    </a-form-item>
                </a-col>

                <a-col :span="5" v-if="!this.$store.state.role.role.includes('company') && !this.$store.state.role.role.includes('facility')">
                    <a-form-item label="企業:">
                        <a-select
                            v-model:value="searchParams.company"
                            :disabled="isLoading"
                            @keyup.enter="getList(1, {...searchParams})"
                            :fieldNames="{label: 'name', value: 'id'}"
                            :options="companies"/>
                    </a-form-item>
                </a-col>

                <a-col :span="5" v-if="!this.$store.state.role.role.includes('facility')">
                    <a-form-item label="施設:">
                        <a-select
                            v-model:value="searchParams.facility"
                            :disabled="isLoading"
                            @keyup.enter="getList(1, {...searchParams})"
                            :fieldNames="{label: 'name', value: 'id'}"
                            :options="facilities"/>
                    </a-form-item>
                </a-col>

                <a-col :span="5">
                    <a-form-item label="建屋:">
                        <a-select
                            v-model:value="searchParams.building"
                            :disabled="isLoading"
                            @keyup.enter="getList(1, {...searchParams})"
                            :fieldNames="{label: 'name', value: 'id'}"
                            :options="buildings"/>
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
            :loading="isLoading"
            :dataSource="dataSource"
            :columns="tableColumns"
            :pagination="pagination"
            @change="handleTableChange">
            <template #bodyCell="{column, text, record}">
                <template v-if="column.dataIndex === 'type'">
                    {{record.contract?.energy_type?.name}}
                </template>
                <template v-if="column.dataIndex === 'company' && record.company_id">
                    <router-link :to="{ name: 'company.edit', params: {companyId: record?.company?.id }}">
                        {{ record.company?.name }}
                    </router-link>
                </template>
                <template v-if="column.dataIndex === 'facility' && record.facility_id">
                    <router-link :to="{ name: 'facility.edit', params: {facilityId: record?.facility_id }}">
                        {{ record.facility?.name }}
                    </router-link>
                </template>
                <template v-if="column.dataIndex === 'building' && record.building_id">
                    <router-link :to="{ name: 'building.edit', params: {buildingId: record?.building_id }}">
                        {{ record.building?.name }}
                    </router-link>
                </template>
                <template v-if="column.dataIndex === 'expiry'">
                    {{record.usage_from_date}} ~ {{record.usage_to_date}}
                </template>
                <template v-if="column.dataIndex === 'action'">
                    <router-link :to="{ name: 'usage.edit', params: {usageId: record.id }}">詳細</router-link>
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
import {UsageModel} from "@/model/usage";

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
            searchParams: {
                searchKey: null,
                agency: null,
                company: null,
                facility: null,
                building: null
            },
            agencies: [],
            companies: [],
            facilities: [],
            buildings: [],
            tableColumns: [
                {
                    title: '種別',
                    dataIndex: 'type'
                },
                {
                    title: '企業',
                    dataIndex: 'company'
                },
                {
                    title: '企業',
                    dataIndex: 'facility'
                },
                {
                    title: '建屋',
                    dataIndex: 'building'
                },
                {
                    title: '使用期間',
                    dataIndex: 'expiry'
                },
                {
                    title: '使用量',
                    dataIndex: 'amount'
                },
                {
                    title: 'CO2量(t)',
                    dataIndex: 'converted_co2_amount'
                },
                {
                    title: '金額',
                    dataIndex: 'money'
                },
                {
                    title: '操作',
                    dataIndex: 'action'
                }
            ]
        }
    },
    async mounted() {
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
            building: null
        }) {
            this.isLoading = true
            UsageModel.listUsage({
                page,
                searchKey: params.searchKey,
                agency: params.agency,
                company: params.company,
                facility: params.facility,
                building: params.building
            })
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
