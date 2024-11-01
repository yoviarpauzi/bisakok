<script>
import Layout from "../templates/Admin.vue";

export default {
    layout: Layout,
};
</script>

<script setup>
import axios from "axios";
import { useForm } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";
import { FilterMatchMode } from "@primevue/core/api";
import { onMounted, ref, watch } from "vue";
import DialogForm from "../molecules/DialogForm.vue";
import DialogConfirmation from "../molecules/DialogConfirmation.vue";

const props = defineProps({
    test: Object,
});

const toast = useToast();
const dt = ref();
const loading = ref(true);
const test = ref({});
const question = ref({});
const questions = ref([{}, {}, {}, {}, {}]);
const selectedQuestion = ref([]);
const deleteQuestionDialog = ref(false);
const deleteSelectedQuestionDialog = ref(false);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const exportCSV = () => {
    dt.value.exportCSV();
};

const getData = async () => {
    const response = await axios
        .get(`/admin/tests/${props.test.id}/questions/shows`)
        .finally(() => {
            loading.value = false;
        });

    questions.value = response.data;
};

onMounted(async () => {
    await getData();
});

const downloadExample = () => {};

const handleUpload = (event) => {};
</script>

<template>
    <Button as="a" label="Back" icon="pi pi-arrow-left" href="/admin/tests" />

    <div class="flex flex-col gap-y-4 mt-4">
        <table class="table-auto border border-collapse border-slate-50 w-full">
            <tbody>
                <tr class="border border-slate-50">
                    <td class="border border-slate-250 p-2">Type</td>
                    <td class="border border-slate-250 p-2">
                        {{ props.test.type }}
                    </td>
                </tr>
                <tr class="border border-slate-50">
                    <td class="border border-slate-250 p-2">Course</td>
                    <td class="border border-slate-250 p-2">
                        {{ props.test.course.name }}
                    </td>
                </tr>
                <tr class="border border-slate-50">
                    <td class="border border-slate-250 p-2">Classroom</td>
                    <td class="border border-slate-250 p-2">
                        {{ props.test.classroom.name }}
                    </td>
                </tr>
                <tr class="border border-slate-50">
                    <td class="border border-slate-250 p-2">Study Period</td>
                    <td class="border border-slate-250 p-2">
                        {{ props.test.study_period }}
                    </td>
                </tr>
                <tr class="border border-slate-50">
                    <td class="border border-slate-250 p-2">Session</td>
                    <td class="border border-slate-250 p-2">
                        {{ props.test.session }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="card text-blue-950">
        <Toolbar class="my-6">
            <template #start>
                <Button
                    as="a"
                    label="New"
                    icon="pi pi-plus"
                    severity="success"
                    class="mr-2"
                    :href="`/admin/tests/${props.test.id}/questions/create`"
                />
                <Button
                    label="Delete"
                    icon="pi pi-trash"
                    severity="danger"
                    :disabled="!selectedQuestion || !selectedQuestion.length"
                    @click="deleteQuestionDialog = true"
                />
            </template>

            <template #end>
                <div class="flex gap-x-2">
                    <Button
                        label="Example"
                        icon="pi pi-download"
                        severity="info"
                        @click="downloadExample"
                    />

                    <FileUpload
                        mode="basic"
                        accept="text/csv"
                        :maxFileSize="1000000"
                        label="Import"
                        customUpload
                        chooseLabel="Import"
                        class="mr-2"
                        auto
                        :chooseButtonProps="{ severity: 'contrast' }"
                        @uploader="handleUpload($event)"
                    />
                </div>

                <Button
                    label="Export"
                    icon="pi pi-upload"
                    severity="help"
                    @click="exportCSV($event)"
                />
            </template>
        </Toolbar>

        <DataTable
            ref="dt"
            v-model:selection="selectedQuestion"
            :value="questions"
            :paginator="true"
            dataKey="id"
            :rows="5"
            :filters="filters"
            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink"
        >
            <template #header>
                <div class="flex flex-wrap gap-2 items-center justify-between">
                    <h4 class="m-0 text-xl font-medium">Manage Questions</h4>
                    <IconField>
                        <InputIcon>
                            <i class="pi pi-search" />
                        </InputIcon>
                        <InputText
                            v-model="filters['global'].value"
                            placeholder="Search..."
                        />
                    </IconField>
                </div>
            </template>

            <Column
                selectionMode="multiple"
                style="width: 3rem"
                :exportable="false"
            >
                <template v-if="loading" #body>
                    <Skeleton></Skeleton>
                </template>
            </Column>

            <Column :exportable="false" style="min-width: 12rem">
                <template v-if="loading" #body>
                    <Skeleton></Skeleton>
                </template>

                <template v-else #body="slotProps">
                    <Button
                        icon="pi pi-pencil"
                        outlined
                        rounded
                        class="mr-2"
                        @click="toggleEditDialog(slotProps.data)"
                    />
                    <Button
                        icon="pi pi-trash"
                        outlined
                        rounded
                        severity="danger"
                        @click="toggleDeleteDialog(slotProps.data)"
                    />
                </template>
            </Column>
        </DataTable>
    </div>
</template>
