<script setup>

import Container from "../../Components/Container.vue";
import InputField from "../../Components/InputField.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import {useForm, Head} from "@inertiajs/vue3";
import Error from "../../Components/Error.vue";
import Link from "../../Components/Link.vue";
import CheckBox from "../../Components/CheckBox.vue";
import Message from "../../Components/Message.vue";

const form = useForm({
    email:"",
    password:"",
    remember: null,
});

defineProps({
    status: String,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset("password")
    })
}

</script>

<template>
    <Head title="- Login" />
    <container class="w-1/2">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold mb-2">Login to your account</h1>
            <Link route="/register" name="Register?" />
        </div>

        <Message :message="status"/>
        <Error :errors="form.errors" />

        <form @submit.prevent="submit" class="space-y-6">
            <InputField label="Email" type="email" icon="at" v-model="form.email"/>
            <InputField label="Password" type="password" icon="key" v-model="form.password"/>

            <div class="flex items-center justify-between">
                <Link route="/forgot-password" name="Reset?" />
            </div>
            <check-box name="remember" v-model="form.remember">Remember me!</check-box>
            <PrimaryButton :disabled="form.processing">Login</PrimaryButton>
        </form>
    </container>
</template>
