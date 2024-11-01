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
import Label from "../atoms/Label.vue";
import DialogForm from "../molecules/DialogForm.vue";
import DialogConfirmation from "../molecules/DialogConfirmation.vue";

const toast = useToast();
const dt = ref();
const loading = ref(true);
const admin = ref({});
const admins = ref([{}, {}, {}, {}, {}]);
const selectedAdmins = ref([]);
const addAdminDialog = ref(false);
const editAdminDialog = ref(false);
const deleteAdminDialog = ref(false);
const deleteSelectedAdminDialog = ref(false);
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const getData = async () => {
    const response = await axios.get("/admin/admins/shows").finally(() => {
        loading.value = false;
    });
    admins.value = response.data;
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
    password: "",
    password_confirmation: "",
});

const updateForm = useForm({
    id: 0,
    name: "",
    password: "",
    password_confirmation: "",
});

const deleteForm = useForm({
    ids: [],
});

const addAdmin = () => {
    sendData(
        addForm,
        "/admin/admins/store",
        addAdminDialog,
        "Success add admin"
    );
};

const deleteSelectedAdmin = () => {
    deleteForm.ids = selectedAdmins.value.map((item) => item.id);
    sendData(
        deleteForm,
        "/admin/admins/delete",
        deleteSelectedAdminDialog,
        "Success delete selected admin"
    );
};

const toggleEditDialog = (data) => {
    updateForm.id = data.id;
    updateForm.name = data.name;
    editAdminDialog.value = true;
};

const editAdmin = () => {
    sendData(
        updateForm,
        "/admin/admins/edit",
        editAdminDialog,
        "Success update admin"
    );
};

const toggleDeleteDialog = (data) => {
    admin.value = data;
    deleteForm.ids.push(admin.value.id);
    deleteAdminDialog.value = true;
};

const deleteAdmin = () => {
    sendData(
        deleteForm,
        "/admin/admins/delete",
        deleteAdminDialog,
        "Success delete admin"
    );
};
</script>

<template>
    <Toast></Toast>

    <div class="flex items-center gap-x-2 text-2xl font-medium text-blue-950">
        <i class="pi pi-users"></i>
        <h5>Admin</h5>
    </div>

    <div class="card text-blue-950">
        <ToolbarTable
            v-model:selectedItems="selectedAdmins"
            v-model:addItemDialog="addAdminDialog"
            v-model:deleteSelectedItemDialog="deleteSelectedAdminDialog"
            :exportCSV="exportCSV"
        >
        </ToolbarTable>

        <DataTable
            ref="dt"
            v-model:selection="selectedAdmins"
            :value="admins"
            dataKey="id"
            :paginator="true"
            :rows="5"
            :filters="filters"
            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink"
        >
            <template #header>
                <div class="flex flex-wrap gap-2 items-center justify-between">
                    <h4 class="m-0 text-xl font-medium">Manage Admins</h4>
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

    <!-- Add Dialog Admin -->
    <DialogForm v-model="addAdminDialog" header="Add Admin" :confirm="addAdmin">
        <div class="flex flex-col gap-y-3">
            <div class="flex flex-col gap-y-2">
                <Label for="name">Name</Label>
                <InputText
                    v-model="addForm.name"
                    placeholder="John Doe"
                    autofocus="true"
                    id="name"
                />
                <template v-if="addForm.errors.name">
                    <Message severity="error">
                        {{ addForm.errors.name }}
                    </Message>
                </template>
            </div>

            <div class="flex flex-col gap-y-2">
                <Label for="password">Password</Label>
                <Password
                    :inputStyle="{ width: '100%' }"
                    inputId="password"
                    v-model="addForm.password"
                    placeholder="********"
                    :feedback="false"
                    toggleMask
                />
                <template v-if="addForm.errors.password">
                    <Message severity="error">
                        {{ addForm.errors.password }}
                    </Message>
                </template>
            </div>

            <div class="flex flex-col gap-y-2">
                <Label for="password_confirmation">Confirmation Password</Label>
                <Password
                    :inputStyle="{ width: '100%' }"
                    inputId="password_confirmation"
                    v-model="addForm.password_confirmation"
                    placeholder="********"
                    :feedback="false"
                    toggleMask
                />
                <template v-if="addForm.errors.password_confirmation">
                    <Message severity="error">
                        {{ addForm.errors.password_confirmation }}
                    </Message>
                </template>
            </div>
        </div>
    </DialogForm>

    <!-- Delete Selected dialog Admin -->
    <DialogConfirmation
        v-model="deleteSelectedAdminDialog"
        :confirm="deleteSelectedAdmin"
    >
        <div class="flex items-center gap-4">
            <i class="pi pi-exclamation-triangle !text-3xl" />
            <span>Are you sure you want to delete the selected admin?</span>
        </div>
    </DialogConfirmation>

    <!-- Edit dialog Admin -->
    <DialogForm
        v-model="editAdminDialog"
        header="Update Admin"
        :confirm="editAdmin"
    >
        <div class="flex flex-col gap-y-3">
            <div class="flex flex-col gap-y-2">
                <Label for="name">Name</Label>
                <InputText
                    v-model="updateForm.name"
                    autofocus="true"
                    id="name"
                />
                <template v-if="updateForm.errors.name">
                    <Message severity="error">
                        {{ updateForm.errors.name }}
                    </Message>
                </template>
            </div>

            <div class="flex flex-col gap-y-2">
                <Label for="password">Password</Label>
                <Password
                    :inputStyle="{ width: '100%' }"
                    inputId="password"
                    v-model="updateForm.password"
                    placeholder="********"
                    :feedback="false"
                    toggleMask
                />
                <template v-if="updateForm.errors.password">
                    <Message severity="error">
                        {{ updateForm.errors.password }}
                    </Message>
                </template>
            </div>

            <div class="flex flex-col gap-y-2">
                <Label for="password_confirmation">Confirmation Password</Label>
                <Password
                    :inputStyle="{ width: '100%' }"
                    inputId="password_confirmation"
                    v-model="updateForm.password_confirmation"
                    placeholder="********"
                    :feedback="false"
                    toggleMask
                />
                <template v-if="updateForm.errors.password_confirmation">
                    <Message severity="error">
                        {{ updateForm.errors.password_confirmation }}
                    </Message>
                </template>
            </div>
        </div>
    </DialogForm>

    <!-- Delete dialog Admin -->
    <DialogConfirmation v-model="deleteAdminDialog" :confirm="deleteAdmin">
        <div class="flex items-center gap-4">
            <i class="pi pi-exclamation-triangle !text-3xl" />
            <span
                >Are you sure you want to delete admin with name
                {{ admin.name }}?</span
            >
        </div>
    </DialogConfirmation>
</template>
