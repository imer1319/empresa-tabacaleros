<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestión de Documentos
            </h2>
        </template>

        <div class="py-12">
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6"
            >
                <div class="text-gray-900">
                    <!-- Barra de búsqueda y filtros -->
                    <div class="mb-6 flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <input
                                v-model="searchForm.search"
                                type="text"
                                placeholder="Buscar por nombre de documento, tipo o productor..."
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                @input="search"
                            />
                        </div>
                        <div>
                            <select
                                v-model="searchForm.estado"
                                class="px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                @change="search"
                            >
                                <option value="">Todos los estados</option>
                                <option value="pendiente">Pendiente</option>
                                <option value="entregado">Entregado</option>
                                <option value="aprobado">Aprobado</option>
                                <option value="rechazado">Rechazado</option>
                            </select>
                        </div>
                        <div>
                            <select
                                v-model="searchForm.tipo"
                                class="px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                @change="search"
                            >
                                <option value="">Todos los tipos</option>
                                <option
                                    v-for="tipo in tiposDocumento"
                                    :key="tipo.id"
                                    :value="tipo.nombre"
                                >
                                    {{ tipo.nombre }}
                                </option>
                            </select>
                        </div>
                        <div>
                            <select
                                v-model="searchForm.vencidos"
                                class="px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                @change="search"
                            >
                                <option value="">Todos</option>
                                <option value="si">Vencidos</option>
                                <option value="proximos">
                                    Próximos a vencer
                                </option>
                            </select>
                        </div>
                        <div>
                            <Link
                                :href="route('documentos.create')"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                Nuevo Documento
                            </Link>
                        </div>
                    </div>

                    <!-- Lista de documentos -->
                    <div
                        v-if="documentos.data && documentos.data.length > 0"
                        class="space-y-4"
                    >
                        <div
                            v-for="documento in documentos.data"
                            :key="documento.id"
                            class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                        >
                            <!-- Encabezado del documento -->
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center">
                                    <div>
                                        <h3
                                            class="text-lg font-medium text-gray-900"
                                        >
                                            {{ documento.nombre }}
                                        </h3>
                                        <p class="text-sm text-gray-600">
                                            Tipo: {{ documento.tipo }}
                                        </p>
                                    </div>
                                </div>
                                <!-- Estado -->
                                <div class="flex items-center space-x-2">
                                    <span
                                        :class="
                                            getEstadoClass(documento.estado)
                                        "
                                        class="px-3 py-1 rounded-full text-sm font-medium"
                                    >
                                        {{ documento.estado || "Pendiente" }}
                                    </span>
                                </div>
                            </div>

                            <!-- Información del productor -->
                            <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <div class="flex-shrink-0">
                                        <svg
                                            class="w-5 h-5 text-black"
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
                                    <div>
                                        <p
                                            class="text-sm font-medium text-gray-900"
                                        >
                                            {{
                                                documento.productor
                                                    .nombre_completo
                                            }}
                                        </p>
                                        <p class="text-xs text-gray-600">
                                            N°
                                            {{
                                                documento.productor
                                                    .numero_productor
                                            }}
                                            - CUIT:
                                            {{ documento.productor.cuit_cuil }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Información del documento -->
                            <div
                                class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4"
                            >
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">
                                        Fecha de entrega:
                                    </p>
                                    <p
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{
                                            formatDateOnly(
                                                documento.fecha_entrega
                                            ) ||
                                            formatDate(documento.created_at)
                                        }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">
                                        Fecha de vencimiento:
                                    </p>
                                    <p
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{
                                            formatDateOnly(
                                                documento.fecha_vencimiento
                                            ) || "No especificada"
                                        }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">
                                        Fecha de revisión:
                                    </p>
                                    <p
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{
                                            formatDateOnly(
                                                documento.fecha_revision
                                            ) || "No revisado"
                                        }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">
                                        Archivo:
                                    </p>
                                    <p
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{
                                            documento.archivo_nombre ||
                                            "Sin archivo"
                                        }}
                                    </p>
                                </div>
                            </div>

                            <!-- Observaciones -->
                            <div
                                v-if="documento.observaciones"
                                class="mb-4 bg-yellow-50 p-4 rounded-lg"
                            >
                                <p class="text-sm text-gray-600 mb-1">
                                    <strong>Observaciones:</strong>
                                </p>
                                <p class="text-sm text-gray-900">
                                    {{ documento.observaciones }}
                                </p>
                            </div>

                            <!-- Acciones -->
                            <div class="flex justify-end space-x-3">
                                <Link
                                    :href="
                                        route('documentos.show', documento.id)
                                    "
                                    class="inline-flex items-center justify-center p-2 text-blue-600 hover:text-blue-700 hover:bg-blue-100 rounded-lg transition-colors"
                                    title="Ver documento"
                                >
                                    <svg
                                        class="w-5 h-5"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        viewBox="0 0 24 24"
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
                                </Link>
                                <button
                                    v-if="documento.archivo_path"
                                    @click="downloadDocument(documento.id)"
                                    class="inline-flex items-center justify-center p-2 text-green-600 hover:text-green-900 hover:bg-green-100 rounded-lg transition-colors"
                                    title="Descargar documento"
                                >
                                    <svg
                                        class="w-5 h-5"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        ></path>
                                    </svg>
                                </button>
                                <Link
                                    :href="
                                        route('documentos.edit', documento.id)
                                    "
                                    class="inline-flex items-center justify-center p-2 text-yellow-600 hover:text-yellow-700 hover:bg-yellow-100 rounded-lg transition-colors"
                                    title="Editar documento"
                                >
                                    <svg
                                        class="w-5 h-5"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                        ></path>
                                    </svg>
                                </Link>
                                <button
                                    @click="deleteDocument(documento)"
                                    class="inline-flex items-center justify-center p-2 text-red-600 hover:text-red-700 hover:bg-red-100 rounded-lg transition-colors"
                                    title="Eliminar documento"
                                >
                                    <svg
                                        class="w-5 h-5"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                        viewBox="0 0 24 24"
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
                    </div>

                    <!-- Estado vacío -->
                    <div v-else class="text-center py-12">
                        <div
                            class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4"
                        >
                            <svg
                                class="w-12 h-12 text-gray-400"
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
                        <h3 class="text-lg font-medium text-gray-900 mb-2">
                            No hay documentos
                        </h3>
                        <p class="text-gray-500 mb-4">
                            No se encontraron documentos que coincidan con los
                            filtros aplicados.
                        </p>
                        <Link
                            :href="route('documentos.create')"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                        >
                            Crear Primer Documento
                        </Link>
                    </div>

                    <!-- Paginación -->
                    <div
                        class="mt-6"
                        v-if="documentos.links && documentos.links.length > 3"
                    >
                        <nav class="flex items-center justify-between">
                            <div class="flex-1 flex justify-between sm:hidden">
                                <Link
                                    v-if="documentos.prev_page_url"
                                    :href="documentos.prev_page_url"
                                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                                >
                                    Anterior
                                </Link>
                                <Link
                                    v-if="documentos.next_page_url"
                                    :href="documentos.next_page_url"
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
                                            documentos.from
                                        }}</span>
                                        a
                                        <span class="font-medium">{{
                                            documentos.to
                                        }}</span>
                                        de
                                        <span class="font-medium">{{
                                            documentos.total
                                        }}</span>
                                        resultados
                                    </p>
                                </div>
                                <div>
                                    <nav
                                        class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                                    >
                                        <Link
                                            v-for="link in documentos.links"
                                            :key="link.label"
                                            :href="link.url"
                                            v-html="link.label"
                                            :class="[
                                                link.active
                                                    ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                                                    : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                                                'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                            ]"
                                        />
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

<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link, router } from "@inertiajs/vue3";
import { debounce } from "lodash";

export default {
    components: {
        AuthenticatedLayout,
        Link,
    },
    props: {
        documentos: Object,
        tiposDocumento: Array,
        filters: Object,
    },
    data() {
        return {
            searchForm: {
                search: this.filters.search || "",
                estado: this.filters.estado || "",
                tipo: this.filters.tipo || "",
                vencidos: this.filters.vencidos || "",
            },
        };
    },
    methods: {
        search: debounce(function () {
            router.get(route("documentos.index"), this.searchForm, {
                preserveState: true,
                replace: true,
            });
        }, 300),
        getEstadoClass(estado) {
            switch (estado) {
                case "aprobado":
                    return "bg-green-100 text-green-800";
                case "entregado":
                    return "bg-blue-100 text-blue-800";
                case "pendiente":
                    return "bg-yellow-100 text-yellow-800";
                case "rechazado":
                    return "bg-red-100 text-red-800";
                default:
                    return "bg-gray-100 text-gray-800";
            }
        },
        formatDate(dateString) {
            if (!dateString) return "";
            const date = new Date(dateString);
            return date.toLocaleString("es-ES", {
                year: "numeric",
                month: "2-digit",
                day: "2-digit",
                hour: "2-digit",
                minute: "2-digit",
            });
        },
        formatDateOnly(dateString) {
            if (!dateString) return "";
            const date = new Date(dateString);
            return date.toLocaleDateString("es-ES", {
                year: "numeric",
                month: "2-digit",
                day: "2-digit",
            });
        },
        downloadDocument(documentoId) {
            window.open(route("documentos.download", documentoId), "_blank");
        },
        deleteDocument(documento) {
            if (
                confirm(
                    `¿Está seguro de eliminar el documento "${documento.nombre}"?`
                )
            ) {
                router.delete(route("documentos.destroy", documento.id));
            }
        },
    },
};
</script>
