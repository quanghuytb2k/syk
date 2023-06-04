<template>
    <div>
        <div class="content">
            <a-form>
                <a-row :gutter="50">
                    <a-col>
                        <a-form-item
                            label="アラート期間"
                        >
                            <a-date-picker
                                :value-format="dateFormat"
                                v-model:value="time_from"
                                class="me-4 w-40"
                            />
                            ~
                            <a-date-picker
                                :value-format="dateFormat"
                                class="ms-4 w-40"
                                v-model:value="time_to"
                            />
                        </a-form-item>
                    </a-col>

                    <a-col>
                        <a-button class="btn-time" type="default" :loading="isLoading" @click="fillThisMonth">
                            今月
                        </a-button>
                    </a-col>
                    <a-col>
                        <a-button class="btn-time" type="default" :loading="isLoading" @click="fillNextMonth">
                            来月
                        </a-button>
                    </a-col>
                    <a-col :span="12" class="text-end">
                        <a-button type="primary" :loading="isLoading" @click="getList(1)">
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
                    <template v-if="column.dataIndex === 'alert_date'">
                        {{ dayjs(record.next_alarm_date).format('YYYY-MM-DD') }}
                    </template>
                    <template v-if="column.dataIndex === 'equipment_name'">
                        {{ record?.equipment?.name }}
                    </template>
                    <template v-if="column.dataIndex === 'previous_maintenance'">
                        {{ record?.content }}
                    </template>
                    <template v-if="column.dataIndex === 'location'">
                        {{ record?.equipment?.installation_detail_area }}
                    </template>
                    <template v-if="column.dataIndex === 'company'">
                        {{ record?.maintain_company?.name }}
                    </template>
                    <template v-if="column.dataIndex === 'next_maintain_day'">
                        {{ dayjs(record.next_maintenance_date).format('YYYY-MM-DD') }}
                    </template>
                </template>
            </a-table>
        </div>
    </div>
</template>

<script>

import {defineComponent} from "vue";
import {SearchOutlined, UserAddOutlined} from "@ant-design/icons-vue";
import dayjs from 'dayjs';
import {AlarmModel} from "@/model/alarm";

export default {
    components: {
        SearchOutlined
    },
    data() {
        const handleTableChange = async (pag, filters, sorter) => {
            this.isLoading = true
            await this.getList(pag.current, {...this.search})
            this.isLoading = false
        };
        return {
            isLoading: false,
            dataSource: [],
            time_from: null,
            time_to: null,
            handleTableChange,
            dateFormat: 'YYYY/MM/DD',
            tableColumns: [
                {
                    title: 'アラート日',
                    dataIndex: 'alert_date',
                    key: 'alert_date',
                },
                {
                    title: '施設名称',
                    dataIndex: 'equipment_name',
                    key: 'equipment_name',
                },
                {
                    title: '前のメンテ内容',
                    dataIndex: 'previous_maintenance',
                    key: 'previous_maintenance',
                },
                {
                    title: '施設名',
                    dataIndex: 'facility_name',
                    key: 'facility_name',
                },
                {
                    title: '建屋名',
                    dataIndex: 'building_name',
                    key: 'building_name',
                },
                {
                    title: '場所',
                    dataIndex: 'location',
                    key: 'location',
                },
                {
                    title: '導入区分',
                    dataIndex: 'type',
                    key: 'type',
                },
                {
                    title: 'メンテ会社',
                    dataIndex: 'company',
                    key: 'company',
                },
                {
                    title: 'メンテ履歴',
                    dataIndex: 'next_maintain_day',
                    key: 'next_maintain_day',
                },
            ],
            pagination: {},
        }
    },
    computed: {
        dayjs() {
            return dayjs
        }
    },
    methods: {
        async getList(page, search = {
            time_start: null,
            time_end: null,
        }) {
            this.isLoading = true
            let user = await this.$store.dispatch('auth/me')
            AlarmModel.list({
                page,
                time_from: this.time_from,
                time_to: this.time_to,
                agency_id: user.agency_id,
                company_id: user.company_id,
                facility_id: user.facility
            }).then(res => {
                this.dataSource = res
                this.pagination = {
                    total: res?.total,
                    current: res?.current_page,
                    pageSize: res?.per_page,
                }
                this.isLoading = false
            })
        },

        fillThisMonth() {
            let firstDayOfMonth = dayjs().startOf('month').toDate();
            firstDayOfMonth = dayjs(firstDayOfMonth).format('YYYY/MM/DD');
            let lastDayOfMonth = dayjs().endOf('month').toDate();
            lastDayOfMonth = dayjs(lastDayOfMonth).format('YYYY/MM/DD');

            this.time_from = firstDayOfMonth;
            this.time_to = lastDayOfMonth;
        },

        fillNextMonth() {
            let firstDayOfNextMonth = dayjs().add(1, 'month').startOf('month').toDate();
            firstDayOfNextMonth = dayjs(firstDayOfNextMonth).format('YYYY/MM/DD');
            let lastDayOfNextMonth = dayjs().add(1, 'month').endOf('month').toDate();
            lastDayOfNextMonth = dayjs(lastDayOfNextMonth).format('YYYY/MM/DD');

            this.time_from = firstDayOfNextMonth;
            this.time_to = lastDayOfNextMonth;
        },
    },
    mounted() {
        this.getList()
    }
}
</script>

<style>
.btn-time {
    background-color: #a5a5a5;
    border: 1px solid gray;
}

.btn-time:hover {
    cursor: pointer;
}
</style>
