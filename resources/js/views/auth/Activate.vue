<template>
    <div class="d-flex justify-content-center my-5">
        <div class="card w-100" style="max-width: 500px;">
            <div class="card-body p-5 text-center">
                <div v-if="isLoading">
                    <a-spin />
                </div>
                <div v-else>
                    <h4 v-if="isActivated">本登録が完了いたしました</h4>
                    <h4 v-else>Activate error. Please try again later.</h4>
                    <div class="mb-5"></div>
                    <a-button type="primary" html-type="button" @click="goToLogin">
                        ログイン画面へ
                    </a-button>
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
import { message } from 'ant-design-vue';

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
            isLoading: false,
            isActivated: false,
        }
    },
    mounted() {
        if (this.$store.getters['auth/isLogged']) {
            this.$router.push({ name: 'home' });
            return;
        }

        this.submitActivationCode();
    },
    methods: {
        async submitActivationCode() {
            this.isLoading = true;

            const params = new URLSearchParams(window.location.search);
            const activationCode = params.get('code');

            try {
                await this.$store.dispatch(
                    'auth/activate',
                    {
                        code: activationCode,
                    }
                );

                this.isActivated = true;
            } catch {
                message.error('Activate error. Please try again later.');
            }

            this.isLoading = false;
        },

        goToLogin() {
            this.$router.push({ name: 'login' });
        },
    },
}
</script>

<style module>
</style>
