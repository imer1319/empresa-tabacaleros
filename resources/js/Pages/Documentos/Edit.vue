<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Editar Documento: {{ documento.nombre }}
                </h2>
                <div class="flex space-x-2">
                    <Link
                        :href="route('documentos.show', documento.id)"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        Ver Documento
                    </Link>
                    <Link
                        :href="route('documentos.index')"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        Volver al Listado
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <form @submit.prevent="submit">
                        <!-- Selección de Productor -->
                        <div class="mb-6">
                            <label for="productor_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Productor *
                            </label>
                            <select
                                id="productor_id"
                                v-model="form.productor_id"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                :class="{ 'border-red-500': form.errors.productor_id }"
                                required
                            >
                                <option value="" disabled>Seleccione un productor</option>
                                <option
                                    v-for="prod in productores"
                                    :key="prod.id"
                                    :value="prod.id"
                                >
                                    {{ prod.numero_productor }} - {{ prod.nombre_completo }}
                                </option>
                            </select>
                            <div v-if="form.errors.productor_id" class="mt-1 text-sm text-red-600">
                                {{ form.errors.productor_id }}
                            </div>
                        </div>

                        <!-- Información del documento -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Nombre del documento -->
                            <div>
                                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nombre del Documento *
                                </label>
                                <input
                                    id="nombre"
                                    v-model="form.nombre"
                                    type="text"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                    :class="{ 'border-red-500': form.errors.nombre }"
                                    placeholder="Ej: Certificado de Calidad"
                                    required
                                />
                                <div v-if="form.errors.nombre" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.nombre }}
                                </div>
                            </div>

                            <!-- Tipo de documento -->
                            <div>
                                <label for="tipo_documento_id" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tipo de Documento *
                                </label>
                                <select
                                    id="tipo_documento_id"
                                    v-model="form.tipo_documento_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                    :class="{ 'border-red-500': form.errors.tipo_documento_id }"
                                    required
                                >
                                    <option value="" disabled>Seleccione un tipo</option>
                                    <option
                                        v-for="tipoDoc in tiposDocumento"
                                        :key="tipoDoc.id"
                                        :value="tipoDoc.id"
                                    >
                                        {{ tipoDoc.nombre }}
                                    </option>
                                </select>
                                <div v-if="form.errors.tipo" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.tipo }}
                                </div>
                            </div>
                        </div>

                        <!-- Fechas -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <!-- Fecha de entrega -->
                            <div>
                                <label for="fecha_entrega" class="block text-sm font-medium text-gray-700 mb-2">
                                    Fecha de Entrega
                                </label>
                                <input
                                    id="fecha_entrega"
                                    v-model="form.fecha_entrega"
                                    type="date"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                    :class="{ 'border-red-500': form.errors.fecha_entrega }"
                                />
                                <div v-if="form.errors.fecha_entrega" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.fecha_entrega }}
                                </div>
                            </div>

                            <!-- Fecha de vencimiento -->
                            <div>
                                <label for="fecha_vencimiento" class="block text-sm font-medium text-gray-700 mb-2">
                                    Fecha de Vencimiento
                                </label>
                                <input
                                    id="fecha_vencimiento"
                                    v-model="form.fecha_vencimiento"
                                    type="date"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                    :class="{ 'border-red-500': form.errors.fecha_vencimiento }"
                                />
                                <div v-if="form.errors.fecha_vencimiento" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.fecha_vencimiento }}
                                </div>
                            </div>

                            <!-- Estado -->
                            <div>
                                <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">
                                    Estado
                                </label>
                                <select
                                    id="estado"
                                    v-model="form.estado"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                    :class="{ 'border-red-500': form.errors.estado }"
                                >
                                    <option value="pendiente">Pendiente</option>
                                    <option value="entregado">Entregado</option>
                                    <option value="aprobado">Aprobado</option>
                                    <option value="rechazado">Rechazado</option>
                                </select>
                                <div v-if="form.errors.estado" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.estado }}
                                </div>
                            </div>
                        </div>

                        <!-- Archivo actual y nuevo -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Archivo del Documento
                            </label>
                            
                            <!-- Archivo actual -->
                            <div v-if="documento.archivo_nombre" class="mb-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Archivo actual:</p>
                                            <p class="text-sm text-gray-600">{{ documento.archivo_nombre }}</p>
                                        </div>
                                    </div>
                                    <a
                                        :href="route('documentos.download', documento.id)"
                                        class="inline-flex items-center px-3 py-2 border border-blue-300 shadow-sm text-sm leading-4 font-medium rounded-md text-blue-700 bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    >
                                        Descargar
                                    </a>
                                </div>
                            </div>

                            <!-- Subir nuevo archivo -->
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="archivo" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span>{{ documento.archivo_nombre ? 'Reemplazar archivo' : 'Subir un archivo' }}</span>
                                            <input
                                                id="archivo"
                                                type="file"
                                                class="sr-only"
                                                @change="handleFileUpload"
                                                accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx"
                                            />
                                        </label>
                                        <p class="pl-1">o arrastra y suelta</p>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        PDF, JPG, PNG, DOC, DOCX, XLS, XLSX hasta 10MB
                                    </p>
                                </div>
                            </div>
                            <div v-if="selectedFile" class="mt-2 p-3 bg-green-50 rounded-md border border-green-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="text-sm text-gray-900">Nuevo archivo: {{ selectedFile.name }}</span>
                                        <span class="text-xs text-gray-500">({{ formatFileSize(selectedFile.size) }})</span>
                                    </div>
                                    <button
                                        type="button"
                                        @click="removeFile"
                                        class="text-red-600 hover:text-red-500"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div v-if="form.errors.archivo" class="mt-1 text-sm text-red-600">
                                {{ form.errors.archivo }}
                            </div>
                        </div>

                        <!-- Observaciones -->
                        <div class="mb-6">
                            <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-2">
                                Observaciones
                            </label>
                            <textarea
                                id="observaciones"
                                v-model="form.observaciones"
                                rows="4"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                :class="{ 'border-red-500': form.errors.observaciones }"
                                placeholder="Ingrese observaciones adicionales sobre el documento..."
                            ></textarea>
                            <div v-if="form.errors.observaciones" class="mt-1 text-sm text-red-600">
                                {{ form.errors.observaciones }}
                            </div>
                        </div>

                        <!-- Documento requerido -->
                        <div class="mb-6">
                            <div class="flex items-center">
                                <input
                                    id="es_requerido"
                                    v-model="form.es_requerido"
                                    type="checkbox"
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                />
                                <label for="es_requerido" class="ml-2 block text-sm text-gray-900">
                                    Este documento es requerido
                                </label>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="flex items-center justify-end space-x-3">
                            <Link
                                :href="route('documentos.show', documento.id)"
                                class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                Cancelar
                            </Link>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50"
                            >
                                <span v-if="form.processing">Actualizando...</span>
                                <span v-else>Actualizar Documento</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";

export default {
    components: {
        AuthenticatedLayout,
        Link,
    },
    props: {
        documento: Object,
        tiposDocumento: Array,
        productores: Array,
    },
    data() {
        return {
            selectedFile: null,
            form: useForm({
                productor_id: this.documento.productor_id,
                nombre: this.documento.nombre,
                tipo_documento_id: this.documento.tipo_documento_id,
                archivo: null,
                observaciones: this.documento.observaciones || "",
                es_requerido: this.documento.es_requerido || false,
                estado: this.documento.estado,
                fecha_entrega: this.documento.fecha_entrega ? this.documento.fecha_entrega.split(' ')[0] : "",
                fecha_vencimiento: this.documento.fecha_vencimiento ? this.documento.fecha_vencimiento.split(' ')[0] : "",
            }),
        };
    },
    methods: {
        handleFileUpload(event) {
            const file = event.target.files[0];
            if (!file) return;

            // Validar tamaño (10MB)
            if (file.size > 10 * 1024 * 1024) {
                alert("El archivo es demasiado grande. El tamaño máximo es 10MB.");
                event.target.value = "";
                return;
            }

            // Validar tipo de archivo
            const allowedTypes = [
                "application/pdf",
                "image/jpeg",
                "image/jpg",
                "image/png",
                "application/msword",
                "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
                "application/vnd.ms-excel",
                "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            ];
            if (!allowedTypes.includes(file.type)) {
                alert(
                    "Tipo de archivo no permitido. Solo se permiten archivos PDF, JPG, PNG, DOC, DOCX, XLS y XLSX."
                );
                event.target.value = "";
                return;
            }

            this.selectedFile = file;
            this.form.archivo = file;
        },
        removeFile() {
            this.selectedFile = null;
            this.form.archivo = null;
            const fileInput = document.querySelector('input[type="file"]');
            if (fileInput) {
                fileInput.value = "";
            }
        },
        formatFileSize(bytes) {
            if (bytes === 0) return "0 Bytes";
            const k = 1024;
            const sizes = ["Bytes", "KB", "MB", "GB"];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
        },
        submit() {
            this.form.put(route("documentos.update", this.documento.id), {
                onSuccess: () => {
                    // Redirigir a la vista del documento después de actualizar
                },
                onError: (errors) => {
                    console.log("Errores:", errors);
                },
            });
        },
    },
};
</script>