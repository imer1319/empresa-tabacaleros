<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Tipos de Documentos
                </h2>
                <Link
                    :href="route('tipos-documento.create')"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    Nuevo Tipo
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="text-gray-900">
                        <!-- Lista de tipos de documentos -->
                        <div v-if="tiposDocumento && tiposDocumento.data && tiposDocumento.data.length > 0" class="space-y-4">
                            <div
                                v-for="tipo in tiposDocumento.data"
                                :key="tipo.id"
                                class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                            >
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <h3 class="text-lg font-medium text-gray-900">
                                            {{ tipo.nombre }}
                                        </h3>
                                        <p v-if="tipo.descripcion" class="text-sm text-gray-600 mt-1">
                                            {{ tipo.descripcion }}
                                        </p>
                                        <div class="flex items-center mt-2 space-x-4">
                                            <span
                                                :class="[
                                                    tipo.activo
                                                        ? 'bg-green-100 text-green-800'
                                                        : 'bg-red-100 text-red-800',
                                                    'px-2 py-1 rounded-full text-xs font-medium'
                                                ]"
                                            >
                                                {{ tipo.activo ? 'Activo' : 'Inactivo' }}
                                            </span>
                                            <span class="text-xs text-gray-500">
                                                Orden: {{ tipo.orden }}
                                            </span>
                                            <span v-if="tipo.documentos_count !== undefined" class="text-xs text-gray-500">
                                                {{ tipo.documentos_count }} documento(s)
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <Link
                                            :href="route('tipos-documento.show', tipo.id)"
                                            class="inline-flex items-center justify-center p-2 text-blue-600 hover:text-blue-700 hover:bg-blue-100 rounded-lg transition-colors"
                                            title="Ver detalles"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </Link>
                                        <Link
                                            :href="route('tipos-documento.edit', tipo.id)"
                                            class="inline-flex items-center justify-center p-2 text-yellow-600 hover:text-yellow-700 hover:bg-yellow-100 rounded-lg transition-colors"
                                            title="Editar tipo"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </Link>
                                        <button
                                            @click="confirmarEliminacion(tipo)"
                                            class="inline-flex items-center justify-center p-2 text-red-600 hover:text-red-700 hover:bg-red-100 rounded-lg transition-colors"
                                            title="Eliminar tipo"
                                            :disabled="tipo.documentos_count > 0"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Paginación -->
                        <div v-if="tiposDocumento && tiposDocumento.data && tiposDocumento.data.length > 0 && tiposDocumento.links" class="mt-6">
                            <nav class="flex items-center justify-between border-t border-gray-200 px-4 sm:px-0">
                                <div class="-mt-px flex w-0 flex-1">
                                    <Link
                                        v-if="tiposDocumento.prev_page_url"
                                        :href="tiposDocumento.prev_page_url"
                                        class="inline-flex items-center border-t-2 border-transparent pr-1 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700"
                                    >
                                        <svg class="mr-3 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M18 10a.75.75 0 01-.75.75H4.66l2.1 1.95a.75.75 0 11-1.02 1.1l-3.5-3.25a.75.75 0 010-1.1l3.5-3.25a.75.75 0 111.02 1.1L4.66 9.25h12.59A.75.75 0 0118 10z" clip-rule="evenodd" />
                                        </svg>
                                        Anterior
                                    </Link>
                                </div>
                                <div class="hidden md:-mt-px md:flex">
                                    <span class="inline-flex items-center border-t-2 border-transparent px-4 pt-4 text-sm font-medium text-gray-500">
                                        Página {{ tiposDocumento.current_page }} de {{ tiposDocumento.last_page }}
                                    </span>
                                </div>
                                <div class="-mt-px flex w-0 flex-1 justify-end">
                                    <Link
                                        v-if="tiposDocumento.next_page_url"
                                        :href="tiposDocumento.next_page_url"
                                        class="inline-flex items-center border-t-2 border-transparent pl-1 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700"
                                    >
                                        Siguiente
                                        <svg class="ml-3 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M2 10a.75.75 0 01.75-.75h12.59l-2.1-1.95a.75.75 0 111.02-1.1l3.5 3.25a.75.75 0 010 1.1l-3.5 3.25a.75.75 0 11-1.02-1.1l2.1-1.95H2.75A.75.75 0 012 10z" clip-rule="evenodd" />
                                        </svg>
                                    </Link>
                                </div>
                            </nav>
                        </div>

                        <!-- Estado vacío -->
                        <div v-else class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay tipos de documentos</h3>
                            <p class="mt-1 text-sm text-gray-500">Comienza creando un nuevo tipo de documento.</p>
                            <div class="mt-6">
                                <Link
                                    :href="route('tipos-documento.create')"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                >
                                    Crear Primer Tipo
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de confirmación de eliminación -->
        <div v-if="mostrarModalEliminacion" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3 text-center">
                    <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mt-2">Confirmar eliminación</h3>
                    <div class="mt-2 px-7 py-3">
                        <p class="text-sm text-gray-500">
                            ¿Estás seguro de que deseas eliminar el tipo de documento "{{ tipoAEliminar?.nombre }}"?
                            Esta acción no se puede deshacer.
                        </p>
                    </div>
                    <div class="flex justify-center space-x-4 mt-4">
                        <button
                            @click="cancelarEliminacion"
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors"
                        >
                            Cancelar
                        </button>
                        <button
                            @click="eliminarTipo"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors"
                            :disabled="eliminando"
                        >
                            {{ eliminando ? 'Eliminando...' : 'Eliminar' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

// Props
defineProps({
    tiposDocumento: {
        type: Array,
        default: () => []
    }
})

// Estado del componente
const mostrarModalEliminacion = ref(false)
const tipoAEliminar = ref(null)
const eliminando = ref(false)

// Métodos
const confirmarEliminacion = (tipo) => {
    if (tipo.documentos_count > 0) {
        alert('No se puede eliminar este tipo de documento porque tiene documentos asociados.')
        return
    }
    tipoAEliminar.value = tipo
    mostrarModalEliminacion.value = true
}

const cancelarEliminacion = () => {
    mostrarModalEliminacion.value = false
    tipoAEliminar.value = null
}

const eliminarTipo = () => {
    if (!tipoAEliminar.value) return
    
    eliminando.value = true
    
    router.delete(route('tipos-documento.destroy', tipoAEliminar.value.id), {
        onSuccess: () => {
            mostrarModalEliminacion.value = false
            tipoAEliminar.value = null
        },
        onError: (errors) => {
            console.error('Error al eliminar:', errors)
        },
        onFinish: () => {
            eliminando.value = false
        }
    })
}
</script>