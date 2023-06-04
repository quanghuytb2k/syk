<template>
    <div class="d-flex justify-content-between">
        <div class="screen-title">代理店登録</div>
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

                    <a-col :span="24" class="d-flex ps-2em ms-2">
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
                                v-model:value="agency.prefecture_id"
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
                            <a-input placeholder="市区町村" v-model:value="agency.municipality"/>
                            <span class="text-danger" v-if="isInvalid('municipality')">
                                {{ invalidMessages('municipality')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item class="offset-4">
                            <a-input placeholder="丁目番地" v-model:value="agency.street"/>
                            <span class="text-danger" v-if="isInvalid('street')">
                                {{ invalidMessages('street')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item class="offset-4">
                            <a-input placeholder="建物名階数" v-model:value="agency.building"/>
                        </a-form-item>
                        <span class="text-danger" v-if="isInvalid('building')">
                                {{ invalidMessages('building')[0] }}
                            </span>
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
                            label="担当者電話番号">
                            <a-input v-model:value="agency.phone"></a-input>
                            <span class="text-danger" v-if="isInvalid('phone')">
                                {{ invalidMessages('phone')[0] }}
                            </span>
                        </a-form-item>
                    </a-col>

                    <a-col :span="24">
                        <a-form-item
                            label="担当者メールアドレス">
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
                <a-button class="mx-2" @click="$router.push({ name: 'agency.list' })" danger>キャンセル</a-button>
                <a-button class="mx-2" type="primary" :loading="isLoading" @click="create">登録</a-button>
            </div>

        </a-form>
    </div>
</template>

<script>
import {agency} from "@/model/agency"
import {PrefectureModel} from "@/model/prefecture";
import {message} from "ant-design-vue";
import axios from 'axios';

export default {
    data() {
        return {
            isLoading: false,
            labelCol: {span: 8},
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
            prefectures: []
        }
    },
    mounted() {
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
    },
    methods: {
        create() {
            this.clearError()
            this.isLoading = true
            agency.create(this.agency).then((res) => {
                if (res?.response?.status === 422) {
                    message.error('Create Agency Fail!')
                    this.tryGetErrorResponse(res)
                } else {
                    message.success(res.message)
                    this.$router.push({name: 'agency.list'})
                }
            })
            this.isLoading = false;
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
