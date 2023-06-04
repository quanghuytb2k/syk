<template>
    <div class="d-flex justify-content-center my-5">
        <div class="card w-100" style="max-width: 800px;">
            <div class="card-body text-center p-5">
                <div class="screen-title">新パスワード設定仮が完了いたしました</div>
                <a-button type="primary" html-type="button" @click="goToLogin">ログイン画面へ</a-button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
        }
    },
    mounted() {
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
