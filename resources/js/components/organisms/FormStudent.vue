<script setup>
const form = defineModel("form");
const classrooms = defineModel("classrooms");
</script>

<template>
    <div class="flex flex-col gap-y-2">
        <div class="flex flex-col gap-y-2">
            <Label for="nisn">NISN</Label>
            <InputMask
                ref="input"
                id="nisn"
                v-model="form.nisn"
                mask="99-9999-9999"
                placeholder="99-9999-9999"
            />
            <template v-if="form.errors.nisn">
                <Message severity="error">
                    {{ form.errors.nisn }}
                </Message>
            </template>
        </div>

        <div class="flex flex-col gap-y-2">
            <label for="name">Name</label>
            <InputText v-model="form.name" placeholder="John Doe" />
            <template v-if="form.errors.name">
                <Message severity="error">
                    {{ form.errors.name }}
                </Message>
            </template>
        </div>

        <div class="flex flex-col gap-y-2">
            <label for="classroom_id">Classroom</label>
            <Select
                v-model="form.classrooms_id"
                :options="classrooms"
                optionLabel="name"
                optionValue="id"
                placeholder="Select Classroom"
            />
            <template v-if="form.errors.classroom_id">
                <Message severity="error">
                    {{ form.errors.classroom_id }}
                </Message>
            </template>
        </div>

        <div class="flex flex-col gap-y-2">
            <label for="password">Password</label>
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

        <div class="flex flex-col gap-y-2">
            <label for="password_confirmation">Confirmation Password</label>
            <Password
                :inputStyle="{ width: '100%' }"
                inputId="password_confirmation"
                v-model="form.password_confirmation"
                placeholder="********"
                :feedback="false"
                toggleMask
            />
            <template v-if="form.errors.password_confirmation">
                <Message severity="error">
                    {{ form.errors.password_confirmation }}
                </Message>
            </template>
        </div>
    </div>
</template>
