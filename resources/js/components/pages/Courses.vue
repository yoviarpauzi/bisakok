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
const course = ref({});
const courses = ref([{}, {}, {}, {}, {}]);
const selectedCourses = ref([]);
const addCoursesDialog = ref(false);
const editCoursesDialog = ref(false);
const deleteCoursesDialog = ref(false);
const deleteSelectedCoursesDialog = ref(false);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const getData = async () => {
    const response = await axios.get("/admin/courses/shows").finally(() => {
        loading.value = false;
    });
    courses.value = response.data;
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

const exportCSV = () => {
    dt.value.exportCSV();
};

const addForm = useForm({
    name: "",
});

const updateForm = useForm({
    id: 0,
    name: "",
});

const deleteForm = useForm({
    ids: [],
});

watch(addCoursesDialog, (value) => {
    if (value === true) {
        addForm.reset();
        addForm.clearErrors();
    }
});

const addCourses = () => {
    sendData(
        addForm,
        "/admin/courses/store",
        addCoursesDialog,
        "Success add course"
    );
};

const toggleEditDialog = (data) => {
    updateForm.id = data.id;
    updateForm.name = data.name;
    editCoursesDialog.value = true;
};

const editCourses = () => {
    sendData(
        updateForm,
        "/admin/courses/edit",
        editCoursesDialog,
        "Success update course"
    );
};

const deleteSelectedCourses = async () => {
    deleteForm.ids = selectedCourses.value.map((item) => item.id);
    sendData(
        deleteForm,
        "/admin/courses/delete",
        deleteSelectedCoursesDialog,
        "Success delete selected classroom"
    );
};

const toggleDeleteDialog = (data) => {
    course.value = data;
    deleteForm.ids.push(course.value.id);
    deleteCoursesDialog.value = true;
};

const deleteCourses = () => {
    sendData(
        deleteForm,
        "/admin/courses/delete",
        deleteCoursesDialog,
        "Success delete course"
    );
};
</script>

<template>
    <Toast></Toast>

    <div class="flex items-center gap-x-2 text-2xl font-medium text-blue-950">
        <i class="pi pi-book"></i>
        <h5>Courses</h5>
    </div>

    <div class="card text-blue-950">
        <ToolbarTable
            v-model:selectedItems="selectedCourses"
            v-model:addItemDialog="addCoursesDialog"
            v-model:deleteSelectedItemDialog="deleteSelectedCoursesDialog"
            :exportCSV="exportCSV"
        ></ToolbarTable>

        <DataTable
            ref="dt"
            v-model:selection="selectedCourses"
            :value="courses"
            dataKey="id"
            :paginator="true"
            :rows="10"
            :filters="filters"
            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink"
        >
            <template #header>
                <div class="flex flex-wrap gap-2 items-center justify-between">
                    <h4 class="m-0 text-xl font-medium">Manage Courses</h4>
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
                field="exams_count"
                header="Number of exams"
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

    <!-- Add Courses Dialog -->
    <DialogForm
        v-model="addCoursesDialog"
        header="Add Course"
        :confirm="addCourses"
    >
        <div class="flex flex-col gap-y-2">
            <label for="name">Name</label>
            <InputText v-model="addForm.name" autofocus="true" />
            <template v-if="addForm.errors.name">
                <Message severity="error">
                    {{ addForm.errors.name }}
                </Message>
            </template>
        </div>
    </DialogForm>

    <!-- Delete Selected Courses Dialog -->
    <DialogConfirmation
        v-model="deleteSelectedCoursesDialog"
        :confirm="deleteSelectedCourses"
    >
        <div class="flex items-center gap-4">
            <i class="pi pi-exclamation-triangle !text-3xl" />
            <span>Are you sure you want to delete the selected courses?</span>
        </div>
    </DialogConfirmation>

    <!-- Edit Course Dialog -->
    <DialogForm
        v-model="editCoursesDialog"
        header="Update Course"
        :confirm="editCourses"
    >
        <div class="flex flex-col gap-y-2">
            <label for="name">Name</label>
            <InputText v-model="updateForm.name" autofocus="true" />
            <template v-if="updateForm.errors.name">
                <Message severity="error">
                    {{ updateForm.errors.name }}
                </Message>
            </template>
        </div>
    </DialogForm>

    <!-- Delete Courses Dialog -->
    <DialogConfirmation v-model="deleteCoursesDialog" :confirm="deleteCourses">
        <div class="flex items-center gap-4">
            <i class="pi pi-exclamation-triangle !text-3xl" />
            <span
                >Are you sure you want to delete courses
                {{ course.name }}?</span
            >
        </div>
    </DialogConfirmation>
</template>
