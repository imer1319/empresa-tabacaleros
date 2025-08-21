<template>
    <div>
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">
                Documentos del Productor
            </h3>
            <button
                @click="openDocumentoModal()"
                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150"
            >
                <svg
                    class="w-4 h-4 mr-2"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                    ></path>
                </svg>
                Subir Documento
            </button>
        </div>

        <div v-if="documentos && documentos.length > 0" class="space-y-4">
            <div
                v-for="documento in documentos"
                :key="documento.id"
                class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
            >
                <!-- Título del documento -->
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center justify-between space-x-3">
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ documento.nombre }}
                        </h3>
                        <!-- Estado -->
                        <div
                            class="flex items-center space-x-2 px-3 py-1 rounded-full text-sm font-medium text-green-600 bg-green-100"
                        >
                            <svg
                                class="w-5 h-5 text-green-500 mr-2"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                ></path>
                            </svg>
                            <span class="text-green-600 font-medium">
                                {{ documento.estado || "Entregado" }}
                            </span>
                        </div>
                    </div>
                    <!-- Acciones -->
                    <div class="flex justify-end space-x-3">
                        <button
                            @click="openViewModal(documento)"
                            class="inline-flex items-center justify-center p-2 text-blue-600 hover:text-blue-700 hover:bg-blue-100 rounded-lg transition-colors"
                            title="Ver documento"
                        >
                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                                version="1.1"
                            >
                                <path
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                ></path>
                                <path
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                ></path>
                            </svg>
                        </button>
                        <a
                            :href="route('documentos.download', documento.id)"
                            class="inline-flex items-center justify-center p-2 text-green-600 hover:text-green-700 hover:bg-green-100 rounded-lg transition-colors"
                            title="Descargar documento"
                            target="_blank"
                        >
                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                ></path>
                            </svg>
                        </a>
                        <button
                            @click="openDocumentoModal(documento)"
                            class="inline-flex items-center justify-center p-2 text-yellow-600 hover:text-yellow-700 hover:bg-yellow-100 rounded-lg transition-colors"
                            title="Editar documento"
                        >
                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                ></path>
                            </svg>
                        </button>
                        <button
                            @click="deleteDocument(documento.id)"
                            class="inline-flex items-center justify-center p-2 text-red-600 hover:text-red-700 hover:bg-red-100 rounded-lg transition-colors"
                            title="Eliminar documento"
                        >
                            <svg
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                ></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <!-- Información del documento -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">
                            Fecha de entrega:
                        </p>
                        <p class="text-sm font-medium text-gray-900">
                            {{
                                formatDateOnly(documento.fecha_entrega) ||
                                formatDate(documento.created_at)
                            }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Vence:</p>
                        <p class="text-sm font-medium text-gray-900">
                            {{
                                formatDateOnly(documento.fecha_vencimiento) ||
                                "No especificada"
                            }}
                        </p>
                    </div>
                </div>

                <!-- Observaciones -->
                <div class="mb-4 bg-gray-50 py-4 rounded-lg px-4">
                    <p class="text-sm text-gray-600 mb-1">
                        <strong>Observaciones:</strong>
                        {{
                            documento.observaciones ||
                            "Documento aprobado sin observaciones"
                        }}
                    </p>
                </div>
            </div>
        </div>
        <div v-else class="text-center py-8">
            <p class="text-gray-500">
                No hay documentos cargados para este productor.
            </p>
        </div>

        <!-- Modal para Documentos -->
        <Modal
            :show="showDocumentoModal"
            :title="modalTitle"
            :show-footer="false"
            @close="closeDocumentoModal"
        >
            <DocumentForm
                v-if="modalMode !== 'view'"
                :productor-id="productor.id"
                :tipos-documento="tiposDocumento"
                :documento="selectedDocumento"
                :mode="modalMode"
                :errors="errors"
                @submitted="refreshPage"
                @cancel="closeDocumentoModal"
            />

            <DocumentShow v-else :documento="selectedDocumento" />
        </Modal>

        <!-- Vista de documento individual -->
        <div
            v-if="selectedDocumento && modalMode === 'view'"
            class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6"
        >
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
                            {{ selectedDocumento.nombre }}
                        </h1>
                        <p class="text-gray-600">
                            Tipo:
                            {{
                                selectedDocumento.tipo_documento?.nombre ||
                                "Sin tipo"
                            }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <span
                        :class="getEstadoClass(selectedDocumento.estado)"
                        class="px-4 py-2 rounded-full text-sm font-medium"
                    >
                        {{ selectedDocumento.estado || "Pendiente" }}
                    </span>
                </div>
            </div>

            <!-- Información básica -->
            <div
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6"
            >
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">
                        Fecha de Creación
                    </h3>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ formatDate(selectedDocumento.created_at) }}
                    </p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">
                        Fecha de Entrega
                    </h3>
                    <p class="text-lg font-semibold text-gray-900">
                        {{
                            formatDateOnly(selectedDocumento.fecha_entrega) ||
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
                            formatDateOnly(
                                selectedDocumento.fecha_vencimiento
                            ) || "No especificada"
                        }}
                    </p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">
                        Fecha de Revisión
                    </h3>
                    <p class="text-lg font-semibold text-gray-900">
                        {{
                            formatDateOnly(selectedDocumento.fecha_revision) ||
                            "No revisado"
                        }}
                    </p>
                </div>
            </div>

            <!-- Información del archivo -->
            <div
                v-if="selectedDocumento.archivo_nombre"
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
                                {{ selectedDocumento.archivo_nombre }}
                            </p>
                            <p class="text-xs text-gray-600">
                                Tamaño:
                                {{
                                    selectedDocumento.tamaño_formateado ||
                                    "No disponible"
                                }}
                            </p>
                        </div>
                    </div>
                    <button
                        @click="downloadDocument(selectedDocumento)"
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
                v-if="selectedDocumento.observaciones"
                class="bg-yellow-50 p-6 rounded-lg mb-6"
            >
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    Observaciones
                </h3>
                <p class="text-gray-700 whitespace-pre-wrap">
                    {{ selectedDocumento.observaciones }}
                </p>
            </div>

            <!-- Información de revisión -->
            <div
                v-if="selectedDocumento.revisor"
                class="bg-gray-50 p-6 rounded-lg"
            >
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    Información de Revisión
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Revisado por:</p>
                        <p class="text-sm font-medium text-gray-900">
                            {{ selectedDocumento.revisor.name }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">
                            Fecha de revisión:
                        </p>
                        <p class="text-sm font-medium text-gray-900">
                            {{ formatDate(selectedDocumento.fecha_revision) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import Modal from "@/Components/Modal.vue";
import DocumentForm from "@/Pages/Productores/DocumentForm.vue";
import DocumentShow from "@/Pages/Productores/DocumentShow.vue";
import { ref, computed } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    productor: Object,
    tiposDocumento: Array,
    documentos: Array,
});

const emit = defineEmits(["openCommunicationModal"]);

// Variables reactivas para el modal de documentos
const showDocumentoModal = ref(false);
const selectedDocumento = ref(null);
const modalMode = ref("create"); // Modos: 'create', 'edit', 'view'
const errors = ref({});

const modalTitle = computed(() => {
    switch (modalMode.value) {
        case "create":
            return "Nuevo Documento";
        case "edit":
            return "Editar Documento";
        case "view":
            return "Detalles del Documento";
        default:
            return "Documento";
    }
});

// Métodos para manejar el modal de documentos
const openDocumentoModal = (documento = null) => {
    if (documento) {
        documento.fecha_entrega = documento.fecha_entrega
            ? new Date(documento.fecha_entrega).toISOString().split("T")[0]
            : "";
        documento.fecha_vencimiento = documento.fecha_vencimiento
            ? new Date(documento.fecha_vencimiento).toISOString().split("T")[0]
            : "";
    }
    selectedDocumento.value = documento;
    modalMode.value = documento ? "edit" : "create";
    showDocumentoModal.value = true;
};

const openViewModal = (documento) => {
    selectedDocumento.value = documento;
    modalMode.value = "view";
    showDocumentoModal.value = true;
};

const closeDocumentoModal = () => {
    showDocumentoModal.value = false;
    selectedDocumento.value = null;
};

const refreshPage = () => {
    router.reload();
};

const downloadDocument = (documento) => {
    if (!documento || !documento.id) return;
    window.location.href = route("documentos.download", documento.id);
};

const deleteDocument = (documentoId) => {
    if (confirm("¿Estás seguro de que deseas eliminar este documento?")) {
        router.delete(route("documentos.destroy", documentoId), {
            onSuccess: () => {
                router.reload();
            },
        });
    }
};

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
