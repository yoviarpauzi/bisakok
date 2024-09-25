<script setup>
import { defineModel } from "vue";

const props = defineProps({
    exportCSV: Function,
});

const selectedItems = defineModel("selectedItems");
const addItemDialog = defineModel("addItemDialog");
const deleteSelectedItemDialog = defineModel("deleteSelectedItemDialog");
</script>

<template>
    <Toolbar class="my-4">
        <template #start>
            <Button
                label="New"
                icon="pi pi-plus"
                severity="success"
                class="mr-2"
                @click="addItemDialog = true"
            />
            <Button
                label="Delete"
                icon="pi pi-trash"
                severity="danger"
                :disabled="!selectedItems || !selectedItems.length"
                @click="deleteSelectedItemDialog = true"
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
