<script>
import Layout from "../templates/Admin.vue";
import FormTest from "../organisms/FormTest.vue";

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
import FormTest from "../organisms/FormTest.vue";
import DialogConfirmation from "../molecules/DialogConfirmation.vue";

const toast = useToast();
const dt = ref();
const loading = ref(true);
const tests = ref([{}, {}, {}, {}, {}]);
const courses = ref([]);
const classrooms = ref([]);
const types = ref([
    { name: "UTS", value: "UTS" },
    { name: "UAS", value: "UAS" },
    { name: "Quiz", value: "Q" },
    { name: "Entrance", value: "Entrance" },
]);
const selectedTests = ref([]);
const addTestDialog = ref(false);
const editTestDialog = ref(false);
const deleteTestDialog = ref(false);
const deleteSelectedTestDialog = ref(false);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const getData = async () => {
    const response = await axios.get("/admin/tests/index").finally(() => {
        loading.value = false;
    });
    tests.value = response.data;
};

const getDataCourses = async () => {
    const response = await axios.get("/admin/courses/shows").finally(() => {
        loading.value = false;
    });
    courses.value = response.data;
};

const getDataClassrooms = async () => {
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
    await getDataClassrooms();
    await getDataCourses();
});

const exportCSV = () => {
    dt.value.exportCSV();
};

watch(addTestDialog, (value) => {
    if (value === true) {
        addForm.reset();
        addForm.clearErrors();
    }
});

const addForm = useForm({
    classrooms_id: null,
    courses_id: null,
    study_period: null,
    session: 0,
    type: null,
});

const editForm = useForm({
    id: 0,
    classrooms_id: 0,
    courses_id: 0,
    study_period: "",
    session: 0,
    type: "",
});

const deleteForm = useForm({
    ids: [],
});

const addTest = () => {
    sendData(addForm, "/admin/tests/store", addTestDialog, "Success add test");
};

const deleteSelectedTest = () => {
    deleteForm.ids = selectedTests.value.map((item) => item.id);
    sendData(
        deleteForm,
        "/admin/tests/delete",
        deleteSelectedTestDialog,
        "Success delete selected test"
    );
};

const toggleEditDialog = (data) => {
    editForm.id = data.id;
    editForm.type = data.type;
    editForm.classrooms_id = data.classrooms_id;
    editForm.courses_id = data.courses_id;
    editForm.study_period = data.study_period;
    editForm.session = data.session;
    editTestDialog.value = true;
};

const editTest = () => {
    sendData(
        editForm,
        "/admin/tests/edit",
        editTestDialog,
        "Success edit test"
    );
};

const toggleDeleteDialog = (data) => {
    deleteForm.ids.push(data.id);
    deleteTestDialog.value = true;
};

const deleteTest = () => {
    sendData(
        deleteForm,
        "/admin/tests/delete",
        deleteTestDialog,
        "Success delete test"
    );
};
</script>
<template>
    <Toast></Toast>

    <div class="flex items-center gap-x-2 text-2xl font-medium text-blue-950">
        <i class="pi pi-book"></i>
        <h5>Tests</h5>
    </div>

    <div class="card text-blue-950">
        <ToolbarTable
            v-model:selectedItems="selectedTests"
            v-model:addItemDialog="addTestDialog"
            v-model:deleteSelectedItemDialog="deleteSelectedTestDialog"
            :exportCSV="exportCSV"
        ></ToolbarTable>

        <DataTable
            ref="dt"
            v-model:selection="selectedTests"
            :value="tests"
            dataKey="id"
            :paginator="true"
            :rows="5"
            :filters="filters"
            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink"
        >
            <template #header>
                <div class="flex flex-wrap gap-2 items-center justify-between">
                    <h4 class="m-0 text-xl font-medium">Manage Tests</h4>
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
                field="type"
                header="Type"
                sortable
                style="min-width: 12rem"
            >
                <template v-if="loading" #body>
                    <Skeleton></Skeleton>
                </template>
            </Column>

            <Column
                field="course.name"
                header="Course"
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

            <Column
                field="study_period"
                header="Study Period"
                sortable
                style="min-width: 12rem"
            >
                <template v-if="loading" #body>
                    <Skeleton></Skeleton>
                </template>
            </Column>

            <Column
                field="session"
                header="Session"
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
                        as="a"
                        icon="pi pi-plus"
                        severity="warn"
                        outlined
                        rounded
                        class="mr-2"
                        :href="`/admin/tests/${slotProps.data.id}/questions`"
                    />
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

    <!-- Add Test Dialog -->
    <DialogForm v-model="addTestDialog" header="Add Test" :confirm="addTest">
        <FormTest
            v-model:form="addForm"
            v-model:courses="courses"
            v-model:classrooms="classrooms"
            v-model:types="types"
        />
    </DialogForm>

    <!-- Delete Selected Test Dialog -->
    <DialogConfirmation
        v-model="deleteSelectedTestDialog"
        :confirm="deleteSelectedTest"
    >
        <div class="flex items-center gap-4">
            <i class="pi pi-exclamation-triangle !text-3xl" />
            <span>Are you sure you want to delete the selected courses?</span>
        </div>
    </DialogConfirmation>

    <!-- Edit Test Dialog -->
    <DialogForm v-model="editTestDialog" header="Edit Test" :confirm="editTest">
        <FormTest
            v-model:form="editForm"
            v-model:courses="courses"
            v-model:classrooms="classrooms"
            v-model:types="types"
        />
    </DialogForm>

    <!-- Delete Test Dialog -->
    <DialogConfirmation v-model="deleteTestDialog" :confirm="deleteTest">
        <div class="flex items-center gap-4">
            <i class="pi pi-exclamation-triangle !text-3xl" />
            <span>Are you sure you want to delete this test?</span>
        </div>
    </DialogConfirmation>
</template>
