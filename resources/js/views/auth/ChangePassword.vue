<template>
    <div class="d-flex justify-content-between">
        <div class="screen-title">パスワード変更</div>
    </div>
    <div class="content">
        <div class="row">
            <div class="col-xl-6">
                <a-form :model="formState" :label-col="{ span: 8 }" :wrapper-col="{ span: 16 }">
                    <a-form-item label="以前のパスワード" :rules="[{ required: true }]">
                        <a-input-password v-model:value="formState.old_password" :disabled="isLoading" />
                        <div v-if="isInvalid('old_password')">
                            <div v-for="message in invalidMessages('old_password')" class="invalid-feedback">{{ message }}</div>
                        </div>
                    </a-form-item>
                    <a-form-item label="新しいパスワード" :rules="[{ required: true }]">
                        <a-input-password v-model:value="formState.password" :disabled="isLoading" />
                        <div v-if="isInvalid('password')">
                            <div v-for="message in invalidMessages('password')" class="invalid-feedback">{{ message }}</div>
                        </div>
                    </a-form-item>
                    <a-form-item label="新しいパスワード(確認)" :rules="[{ required: true }]">
                        <a-input-password v-model:value="formState.password_confirmation" :disabled="isLoading" />
                    </a-form-item>
                    <a-form-item :wrapper-col="{ offset: 8, span: 16 }">
                        <a-button type="primary" html-type="button" :disabled="isLoading" @click="submit">パスワード設定</a-button>
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
                old_password: null,
                password: null,
                password_confirmation: null,
            },
        }
    },
    mounted() {

    },
    methods: {
        async submit() {
            this.isLoading = true;

            const res = await this.$store.dispatch('auth/changepass', this.formState);
            if (res?.response?.status == 422) {
                this.tryGetErrorResponse(res);
                message.error(this.errorMessage());
            } else {
                Modal.success({
                    title: 'Update password completed',
                    content: `Update password completed.`,
                    onOk: () => {
                        this.$router.push({ name: 'home' });
                    },
                });
            }

            this.isLoading = false;
        },
    },
}
</script>

<style module>
</style>
