<template>
    <div class="d-flex justify-content-center my-5">
        <div class="card w-100" style="max-width: 800px;">
            <div class="card-body p-5">
                <a-form :model="formState" :label-col="{ span: 8 }" :wrapper-col="{ span: 12 }" @finish="submit">
                    <a-form-item :wrapper-col="{ offset: 8, span: 16 }">
                        <div class="screen-title">新パスワード設定</div>
                    </a-form-item>
                    <a-form-item label="新パスワード" :rules="[{ required: true }]">
                        <a-input-password v-model:value="formState.password" :disabled="isLoading" />
                        <div v-if="isInvalid('email')">
                            <div v-for="message in invalidMessages('email')" class="invalid-feedback">{{ message }}</div>
                        </div>
                    </a-form-item>
                    <a-form-item label="新パスワード(確認)" :rules="[{ required: true }]">
                        <a-input-password v-model:value="formState.password_confirmation" :disabled="isLoading" />
                    </a-form-item>
                    <a-form-item :wrapper-col="{ offset: 10, span: 16 }">
                        <a-button type="primary" html-type="submit" :disabled="isLoading">パスワード設定</a-button>
                    </a-form-item>
                </a-form>
            </div>
        </div>
    </div>
</template>

<script>
import { Modal, message } from 'ant-design-vue';

export default {
    data() {
        return {
            isLoading: false,
            formState: {
                code: null,
                password: null,
                password_confirmation: null,
            },
        }
    },
    mounted() {
        this.getResetPassCode();
    },
    methods: {
        getResetPassCode() {
            const params = new URLSearchParams(window.location.search);
            this.formState.code = params.get('code');
        },

        async submit() {
            this.isLoading = true;

            const res = await this.$store.dispatch('auth/resetpass', this.formState);
            if (res?.response?.status == 422) {
                this.tryGetErrorResponse(res);
                message.error(this.errorMessage());
            } else {
                this.$router.push({ name: 'auth.resetpass-completed' });
            }

            this.isLoading = false;
        },
    },
}
</script>

<style module>
</style>
