<script setup>
const dialog = defineModel();
const props = defineProps({
    header: String,
    confirm: Function,
});
</script>

<template>
    <Dialog
        v-model:visible="dialog"
        :style="{ width: '450px' }"
        :header
        :modal="true"
    >
        <slot />

        <template #footer>
            <Button
                label="Cancel"
                icon="pi pi-times"
                text
                @click="dialog = false"
            />
            <Button label="Save" icon="pi pi-check" @click="confirm" />
        </template>
    </Dialog>
</template>
