<template>
    <div class="d-flex justify-content-between">
        <div class="screen-title">施設登録</div>
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
                            :rules="[{ required:true}]">
                            <a-input
                                v-model:value="facility.name"
                                :class="[isInvalid('name') ? 'border-danger' : '']"></a-input>
                            <span class="text-danger" v-if="isInvalid('name')">
                                {{ invalidMessages('name')[0] }}
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
                        <a-form-item label="住所" :rules="[{ required:true}]">
                            <a-input
                            placeholder="住所"
                                v-model:value="facility.address"
                                :class="[isInvalid('address') ? 'border-danger' : '']"></a-input>
                            <span class="text-danger" v-if="isInvalid('address')">
                                {{ invalidMessages('address')[0] }}
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
                        <a-form-item label="課題・悩み">
                            <a-textarea v-model:value="facility.concern"></a-textarea>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item label="ステータス">
                            <a-switch v-model:checked="status" @change="facility.status = status === true? 1 : 0"
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
                            <a-textarea v-model:value="facility.memo"></a-textarea>
                            <span class="text-danger" v-if="isInvalid('memo')">
                                {{ invalidMessages('memo')[0] }}
                            </span>
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
                <a-button class="mx-2" @click="$router.push({ name: 'facility.list' })" danger>キャンセル</a-button>
                <a-button class="mx-2" type="primary" :loading="isLoading" @click="create">登録</a-button>
            </div>

        </a-form>
    </div>
</template>

<script>
import {
    CheckOutlined,
    CloseOutlined,
} from '@ant-design/icons-vue'
import {FacilityModel} from "@/model/facility"
import {CompanyModel} from "@/model/company";
import {agency} from "@/model/agency";
import {message} from "ant-design-vue";
import {PrefectureModel} from "@/model/prefecture";

const defaultSelectOption = {
    name: '全て',
    id: null
}
export default {
    components: {
        CheckOutlined,
        CloseOutlined,
    },
    data() {
        return {
            isLoading: false,
            labelCol: { style: { width: '120px' } },
            facility: {
                name: '',
                prefecture_id: null,
                agency_id: null,
                company_id: null,
                address: '',
                concern: '',
                memo: '',
                size_of_employee: '',
            },
            status: true,
            companies: [],
            agencies: [],
            prefecture: [],
        }
    },
    methods: {
        async create()
        {
            this.clearError()
            this.isLoading = true
            await FacilityModel.create(this.facility).then(res => {
                if (res?.response?.status === 422) {
                    message.error('Create Facility Fail!')
                    this.tryGetErrorResponse(res)
                } else {
                    message.success(res.message)
                    this.$router.push({name: 'facility.list'})
                }
                this.isLoading = false;
            })
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
        let user = await this.$store.dispatch('auth/me')
        if (this.$store.state.role.role === 'admin') {
            this.agencies = await agency.getAllAgencyNames()
        } else {
            this.facility.agency_id = user.agency_id
        }

        if (!this.$store.state.role.role.includes('company')) {
            if (!user.agency_id) {
                this.companies = [defaultSelectOption, ...await CompanyModel.getAllCompanyNames()]
            } else {
                this.companies = [defaultSelectOption, ...await CompanyModel.getCompanyByAgency(user.agency_id)]
            }
        } else {
            this.facility.company_id = user.company_id
        }

    }
}
</script>
