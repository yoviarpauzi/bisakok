<script setup>
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    href: {
        type: String,
        default: "",
    },
    tooltip: {
        typpe: String,
        default: "",
    },
});

const visible = defineModel();
</script>

<template>
    <li>
        <Link
            class="flex items-center text-sm gap-x-2 p-3 hover:bg-blue-500 hover:text-white rounded-lg"
            :class="visible ? 'justify-stretch' : 'justify-center'"
            v-tooltip="visible ? '' : tooltip"
            placeholder="Right"
            @click="visible = false"
            :href
        >
            <slot />
        </Link>
    </li>
</template>
