/************* ✨ Codeium Command 🌟 *************/
<script>
import Layout from "../templates/Admin.vue";

export default {
    layout: Layout,
};
</script>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { useForm, usePage } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";
import { FilterMatchMode } from "@primevue/core/api";
import { onMounted, ref } from "vue";
import Toast from "primevue/toast";
import Toolbar from "primevue/toolbar";
import DataTable from "primevue/datatable";
import Button from "primevue/button";
import IconField from "primevue/iconfield";
import InputIcon from "primevue/inputicon";
import Column from "primevue/column";
import InputText from "primevue/inputtext";
import Dialog from "primevue/dialog";
import Message from "primevue/message";

const page = usePage();

const toast = useToast();
const dt = ref();
const loading = ref(false);
const classroom = ref({});
const classrooms = ref([]);
const selectedClassrooms = ref([]);
const addDialog = ref(false);
const editDialog = ref(false);
const deleteDialog = ref(false);
const deleteDialogs = ref(false);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

onMounted(async () => {
    const response = await axios.get("/admin/classroom/shows");
    classrooms.value = response.data;
});

const form = useForm({
    name: "",
});

const updateForm = useForm({
    id: 0,
    name: "",
});

const deleteForm = useForm({
    ids: [],
});

const addClassroom = async () => {
    loading.value = false;
    form.clearErrors();
    form.post("/admin/classroom/store", {
        onSuccess: () => {
            loading.value = false;
            const classroom = { name: form.name, students_count: 0 };
            classrooms.value.push(classroom);
            classroom.value = { name: form.name, students_count: 0 };
            classrooms.value.push(classroom.value);
            addDialog.value = false;
            form.reset();
            toast.add({
                severity: "success",
                summary: "Success",
                detail: "Classroom created successfully",
                life: 3000,
            });
        },
        onError: () => {
            loading.value = false;
        },
    });
};

const toggleEditDialog = (data) => {
    loading.value = true;
    updateForm.id = data.id;
    updateForm.name = data.name;
    classroom.value = data;
    updateForm.id = classroom.value.id;
    updateForm.name = classroom.value.name;
    editDialog.value = true;
};

const editClassroom = async () => {
    updateForm.post("/admin/classroom/edit", {
        onSuccess: () => {
            loading.value = false;
            classrooms.value = classrooms.value.map((item) => {
                if (item.id === updateForm.id) {
                    item.name = updateForm.name;
                }
                return item;
            });
            classrooms.value[findIndexById(classroom.value.id)].name =
                classroom.value.name;
            classroom.value = {};
            editDialog.value = false;
            updateForm.reset();
            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Classroom Updated",
                life: 3000,
            });
        },
        onError: () => {
            loading.value = false;
        },
    });
};

const deleteSelectedClassroom = async () => {
    loading.value = true;
    deleteForm.ids = selectedClassrooms.value.map((item) => item.id);
    deleteForm.post("/admin/classroom/delete", {
        onSuccess: () => {
            loading.value = false;
            classrooms.value = classrooms.value.filter(
                (val) => !selectedClassrooms.value.includes(val)
            );
            deleteDialog.value = false;
            selectedClassrooms.value = null;
            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Classroom Deleted",
                life: 3000,
            });
        },

        onError: () => {
            loading.value = false;
        },
    });
};

const toggleDeleteDialog = (data) => {
    loading.value = true;
    deleteForm.ids.push(data.id);
    classroom.value = data;
    deleteForm.ids.push(classroom.value.id);
    deleteDialog.value = true;
};

const deleteClassroom = async () => {
    deleteForm.post("/admin/classroom/delete", {
        onSuccess: () => {
            classrooms.value = classrooms.value.filter(
                (val) => val.id !== deleteForm.ids[0]
            );
            deleteDialog.value = false;
            classroom.value = {};
            loading.value = false;
            deleteForm.reset();
            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Classroom Deleted",
                life: 3000,
            });
        },
        onError: () => {
            loading.value = false;
            toast.add({
                severity: "success",
                summary: "Successful",
                detail: "Classroom Deleted",
                life: 3000,
            });
        },
        onError: () => {
            loading.value = false;
        },
    });
};

const findIndexById = (id) => {
    let index = -1;
    for (let i = 0; i < classrooms.value.length; i++) {
        if (classrooms.value[i].id === id) {
            index = i;
            break;
        }
    }

    return index;
};

const exportCSV = () => {
    dt.value.exportCSV();
};
</script>

<template>
    <Toast></Toast>
    <div class="flex items-center gap-x-2 text-2xl font-medium text-blue-950">
        <i class="pi pi-graduation-cap"></i>
        <h5>Classroom</h5>
    </div>

    <div class="card text-blue-950">
        <Toolbar class="my-4">
            <template #start>
                <Button
                    label="New"
                    icon="pi pi-plus"
                    severity="success"
                    class="mr-2"
                    @click="addDialog = true"
                />
                <Button
                    label="Delete"
                    icon="pi pi-trash"
                    severity="danger"
                    :disabled="
                        !selectedClassrooms || !selectedClassrooms.length
                    "
                    @click="deleteDialog = true"
                />
            </template>

            <template #end>
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
            v-model:selection="selectedClassrooms"
            :value="classrooms"
            dataKey="id"
            :paginator="true"
            :rows="10"
            :filters="filters"
            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink"
        >
            <template #header>
                <div class="flex flex-wrap gap-2 items-center justify-between">
                    <h4 class="m-0 text-xl font-medium">Manage Classroom</h4>
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
            ></Column>

            <Column
                field="name"
                header="Name"
                sortable
                style="min-width: 12rem"
            ></Column>

            <Column
                field="students_count"
                header="Number of students"
                sortable
                style="min-width: 12rem"
            ></Column>

            <Column :exportable="false" style="min-width: 12rem">
                <template #body="slotProps">
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

    <Dialog
        v-model:visible="addDialog"
        :style="{ width: '450px' }"
        header="Add Classroom"
        :modal="true"
    >
        <div class="flex flex-col gap-y-2">
            <label for="name">Name</label>
            <InputText v-model="form.name" />
            <template v-if="form.errors.name">
                <Message severity="error">
                    {{ form.errors.name }}
                </Message>
            </template>
        </div>

        <template #footer>
            <Button
                label="Cancel"
                icon="pi pi-times"
                text
                @click="addDialog = false"
            />
            <Button label="Save" icon="pi pi-check" @click="addClassroom" />
        </template>
    </Dialog>

    <Dialog
        v-model:visible="editDialog"
        :style="{ width: '450px' }"
        header="Update Classroom"
        :modal="true"
    >
        <div class="flex flex-col gap-y-2">
            <label for="name">Name</label>
            <InputText v-model="updateForm.name" />
            <template v-if="updateForm.errors.name">
                <Message severity="error">
                    {{ updateForm.errors.name }}
                </Message>
            </template>
        </div>

        <template #footer>
            <Button
                label="Cancel"
                icon="pi pi-times"
                text
                @click="editDialog = false"
            />
            <Button label="Save" icon="pi pi-check" @click="editClassroom" />
        </template>
    </Dialog>

    <Dialog
        v-model:visible="deleteDialogs"
        :style="{ width: '450px' }"
        header="Confirm"
        :modal="true"
    >
        <div class="flex items-center gap-4">
            <i class="pi pi-exclamation-triangle !text-3xl" />
            <span>Are you sure you want to delete the selected products?</span>
        </div>

        <template #footer>
            <Button
                label="No"
                icon="pi pi-times"
                text
                @click="deleteDialog = false"
            />
            <Button
                label="Yes"
                icon="pi pi-check"
                text
                @click="deleteSelectedClassroom"
            />
        </template>
    </Dialog>

    <Dialog
        v-model:visible="deleteDialog"
        :style="{ width: '450px' }"
        header="Confirm"
        :modal="true"
    >
        <div class="flex items-center gap-4">
            <i class="pi pi-exclamation-triangle !text-3xl" />
            <span
                >Are you sure you want to delete class
                {{ classroom.name }}?</span
            >
        </div>

        <template #footer>
            <Button
                label="No"
                icon="pi pi-times"
                text
                @click="deleteDialog = false"
            />
            <Button
                label="Yes"
                icon="pi pi-check"
                text
                @click="deleteClassroom"
            />
        </template>
    </Dialog>
</template>

/****** 88cef8e7-a3c6-4235-a46b-3857a49ff949 *******/
