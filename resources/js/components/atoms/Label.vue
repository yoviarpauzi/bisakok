<script setup>
const props = defineProps({
    for: {
        type: String,
        default: "",
    },
});
</script>

<template>
    <label :for class="text-gray-600 font-medium">
        <slot />
    </label>
</template>
