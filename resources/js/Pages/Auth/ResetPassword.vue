<script setup>

import Container from "../../Components/Container.vue";
import InputField from "../../Components/InputField.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import {Head ,useForm} from "@inertiajs/vue3";
import Error from "../../Components/Error.vue";

const props = defineProps({
    token: String,
    email: String,
})


const form = useForm({
    token: props.token,
    email: props.email,
    password:"",
    password_confirmation:"",
});

const submit = () => {
    form.post(route('password.update'), {
        onFinish: () => form.reset("password","password_confirmation")
    })
}

</script>

<template>
    <Head title="- Reset Password"/>
    <container class="w-1/2">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold mb-2">Reset Password!</h1>
        </div>

        <Error :errors="form.errors" />

        <form class="space-y-6" @submit.prevent="submit">
            <InputField label="Email" type="email" icon="at" v-model="form.email"/>
            <InputField label="Password" type="password" icon="key" v-model="form.password"/>
            <InputField label="Confirm Password" type="password" icon="key" v-model="form.password_confirmation"/>
            <PrimaryButton :disabled="form.processing">Reset Password</PrimaryButton>
        </form>
    </container>
</template>
