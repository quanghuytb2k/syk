<template>
    <div class="d-flex justify-content-between">
        <div class="screen-title">建屋一覧</div>
        <a-button type="primary" size="large" ghost @click="$router.push({ name: 'building.create' })">
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

                <a-col :span="14">
                    <a-form-item label="区分:">
                        <a-radio-group v-model:value="searchParams.directProfit" :disabled="isLoading">
                            <a-radio class="mx-3" value="2">全て</a-radio>
                            <a-radio class="mx-3" value="0">間接</a-radio>
                            <a-radio class="mx-3" value="1">直接</a-radio>
                        </a-radio-group>
                    </a-form-item>
                </a-col>

                <a-col :span="6" v-if="this.$store.state.role.role === 'admin'">
                    <a-form-item label="代理店">
                        <a-select
                            placeholder="代理店"
                            v-model:value="searchParams.agencyId"
                            :options="agencies"
                            :disabled="isLoading"
                            :fieldNames="{label: 'name', value: 'id'}"
                            @keyup.enter="getList(1, {...searchParams})"/>
                    </a-form-item>
                </a-col>

                <a-col :span="6"
                       v-if="!this.$store.state.role.role.includes('company') && !this.$store.state.role.role.includes('facility')">
                    <a-form-item label="企業">
                        <a-select
                            placeholder="企業"
                            v-model:value="searchParams.companyId"
                            :options="companies"
                            :disabled="isLoading"
                            :fieldNames="{label: 'name', value: 'id'}"
                            @keyup.enter="getList(1, {...searchParams})"/>
                    </a-form-item>
                </a-col>

                <a-col :span="6" v-if="!this.$store.state.role.role.includes('facility')">
                    <a-form-item label="施設">
                        <a-select
                            placeholder="施設"
                            v-model:value="searchParams.facilityId"
                            :options="facilities"
                            :disabled="isLoading"
                            :fieldNames="{label: 'name', value: 'id'}"
                            @keyup.enter="getList(1, {...searchParams})"/>
                    </a-form-item>
                </a-col>

                <a-col :span="6" class="text-end">
                    <a-button type="primary" :loading="isLoading" @click="getList(1, {...searchParams})">
                        <template #icon>
                            <SearchOutlined/>
                        </template>
                        検索
                    </a-button>
                </a-col>
            </a-row>
        </a-form>
        <a-divider/>

        <a-table
            :loading="isLoading"
            :dataSource="dataSource"
            :columns="tableColumns"
            :pagination="pagination"
            @change="handleTableChange">
            <template #bodyCell="{column, text, record}">
                <template v-if="column.dataIndex === 'agency_name'">
                    <router-link :to="{ name: 'agency.edit', params: {agencyId: record.agency_id }}">
                        {{ record?.agency?.name }}
                    </router-link>
                </template>
                <template v-if="column.dataIndex === 'company_name'">
                    <router-link :to="{ name: 'company.edit', params: {companyId: record.company_id }}">
                        {{ record?.company?.name }}
                    </router-link>
                </template>
                <template v-if="column.dataIndex === 'facility_name'">
                    <router-link :to="{ name: 'facility.edit', params: {facilityId: record.facility_id }}">
                        {{ record?.facility?.name }}
                    </router-link>
                </template>
                <template v-if="column.dataIndex === 'prefecture'">
                    {{ record?.facility?.prefecture?.prefecture_name }}
                </template>
                <template v-if="column.dataIndex === 'status'">
                    <a-tag :color="text === 1 ? 'blue':'red'"> {{ text == 1 ? "有効" : "無効" }}</a-tag>
                </template>
                <template v-if="column.dataIndex === 'action'">
                    <router-link :to="{ name: 'building.edit', params: {buildingId: record.id }}">詳細</router-link>
                </template>
            </template>
        </a-table>
    </div>
</template>

<script>
import {
    UserAddOutlined,
    SearchOutlined
} from '@ant-design/icons-vue'
import {BuildingModel} from '@/model/building.js'
import {CompanyModel} from "@/model/company";
import {message} from 'ant-design-vue';
import {agency} from "@/model/agency"
import {FacilityModel} from "@/model/facility";

const defaultSelectOption = {
    name: '全て',
    id: null
}
export default {
    components: {
        UserAddOutlined,
        SearchOutlined
    },
    data() {
        const handleTableChange = async (pag, filters, sorter) => {
            this.isLoading = true
            await this.getList(pag.current, {...this.searchParams})
            this.isLoading = false
        };

        return {
            isLoading: true,
            handleTableChange,
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
                    dataIndex: 'name',
                    key: 'name'
                },
                {
                    title: '都道府県',
                    dataIndex: 'prefecture'
                },
                {
                    title: '階数',
                    dataIndex: 'floor_count',
                    key: 'floor_count'
                },
                {
                    title: 'ステータス',
                    dataIndex: 'status',
                    key: 'status'
                },
                {
                    title: '操作',
                    dataIndex: 'action'
                }
            ],
            dataSource: [],
            pagination: {},
            searchParams: {
                searchKey: null,
                directProfit: '2',
                agencyId: null,
                companyId: null,
                facilityId: null
            },
            agencies: [],
            companies: [],
            facilities: []
        }
    },
    async mounted() {
        let user = await this.$store.dispatch('auth/me')
        if (this.$store.state.role.role === 'admin') {
            this.agencies = [{
                name: '全て',
                id: null
            }, ...await agency.getAllAgencyNames()]
        } else {
            this.searchParams.agencyId = user.agency_id
        }

        if (!this.$store.state.role.role.includes('company') && !this.$store.state.role.role.includes('facility')) {
            if (!user.agency_id) {
                this.companies = [defaultSelectOption, ...await CompanyModel.getAllCompanyNames()]
            } else {
                this.companies = [defaultSelectOption, ...await CompanyModel.getCompanyByAgency(user.agency_id)]
            }
        } else {
            this.searchParams.companyId = user.company_id
        }

        if (!this.$store.state.role.role.includes('facility')) {
            if (user.agency_id || user.company_id) {
                this.facilities = [defaultSelectOption, ...await FacilityModel.getFacilityByParent(user.agency_id, user.company_id)]
            } else {
                this.facilities = [defaultSelectOption, ...await FacilityModel.getAllFacilityNames()]
            }
        } else {
            this.searchParams.facilityId = user.facility_id
        }

        this.searchParams.facilityId = !isNaN(parseInt(this.$route.query.facility)) ? parseInt(this.$route.query.facility) : null

        this.getList(1, this.searchParams)
    },
    methods: {
        getList(page, params = {
            searchKey: null,
            directProfit: null,
            agencyId: null,
            companyId: null,
            facilityId: null
        }) {
            this.isLoading = true
            BuildingModel.list({
                page,
                searchKey: params.searchKey,
                directProfit: params.directProfit,
                agencyId: params.agencyId,
                companyId: params.companyId,
                facilityId: params.facilityId
            }).then(res => {
                this.dataSource = res.data
                this.pagination = {
                    total: res.total,
                    current: res.current_page,
                    pageSize: res.per_page,
                }
                this.isLoading = false
            })
        }
    }
}
</script>
