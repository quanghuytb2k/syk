<template>
    <div class="d-flex justify-content-between">
        <div class="screen-title">施工・メンテ会社一覧</div>
        <a-button type="primary" size="large" ghost @click="$router.push({ name: 'maintain-company.create' })">
            新規作成
        </a-button>
    </div>
    <div class="content">
        <a-form>
            <a-row>
                <a-col>
                    <a-form-item label="区分:">
                        <a-radio-group v-model:value="searchParams.company_type" :disabled="isLoading">
                            <a-radio class="mx-3" value="all">全て</a-radio>
                            <a-radio class="mx-3" value="0">施工会社</a-radio>
                            <a-radio class="mx-3" value="1">メンテ会社</a-radio>
                        </a-radio-group>
                    </a-form-item>
                </a-col>
            </a-row>
            <a-row :gutter="24">
                <a-col :span="7">
                    <a-form-item label="都道府県">
                        <a-select
                            v-model:value="searchParams.prefecture"
                            :disabled="isLoading"
                            placeholder="都道府県"
                            @keyup.enter="getList(1, {...searchParams})"
                            :fieldNames="{label: 'prefecture_name', value: 'id'}"
                            :options="prefectures"/>
                    </a-form-item>
                </a-col>

                <a-col :span="7">
                    <a-form-item label="施工区分">
                        <a-select
                            v-model:value="searchParams.type"
                            :disabled="isLoading"
                            placeholder="施工区分"
                            @keyup.enter="getList(1, {...searchParams})"
                            :fieldNames="{label: 'name', value: 'id'}"
                            :options="typeList"/>
                    </a-form-item>
                </a-col>

                <a-col :span="7">
                    <a-form-item label="詳細区分">
                        <a-select
                            v-model:value="searchParams.detailType"
                            :disabled="isLoading"
                            placeholder="詳細区分"
                            @keyup.enter="getList(1, {...searchParams})"
                            :fieldNames="{label: 'name', value: 'id'}"
                            :options="detailTypeList"/>
                    </a-form-item>
                </a-col>

                <a-col :span="3" class="text-end">
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
                <template v-if="column.dataIndex === 'prefectures'">
                    {{ record?.prefecture?.prefecture_name }}
                </template>
                <template v-if="column.dataIndex === 'construction_division'">
                    {{ record.type?.name }}
                </template>
                <template v-if="column.dataIndex === 'company_type'">
                    {{ record.company_type == 0 ? '施工会社' : 'メンテ会社' }}
                </template>
                <template v-if="column.dataIndex === 'detail_division'">
                    {{ record.detail_type?.name }}
                </template>
                <template v-if="column.dataIndex === 'status'">
                    <a-tag :color="text === 1 ? 'blue':'red'"> {{ text == 1 ? "有効" : "無効" }}</a-tag>
                </template>
                <template v-if="column.dataIndex === 'action'">
                    <router-link :to="{ name: 'maintain-company.edit', params: {maintainId: record.id }}">詳細
                    </router-link>
                </template>
            </template>
        </a-table>
    </div>
</template>

<script setup>
import {SearchOutlined} from "@ant-design/icons-vue";
import {ref, onMounted} from "vue";
import {MaintainCompanyModel} from "@/model/maintain-company";
import {PrefectureModel} from "@/model/prefecture";

const isLoading = ref(true)
const searchParams = ref({
    prefecture: null,
    type: null,
    detailType: null,
    company_type: 'all',
})
const prefectures = ref([])
const typeList = ref([])
const detailTypeList = ref([])
const pagination = ref({})
const dataSource = ref([])
const tableColumns = ref([
    {
        title: 'メンテ会社名',
        dataIndex: 'name'
    },
    {
        title: '種別',
        dataIndex: 'company_type'
    },
    {
        title: '都道府県',
        dataIndex: 'prefectures'
    },
    {
        title: '施工区分',
        dataIndex: 'construction_division'
    },
    {
        title: '詳細区分',
        dataIndex: 'detail_division'
    },
    {
        title: 'ステータス',
        dataIndex: 'status'
    },
    {
        title: '操作',
        dataIndex: 'action'
    }
])

const defaultSelectOption = {
    prefecture_name: '全て',
    name: '全て',
    id: null
}

const handleTableChange = (pag, filters, sorter) => {
    this.isLoading = true
    this.getList(pag.current, {...this.searchParams})
    this.isLoading = false
};

onMounted(async () => {
    [
        typeList.value,
        detailTypeList.value
    ] = await Promise.all([
        [defaultSelectOption, ...await MaintainCompanyModel.getMaintainTypeList()],
        [defaultSelectOption, ...await MaintainCompanyModel.getMaintainDetailTypeList()]
    ])

    PrefectureModel.getAllPrefecture()
        .then(res => {
            prefectures.value = [defaultSelectOption, ...res.data.data]
            getList(1)
        })
})

const getList = (page, params = {
    searchKey: null,
    agency: null,
    company: null,
    facility: null,
    building: null,
    company_type: null,
}) => {
    isLoading.value = true
    let searchParams = {page, ...params}
    MaintainCompanyModel.list(searchParams)
        .then(res => {
            dataSource.value = res.data
            pagination.value = {
                total: res.total,
                current: res.current_page,
                pageSize: res.per_page,
            }
            isLoading.value = false
        })
    return true
}
</script>
