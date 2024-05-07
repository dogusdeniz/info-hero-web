<script setup>
import { ref } from "vue";
import NavLink from "@/Components/NavLink.vue";
import { DocumentTextIcon, TrashIcon } from "@heroicons/vue/24/outline";
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
                                        J
                                    </span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="text-base font-medium text-white">
                                    John Doe
                                </div>
                                <div class="text-sm font-medium text-gray-500">
                                    <a href="#" class="text-primary">Log out</a>
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
                        <div>
                            <span
                                class="block px-1 pt-1 text-sm font-medium text-white focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out"
                            >
                                Documents
                                <span
                                    class="bg-avatar px-2 py-1 rounded cursor-pointer select-none active:border-white active:border"
                                >
                                    +
                                </span>
                            </span>

                            <ul
                                class="mt-5 px-1 pt-1 max-h-[720px] overflow-auto"
                            >
                                <li>
                                    <div class="text-center text-gray-500">
                                        You don't have a document
                                    </div>
                                </li>
                                <li class="flex text-white text-sm mb-3">
                                    <div class="mr-3 p-1">
                                        <DocumentTextIcon class="h-5 w-5" />
                                    </div>
                                    <div
                                        class="overflow-hidden truncate p-1 select-none"
                                    >
                                        2024_04_25_Basel_III_Raporu.pdf
                                    </div>
                                    <div
                                        class="ml-auto bg-avatar rounded p-1 active:border cursor-pointer"
                                    >
                                        <TrashIcon class="h-5 w-5" />
                                    </div>
                                </li>
                            </ul>
                        </div>
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
