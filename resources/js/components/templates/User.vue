<script setup>
import { ref } from "vue";
import Header from "../organisms/Header.vue";
import SidebarBottom from "../organisms/SidebarBottom.vue";
import SidebarItem from "../atoms/SidebarItem.vue";

const visible = ref(false);
</script>

<template>
    <div class="flex">
        <Sidebar v-model="visible" class="hidden md:block">
            <SidebarItem
                v-model="visible"
                tooltip="Dashboard"
                :class="{
                    'bg-blue-500 text-white rounded-lg':
                        $page.url === '/dashboard',
                }"
                href="/dashboard"
            >
                <i class="pi pi-th-large"></i>
                <span v-show="visible">Dashboard</span>
            </SidebarItem>

            <Divider />

            <SidebarItem
                v-model="visible"
                tooltip="Test"
                :class="{
                    'bg-blue-500 text-white rounded-lg': $page.url === '/tests',
                }"
                href="/tests"
            >
                <i class="pi pi-book"></i>
                <span v-show="visible">Test</span>
            </SidebarItem>

            <SidebarItem
                v-model="visible"
                tooltip="Leaderboard"
                :class="{
                    'bg-blue-500 text-white rounded-lg':
                        $page.url === '/leaderboard',
                }"
                href="/leaderboard"
            >
                <i class="pi pi-trophy"></i>
                <span v-show="visible">Leaderboard</span>
            </SidebarItem>

            <SidebarItem
                v-model="visible"
                tooltip="Performance"
                :class="{
                    'bg-blue-500 text-white rounded-lg':
                        $page.url === '/performance',
                }"
                href="/performance"
            >
                <i class="pi pi-chart-pie"></i>
                <span v-show="visible">Performance</span>
            </SidebarItem>
        </Sidebar>

        <div>
            <Header>
                <h6 class="md:hidden text-xl text-blue-500 font-medium">
                    bisaKok!
                </h6>
            </Header>

            <div class="w-screen container">
                <div
                    class="mt-20 mb-4 md:mb-0"
                    :class="visible ? 'md:ms-64 lg:ms-52' : 'md:ms-14 lg:ms-0'"
                >
                    <slot />
                </div>
            </div>
        </div>
    </div>

    <SidebarBottom />
</template>
