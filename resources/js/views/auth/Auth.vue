<template>
    <a-layout class="layout" style="min-height: 100vh;">
        <a-layout-header style="background-color: #fff;">
            <div class="logo" @click="goToHomepage">
                <img src="../../../images/logo.svg" alt="Logo">
                <span>省エネ笑太くん</span>
            </div>
        </a-layout-header>
        <a-layout-content class="p-4">
            <router-view></router-view>
        </a-layout-content>
        <a-layout-footer class="mt-auto bottom-0 w-100">
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4">
                    <div class="text-center mb-3">Copyright © 2022 株式会社SYK</div>
                </div>
                <div class="col-md-4">
                    <div class="text-center text-md-end mb-3">
                        <a href="#" class="me-3"><u>sykホームページへ</u></a>
                        <a href="#"><u>個人情報保護方針</u></a>
                    </div>
                </div>
            </div>
        </a-layout-footer>
    </a-layout>
</template>

<script>
export default {
    methods: {
        goToHomepage() {
            this.$router.push({ name: 'home' });
        },
    },
}
</script>
