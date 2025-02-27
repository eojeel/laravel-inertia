<script setup>
import Container from "../../Components/Container.vue";
import Title from "../../Components/Title.vue";
import InputField from "../../Components/InputField.vue";
import Error from "../../Components/Error.vue";
import PrimaryButton from "../../Components/PrimaryButton.vue";
import {useForm, Head} from "@inertiajs/vue3";
import TextArea from "../../Components/TextArea.vue";
import ImageUpload from "../../Components/ImageUpload.vue";

const props = defineProps({
    listing: Object,
    image: String,
})

const form = useForm({
    title: props.listing.title,
    description: props.listing.description,
    tags: props.listing.tags,
    link: props.listing.link,
    image: null,
    _method: 'PUT',
});
</script>

<template>
    <div>
        <Head title="- Create Listing"/>
        <Container>
            <div class="mb-6">
                <Title>Edit Listing</Title>
            </div>

            <Error :errors="form.errors" />

            <form @submit.prevent="form.post(route('listing.update', listing.id))" class="grid grid-cols-2 gap-6">
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
                        label="External Link"
                        icon="up-right-from-square"
                        placeholder="https://www.laravel.com"
                        v-model="form.url"
                    />
                    <ImageUpload :defaultImage=props.listing.image @image="(e) => form.image = e"></ImageUpload>
                </div>
                <div>
                    <PrimaryButton :disabled="form.processing">Update Listing</PrimaryButton>
                </div>
            </form>
        </Container>
    </div>
</template>
