<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestión de Productores
            </h2>
            <!-- Modal de Importación -->
    <div v-if="modalImportacion" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Importar Productores desde Excel</h3>
                <button @click="cerrarModalImportacion" class="text-gray-400 hover:text-gray-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="mb-4">
                <p class="text-sm text-gray-600 mb-2">Seleccione un archivo Excel con el siguiente formato:</p>
                <div class="bg-gray-50 p-3 rounded text-sm">
                    <p class="font-medium">Columnas requeridas:</p>
                    <ul class="list-disc list-inside text-gray-600 mt-1">
                        <li>FET N°</li>
                        <li>Razon Social</li>
                        <li>Calle Dom Real</li>
                        <li>Localidad</li>
                        <li>CUIT</li>
                    </ul>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Archivo Excel</label>
                <input
                    type="file"
                    @change="manejarArchivo"
                    accept=".xlsx,.xls"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                />
            </div>

            <div v-if="erroresImportacion.length > 0" class="mb-4">
                <div class="bg-red-50 border border-red-200 rounded-md p-3">
                    <div class="text-sm text-red-600">
                        <ul class="list-disc list-inside">
                            <li v-for="error in erroresImportacion" :key="error">{{ error }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="flex justify-end space-x-3">
                <button
                    @click="cerrarModalImportacion"
                    class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Cancelar
                </button>
                <button
                    @click="importarExcel"
                    :disabled="!form.archivo || form.processing"
                    class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-medium text-sm text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    {{ form.processing ? 'Importando...' : 'Importar' }}
                </button>
            </div>
        </div>
    </div>
</template>

        <div class="py-12">
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6"
            >
                <div class="text-gray-900">
                    <!-- Barra de búsqueda y filtros -->
                    <div class="mb-6">
                        <div class="flex justify-between items-start">
                            <form :action="route('productores.index')" method="get" class="flex-1 flex flex-col sm:flex-row gap-4">
                                <div class="flex-1">
                                    <input
                                        name="search"
                                        type="text"
                                        :value="filters.search"
                                        placeholder="Buscar por nombre, número de productor o CUIT..."
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                    />
                                </div>
                                <div>
                                    <select
                                        name="estado"
                                        class="px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                        :value="filters.estado"
                                    >
                                        <option value="">Todos los estados</option>
                                        <option value="En proceso">En proceso</option>
                                        <option value="Aprobado">Aprobado</option>
                                        <option value="Faltante">Faltante</option>
                                    </select>
                                </div>
                                <div>
                                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        Buscar
                                    </button>
                                </div>
                            </form>
                            <div class="flex space-x-2 ml-4">
                                <button
                                    type="button"
                                    @click.prevent="abrirModalImportacion"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                                    </svg>
                                    Importar Excel
                                </button>
                                <Link
                                    :href="route('productores.create')"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                >
                                    Nuevo Productor
                                </Link>
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de productores -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Número
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Nombre Completo
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        CUIT/CUIL
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Localidad
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Estado
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                                    >
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr
                                    v-for="productor in productores.data"
                                    :key="productor.id"
                                >
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                    >
                                        {{ productor.numero_productor }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                    >
                                        {{ productor.nombre_completo }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                    >
                                        {{ productor.cuit_cuil }}
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"
                                    >
                                        {{ productor.localidad }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            :class="
                                                getEstadoClass(
                                                    productor.estado_documentacion
                                                )
                                            "
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                        >
                                            {{ productor.estado_documentacion }}
                                        </span>
                                    </td>
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium"
                                    >
                                        <Link
                                            :href="
                                                route(
                                                    'productores.show',
                                                    productor.id
                                                )
                                            "
                                            class="text-indigo-600 hover:text-indigo-900 mr-3 inline-flex items-center"
                                            title="Ver"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </Link>
                                        <Link
                                            :href="
                                                route(
                                                    'productores.edit',
                                                    productor.id
                                                )
                                            "
                                            class="text-yellow-600 hover:text-yellow-900 mr-3 inline-flex items-center"
                                            title="Editar"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                        </Link>
                                        <form
                                            :action="route('productores.destroy', productor.id)"
                                            method="POST"
                                            class="inline"
                                            @submit.prevent="confirmarEliminacion($event, productor.nombre_completo)"
                                        >
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" :value="$page.props.csrf_token">
                                            <button
                                                type="submit"
                                                class="text-red-600 hover:text-red-900 inline-flex items-center"
                                                title="Eliminar"
                                            >
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="mt-6" v-if="productores.links.length > 3">
                        <nav class="flex items-center justify-between">
                            <div class="flex-1 flex justify-between sm:hidden">
                                <Link
                                    v-if="productores.prev_page_url"
                                    :href="productores.prev_page_url"
                                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                                >
                                    Anterior
                                </Link>
                                <Link
                                    v-if="productores.next_page_url"
                                    :href="productores.next_page_url"
                                    class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                                >
                                    Siguiente
                                </Link>
                            </div>
                            <div
                                class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between"
                            >
                                <div>
                                    <p class="text-sm text-gray-700">
                                        Mostrando
                                        <span class="font-medium">{{
                                            productores.from
                                        }}</span>
                                        a
                                        <span class="font-medium">{{
                                            productores.to
                                        }}</span>
                                        de
                                        <span class="font-medium">{{
                                            productores.total
                                        }}</span>
                                        resultados
                                    </p>
                                </div>
                                <div>
                                    <nav
                                        class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                    >
                                        <template v-for="link in productores.links" :key="link.label">
                                            <Link
                                                v-if="link.url"
                                                :href="link.url"
                                                :class="[
                                                    link.active
                                                        ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                                                        : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                                                    'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                                ]"
                                            >
                                                <span v-if="link.label === '&laquo; Previous'">Anterior</span>
                                                <span v-else-if="link.label === 'Next &raquo;'">Siguiente</span>
                                                <span v-else>{{ link.label }}</span>
                                            </Link>
                                            <span
                                                v-else
                                                :class="[
                                                    link.active
                                                        ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                                                        : 'bg-white border-gray-300 text-gray-500',
                                                    'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                                ]"
                                            >
                                                <span v-if="link.label === '&laquo; Previous'">Anterior</span>
                                                <span v-else-if="link.label === 'Next &raquo;'">Siguiente</span>
                                                <span v-else>{{ link.label }}</span>
                                            </span>
                                        </template>
                                    </nav>
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link } from "@inertiajs/vue3";
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const modalImportacion = ref(false);
const archivoExcel = ref(null);
const erroresImportacion = ref([]);

const form = useForm({
    archivo: null
});

const abrirModalImportacion = () => {
    modalImportacion.value = true;
};

const cerrarModalImportacion = () => {
    modalImportacion.value = false;
    form.reset();
    erroresImportacion.value = [];
};

const manejarArchivo = (e) => {
    const archivo = e.target.files[0];
    form.archivo = archivo;
};

const importarExcel = () => {
    form.post(route('productores.importar'), {
        preserveScroll: true,
        onSuccess: () => {
            cerrarModalImportacion();
            window.location.reload();
        },
        onError: (errors) => {
            if (Array.isArray(errors.archivo)) {
                erroresImportacion.value = errors.archivo;
            } else {
                erroresImportacion.value = [errors.archivo];
            }
        }
    });
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

const confirmarEliminacion = (event, nombreProductor) => {
    if (confirm(`¿Está seguro de eliminar al productor ${nombreProductor}?`)) {
        event.target.submit();
    }
};

defineProps({
    productores: Object,
    filters: Object
});
</script>
