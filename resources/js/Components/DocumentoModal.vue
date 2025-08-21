<template>
    <Modal :show="show" @close="closeModal" max-width="2xl">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-6">
                {{
                    isEditing
                        ? "Actualizar Documento"
                        : isViewing
                        ? "Ver Documento"
                        : "Subir Documento"
                }}
            </h2>

            <form @submit.prevent="submitForm" class="space-y-6">
                <!-- Productor -->
                <div>
                    <InputLabel for="productor" value="Productor" />
                    <select
                        id="productor"
                        v-model="form.productor_id"
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        required
                        disabled
                    >
                        <option :value="productorId">
                            40 - MAZZONE, JULIO CESAR
                        </option>
                    </select>
                </div>

                <!-- Información básica del documento -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <InputLabel for="nombre" value="Nombre del Documento" />
                        <TextInput
                            id="nombre"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.nombre"
                            required
                            :disabled="isViewing"
                        />
                        <InputError
                            :message="form.errors.nombre"
                            class="mt-2"
                        />
                    </div>

                    <div>
                        <InputLabel
                            for="tipo_documento_id"
                            value="Tipo de Documento"
                        />
                        <select
                            id="tipo_documento_id"
                            v-model="form.tipo_documento_id"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            required
                            :disabled="isViewing"
                        >
                            <option value="">
                                Seleccione un tipo de documento
                            </option>
                            <option
                                v-for="tipo in tiposDocumento"
                                :key="tipo.id"
                                :value="tipo.id"
                            >
                                {{ tipo.nombre }}
                            </option>
                        </select>
                        <InputError
                            :message="form.errors.tipo_documento_id"
                            class="mt-2"
                        />
                    </div>
                </div>

                <!-- Fechas y Estado -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <InputLabel
                            for="fecha_entrega"
                            value="Fecha de Entrega"
                        />
                        <TextInput
                            id="fecha_entrega"
                            type="date"
                            class="mt-1 block w-full"
                            v-model="form.fecha_entrega"
                            :disabled="isViewing"
                        />
                        <InputError
                            :message="form.errors.fecha_entrega"
                            class="mt-2"
                        />
                    </div>
                    <!-- Fecha de vencimiento -->
                    <div>
                        <InputLabel
                            for="fecha_vencimiento"
                            value="Fecha de Vencimiento"
                        />
                        <TextInput
                            id="fecha_vencimiento"
                            type="date"
                            class="mt-1 block w-full"
                            v-model="form.fecha_vencimiento"
                            :disabled="isViewing"
                        />
                        <InputError
                            :message="form.errors.fecha_vencimiento"
                            class="mt-2"
                        />
                    </div>
                    <!-- Estado -->
                    <div>
                        <InputLabel for="estado" value="Estado" />
                        <select
                            id="estado"
                            v-model="form.estado"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            :disabled="isViewing"
                        >
                            <option value="pendiente">Pendiente</option>
                            <option value="entregado">Entregado</option>
                            <option value="aprobado">Aprobado</option>
                            <option value="rechazado">Rechazado</option>
                        </select>
                        <InputError
                            :message="form.errors.estado"
                            class="mt-2"
                        />
                        <InputError
                            :message="form.errors.fecha_entrega"
                            class="mt-2"
                        />
                    </div>
                </div>

                <!-- Archivo del documento -->
                <div>
                    <InputLabel for="archivo" value="Archivo del Documento" />
                    <div
                        v-if="documento"
                        class="flex items-center space-x-4 bg-blue-50 p-4 rounded-lg mb-4"
                    >
                        <svg
                            class="w-8 h-8 text-gray-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"
                            ></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">
                                Archivo actual:
                                <span class="text-gray-600">{{
                                    documento.archivo_nombre
                                }}</span>
                            </p>
                        </div>
                        <a
                            :href="route('documentos.download', documento.id)"
                            target="_blank"
                            class="text-blue-600 hover:text-blue-800"
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
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                                ></path>
                            </svg>
                            Descargar
                        </a>
                    </div>
                    <div v-if="!isViewing" class="mt-2">
                        <div
                            class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center"
                        >
                            <svg
                                class="mx-auto h-12 w-12 text-gray-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3 3m0 0l-3-3m3 3V6"
                                ></path>
                            </svg>
                            <div
                                class="mt-4 flex text-sm text-gray-600 justify-center"
                            >
                                <label
                                    for="archivo"
                                    class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500"
                                >
                                    <span>{{ isEditing ? 'Reemplazar archivo' : 'Subir archivo' }}</span>
                                    <input
                                        type="file"
                                        id="archivo"
                                        @change="handleFileChange"
                                        class="sr-only"
                                        :required="!isEditing"
                                    />
                                </label>
                                <p class="pl-1">o arrastra y suelta</p>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">
                                PDF, JPG, PNG, DOC, DOCX, XLS, XLSX hasta 10MB
                            </p>
                        </div>
                        <InputError
                            :message="form.errors.archivo"
                            class="mt-2"
                        />
                    </div>
                </div>

                <!-- Observaciones -->
                <div>
                    <InputLabel for="observaciones" value="Observaciones" />
                    <textarea
                        id="observaciones"
                        v-model="form.observaciones"
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        rows="3"
                        :disabled="isViewing"
                        placeholder="Ingrese cualquier observación relevante sobre el documento"
                    ></textarea>
                    <InputError
                        :message="form.errors.observaciones"
                        class="mt-2"
                    />
                </div>

                <div class="flex items-center mt-4">
                    <input
                        type="checkbox"
                        id="es_requerido"
                        v-model="form.es_requerido"
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                        :disabled="isViewing"
                    />
                    <label
                        for="es_requerido"
                        class="ml-2 block text-sm text-gray-900"
                    >
                        Este documento es requerido
                    </label>
                </div>
            </form>

            <!-- Botones de acción -->
            <div class="flex justify-end space-x-3 mt-6">
                <SecondaryButton
                    @click="closeModal"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-800"
                >
                    CANCELAR
                </SecondaryButton>
                <PrimaryButton
                    v-if="!isViewing"
                    @click="submitForm"
                    :disabled="form.processing"
                    class="bg-indigo-600 hover:bg-indigo-700"
                >
                    ACTUALIZAR DOCUMENTO
                </PrimaryButton>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import { ref, defineProps, defineEmits, watch, computed } from "vue";
import { useForm } from "@inertiajs/vue3";
import Modal from "@/Components/Modal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps({
    show: {
        type: Boolean,
        required: true,
    },
    tiposDocumento: {
        type: Array,
        required: true,
    },
    productorId: {
        type: Number,
        required: true,
    },
    mode: {
        type: String,
        default: "create",
        validator: (value) => ["create", "edit", "view"].includes(value),
    },
    documento: {
        type: Object,
        default: null,
    },
});

const emit = defineEmits(["close", "submitted"]);

const mode = ref(props.mode);
const isEditing = computed(() => mode.value === "edit");
const isViewing = computed(() => mode.value === "view");
const selectedFile = ref(null);

// Watcher para actualizar el modo cuando cambia
watch(
    () => props.mode,
    (newMode) => {
        mode.value = newMode;
    },
    { immediate: true }
);

const form = useForm({
    productor_id: props.productorId,
    nombre: props.documento?.nombre || "",
    tipo_documento_id: props.documento?.tipo_documento_id || "",
    archivo: null,
    observaciones: props.documento?.observaciones || "",
    estado: props.documento?.estado || "pendiente",
    fecha_entrega: props.documento?.fecha_entrega || null,
    fecha_vencimiento: props.documento?.fecha_vencimiento || null,
});

// Watcher para actualizar el formulario cuando cambia el documento
watch(
    () => props.documento,
    (newDoc) => {
        if (newDoc) {
            form.nombre = newDoc.nombre || "";
            form.tipo_documento_id = newDoc.tipo_documento_id || "";
            form.observaciones = newDoc.observaciones || "";
            form.estado = newDoc.estado || "pendiente";
            form.fecha_entrega = newDoc.fecha_entrega || null;
            form.fecha_vencimiento = newDoc.fecha_vencimiento || null;
        } else {
            form.reset();
        }
    },
    { immediate: true }
);

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        if (file.size > 10 * 1024 * 1024) {
            alert(
                "El archivo es demasiado grande. El tamaño máximo permitido es 10MB."
            );
            event.target.value = "";
            return;
        }

        const allowedTypes = [
            "application/pdf",
            "image/jpeg",
            "image/jpg",
            "image/png",
            "application/msword",
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
        ];
        if (!allowedTypes.includes(file.type)) {
            alert(
                "Tipo de archivo no permitido. Solo se permiten archivos PDF, JPG, PNG, DOC y DOCX."
            );
            event.target.value = "";
            return;
        }

        selectedFile.value = file;
        form.archivo = file;
    }
};

const submitForm = () => {
    if (isEditing.value && props.documento?.id) {
        // Si estamos editando y hay un nuevo archivo, usamos FormData
        if (form.archivo) {
            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('archivo', form.archivo);
            formData.append('nombre', form.nombre);
            formData.append('tipo_documento_id', form.tipo_documento_id);
            formData.append('observaciones', form.observaciones);
            formData.append('estado', form.estado);
            formData.append('fecha_entrega', form.fecha_entrega);
            formData.append('fecha_vencimiento', form.fecha_vencimiento);
            formData.append('es_requerido', form.es_requerido);

            form.post(route("documentos.update", props.documento.id), {
                onSuccess: () => {
                    emit("submitted");
                    closeModal();
                },
                preserveScroll: true,
            });
        } else {
            // Si no hay nuevo archivo, usamos PUT normal
            form.put(route("documentos.update", props.documento.id), {
                onSuccess: () => {
                    emit("submitted");
                    closeModal();
                },
                preserveScroll: true,
            });
        }
    } else {
        form.post(route("documentos.store"), {
            onSuccess: () => {
                emit("submitted");
                closeModal();
            },
            preserveScroll: true,
        });
    }
};

const closeModal = () => {
    form.reset();
    form.clearErrors();
    selectedFile.value = null;
    emit("close");
};
</script>
