<script setup>
import Button from "primevue/button";

const visible = defineModel();
</script>

<template>
    <div
        class="fixed top-0 left-0 h-screen bg-white border border-gray-100 shadow-md z-10"
        :class="visible ? 'w-[60%] md:w-[35%] lg:w-[18%]' : ''"
    >
        <div class="p-3 md:p-4">
            <div
                class="flex items-center"
                :class="visible ? 'justify-between' : 'justify-center'"
            >
                <h6 class="text-blue-500 text-xl font-medium" v-show="visible">
                    bisaKok!
                </h6>
                <Button icon="pi pi-bars" @click="visible = !visible" />
            </div>
        </div>

        <div class="px-4 mt-4 text-blue-950">
            <ul class="list-none flex flex-col gap-y-5">
                <slot />
            </ul>
        </div>
    </div>
</template>
