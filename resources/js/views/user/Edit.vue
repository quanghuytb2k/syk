<template>
    <div class="screen-title">アカウント詳細</div>
    <div class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="border-left-title mb-3">ログイン情報</div>
                <a-form :label-col="{ span: 6 }" :wrapper-col="{ span: 18 }">

                    <a-form-item label="氏名" :rules="[{ required: true }]">
                        <a-input v-model:value="user.name" :disabled="isLoading"/>
                        <span class="text-danger" v-if="isInvalid('name')">
                                {{ invalidMessages('name')[0] }}
                            </span>
                    </a-form-item>

                    <a-form-item label="カナ氏名" :rules="[{ required: true }]">
                        <a-input v-model:value="user.name_kana" :disabled="isLoading"/>
                        <span class="text-danger" v-if="isInvalid('name_kana')">
                                {{ invalidMessages('name_kana')[0] }}
                            </span>
                    </a-form-item>

                    <a-form-item label="メールアドレス" :rules="[{ required: true }]">
                        <a-input v-model:value="user.email" :disabled="isLoading" readonly="readonly"/>
                        <span class="text-danger" v-if="isInvalid('email')">
                                {{ invalidMessages('email')[0] }}
                            </span>
                    </a-form-item>

                    <a-form-item label="連絡先電話番号" :rules="[{ required: true }]">
                        <a-input v-model:value="user.phone" :disabled="isLoading"/>
                        <span class="text-danger" v-if="isInvalid('phone')">
                                {{ invalidMessages('phone')[0] }}
                            </span>
                    </a-form-item>

                    <a-form-item label="パスワード" :rules="[{ required: true }]" hidden>
                        <a-input-password v-model:value="user.password" :disabled="isLoading"/>
                        <span class="text-danger" v-if="isInvalid('password')">
                                {{ invalidMessages('password')[0] }}
                            </span>
                    </a-form-item>

                    <a-form-item label="担当区分" :rules="[{ required: true }]">
                        <a-select
                            style="display: block;"
                            :disabled="isLoading"
                            v-model:value="user.energy_role"
                            :options="energy_role"/>
                    </a-form-item>

                    <a-form-item label="部署名" :rules="[{ required: true }]">
                        <a-input v-model:value="user.department" :disabled="isLoading"/>
                        <span class="text-danger" v-if="isInvalid('department')">
                                {{ invalidMessages('department')[0] }}
                            </span>
                    </a-form-item>

                    <a-form-item label="役職" :rules="[{ required: true }]">
                        <a-input v-model:value="user.job_title" :disabled="isLoading"/>
                        <span class="text-danger" v-if="isInvalid('job_title')">
                                {{ invalidMessages('job_title')[0] }}
                            </span>
                    </a-form-item>

                    <a-col :span="24">
                        <a-form-item label="ステータス　:">
                            <a-switch v-model:checked="status" @change="user.status = status === true? 2 : 0">
                                <template #checkedChildren>
                                    <CheckOutlined/>
                                </template>
                                <template #unCheckedChildren>
                                    <CloseOutlined/>
                                </template>
                            </a-switch>
                        </a-form-item>
                    </a-col>

                    <a-form-item label="メモ">
                        <a-textarea v-model:value="user.memo"/>
                        <span class="text-danger" v-if="isInvalid('memo')">
                                {{ invalidMessages('memo')[0] }}
                            </span>
                    </a-form-item>

                </a-form>
            </div>
            <div class="col-md-6">
                <div class="border-left-title mb-3">権限</div>
                <a-form :label-col="{ span: 6 }" :wrapper-col="{ span: 18 }">

                    <a-form-item label="権限" :rules="[{ required: true }]">
                        <a-select v-model:value="user.role" :options="roles"
                                  :fieldNames="{ label: 'name', value: 'name' }" style="display: block;"
                                  :disabled="isLoading"></a-select>
                        <span class="text-danger" v-if="isInvalid('role')">
                                {{ invalidMessages('role')[0] }}
                            </span>
                    </a-form-item>

                    <a-form-item label="代理店" :rules="[{ required: true }]"
                                 v-if="this.$store.state.role.role === 'admin'">
                        <a-select v-model:value="user.agency_id" :options="agencies"
                                  :fieldNames="{ label: 'name', value: 'id' }" style="display: block;"
                                  :disabled="isLoading"></a-select>
                        <span class="text-danger" v-if="isInvalid('agency_id')">
                                {{ invalidMessages('agency_id')[0] }}
                            </span>
                    </a-form-item>

                    <a-form-item label="企業" v-if="!this.$store.state.role.role.includes('company')">
                        <a-select v-model:value="user.company_id" :options="companies"
                                  :fieldNames="{ label: 'name', value: 'id' }" style="display: block;"
                                  :disabled="isLoading"></a-select>
                        <span class="text-danger" v-if="isInvalid('company_id')">
                                {{ invalidMessages('company_id')[0] }}
                            </span>
                    </a-form-item>

                    <a-form-item label="施設">
                        <a-select v-model:value="user.facility_id" :options="facilities"
                                  :fieldNames="{ label: 'name', value: 'id' }" style="display: block;"
                                  :disabled="isLoading"></a-select>
                        <span class="text-danger" v-if="isInvalid('facility_id')">
                                {{ invalidMessages('facility_id')[0] }}
                            </span>
                    </a-form-item>

                </a-form>
            </div>
        </div>
        <a-space class="d-flex justify-content-center">
            <a-button danger ghost :disabled="isLoading" @click="goToList">キャンセル</a-button>
            <a-button class="mx-2" type="primary" @click="deleteUser" :loading="isLoading" danger>削除</a-button>
            <a-button type="primary" :loading="isLoading" @click="doSubmit">更新</a-button>
        </a-space>
    </div>
</template>

<script>
import UserModel from '@/model/user';
import {agency as AgencyModel} from '@/model/agency';
import {CompanyModel} from '@/model/company';
import {FacilityModel} from '@/model/facility';
import {message, Modal} from 'ant-design-vue';
import {
    CheckOutlined,
    CloseOutlined,
} from '@ant-design/icons-vue'
import {Confirm} from "notiflix";
import {agency} from "@/model/agency";

export default {
    components: {
        CheckOutlined,
        CloseOutlined,
    },
    data() {
        return {
            isLoading: false,
            user: {},
            agencies: [],
            companies: [],
            facilities: [],
            roles: [],
            energy_role: [
                {
                    label: "エネルギー管理者",
                    value: 1
                },
                {
                    label: "エネルギー担当者",
                    value: 2
                },
                {
                    label: "その他",
                    value: 3
                }
            ],
            status: false
        };
    },
    async mounted() {
        await this.getScreenData();
        await this.getUserData();
    },
    methods: {
        goToList() {
            this.$router.push({name: 'user.list'});
        },
        async getScreenData() {
            this.isLoading = true;

            try {
                let user = await this.$store.dispatch('auth/me')

                if (this.$store.state.role.role === 'admin') {
                    this.agencies = [...await agency.getAllAgencyNames()]
                }

                if (!this.$store.state.role.role.includes('company') && !this.$store.state.role.role.includes('facility')) {
                    if (!user.agency_id) {
                        this.companies = [...await CompanyModel.getAllCompanyNames()]
                    } else {
                        this.companies = [...await CompanyModel.getCompanyByAgency(user.agency_id)]
                    }
                }

                if (!this.$store.state.role.role.includes('facility')) {
                    if (user.agency_id || user.company_id) {
                        this.facilities = [...await FacilityModel.getFacilityByParent(user.agency_id, user.company_id)]
                    } else {
                        this.facilities = [...await FacilityModel.getAllFacilityNames()]
                    }
                }
            } catch (error) {
                if (this.tryGetErrorResponse(error)) {
                    message.error(this.errorMessage());
                }
            }

            this.roles = await UserModel.getAllRoles()

            this.isLoading = false;
        },
        async getUserData() {
            this.isLoading = true;

            try {
                let currentUser = await this.$store.dispatch('auth/me')
                await UserModel.detail(this.$route.params.id)
                    .then(res => {
                        if (this.$store.state.role.role === 'admin' || (currentUser.agency_id === res.agency_id || currentUser.company_id === res.company_id)) {
                            this.user = res
                            this.status = this.user.status === 2
                        } else {
                            message.error(`you don't have permission to access`)
                            this.$router.push({name: 'user.list'})
                        }
                    })
            } catch (error) {
                if (this.tryGetErrorResponse(error)) {
                    message.error(this.errorMessage());
                }
            }

            this.isLoading = false;
        },
        async doSubmit() {
            this.isLoading = true;
            UserModel.update(this.$route.params.id, {
                user: this.user,
            }).then((res) => {
                if (res?.response?.status === 422 || res?.response?.status === 500) {
                    message.error('Update User Fail!')
                    this.tryGetErrorResponse(res)
                } else {
                    message.success(res.message)
                    this.$router.push({name: 'user.list'})
                }
            })
            this.isLoading = false;
        },
        deleteUser() {
            Confirm.show(
                '操作確認',
                '削除してもよろしいでしょうか?',
                'はい',
                'いいえ',
                () => {
                    this.isLoading = true
                    UserModel.deleteUser(this.$route.params.id)
                        .then(res => {
                            if (res && res.status === true) {
                                message.success('成功に削除されました。')
                                this.$router.push({name: 'user.list'})
                            } else {
                                message.error('に削除されました。')
                            }
                            this.isLoading = false
                        })
                },
                () => {
                },
                {
                    titleColor: '#1890ff',
                    okButtonBackground: 'red'
                }
            )
        },
    }
}
</script>
