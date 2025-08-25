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

        <div class="bg-white shadow-sm rounded-lg divide-y divide-gray-200">
            <div
                v-for="cambio in historial"
                :key="cambio.id"
                class="p-4 hover:bg-gray-50 transition-colors duration-200 grid grid-cols-[50px_1fr] gap-4"
            >
                <!-- Columna izquierda (ícono) -->
                <div class="flex items-start justify-center">
                    <!-- Íconos -->
                    <svg
                        v-if="cambio.modelo_type.includes('Productor')"
                        class="w-8 h-8 text-blue-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                        />
                    </svg>

                    <svg
                        v-else-if="cambio.modelo_type.includes('Cita')"
                        class="w-8 h-8 text-green-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                        />
                    </svg>

                    <svg
                        v-else-if="cambio.modelo_type.includes('Documento')"
                        class="w-8 h-8 text-yellow-600"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                        />
                    </svg>
                </div>

                <!-- Columna derecha (contenido) -->
                <div>
                    <!-- Tipo de acción -->
                    <h4 class="text-sm font-semibold text-gray-900 mb-1">
                        {{ obtenerTituloAccion(cambio) }}
                    </h4>

                    <!-- Mensaje -->
                    <p
                        class="text-sm mb-2"
                        :class="{
                            'text-green-600':
                                cambio.tipo_operacion === 'creacion',
                            'text-red-600':
                                cambio.tipo_operacion === 'eliminacion',
                            'text-gray-700':
                                cambio.tipo_operacion === 'actualizacion',
                        }"
                    >
                        {{ obtenerMensaje(cambio) }}
                    </p>

                    <!-- Cambios en actualizaciones -->
                    <div
                        v-if="
                            cambio.tipo_operacion === 'actualizacion' &&
                            cambio.cambios
                        "
                        class="mb-2 text-sm text-gray-600"
                    >
                        <template
                            v-for="(valor, campo) in cambio.cambios"
                            :key="campo"
                        >
                            <span class="font-medium"
                                >{{ formatoTexto(campo) }}:</span
                            >
                            <span class="text-red-500 line-through mr-1">{{
                                obtenerValorFormateado(valor.anterior, campo)
                            }}</span>
                            <span class="text-green-500 mr-3">{{
                                obtenerValorFormateado(valor.nuevo, campo)
                            }}</span>
                        </template>
                    </div>

                    <!-- Metadatos -->
                    <div class="text-xs text-gray-500">
                        Por {{ cambio.usuario?.name || "Sistema" }} •
                        Actualizado:
                        {{ formatearFecha(cambio.updated_at) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps } from "vue";

const props = defineProps({
    historial: {
        type: Array,
        required: true,
    },
});

const formatearFecha = (fecha, soloFecha = false) => {
    if (!fecha) return "No definido";

    // Si el formato es tipo "YYYY-MM-DD", lo trato como fecha plana
    if (/^\d{4}-\d{2}-\d{2}$/.test(fecha)) {
        const [y, m, d] = fecha.split("-");
        const opciones = { year: "numeric", month: "short", day: "numeric" };
        return new Date(y, m - 1, d).toLocaleDateString("es-ES", opciones);
    }

    // Para los demás casos (datetime con hora)
    const opciones = soloFecha
        ? { year: "numeric", month: "short", day: "numeric" }
        : {
              year: "numeric",
              month: "short",
              day: "numeric",
              hour: "2-digit",
              minute: "2-digit",
          };

    return new Date(fecha).toLocaleDateString("es-ES", opciones);
};

const formatoTexto = (texto) => {
    if (!texto) return "";
    return texto
        .replace(/_/g, " ")
        .replace(/([A-Z])/g, " $1")
        .replace(/^./, (str) => str.toUpperCase());
};

const obtenerValorFormateado = (valor, campo) => {
    if (valor === null || valor === undefined) return "No definido";
    if (
        campo.includes("fecha") ||
        campo === "created_at" ||
        campo === "updated_at"
    ) {
        return formatearFecha(valor);
    }
    return valor;
};

const obtenerTituloAccion = (cambio) => {
    if (cambio.tipo_operacion === "creacion") return "Creación";
    if (cambio.tipo_operacion === "eliminacion") return "Eliminación";
    return "Actualización";
};

const obtenerMensaje = (cambio) => {
    if (cambio.tipo_operacion === "creacion") {
        if (cambio.modelo_type.includes("Cita")) {
            return `Cita creada: ${cambio.despues.descripcion} - Estado: ${
                cambio.despues.estado
            } - Próxima visita: ${formatearFecha(
                cambio.despues.fecha_proxima_cita,
                true
            )}`;
        }
        if (cambio.modelo_type.includes("Productor")) {
            return `Se creó el productor Nº ${cambio.despues.numero} - ${cambio.despues.nombre} ${cambio.despues.apellido} - CUIT: ${cambio.despues.cuit}`;
        }
        if (cambio.modelo_type.includes("Documento")) {
            return `Documento: ${
                cambio.despues.nombre
            } creado - Entrega: ${formatearFecha(
                cambio.despues.fecha_entrega,
                true
            )} - Vencimiento: ${formatearFecha(
                cambio.despues.fecha_vencimiento,
                true
            )} - Estado: ${cambio.despues.estado}`;
        }
        return "Se creó correctamente.";
    }

    if (cambio.tipo_operacion === "eliminacion") {
        if (cambio.modelo_type.includes("Cita")) {
            return `Cita eliminada:  Descripcion: ${
                cambio.antes.descripcion
            } - Estado: ${cambio.antes.estado} - Fecha visita: ${formatearFecha(
                cambio.antes.fecha_visita,
                true
            )} - Próxima visita: ${formatearFecha(
                cambio.antes.fecha_proxima_cita,
                true
            )}`;
        }
        if (cambio.modelo_type.includes("Productor")) {
            return `Productor eliminado: Nº ${cambio.antes.numero} - ${cambio.antes.nombre} ${cambio.antes.apellido} - CUIT: ${cambio.antes.cuit}`;
        }
        if (cambio.modelo_type.includes("Documento")) {
            return `Documento eliminado: ${
                cambio.antes.nombre
            } - Entrega: ${formatearFecha(
                cambio.antes.fecha_entrega,
                true
            )} - Vencimiento: ${formatearFecha(
                cambio.antes.fecha_vencimiento,
                true
            )} - Estado: ${cambio.antes.estado}`;
        }
        return `${formatoTexto(cambio.modelo_type)} eliminado correctamente.`;
    }

    return "Se realizaron cambios en este registro.";
};
</script>
