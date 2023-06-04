<template>
    <div class="d-flex justify-content-between">
        <div class="screen-title">施設詳細</div>
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
                                v-model:value="facility.agency_id"
                                show-search
                                :loading="isLoading"
                                :fieldNames="{ label: 'name', value: 'id' }"
                                :options="agencies"/>
                            <span class="text-danger" v-if="isInvalid('agency_id')">
                                {{ invalidMessages('agency_id')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24" v-if="!this.$store.state.role.role.includes('company')">
                        <a-form-item
                            :rules="[{ required:true}]"
                            label="企業">
                            <a-select
                                show-search
                                :loading="isLoading"
                                :fieldNames="{ label: 'name', value: 'id' }"
                                :options="companies"
                                v-model:value="facility.company_id"
                                :class="[isInvalid('company_id') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('company_id')">
                                {{ invalidMessages('company_id')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="施設名"
                            :rules="[{ required:true, message: 'Please input ...'}]">
                            <a-input
                                v-model:value="facility.name"
                                :class="[isInvalid('name') ? 'border-danger' : '']"
                                :disabled="isLoading"/>
                            <span class="text-danger" v-if="isInvalid('name')">
                                {{ invalidMessages('name')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>
                    <a-col :span="24">
                        <a-form-item label="従業員数">
                            <a-input
                                v-model:value="facility.size_of_employee"
                                :class="[isInvalid('size_of_employee') ? 'border-danger' : '']"></a-input>
                            <span class="text-danger" v-if="isInvalid('size_of_employee')">
                                {{ invalidMessages('size_of_employee')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="都道府県"
                            :rules="[{ required:true}]">
                            <a-select
                                placeholder="都道府県"
                                v-model:value="facility.prefecture_id"
                                :options="prefecture"/>
                            <span class="text-danger" v-if="isInvalid('prefecture_id')">
                                {{ invalidMessages('prefecture_id')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item label="住所">
                            <a-input
                                placeholder="住所"
                                v-model:value="facility.address"
                                :class="[isInvalid('address') ? 'border-danger' : '']"
                                :disabled="isLoading"/>
                            <span class="text-danger" v-if="isInvalid('address')">
                                {{ invalidMessages('address')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item label="課題・悩み">
                            <a-textarea v-model:value="facility.concern" :disabled="isLoading"/>
                        </a-form-item>
                    </a-col>
                    <a-col :span="24">
                        <a-form-item label="ステータス">
                            <a-switch v-model:checked="status" @change="facility.status = status === true? 1 : 0"
                                      :disabled="isLoading">
                                <template #checkedChildren>
                                    有効
                                    <CheckOutlined/>
                                </template>
                                <template #unCheckedChildren>
                                    無効
                                    <CloseOutlined/>
                                </template>
                            </a-switch>
                            <span class="label" v-if="facility.status">有効</span>
                            <span class="label" v-else>無効</span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item label="メモ">
                            <a-textarea v-model:value="facility.memo" :disabled="isLoading"/>
                        </a-form-item>
                    </a-col>

                </a-col>
                <a-col class="px-3" :span="12">
                    <a-col :span="24">
                        <h5 class="border-left-title fw-bolder">連絡情報</h5>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item label="施設担当者名">
                            <a-input
                                v-model:value="facility.contact_person_name"
                                :class="[isInvalid('contact_person_name') ? 'border-danger' : '']"></a-input>
                            <span class="text-danger" v-if="isInvalid('contact_person_name')">
                                {{ invalidMessages('contact_person_name')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item label="役職">
                            <a-input
                                v-model:value="facility.job_title"
                                :class="[isInvalid('job_title') ? 'border-danger' : '']"></a-input>
                            <span class="text-danger" v-if="isInvalid('job_title')">
                                {{ invalidMessages('job_title')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item label="電話番号">
                            <a-input
                                v-model:value="facility.phone"
                                :class="[isInvalid('phone') ? 'border-danger' : '']"></a-input>
                            <span class="text-danger" v-if="isInvalid('phone')">
                                {{ invalidMessages('phone')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item label="メールアドレス: ">
                            <a-input
                                v-model:value="facility.email"
                                :class="[isInvalid('email') ? 'border-danger' : '']"></a-input>
                            <span class="text-danger" v-if="isInvalid('email')">
                                {{ invalidMessages('email')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                </a-col>
            </a-row>

            <div class="text-center">
                <a-button class="mx-2" :loading="isLoading" @click="$router.push({ name: 'facility.list' })" danger>
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
            @ok="deleteFacility"
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
    CloseOutlined,
    ExclamationCircleOutlined,
} from '@ant-design/icons-vue'
import {FacilityModel} from "@/model/facility";
import {message} from 'ant-design-vue';
import {CompanyModel} from "@/model/company";
import {agency} from "@/model/agency";
import {PrefectureModel} from "@/model/prefecture";

const defaultSelectOption = {
    name: '全て',
    id: null
}
export default {
    components: {
        CheckOutlined,
        CloseOutlined,
        ExclamationCircleOutlined,
    },
    data() {
        return {
            isLoading: true,
            labelCol: {style: {width: '120px'}},
            status: true,
            visible: false,
            facility: {
                id: 0,
                name: null,
                prefecture_id: 0,
                address: null,
                concern: null,
                memo: null,
                size_of_employee: null,
                status: true,
                contact_person_name: null,
                phone: null,
                email: null,
                job_title: null

            },
            companies: [],
            agencies: [],
            prefecture: [],
        }
    },
    async mounted() {
        let user = await this.$store.dispatch('auth/me')
        let facilityId = this.$router.currentRoute.value.params.facilityId
        FacilityModel.detail(facilityId).then(res => {
            if (res && res.success === false) {
                message.error(res.message)
                this.$router.push({name: 'NotFound'})
            } else {
                if (this.$store.state.role.role === 'admin' || (res.data.agency_id === user.agency_id || res.data.company_id === user.company_id)) {
                    this.facility = res.data
                } else {
                    message.error(`you don't have permission to access`)
                    this.$router.push({name: 'facility.list'})
                }
            }
            this.status = res.data.status === 1
            this.isLoading = false
        })
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
        }

        if (!this.$store.state.role.role.includes('company')) {
            if (!user.agency_id) {
                this.companies = [defaultSelectOption, ...await CompanyModel.getAllCompanyNames()]
            } else {
                this.companies = [defaultSelectOption, ...await CompanyModel.getCompanyByAgency(user.agency_id)]
            }
        }
    },
    methods: {
        update() {
            this.clearError()
            this.isLoading = true
            FacilityModel.update(this.facility.id, this.facility)
                .then(res => {
                    if (res?.response?.status === 422) {
                        message.error('Update Facility Fail!')
                        this.tryGetErrorResponse(res)
                    } else {
                        message.success(res.message)
                        this.$router.push({name: 'facility.list'})
                    }
                    this.isLoading = false
                })
        },

        deleteFacility() {
            this.isLoading = true
            FacilityModel.deleteFacility(this.facility.id)
                .then(res => {
                    if (res && res.status === true) {
                        message.success('成功に削除されました。')
                        this.$router.push({name: 'facility.list'})
                    } else {
                        message.error('に削除されました。')
                    }
                    this.isLoading = false
                })
        },

    }
}
</script>
<style>
.label {
    padding: 10px
}
</style>
