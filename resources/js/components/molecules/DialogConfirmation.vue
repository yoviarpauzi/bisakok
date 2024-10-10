<script setup>
const dialog = defineModel();
const props = defineProps({
    confirm: Function,
});
</script>

<template>
    <Dialog
        v-model:visible="dialog"
        :style="{ width: '450px' }"
        header="Confirm"
        :modal="true"
    >
        <slot />

        <template #footer>
            <Button
                label="No"
                icon="pi pi-times"
                text
                @click="dialog = false"
            />
            <Button label="Yes" icon="pi pi-check" text @click="confirm" />
        </template>
    </Dialog>
</template>
