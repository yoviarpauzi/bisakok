<script setup>
import { defineModel } from "vue";

const props = defineProps({
    exportCSV: Function,
});

const selectedClassrooms = defineModel("selectedClassrooms");
const addClassroomDialog = defineModel("addClassroomDialog");
const deleteSelectedClassroomDialog = defineModel(
    "deleteSelectedClassroomDialog"
);
</script>

<template>
    <Toolbar class="my-4">
        <template #start>
            <Button
                label="New"
                icon="pi pi-plus"
                severity="success"
                class="mr-2"
                @click="addClassroomDialog = true"
            />
            <Button
                label="Delete"
                icon="pi pi-trash"
                severity="danger"
                :disabled="!selectedClassrooms || !selectedClassrooms.length"
                @click="deleteSelectedClassroomDialog = true"
            />
        </template>

        <template #end>
            <slot />
            <Button
                label="Export"
                icon="pi pi-upload"
                severity="help"
                @click="exportCSV($event)"
            />
        </template>
    </Toolbar>
</template>
