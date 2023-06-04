<template>
    <div class="d-flex justify-content-center my-5">
        <div class="card w-100" style="max-width: 500px;">
            <div class="card-body p-5">
                <h3 class="text-center">ログイン</h3>
                <div class="mb-3"></div>

                <a-alert v-if="isFailed" message="Your username or password is incorrect. Please try again."
                         type="warning" class="mb-3 alert-fail" closable/>

                <div class="d-flex flex-column gap-3">
                    <div class="form-group">
                        <label>
                            メールアドレス
                        </label>
                        <a-input v-model:value="formState.username" :disabled="isLoggingIn" @keyup.enter="doLogin">
                            <template #prefix>
                                <user-outlined type="user" style="opacity: 0.5"/>
                            </template>
                        </a-input>
                    </div>

                    <div class="form-group">
                        <label>
                            パスワード
                        </label>
                        <a-input-password v-model:value="formState.password" :disabled="isLoggingIn"
                                          @keyup.enter="doLogin">
                            <template #prefix>
                                <lock-outlined style="opacity: 0.5"/>
                            </template>
                        </a-input-password>
                        <div class="text-end">
                            <router-link :to="{ name: 'auth.forgotpass' }"><u>パスワード忘れ</u></router-link>
                        </div>
                    </div>

                    <div class="d-flex flex-column gap-2">
                        <a-button type="primary" size="large" block html-type="submit" :disabled="isLoggingIn" @click="doLogin">
                            ログイン
                        </a-button>

                        <a-button type="danger" size="large" block @click="goToRegister">
                            新規登録
                        </a-button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
import {
    UserOutlined,
    LockOutlined,
    LoadingOutlined,
    LoginOutlined,
    UserAddOutlined,
} from '@ant-design/icons-vue';

export default {
    components: {
        UserOutlined,
        LockOutlined,
        LoadingOutlined,
        LoginOutlined,
        UserAddOutlined,
    },
    data() {
        return {
            formState: {
                username: null,
                password: null,
            },

            isLoggingIn: false,
            isFailed: false,
        }
    },
    mounted() {
        if (this.$store.getters['auth/isLogged']) {
            this.$router.push({ name: 'home' });
            return;
        }
    },
    methods: {
        goToRegister() {
            this.$router.push({ name: 'register' });
        },
        async doLogin() {
            this.isLoggingIn = true;
            this.isFailed = false;

            try {
                await this.$store.dispatch(
                    'auth/login',
                    {
                        email: this.formState.username,
                        password: this.formState.password,
                    }
                ).then(res => {
                    if (res.data.data !== undefined) {
                        this.$store.commit('auth/setUser', res.data.data)
                        this.$store.dispatch('role/setRole')
                    }
                    this.$router.push({ name: 'home' });
                })
            } catch {
                this.isFailed = true;
            }

            this.isLoggingIn = false;
        },
    },
}
</script>

<style module>
.ant-form-item-with-help .ant-form-item-explain {
    min-height: 0px;
}
</style>
