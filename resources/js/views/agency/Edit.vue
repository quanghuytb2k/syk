<template>
    <div class="d-flex justify-content-between">
        <div class="screen-title">代理店詳細</div>
    </div>
    <div class="content">
        <a-form :label-col="labelCol">
            <a-row :glutter="24">
                <a-col class="px-3" :span="12">
                    <a-col :span="24">
                        <h5 class="border-left-title fw-bolder">基本情報</h5>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="会社名"
                            :rules="[{ required:true}]">
                            <a-input
                                v-model:value="agency.name"
                                :class="[isInvalid('name') ? 'border-danger' : '']"
                                :disabled="isLoading"/>
                            <span class="text-danger" v-if="isInvalid('name')">
                                {{ invalidMessages('name')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24" class="d-flex ps-2em">
                        <a-form-item
                            class="w-100"
                            label="郵便番号"
                            :rules="[{ required:true}]">
                            <a-input
                                v-model:value="agency.post_code"
                            />
                            <span class="text-danger" v-if="isInvalid('post_code')">
                                {{ invalidMessages('post_code')[0] }}
                            </span>
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
                                v-model:value="agency.prefecture_id"
                                :disabled="isLoading"
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
                                v-model:value="agency.municipality"
                                :disabled="isLoading"
                                :class="[isInvalid('municipality') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('municipality')">
                                {{ invalidMessages('municipality')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24" class="ps-2em ms-lg-3 ps-custom">
                        <a-form-item class="offset-3">
                            <a-input v-model:value="agency.street" placeholder="丁目番地"></a-input>
                            <span class="text-danger" v-if="isInvalid('street')">
                                {{ invalidMessages('street')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24" class="ps-2em ms-lg-3 ps-custom">
                        <a-form-item class="offset-3">
                            <a-input v-model:value="agency.building" placeholder="建物名階数"></a-input>
                            <span class="text-danger" v-if="isInvalid('building')">
                                {{ invalidMessages('building')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item label="ステータス">
                            <a-switch v-model:checked="isChecked">
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
                            <a-textarea v-model:value="agency.memo"></a-textarea>
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
                        <a-form-item
                            label="担当者名">
                            <a-input v-model:value="agency.contact_person_name"></a-input>
                            <span class="text-danger" v-if="isInvalid('contact_person_name')">
                                {{ invalidMessages('contact_person_name')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="役職">
                            <a-input v-model:value="agency.job_title"></a-input>
                            <span class="text-danger" v-if="isInvalid('job_title')">
                                {{ invalidMessages('job_title')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="担当者電話番号:">
                            <a-input v-model:value="agency.phone"></a-input>
                            <span class="text-danger" v-if="isInvalid('phone')">
                                {{ invalidMessages('phone')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="担当者メールアドレス:">
                            <a-input
                                v-model:value="agency.mail"
                                :disabled="isLoading"
                                :class="[isInvalid('mail') ? 'border-danger' : '']"/>
                            <span class="text-danger" v-if="isInvalid('mail')">
                                {{ invalidMessages('mail')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                </a-col>
            </a-row>

            <div class="text-center">
                <a-button class="mx-2" :loading="isLoading" @click="$router.push({ name: 'agency.list' })" danger>
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
            @ok="deleteAgency"
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
import {agency} from "@/model/agency"
import {PrefectureModel} from "@/model/prefecture";
import {message} from "ant-design-vue";
import axios from "axios";
import {Confirm} from "notiflix";

export default {
    components: {
        ExclamationCircleOutlined,
        CheckOutlined,
        CloseOutlined
    },
    data() {
        return {
            isLoading: true,
            isChecked: true,
            labelCol: {span: 7},
            visible: false,
            agency: {
                name: '',
                prefecture_id: null,
                municipality: '',
                building: '',
                street: '',
                contact_person_name: '',
                job_title: '',
                phone: '',
                mail: '',
                memo: '',
                status: 1,
                post_code: null,
            },
            errors: {
                name: null,
                prefecture_id: null,
                municipality: null,
                mail: null
            },
            prefectures: []
        }
    },
    mounted() {
        let agencyId = this.$router.currentRoute.value.params.agencyId

        PrefectureModel.getAllPrefecture()
            .then(res => {
                let data = res.data.data
                this.prefectures = data.map(val => {
                    return {
                        label: val.prefecture_name,
                        value: val.id
                    }
                })
            })

        agency.detail(agencyId).then(resData => {
            if (resData && resData.success === false) {
                message.error(resData.message)
                this.$router.push({name: 'agency.list'})
            } else {
                this.agency = {
                    id: resData.data.id,
                    name: resData.data.name,
                    prefecture_id: resData.data.prefecture_id,
                    municipality: resData.data.municipality,
                    building: resData.data.building,
                    street: resData.data.street,
                    contact_person_name: resData.data.contact_person_name,
                    job_title: resData.data.job_title,
                    phone: resData.data.phone,
                    mail: resData.data.mail,
                    memo: resData.data.memo,
                    status: resData.data.status,
                    post_code: resData.data.post_code,
                }
                this.isChecked = resData.data.status == 1 ? true : false
            }

            this.isLoading = false
        })
    },
    methods: {
        async update() {
            this.clearError()
            this.isLoading = true
            this.agency.status = this.isChecked == true ? 1 : 0
            await agency.update(this.agency.id, this.agency).then((res) => {
                if (res?.response?.status === 422) {
                    message.error('Update Agency Fail!')
                    this.tryGetErrorResponse(res)
                } else {
                    message.success(res.message)
                    this.$router.push({name: 'agency.list'})
                }
                this.isLoading = false
            })
        },

        searchPostCode() {
            axios.get(`https://zipcloud.ibsnet.co.jp/api/search`, {
                params: {
                    zipcode: this.agency.post_code,
                }
            }).then(res => {
                if (res?.data?.status == 400) {
                    message.error(res?.data?.message);
                } else if (res?.data?.status == 500 || res?.data?.status == 404) {
                    message.error(res?.data?.message);
                } else {
                    let data = res?.data?.results;
                    this.prefectures.forEach(item => {
                        if (item.label == data[0]?.address1) {
                            this.agency.prefecture_id = item.value;
                        }
                    });
                    this.agency.municipality = data[0]?.address2;
                    this.agency.street = data[0]?.address3;
                }
            })
        },

        deleteAgency() {
            this.isLoading = true
            agency.deleteAgency(this.agency.id)
                .then(res => {
                    if (res && res.status === true) {
                        message.success('成功に削除されました。')
                        this.$router.push({name: 'agency.list'})
                    } else {
                        message.error('に削除されました。')
                    }
                    this.isLoading = false
                })
        },
    }
}
</script>

<style scoped>
@media (max-width: 1440px) {
    .ps-custom {
        padding-left: 1em !important;
    }
}

@media (max-width: 1024px) {
    .ps-custom {
        padding-left: 0 !important;
    }
}
</style>
