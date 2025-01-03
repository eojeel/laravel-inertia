<script setup>

import Container from "../../Components/Container.vue";
import InputField from "../../Components/InputField.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import Link from "../../Components/Link.vue";
import {useForm} from "@inertiajs/vue3";

const form = useForm({
    name:"",
    email:"",
    password:"",
    password_confirmation:"",
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset("password","password_confirmation")
    })
}

</script>

<template>
    <container class="w-1/2">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold mb-2">Register User</h1>
            <p>Already Have an Account? <Link route="/login" name="login" /></p>
        </div>

        <form class="space-y-6" @submit.prevent="submit">
            <InputField label="Name" icon="id-badge" v-model="form.name"/>
            <InputField label="Email" type="email" icon="at" v-model="form.email"/>
            <InputField label="Password" type="password" icon="key" v-model="form.password"/>
            <InputField label="Confirm Password" type="password" icon="key" v-model="form.password_confirmation"/>
            <PrimaryButton :disabled="form.processing">Register</PrimaryButton>
        </form>
    </container>
</template>
