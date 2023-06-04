<template>
    <div class="d-flex justify-content-between">
        <div class="screen-title">企業詳細</div>
    </div>
    <div class="content">
        <a-form :label-col="labelCol">
            <a-row :glutter="24">
                <a-col class="px-3" :span="12">
                    <a-col :span="24">
                        <h5 class="border-left-title fw-bolder">基本情報</h5>
                    </a-col>

                    <a-col :span="24" v-if="this.$store.state.role.role === 'admin'">
                        <a-form-item
                            label="代理店">
                            <a-select
                                v-model:value="company.agency_id"
                                show-search
                                :loading="isLoading"
                                :fieldNames="{ label: 'name', value: 'id' }"
                                :options="agencies"/>
                            <span class="text-danger" v-if="isInvalid('agency_id')">
                                {{ invalidMessages('agency_id')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="組織名称"
                            :rules="[{ required:true}]">
                            <a-input
                                v-model:value="company.name"
                                :class="[isInvalid('name') === true ? 'border-danger' : '']"
                                :disabled="isLoading"/>
                            <span class="text-danger" v-if="isInvalid('name')">
                                {{ invalidMessages('name')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="特定事業者区分"
                            :rules="[{ required:true}]">
                            <a-select v-model:value="bussiness_types_dict[company.business_type]"
                                      style="display: block;" @change="onChange">
                                <a-select-option value=21>一般企業</a-select-option>
                                <a-select-option value=22>福祉施設</a-select-option>
                                <a-select-option value=23>学校</a-select-option>
                                <a-select-option value=24>その他</a-select-option>
                            </a-select>
                        </a-form-item>
                        <span class="text-danger" v-if="isInvalid('company.business_type')">
                            {{ invalidMessages('company.business_type')[0] }}
                        </span>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item label="業種" :rules="[{ required:true}]">
                            <a-select v-model:value="company.insdustry" style="display: block;">
                                <a-select-option value="1">製造業</a-select-option>
                                <a-select-option value="2">電気・ガス業</a-select-option>
                                <a-select-option value="3">運輸・情報通信業</a-select-option>
                                <a-select-option value="4">商業</a-select-option>
                                <a-select-option value="5">金融・保険業</a-select-option>
                                <a-select-option value="6">不動産業</a-select-option>
                                <a-select-option value="7">サービス業</a-select-option>
                                <a-select-option value="8">水産・農林業</a-select-option>
                                <a-select-option value="9">鉱業</a-select-option>
                                <a-select-option value="10">建設業</a-select-option>
                                <a-select-option value="11">その他</a-select-option>
                            </a-select>
                        </a-form-item>
                        <span class="text-danger" v-if="isInvalid('company.insdustry')">
                            {{ invalidMessages('company.insdustry')[0] }}
                        </span>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="上場区分"
                            :rules="[{ required:true}]">
                            <a-select
                                v-model:value="company.is_stock_listing"
                                :class="[isInvalid('is_stock_listing') === true ? 'border-danger-select' : '']"
                                placeholder="上場"
                                :options="stock_listing"
                                :disabled="isLoading"/>
                            <span class="text-danger" v-if="isInvalid('is_stock_listing')">
                                {{ invalidMessages('is_stock_listing')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24" class="d-flex ps-2em ms-2">
                        <a-form-item
                            class="w-100"
                            label="郵便番号"
                            :rules="[{ required:true}]">
                            <a-input
                                v-model:value="company.post_code"
                            ></a-input>
                            <span class="text-danger" v-if="isInvalid('post_code')">
                                {{ invalidMessages('post_code')[0] }}
                            </span>
                        </a-form-item>
                        <a-button class="ms-2" type="primary" @click="searchPostCode" :loading="isLoading">検索
                        </a-button>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="都道府県"
                            :rules="[{ required:true}]">
                            <a-select
                                placeholder="都道府県"
                                v-model:value="company.prefecture_id"
                                :options="prefecture"
                                :disabled="isLoading"/>
                            <span class="text-danger" v-if="isInvalid('prefecture_id')">
                                {{ invalidMessages('prefecture_id')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="住所"
                            :rules="[{ required:true}]">
                            <a-input placeholder="市区町村" v-model:value="company.municipality" :disabled="isLoading"/>
                            <span class="text-danger" v-if="isInvalid('municipality')">
                                {{ invalidMessages('municipality')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item class="offset-4">
                            <a-input v-model:value="company.street" :disabled="isLoading"/>
                            <span class="text-danger" v-if="isInvalid('street')">
                                {{ invalidMessages('street')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item class="offset-4">
                            <a-input v-model:value="company.building" :disabled="isLoading"/>
                            <span class="text-danger" v-if="isInvalid('building')">
                                {{ invalidMessages('building')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item label="環境レポート出力">
                            <a-switch v-model:checked="isExport"
                                      @change="company.is_export_report = isExport === true? 1 : 0"
                                      :disabled="isLoading">
                                <template #checkedChildren>
                                    <CheckOutlined/>
                                </template>
                                <template #unCheckedChildren>
                                    <CloseOutlined/>
                                </template>
                            </a-switch>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item label="ステータス">
                            <a-switch v-model:checked="status" @change="company.status = status === true? 1 : 0"
                                      :disabled="isLoading">
                                <template #checkedChildren>
                                    <CheckOutlined/>
                                </template>
                                <template #unCheckedChildren>
                                    <CloseOutlined/>
                                </template>
                            </a-switch>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item label="メモ">
                            <a-textarea v-model:value="company.memo" :disabled="isLoading"/>
                        </a-form-item>
                        <span class="text-danger" v-if="isInvalid('memo')">
                                {{ invalidMessages('memo')[0] }}
                            </span>
                    </a-col>

                </a-col>

                <a-col class="px-3" :span="12">
                    <a-col :span="24">
                        <h5 class="border-left-title fw-bolder">連絡情報</h5>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="担当者名">
                            <a-input
                                v-model:value="company.contact_person_name"
                            ></a-input>
                            <span class="text-danger" v-if="isInvalid('contact_person_name')">
                                {{ invalidMessages('contact_person_name')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="部署名">
                            <a-input
                                v-model:value="company.department"
                            ></a-input>
                            <span class="text-danger" v-if="isInvalid('department')">
                                {{ invalidMessages('department')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="役職">
                            <a-input
                                v-model:value="company.job_title"
                            ></a-input>
                            <span class="text-danger" v-if="isInvalid('job_title')">
                                {{ invalidMessages('job_title')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="電話番号">
                            <a-input
                                v-model:value="company.phone"
                            ></a-input>
                            <span class="text-danger" v-if="isInvalid('phone')">
                                {{ invalidMessages('phone')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="メールアドレス">
                            <a-input
                                v-model:value="company.email"
                            ></a-input>
                            <span class="text-danger" v-if="isInvalid('email')">
                                {{ invalidMessages('email')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>
                </a-col>
            </a-row>

            <div class="text-center">
                <a-button class="mx-2" @click="$router.push({ name: 'company.list' })" :loading="isLoading" danger>
                    キャンセル
                </a-button>
                <a-button class="mx-2" type="primary" :loading="isLoading" @click="this.visible = true" danger>削除
                </a-button>
                <a-button class="mx-2" type="primary" :loading="isLoading" @click="update">更新</a-button>
            </div>

        </a-form>

        <a-modal
            v-model:visible="visible"
            title="操作確認"
            ok-text="はい"
            cancel-text="いいえ"
            @ok="deleteCompany"
            @cancel="this.visible = false"
            :bodyStyle="{ borderRadius: '5px' }"
            :width="400"
            :okButtonProps="{ type: 'danger' }"
        >
            <p>
                <ExclamationCircleOutlined class="larger-icon"/>
                削除してもよろしいでしょうか?
            </p>
        </a-modal>
    </div>
</template>

<script>
import {
    CheckOutlined,
    CloseOutlined, ExclamationCircleOutlined
} from '@ant-design/icons-vue'
import {CompanyModel} from "@/model/company";
import {PrefectureModel} from "@/model/prefecture";
import {agency} from "@/model/agency";
import {message} from 'ant-design-vue';
import {Confirm} from "notiflix";
import axios from "axios";

export default {
    components: {
        ExclamationCircleOutlined,
        CheckOutlined,
        CloseOutlined
    },
    data() {
        return {
            isExport: false,
            status: false,
            visible: false,
            isLoading: true,
            labelCol: {span: 8},
            company: {},
            prefecture: [],
            bussiness_types_dict: {
                21: "一般企業",
                22: "福祉施設",
                23: "学校",
                24: "その他"
            },

            stock_listing: [
                {
                    label: '上場',
                    value: 1
                },
                {
                    label: '非上表',
                    value: 0
                }
            ],
            agencies: [],
        }
    },
    async mounted() {
        let companyId = this.$router.currentRoute.value.params.companyId
        this.isLoading = true;
        // get list prefecture
        PrefectureModel.getAllPrefecture()
            .then(res => {
                let data = res.data.data
                this.prefecture = data.map(val => {
                    return {
                        label: val.prefecture_name,
                        value: val.id
                    }
                })
            })

        // get detail company
        CompanyModel.detail(companyId)
            .then(async res => {
                if (res && res.success === false) {
                    message.error(res.message)
                    this.$router.push({name: 'company.list'})
                } else {
                    let currentUser = await this.$store.dispatch('auth/me')
                    if (this.$store.state.role.role === 'admin' || currentUser.agency_id === res.data.agency_id) {
                        this.company = res.data
                        this.isExport = res.data.is_export_report === 1
                        this.status = res.data.status === 1
                    } else {
                        message.error(`you don't have permission to access`)
                        this.$router.push({name: 'company.list'})
                    }
                }
                this.isLoading = false
            }).catch(errors => {
            message.error('An Error Occurred Please Try Again Later')
        })

        if (this.$store.state.role.role === 'admin') {
            this.agencies = await agency.getAllAgencyNames()
        }
    },
    methods: {
        onChange(value) {
            this.company.business_type = value
        },
        update() {
            this.clearError()
            this.isLoading = true
            CompanyModel.update(this.company, this.company.id)
                .then(res => {
                    if (res?.response?.status === 422) {
                        message.error('Update Company Fail!')
                        this.tryGetErrorResponse(res)
                    } else {
                        message.success(res.message)
                        this.$router.push({name: 'company.list'})
                    }
                    this.isLoading = false
                })
        },

        deleteCompany() {
            this.isLoading = true
            CompanyModel.deleteCompany(this.company.id)
                .then(res => {
                    if (res && res.status === true) {
                        message.success('成功に削除されました。')
                        this.$router.push({name: 'company.list'})
                    } else {
                        message.error('に削除されました。')
                    }
                    this.isLoading = false
                })

        },

        searchPostCode() {
            axios.get(`https://zipcloud.ibsnet.co.jp/api/search`, {
                params: {
                    zipcode: this.company.post_code,
                }
            }).then(res => {
                if (res?.data?.status === 400) {
                    message.error(res?.data?.message);
                } else if (res?.data?.status === 500 || res?.data?.status === 404) {
                    message.error(res?.data?.message);
                } else {
                    let data = res?.data?.results;
                    this.prefecture.forEach(item => {
                        if (item.label === data[0]?.address1) {
                            this.company.prefecture_id = item.value;
                        }
                    });
                    this.company.municipality = data[0]?.address2;
                    this.company.street = data[0]?.address3;
                }
            })
        },
    }
}
</script>
