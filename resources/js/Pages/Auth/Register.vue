<script setup>

import Container from "../../Components/Container.vue";
import InputField from "../../Components/InputField.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
</script>

<template>
    <container class="w-1/2">
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold mb-2">Register User</h1>
            <p>Already Have an Account?</p>
        </div>

        <form class="space-y-6">
            <InputField label="Name" icon="id-badge"/>
            <InputField label="Email" type="email" icon="at"/>
            <InputField label="Password" type="password" icon="key"/>
            <InputField label="Confirm Password" type="password" icon="key"/>
            <PrimaryButton>Register</PrimaryButton>
        </form>
    </container>
</template>
