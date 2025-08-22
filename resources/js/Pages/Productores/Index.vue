<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestión de Productores
            </h2>
        </template>

        <div class="py-12">
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6"
            >
                <div class="text-gray-900">
                    <!-- Barra de búsqueda y filtros -->
                    <form :action="route('productores.index')" method="get" class="mb-6 flex flex-col sm:flex-row gap-4">
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
                        <div>
                            <Link
                                :href="route('productores.create')"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                Nuevo Productor
                            </Link>
                        </div>
                    </form>

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

<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link } from "@inertiajs/vue3";

export default {
    components: {
        AuthenticatedLayout,
        Link,
    },
    props: {
        productores: Object,
        filters: Object,
    },
    data() {
        return {
            search: this.filters.search || "",
            estado: this.filters.estado || ""
        };
    },
    methods: {
        getEstadoClass(estado) {
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
        },
        confirmarEliminacion(event, nombreProductor) {
            if (confirm(`¿Está seguro de eliminar al productor ${nombreProductor}?`)) {
                event.target.submit();
            }
        },
    },
};
</script>
