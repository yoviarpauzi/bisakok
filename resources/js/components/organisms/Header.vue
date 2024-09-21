<script setup>
import { reactive, onMounted, ref } from "vue";
import axios from "axios";
import { Link } from "@inertiajs/vue3";

const visible = ref(false);
const user = reactive({});

const currentRoute = window.location.pathname;

onMounted(async () => {
    const response = await axios.get("/users/show");
    Object.assign(user, response.data);
});
</script>

<template>
    <header
        class="fixed top-0 w-screen bg-white shadow-md border border-gray-100 z-0 py-3"
    >
        <div class="container">
            <div
                class="flex items-center md:justify-end"
                :class="
                    currentRoute.includes('admin')
                        ? 'justify-end'
                        : 'justify-between'
                "
            >
                <slot />
                <div class="relative">
                    <button
                        class="flex items-center justify-center w-10 h-10 text-white bg-blue-500 rounded-full cursor-pointer hover:bg-blue-600"
                        type="button"
                        @click="visible = !visible"
                    >
                        {{ user.name?.charAt(0).toUpperCase() }}
                    </button>

                    <div
                        class="absolute top-11 right-0 w-36 rounded-md bg-white border border-gray-100 shadow-md"
                        v-show="visible"
                    >
                        <div class="p-3 py-4">
                            <ul
                                class="list-none flex flex-col gap-y-2 text-blue-950 text-sm"
                            >
                                <li>
                                    <Link
                                        class="flex items-center gap-x-2 p-2 rounded-md hover:bg-blue-500 hover:text-white cursor-pointer"
                                        @click="visible = false"
                                        :href="
                                            currentRoute.includes('admin')
                                                ? '/admin/profile'
                                                : '/profile'
                                        "
                                    >
                                        <i class="pi pi-user"></i>
                                        <span>Profile</span>
                                    </Link>
                                </li>

                                <li>
                                    <Link
                                        class="flex items-center gap-x-2 p-2 rounded-md hover:bg-blue-500 hover:text-white cursor-pointer"
                                        :href="
                                            currentRoute.includes('admin')
                                                ? '/admin/signout'
                                                : '/signout'
                                        "
                                    >
                                        <i class="pi pi-sign-out"></i>
                                        <span>Sign out</span>
                                    </Link>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
</template>
