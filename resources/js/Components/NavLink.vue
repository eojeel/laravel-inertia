<script setup>
defineProps({
    routeName: String,
})
</script>

<template>
    <Link :href="route(routeName)"
          class="px-3 py-2 rounded-lg hover:bg-slate-700"
          :class ="{'bg-slate-700' : $page.url.substring(1) === routeName}">
        <slot />
    </Link>
</template>
