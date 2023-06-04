<template>
    <div class="screen-title">アカウント登録</div>
    <div class="content">
        <div class="row">
            <div class="col-md-6">
                <div class="border-left-title mb-3">ログイン情報</div>
                <a-form :label-col="{ span: 6 }" :wrapper-col="{ span: 18 }">

                    <a-form-item label="氏名" :rules="[{ required: true }]">
                        <a-input v-model:value="user.name" :disabled="isLoading" />
                        <div v-if="isInvalid('user.name')">
                            <div v-for="message in invalidMessages('user.name')" class="invalid-feedback">{{ message }}</div>
                        </div>
                    </a-form-item>

                    <a-form-item label="カナ氏名" :rules="[{ required: true }]">
                        <a-input v-model:value="user.name_kana" :disabled="isLoading" />
                        <div v-if="isInvalid('user.name_kana')">
                            <div v-for="message in invalidMessages('user.name_kana')" class="invalid-feedback">{{ message }}</div>
                        </div>
                    </a-form-item>

                    <a-form-item label="メールアドレス" :rules="[{ required: true }]">
                        <a-input v-model:value="user.email" :disabled="isLoading" />
                        <div v-if="isInvalid('user.email')">
                            <div v-for="message in invalidMessages('user.email')" class="invalid-feedback">{{ message }}</div>
                        </div>
                    </a-form-item>

                    <a-form-item label="連絡先電話番号" :rules="[{ required: true }]">
                        <a-input v-model:value="user.phone" :disabled="isLoading" />
                        <div v-if="isInvalid('user.phone')">
                            <div v-for="message in invalidMessages('user.phone')" class="invalid-feedback">{{ message }}</div>
                        </div>
                    </a-form-item>

                    <a-form-item label="パスワード" :rules="[{ required: true }]">
                        <a-input-password v-model:value="user.password" :disabled="isLoading" />
                        <div v-if="isInvalid('user.password')">
                            <div v-for="message in invalidMessages('user.password')" class="invalid-feedback">{{ message }}</div>
                        </div>
                    </a-form-item>

                    <a-form-item label="担当区分" :rules="[{ required: true }]">
                        <a-select
                            style="display: block;"
                            :disabled="isLoading"
                            :options="energy_role"
                            v-model:value="user.energy_role"/>
                    </a-form-item>

                    <a-form-item label="部署名" :rules="[{ required: true }]">
                        <a-input v-model:value="user.department" :disabled="isLoading" />
                        <div v-if="isInvalid('user.department')">
                            <div v-for="message in invalidMessages('user.department')" class="invalid-feedback">{{ message }}</div>
                        </div>
                    </a-form-item>

                    <a-form-item label="役職" :rules="[{ required: true }]">
                        <a-input v-model:value="user.job_title" :disabled="isLoading" />
                        <div v-if="isInvalid('user.job_title')">
                            <div v-for="message in invalidMessages('user.job_title')" class="invalid-feedback">{{ message }}</div>
                        </div>
                    </a-form-item>

                    <a-form-item label="メモ">
                        <a-textarea v-model:value="user.memo" />
                        <div v-if="isInvalid('user.memo')">
                            <div v-for="message in invalidMessages('user.memo')" class="invalid-feedback">{{ message }}</div>
                        </div>
                    </a-form-item>

                </a-form>
            </div>
            <div class="col-md-6">
                <div class="border-left-title mb-3">権限</div>
                <a-form :label-col="{ span: 6 }" :wrapper-col="{ span: 18 }">

                    <a-form-item label="権限" :rules="[{ required: true }]">
                        <a-select
                            v-model:value="user.role"
                            :options="roles"
                            :fieldNames="{ label: 'name', value: 'name' }"
                            @change="handleSelectRole"
                            style="display: block;" :disabled="isLoading"/>
                        <div v-if="isInvalid('user.role')">
                            <div v-for="message in invalidMessages('user.role')" class="invalid-feedback">{{ message }}</div>
                        </div>
                    </a-form-item>

                    <a-form-item label="代理店" :rules="[{ required: true }]" v-if="this.$store.state.role.role === 'admin' && !selectHidden.agency">
                        <a-select v-model:value="user.agency_id" :options="agencies" :fieldNames="{ label: 'name', value: 'id' }" style="display: block;" :disabled="isLoading"></a-select>
                        <div v-if="isInvalid('user.agency_id')">
                            <div v-for="message in invalidMessages('user.agency_id')" class="invalid-feedback">{{ message }}</div>
                        </div>
                    </a-form-item>

                    <a-form-item label="企業" v-if="!this.$store.state.role.role.includes('company') && !selectHidden.company">
                        <a-select v-model:value="user.company_id" :options="companies" :fieldNames="{ label: 'name', value: 'id' }" style="display: block;" :disabled="isLoading"></a-select>
                        <div v-if="isInvalid('user.company_id')">
                            <div v-for="message in invalidMessages('user.company_id')" class="invalid-feedback">{{ message }}</div>
                        </div>
                    </a-form-item>

                    <a-form-item label="施設" v-if="!selectHidden.facility">
                        <a-select v-model:value="user.facility_id" :options="facilities" :fieldNames="{ label: 'name', value: 'id' }" style="display: block;" :disabled="isLoading"></a-select>
                        <div v-if="isInvalid('user.facility_id')">
                            <div v-for="message in invalidMessages('user.facility_id')" class="invalid-feedback">{{ message }}</div>
                        </div>
                    </a-form-item>

                </a-form>
            </div>
        </div>
        <a-space class="d-flex justify-content-center">
            <a-button type="danger" ghost :disabled="isLoading" @click="goToList">キャンセル</a-button>
            <a-button type="primary" :loading="isLoading" @click="doSubmit">登録</a-button>
        </a-space>
    </div>
</template>

<script>
import UserModel from '@/model/user';
import {agency, agency as AgencyModel} from '@/model/agency';
import { CompanyModel } from '@/model/company';
import { FacilityModel } from '@/model/facility';
import { message, Modal } from 'ant-design-vue';

const defaultSelectOption = {
    name: '全て',
    id: 0
}
export default {
    data() {
        return {
            isLoading: false,
            user: {
                energy_role: 1
            },
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
            selectHidden: {
                agency: false,
                company: false,
                facility: false
            }
        };
    },
    async mounted() {
        await this.getScreenData();
    },
    methods: {
        goToList() {
            this.$router.push({ name: 'user.list' });
        },
        async getScreenData() {
            this.isLoading = true;

            try {
                let user = await this.$store.dispatch('auth/me')

                if (this.$store.state.role.role === 'admin') {
                    this.agencies = [...await agency.getAllAgencyNames()]
                } else {
                    this.user.agency_id = user.agency_id
                }

                if (!this.$store.state.role.role.includes('company') && !this.$store.state.role.role.includes('facility')) {
                    if (!user.agency_id) {
                        this.companies = [...await CompanyModel.getAllCompanyNames()]
                    } else {
                        this.companies = [...await CompanyModel.getCompanyByAgency(user.agency_id)]
                    }
                } else {
                    this.user.company_id = user.company_id
                }

                if (!this.$store.state.role.role.includes('facility')) {
                    if (user.agency_id || user.company_id) {
                        this.facilities = [...await FacilityModel.getFacilityByParent(user.agency_id, user.company_id)]
                    } else {
                        this.facilities = [...await FacilityModel.getAllFacilityNames()]
                    }
                } else {
                    this.user.facility_id = user.facility_id
                }
            } catch (error) {
                if (this.tryGetErrorResponse(error)) {
                    message.error(this.errorMessage());
                }
            }

            this.roles = await UserModel.getAllRoles()

            this.isLoading = false;
        },
        async doSubmit() {
            this.isLoading = true;

            try {
                await UserModel.create(this.user);

                Modal.success({
                    title: 'Create completed',
                    content: `Create completed.`,
                    onOk: () => {
                        this.$router.push({ name: 'user.list' });
                    },
                });
            } catch (error) {
                if (this.tryGetErrorResponse(error)) {
                    message.error(this.errorMessage());
                }
            }

            this.isLoading = false;
        },

        handleSelectRole()
        {
            if (this.user.role === 'admin') {
                this.selectHidden = {
                    agency: true,
                    company: true,
                    facility: true
                }
                this.user.agency_id = null
                this.user.company_id = null
                this.user.facility_id = null
            } else if (this.user.role.includes('agency')) {
                this.selectHidden = {
                    company: true,
                    facility: true
                }
                this.user.company_id = null
                this.user.facility_id = null
            } else if (this.user.role.includes('company')) {
                this.selectHidden = {
                    facility: true
                }
                this.user.facility_id = null
            } else {
                this.selectHidden = {
                    agency: false,
                    company: false,
                    facility: false
                }
            }
        }
    }
}
</script>
