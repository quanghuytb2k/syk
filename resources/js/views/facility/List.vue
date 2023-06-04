<template>
    <div class="d-flex justify-content-between">
        <div class="screen-title">施設一覧</div>
        <a-button type="primary" size="large" ghost @click="$router.push({ name: 'facility.create' })">
            新規作成
        </a-button>
    </div>
    <div class="content">
        <a-form>
            <a-row :gutter="24">
                <a-col :span="6">
                    <a-form-item>
                        <a-input
                            placeholder="フリーテキスト検索"
                            v-model:value="searchParams.searchKey"
                            :disabled="isLoading"
                            @keyup.enter="getList(1, {...searchParams})"
                        />
                    </a-form-item>
                </a-col>

                <a-col :span="6">
                    <a-form-item label="都道府県">
                        <a-select
                            placeholder="都道府県"
                            :options="prefectures"
                            show-search
                            v-model:value="searchParams.prefectureId"
                            :disabled="isLoading"
                            @keyup.enter="getList(1, {...searchParams})"/>
                    </a-form-item>
                </a-col>

                <a-col :span="6" v-if="this.$store.state.role.role === 'admin'">
                    <a-form-item label="代理店">
                        <a-select
                            placeholder="代理店"
                            :options="agencies"
                            v-model:value="searchParams.agencyId"
                            show-search
                            :fieldNames="{label: 'name', value: 'id'}"
                            @change="filterCompanyByAgency"
                            :disabled="isLoading"
                            @keyup.enter="getList(1, {...searchParams})"/>
                    </a-form-item>
                </a-col>

                <a-col :span="6" v-if="!this.$store.state.role.role.includes('company')">
                    <a-form-item label="企業">
                        <a-select
                            placeholder="企業"
                            :options="companies"
                            show-search
                            :fieldNames="{label: 'name', value: 'id'}"
                            v-model:value="searchParams.companyId"
                            :disabled="isLoading"
                            @keyup.enter="getList(1, {...searchParams})"/>
                    </a-form-item>
                </a-col>
            </a-row>

            <a-row :gutter="24">
                <a-col :span="6" class="text-end offset-9">
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
                <template v-if="column.dataIndex === 'status'">
                    <a-tag :color="text === 1 ? 'blue':'red'" > {{ text==1 ? "有効": "無効" }}</a-tag>
                </template>
                <template v-if="column.dataIndex === 'agency' && record.company_id">
                    <router-link :to="{ name: 'agency.edit', params: {agencyId: record?.agency_id }}">
                        {{ record?.agency?.name }}
                    </router-link>
                </template>
                <template v-if="column.dataIndex === 'prefecture' && record.company_id">
                    {{ record?.prefecture?.prefecture_name }}
                </template>
                <template v-if="column.dataIndex === 'company_name' && record.company_id">
                    <router-link :to="{ name: 'company.edit', params: {companyId: record?.company_id }}">
                        {{ record?.company?.name }}
                    </router-link>
                </template>
                <template v-if="column.dataIndex === 'users_count'">
                    <router-link :to="{ name: 'user.list', query: {facility: record?.id }}">
                        {{ record.users_count }}
                    </router-link>
                </template>
                <template v-if="column.dataIndex === 'buildings_count'">
                    <router-link :to="{ name: 'building.list', query: {facility: record?.id }}">
                        {{ record.buildings_count }}
                    </router-link>
                </template>
                <template v-if="column.dataIndex === 'action'">
                    <router-link :to="{ name: 'facility.edit', params: {facilityId: record.id }}">詳細</router-link>
                </template>
            </template>
        </a-table>
    </div>
</template>

<script>
import {
    UserAddOutlined,
    SearchOutlined
} from '@ant-design/icons-vue';
import {FacilityModel} from "@/model/facility";
import {PrefectureModel} from "@/model/prefecture";
import {CompanyModel} from '@/model/company';
import {agency} from "@/model/agency"
import {message} from 'ant-design-vue';

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
                    dataIndex: 'agency',
                    key: 'agency'
                },
                {
                    title: '企業名',
                    dataIndex: 'company_name',
                    key: 'company_name'
                },
                {
                    title: '施設名',
                    dataIndex: 'name',
                    key: 'name'
                },
                {
                    title: '都道府県',
                    dataIndex: 'prefecture',
                    key: 'prefecture'
                },
                {
                    title: '施設アカウント数',
                    dataIndex: 'users_count',
                    key: 'users_count'
                },
                {
                    title: '建屋数',
                    dataIndex: 'buildings_count',
                    key: 'buildings_count'
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
                prefectureId: null,
                agencyId: null,
                companyId: null
            },
            prefectures: [
                {
                    label: '全て',
                    value: null
                }
            ],
            agencies: [],
            companies: []
        };
    },
    async mounted() {
        PrefectureModel.getAllPrefecture()
            .then(res => {
                let data = res.data.data
                Object.entries(data).forEach(entry => {
                    const [key, val] = entry;
                    this.prefectures.push({
                        label: val.prefecture_name,
                        value: val.id
                    })
                })
            })

        let user = await this.$store.dispatch('auth/me')
        if (this.$store.state.role.role === 'admin') {
            this.agencies = [{
                name: '全て',
                id: null
            }, ...await agency.getAllAgencyNames()]
        } else {
            this.searchParams.agencyId = user.agency_id
        }

        if (!this.$store.state.role.role.includes('company')) {
            if (user.agency_id || user.company_id) {
                this.companies = [defaultSelectOption, ...await CompanyModel.getCompanyByAgency(user.agency_id)]
            } else {
                this.companies = [defaultSelectOption, ...await CompanyModel.getAllCompanyNames()]
            }
        } else {
            this.searchParams.companyId = user.company_id
        }

        this.getList(1, this.searchParams)
    },
    methods: {
        getList(page, params = {
            searchKey: null,
            prefectureId: null,
            agencyId: null,
            companyId: null
        }) {
            this.isLoading = true
            FacilityModel.list({
                page,
                searchKey: params.searchKey,
                prefectureId: params.prefectureId,
                agencyId: params.agencyId,
                companyId: params.companyId
            }).then(res => {
                this.dataSource = res.data
                this.pagination = {
                    total: res.total,
                    current: res.current_page,
                    pageSize: res.per_page,
                }
                this.isLoading = false
            })
        },

        async filterCompanyByAgency() {
            let options
            if (this.searchParams.agencyId) {
                options = await CompanyModel.getCompanyByAgency(this.searchParams.agencyId)
            } else {
                options = await CompanyModel.getAllCompanyNames()
            }
            this.companies = [defaultSelectOption, ...options]
        }
    },
}
</script>

