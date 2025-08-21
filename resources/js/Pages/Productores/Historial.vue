<template>
    <div>
        <div class="mb-4">
            <h3 class="text-lg font-medium text-gray-900">
                Historial de Actividades
            </h3>
            <p class="text-sm text-gray-500 mt-1">
                Registro cronológico de todas las actividades del productor
            </p>
        </div>

        <div v-if="historial && historial.length > 0" class="flow-root">
            <ul class="-mb-8">
                <li v-for="(actividad, index) in historial" :key="actividad.id" class="relative pb-8">
                    <div v-if="index !== historial.length - 1" class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></div>
                    <div class="relative flex space-x-3">
                        <div>
                            <span :class="['h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white', obtenerColor(actividad.tipo_cambio)]">
                                <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" version="1.1">
                                    <path :d="obtenerIcono(actividad.tipo_cambio)" />
                                </svg>
                            </span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div>
                                <div class="text-sm">
                                    <span class="font-medium text-gray-900">
                                        {{ actividad.usuario?.name || "Sistema" }}
                                    </span>
                                </div>
                                <p class="mt-0.5 text-sm text-gray-500">
                                    {{ formatearFecha(actividad.created_at) }}
                                </p>
                            </div>
                            <div class="mt-2 text-sm text-gray-700">
                                <p>{{ actividad.descripcion }}</p>
                                <div v-if="actividad.detalles" class="mt-2">
                                    <template v-if="actividad.tipo_cambio === 'datos'">
                                        <div v-for="(cambio, campo) in actividad.detalles" :key="campo" class="mt-1 text-sm">
                                            <span class="font-medium">{{ campo }}:</span>
                                            <span class="text-red-500 line-through mr-2">{{ cambio.anterior || "No especificado" }}</span>
                                            <span class="text-green-600 font-medium">→</span>
                                            <span class="text-green-600 ml-2">{{ cambio.nuevo || "No especificado" }}</span>
                                        </div>
                                    </template>
                                    <template v-else-if="actividad.tipo_cambio === 'documento'">
                                        <div class="mt-2 text-sm text-gray-600">
                                            <p><span class="font-medium">Documento:</span> {{ actividad.detalles.nombre }}</p>
                                            <p><span class="font-medium">Estado:</span> {{ actividad.detalles.estado }}</p>
                                            <p v-if="actividad.detalles.observaciones">
                                                <span class="font-medium">Observaciones:</span>
                                                {{ actividad.detalles.observaciones }}
                                            </p>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div v-else class="text-center py-8">
            <p class="text-gray-500">No hay actividades registradas para este productor.</p>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from 'vue';

const props = defineProps({
    historial: {
        type: Array,
        required: true
    }
});

// Función para formatear la fecha
const formatearFecha = (fecha) => {
    return new Date(fecha).toLocaleString("es-AR", {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};

// Función para obtener el ícono según el tipo de cambio
const obtenerIcono = (tipoCambio) => {
    switch (tipoCambio) {
        case "datos":
            return "M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z";
        case "documento":
            return "M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z";
        default:
            return "M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z";
    }
};

// Función para obtener el color según el tipo de cambio
const obtenerColor = (tipoCambio) => {
    switch (tipoCambio) {
        case "datos":
            return "bg-blue-500";
        case "documento":
            return "bg-green-500";
        default:
            return "bg-gray-500";
    }
};
</script>