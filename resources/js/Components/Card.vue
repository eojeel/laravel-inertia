<script setup>
import { router} from "@inertiajs/vue3";

const params = route().params;

defineProps({
    listing: Object,
})

const selectUser = (id) => {
    router.get(route('home'), {
        user_id: id,
        tag: params.tag,
        search: params.search,
    })
}

const selectTag = (tag) => {
    router.get(route('home'), {
        tag: tag,
        user_id: params.user_id,
        search: params.search,
    })
}
</script>

<template>
<div class="bg-white rounded-lg shadow-lg overflow-hidden dark:bg-slate-800 h-full flex flex-col justify-between">
    <div>
        <Link href="">
            <img class="w-full h-48 object-cover object-center" :src="listing.image ? listing.image : '/storage/images/listing/default.png'">
        </Link>

        <div class="p-4">
            <h3 class="font-bold text-xl mb-2">
                {{ listing.title.substring(0, 40) }}...
            </h3>

            <p>Listed On {{ new Date(listing.created_at).toLocaleDateString()}} By <button @click="selectUser(listing.user.id)" class="text-link">{{ listing.user.name }}</button></p>
        </div>
    </div>

    <div v-if="listing.tags" class="flex items-center gap-3 p-2">
        <div v-for="tag in listing.tags.split(',')" :key="tag">
            <button @click="selectTag(tag)"
                    class="bg-slate-500 text-white px-2 py-px rounded-full hover:bg-slate-700 dark:hover:bg-slate-900">{{ tag }}</button>
            </div>
    </div>
</div>
</template>
