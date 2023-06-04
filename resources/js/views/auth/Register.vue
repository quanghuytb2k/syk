<template>
    <div class="my-5">
        <div class="card">
            <div class="card-body p-5">
                <h3 class="text-center"><b>新規登録</b></h3>
                <div class="mb-5"></div>

                <div class="row">

                    <div class="col-md-6 mb-3" style="border-right: 1px solid #D9D9D9">
                        <div style="font-size: 16px" class="border-left-title mb-3">ログイン情報</div>
                        <div class="d-flex flex-column gap-3">
                            <div class="row align-items-center">
                                <div class="col-md-4 text-end">
                                    <span class="text-danger">*</span>
                                    氏名:
                                </div>
                                <div class="col-md-8">
                                  <a-input placeholder="山田　太郎" pl v-model:value="user.name"
                                             :disabled="isSubmitting"/>
                                </div>
                                <div v-if="isInvalid('user.name')" class="offset-md-4 col-md-8">
                                    <div v-for="message in invalidMessages('user.name')" class="invalid-feedback">
                                        {{ message }}
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-md-4 text-end">
                                    <span class="text-danger">*</span>
                                    カナ氏名:
                                </div>
                                <div class="col-md-8">
                                    <a-input placeholder="ヤマダ　タロウ" v-model:value="user.name_kana"
                                             :disabled="isSubmitting"/>
                                </div>
                                <div v-if="isInvalid('user.name_kana')" class="offset-md-4 col-md-8">
                                    <div v-for="message in invalidMessages('user.name_kana')" class="invalid-feedback">
                                        {{ message }}
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-md-4 text-end">
                                    <span class="text-danger">*</span>
                                    メールアドレス:
                                </div>
                                <div class="col-md-8">
                                    <a-input placeholder="yamada@example.com" v-model:value="user.email"
                                             :disabled="isSubmitting"/>
                                </div>
                                <div v-if="isInvalid('user.email')" class="offset-md-4 col-md-8">
                                    <div v-for="message in invalidMessages('user.email')" class="invalid-feedback">
                                        {{ message }}
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-md-4 text-end">
                                    <span class="text-danger">*</span>
                                    メールアドレス(確認):
                                </div>
                                <div class="col-md-8">
                                    <a-input placeholder="Input" v-model:value="user.email_confirmation"
                                             :disabled="isSubmitting"/>
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-md-4 text-end">
                                    <span class="text-danger">*</span>
                                    パスワード:
                                </div>
                                <div class="col-md-8">
                                    <a-input-password placeholder="８文字以上、英数文字、大文字を含める"
                                                      v-model:value="user.password" :disabled="isSubmitting"/>
                                </div>
                                <div v-if="isInvalid('user.password')" class="offset-md-4 col-md-8">
                                    <div v-for="message in invalidMessages('user.password')" class="invalid-feedback">
                                        {{ message }}
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-md-4 text-end">
                                    <span class="text-danger">*</span>
                                    部署名:
                                </div>
                                <div class="col-md-8">
                                    <a-input placeholder="要整理" v-model:value="user.department"
                                             :disabled="isSubmitting"/>
                                </div>
                                <div v-if="isInvalid('user.department')" class="offset-md-4 col-md-8">
                                    <div v-for="message in invalidMessages('user.department')" class="invalid-feedback">
                                        {{ message }}
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-md-4 text-end">
                                    <span class="text-danger">*</span>
                                    担当区分:
                                </div>
                                <div class="col-md-8">
                                    <a-select
                                        style="display: block;"
                                        :disabled="isSubmitting"
                                        :options="energy_role"
                                        v-model:value="user.energy_role"/>
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-md-4 text-end">
                                    <span class="text-danger">*</span>
                                    役職:
                                </div>
                                <div class="col-md-8">
                                    <a-input v-model:value="user.job_title" :disabled="isSubmitting"/>
                                </div>
                                <div v-if="isInvalid('user.job_title')" class="offset-md-4 col-md-8">
                                    <div v-for="message in invalidMessages('user.job_title')" class="invalid-feedback">
                                        {{ message }}
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-md-4 text-end">
                                    <span class="text-danger">*</span>
                                    連絡先電話番号:
                                </div>
                                <div class="col-md-8">
                                    <a-input placeholder="080123456" v-model:value="user.phone"
                                             :disabled="isSubmitting"/>
                                </div>
                                <div v-if="isInvalid('user.phone')" class="offset-md-4 col-md-8">
                                    <div v-for="message in invalidMessages('user.phone')" class="invalid-feedback">
                                        {{ message }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div style="font-size: 16px" class="border-left-title mb-3">企業情報</div>
                        <div class="d-flex flex-column gap-3">
                            <div class="row align-items-center">
                                <div class="col-md-4 text-end">
                                    <span class="text-danger">*</span>
                                    組織名称:
                                </div>
                                <div class="col-md-8">
                                    <a-input v-model:value="company.name" :disabled="isSubmitting"/>
                                </div>
                                <div v-if="isInvalid('company.name')" class="offset-md-4 col-md-8">
                                    <div v-for="message in invalidMessages('company.name')" class="invalid-feedback">
                                        {{ message }}
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-md-4 text-end">
                                    <span class="text-danger">*</span>
                                    特定事業者区分:
                                </div>
                                <div class="col-md-8">
                                    <a-select v-model:value="company.business_type" style="display: block;"
                                              :disabled="isSubmitting">
                                        <a-select-option value="21">一般企業</a-select-option>
                                        <a-select-option value="22">福祉施設</a-select-option>
                                        <a-select-option value="23">学校</a-select-option>
                                        <a-select-option value="24">その他</a-select-option>
                                    </a-select>
                                </div>
                                <div v-if="isInvalid('company.business_type')" class="offset-md-4 col-md-8">
                                    <div v-for="message in invalidMessages('company.business_type')"
                                         class="invalid-feedback">{{ message }}
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-md-4 text-end">
                                    <span class="text-danger">*</span>
                                    業種:
                                </div>
                                <div class="col-md-8">
                                    <a-select v-model:value="company.insdustry" style="display: block;"
                                              :disabled="isSubmitting">
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
                                </div>
                                <div v-if="isInvalid('company.insdustry')" class="offset-md-4 col-md-8">
                                    <div v-for="message in invalidMessages('company.insdustry')"
                                         class="invalid-feedback">{{ message }}
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-md-4 text-end">
                                    <span class="text-danger">*</span>
                                    上場区分:
                                </div>
                                <div class="col-md-8">
                                    <a-select v-model:value="company.is_stock_listing" style="display: block;"
                                              :disabled="isSubmitting">
                                        <a-select-option value="0">非上表</a-select-option>
                                        <a-select-option value="1">上表</a-select-option>
                                    </a-select>
                                </div>
                                <div v-if="isInvalid('company.is_stock_listing')" class="offset-md-4 col-md-8">
                                    <div v-for="message in invalidMessages('company.is_stock_listing')"
                                         class="invalid-feedback">{{ message }}
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-md-4 text-end">
                                    <span class="text-danger">*</span>
                                    郵便番号:
                                </div>
                                <div class="col-md-8">
                                    <a-input-search placeholder="270-0023" v-model:value="company.post_code"
                                                    enter-button="検索" :disabled="isSubmitting"
                                                    @search="searchPostCode"/>
                                </div>
                                <div v-if="isInvalid('company.post_code')" class="offset-md-4 col-md-8">
                                    <div v-for="message in invalidMessages('company.post_code')"
                                         class="invalid-feedback">{{ message }}
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-md-4 text-end">
                                    <span class="text-danger">*</span>
                                    都道府県:
                                </div>
                                <div class="col-md-8">
                                    <a-select v-model:value="company.prefecture_id" :options="prefectures"
                                              style="display: block;" :disabled="isSubmitting"/>
                                </div>
                                <div v-if="isInvalid('company.prefecture_id')" class="offset-md-4 col-md-8">
                                    <div v-for="message in invalidMessages('company.prefecture_id')"
                                         class="invalid-feedback">{{ message }}
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-md-4 text-end">
                                    <span class="text-danger">*</span>
                                    住所:
                                </div>
                                <div class="col-md-8">
                                    <a-input placeholder="市区町村" v-model:value="company.municipality"
                                             :disabled="isSubmitting"/>
                                </div>
                                <div v-if="isInvalid('company.municipality')" class="offset-md-4 col-md-8">
                                    <div v-for="message in invalidMessages('company.municipality')"
                                         class="invalid-feedback">{{ message }}
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-md-4 text-end"></div>
                                <div class="col-md-8">
                                    <a-input placeholder="丁目番地" v-model:value="company.street"
                                             :disabled="isSubmitting"/>
                                </div>
                                <div v-if="isInvalid('company.street')" class="offset-md-4 col-md-8">
                                    <div v-for="message in invalidMessages('company.street')" class="invalid-feedback">
                                        {{ message }}
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-md-4 text-end"></div>
                                <div class="col-md-8">
                                    <a-input placeholder="建物名階数" v-model:value="company.building"
                                             :disabled="isSubmitting"/>
                                </div>
                                <div v-if="isInvalid('company.building')" class="offset-md-4 col-md-8">
                                    <div v-for="message in invalidMessages('company.building')"
                                         class="invalid-feedback">{{ message }}
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-md-4 text-end">
                                    <span class="text-danger">*</span>
                                    代表電話番号:
                                </div>
                                <div class="col-md-8">
                                    <a-input v-model:value="company.phone" :disabled="isSubmitting"/>
                                </div>
                                <div v-if="isInvalid('company.phone')" class="offset-md-4 col-md-8">
                                    <div v-for="message in invalidMessages('company.phone')" class="invalid-feedback">
                                        {{ message }}
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-md-4 text-end">
                                    ホームページ:
                                </div>
                                <div class="col-md-8">
                                    <a-input v-model:value="company.homepage" :disabled="isSubmitting"/>
                                </div>
                                <div v-if="isInvalid('company.homepage')" class="offset-md-4 col-md-8">
                                    <div v-for="message in invalidMessages('company.homepage')"
                                         class="invalid-feedback">{{ message }}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <div class="mb-3">
                    <div class="border-left-title mb-3">省エネ笑太くん契約約款</div>
                    <textarea class="form-control" rows="6" readonly style="font-size: inherit;">Text here</textarea>
                </div>

                <div class="mb-3">
                    <div class="border-left-title mb-3">省エネ笑太くんデータ取り扱い規約</div>
                    <textarea class="form-control" rows="6" readonly style="font-size: inherit;">Text here</textarea>
                </div>

                <div class="mb-3">
                    <div class="mb-2">
                        <a-checkbox v-model:checked="isAgreement1" class="fw-bold" :disabled="isSubmitting">
                            契約約款に同意します。
                            <span class="text-danger">*</span>
                        </a-checkbox>
                    </div>
                    <div class="mb-2">
                        <a-checkbox v-model:checked="isAgreement2" class="fw-bold" :disabled="isSubmitting">
                            データ取り扱い規約に同意します。*
                            <span class="text-danger">*</span>
                        </a-checkbox>
                    </div>
                </div>

                <a-space class="d-flex justify-content-center">
                    <a-button type="danger" ghost :disabled="isSubmitting" @click="goToLogin">戻る</a-button>
                    <a-button type="primary" @click="doRegister">登録</a-button>
                </a-space>
            </div>
        </div>
    </div>
</template>

<script>
import {
    UserOutlined,
    LockOutlined,
} from '@ant-design/icons-vue';
import {message, Modal} from 'ant-design-vue';
import {PrefectureModel as prefecture} from '@/model/prefecture';
import axios from "axios";

export default {
    components: {
        UserOutlined,
        LockOutlined,
    },
    data() {
        return {
            user: {
                energy_role: 1
            },
            company: {},
            prefectures: [],
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
            isSubmitting: false,
            isAgreement1: false,
            isAgreement2: false,

            errors: {},
        }
    },
    async mounted() {
        if (this.$store.getters['auth/isLogged']) {
            this.$router.push({name: 'home'});
            return;
        }

        await this.getPrefectures();
    },
    methods: {
        goToLogin() {
            this.$router.push({name: 'login'});
        },
        async doRegister() {
            this.isSubmitting = true;

            try {
                await this.$store.dispatch('auth/register', {
                    user: this.user,
                    company: this.company,
                });

                this.$router.push({name: 'auth.register-complete'});
            } catch (error) {
                if (this.tryGetErrorResponse(error)) {
                    message.error(this.errorMessage());
                }
            }

            this.isSubmitting = false;
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
                    this.prefectures.forEach(item => {
                        if (item.label == data[0]?.address1) {
                            this.company.prefecture_id = item.value;
                        }
                    });
                    this.company.municipality = data[0]?.address2;
                    this.company.street = data[0]?.address3;
                }
            })
        },

        async getPrefectures() {
            prefecture.getAllPrefecture()
                .then(res => {
                    let data = res.data.data
                    this.prefectures = data.map(val => {
                        return {
                            label: val.prefecture_name,
                            value: val.id
                        }
                    })
                })
        }
    },
}
</script>

<style scoped>
.text-end {
    font-size: 14px;
}
</style>
