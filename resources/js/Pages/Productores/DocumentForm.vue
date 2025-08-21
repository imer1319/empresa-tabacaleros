<template>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <!-- Encabezado del formulario -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-4">
                <div
                    class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center"
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
                            d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"
                        ></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">
                        {{
                            mode === "create"
                                ? "Nuevo Documento"
                                : "Editar Documento"
                        }}
                    </h2>
                    <p class="text-sm text-gray-500">
                        Complete los campos requeridos (*)
                    </p>
                </div>
            </div>
        </div>

        <form @submit.prevent="submitForm" class="space-y-6">
            <!-- Nombre y Tipo de Documento -->
            <div class="grid grid-cols-2 gap-4">
                <!-- Nombre del Documento -->
                <div>
                    <label
                        for="nombre"
                        class="block text-sm font-medium text-gray-700 mb-1"
                    >
                        Nombre del Documento *
                    </label>
                    <div class="relative rounded-md shadow-sm">
                        <input
                            id="nombre"
                            v-model="form.nombre"
                            type="text"
                            required
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                            placeholder="Ingrese el nombre del documento"
                        />
                    </div>
                    <p v-if="errors.nombre" class="mt-1 text-sm text-red-600">
                        {{ errors.nombre }}
                    </p>
                </div>

                <!-- Tipo de Documento -->
                <div>
                    <label
                        for="tipo_documento_id"
                        class="block text-sm font-medium text-gray-700 mb-1"
                    >
                        Tipo de Documento *
                    </label>
                    <div class="relative rounded-md shadow-sm">
                        <select
                            id="tipo_documento_id"
                            v-model="form.tipo_documento_id"
                            required
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out appearance-none"
                        >
                            <option value="">
                                Seleccionar tipo de documento
                            </option>
                            <option
                                v-for="tipo in tiposDocumento"
                                :key="tipo.id"
                                :value="tipo.id"
                            >
                                {{ tipo.nombre }}
                            </option>
                        </select>
                        <div
                            class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none"
                        >
                            <svg
                                class="h-5 w-5 text-gray-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 9l-7 7-7-7"
                                ></path>
                            </svg>
                        </div>
                    </div>
                    <p
                        v-if="errors.tipo_documento_id"
                        class="mt-1 text-sm text-red-600"
                    >
                        {{ errors.tipo_documento_id }}
                    </p>
                </div>
            </div>

            <!-- Archivo del Documento -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Archivo del Documento {{ mode === "create" ? "*" : "" }}
                </label>

                <!-- Mostrar archivo existente en modo edición -->
                <div
                    v-if="mode === 'edit' && documento?.archivo_nombre"
                    class="mt-4 p-4 bg-blue-50 rounded-lg flex items-center justify-between mb-4"
                >
                    <div class="flex items-center space-x-3">
                        <svg
                            class="w-8 h-8 text-blue-500"
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
                        <div>
                            <p class="text-sm font-medium text-gray-900">
                                {{ documento.archivo_nombre }}
                            </p>
                            <p class="text-xs text-gray-500">
                                Documento actual
                            </p>
                        </div>
                    </div>
                    <button
                        type="button"
                        @click="downloadDocument(documento)"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    >
                        <svg
                            class="-ml-0.5 mr-2 h-4 w-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                            />
                        </svg>
                        Descargar
                    </button>
                </div>

                <!-- Área de carga de archivo -->
                <div
                    class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-400 transition-colors duration-150 ease-in-out"
                >
                    <div class="space-y-1 text-center">
                        <svg
                            class="mx-auto h-12 w-12 text-gray-400"
                            stroke="currentColor"
                            fill="none"
                            viewBox="0 0 48 48"
                        >
                            <path
                                d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                        <div class="flex text-sm text-gray-600 justify-center">
                            <label
                                for="archivo"
                                class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500"
                            >
                                <span>Seleccionar archivo</span>
                                <input
                                    id="archivo"
                                    type="file"
                                    @change="handleFileChange"
                                    class="sr-only"
                                    :required="mode === 'create'"
                                />
                            </label>
                            <p class="pl-1">o arrastrar y soltar</p>
                        </div>
                        <p class="text-xs text-gray-500">
                            PDF, DOC, DOCX, XLS, XLSX hasta 10MB
                        </p>
                    </div>
                </div>

                <!-- Archivo seleccionado -->
                <div
                    v-if="selectedFile"
                    class="mt-4 p-4 bg-blue-50 rounded-lg flex items-center justify-between"
                >
                    <div class="flex items-center space-x-3">
                        <svg
                            class="w-8 h-8 text-blue-500"
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
                        <div>
                            <p class="text-sm font-medium text-gray-900">
                                {{ selectedFile.name }}
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ formatFileSize(selectedFile.size) }}
                            </p>
                        </div>
                    </div>
                    <button
                        type="button"
                        @click="removeFile"
                        class="inline-flex items-center p-2 text-red-600 hover:text-red-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                    >
                        <svg
                            class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                            ></path>
                        </svg>
                    </button>
                </div>
                <p
                    v-if="errors.archivo"
                    class="mt-2 text-sm text-red-600 flex items-center"
                >
                    <svg
                        class="w-4 h-4 mr-1"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        ></path>
                    </svg>
                    {{ errors.archivo }}
                </p>
            </div>

            <!-- Fechas y Estado -->
            <div class="grid grid-cols-3 gap-4">
                <!-- Fecha de Entrega -->
                <div>
                    <div class="flex items-center space-x-2 mb-2">
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
                        <label
                            for="fecha_entrega"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Entrega
                        </label>
                    </div>
                    <div class="relative">
                        <input
                            id="fecha_entrega"
                            type="date"
                            v-model="form.fecha_entrega"
                            class="mt-1 block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-gray-700"
                        />
                        <div
                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                        >
                            <svg
                                class="h-5 w-5 text-gray-400"
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
                    </div>
                    <p
                        v-if="errors.fecha_entrega"
                        class="mt-2 text-sm text-red-600 flex items-center"
                    >
                        <svg
                            class="w-4 h-4 mr-1"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            ></path>
                        </svg>
                        {{ errors.fecha_entrega }}
                    </p>
                </div>

                <!-- Fecha de Vencimiento -->
                <div>
                    <div class="flex items-center space-x-2 mb-2">
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
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 01118 0z"
                            ></path>
                        </svg>
                        <label
                            for="fecha_vencimiento"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Vencimiento
                        </label>
                    </div>
                    <div class="relative">
                        <input
                            id="fecha_vencimiento"
                            type="date"
                            v-model="form.fecha_vencimiento"
                            class="mt-1 block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-gray-700"
                        />
                        <div
                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                        >
                            <svg
                                class="h-5 w-5 text-gray-400"
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
                    <p
                        v-if="errors.fecha_vencimiento"
                        class="mt-2 text-sm text-red-600 flex items-center"
                    >
                        <svg
                            class="w-4 h-4 mr-1"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            ></path>
                        </svg>
                        {{ errors.fecha_vencimiento }}
                    </p>
                </div>

                <!-- Estado -->
                <div>
                    <div class="flex items-center space-x-2 mb-2">
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
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                            ></path>
                        </svg>
                        <label
                            for="estado"
                            class="block text-sm font-medium text-gray-700"
                        >
                            Estado
                        </label>
                    </div>
                    <div class="relative">
                        <select
                            id="estado"
                            v-model="form.estado"
                            class="mt-1 block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white text-gray-700"
                        >
                            <option value="pendiente">Pendiente</option>
                            <option value="aprobado">Aprobado</option>
                            <option value="rechazado">Rechazado</option>
                            <option value="vencido">Vencido</option>
                        </select>
                        <div
                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
                        >
                            <svg
                                class="h-5 w-5 text-gray-400"
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
                        </div>
                        <div
                            class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none"
                        >
                            <svg
                                class="h-5 w-5 text-gray-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 9l-7 7-7-7"
                                ></path>
                            </svg>
                        </div>
                    </div>
                    <p
                        v-if="errors.estado"
                        class="mt-2 text-sm text-red-600 flex items-center"
                    >
                        <svg
                            class="w-4 h-4 mr-1"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            ></path>
                        </svg>
                        {{ errors.estado }}
                    </p>
                </div>
            </div>
            <!-- Observaciones -->
            <div class="mt-4">
                <div class="flex items-center space-x-2 mb-2">
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
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                        ></path>
                    </svg>
                    <label
                        for="observaciones"
                        class="block text-sm font-medium text-gray-700"
                    >
                        Observaciones
                    </label>
                </div>
                <div class="relative">
                    <textarea
                        id="observaciones"
                        v-model="form.observaciones"
                        rows="3"
                        placeholder="Ingrese las observaciones del documento..."
                        class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 text-gray-700 resize-none"
                    ></textarea>
                </div>
                <p
                    v-if="errors.observaciones"
                    class="mt-2 text-sm text-red-600 flex items-center"
                >
                    <svg
                        class="w-4 h-4 mr-1"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        ></path>
                    </svg>
                    {{ errors.observaciones }}
                </p>
            </div>
            <!-- Botones -->
            <div class="flex justify-end space-x-3 pt-4">
                <button
                    type="button"
                    @click="$emit('cancel')"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150 ease-in-out"
                >
                    <svg
                        class="-ml-1 mr-2 h-5 w-5 text-gray-500"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        ></path>
                    </svg>
                    Cancelar
                </button>
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150 ease-in-out disabled:opacity-50"
                >
                    <svg
                        class="-ml-1 mr-2 h-5 w-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            v-if="mode === 'create'"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                        ></path>
                        <path
                            v-else
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7"
                        ></path>
                    </svg>
                    {{
                        mode === "create"
                            ? "Crear Documento"
                            : "Actualizar Documento"
                    }}
                </button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, watch } from "vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    productorId: {
        type: Number,
        required: true,
    },
    tiposDocumento: {
        type: Array,
        default: () => [],
    },
    documento: {
        type: Object,
        default: null,
    },
    mode: {
        type: String,
        default: "create", // 'create' or 'edit'
    },
    errors: {
        type: Object,
        default: () => ({}),
    },
});

const emit = defineEmits(["submitted", "cancel"]);

const selectedFile = ref(null);

const form = useForm({
    productor_id: props.productorId,
    nombre: props.documento?.nombre || "",
    tipo_documento_id: props.documento?.tipo_documento_id || "",
    archivo: null,
    observaciones: props.documento?.observaciones || "",
    estado: props.documento?.estado || "pendiente",
    fecha_entrega: props.documento?.fecha_entrega || "",
    fecha_vencimiento: props.documento?.fecha_vencimiento || "",
});

// Watch for documento changes
watch(
    () => props.documento,
    (newDocumento) => {
        if (newDocumento) {
            form.nombre = newDocumento.nombre || "";
            form.tipo_documento_id = newDocumento.tipo_documento_id || "";
            form.observaciones = newDocumento.observaciones || "";
            form.estado = newDocumento.estado || "pendiente";
            form.fecha_entrega = newDocumento.fecha_entrega || "";
            form.fecha_vencimiento = newDocumento.fecha_vencimiento || "";
        }
    }
);

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        // Validar tamaño del archivo (10MB máximo)
        if (file.size > 10 * 1024 * 1024) {
            alert(
                "El archivo es demasiado grande. El tamaño máximo permitido es 10MB."
            );
            event.target.value = "";
            return;
        }

        // Validar tipo de archivo (actualizado para coincidir con el texto)
        const allowedTypes = [
            "application/pdf",
            "application/msword",
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            "application/vnd.ms-excel",
            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        ];
        if (!allowedTypes.includes(file.type)) {
            alert(
                "Tipo de archivo no permitido. Solo se permiten archivos PDF, DOC, DOCX, XLS y XLSX."
            );
            event.target.value = "";
            return;
        }

        selectedFile.value = file;
        form.archivo = file;
    }
};

const downloadDocument = (documento) => {
    if (!documento || !documento.id) return;
    window.location.href = route("documentos.download", documento.id);
};

const removeFile = () => {
    selectedFile.value = null;
    form.archivo = null;
    // Reset file input if it exists
    const fileInput = document.querySelector('input[type="file"]');
    if (fileInput) {
        fileInput.value = "";
    }
};

const formatFileSize = (bytes) => {
    if (bytes === 0) return "0 Bytes";
    const k = 1024;
    const sizes = ["Bytes", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
};

const submitForm = () => {
    if (props.mode === "create") {
        form.post(route("documentos.store"), {
            onSuccess: () => {
                form.reset();
                selectedFile.value = null;
                emit("submitted");
            },
        });
    } else {
        form.put(route("documentos.update", props.documento.id), {
            onSuccess: () => {
                emit("submitted");
            },
        });
    }
};
</script>
