<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Importación Masiva de Productores
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6"
                >
                    <div class="p-6 text-gray-900">
                        <!-- Paso 1: Carga de archivos -->
                        <div v-if="paso === 1" class="space-y-6">
                            <div
                                class="bg-blue-50 border border-blue-200 rounded-lg p-4"
                            >
                                <h3
                                    class="text-lg font-medium text-blue-900 mb-2"
                                >
                                    Paso 1: Cargar Archivos
                                </h3>
                                <p class="text-blue-700 text-sm">
                                    Sube un archivo Excel con los datos de
                                    productores y una plantilla Word para
                                    generar documentos.
                                </p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Archivo Excel -->
                                <div class="space-y-4">
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Archivo Excel (.xlsx, .xls)
                                    </label>
                                    <div
                                        class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center"
                                    >
                                        <input
                                            type="file"
                                            ref="excelInput"
                                            @change="handleExcelChange"
                                            accept=".xlsx,.xls"
                                            class="hidden"
                                        />
                                        <div v-if="!archivoExcel">
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
                                            <p
                                                class="mt-2 text-sm text-gray-600"
                                            >
                                                <button
                                                    @click="
                                                        $refs.excelInput.click()
                                                    "
                                                    class="font-medium text-blue-600 hover:text-blue-500"
                                                >
                                                    Seleccionar archivo Excel
                                                </button>
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                Formato: FET N°, Razón Social,
                                                Calle Dom Real, Localidad, CUIT
                                            </p>
                                        </div>
                                        <div v-else class="text-green-600">
                                            <svg
                                                class="mx-auto h-8 w-8"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                            <p class="mt-1 text-sm font-medium">
                                                {{ archivoExcel.name }}
                                            </p>
                                            <button
                                                @click="removerExcel"
                                                class="mt-1 text-xs text-red-600 hover:text-red-500"
                                            >
                                                Remover archivo
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Plantilla Word -->
                                <div class="space-y-4">
                                    <label
                                        class="block text-sm font-medium text-gray-700"
                                    >
                                        Plantilla Word (.docx, .doc)
                                    </label>
                                    <div
                                        class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center"
                                    >
                                        <input
                                            type="file"
                                            ref="wordInput"
                                            @change="handleWordChange"
                                            accept=".docx,.doc"
                                            class="hidden"
                                        />
                                        <div v-if="!archivoWord">
                                            <svg
                                                class="mx-auto h-12 w-12 text-gray-400"
                                                stroke="currentColor"
                                                fill="none"
                                                viewBox="0 0 48 48"
                                            >
                                                <path
                                                    d="M9 12h6l3 9 3-9h6m-6 0v12m-6-6h12M21 21H9.5a2.5 2.5 0 000 5h13a2.5 2.5 0 000 5H9"
                                                    stroke-width="2"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                />
                                            </svg>
                                            <p
                                                class="mt-2 text-sm text-gray-600"
                                            >
                                                <button
                                                    @click="
                                                        $refs.wordInput.click()
                                                    "
                                                    class="font-medium text-blue-600 hover:text-blue-500"
                                                >
                                                    Seleccionar plantilla Word
                                                </button>
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                Use campos como {fet_numero},
                                                {razon_social}, etc.
                                            </p>
                                        </div>
                                        <div v-else class="text-green-600">
                                            <svg
                                                class="mx-auto h-8 w-8"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                            <p class="mt-1 text-sm font-medium">
                                                {{ archivoWord.name }}
                                            </p>
                                            <button
                                                @click="removerWord"
                                                class="mt-1 text-xs text-red-600 hover:text-red-500"
                                            >
                                                Remover archivo
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button
                                    @click="procesarArchivos"
                                    :disabled="
                                        !archivoExcel ||
                                        !archivoWord ||
                                        procesando
                                    "
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <span v-if="procesando">Procesando...</span>
                                    <span v-else>Procesar Archivos</span>
                                </button>
                            </div>
                        </div>

                        <!-- Paso 2: Vista previa de datos -->
                        <div v-if="paso === 2" class="space-y-6">
                            <div
                                class="bg-green-50 border border-green-200 rounded-lg p-4"
                            >
                                <h3
                                    class="text-lg font-medium text-green-900 mb-2"
                                >
                                    Paso 2: Vista Previa de Datos
                                </h3>
                                <p class="text-green-700 text-sm">
                                    Revisa los datos extraídos del Excel y
                                    configura los campos para la plantilla Word.
                                </p>
                            </div>

                            <div
                                class="bg-white border rounded-lg overflow-hidden"
                            >
                                <div class="px-4 py-3 bg-gray-50 border-b">
                                    <h4
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        Datos encontrados:
                                        {{ datosExcel.length }} registros
                                    </h4>
                                </div>
                                <div class="overflow-x-auto">
                                    <table
                                        class="min-w-full divide-y divide-gray-200"
                                    >
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                                >
                                                    FET N°
                                                </th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                                >
                                                    Razón Social
                                                </th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                                >
                                                    Dirección
                                                </th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                                >
                                                    Localidad
                                                </th>
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                                >
                                                    CUIT
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody
                                            class="bg-white divide-y divide-gray-200"
                                        >
                                            <tr
                                                v-for="(
                                                    dato, index
                                                ) in datosExcel.slice(0, 5)"
                                                :key="index"
                                            >
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                                >
                                                    {{ dato.fet_numero }}
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                                >
                                                    {{ dato.razon_social }}
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                                >
                                                    {{ dato.calle_dom_real }}
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                                >
                                                    {{ dato.localidad }}
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                                >
                                                    {{ dato.cuit }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div
                                    v-if="datosExcel.length > 5"
                                    class="px-4 py-3 bg-gray-50 border-t text-sm text-gray-500"
                                >
                                    Mostrando los primeros 5 registros de
                                    {{ datosExcel.length }} total
                                </div>
                            </div>

                            <div class="flex justify-between">
                                <button
                                    @click="volverPaso1"
                                    class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700"
                                >
                                    Volver
                                </button>
                                <div class="space-x-3">
                                    <button
                                        @click="importarProductores"
                                        :disabled="importando"
                                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 disabled:opacity-50"
                                    >
                                        <span v-if="importando"
                                            >Importando...</span
                                        >
                                        <span v-else>Importar Productores</span>
                                    </button>
                                    <button
                                        @click="generarDocumentos"
                                        :disabled="generando"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50"
                                    >
                                        <span v-if="generando"
                                            >Generando...</span
                                        >
                                        <span v-else>Generar Documentos</span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Paso 3: Resultados -->
                        <div v-if="paso === 3" class="space-y-6">
                            <div
                                class="bg-purple-50 border border-purple-200 rounded-lg p-4"
                            >
                                <h3
                                    class="text-lg font-medium text-purple-900 mb-2"
                                >
                                    Paso 3: Resultados
                                </h3>
                                <p class="text-purple-700 text-sm">
                                    Proceso completado. Revisa los resultados y
                                    descarga los documentos generados.
                                </p>
                            </div>

                            <!-- Resultados de importación -->
                            <div
                                v-if="resultadoImportacion"
                                class="bg-white border rounded-lg p-4"
                            >
                                <h4
                                    class="text-sm font-medium text-gray-900 mb-3"
                                >
                                    Resultado de Importación
                                </h4>
                                <div class="text-sm text-gray-600">
                                    <p class="text-green-600">
                                        ✓
                                        {{
                                            resultadoImportacion.productores_creados
                                        }}
                                        productores creados exitosamente
                                    </p>
                                    <div
                                        v-if="
                                            resultadoImportacion.errores &&
                                            resultadoImportacion.errores
                                                .length > 0
                                        "
                                        class="mt-2"
                                    >
                                        <p class="text-red-600 font-medium">
                                            Errores encontrados:
                                        </p>
                                        <ul
                                            class="list-disc list-inside text-red-600 text-xs mt-1"
                                        >
                                            <li
                                                v-for="error in resultadoImportacion.errores"
                                                :key="error"
                                            >
                                                {{ error }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Documentos generados -->
                            <div
                                v-if="
                                    documentosGenerados &&
                                    documentosGenerados.length > 0
                                "
                                class="bg-white border rounded-lg p-4"
                            >
                                <div
                                    class="flex items-center justify-between mb-3"
                                >
                                    <h4
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        Documentos Generados
                                    </h4>
                                    <button
                                        @click="descargarPdfConsolidado"
                                        :disabled="generandoPdf"
                                        class="px-3 py-1 bg-red-600 text-white text-xs rounded-md hover:bg-red-700 disabled:opacity-50 flex items-center space-x-1"
                                    >
                                        <svg
                                            class="w-4 h-4"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                            ></path>
                                        </svg>
                                        <span v-if="generandoPdf"
                                            >Generando PDF...</span
                                        >
                                        <span v-else
                                            >Descargar PDF Consolidado</span
                                        >
                                    </button>
                                </div>
                                <div class="space-y-2">
                                    <div
                                        v-for="documento in documentosGenerados"
                                        :key="documento.nombre"
                                        class="flex items-center justify-between p-2 bg-gray-50 rounded"
                                    >
                                        <div>
                                            <p
                                                class="text-sm font-medium text-gray-900"
                                            >
                                                {{ documento.nombre }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ documento.productor }}
                                            </p>
                                        </div>
                                        <div
                                            class="flex items-center space-x-2"
                                        >
                                            <button
                                                @click="
                                                    generarPdfIndividual(
                                                        documento.nombre
                                                    )
                                                "
                                                :disabled="
                                                    generandoPdfIndividual[
                                                        documento.nombre
                                                    ]
                                                "
                                                class="px-2 py-1 bg-orange-600 text-white text-xs rounded-md hover:bg-orange-700 disabled:opacity-50 flex items-center space-x-1"
                                            >
                                                <svg
                                                    class="w-3 h-3"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                                    ></path>
                                                </svg>
                                                <span
                                                    v-if="
                                                        generandoPdfIndividual[
                                                            documento.nombre
                                                        ]
                                                    "
                                                    >PDF...</span
                                                >
                                                <span v-else>PDF</span>
                                            </button>
                                            <button
                                                @click="
                                                    enviarEmailPdf(
                                                        documento.nombre
                                                    )
                                                "
                                                :disabled="
                                                    enviandoEmail[
                                                        documento.nombre
                                                    ]
                                                "
                                                class="px-2 py-1 bg-green-600 text-white text-xs rounded-md hover:bg-green-700 disabled:opacity-50 flex items-center space-x-1"
                                            >
                                                <svg
                                                    class="w-3 h-3"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                                    ></path>
                                                </svg>
                                                <span
                                                    v-if="
                                                        enviandoEmail[
                                                            documento.nombre
                                                        ]
                                                    "
                                                    >Enviando...</span
                                                >
                                                <span v-else>Email</span>
                                            </button>
                                            <a
                                                :href="`/importacion/descargar/${documento.nombre}`"
                                                class="text-blue-600 hover:text-blue-500 text-sm"
                                            >
                                                Descargar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-start">
                                <button
                                    @click="reiniciar"
                                    class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700"
                                >
                                    Nueva Importación
                                </button>
                            </div>
                        </div>

                        <!-- Mensajes de error -->
                        <div
                            v-if="error"
                            class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg"
                        >
                            <p class="text-red-800 text-sm">{{ error }}</p>
                        </div>

                        <!-- Mensajes de éxito -->
                        <div
                            v-if="mensaje"
                            class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg"
                        >
                            <p class="text-green-800 text-sm">{{ mensaje }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, reactive } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import axios from "axios";

// Estado de la aplicación
const paso = ref(1);
const procesando = ref(false);
const importando = ref(false);
const generando = ref(false);
const generandoPdf = ref(false);
const generandoPdfIndividual = ref({});
const enviandoEmail = ref({});
const generandoPdfDirecto = ref(false);
const error = ref("");
const mensaje = ref("");

// Archivos
const archivoExcel = ref(null);
const archivoWord = ref(null);

// Datos procesados
const datosExcel = ref([]);
const resultadoImportacion = ref(null);
const documentosGenerados = ref([]);

// Configuración de campos
const camposReemplazo = reactive({
    fet_numero: "fet_numero",
    razon_social: "razon_social",
    calle_dom_real: "calle_dom_real",
    localidad: "localidad",
    cuit: "cuit",
});

// Manejo de archivos
const handleExcelChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        archivoExcel.value = file;
        error.value = "";
    }
};

const handleWordChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        // Verificar que el archivo sea de tipo Word
        if (!file.type.match('application/vnd.openxmlformats-officedocument.wordprocessingml.document|application/msword')) {
            error.value = 'Por favor, seleccione un archivo Word (.docx, .doc)';
            archivoWord.value = null;
            event.target.value = '';
            return;
        }
        
        // Crear una copia del archivo para evitar problemas de referencia
        const fileBlob = file.slice(0, file.size, file.type);
        const newFile = new File([fileBlob], file.name, { type: file.type });
        
        archivoWord.value = newFile;
        error.value = "";
        
        // Log para debug
        console.log('Archivo Word seleccionado:', {
            nombre: newFile.name,
            tipo: newFile.type,
            tamaño: newFile.size
        });
    }
};

const removerExcel = () => {
    archivoExcel.value = null;
};

const removerWord = () => {
    archivoWord.value = null;
};

// Procesamiento de archivos
const procesarArchivos = async () => {
    if (!archivoExcel.value || !archivoWord.value) {
        error.value = "Debe seleccionar ambos archivos";
        return;
    }

    procesando.value = true;
    error.value = "";

    const formData = new FormData();
    formData.append("archivo_excel", archivoExcel.value);
    formData.append("plantilla_word", archivoWord.value);

    try {
        const { data } = await axios.post(
            "/importacion/procesar-excel",
            formData,
            {
                headers: {
                    "Content-Type": "multipart/form-data",
                },
            }
        );

        if (data.success) {
            datosExcel.value = data.datos;
            mensaje.value = data.message;
            paso.value = 2;
        } else {
            error.value = data.message;
        }
    } catch (err) {
        error.value = "Error al procesar los archivos: " + err.message;
    } finally {
        procesando.value = false;
    }
};

// Importación de productores
const importarProductores = async () => {
    importando.value = true;
    error.value = "";

    try {
        const { data } = await axios.post("/importacion/importar-productores", {
            datos: datosExcel.value,
        });

        if (data.success) {
            resultadoImportacion.value = data;
            mensaje.value = data.message;
            paso.value = 3;
        } else {
            error.value = data.message;
        }
    } catch (err) {
        error.value = "Error al importar productores: " + err.message;
    } finally {
        importando.value = false;
    }
};

// Generación de documentos
const generarDocumentos = async () => {
    if (!archivoWord.value) {
        error.value = "La plantilla Word es requerida";
        return;
    }

    generando.value = true;
    error.value = "";

    try {
        const formData = new FormData();
        
        // Agregar el archivo Word
        if (archivoWord.value instanceof File) {
            // Crear una nueva copia del archivo para asegurar que se envíe correctamente
            const fileBlob = archivoWord.value.slice(0, archivoWord.value.size, archivoWord.value.type);
            const newFile = new File([fileBlob], archivoWord.value.name, { type: archivoWord.value.type });
            formData.append("plantilla_word", newFile);
            
            // Log para debug
            console.log('Archivo Word a enviar:', {
                nombre: newFile.name,
                tipo: newFile.type,
                tamaño: newFile.size
            });
        } else {
            throw new Error("El archivo Word no es válido");
        }

        // Agregar los datos como string
        formData.append("datos", JSON.stringify(datosExcel.value));

        // Agregar los campos de reemplazo como string
        formData.append("campos_reemplazo", JSON.stringify(camposReemplazo));

        // Usar fetch con modo no-cors
        const response = await fetch("/importacion/generar-documentos", {
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                "X-Requested-With": "XMLHttpRequest",
                // NO establecer Content-Type, dejar que el navegador lo maneje
            },
            credentials: "same-origin", // Importante para enviar cookies de sesión
        });

        if (!response.ok) {
            throw new Error(`Error en la solicitud: ${response.status} ${response.statusText}`);
        }

        const data = await response.json();


        if (data.success) {
            documentosGenerados.value = data.documentos;
            mensaje.value = data.message;
            paso.value = 3;
        } else {
            error.value = data.message;
        }
    } catch (err) {
        error.value = "Error al generar documentos: " + err.message;
    } finally {
        generando.value = false;
    }
};

// Generar PDF consolidado
const descargarPdfConsolidado = async () => {
    if (!documentosGenerados.value || documentosGenerados.value.length === 0) {
        error.value = "No hay documentos para consolidar";
        return;
    }

    generandoPdf.value = true;
    error.value = "";

    try {
        const response = await axios.post(
            "/importacion/generar-pdf-consolidado",
            {
                documentos: documentosGenerados.value,
            },
            {
                responseType: "blob",
            }
        );

        if (response.status === 200) {
            // Crear un blob con la respuesta PDF
            const blob = response.data;
            const url = window.URL.createObjectURL(blob);

            // Crear un enlace temporal para descargar
            const a = document.createElement("a");
            a.style.display = "none";
            a.href = url;
            a.download = `documentos_consolidados_${new Date()
                .toISOString()
                .slice(0, 19)
                .replace(/:/g, "-")}.pdf`;

            document.body.appendChild(a);
            a.click();

            // Limpiar
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);

            mensaje.value =
                "PDF consolidado generado y descargado exitosamente";
        } else {
            const data = await response.json();
            error.value = data.message || "Error al generar PDF consolidado";
        }
    } catch (err) {
        error.value = "Error al generar PDF consolidado: " + err.message;
    } finally {
        generandoPdf.value = false;
    }
};

// Generar PDF individual
const generarPdfIndividual = async (nombreDocumento) => {
    if (!nombreDocumento) {
        error.value = "Nombre de documento no válido";
        return;
    }

    if (!datosExcel.value || datosExcel.value.length === 0) {
        error.value = "No hay datos del Excel para procesar";
        return;
    }

    if (!archivoExcel.value || !archivoWord.value) {
        error.value = "Archivos Excel y Word son requeridos";
        return;
    }

    generandoPdfIndividual.value[nombreDocumento] = true;
    error.value = "";

    try {
        const response = await axios.post(
            "/importacion/generar-pdf-individual",
            {
                datos: datosExcel.value,
                campos_reemplazo: camposReemplazo,
                nombre_documento: nombreDocumento
            },
            {
                responseType: "blob",
            }
        );

        if (response.status === 200) {
            // Crear un blob con la respuesta PDF
            const blob = response.data;
            const url = window.URL.createObjectURL(blob);

            // Crear un enlace temporal para descargar
            const a = document.createElement("a");
            a.style.display = "none";
            a.href = url;
            a.download = `${nombreDocumento.replace(".docx", "")}.pdf`;

            document.body.appendChild(a);
            a.click();

            // Limpiar
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);

            mensaje.value = "PDF individual generado y descargado exitosamente";
        } else {
            error.value = "Error al generar PDF individual";
        }
    } catch (err) {
        error.value = "Error al generar PDF individual: " + err.message;
    } finally {
        generandoPdfIndividual.value[nombreDocumento] = false;
    }
};

// Enviar email con PDF individual
const enviarEmailPdf = async (nombreDocumento) => {
    if (!datosExcel.value || datosExcel.value.length === 0) {
        error.value = "No hay datos del Excel para procesar";
        return;
    }

    if (!archivoExcel.value || !archivoWord.value) {
        error.value = "Faltan archivos Excel o Word";
        return;
    }

    // Solicitar email del destinatario
    const emailDestinatario = prompt("Ingrese el email del destinatario:");
    if (!emailDestinatario) {
        return;
    }

    // Validar formato de email básico
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(emailDestinatario)) {
        error.value = "Por favor ingrese un email válido";
        return;
    }

    enviandoEmail.value[nombreDocumento] = true;
    error.value = "";

    try {
        // Encontrar el índice del documento en los datos del Excel
        const filaIndex = documentosGenerados.value.findIndex(
            (doc) => doc.nombre === nombreDocumento
        );

        if (filaIndex === -1) {
            error.value = "No se pudo encontrar el documento en los datos";
            return;
        }

        const formData = new FormData();
        formData.append("archivo_excel", archivoExcel.value);
        formData.append("plantilla_word", archivoWord.value);
        formData.append("fila_index", filaIndex.toString());
        formData.append("email_destinatario", emailDestinatario);

        // Enviar campos_reemplazo como campos individuales del array
        Object.keys(camposReemplazo).forEach((key) => {
            formData.append(`campos_reemplazo[${key}]`, camposReemplazo[key]);
        });

        const response = await fetch("/importacion/enviar-email-pdf", {
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
        });

        if (response.ok) {
            const data = await response.json();
            mensaje.value = data.message || "Email enviado exitosamente";
        } else {
            const data = await response.json();
            error.value = data.message || "Error al enviar email";
        }
    } catch (err) {
        error.value = "Error al enviar email: " + err.message;
    } finally {
        enviandoEmail.value[nombreDocumento] = false;
    }
};

// Generar PDF directo desde Excel
const generarPdfDirecto = async () => {
    if (!datosExcel.value || datosExcel.value.length === 0) {
        error.value = "No hay datos del Excel para procesar";
        return;
    }

    if (!archivoWord.value) {
        error.value = "No hay plantilla Word cargada";
        return;
    }

    generandoPdfDirecto.value = true;
    error.value = "";

    try {
        // Necesitamos obtener la ruta de la plantilla Word desde la sesión o almacenamiento
        // Por ahora, asumimos que está almacenada temporalmente
        const formData = new FormData();
        formData.append("datos_excel", JSON.stringify(datosExcel.value));
        formData.append("campos_reemplazo", JSON.stringify(camposReemplazo));
        formData.append("plantilla_word", archivoWord.value);

        const response = await fetch("/importacion/generar-pdf-desde-excel", {
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
        });

        if (response.ok) {
            // Crear un blob con la respuesta PDF
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);

            // Crear un enlace temporal para descargar
            const a = document.createElement("a");
            a.style.display = "none";
            a.href = url;
            a.download = `documentos_consolidados_${new Date()
                .toISOString()
                .slice(0, 19)
                .replace(/:/g, "-")}.pdf`;

            document.body.appendChild(a);
            a.click();

            // Limpiar
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);

            mensaje.value =
                "PDF consolidado generado y descargado exitosamente";
        } else {
            const data = await response.json();
            error.value = data.message || "Error al generar PDF consolidado";
        }
    } catch (err) {
        error.value = "Error al generar PDF consolidado: " + err.message;
    } finally {
        generandoPdfDirecto.value = false;
    }
};

// Navegación
const volverPaso1 = () => {
    paso.value = 1;
    error.value = "";
    mensaje.value = "";
};

const reiniciar = () => {
    // Solo limpiar los archivos si estamos volviendo al paso 1
    if (paso.value === 3) {
        archivoExcel.value = null;
        archivoWord.value = null;
    }
    paso.value = 1;
    datosExcel.value = [];
    resultadoImportacion.value = null;
    documentosGenerados.value = [];
    generandoPdfIndividual.value = {};
    error.value = "";
    mensaje.value = "";
};
</script>
