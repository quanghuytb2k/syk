<template>
    <a-layout>
        <a-layout-sider v-model:collapsed="collapsed" :trigger="null" collapsible style="min-height: 100vh;">
            <div class="logo" style="color: #fff; font-size: 1em; padding: 10px;" @click="goToHomepage">
                <img src="../../images/logo.svg" alt="Logo">
                <span>省エネ笑太くん</span>
            </div>
            <a-menu :selectedKeys="this.$store.state.route.currentRoute" theme="dark" mode="inline">
                <a-menu-item key="home" @click="$router.push({ name: 'home' })">
                    <dashboard-outlined/>
                    <span>ダッシュボード</span>
                </a-menu-item>
                <a-menu-item key="usage" @click="$router.push({ name: 'usage.list' })">
                    <BarChartOutlined/>
                    <span>使用状況管理</span>
                </a-menu-item>
                <a-menu-item key="user" @click="$router.push({ name: 'user.list' })"
                             v-if="userMenuRole.includes(this.$store.state.role.role)">
                    <user-outlined/>
                    <span>アカウント管理</span>
                </a-menu-item>
                <a-menu-item key="agencies" @click="$router.push({ name: 'agency.list' })"
                             v-if="agencyMenuRole.includes(this.$store.state.role.role)">
                    <shop-outlined/>
                    <span>代理店管理</span>
                </a-menu-item>
                <a-menu-item key="company" @click="$router.push({ name: 'company.list' })"
                             v-if="companyMenuRole.includes(this.$store.state.role.role)">
                    <gold-outlined/>
                    <span>企業管理</span>
                </a-menu-item>
                <a-menu-item key="facility" @click="$router.push({ name: 'facility.list' })"
                             v-if="facilityMenuRole.includes(this.$store.state.role.role)">
                    <minus-square-outlined/>
                    <span>施設管理</span>
                </a-menu-item>
                <a-menu-item key="building" @click="$router.push({ name: 'building.list' })">
                    <container-outlined/>
                    <span>建屋管理</span>
                </a-menu-item>
                <a-menu-item key="energy_contract" @click="$router.push({ name: 'energy_contract.list' })">
                    <ThunderboltOutlined/>
                    <span>エネルギー契約管理</span>
                </a-menu-item>
                <a-menu-item key="equipment" @click="$router.push({name: 'equipment.list'})">
                    <tool-outlined/>
                    <span>設備管理</span>
                </a-menu-item>
                <a-menu-item key="maintain-company" @click="$router.push({name: 'maintain-company.list'})">
                    <api-outlined/>
                    <span>施工・メンテ会社管理</span>
                </a-menu-item>
                <a-menu-item key="map" @click="$router.push({name: 'map.list'})">
                    <heat-map-outlined/>
                    <span>図面管理</span>
                </a-menu-item>
                <a-menu-item key="alarm" @click="$router.push({name: 'alarm.list'})">
                    <bell-outlined/>
                    <span>アラーム</span>
                </a-menu-item>
            </a-menu>
            <a-menu class="mt-auto" v-model:selectedKeys="this.$store.state.route.currentRoute" theme="dark"
                    mode="inline">
                <a-menu-item key="master" @click="$router.push({ name: 'master.list' })"
                             v-if="this.$store.state.role.role === 'admin'">
                    <setting-outlined/>
                    <span>マスター設定</span>
                </a-menu-item>
                <a-menu-item key="logout" :disabled="isLoggingOut" @click="doLogout">
                    <loading-outlined v-if="isLoggingOut"/>
                    <logout-outlined v-else/>
                    <span>ログアウト</span>
                </a-menu-item>
            </a-menu>
        </a-layout-sider>
        <a-layout>
            <a-layout-header class="bg-light p-0 border-bottom d-flex justify-content-between">
                <div>
                    <menu-unfold-outlined v-if="collapsed" class="menu-trigger"
                                          @click="() => (collapsed = !collapsed)"/>
                    <menu-fold-outlined v-else class="menu-trigger" @click="() => (collapsed = !collapsed)"/>
                </div>
                <a-dropdown>
                    <div class="header-user-info">
                        <img
                            src="https://avatar-management--avatars.us-west-2.prod.public.atl-paas.net/default-avatar.png"
                            alt="">
                        <span>太郎</span>
                        <DownOutlined/>
                    </div>
                    <template #overlay>
                        <a-menu>
                            <a-menu-item>
                                <router-link :to="{ name: 'auth.changepass' }">パスワード変更</router-link>
                            </a-menu-item>
                        </a-menu>
                    </template>
                </a-dropdown>
            </a-layout-header>
            <a-layout-content class="p-4">
                <router-view></router-view>
            </a-layout-content>
        </a-layout>
    </a-layout>
</template>


<script>
import {
    BulbOutlined,
    UserOutlined,
    DashboardOutlined,
    MenuUnfoldOutlined,
    MenuFoldOutlined,
    ShopOutlined,
    GoldOutlined,
    MinusSquareOutlined,
    SettingOutlined,
    LogoutOutlined,
    LoadingOutlined,
    ContainerOutlined,
    ThunderboltOutlined,
    DownOutlined,
    BarChartOutlined,
    ToolOutlined,
    ApiOutlined,
    HeatMapOutlined,
    BellOutlined,
} from '@ant-design/icons-vue';

export default {
    components: {
        BulbOutlined,
        UserOutlined,
        DashboardOutlined,
        MenuUnfoldOutlined,
        MenuFoldOutlined,
        ShopOutlined,
        GoldOutlined,
        MinusSquareOutlined,
        SettingOutlined,
        LogoutOutlined,
        LoadingOutlined,
        ContainerOutlined,
        ThunderboltOutlined,
        DownOutlined,
        BarChartOutlined,
        ToolOutlined,
        ApiOutlined,
        HeatMapOutlined,
        BellOutlined,
    },
    data() {
        return {
            collapsed: false,
            isLoggingOut: false,
            userMenuRole: this.$store.state.accessRole.userManagementRole,
            agencyMenuRole: this.$store.state.accessRole.adminRole,
            companyMenuRole: this.$store.state.accessRole.agencyRole,
            facilityMenuRole: this.$store.state.accessRole.companyRole
        };
    },
    methods: {
        goToHomepage() {
            this.$router.push({name: 'home'});
        },
        async doLogout() {
            this.isLoggingOut = true;

            await this.$store.dispatch('auth/logout')
                .then(() => {
                    this.$store.commit('auth/removeUser')
                    this.$store.dispatch('role/removeRole')
                    this.$router.push({name: 'login'});
                })

            this.isLoggingOut = false;
        },
    },
}
</script>
<style>
.ant-select-item-option-content {
    max-width: 200px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>
