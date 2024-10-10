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
import ToolbarTable from "../molecules/ToolbarTable.vue";
import { onMounted, ref, watch } from "vue";
import DialogForm from "../molecules/DialogForm.vue";
import DialogConfirmation from "../molecules/DialogConfirmation.vue";

const toast = useToast();
const dt = ref();
const loading = ref(true);
const classroom = ref({});
const classrooms = ref([{}, {}, {}, {}, {}]);
const selectedClassrooms = ref([]);
const addClassroomDialog = ref(false);
const editClassroomDialog = ref(false);
const deleteClassroomDialog = ref(false);
const deleteSelectedClassroomDialog = ref(false);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const getData = async () => {
    const response = await axios.get("/admin/classroom/shows").finally(() => {
        loading.value = false;
    });
    classrooms.value = response.data;
};

const sendData = (form, url, dialog, message) => {
    loading.value = true;
    form.clearErrors();
    form.post(url, {
        onSuccess: async () => {
            dialog.value = false;
            await getData();
            form.reset();
            toast.add({
                severity: "success",
                summary: "Success",
                detail: message,
                life: 3000,
            });
        },

        onError: () => {
            loading.value = false;
            toast.add({
                severity: "error",
                summary: "Error",
                detail: "Something went wrong!",
                life: 3000,
            });
        },
    });
};

onMounted(async () => {
    await getData();
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

watch(addClassroomDialog, (value) => {
    if (value === true) {
        form.reset();
        form.clearErrors();
    }
});

const addClassroom = async () => {
    sendData(
        form,
        "/admin/classroom/store",
        addClassroomDialog,
        "Success add classroom",
    );
};

const toggleEditDialog = (data) => {
    updateForm.id = data.id;
    updateForm.name = data.name;
    editClassroomDialog.value = true;
};

const editClassroom = async () => {
    sendData(
        updateForm,
        "/admin/classroom/edit",
        editClassroomDialog,
        "Success update classroom",
    );
};

const deleteSelectedClassroom = async () => {
    deleteForm.ids = selectedClassrooms.value.map((item) => item.id);
    sendData(
        deleteForm,
        "/admin/classroom/delete",
        deleteSelectedClassroomDialog,
        "Success delete selected classroom",
    );
};

const toggleDeleteDialog = (data) => {
    classroom.value = data;
    deleteForm.ids.push(classroom.value.id);
    deleteClassroomDialog.value = true;
};

const deleteClassroom = async () => {
    sendData(
        deleteForm,
        "/admin/classroom/delete",
        deleteClassroomDialog,
        "Successs delete classroom",
    );
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
        <ToolbarTable
            v-model:selectedItems="selectedClassrooms"
            v-model:addItemDialog="addClassroomDialog"
            v-model:deleteSelectedItemDialog="deleteSelectedClassroomDialog"
            :exportCSV="exportCSV"
        ></ToolbarTable>

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
            >
                <template v-if="loading" #body>
                    <Skeleton></Skeleton>
                </template>
            </Column>

            <Column
                field="name"
                header="Name"
                sortable
                style="min-width: 12rem"
            >
                <template v-if="loading" #body>
                    <Skeleton></Skeleton>
                </template>
            </Column>

            <Column
                field="students_count"
                header="Number of students"
                sortable
                style="min-width: 12rem"
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

    <!-- Add Classroom Dialog -->
    <DialogForm
        v-model="addClassroomDialog"
        header="Add Classroom"
        :confirm="addClassroom"
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
    </DialogForm>

    <!-- Edit Classroom Dialog  -->
    <DialogForm
        v-model="editClassroomDialog"
        header="Update Classroom"
        :confirm="editClassroom"
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
    </DialogForm>

    <!-- Delete Selected Classroom Dialog -->
    <DialogConfirmation
        v-model="deleteSelectedClassroomDialog"
        :confirm="deleteSelectedClassroom"
    >
        <div class="flex items-center gap-4">
            <i class="pi pi-exclamation-triangle !text-3xl" />
            <span>Are you sure you want to delete the selected products?</span>
        </div>
    </DialogConfirmation>

    <!-- Delete classroom Dialog -->
    <DialogConfirmation
        v-model="deleteClassroomDialog"
        :confirm="deleteClassroom"
    >
        <div class="flex items-center gap-4">
            <i class="pi pi-exclamation-triangle !text-3xl" />
            <span
                >Are you sure you want to delete class
                {{ classroom.name }}?</span
            >
        </div>
    </DialogConfirmation>
</template>
