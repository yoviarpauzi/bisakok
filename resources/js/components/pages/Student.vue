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
import FormStudent from "../organisms/FormStudent.vue";
import DialogConfirmation from "../molecules/DialogConfirmation.vue";

const toast = useToast();
const dt = ref();
const loading = ref(true);
const student = ref({});
const classrooms = ref([]);
const students = ref([{}, {}, {}, {}, {}]);
const selectedStudents = ref([]);
const addStudentDialog = ref(false);
const editStudentDialog = ref(false);
const deleteStudentDialog = ref(false);
const deleteSelectedStudentDialog = ref(false);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const getData = async () => {
    const response = await axios.get("/admin/students/index").finally(() => {
        loading.value = false;
    });
    students.value = response.data;
};

const getClassrooms = async () => {
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
    await getClassrooms();
});

const addForm = useForm({
    nisn: "",
    name: "",
    classrooms_id: "",
    password: "",
    password_confirmation: "",
});

const updateForm = useForm({
    id: 0,
    name: "",
    nisn: "",
    name: "",
    classrooms_id: "",
    password: "",
    password_confirmation: "",
});

const deleteForm = useForm({
    ids: [],
});

const uploadForm = useForm({
    file: null,
});

watch(addStudentDialog, (value) => {
    if (value === true) {
        addForm.reset();
        addForm.clearErrors();
    }
});

const exportCSV = () => {
    dt.value.exportCSV();
};

const addStudent = () => {
    sendData(
        addForm,
        "/admin/students/store",
        addStudentDialog,
        "Success add student"
    );
};

const deleteSelectedStudent = async () => {
    deleteForm.ids = selectedStudents.value.map((item) => item.id);
    sendData(
        deleteForm,
        "/admin/students/delete",
        deleteSelectedStudentDialog,
        "Success delete selected classroom"
    );
};

const toggleEditDialog = (data) => {
    updateForm.id = data.id;
    updateForm.nisn = data.nisn;
    updateForm.name = data.name;
    updateForm.classrooms_id = data.classrooms_id;
    editStudentDialog.value = true;
};

const editSudent = () => {
    sendData(
        updateForm,
        "/admin/students/edit",
        editStudentDialog,
        "Success update student"
    );
};

const toggleDeleteDialog = (data) => {
    student.value = data;
    deleteForm.ids.push(student.value.id);
    deleteStudentDialog.value = true;
};

const deleteStudent = () => {
    sendData(
        deleteForm,
        "/admin/students/delete",
        deleteStudentDialog,
        "Success delete student"
    );
};

const handleUpload = (event) => {
    uploadForm.file = event.files[0];
    loading.value = true;
    uploadForm.post("/admin/students/uploadFile", {
        onSuccess: async () => {
            await getData();
            toast.add({
                severity: "success",
                summary: "Success",
                detail: "Success upload file",
                life: 3000,
            });
        },
    });
};

const downloadExample = () => {
    window.location.href = "/admin/students/example";
};
</script>

<template>
    <Toast></Toast>

    <div class="flex items-center gap-x-2 text-2xl font-medium text-blue-950">
        <i class="pi pi-user"></i>
        <h5>Students</h5>
    </div>

    <div class="card text-blue-950">
        <ToolbarTable
            v-model:selectedItems="selectedStudents"
            v-model:addItemDialog="addStudentDialog"
            v-model:deleteSelectedItemDialog="deleteSelectedStudentDialog"
            :exportCSV="exportCSV"
        >
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
        </ToolbarTable>

        <DataTable
            ref="dt"
            v-model:selection="selectedStudents"
            :value="students"
            dataKey="id"
            :paginator="true"
            :rows="5"
            :filters="filters"
            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink"
        >
            <template #header>
                <div class="flex flex-wrap gap-2 items-center justify-between">
                    <h4 class="m-0 text-xl font-medium">Manage Students</h4>
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
                field="nisn"
                header="NISN"
                sortable
                style="min-width: 12rem"
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
                field="classroom.name"
                header="Classroom"
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

    <!-- Add Student Dialog -->
    <DialogForm
        v-model="addStudentDialog"
        header="Add Student"
        :confirm="addStudent"
    >
        <FormStudent v-model:form="addForm" v-model:classrooms="classrooms" />
    </DialogForm>

    <!-- Delete Selected Students Dialog -->
    <DialogConfirmation
        v-model="deleteSelectedStudentDialog"
        :confirm="deleteSelectedStudent"
    >
        <div class="flex items-center gap-4">
            <i class="pi pi-exclamation-triangle !text-3xl" />
            <span>Are you sure you want to delete the selected courses?</span>
        </div>
    </DialogConfirmation>

    <!-- Edit Student Dialog -->
    <DialogForm
        v-model="editStudentDialog"
        header="Edit Student"
        :confirm="editSudent"
    >
        <FormStudent
            v-model:form="updateForm"
            v-model:classrooms="classrooms"
        />
    </DialogForm>

    <!-- Delete Student Dialog -->
    <DialogConfirmation v-model="deleteStudentDialog" :confirm="deleteStudent">
        <div class="flex items-center gap-4">
            <i class="pi pi-exclamation-triangle !text-3xl" />
            <span
                >Are you sure you want to delete courses
                {{ student.name }}?</span
            >
        </div>
    </DialogConfirmation>
</template>
