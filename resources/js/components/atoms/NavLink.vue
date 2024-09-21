<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    href: {
        type: String,
        default: "",
    },
});
</script>

<template>
    <li>
        <Link
            class="flex items-center text-sm gap-x-2 p-3 hover:bg-blue-500 hover:text-white rounded-lg"
            :href
        >
            <slot />
        </Link>
    </li>
</template>
