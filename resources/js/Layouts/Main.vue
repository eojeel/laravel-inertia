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
    <div class="min-h-screen flex flex-col bg-gradient-to-br from-white to-slate-100 dark:from-slate-950 dark:to-slate-900 text-slate-800 dark:text-slate-200">
        <header class="bg-white dark:bg-slate-900 shadow-sm sticky top-0 z-50 border-b border-slate-200 dark:border-slate-800">
            <nav class="px-6 py-4 mx-auto max-w-screen-xl flex items-center justify-between">
                <div class="flex items-center">
                    <NavLink routeName="home" class="font-bold text-lg tracking-tight">
                        <span class="text-slate-800 dark:text-slate-200 hover:text-slate-600 dark:hover:text-white transition-colors duration-200">Home</span>
                    </NavLink>
                </div>

                <div class="flex items-center space-x-8">
                    <div v-if="user" class="relative flex items-center gap-4" ref="triggerRef">
                        <button @click="show = !show"
                                class="flex items-center gap-2 px-4 py-2 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 focus:ring-2 focus:ring-slate-300 dark:focus:ring-slate-600 focus:outline-none transition-all duration-200">
                            <span>Account</span>
                            <i class="fa-solid transition-transform duration-200" :class="show ? 'fa-angle-up -rotate-180' : 'fa-angle-down rotate-0'"></i>
                        </button>

                        <div v-show="show"
                             ref="menuRef"
                             class="absolute z-50 top-14 right-0 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-200 dark:border-slate-700 overflow-hidden w-48 transition-all duration-200 ease-in-out">
                            <div class="py-2">
                                <Link
                                    :href="route('listing.create')"
                                    @click="show = false"
                                    class="flex items-center w-full px-6 py-2.5 hover:bg-slate-100 dark:hover:bg-slate-700 text-left transition-colors duration-150">
                                    <i class="fa-solid fa-plus mr-2 text-slate-500 dark:text-slate-400"></i>
                                    <span>New Listing</span>
                                </Link>

                                <Link
                                    :href="route('profile.edit')"
                                    @click="show = false"
                                    class="flex items-center w-full px-6 py-2.5 hover:bg-slate-100 dark:hover:bg-slate-700 text-left transition-colors duration-150">
                                    <i class="fa-solid fa-user mr-2 text-slate-500 dark:text-slate-400"></i>
                                    <span>Profile</span>
                                </Link>

                                <Link
                                    :href="route('dashboard')"
                                    @click="show = false"
                                    class="flex items-center w-full px-6 py-2.5 hover:bg-slate-100 dark:hover:bg-slate-700 text-left transition-colors duration-150">
                                    <i class="fa-solid fa-gauge-high mr-2 text-slate-500 dark:text-slate-400"></i>
                                    <span>Dashboard</span>
                                </Link>

                                <div class="border-t border-slate-200 dark:border-slate-700 my-1"></div>

                                <Link
                                    :href="route('logout')"
                                    @click="show = false"
                                    class="flex items-center w-full px-6 py-2.5 hover:bg-red-50 dark:hover:bg-red-900/20 text-red-600 dark:text-red-400 text-left transition-colors duration-150">
                                    <i class="fa-solid fa-arrow-right-from-bracket mr-2"></i>
                                    <span>Logout</span>
                                </Link>
                            </div>
                        </div>

                        <Link v-if="user.role === 'admin'"
                              :href="route('admin.index')"
                              class="flex items-center justify-center w-9 h-9 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 focus:ring-2 focus:ring-slate-300 dark:focus:ring-slate-600 focus:outline-none transition-all duration-200">
                            <i class="fa-solid fa-lock"></i>
                        </Link>
                    </div>

                    <div v-else class="flex items-center space-x-3">
                        <NavLink routeName="login" class="px-4 py-2 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 focus:ring-2 focus:ring-slate-300 dark:focus:ring-slate-600 focus:outline-none transition-all duration-200">Login</NavLink>
                        <NavLink routeName="register" class="px-4 py-2 rounded-full bg-slate-700 dark:bg-slate-600 text-white hover:bg-slate-800 dark:hover:bg-slate-500 focus:ring-2 focus:ring-slate-400 focus:ring-offset-2 dark:focus:ring-slate-500 focus:outline-none transition-all duration-200">Register</NavLink>
                    </div>

                    <button
                        @click="switchTheme"
                        class="flex items-center justify-center w-9 h-9 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 focus:ring-2 focus:ring-slate-300 dark:focus:ring-slate-600 focus:outline-none transition-all duration-200">
                        <i class="fa-solid fa-circle-half-stroke"></i>
                    </button>
                </div>
            </nav>
        </header>

        <main class="flex-grow px-4 py-8 mx-auto max-w-screen-xl w-full sm:px-6 lg:px-8">
                <slot />

        </main>

        <footer class="bg-white dark:bg-slate-900 py-6 mt-12 border-t border-slate-200 dark:border-slate-800">
            <div class="mx-auto max-w-screen-xl px-6">
                <div class="text-center text-slate-500 dark:text-slate-400 text-sm">
                    &copy; {{ new Date().getFullYear() }} joelee.io
                </div>
            </div>
        </footer>
    </div>
</template>
