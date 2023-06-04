<template>
    <div class="d-flex justify-content-between">
        <div class="screen-title">アカウント一覧</div>
        <a-button type="primary" size="large" ghost @click="$router.push({ name: 'user.create' })">
            新規作成
        </a-button>
    </div>
    <div class="content">
        <div class="d-flex flex-column gap-3">
            <div class="row">
                <div class="col-md-7 col-xl-4">
                    <a-input @keyup.enter="onSearch" v-model:value="search.text" placeholder="フリーテキスト検索"
                             :disabled="isLoading"/>
                </div>
            </div>
            <div class="row align-items-center">
                <div class="col-md-4" v-if="this.$store.state.role.role === 'admin'">
                    <div class="d-flex gap-3 align-items-center">
                        <div>代理店:</div>
                        <a-select
                            v-model:value="search.agency_id"
                            class="flex-fill"
                            show-search
                            @keyup.enter="onSearch"
                            placeholder="全て"
                            :options="agencies"
                            :fieldNames="{ label: 'name', value: 'id' }"
                            :disabled="isLoading"
                            :filter-option="filterOption"
                            @change="filterSelectOptionsByAgency"/>
                    </div>
                </div>
                <div class="col-md-3 col-xxl-4" v-if="!this.$store.state.role.role.includes('company')">
                    <div class="d-flex gap-3 align-items-center">
                        <div>企業:</div>
                        <a-select
                            v-model:value="search.company_id"
                            class="flex-fill"
                            show-search placeholder="全て"
                            :options="companies"
                            @keyup.enter="onSearch"
                            :fieldNames="{ label: 'name', value: 'id' }"
                            :disabled="isLoading"
                            :filter-option="filterOption"
                            @change="filterSelectFacilityByCompany"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex gap-3 align-items-center">
                        <div>施設:</div>
                        <a-select
                            v-model:value="search.facility_id"
                            class="flex-fill"
                            show-search
                            placeholder="全て"
                            @keyup.enter="onSearch"
                            :options="facilities"
                            :fieldNames="{ label: 'name', value: 'id' }"
                            :disabled="isLoading"
                            :filter-option="filterOption"/>
                    </div>
                </div>
                <div class="col-md-2 col-xxl-1 text-center">
                    <a-button type="primary" :loading="isLoading" @click="onSearch">
                        <template #icon>
                            <SearchOutlined/>
                        </template>
                        検索
                    </a-button>
                </div>
            </div>
            <a-table :dataSource="pagination.data" :columns="tableColumns" :pagination="aTablePagination"
                     :loading="isLoading" @change="onTableChanged">
                <template #bodyCell="{ column, text, record }">
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
                    <template v-if="column.key == 'status'">
                        <a-tag v-if="text == 2" color="blue">有効</a-tag>
                        <a-tag v-if="text == 1" color="orange">仮登録</a-tag>
                        <a-tag v-if="text == 0" color="red">無効</a-tag>
                    </template>
                    <template v-if="column.key == 'action'">
                        <router-link :to="{ name: 'user.edit', params: { id: record.id } }">詳細</router-link>
                    </template>
                </template>
            </a-table>
        </div>
    </div>
</template>

<script>
import {
    UserAddOutlined,
    SearchOutlined,
} from '@ant-design/icons-vue';
import api from '@/model/user';
import {CompanyModel} from "@/model/company";
import {FacilityModel} from "@/model/facility";
import {sortParamFromSorter} from '@/helpers';
import {agency} from "@/model/agency";

const defaultSelectOption = {
    name: '全て',
    id: 0
}

export default {
    components: {
        UserAddOutlined,
        SearchOutlined,
    },
    data() {
        return {
            isLoading: false,
            tableColumns: [
                {
                    title: '氏名',
                    dataIndex: 'name',
                    key: 'name',
                },
                {
                    title: '代理店名',
                    dataIndex: 'agency_name',
                    key: 'agency_name',
                },
                {
                    title: '企業名',
                    dataIndex: 'company_name',
                    key: 'company_name',
                },
                {
                    title: '施設名',
                    dataIndex: 'facility_name',
                    key: 'facility_name',
                },
                {
                    title: '権限',
                    dataIndex: 'role',
                    key: 'role',
                },
                {
                    title: 'ステータス',
                    dataIndex: 'status',
                    key: 'status',
                },
                {
                    title: '操作',
                    key: 'action',
                },
            ],
            pagination: {
                data: [],
                per_page: 10,
                current_page: 1,
                total: 250,
            },
            search: {
                agency_id: 0,
                company_id: 0,
                facility_id: 0,
                text: null,
            },
            agencies: [],
            companies: [],
            facilities: [],
        };
    },
    async mounted() {
        this.search.facility_id = !isNaN(parseInt(this.$route.query.facility)) ? parseInt(this.$route.query.facility) : 0
        await this.getScreenData();
        await this.getList(this.search);
    },
    computed: {
        aTablePagination() {
            return {
                current: this.pagination.current_page,
                pageSise: this.pagination.per_page,
                total: this.pagination.total,
            };
        },
    },
    methods: {
        async getList(params = {}) {
            this.isLoading = true;

            try {
                this.pagination = await api.list(params);
            } catch {
            }

            this.isLoading = false;
        },
        clearSearch() {
            for (const key in this.search) {
                this.search[key] = null;
            }
        },
        async getScreenData() {
            try {
                let user = await this.$store.dispatch('auth/me')

                if (this.$store.state.role.role === 'admin') {
                    this.agencies = [defaultSelectOption, ...await agency.getAllAgencyNames()]
                } else {
                    this.search.agency_id = user.agency_id
                }

                if (!this.$store.state.role.role.includes('company') && !this.$store.state.role.role.includes('facility')) {
                    if (!user.agency_id) {
                        this.companies = [defaultSelectOption, ...await CompanyModel.getAllCompanyNames()]
                    } else {
                        this.companies = [defaultSelectOption, ...await CompanyModel.getCompanyByAgency(user.agency_id)]
                    }
                } else {
                    this.search.company_id = user.company_id
                }

                if (!this.$store.state.role.role.includes('facility')) {
                    if (user.agency_id || user.company_id) {
                        this.facilities = [defaultSelectOption, ...await FacilityModel.getFacilityByParent(user.agency_id, user.company_id)]
                    } else {
                        this.facilities = [defaultSelectOption, ...await FacilityModel.getAllFacilityNames()]
                    }
                } else {
                    this.search.facility_id = user.facility_id
                }
            } catch { }
        },
        filterOption(input, option) {
            try {
                return option.name.toLowerCase().indexOf(input.toLowerCase()) >= 0;
            } catch {
                return false;
            }
        },
        onTableChanged(pagination, filters, sorter) {
            this.getList({
                page: pagination.current,
                per_page: pagination.pageSize,
                sort: sortParamFromSorter(sorter),
                search: this.search,
            });
        },
        onSearch() {
            this.getList({
                page: 1,
                per_page: this.pagination.per_page,
                search: this.search,
            });
        },
        async filterSelectOptionsByAgency() {
            let listCompany = await CompanyModel.getCompanyByAgency(this.search.agency_id)
            let listFacility = await FacilityModel.getFacilityByParent(this.search.agency_id, this.search.company_id)

            this.companies = [defaultSelectOption, ...listCompany]
            this.facilities = [defaultSelectOption, ...listFacility]
        },
        async filterSelectFacilityByCompany() {
            let listFacility = await FacilityModel.getFacilityByParent(0, this.search.company_id)
            this.facilities = [defaultSelectOption, ...listFacility]
        }
    },
}
</script>
