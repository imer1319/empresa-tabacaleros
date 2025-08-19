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
                    <div class="mb-6 flex flex-col sm:flex-row gap-4">
                        <div class="flex-1">
                            <input
                                v-model="searchForm.search"
                                type="text"
                                placeholder="Buscar por nombre, número de productor o CUIT..."
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
                                <option value="En proceso">En proceso</option>
                                <option value="Aprobado">Aprobado</option>
                                <option value="Faltante">Faltante</option>
                            </select>
                        </div>
                        <div>
                            <Link
                                :href="route('productores.create')"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                Nuevo Productor
                            </Link>
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
                                            class="text-indigo-600 hover:text-indigo-900 mr-3"
                                        >
                                            Ver
                                        </Link>
                                        <Link
                                            :href="
                                                route(
                                                    'productores.edit',
                                                    productor.id
                                                )
                                            "
                                            class="text-yellow-600 hover:text-yellow-900 mr-3"
                                        >
                                            Editar
                                        </Link>
                                        <button
                                            @click="deleteProductor(productor)"
                                            class="text-red-600 hover:text-red-900"
                                        >
                                            Eliminar
                                        </button>
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
                                        <Link
                                            v-for="link in productores.links"
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
        productores: Object,
        filters: Object,
    },
    data() {
        return {
            searchForm: {
                search: this.filters.search || "",
                estado: this.filters.estado || "",
            },
        };
    },
    methods: {
        search: debounce(function () {
            router.get(route("productores.index"), this.searchForm, {
                preserveState: true,
                replace: true,
            });
        }, 300),
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
        deleteProductor(productor) {
            if (
                confirm(
                    `¿Está seguro de eliminar al productor ${productor.nombre_completo}?`
                )
            ) {
                router.delete(route("productores.destroy", productor.id));
            }
        },
    },
};
</script>
