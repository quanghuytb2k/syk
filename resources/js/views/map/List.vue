<template>
    <div class="d-flex justify-content-between">
        <div class="screen-title">図面一覧</div>
        <a-button type="primary" size="large" ghost @click="$router.push({ name: 'map.create' })">
            新規登録
        </a-button>
    </div>
    <div class="content">
        <a-form :label-col="labelCol">
            <a-row :gutter="50">
                <a-col :span="6">
                    <a-form-item
                        label="施設名:">
                        <a-select
                            v-model:value="search.facility_id"
                            show-search
                            :options="listFacility"
                            @keyup.enter="getList(1, {...search})"
                            :fieldNames="{label: 'name', value: 'id'}"
                            :disabled="isLoading"
                        />
                    </a-form-item>
                </a-col>

                <a-col :span="6">
                    <a-form-item label="保管場所:">
                        <a-input
                            v-model:value="search.storage_location"
                            :disabled="isLoading"
                            @keyup.enter="getList(1, {...search})"
                        />
                    </a-form-item>
                </a-col>
            </a-row>

            <a-row :gutter="50">
                <a-col :span="6">
                    <a-form-item label="図面区分:">
                        <a-select
                            v-model:value="search.drawing_type_id"
                            :disabled="isLoading"
                            show-search
                            @keyup.enter="getList(1, {...search})"
                            :options="listDrawing"
                            :fieldNames="{label: 'name', value: 'id'}"
                        />
                    </a-form-item>
                </a-col>

                <a-col :span="6">
                    <a-form-item label="図面番号:">
                        <a-input
                            v-model:value="search.code"
                            :disabled="isLoading"
                            @keyup.enter="getList(1, {...search})"
                        />
                    </a-form-item>
                </a-col>

                <a-col :span="12" class="text-end">
                    <a-button type="primary" :loading="isLoading" @click="getList(1, {...search})">
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
            :dataSource="maps"
            :columns="tableColumns"
            :pagination="pagination"
            @change="handleTableChange"
        >
            <template #bodyCell="{column, text, record}">
                <template v-if="column.dataIndex === 'facility_name' && record.facility_id">
                    <router-link :to="{ name: 'facility.edit', params: {facilityId: record.facility_id }}">
                        {{ record?.facility?.name }}
                    </router-link>
                </template>
                <template v-if="column.dataIndex === 'created_at'">
                    {{ dayjs(record.created_at).format('DD-MM-YYYY') }}
                </template>
                <template v-if="column.dataIndex === 'drawing_number'">
                    {{ record.number_draw }}
                </template>
                <template v-if="column.dataIndex === 'drawing_division'">
                    {{ record?.drawing_type?.name }}
                </template>
                <template v-if="column.dataIndex === 'author'">
                    {{ record.creator }}
                </template>
                <template v-if="column.dataIndex === 'summary'">
                    {{ record.note }}
                </template>
                <template v-if="column.dataIndex === 'action'">
                    <router-link :to="{ name: 'map.edit', params: {mapId: record.id }}">詳細</router-link>
                </template>
            </template>
        </a-table>
    </div>
</template>

<script>
import {FacilityModel} from "@/model/facility";
import {SearchOutlined} from "@ant-design/icons-vue";
import mapApi from '@/model/map';
import dayjs from "dayjs";

const defaultSelectOption = {
    name: '全て',
    id: null
}
export default {
    computed: {
        dayjs() {
            return dayjs
        }
    },
    components: {
        SearchOutlined,
    },
    data() {
        const handleTableChange = async (pag, filters, sorter) => {
            this.isLoading = true
            await this.getList(pag.current, {...this.search})
            this.isLoading = false
        };

        return {
            labelCol: {style: {width: '70px'}},
            dataSource: [],
            pagination: {},
            handleTableChange,
            isLoading: true,
            tableColumns: [
                {
                    title: '施設名',
                    dataIndex: 'facility_name',
                    key: 'facility_name',
                },
                {
                    title: '図面区分',
                    dataIndex: 'drawing_division',
                    key: 'drawing_division',
                },
                {
                    title: '図面番号',
                    dataIndex: 'drawing_number',
                    key: 'drawing_number',
                },
                {
                    title: '保管場所',
                    dataIndex: 'storage_location',
                    key: 'storage_location',
                },
                {
                    title: '作成日',
                    dataIndex: 'created_at',
                },
                {
                    title: '作成者',
                    dataIndex: 'author',
                },
                {
                    title: '摘要',
                    dataIndex: 'summary'
                },
                {
                    title: '操作',
                    dataIndex: 'action'
                }
            ],
            search: {
                facility_id: null,
                drawing_type_id: null,
                storage_location: null,
                code: null,
            },
            maps: [],
            listDrawing: [],
            listFacility: [],
        }
    },
    methods: {
        getList(page, search = {
            facility_id: null,
            drawing_type_id: null,
            storage_location: null,
            code: null,
        }) {
            this.isLoading = true;
            mapApi.list({
                page,
                facilityId: search.facility_id,
                drawingType: search.drawing_type_id,
                storageLocation: search.storage_location,
                code: search.code,
            }).then(res => {
                this.maps = res.data
                this.pagination = {
                    total: res.total,
                    current: res.current_page,
                    pageSize: res.per_page,
                }
                this.isLoading = false;
            });
        },

        getListDrawingType() {
            this.loading = true
            mapApi.listDrawingType().then(res => {
                this.listDrawing = res ? res : []
            });
        },

        getListFacility() {
            this.loading = true
            FacilityModel.getAllFacilityNames().then(res => {
                this.listFacility = res ? res : []
                this.listFacility.unshift(defaultSelectOption);
            })
        }
    },

    async mounted() {
        this.listDrawing = [defaultSelectOption, ...await mapApi.listDrawingType()]
        let user = await this.$store.dispatch('auth/me')

        if (user.agency_id || user.company_id) {
            this.listFacility = [defaultSelectOption, ...await FacilityModel.getFacilityByParent(user.agency_id, user.company_id)]
        } else {
            this.listFacility = [defaultSelectOption, ...await FacilityModel.getAllFacilityNames()]
        }

        this.getList(1)
    },

}
</script>
