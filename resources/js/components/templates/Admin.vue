<script setup>
import { ref } from "vue";
import Sidebar from "../organisms/Sidebar.vue";
import Header from "../organisms/Header.vue";
import SidebarItem from "../atoms/SidebarItem.vue";
import Divider from "primevue/divider";

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
                href="/admin/classes"
                tooltip="Classes"
                :class="{
                    'bg-blue-500 text-white rounded-lg':
                        $page.url === '/admin/classes',
                }"
            >
                <i class="pi pi-graduation-cap"></i>
                <span v-show="visible">Classes</span>
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
        </Sidebar>

        <div>
            <Header></Header>

            <div class="container">
                <div class="mt-20 ms-14">
                    <slot />
                </div>
            </div>
        </div>
    </div>
</template>
