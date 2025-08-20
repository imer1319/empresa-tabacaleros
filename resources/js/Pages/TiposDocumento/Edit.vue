<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Editar Tipo de Documento
                </h2>
                <div class="flex space-x-2">
                    <Link
                        :href="route('tipos-documento.show', tipoDocumento.id)"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        Ver
                    </Link>
                    <Link
                        :href="route('tipos-documento.index')"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        Volver
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <form @submit.prevent="submit">
                        <div class="space-y-6">
                            <!-- Nombre -->
                            <div>
                                <label for="nombre" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nombre *
                                </label>
                                <input
                                    id="nombre"
                                    v-model="form.nombre"
                                    type="text"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    :class="{ 'border-red-500': errors.nombre }"
                                    required
                                />
                                <div v-if="errors.nombre" class="mt-1 text-sm text-red-600">
                                    {{ errors.nombre }}
                                </div>
                            </div>

                            <!-- Descripción -->
                            <div>
                                <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-2">
                                    Descripción
                                </label>
                                <textarea
                                    id="descripcion"
                                    v-model="form.descripcion"
                                    rows="3"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    :class="{ 'border-red-500': errors.descripcion }"
                                ></textarea>
                                <div v-if="errors.descripcion" class="mt-1 text-sm text-red-600">
                                    {{ errors.descripcion }}
                                </div>
                            </div>

                            <!-- Orden -->
                            <div>
                                <label for="orden" class="block text-sm font-medium text-gray-700 mb-2">
                                    Orden
                                </label>
                                <input
                                    id="orden"
                                    v-model.number="form.orden"
                                    type="number"
                                    min="0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    :class="{ 'border-red-500': errors.orden }"
                                />
                                <div v-if="errors.orden" class="mt-1 text-sm text-red-600">
                                    {{ errors.orden }}
                                </div>
                                <p class="mt-1 text-sm text-gray-500">
                                    Orden de visualización (menor número = mayor prioridad)
                                </p>
                            </div>

                            <!-- Activo -->
                            <div>
                                <div class="flex items-center">
                                    <input
                                        id="activo"
                                        v-model="form.activo"
                                        type="checkbox"
                                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                    />
                                    <label for="activo" class="ml-2 block text-sm text-gray-700">
                                        Activo
                                    </label>
                                </div>
                                <div v-if="errors.activo" class="mt-1 text-sm text-red-600">
                                    {{ errors.activo }}
                                </div>
                                <p class="mt-1 text-sm text-gray-500">
                                    Los tipos de documento inactivos no aparecerán en los formularios
                                </p>
                            </div>

                            <!-- Información adicional -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <h4 class="text-sm font-medium text-gray-700 mb-2">Información del registro</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                                    <div>
                                        <span class="font-medium">Creado:</span>
                                        {{ formatDate(tipoDocumento.created_at) }}
                                    </div>
                                    <div>
                                        <span class="font-medium">Actualizado:</span>
                                        {{ formatDate(tipoDocumento.updated_at) }}
                                    </div>
                                    <div v-if="tipoDocumento.documentos_count !== undefined">
                                        <span class="font-medium">Documentos asociados:</span>
                                        {{ tipoDocumento.documentos_count }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6 space-x-4">
                            <Link
                                :href="route('tipos-documento.index')"
                                class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                Cancelar
                            </Link>
                            <button
                                type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                :disabled="processing"
                            >
                                {{ processing ? 'Guardando...' : 'Actualizar Tipo' }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

// Props
const props = defineProps({
    tipoDocumento: {
        type: Object,
        required: true
    },
    errors: {
        type: Object,
        default: () => ({})
    }
})

// Formulario
const form = useForm({
    nombre: props.tipoDocumento.nombre,
    descripcion: props.tipoDocumento.descripcion || '',
    orden: props.tipoDocumento.orden || 0,
    activo: props.tipoDocumento.activo
})

// Métodos
const submit = () => {
    form.put(route('tipos-documento.update', props.tipoDocumento.id), {
        onSuccess: () => {
            // Redirigir después de actualizar
        },
        onError: (errors) => {
            console.error('Errores de validación:', errors)
        }
    })
}

const formatDate = (dateString) => {
    if (!dateString) return 'N/A'
    return new Date(dateString).toLocaleString('es-ES', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    })
}

// Acceso a propiedades del formulario
const { processing } = form
</script>