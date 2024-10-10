<script setup>
import { ref } from "vue";
import Sidebar from "../organisms/Sidebar.vue";
import Header from "../organisms/Header.vue";
import SidebarItem from "../atoms/SidebarItem.vue";

const visible = ref(false);
</script>

<template>
    <div class="flex">
        <Sidebar v-model="visible" class="overflow-auto">
            <SidebarItem
                v-model="visible"
                href="/admin/dashboard"
                tooltip="Dashboard"
                :class="{
                    'bg-blue-500 text-white rounded-lg':
                        $page.url === '/admin/dashboard',
                }"
            >
                <i class="pi pi-th-large"></i>
                <span v-show="visible">Dashboard</span>
            </SidebarItem>

            <Divider />

            <SidebarItem
                v-model="visible"
                href="/admin/classroom"
                tooltip="Classroom"
                :class="{
                    'bg-blue-500 text-white rounded-lg':
                        $page.url === '/admin/classroom',
                }"
            >
                <i class="pi pi-graduation-cap"></i>
                <span v-show="visible">Classroom</span>
            </SidebarItem>

            <SidebarItem
                v-model="visible"
                href="/admin/courses"
                tooltip="Courses"
                :class="{
                    'bg-blue-500 text-white rounded-lg':
                        $page.url === '/admin/courses',
                }"
            >
                <i class="pi pi-book"></i>
                <span v-show="visible">Courses</span>
            </SidebarItem>

            <SidebarItem
                v-model="visible"
                href="/admin/admins"
                tooltip="Admins"
                :class="{
                    'bg-blue-500 text-white rounded-lg':
                        $page.url === '/admin/admins',
                }"
            >
                <i class="pi pi-users"></i>
                <span v-show="visible">Admins</span>
            </SidebarItem>

            <SidebarItem
                v-model="visible"
                href="/admin/students"
                tooltip="Students"
                :class="{
                    'bg-blue-500 text-white rounded-lg':
                        $page.url === '/admin/students',
                }"
            >
                <i class="pi pi-user"></i>
                <span v-show="visible">Students</span>
            </SidebarItem>

            <Divider />

            <SidebarItem
                v-model="visible"
                href="/admin/tests"
                tooltip="Test"
                :class="{
                    'bg-blue-500 text-white rounded-lg':
                        $page.url === '/admin/tests',
                }"
            >
                <i class="pi pi-book"></i>
                <span v-show="visible">Test</span>
            </SidebarItem>

            <SidebarItem
                v-model="visible"
                href="/admin/testsessions"
                tooltip="Test Session"
                :class="{
                    'bg-blue-500 text-white rounded-lg':
                        $page.url === '/admin/testsessions',
                }"
            >
                <i class="pi pi-clock"></i>
                <span v-show="visible">Test Session</span>
            </SidebarItem>

            <Divider />

            <SidebarItem
                v-model="visible"
                tooltip="Score Reports"
                href="/admin/report"
                :class="{
                    'bg-blue-500 text-white rounded-lg':
                        $page.url === '/admin/scorereports',
                }"
            >
                <i class="pi pi-chart-bar"></i>
                <span v-show="visible">Score Report</span>
            </SidebarItem>
        </Sidebar>

        <div>
            <Header></Header>

            <div class="w-screen container">
                <div
                    class="mt-24 ms-20 lg:ms-0"
                    :class="visible ? 'md:ms-66 lg:ms-56' : 'md:ms-16 lg:ms-4'"
                >
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
