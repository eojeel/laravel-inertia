<script setup>
import Container from "../../Components/Container.vue";
import {router} from "@inertiajs/vue3";
import Link from "../../Components/Link.vue";

const props = defineProps({
    listing: Object,
    user: Object,
})

const params = route().params;

const selectTag = (tag) => {
    router.get(route('home'), {
        tag: tag,
        user_id: params.user_id,
    })
}

const deleteListing = () => {
    if (confirm('Are you sure you want to delete this listing?')) {
        router.delete(route('listing.destroy', props.listing.id));
    }
}
</script>

<template>
    <head title="- Listing Details"/>
    <Container class="flex gap-4">
        <div class="w-1/4 rounded-md overflow-hidden">
            <img :src="listing.image ? `${listing.image}` : '/storage/images/listing/default.png'" class="w-full h-full object-cover object-center">
        </div>

        <div class="w-3/4">
            <div class="mb-6">
                <div class="flex items-end justify-between mb-2">
                    <p class="text-slate-400 w-full border-b">
                        Listing Details
                    </p>
                    <div class="pl-4 flex items-center gap-4">
                        <Link :href="route('listing.edit', listing.id)" name="Edit" class="bg-green-500 rounded-md text-white px-6 py-2 hover:outline outline-green-500 outline-offset-7">
                        </Link>
                        <button @click="deleteListing" type="button" class="bg-red-500 rounded-md text-white px-6 py-2 hover:outline outline-red-500 outline-offset-7">Delete</button>
                    </div>
                </div>

                <h3 class="font-bold text-2xl mb-4">
                    {{ listing.title }}
                </h3>
                <p>{{ listing.description }}</p>
            </div>

            <div class="mb-6">
                    <p class="text-slate-400 w-full border-b mb-2">Contact Info</p>
                    <div v-if="user.email" class="flex items-center mb-2 gap-2">
                        <i class="fa-solid fa-at"></i>
                            <p>Email:</p>
                            <a :href="`mailto:${listing.email}`"
                            class="text-link">
                                {{ user.email }}
                            </a>
                    </div>
                    <div v-if="listing.link" class="flex items-center mb-2 gap-2">
                        <i class="fa-solid fa-pull-right-from-square"></i>
                        <p>Link:</p>
                        <a :href="`${listing.link}`" target="_blank"
                           class="text-link">
                            {{ listing.link }}
                        </a>
                    </div>
                    <div class="flex items-center mb-2 gap-2">
                        <i class="fa-solid fa-user"></i>
                        <p>User:</p>
                        <Link :href="route('home', {user_id: user.id})" class="text-link">{{ user.name }}</Link>
                    </div>
            </div>

            <div class="mb-6">
                <p class="text-slate-400 w-full border-b mb-2">Tags</p>
                <div v-if="listing.tags" class="flex items-center gap-3 p-2">
                    <div v-for="tag in listing.tags.split(',')" :key="tag">
                        <button @click="selectTag(tag)"
                                class="bg-slate-500 text-white px-2 py-px rounded-full hover:bg-slate-700 dark:hover:bg-slate-900">{{ tag }}</button>
                    </div>
                </div>
            </div>
        </div>
    </Container>

</template>
