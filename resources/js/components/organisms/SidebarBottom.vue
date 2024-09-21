<script setup>
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <div
        class="fixed bottom-0 left-0 right-0 bg-white border border-gray-200 md:hidden"
    >
        <div class="container">
            <div class="flex items-center justify-around py-4 text-blue-950">
                <div>
                    <Link
                        class="p-2 hover:bg-blue-500 hover:text-white rounded-lg"
                        v-tooltip.top="'Dashboard'"
                        :class="{
                            'bg-blue-500 text-white':
                                $page.url === '/dashboard',
                        }"
                        href="/dashboard"
                    >
                        <i class="pi pi-th-large"></i>
                    </Link>
                </div>

                <div>
                    <Link
                        class="p-2 hover:bg-blue-500 hover:text-white rounded-lg"
                        v-tooltip.top="'Test'"
                        :class="{
                            'bg-blue-500 text-white': $page.url === '/tests',
                        }"
                        href="/tests"
                    >
                        <i class="pi pi-book"></i>
                    </Link>
                </div>

                <div>
                    <Link
                        class="p-2 hover:bg-blue-500 hover:text-white rounded-lg"
                        v-tooltip.top="'Leaderboard'"
                        :class="{
                            'bg-blue-500 text-white':
                                $page.url === '/leaderboard',
                        }"
                        href="/leaderboard"
                    >
                        <i class="pi pi-trophy"></i>
                    </Link>
                </div>

                <div>
                    <Link
                        class="p-2 hover:bg-blue-500 hover:text-white rounded-lg"
                        v-tooltip.top="'Performance'"
                        :class="{
                            'bg-blue-500 text-white':
                                $page.url === '/performance',
                        }"
                        href="/performance"
                    >
                        <i class="pi pi-chart-pie"></i>
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
