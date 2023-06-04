<template>
    <div class="d-flex justify-content-between w-100">
        <div class="screen-title">メンテ履歴</div>
        <CreateMaintainModel
            :id="$props.id"
            :is-loading="$props.isLoading"
            :get-list="getList"
            :maintain-companies="maintain_companies"/>
    </div>

    <a-table
        class="w-100 mb-4"
        :loading="isLoading"
        :dataSource="dataSource"
        :columns="tableColumns"
        :pagination="pagination"
        @change="handleTableChange">
        <template #bodyCell="{column, text, record}">
            <template v-if="column.dataIndex === 'company'">
                {{ record.maintain_company?.name }}
            </template>
            <template v-if="column.dataIndex === 'action'">
                <update-maintain-model
                    :id="$props.id"
                    :is-loading="$props.isLoading"
                    :get-list="getList"
                    :maintain-companies="maintain_companies"
                    :maintain-history="record"/>
            </template>
        </template>
    </a-table>
</template>
<script>
import CreateMaintainModel from "./Create.vue"
import UpdateMaintainModel from "./Edit.vue"
import {EquipmentModel} from "@/model/equipment";
import {MaintainCompanyModel} from "@/model/maintain-company";

export default {
    props: ['id', 'isLoading'],
    components: {
        CreateMaintainModel,
        UpdateMaintainModel
    },
    data() {
        return {
            isLoading: this.isLoading,
            dataSource: [],
            pagination: {},
            columns: [],
            tableColumns: [
                {
                    title: '実施日',
                    dataIndex: 'date'
                },
                {
                    title: 'メンテ会社',
                    dataIndex: 'company'
                },
                {
                    title: '金額',
                    dataIndex: 'money'
                },
                {
                    title: '次メンテ日',
                    dataIndex: 'next_maintenance_date'
                },
                {
                    title: '操作',
                    dataIndex: 'action',
                    fixed: 'right',
                    width: 100,
                }
            ],
            maintain_companies: []
        }
    },
    mounted() {
        MaintainCompanyModel.getAllMaintainCompany()
            .then(res => {
                this.maintain_companies = res
                this.getList(1)
            })
    },
    watch: {
        isLoading: function (newVal, oldVal) {
            this.isLoading = newVal
        }
    },
    methods: {
        getList(page, params = {
            searchKey: null
        })
        {
            this.isLoading = true
            let searchParams = {page, ...params}
            EquipmentModel.getMaintainHistoryList(searchParams, this.id)
                .then(res => {
                    this.dataSource = res.data
                    this.isLoading = false
                    this.pagination = {
                        total: res.total,
                        current: res.current_page,
                        pageSize: res.per_page,
                    }

                })
            return true
        }
    }
}
</script>
