<template>
    <div class="d-flex justify-content-center my-5">
        <div class="card w-100" style="max-width: 500px;">
            <div class="card-body p-5 text-center">
                <h4>仮登録が完了いたしました</h4>
                <div class="mb-5"></div>
                <p>登録したメールアドレスに、本登録のご案内が<br>届いているのでご確認ください。</p>
                <div class="mb-5"></div>
                <a-button type="primary" html-type="button" @click="goToLogin">
                    ログイン画面へ
                </a-button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    mounted() {
        if (this.$store.getters['auth/isLogged']) {
            this.$router.push({ name: 'home' });
            return;
        }
    },
    methods: {
        goToLogin() {
            this.$router.push({ name: 'login' });
        },
    },
}
</script>

<style module>
</style>
