<script>
import Layout from "../../templates/Login.vue";

export default {
    layout: Layout,
};
</script>

<script setup>
import { ref, onMounted } from "vue";
import { useForm, usePage } from "@inertiajs/vue3";
import ProgressSpinner from "primevue/progressspinner";
import Message from "primevue/message";
import Label from "../../atoms/Label.vue";
import InputText from "primevue/inputtext";
import Password from "primevue/password";
import Checkbox from "primevue/checkbox";
import Button from "primevue/button";
import { Link } from "@inertiajs/vue3";

const loading = ref(true);

const page = usePage();

const form = useForm({
    name: "",
    password: "",
    remember: "",
});

const submit = () => {
    page.props.errors.message = null;
    form.clearErrors();
    form.post("/admin/login");
};
</script>

<template>
    <template v-if="page.props.errors.message">
        <Message severity="error" class="mb-3">
            {{ page.props.errors.message }}
        </Message>
    </template>

    <form @submit.prevent="submit" class="flex flex-col gap-y-3">
        <div class="flex flex-col gap-y-2">
            <Label for="username">Username</Label>
            <InputText
                v-model="form.name"
                placeholder="John Doe"
                id="username"
            />
            <template v-if="form.errors.name">
                <Message severity="error">
                    {{ form.errors.name }}
                </Message>
            </template>
        </div>

        <div class="flex flex-col gap-y-2">
            <Label for="password">Password</Label>
            <Password
                :inputStyle="{ width: '100%' }"
                inputId="password"
                v-model="form.password"
                placeholder="********"
                :feedback="false"
                toggleMask
            />
            <template v-if="form.errors.password">
                <Message severity="error">
                    {{ form.errors.password }}
                </Message>
            </template>
        </div>

        <div class="flex items-center gap-x-2">
            <Checkbox
                inputId="remember"
                :binary="true"
                v-model="form.remember"
            />
            <Label for="remember">Remember Me</Label>
        </div>

        <Button label="Sign in" type="submit" />
    </form>

    <p class="text-center mt-10 text-sm text-gray-400">
        Are you a student?
        <Link
            href="/login"
            class="text-blue-500 hover:text-blue-400 font-medium"
            >Sign in</Link
        >
    </p>
</template>
