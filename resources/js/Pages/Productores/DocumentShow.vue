<template>
    <div v-if="!documento" class="p-4 text-center text-gray-500">
        No se encontró información del documento.
    </div>
    <div v-else class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-4">
                <div
                    class="w-16 h-16 bg-blue-500 rounded-lg flex items-center justify-center"
                >
                    <svg
                        class="w-8 h-8 text-white"
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
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        {{ documento.nombre }}
                    </h1>
                    <p class="text-gray-600">
                        Tipo:
                        {{ documento.tipo_documento?.nombre || "Sin tipo" }}
                    </p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <span
                    :class="getEstadoClass(documento.estado)"
                    class="px-4 py-2 rounded-full text-sm font-medium"
                >
                    {{ documento.estado || "Pendiente" }}
                </span>
            </div>
        </div>

        <!-- Información básica -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-sm font-medium text-gray-500 mb-2">
                    Fecha de Creación
                </h3>
                <p class="text-lg font-semibold text-gray-900">
                    {{ formatDate(documento.created_at) }}
                </p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-sm font-medium text-gray-500 mb-2">
                    Fecha de Entrega
                </h3>
                <p class="text-lg font-semibold text-gray-900">
                    {{
                        formatDateOnly(documento.fecha_entrega) ||
                        "No especificada"
                    }}
                </p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-sm font-medium text-gray-500 mb-2">
                    Fecha de Vencimiento
                </h3>
                <p class="text-lg font-semibold text-gray-900">
                    {{
                        formatDateOnly(documento.fecha_vencimiento) ||
                        "No especificada"
                    }}
                </p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-sm font-medium text-gray-500 mb-2">
                    Fecha de Revisión
                </h3>
                <p class="text-lg font-semibold text-gray-900">
                    {{
                        formatDateOnly(documento.fecha_revision) ||
                        "No revisado"
                    }}
                </p>
            </div>
        </div>

        <!-- Información del archivo -->
        <div
            v-if="documento.archivo_nombre"
            class="bg-blue-50 p-6 rounded-lg mb-6"
        >
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                Archivo Adjunto
            </h3>
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center"
                    >
                        <svg
                            class="w-6 h-6 text-white"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                            version="1.1"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"
                            ></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">
                            {{ documento.archivo_nombre }}
                        </p>
                        <p class="text-xs text-gray-600">
                            Tamaño:
                            {{ documento.tamaño_formateado || "No disponible" }}
                        </p>
                    </div>
                </div>
                <button
                    @click="downloadDocument(documento)"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    <svg
                        class="w-4 h-4 mr-2"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                        version="1.1"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                        ></path>
                    </svg>
                    Descargar
                </button>
            </div>
        </div>

        <!-- Observaciones -->
        <div
            v-if="documento.observaciones"
            class="bg-yellow-50 p-6 rounded-lg mb-6"
        >
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                Observaciones
            </h3>
            <p class="text-gray-700 whitespace-pre-wrap">
                {{ documento.observaciones }}
            </p>
        </div>

        <!-- Información de revisión -->
        <div v-if="documento.revisor" class="bg-gray-50 p-6 rounded-lg">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                Información de Revisión
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Revisado por:</p>
                    <p class="text-sm font-medium text-gray-900">
                        {{ documento.revisor.name }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Fecha de revisión:</p>
                    <p class="text-sm font-medium text-gray-900">
                        {{ formatDate(documento.fecha_revision) }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from "vue";

const props = defineProps({
    documento: {
        type: Object,
        default: null,
    },
});

const getEstadoClass = (estado) => {
    switch (estado) {
        case "Aprobado":
            return "bg-green-100 text-green-800";
        case "En proceso":
            return "bg-yellow-100 text-yellow-800";
        case "Faltante":
            return "bg-red-100 text-red-800";
        default:
            return "bg-gray-100 text-gray-800";
    }
};

const downloadDocument = (documento) => {
    if (!documento || !documento.id) return;
    window.location.href = route("documentos.download", documento.id);
};

const formatDate = (dateString) => {
    if (!dateString) return "No especificada";
    const date = new Date(dateString);
    return date.toLocaleDateString("es-AR", {
        year: "numeric",
        month: "long",
        day: "numeric",
        timeZone: "America/Argentina/Buenos_Aires",
    });
};

const formatDateOnly = (dateString) => {
    if (!dateString) return "No especificada";
    const date = new Date(dateString);
    return date.toLocaleDateString("es-AR", {
        year: "numeric",
        month: "long",
        day: "numeric",
        timeZone: "America/Argentina/Buenos_Aires",
    });
};
</script>
