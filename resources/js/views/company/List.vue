<template>
    <div class="d-flex justify-content-between">
        <div class="screen-title">企業一覧</div>
        <a-button type="primary" size="large" ghost @click="$router.push({ name: 'company.create' })">
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
                            @keyup.enter="this.getList(1, {...searchParams})"
                            :disabled="isLoading"
                        />
                    </a-form-item>
                </a-col>

                <a-col :span="6" v-if="this.$store.state.role.role === 'admin'">
                    <a-form-item label="代理店">
                        <a-select
                            v-model:value="searchParams.agencyId"
                            placeholder="代理店"
                            :options="listAgency"
                            show-search
                            :fieldNames="{label: 'name', value: 'id'}"
                            :disabled="isLoading"
                            @keyup.enter="this.getList(1, {...searchParams})"/>
                    </a-form-item>
                </a-col>

                <a-col :span="6">
                    <a-form-item label="上場区分">
                        <a-select
                            v-model:value="searchParams.listType"
                            :options="listDivision"
                            :disabled="isLoading"
                            @keyup.enter="this.getList(1, {...searchParams})"/>
                    </a-form-item>
                </a-col>

                <a-col :span="6" class="text-end">
                    <a-button type="primary" :loading="isLoading" @click="this.getList(1, {...searchParams})">
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
                <template v-if="column.dataIndex === 'prefecture'">
                    {{ record?.prefecture?.prefecture_name }}
                </template>
                <template v-if="column.dataIndex === 'business_type'">
                    {{ this.showBusinessType(record.business_type) }}
                </template>
                <template v-if="column.dataIndex === 'user_count'">
                    <router-link :to="{ name: 'user.list', params: {id: record.id }}">{{ text }}</router-link>
                </template>
                <template v-if="column.dataIndex === 'stock_listing'">
                    {{ record.is_stock_listing === 1 ? '上表' : '非上表' }}
                </template>
                <template v-if="column.dataIndex === 'status'">
                    <a-tag :color="text === 1 ? 'blue':'red'" > {{ text==1 ? "有効": "無効" }}</a-tag>
                </template>
                <template v-if="column.dataIndex === 'action'">
                    <router-link :to="{ name: 'company.edit', params: {companyId: record.id }}">詳細</router-link>
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
import {CompanyModel} from "@/model/company";
import {message} from 'ant-design-vue';
import {agency} from "@/model/agency"

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
                    title: '組織名称',
                    dataIndex: 'name',
                    key: 'name'
                },
                {
                    title: '都道府県',
                    dataIndex: 'prefecture',
                },
                {
                    title: '特定事業者',
                    dataIndex: 'business_type'
                },
                {
                    title: '上場',
                    dataIndex: 'stock_listing'
                },
                {
                    title: '企業アカウント数',
                    dataIndex: 'user_count',
                    key: 'user_count'
                },
                {
                    title: '施設アカウント数',
                    dataIndex: 'facility_users',
                    key: 'facility_users'
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
                agencyId: null,
                listType: null
            },
            listDivision: [
                // {
                //     label: '全て',
                //     value: null
                // },
                {
                    label: '上場',
                    value: 1
                },
                {
                    label: '非上場',
                    value: 0
                }
            ],
            listAgency: []
        }
    },
    async mounted() {
        let user = await this.$store.dispatch('auth/me')
        if (this.$store.state.role.role === 'admin') {
            this.listAgency = [{
                name: '全て',
                id: null
            }, ...await agency.getAllAgencyNames()]
        } else {
            this.searchParams.agencyId = user.agency_id
        }

        this.getList(1, this.searchParams)
    },
    methods: {
        getList(page, params = {
            searchKey: null,
            agencyId: null,
            listType: null
        }) {
            this.isLoading = true
            let agencyId = this.$router.currentRoute.value.params.id
            CompanyModel.list({
                page,
                searchKey: params.searchKey,
                agencyId: agencyId ? agencyId : params.agencyId,
                listType: params.listType
            })
                .then(res => {
                    this.dataSource = res?.data
                    this.pagination = {
                        total: res?.total,
                        current: res?.current_page,
                        pageSize: res?.per_page,
                    }
                    this.isLoading = false
                })
        },

        showBusinessType(key) {
            return CompanyModel.getBusinessType(key)
        }
    }
}
</script>
