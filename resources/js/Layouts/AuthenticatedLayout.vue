<script setup>
import NavLink from "@/Components/NavLink.vue";
import DocumentList from "@/Components/DocumentList.vue";
import { usePage } from "@inertiajs/vue3";
import { ref } from "vue";

const page = usePage();

const user = ref(page.props.auth.user ?? {});
</script>

<template>
    <div>
        <div class="min-h-screen bg-secondary dark:bg-neutral-900 flex">
            <!-- Primary Navigation Menu -->
            <nav class="bg-primary w-96 min-h-full border-r border-neutral-700">
                <div class="px-6 py-4">
                    <div class="flex justify-between">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="h-10 w-10 bg-avatar rounded-full flex items-center justify-center"
                                >
                                    <span class="text-white text-2xl font-bold">
                                        {{ user.name.charAt(0) }}
                                    </span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="text-base font-medium text-white">
                                    {{ user.name }}
                                </div>
                                <div class="text-sm font-medium text-gray-500">
                                    <!-- <a href="#" class="text-primary">Log out</a> -->
                                    <form
                                        method="POST"
                                        :action="route('logout')"
                                    >
                                        <button
                                            type="submit"
                                            class="text-primary"
                                        >
                                            Log out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Links -->
                    <div class="mt-8">
                        <NavLink
                            href="/"
                            :active="route().current('dashboard')"
                        >
                            Users
                        </NavLink>
                        <DocumentList />
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="flex-1">
                <slot />
            </main>
        </div>
    </div>
</template>
