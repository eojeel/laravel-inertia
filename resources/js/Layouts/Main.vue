<script setup>
import {switchTheme} from "../theme.js";
import NavLink from "../Components/NavLink.vue";
import {usePage, Link} from "@inertiajs/vue3";
import {computed, ref, onMounted, onUnmounted} from "vue";

const page = usePage();
const user = computed(() => page.props.auth.user)

const show = ref(false)
const triggerRef = ref(null);
const menuRef = ref(null);

const clickOutside = (event) => {
    if (show.value && triggerRef.value && menuRef.value && !triggerRef.value.contains(event.target) && !menuRef.value.contains(event.target)) {
        show.value = false;
    }
};

onMounted(() => {
    document.addEventListener('click', clickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', clickOutside);
});
</script>
<template>
    <header class="bg-slate-800 text-white">
        <nav class="p-6 mx-auto max-w-screen-lg flex items-center justify-between">
            <NavLink routeName="home">Home</NavLink>
            <div class="flex items-center space-x-6">
                <div v-if="user" class="relative flex items-center gap-4" ref="triggerRef" @click="show = !show">
                    <div class="flex items-center gap-2 px-3 py-1 rounded-lg hover:bg-slate-7000 cursor-pointer">
                        Account
                        <i class="fa-solid fa-angle-down" :class="{'fa-angle-down': !show, 'fa-angle-up': show}"></i>
                    </div>
                    <div v-show="show"
                         @click="show = !show"
                         ref="menuRef"
                         class="absolute z-50 top-16 right-0 bg-slate-800 text-white rounded-lg border-slate-700 border overflow-hidden w-40">

                        <Link
                            :href="route('listing.create')"
                            @click="show = !show"
                            class="block w-full px-6 py-3 hover:bg-slate-700 text-left">
                            New Listing
                        </Link>

                        <Link
                            :href="route('profile.edit')"
                            @click="show = !show"
                            class="block w-full px-6 py-3 hover:bg-slate-700 text-left">
                            Profile
                        </Link>

                        <Link
                        :href="route('dashboard')"
                        @click="show = !show"
                        class="block w-full px-6 py-3 hover:bg-slate-700 text-left">
                        Dashboard
                        </Link>

                        <Link
                            :href="route('logout')"
                            @click="show = !show"
                            class="block w-full px-6 py-3 hover:bg-slate-700 text-left">
                            Logout
                        </Link>
                    </div>
                    <Link v-if="user.role === 'admin'"
                          :href="route('admin.index')"
                          class="hover:bg-slate-700 w-6 h-4 grid placed-items-center rounded-full hover:outline outline-1 outline-white">
                        <i class="fa-solid fa-lock"></i>
                    </Link>
                </div>


                <div v-else>
                    <NavLink routeName="login">Login</NavLink>
                    <NavLink routeName="register">Register</NavLink>
                </div>

                <button
                    @click="switchTheme"
                    class="hover:bg-slate-700 w-6 h-4 grid placed-items-center rounded-full hover:outline outline-1 outline-white">
                    <i class="fa-solid fa-circle-half-stroke"></i>
                </button>

            </div>
        </nav>
    </header>

    <main class="p-6 mx-auto max-w-screen-lg">
        <slot />
    </main>
</template>
