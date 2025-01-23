<script setup>
import Container from "../../../Components/Container.vue";
import InputField from "../../../Components/InputField.vue";
import PrimaryButton from "../../../Components/PrimaryButton.vue";
import Title from "../../../Components/Title.vue";
import {useForm} from "@inertiajs/vue3";
import Message from "../../../Components/Message.vue";
import Error from "../../../Components/Error.vue";

const form = useForm({
    current_password: "",
    new_password: "",
    new_password_confirmation: "",
});

const submit = () =>
{
    form.put(route('profile.updatePassword'), {
        onSuccess: () => form.reset(),
        onError: () => form.reset(),
        preserveScroll: 'errors'
    });
}

</script>

<template>
    <Container clas="mb-6">
        <div class="mb-4">
            <Title>Update Password</Title>
            <p>Update your account's password.</p>
        </div>

        <Message :message="status"/>
        <Error :errors="form.errors" />

        <form
            @submit.prevent="submit"
            class="space-y-6">
            <InputField
                label="Current Password"
                icon="key"
                v-model="form.current_password"
                type="password"
                class="w-1/2"
            />

            <InputField
                label="New Password"
                icon="key"
                v-model="form.new_password"
                type="password"
                class="w-1/2"
            />

            <InputField
                label="Confirm Password"
                icon="key"
                v-model="form.new_password_confirmation"
                type="password"
                class="w-1/2"
            />

            <p v-if="form.recentlySuccessful" class="text-green-500 font-medium">Saved</p>

            <PrimaryButton :disabled="form.processing">Update Password</PrimaryButton>
        </form>
    </Container>
</template>
