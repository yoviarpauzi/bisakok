<script setup>
import Label from "../atoms/Label.vue";

const form = defineModel("form");
const classrooms = defineModel("classrooms");
const courses = defineModel("courses");
const types = defineModel("types");
</script>

<template>
    <div class="flex flex-col gap-y-2">
        <div class="flex flex-col gap-y-2">
            <Label for="type"> Type </Label>
            <Select
                v-model="form.type"
                :options="types"
                optionLabel="name"
                optionValue="value"
                placeholder="Select Types"
            />
            <template v-if="form.errors.type">
                <Message severity="error">
                    {{ form.errors.type }}
                </Message>
            </template>
        </div>

        <div class="flex flex-col gap-y-2">
            <label for="type"> Courses </label>
            <Select
                v-model="form.courses_id"
                :options="courses"
                optionLabel="name"
                optionValue="id"
                placeholder="Select Courses"
            />
            <template v-if="form.errors.courses_id">
                <Message severity="error">
                    {{ form.errors.courses_id }}
                </Message>
            </template>
        </div>

        <div class="flex flex-col gap-y-2">
            <Label for="type"> Classroom </Label>
            <Select
                v-model="form.classrooms_id"
                :options="classrooms"
                optionLabel="name"
                optionValue="id"
                placeholder="Select Classroom"
            />
            <template v-if="form.errors.type">
                <Message severity="error">
                    {{ form.errors.type }}
                </Message>
            </template>
        </div>

        <div class="flex flex-col gap-y-2">
            <Label for="nisn">Study Period</Label>
            <InputMask
                ref="input"
                id="nisn"
                v-model="form.study_period"
                mask="9999/9999"
                placeholder="2001/2002"
            />
            <template v-if="form.errors.study_period">
                <Message severity="error">
                    {{ form.errors.study_period }}
                </Message>
            </template>
        </div>

        <div class="flex flex-col gap-y-2">
            <label for="session">Session</label>
            <InputNumber
                v-model="form.session"
                inputId="session"
                :min="0"
                :max="16"
                fluid
            />
            <template v-if="form.errors.session">
                <Message severity="error">
                    {{ form.errors.session }}
                </Message>
            </template>
        </div>
    </div>
</template>
