<template>
    <div class="d-flex justify-content-between">
        <div class="screen-title">企業登録</div>
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
                                :class="[isInvalid('name') ? 'border-danger' : '']"/>
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
                        <span class="text-danger" v-if="isInvalid('business_type')">
                                                                {{ invalidMessages('business_type')[0] }}
                                                            </span>
                    </a-col>
                    <a-col :span="24">
                        <a-form-item
                            label="業種"
                            :rules="[{ required:true}]">
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
                                :class="[isInvalid('is_stock_listing') ? 'border-danger-select' : '']"
                                placeholder="上場">
                                <a-select-option value="1">上場</a-select-option>
                                <a-select-option value="0">非上場</a-select-option>
                            </a-select>
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
                                :options="prefecture"/>
                            <span class="text-danger" v-if="isInvalid('prefecture_id')">
                                {{ invalidMessages('prefecture_id')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="住所"
                            :rules="[{ required:true}]">
                            <a-input placeholder="市区町村" v-model:value="company.municipality"/>
                            <span class="text-danger" v-if="isInvalid('municipality')">
                                {{ invalidMessages('municipality')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item class="offset-4">
                            <a-input placeholder="丁目番地" v-model:value="company.street"/>
                            <span class="text-danger" v-if="isInvalid('street')">
                                {{ invalidMessages('street')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item class="offset-4">
                            <a-input placeholder="建物名階数" v-model:value="company.building"/>
                        </a-form-item>
                        <span class="text-danger" v-if="isInvalid('building')">
                                {{ invalidMessages('building')[0] }}
                            </span>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item label="環境レポート出力">
                            <a-switch v-model:checked="company.is_export_report">
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
                            <a-textarea v-model:value="company.memo"/>
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
                <a-button class="mx-2" @click="$router.push({ name: 'company.list' })" danger>キャンセル</a-button>
                <a-button class="mx-2" type="primary" :loading="isLoading" @click="create">登録</a-button>
            </div>

        </a-form>
    </div>
</template>

<script>
import {
    CheckOutlined,
    CloseOutlined
} from '@ant-design/icons-vue'
import {CompanyModel} from "@/model/company";
import {agency} from "@/model/agency";
import {PrefectureModel} from "@/model/prefecture";
import {message} from 'ant-design-vue';
import axios from "axios";

export default {
    components: {
        CheckOutlined,
        CloseOutlined
    },
    data() {
        return {
            isLoading: false,
            labelCol: {span: 8},
            bussiness_types_dict: {
                21: "一般企業",
                22: "福祉施設",
                23: "学校",
                24: "その他"

            },
            company: {
                name: '',
                is_stock_listing: null,
                is_export_report: true,
                memo: null,
                municipality: null,
                street: null,
                prefecture_id: null,
                business_type: 21,
                agency_id: null,
                post_code: null,
                building: '',
                contact_person_name: '',
                job_title: '',
                department: '',
                phone: '',
                email: '',
            },
            prefecture: [],
            agencies: [],
        }
    },
    async mounted() {
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

        if (this.$store.state.role.role === 'admin') {
            this.agencies = await agency.getAllAgencyNames()
        } else {
            let user = await this.$store.dispatch('auth/me')
            this.company.agency_id = user.agency_id
        }

    },
    methods: {
        onChange(value) {
            this.company.business_type = value
        },
        create() {
            this.clearError()
            this.isLoading = true
            this.company.is_export_report = this.company.is_export_report === true ? 1 : 0
            CompanyModel.create(this.company)
                .then(res => {
                    if (res?.response?.status === 422) {
                        message.error('Create Company Fail!')
                        this.tryGetErrorResponse(res)
                    } else {
                        message.success(res.message)
                        this.$router.push({name: 'company.list'})
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
                if (res?.data?.status == 400) {
                    message.error(res?.data?.message);
                } else if (res?.data?.status == 500 || res?.data?.status == 404) {
                    message.error(res?.data?.message);
                } else {
                    let data = res?.data?.results;
                    this.prefecture.forEach(item => {
                        if (item.label == data[0]?.address1) {
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
