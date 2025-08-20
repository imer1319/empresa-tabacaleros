<script setup>
import { ref } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import { Link } from "@inertiajs/vue3";

const sidebarOpen = ref(false);
</script>

<template>
    <div class="flex h-screen bg-gray-50">
        <!-- Sidebar -->
        <div class="hidden md:flex md:w-64 md:flex-col">
            <div
                class="flex flex-col flex-grow pt-5 overflow-y-auto bg-white border-r border-gray-200"
            >
                <!-- Logo -->
                <div class="flex items-center flex-shrink-0 px-4">
                    <Link :href="route('dashboard')" class="flex items-center">
                        <ApplicationLogo class="h-8 w-auto" />
                        <span class="ml-2 text-xl font-bold text-gray-800"
                            >CRM Tabaco</span
                        >
                    </Link>
                </div>

                <!-- Navigation -->
                <nav class="mt-8 flex-1 px-2 space-y-1">
                    <Link
                        :href="route('dashboard')"
                        :class="[
                            route().current('dashboard')
                                ? 'bg-blue-50 border-r-4 border-blue-500 text-blue-700'
                                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
                            'group flex items-center px-2 py-2 text-sm font-medium rounded-l-md transition-colors duration-200',
                        ]"
                    >
                        <svg
                            class="mr-3 h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z"
                            />
                        </svg>
                        Dashboard
                    </Link>

                    <Link
                        :href="route('productores.index')"
                        :class="[
                            route().current('productores.*')
                                ? 'bg-blue-50 border-r-4 border-blue-500 text-blue-700'
                                : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
                            'group flex items-center px-2 py-2 text-sm font-medium rounded-l-md transition-colors duration-200',
                        ]"
                    >
                        <svg
                            class="mr-3 h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                            />
                        </svg>
                        Productores
                    </Link>

                    <!-- Documentos Section -->
                    <div class="mt-6">
                        <h3
                            class="px-2 text-xs font-semibold text-gray-500 uppercase tracking-wider"
                        >
                            Documentos
                        </h3>
                        <div class="mt-2 space-y-1">
                            <Link
                                :href="route('documentos.index')"
                                :class="[
                                    route().current('documentos.*')
                                        ? 'bg-blue-50 border-r-4 border-blue-500 text-blue-700'
                                        : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
                                    'group flex items-center px-2 py-2 text-sm font-medium rounded-l-md transition-colors duration-200',
                                ]"
                            >
                                <svg
                                    class="mr-3 h-5 w-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                    />
                                </svg>
                                Documentos
                            </Link>
                            <Link
                                :href="route('tipos-documento.index')"
                                :class="[
                                    route().current('tipos-documento.*')
                                        ? 'bg-blue-50 border-r-4 border-blue-500 text-blue-700'
                                        : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
                                    'group flex items-center px-2 py-2 text-sm font-medium rounded-l-md transition-colors duration-200',
                                ]"
                            >
                                <svg
                                    class="mr-3 h-5 w-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                    />
                                </svg>
                                Tipos de Documentos
                            </Link>
                        </div>
                    </div>

                    <!-- Importación Section -->
                    <div class="mt-6">
                        <h3
                            class="px-2 text-xs font-semibold text-gray-500 uppercase tracking-wider"
                        >
                            Importación
                        </h3>
                        <div class="mt-2 space-y-1">
                            <Link
                                :href="route('importacion.index')"
                                :class="[
                                    route().current('importacion.*')
                                        ? 'bg-blue-50 border-r-4 border-blue-500 text-blue-700'
                                        : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900',
                                    'group flex items-center px-2 py-2 text-sm font-medium rounded-l-md transition-colors duration-200',
                                ]"
                            >
                                <svg
                                    class="mr-3 h-5 w-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"
                                    />
                                </svg>
                                Importación Masiva
                            </Link>
                        </div>
                    </div>

                    <!-- Comunicaciones Section -->
                    <div class="mt-6">
                        <h3
                            class="px-2 text-xs font-semibold text-gray-500 uppercase tracking-wider"
                        >
                            Comunicaciones
                        </h3>
                        <div class="mt-2 space-y-1">
                            <a
                                href="#"
                                class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-l-md transition-colors duration-200"
                            >
                                <svg
                                    class="mr-3 h-5 w-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                                    />
                                </svg>
                                Historial
                            </a>
                            <a
                                href="#"
                                class="text-gray-600 hover:bg-gray-50 hover:text-gray-900 group flex items-center px-2 py-2 text-sm font-medium rounded-l-md transition-colors duration-200"
                            >
                                <svg
                                    class="mr-3 h-5 w-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 17h5l-5 5v-5zM4 1h5v5H4V1zm0 8h5v5H4V9zm8-8h5v5h-5V1zm0 8h5v5h-5V9z"
                                    />
                                </svg>
                                Notificaciones Masivas
                            </a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>

        <!-- Mobile sidebar -->
        <div class="md:hidden">
            <div v-show="sidebarOpen" class="fixed inset-0 flex z-40">
                <div
                    class="fixed inset-0 bg-gray-600 bg-opacity-75"
                    @click="sidebarOpen = false"
                ></div>
                <div
                    class="relative flex-1 flex flex-col max-w-xs w-full bg-white"
                >
                    <div class="absolute top-0 right-0 -mr-12 pt-2">
                        <button
                            @click="sidebarOpen = false"
                            class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white"
                        >
                            <svg
                                class="h-6 w-6 text-white"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>
                    <div class="flex-1 h-0 pt-5 pb-4 overflow-y-auto">
                        <div class="flex-shrink-0 flex items-center px-4">
                            <ApplicationLogo class="h-8 w-auto" />
                            <span class="ml-2 text-xl font-bold text-gray-800"
                                >CRM Tabaco</span
                            >
                        </div>
                        <nav class="mt-5 px-2 space-y-1">
                            <Link
                                :href="route('dashboard')"
                                :class="[
                                    route().current('dashboard')
                                        ? 'bg-blue-50 text-blue-700'
                                        : 'text-gray-600 hover:bg-gray-50',
                                    'group flex items-center px-2 py-2 text-base font-medium rounded-md',
                                ]"
                            >
                                <svg
                                    class="mr-4 h-6 w-6"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"
                                    />
                                </svg>
                                Dashboard
                            </Link>
                            <Link
                                :href="route('productores.index')"
                                :class="[
                                    route().current('productores.*')
                                        ? 'bg-blue-50 text-blue-700'
                                        : 'text-gray-600 hover:bg-gray-50',
                                    'group flex items-center px-2 py-2 text-base font-medium rounded-md',
                                ]"
                            >
                                <svg
                                    class="mr-4 h-6 w-6"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                    />
                                </svg>
                                Productores
                            </Link>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main content -->
        <div class="flex flex-col flex-1 overflow-hidden">
            <!-- Top header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div
                    class="flex items-center justify-between px-4 py-4 sm:px-6 lg:px-8"
                >
                    <div class="flex items-center">
                        <button
                            @click="sidebarOpen = true"
                            class="text-gray-500 focus:outline-none focus:text-gray-600 md:hidden"
                        >
                            <svg
                                class="h-6 w-6"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                            </svg>
                        </button>
                        <div class="ml-4 md:ml-0">
                            <slot name="header" />
                        </div>
                    </div>

                    <!-- User menu -->
                    <div class="flex items-center space-x-4">
                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button
                                    class="flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                >
                                    <div
                                        class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center"
                                    >
                                        <span
                                            class="text-sm font-medium text-white"
                                            >{{
                                                $page.props.auth.user.name
                                                    .charAt(0)
                                                    .toUpperCase()
                                            }}</span
                                        >
                                    </div>
                                    <span
                                        class="ml-2 text-sm font-medium text-gray-700 hidden sm:block"
                                        >{{ $page.props.auth.user.name }}</span
                                    >
                                    <svg
                                        class="ml-1 h-4 w-4 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M19 9l-7 7-7-7"
                                        />
                                    </svg>
                                </button>
                            </template>
                            <template #content>
                                <div class="px-4 py-2 text-xs text-gray-500">
                                    {{ $page.props.auth.user.email }}
                                </div>
                                <DropdownLink :href="route('profile.edit')">
                                    <svg
                                        class="mr-2 h-4 w-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                        />
                                    </svg>
                                    Perfil
                                </DropdownLink>
                                <DropdownLink
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                >
                                    <svg
                                        class="mr-2 h-4 w-4"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                                        />
                                    </svg>
                                    Cerrar Sesión
                                </DropdownLink>
                            </template>
                        </Dropdown>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 overflow-y-auto bg-gray-50">
                <div class="py-6">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <slot />
                    </div>
                </div>
            </main>
        </div>
    </div>
</template>
