<template>
    <div class="d-flex justify-content-between">
        <div class="screen-title">マスター設定</div>
    </div>
    <div class="content" id="content">
        <a-layout class="bg-white">
            <a-layout-sider width="200" style="height: 100%">
                <a-menu
                    style="height: 100%"
                    v-for="item in masterTables"
                    v-model:selectedKeys="selectedTable">
                    <a-menu-item @click="fetchTableData(item)" :key="item">{{ item }}</a-menu-item>
                </a-menu>
            </a-layout-sider>
            <a-layout-content class="px-4">
                <a-col class="text-end">
                    <a-button
                        type="primary"
                        primary
                        @click="handleAdd"
                        :disabled="!hasTable">
                        <plus-square-outlined />
                        行追加
                    </a-button>
                    <router-link :to="{ name: 'home' }">
                        <a-button
                            class="mx-2"
                            :loading="isLoading"
                            danger>
                            キャンセル
                        </a-button>
                    </router-link>
                </a-col>

                <a-col>
                    <a-table
                        :loading="isLoading"
                        :dataSource="dataSource"
                        :columns="tableColumns"
                        :pagination="false"
                        :scroll="{ y: screenY }">
                        <template #bodyCell="{column, text, record, index}">
                            <template v-if="column.dataIndex === 'no'">
                                {{++index}}
                            </template>
                            <template v-if="column.dataIndex === 'name'">
                                <div class="editable-cell">
                                    <div v-if="editableData[record.id]" class="editable-cell-input-wrapper">
                                        <a-input v-if="record.id !== 'new'" v-model:value="editableData[record.id].name" @pressEnter="update(record.id)" />
                                        <a-input v-else v-model:value="editableData[record.id].name" @pressEnter="create" />
                                    </div>
                                    <div v-else class="editable-cell-text-wrapper">
                                        {{ text || ' ' }}
                                    </div>
                                </div>
                            </template>
                            <template v-if="column.dataIndex === 'prefecture_name'">
                                <div class="editable-cell">
                                    <div v-if="editableData[record.id]" class="editable-cell-input-wrapper">
                                        <a-input v-if="record.id !== 'new'" v-model:value="editableData[record.id].prefecture_name" @pressEnter="update(record.id)" />
                                        <a-input v-else v-model:value="editableData[record.id].prefecture_name" @pressEnter="create" />
                                    </div>
                                    <div v-else class="editable-cell-text-wrapper">
                                        {{ text || ' ' }}
                                    </div>
                                </div>
                            </template>
                            <template v-if="column.dataIndex === 'unit'">
                                <div class="editable-cell">
                                    <div v-if="editableData[record.id]">
                                        <a-input v-if="record.id !== 'new'" v-model:value="editableData[record.id].unit" @pressEnter="update(record.id)" />
                                        <a-input v-else v-model:value="editableData[record.id].unit" @pressEnter="create" />
                                    </div>
                                    <div v-else>
                                        {{ text || ' ' }}
                                    </div>
                                </div>
                            </template>
                            <template v-if="column.dataIndex === 'order'">
                                <div class="editable-cell">
                                    <div v-if="editableData[record.id]">
                                        <a-input v-if="record.id !== 'new'" v-model:value="editableData[record.id].order" @pressEnter="update(record.id)" />
                                        <a-input v-else v-model:value="editableData[record.id].order" @pressEnter="create" />
                                    </div>
                                    <div v-else>
                                        {{ text || ' ' }}
                                    </div>
                                </div>
                            </template>
                            <template v-if="column.dataIndex === 'action'">
                                <div v-if="editableData[record.id]" class="px-3">
                                    <check-outlined v-if="record.id !== 'new'" :disabled="isLoading" class="editable-cell-icon-check" @click="update(record.id)" />&emsp;
                                    <check-outlined v-else :disabled="isLoading" class="editable-cell-icon-check" @click="create" />&emsp;
                                    <close-outlined :disabled="isLoading" class="" @click="closeEdit(record.id)"/>
                                </div>
                                <div v-else>
                                    <a-popconfirm
                                        title="Sure to delete?"
                                        @confirm="onDelete(record.id)"
                                    >
                                        <a-button type="link" danger>削除</a-button>
                                    </a-popconfirm>
                                    <a-button type="link" @click="edit(record.id)" primary>更新</a-button>
                                </div>
                            </template>
                        </template>
                    </a-table>
                </a-col>
            </a-layout-content>
        </a-layout>
    </div>
</template>

<script>
import {CheckOutlined, CloseOutlined, PlusSquareOutlined} from '@ant-design/icons-vue'
import {MasterModel} from "@/model/master";
import {reactive, ref} from "vue";
import {cloneDeep} from "lodash/lang";
import {message} from "ant-design-vue";

const defaultColumn = {
    title: 'action',
    dataIndex: 'action'
}
export default {
    components: {
        PlusSquareOutlined,
        CheckOutlined,
        CloseOutlined
    },
    data() {
        const editableData = reactive({});

        return {
            isLoading : false,
            tableColumns: [],
            dataSource: ref([]),
            masterTables: [],
            selectedTable: [],
            hasTable: false,
            screenY: window.innerHeight,
            editableData,
            masterTableColumn: {}
        }
    },
    mounted() {
        MasterModel.getListMasterTable()
            .then(res => {
                this.masterTables = res
            })
        this.screenY -= document.getElementById('content').offsetTop + 150
    },
    methods: {
        fetchTableData(table) {
            this.isLoading = true
            this.selectedTable = [`${table}`]
            MasterModel.getMasterTable(table)
                .then(res => {
                    this.tableColumns = [{
                        title: 'no',
                        dataIndex: 'no'
                    }]
                    this.masterTableColumn = res.column
                    res.column.map((value, index) => {
                        this.tableColumns.push({
                            title: value,
                            dataIndex: value,
                            key: value,
                        })
                    })
                    this.tableColumns.push(defaultColumn)
                    this.dataSource = res.data_table
                    this.isLoading = false
                    this.hasTable = true
                })
        },

        edit (key) {
            this.editableData[key] = cloneDeep(this.dataSource.filter(item => key === item.id)[0]);
        },

        closeEdit(key) {
            if (key === 'new') {
                let dataTable = []
                this.dataSource.map((val, index) => {
                    if (val.id !== 'new') dataTable.push(this.dataSource[index])
                })

                this.dataSource = dataTable
            }
            delete this.editableData[key]
        },

        handleAdd() {
            if (!this.editableData['new']) {
                let newColumn = {}
                this.masterTableColumn.map(value => {
                    return newColumn[`${value}`] = ''
                })
                newColumn['id'] = 'new'
                this.editableData['new'] = newColumn
                this.dataSource.push(this.editableData['new'])
            }
        },

        create() {
            let insertData = this.editableData['new'] ?? {}
            MasterModel.createRecordMasterTable({
                table: this.selectedTable[0],
                ...insertData
            }).then(res => {
                if (res.success) {
                    message.success(res.message)
                } else {
                    message.error(res.message)
                }
                delete this.editableData['new']
                this.fetchTableData(this.selectedTable[0])
            })
        },

        update(id) {
            this.isLoading = true
            MasterModel.updateRecordMasterTable({
                table: this.selectedTable[0],
                ...this.editableData[id]
            }).then(res => {
                if (res.success) {
                    message.success(res.message)
                } else {
                    message.error(res.message)
                }
                delete this.editableData[id]
                this.fetchTableData(this.selectedTable[0])
            })
        },

        onDelete(id) {
            MasterModel.deleteRecordMasterTable({
                table: this.selectedTable[0],
                id
            }).then(res => {
                if (res.success) {
                    message.success(res.message)
                } else {
                    message.error(res.message)
                }
                this.fetchTableData(this.selectedTable[0])
            })
        }
    }
}
</script>
