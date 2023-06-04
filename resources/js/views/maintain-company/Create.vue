<template>
    <div class="d-flex justify-content-between">
        <div class="screen-title">施工・メンテ会社登録</div>
    </div>
    <div class="content">
        <a-form :label-col="labelCol">
            <a-row :glutter="24">
                <a-col class="px-3" :span="12">
                    <a-col :span="24">
                        <h5 class="border-left-title fw-bolder">基本情報</h5>
                    </a-col>
                    <a-col :span="24">
                        <a-form-item label="種別: " :rules="[{ required:true}]">
                            <a-radio-group :class="[isInvalid('company_type') ? 'border-danger' : '']"
                                           v-model:value="maintain_company.company_type" :disabled="isLoading">
                                <a-radio class="mx-3" value="0">施工会社</a-radio>
                                <a-radio class="mx-3" value="1">メンテ会社</a-radio>
                            </a-radio-group>
                            <span class="text-danger" v-if="isInvalid('company_type')">
                                {{ invalidMessages('company_type')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>
                    <a-col :span="24">
                        <a-form-item
                            label="会社名"
                            :rules="[{ required:true}]">
                            <a-input
                                v-model:value="maintain_company.name"
                                placeholder="会社名"
                                :class="[isInvalid('name') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('name')">
                                {{ invalidMessages('name')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            :rules="[{ required:true}]"
                            label="施工分類">
                            <a-select
                                v-model:value="maintain_company.maintain_company_type_id"
                                :options="typeList"
                                :loading="isLoading"
                                placeholder="施工分類"
                                :fieldNames="{label: 'name', value: 'id'}"
                                :class="[isInvalid('maintain_company_type_id') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('maintain_company_type_id')">
                                {{ invalidMessages('maintain_company_type_id')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            :rules="[{ required:true}]"
                            label="詳細区分">
                            <a-select
                                v-model:value="maintain_company.maintain_company_detail_type_id"
                                :options="detailTypeList"
                                :loading="isLoading"
                                placeholder="詳細区分"
                                :fieldNames="{label: 'name', value: 'id'}"
                                :class="[isInvalid('maintain_company_detail_type_id') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('maintain_company_detail_type_id')">
                                {{ invalidMessages('maintain_company_detail_type_id')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="従業員数">
                            <a-input
                                v-model:value="maintain_company.size_of_employee"
                                :loading="isLoading"
                                placeholder="従業員数"
                                :class="[isInvalid('size_of_employee') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('size_of_employee')">
                                {{ invalidMessages('size_of_employee')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="所有資格">
                            <a-input
                                v-model:value="maintain_company.ownership"
                                :loading="isLoading"
                                placeholder="所有資格"
                                :class="[isInvalid('ownership') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('ownership')">
                                {{ invalidMessages('ownership')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="許認可番号">
                            <a-input
                                v-model:value="maintain_company.license_number"
                                :loading="isLoading"
                                placeholder="許認可番号"
                                :class="[isInvalid('license_number') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('license_number')">
                                {{ invalidMessages('license_number')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="施工地域">
                            <a-input
                                v-model:value="maintain_company.construction_area"
                                :loading="isLoading"
                                placeholder="施工地域"
                                :class="[isInvalid('construction_area') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('construction_area')">
                                {{ invalidMessages('construction_area')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="施工範囲">
                            <a-input
                                v-model:value="maintain_company.construction_range"
                                :loading="isLoading"
                                placeholder="施工範囲"
                                :class="[isInvalid('construction_range') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('construction_range')">
                                {{ invalidMessages('construction_range')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24" class="d-flex ps-2em">
                        <a-form-item
                            class="w-100"
                            label="郵便番号"
                            :rules="[{ required:true}]">
                            <a-input
                                v-model:value="maintain_company.code"
                            />
                        </a-form-item>
                        <a-button class="ms-2" @click="searchPostCode" type="primary" :loading="isLoading">検索
                        </a-button>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="都道府県"
                            :rules="[{ required:true}]">
                            <a-select
                                placeholder="都道府県"
                                :options="prefectures"
                                v-model:value="maintain_company.prefecture_id"
                                :disabled="isLoading"
                                :fieldNames="{label: 'prefecture_name', value: 'id'}"
                                :class="[isInvalid('prefecture_id') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('prefecture_id')">
                                {{ invalidMessages('prefecture_id')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="住所"
                            :rules="[{ required:true}]">
                            <a-input
                                placeholder="市区町村"
                                v-model:value="maintain_company.municipality"
                                :disabled="isLoading"
                                :class="[isInvalid('municipality') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('municipality')">
                                {{ invalidMessages('municipality')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24" class="ps-2em ms-lg-3 ps-custom">
                        <a-form-item class="offset-3">
                            <a-input v-model:value="maintain_company.street" placeholder="丁目番地"></a-input>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24" class="ps-2em ms-lg-3 ps-custom">
                        <a-form-item class="offset-3">
                            <a-input v-model:value="maintain_company.building" placeholder="建物名階数"></a-input>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item label="メモ">
                            <a-textarea v-model:value="maintain_company.memo" placeholder="メモ"/>
                        </a-form-item>
                    </a-col>

                </a-col>
                <a-col class="px-3" :span="12">
                    <a-col :span="24">
                        <h5 class="border-left-title fw-bolder">連絡情報</h5>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="施設担当者名">
                            <a-input
                                v-model:value="maintain_company.contact_person"
                                :disabled="isLoading"
                                placeholder="施設担当者名"
                                :class="[isInvalid('contact_person') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('contact_person')">
                                {{ invalidMessages('contact_person')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="役職">
                            <a-input
                                v-model:value="maintain_company.contact_person_role"
                                :disabled="isLoading"
                                placeholder="役職"
                                :class="[isInvalid('contact_person_role') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('contact_person_role')">
                                {{ invalidMessages('contact_person_role')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="電話番号">
                            <a-input
                                v-model:value="maintain_company.contact_person_phone"
                                :disabled="isLoading"
                                placeholder="電話番号"
                                :class="[isInvalid('contact_person_phone') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('contact_person_phone')">
                                {{ invalidMessages('contact_person_phone')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="メールアドレス">
                            <a-input
                                v-model:value="maintain_company.email"
                                :disabled="isLoading"
                                placeholder="メールアドレス"
                                :class="[isInvalid('email') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('email')">
                                {{ invalidMessages('email')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>
                </a-col>
            </a-row>

            <div class="text-center">
                <a-button class="mx-2" :loading="isLoading" @click="$router.push({ name: 'maintain-company.list' })"
                          danger>キャンセル
                </a-button>
                <a-button class="mx-2" type="primary" :loading="isLoading" @click="create">登録</a-button>
            </div>

        </a-form>
    </div>
</template>

<script>
import {PrefectureModel} from "@/model/prefecture";
import {MaintainCompanyModel} from "@/model/maintain-company";
import {message} from "ant-design-vue";
import axios from "axios";

export default {
    data() {
        return {
            isLoading: true,
            labelCol: {span: 5},
            maintain_company: {},
            prefectures: [],
            typeList: [],
            detailTypeList: []
        }
    },
    async mounted() {
        [
            this.typeList,
            this.detailTypeList
        ] = await Promise.all([
            MaintainCompanyModel.getMaintainTypeList(),
            MaintainCompanyModel.getMaintainDetailTypeList()
        ])
        PrefectureModel.getAllPrefecture()
            .then(res => {
                this.prefectures = res.data.data
                this.isLoading = false
            })
    },
    methods: {
        create() {
            this.isLoading = true
            MaintainCompanyModel.create(this.maintain_company)
                .then(res => {
                    if (res?.response?.status === 422) {
                        this.tryGetErrorResponse(res)
                        this.isLoading = false
                    } else {
                        if (res.success) {
                            message.success(res.message)
                        } else {
                            message.error(res.message)
                        }
                        this.$router.push({name: 'maintain-company.list'})
                    }
                })
        },

        searchPostCode() {
            axios.get(`https://zipcloud.ibsnet.co.jp/api/search`, {
                params: {
                    zipcode: this.maintain_company.code,
                }
            }).then(res => {
                if (res?.data?.status == 400) {
                    message.error(res?.data?.message);
                } else if (res?.data?.status == 500 || res?.data?.status == 404) {
                    message.error(res?.data?.message);
                } else {
                    let data = res?.data?.results;
                    this.prefectures.forEach(item => {
                        if (item.prefecture_name == data[0]?.address1) {
                            this.maintain_company.prefecture_id = item.id;
                        }
                        this.maintain_company.municipality = data[0]?.address2;
                        this.maintain_company.street = data[0]?.address3;
                    });
                }
            })
        },
    }
}
</script>
<style scoped>
.border-danger {
    display: block !important;
}
</style>
