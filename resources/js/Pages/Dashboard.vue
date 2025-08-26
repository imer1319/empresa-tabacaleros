<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";

defineProps({
    estadisticas: {
        type: Object,
        required: true,
        default: () => ({
            totalProductores: 0,
            documentosAprobados: 0,
            documentosPendientes: 0,
            comunicacionesEnviadas: 0,
        }),
    },
    actividadesRecientes: {
        type: Array,
        required: true,
        default: () => [],
    },
    estadoDocumentos: {
        type: Object,
        required: true,
        default: () => ({}),
    },
});
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
                <!-- Estadísticas Generales -->
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
                                    {{ estadisticas.totalProductores }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    Total Productores
                                </p>
                                <p class="text-xs text-green-600 mt-1">
                                    +{{ estadisticas.nuevosProductoresMes }}
                                    este mes
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
                                    {{ estadisticas.documentosAprobados }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    Documentos Aprobados
                                </p>
                                <p class="text-xs text-green-600 mt-1">
                                    +{{
                                        estadisticas.documentosAprobadosSemana
                                    }}
                                    esta semana
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
                                    {{ estadisticas.documentosPendientes }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    Documentos Pendientes
                                </p>
                                <p class="text-xs text-red-600 mt-1">
                                    -{{ estadisticas.documentosPendientesAyer }}
                                    desde ayer
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
                                    {{ estadisticas.comunicacionesEnviadas }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    Comunicaciones Enviadas
                                </p>
                                <p class="text-xs text-green-600 mt-1">
                                    +{{
                                        estadisticas.comunicacionesEnviadasHoy
                                    }}
                                    hoy
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
                            </div>
                            <div class="p-6">
                                <div class="space-y-4">
                                    <template
                                        v-for="actividad in actividadesRecientes"
                                        :key="actividad.id"
                                    >
                                        <div class="flex items-start">
                                            <div
                                                :class="{
                                                    'flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center': true,
                                                    'bg-green-100':
                                                        actividad.tipo ===
                                                        'aprobado',
                                                    'bg-red-100':
                                                        actividad.tipo ===
                                                        'rechazado',
                                                    'bg-yellow-100':
                                                        actividad.tipo ===
                                                        'vencimiento',
                                                    'bg-blue-100':
                                                        actividad.tipo ===
                                                        'comunicacion',
                                                }"
                                            >
                                                <svg
                                                    :class="{
                                                        'w-5 h-5': true,
                                                        'text-green-600':
                                                            actividad.tipo ===
                                                            'aprobado',
                                                        'text-red-600':
                                                            actividad.tipo ===
                                                            'rechazado',
                                                        'text-yellow-600':
                                                            actividad.tipo ===
                                                            'vencimiento',
                                                        'text-blue-600':
                                                            actividad.tipo ===
                                                            'comunicacion',
                                                    }"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                >
                                                    <path
                                                        v-if="
                                                            actividad.tipo ===
                                                            'aprobado'
                                                        "
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M5 13l4 4L19 7"
                                                    ></path>
                                                    <path
                                                        v-else-if="
                                                            actividad.tipo ===
                                                            'notificacion'
                                                        "
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                                    ></path>
                                                    <path
                                                        v-else-if="
                                                            actividad.tipo ===
                                                            'registro'
                                                        "
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M5 13l4 4L19 7"
                                                    ></path>
                                                    <path
                                                        v-else-if="
                                                            actividad.tipo ===
                                                            'comunicacion'
                                                        "
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                                    ></path>
                                                    <path
                                                        v-else-if="
                                                            actividad.tipo ===
                                                            'vencimiento'
                                                        "
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                                    ></path>
                                                    <path
                                                        v-else
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"
                                                    ></path>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0 ml-2">
                                                <p
                                                    class="text-sm text-gray-900"
                                                    v-html="
                                                        actividad.descripcion
                                                    "
                                                ></p>
                                                <p
                                                    class="text-xs text-gray-500 mt-1"
                                                >
                                                    {{ actividad.tiempo }}
                                                </p>
                                            </div>
                                        </div>
                                    </template>
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
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2-2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                                    ></path>
                                </svg>
                            </div>
                            <div class="p-6">
                                <div class="space-y-6">
                                    <template
                                        v-for="(
                                            estadisticas, tipo
                                        ) in estadoDocumentos"
                                        :key="tipo"
                                    >
                                        <div>
                                            <div
                                                class="flex items-center justify-between mb-2"
                                            >
                                                <span
                                                    class="text-sm font-medium text-gray-900"
                                                    >{{ tipo }}</span
                                                >
                                                <span
                                                    class="text-sm text-gray-600"
                                                    >{{
                                                        estadisticas.completados
                                                    }}/{{
                                                        estadisticas.total
                                                    }}</span
                                                >
                                            </div>
                                            <div
                                                class="w-full bg-gray-200 rounded-full h-2"
                                            >
                                                <div
                                                    class="bg-green-600 h-2 rounded-full"
                                                    :style="{
                                                        width:
                                                            estadisticas.porcentaje +
                                                            '%',
                                                    }"
                                                ></div>
                                            </div>
                                            <div
                                                class="flex items-center justify-between mt-1"
                                            >
                                                <span
                                                    class="text-xs text-green-600"
                                                    >{{
                                                        estadisticas.porcentaje
                                                    }}% completado</span
                                                >
                                                <span
                                                    class="text-xs text-orange-600"
                                                    >{{
                                                        estadisticas.pendientes
                                                    }}
                                                    pendientes</span
                                                >
                                            </div>
                                        </div>
                                    </template>
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
