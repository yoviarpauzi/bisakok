<script>
import Layout from "../templates/Admin.vue";

export default {
    layout: Layout,
};
</script>

<script setup>
const props = defineProps({
    exams_id: Number,
});
</script>

<template>
    <Button
        as="a"
        label="Back"
        icon="pi pi-arrow-left"
        :href="`/admin/tests/${props.exams_id}/questions`"
    />
</template>
