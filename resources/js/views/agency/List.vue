<template>
    <div class="d-flex justify-content-between">
        <div class="screen-title">代理店一覧</div>
        <a-button type="primary" size="large" ghost @click="$router.push({ name: 'agency.create' })">
            新規作成
        </a-button>
    </div>
    <div class="content">
        <a-form layout="vertical">
            <a-row :gutter="24">
                <a-col :span="6">
                    <a-input v-model:value="keyword" placeholder="フリーテキスト検索" @keyup.enter="doSearch"/>
                </a-col>
                <a-col :span="6">
                    <a-button type="primary" :loading="isLoading" @click="doSearch">
                        <template #icon>
                            <SearchOutlined/>
                        </template>
                        検索
                    </a-button>
                </a-col>
            </a-row>
        </a-form>
        <a-divider/>
        <a-table :dataSource="agencies.data"
                 :columns="tableColumns"
                 :pagination="{
                     current: agencies.current_page,
                     pageSize: agencies.per_page,
                     total: agencies.total
                 }"
                 :loading="isLoading"
                 @change="onChange"
        >
            <template #users_count="{ text, record }">
                <router-link :to="{ name: 'user.list', params: {id: record.id }}">{{ text }}</router-link>
            </template>
            <template #corporations_count="{ text, record }">
                <router-link :to="{ name: 'company.list', params: {id: record.id }}">{{ text }}</router-link>
            </template>
            <template #status="{ text }">
                <a-tag :color="text === '無効' ? 'red' : 'blue'"> {{ text }}</a-tag>
            </template>
            <template #action="{ text, record }">
                <router-link :to="{ name: 'agency.edit', params: {agencyId: record.id }}">詳細</router-link>
            </template>
        </a-table>
    </div>
</template>

<script>
import {
    UserAddOutlined,
    SearchOutlined
} from '@ant-design/icons-vue';
import {agency} from "@/model/agency";
import {message} from 'ant-design-vue';

export default {
    components: {
        UserAddOutlined,
        SearchOutlined
    },
    data() {
        return {
            isLoading: false,
            tableColumns: [
                {
                    title: '会社名',
                    dataIndex: 'name',
                    key: 'name',
                },
                {
                    title: '都道府県',
                    dataIndex: 'prefecture',
                    key: 'prefecture',
                },
                {
                    title: 'アカウント数',
                    dataIndex: 'users_count',
                    key: 'users_count',
                    slots: {title: 'users_count', customRender: 'users_count'},
                },
                {
                    title: '企業数',
                    dataIndex: 'corporations_count',
                    key: 'corporations_count',
                    slots: {title: 'corporations_count', customRender: 'corporations_count'},
                },
                {
                    title: 'ステータス',
                    dataIndex: 'status',
                    key: 'status',
                    slots: {title: 'status', customRender: 'status'},
                },
                {
                    title: '操作',
                    dataIndex: 'action',
                    key: 'action',
                    slots: {title: 'action', customRender: 'action'},
                },

            ],
            agencies: {},
            keyword: ""
        };
    },
    mounted() {
        this.getList()
    },
    methods: {
        getList(request = {"page": 1}) {
            this.isLoading = true
            agency.list(request).then(agencyList => {
                this.agencies = {
                    data: agencyList.data,
                    per_page: agencyList.per_page,
                    current_page: agencyList.current_page,
                    total: agencyList.total,
                }
                this.isLoading = false
            }).catch(errors => message.error('An Error Occurred'));
        },
        doSearch() {
            this.getList({'keyword': this.keyword})
        },

        onChange(pagination) {
            this.getList({"page": pagination.current})
        }
    },
}
</script>
