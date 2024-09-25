<script>
import Layout from "../templates/Admin.vue";
import ToolbarTable from "../organisms/ToolbarTable.vue";

export default {
    layout: Layout,
};
</script>

<script setup>
import axios from "axios";
import { useForm, usePage } from "@inertiajs/vue3";
import { useToast } from "primevue/usetoast";
import { FilterMatchMode } from "@primevue/core/api";
import { onMounted, ref } from "vue";

const page = usePage();

const toast = useToast();
const dt = ref();
const loading = ref(false);
const classroom = ref({});
const classrooms = ref([]);
const selectedClassrooms = ref([]);
const addClassroomDialog = ref(false);
const editClassroomDialog = ref(false);
const deleteClassroomDialog = ref(false);
const deleteSelectedClassroomDialog = ref(false);
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
            classroom.value = { name: form.name, students_count: 0 };
            classrooms.value.push(classroom.value);
            addClassroomDialog.value = false;
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
    classroom.value = data;
    updateForm.id = classroom.value.id;
    updateForm.name = classroom.value.name;
    editClassroomDialog.value = true;
};

const editClassroom = async () => {
    updateForm.post("/admin/classroom/edit", {
        onSuccess: () => {
            loading.value = false;
            classrooms.value[findIndexById(classroom.value.id)].name =
                classroom.value.name;
            classroom.value = {};
            editClassroomDialog.value = false;
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
            deleteSelectedClassroomDialog.value = false;
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
    classroom.value = data;
    deleteForm.ids.push(classroom.value.id);
    deleteClassroomDialog.value = true;
};

const deleteClassroom = async () => {
    deleteForm.post("/admin/classroom/delete", {
        onSuccess: () => {
            classrooms.value = classrooms.value.filter(
                (val) => val.id !== classroom.value.id
            );
            deleteClassroomDialog.value = false;
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

    <!-- Add Classroom Dialog -->
    <Dialog
        v-model:visible="addClassroomDialog"
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
                @click="addClassroomDialog = false"
            />
            <Button label="Save" icon="pi pi-check" @click="addClassroom" />
        </template>
    </Dialog>

    <!-- Edit Classroom Dialog  -->
    <Dialog
        v-model:visible="editClassroomDialog"
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
                @click="addClassroomDialog = false"
            />
            <Button label="Save" icon="pi pi-check" @click="editClassroom" />
        </template>
    </Dialog>

    <!-- Delete Selected Classroom Dialog -->
    <Dialog
        v-model:visible="deleteSelectedClassroomDialog"
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
                @click="deleteClassroomDialog = false"
            />
            <Button
                label="Yes"
                icon="pi pi-check"
                text
                @click="deleteSelectedClassroom"
            />
        </template>
    </Dialog>

    <!-- Delete classroom Dialog -->
    <Dialog
        v-model:visible="deleteClassroomDialog"
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
                @click="deleteClassroomDialog = false"
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
