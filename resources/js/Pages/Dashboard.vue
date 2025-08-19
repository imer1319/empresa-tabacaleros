<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Tarjetas de estadísticas -->
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8"
                >
                    <!-- Total Productores -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                    >
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center"
                                >
                                    <svg
                                        class="w-6 h-6 text-white"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                        ></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-2xl font-bold text-gray-900">
                                    247
                                </p>
                                <p class="text-sm text-gray-600">
                                    Total Productores
                                </p>
                                <p class="text-xs text-green-600 mt-1">
                                    +12 este mes
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Documentos Aprobados -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                    >
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center"
                                >
                                    <svg
                                        class="w-6 h-6 text-white"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                        ></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-2xl font-bold text-gray-900">
                                    1,834
                                </p>
                                <p class="text-sm text-gray-600">
                                    Documentos Aprobados
                                </p>
                                <p class="text-xs text-green-600 mt-1">
                                    +89 esta semana
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Documentos Pendientes -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                    >
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-12 h-12 bg-orange-500 rounded-lg flex items-center justify-center"
                                >
                                    <svg
                                        class="w-6 h-6 text-white"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                        ></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-2xl font-bold text-gray-900">
                                    18
                                </p>
                                <p class="text-sm text-gray-600">
                                    Documentos Pendientes
                                </p>
                                <p class="text-xs text-red-600 mt-1">
                                    -5 desde ayer
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Comunicaciones Enviadas -->
                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                    >
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div
                                    class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center"
                                >
                                    <svg
                                        class="w-6 h-6 text-white"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                                        ></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-2xl font-bold text-gray-900">
                                    156
                                </p>
                                <p class="text-sm text-gray-600">
                                    Comunicaciones Enviadas
                                </p>
                                <p class="text-xs text-green-600 mt-1">
                                    +23 hoy
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contenido principal -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Actividad Reciente -->
                    <div class="lg:col-span-1">
                        <div
                            class="bg-white rounded-xl shadow-sm border border-gray-200"
                        >
                            <div
                                class="px-6 py-4 border-b border-gray-200 flex items-center justify-between"
                            >
                                <h3 class="text-lg font-medium text-gray-900">
                                    Actividad Reciente
                                </h3>
                                <svg
                                    class="w-5 h-5 text-gray-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                    ></path>
                                </svg>
                            </div>
                            <div class="p-6">
                                <div class="space-y-4">
                                    <!-- Actividad 1 -->
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center"
                                            >
                                                <svg
                                                    class="w-4 h-4 text-green-600"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M5 13l4 4L19 7"
                                                    ></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-gray-900">
                                                Documento "Contrato de Siembra"
                                                aprobado para Juan Pérez
                                            </p>
                                            <p
                                                class="text-xs text-gray-500 mt-1"
                                            >
                                                Hace 15 min
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Actividad 2 -->
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center"
                                            >
                                                <svg
                                                    class="w-4 h-4 text-blue-600"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                                    ></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-gray-900">
                                                Notificación masiva enviada a 45
                                                productores
                                            </p>
                                            <p
                                                class="text-xs text-gray-500 mt-1"
                                            >
                                                Hace 1 hora
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Actividad 3 -->
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center"
                                            >
                                                <svg
                                                    class="w-4 h-4 text-green-600"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M5 13l4 4L19 7"
                                                    ></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-gray-900">
                                                Nuevo productor registrado:
                                                María García
                                            </p>
                                            <p
                                                class="text-xs text-gray-500 mt-1"
                                            >
                                                Hace 2 horas
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Actividad 4 -->
                                    <div class="flex items-start space-x-3">
                                        <div class="flex-shrink-0">
                                            <div
                                                class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center"
                                            >
                                                <svg
                                                    class="w-4 h-4 text-orange-600"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"
                                                    ></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm text-gray-900">
                                                Documento faltante detectado
                                                para Carlos López
                                            </p>
                                            <p
                                                class="text-xs text-gray-500 mt-1"
                                            >
                                                Hace 3 horas
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Estado de Documentos -->
                    <div>
                        <div
                            class="bg-white rounded-xl shadow-sm border border-gray-200"
                        >
                            <div
                                class="px-6 py-4 border-b border-gray-200 flex items-center justify-between"
                            >
                                <h3 class="text-lg font-medium text-gray-900">
                                    Estado de Documentos
                                </h3>
                                <svg
                                    class="w-5 h-5 text-gray-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                                    ></path>
                                </svg>
                            </div>
                            <div class="p-6">
                                <div class="space-y-6">
                                    <!-- Contratos de Siembra -->
                                    <div>
                                        <div
                                            class="flex items-center justify-between mb-2"
                                        >
                                            <span
                                                class="text-sm font-medium text-gray-900"
                                                >Contratos de Siembra</span
                                            >
                                            <span class="text-sm text-gray-600"
                                                >235/247</span
                                            >
                                        </div>
                                        <div
                                            class="w-full bg-gray-200 rounded-full h-2"
                                        >
                                            <div
                                                class="bg-green-600 h-2 rounded-full"
                                                style="width: 95%"
                                            ></div>
                                        </div>
                                        <div
                                            class="flex items-center justify-between mt-1"
                                        >
                                            <span class="text-xs text-green-600"
                                                >95% completado</span
                                            >
                                            <span
                                                class="text-xs text-orange-600"
                                                >12 pendientes</span
                                            >
                                        </div>
                                    </div>

                                    <!-- Certificados AFIP -->
                                    <div>
                                        <div
                                            class="flex items-center justify-between mb-2"
                                        >
                                            <span
                                                class="text-sm font-medium text-gray-900"
                                                >Certificados AFIP</span
                                            >
                                            <span class="text-sm text-gray-600"
                                                >241/247</span
                                            >
                                        </div>
                                        <div
                                            class="w-full bg-gray-200 rounded-full h-2"
                                        >
                                            <div
                                                class="bg-green-600 h-2 rounded-full"
                                                style="width: 98%"
                                            ></div>
                                        </div>
                                        <div
                                            class="flex items-center justify-between mt-1"
                                        >
                                            <span class="text-xs text-green-600"
                                                >98% completado</span
                                            >
                                            <span
                                                class="text-xs text-orange-600"
                                                >6 pendientes</span
                                            >
                                        </div>
                                    </div>

                                    <!-- Licencias Municipales -->
                                    <div>
                                        <div
                                            class="flex items-center justify-between mb-2"
                                        >
                                            <span
                                                class="text-sm font-medium text-gray-900"
                                                >Licencias Municipales</span
                                            >
                                            <span class="text-sm text-gray-600"
                                                >229/247</span
                                            >
                                        </div>
                                        <div
                                            class="w-full bg-gray-200 rounded-full h-2"
                                        >
                                            <div
                                                class="bg-green-600 h-2 rounded-full"
                                                style="width: 93%"
                                            ></div>
                                        </div>
                                        <div
                                            class="flex items-center justify-between mt-1"
                                        >
                                            <span class="text-xs text-green-600"
                                                >93% completado</span
                                            >
                                            <span
                                                class="text-xs text-orange-600"
                                                >18 pendientes</span
                                            >
                                        </div>
                                    </div>

                                    <!-- Seguros Agrícolas -->
                                    <div>
                                        <div
                                            class="flex items-center justify-between mb-2"
                                        >
                                            <span
                                                class="text-sm font-medium text-gray-900"
                                                >Seguros Agrícolas</span
                                            >
                                            <span class="text-sm text-gray-600"
                                                >244/247</span
                                            >
                                        </div>
                                        <div
                                            class="w-full bg-gray-200 rounded-full h-2"
                                        >
                                            <div
                                                class="bg-green-600 h-2 rounded-full"
                                                style="width: 99%"
                                            ></div>
                                        </div>
                                        <div
                                            class="flex items-center justify-between mt-1"
                                        >
                                            <span class="text-xs text-green-600"
                                                >99% completado</span
                                            >
                                            <span
                                                class="text-xs text-orange-600"
                                                >3 pendientes</span
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones Rápidas -->
                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Acciones Rápidas
                    </h3>
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4"
                    >
                        <!-- Nuevo Productor -->
                        <div
                            class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow cursor-pointer"
                        >
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg
                                        class="w-8 h-8 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                        ></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        Nuevo Productor
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Subir Documento -->
                        <div
                            class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow cursor-pointer"
                        >
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg
                                        class="w-8 h-8 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                        ></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        Subir Documento
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Enviar Comunicación -->
                        <div
                            class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow cursor-pointer"
                        >
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg
                                        class="w-8 h-8 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"
                                        ></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        Enviar Comunicación
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Ver Reportes -->
                        <div
                            class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow cursor-pointer"
                        >
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg
                                        class="w-8 h-8 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2-2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                                        ></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        Ver Reportes
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
