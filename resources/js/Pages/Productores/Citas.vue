<template>
    <div>
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Citas y Visitas</h3>
            <button
                type="button"
                @click="showCitaModal = true"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
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
                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                    ></path>
                </svg>
                Nueva Cita
            </button>
        </div>

        <!-- Filtro de estado -->
        <div class="mb-4">
            <select
                v-model="filtroEstado"
                class="mt-1 block w-48 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
            >
                <option value="">Todos los estados</option>
                <option value="pendiente">Pendiente</option>
                <option value="asistio">Asistió</option>
                <option value="no_asistio">No Asistió</option>
            </select>
        </div>

        <!-- Grid de cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div
                v-for="cita in citasFiltradas"
                :key="cita.id"
                class="bg-white rounded-lg shadow-md overflow-hidden"
            >
                <div class="p-4">
                    <!-- Estado de la cita -->
                    <div class="flex justify-between items-start mb-3">
                        <span
                            :class="{
                                'px-2 py-1 text-xs font-semibold rounded-full': true,
                                'bg-yellow-100 text-yellow-800':
                                    cita.estado === 'pendiente',
                                'bg-green-100 text-green-800':
                                    cita.estado === 'asistio',
                                'bg-red-100 text-red-800':
                                    cita.estado === 'no_asistio',
                            }"
                        >
                            {{
                                cita.estado === "pendiente"
                                    ? "Pendiente"
                                    : cita.estado === "asistio"
                                    ? "Asistió"
                                    : "No Asistió"
                            }}
                        </span>
                        <div class="flex space-x-2">
                            <button
                                @click="editCita(cita)"
                                class="text-indigo-600 hover:text-indigo-900"
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
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                    />
                                </svg>
                            </button>
                            <button
                                @click="deleteCita(cita)"
                                class="text-red-600 hover:text-red-900"
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
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Fechas -->
                    <div class="mb-3">
                        <p class="text-sm text-gray-600">
                            <span class="font-semibold">Fecha de Visita:</span
                            ><br />
                            {{ formatDate(cita.fecha_visita) }}
                        </p>
                        <p class="text-sm text-gray-600 mt-2">
                            <span class="font-semibold"
                                >Próxima Cita:</span
                            ><br />
                            {{ formatDate(cita.fecha_proxima_cita) }}
                        </p>
                    </div>

                    <!-- Descripción -->
                    <div class="mt-4">
                        <p class="text-sm text-gray-600">
                            <span class="font-semibold">Descripción:</span><br />
                            {{ cita.descripcion }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="citasFiltradas.length === 0" class="text-center py-8">
            <p class="text-gray-500">
                No hay citas registradas
            </p>
        </div>

        <!-- Modal de Cita -->
        <CitaModal
            :show="showCitaModal"
            :cita="selectedCita"
            :productor-id="productor.id"
            @close="closeCitaModal"
            @saved="refreshPage"
        />
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import CitaModal from '@/Components/CitaModal.vue';

const props = defineProps({
    productor: {
        type: Object,
        required: true
    }
});

const showCitaModal = ref(false);
const selectedCita = ref(null);
const filtroEstado = ref('');

const citasFiltradas = computed(() => {
    if (!filtroEstado.value) {
        return props.productor.citas;
    }
    return props.productor.citas.filter(cita => cita.estado === filtroEstado.value);
});

const editCita = (cita) => {
    selectedCita.value = cita;
    showCitaModal.value = true;
};

const deleteCita = (cita) => {
    if (confirm("¿Estás seguro de que deseas eliminar esta cita?")) {
        router.delete(route("citas.destroy", cita.id), {
            onSuccess: () => {
                router.reload();
            },
        });
    }
};

const closeCitaModal = () => {
    showCitaModal.value = false;
    selectedCita.value = null;
};

const refreshPage = () => {
    router.reload();
};

const formatDate = (dateString) => {
    if (!dateString) return "No especificada";
    const date = new Date(dateString);
    return date.toLocaleDateString("es-AR", {
        year: "numeric",
        month: "long",
        day: "numeric",
        timeZone: "America/Argentina/Buenos_Aires",
    });
};
</script>