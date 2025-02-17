<script setup>
import Container from "../../Components/Container.vue";
import Title from "../../Components/Title.vue";
import InputField from "../../Components/InputField.vue";
import Error from "../../Components/Error.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import {useForm, Head} from "@inertiajs/vue3";
import TextArea from "../../Components/TextArea.vue";
import ImageUpload from "../../Components/ImageUpload.vue";

const form = useForm({
    title: null,
    description: null,
    tags: null,
    email: null,
    link: null,
    image: null,
});
</script>

<template>
    <div>
        <Head title="- Create Listing"/>
        <Container>
            <div class="mb-6">
                <Title>Create Listing</Title>
            </div>

            <Error :errors="form.errors" />

            <form class="grid grid-cols-2 gap-6">
                <div class="space-y-6">
                    <InputField
                        label="Title"
                        icon="heading"
                        placeholder="Title"
                        v-model="form.title"
                        />
                    <InputField
                        label="Tags (comma separated)"
                        icon="tags"
                        placeholder="one, two, three"
                        v-model="form.tags"
                    />
                    <TextArea
                        label="Description"
                        icon="newspaper"
                        placeholder="Description"
                        v-model="form.description"
                        />
                </div>
                <div class="space-y-6">
                    <InputField
                        label="Email"
                        icon="at"
                        placeholder="email@example.com"
                        v-model="form.email"
                    />
                    <InputField
                        label="External Link"
                        icon="up-right-from-square"
                        placeholder="https://www.image.com/image.jpg"
                        v-model="form.image"
                    />
                    <ImageUpload @image="(e) => form.image = e"></ImageUpload>
                </div>
                <div>
                    <PrimaryButton>Create Listing</PrimaryButton>
                </div>
            </form>
        </Container>
    </div>
</template>
