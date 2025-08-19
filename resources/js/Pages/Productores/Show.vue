<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detalles del Productor: {{ productor.nombre_completo }}
            </h2>
        </template>

        <div class="py-12">
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6"
            >
                <div class="text-gray-900">
                    <div class="flex justify-between items-start mb-6">
                        <h3 class="text-lg font-medium text-gray-900">
                            Información Personal
                        </h3>
                        <div class="flex space-x-2">
                            <Link
                                :href="route('productores.edit', productor.id)"
                                class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                Editar
                            </Link>
                            <Link
                                :href="route('productores.index')"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                            >
                                Volver
                            </Link>
                        </div>
                    </div>

                    <div
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6"
                    >
                        <div>
                            <dt class="text-sm font-medium text-gray-500">
                                Número de Productor
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ productor.numero_productor }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">
                                Nombre Completo
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ productor.nombre_completo }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">
                                CUIT/CUIL
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ productor.cuit_cuil }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">
                                Teléfono
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ productor.telefono || "No especificado" }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">
                                Email
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ productor.email || "No especificado" }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">
                                Estado de Documentación
                            </dt>
                            <dd class="mt-1">
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
                            </dd>
                        </div>
                        <div class="md:col-span-2 lg:col-span-3">
                            <dt class="text-sm font-medium text-gray-500">
                                Dirección
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ productor.direccion || "No especificada" }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">
                                Localidad
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ productor.localidad || "No especificada" }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">
                                Departamento
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{
                                    productor.departamento || "No especificado"
                                }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">
                                Fecha de Registro
                            </dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ formatDate(productor.created_at) }}
                            </dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sistema de Pestañas -->
            <div
                class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6"
            >
                <!-- Navegación de Pestañas -->
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                        <button
                            @click="activeTab = 'documentos'"
                            :class="[
                                activeTab === 'documentos'
                                    ? 'border-green-500 text-green-600'
                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                                'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center',
                            ]"
                        >
                            <svg
                                class="w-5 h-5 mr-2"
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
                            Documentos
                        </button>
                        <button
                            @click="activeTab = 'comunicaciones'"
                            :class="[
                                activeTab === 'comunicaciones'
                                    ? 'border-green-500 text-green-600'
                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                                'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center',
                            ]"
                        >
                            <svg
                                class="w-5 h-5 mr-2"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                                ></path>
                            </svg>
                            Comunicaciones
                        </button>
                        <button
                            @click="activeTab = 'historial'"
                            :class="[
                                activeTab === 'historial'
                                    ? 'border-green-500 text-green-600'
                                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                                'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center',
                            ]"
                        >
                            <svg
                                class="w-5 h-5 mr-2"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                ></path>
                            </svg>
                            Historial
                        </button>
                    </nav>
                </div>

                <!-- Contenido de las Pestañas -->
                <div class="p-6">
                    <!-- Pestaña Documentos -->
                    <div v-if="activeTab === 'documentos'">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">
                                Documentos del Productor
                            </h3>
                            <button
                                @click="showModal = true"
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150"
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
                                Subir Documento
                            </button>
                        </div>

                        <div
                            v-if="
                                productor.documentos &&
                                productor.documentos.length > 0
                            "
                            class="space-y-4"
                        >
                            <div
                                v-for="documento in productor.documentos"
                                :key="documento.id"
                                class="bg-white rounded-xl shadow-sm border border-gray-200 p-6"
                            >
                                <!-- Título del documento -->
                                <div
                                    class="flex items-center justify-between mb-4"
                                >
                                    <h3
                                        class="text-lg font-medium text-gray-900"
                                    >
                                        {{ documento.nombre }}
                                    </h3>
                                    <div class="flex items-center">
                                        <svg
                                            class="w-5 h-5 text-green-500 mr-2"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                            ></path>
                                        </svg>
                                        <span
                                            class="text-green-600 font-medium"
                                        >
                                            {{
                                                documento.estado || "Entregado"
                                            }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Información del documento -->
                                <div
                                    class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4"
                                >
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">
                                            Fecha de entrega:
                                        </p>
                                        <p
                                            class="text-sm font-medium text-gray-900"
                                        >
                                            {{
                                                formatDate(documento.created_at)
                                            }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600 mb-1">
                                            Vence:
                                        </p>
                                        <p
                                            class="text-sm font-medium text-gray-900"
                                        >
                                            {{
                                                documento.fecha_vencimiento ||
                                                "30/12/2024"
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
                                                documento.archivo ||
                                                "contrato_siembra_TAB001.pdf"
                                            }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Observaciones -->
                                <div
                                    class="mb-4 bg-gray-50 py-4 rounded-lg px-4"
                                >
                                    <p class="text-sm text-gray-600 mb-1">
                                        <strong>Observaciones:</strong>
                                        {{
                                            documento.observaciones ||
                                            "Documento aprobado sin observaciones"
                                        }}
                                    </p>
                                </div>

                                <!-- Acciones -->
                                <div class="flex justify-end space-x-3">
                                    <button
                                        @click="downloadDocument(documento.id)"
                                        class="inline-flex items-center justify-center p-2 text-blue-600 hover:text-blue-700 hover:bg-blue-100 rounded-lg transition-colors"
                                        title="Ver documento"
                                    >
                                        <svg
                                            class="w-6 h-6"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg"
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
                                    </button>
                                    <button
                                        @click="downloadDocument(documento.id)"
                                        class="inline-flex items-center justify-center p-2 text-green-600 hover:text-green-900 hover:bg-green-100 rounded-lg transition-colors"
                                        title="Descargar documento"
                                    >
                                        <svg
                                            class="w-6 h-6"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            ></path>
                                        </svg>
                                    </button>
                                    <button
                                        class="inline-flex items-center justify-center p-2 text-yellow-600 hover:text-yellow-700 hover:bg-yellow-100 rounded-lg transition-colors"
                                        title="Editar documento"
                                    >
                                        <svg
                                            class="w-6 h-6"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg"
                                        >
                                            <path
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            ></path>
                                        </svg>
                                    </button>
                                    <button
                                        @click="deleteDocument(documento.id)"
                                        class="inline-flex items-center justify-center p-2 text-red-600 hover:text-red-700 hover:bg-red-100 rounded-lg transition-colors"
                                        title="Eliminar documento"
                                    >
                                        <svg
                                            class="w-6 h-6"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                            viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg"
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
                        <div v-else class="text-center py-8">
                            <p class="text-gray-500">
                                No hay documentos cargados para este productor.
                            </p>
                        </div>
                    </div>

                    <!-- Pestaña Comunicaciones -->
                    <div v-if="activeTab === 'comunicaciones'">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">
                                Comunicaciones
                            </h3>
                            <button
                                @click="showCommunicationModal = true"
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
                                Nueva Comunicación
                            </button>
                        </div>

                        <div
                            v-if="comunicaciones && comunicaciones.length > 0"
                            class="space-y-4"
                        >
                            <div
                                v-for="comunicacion in comunicaciones"
                                :key="comunicacion.id"
                                class="bg-gray-50 rounded-lg p-4 border border-gray-200"
                            >
                                <div
                                    class="flex justify-between items-start mb-2"
                                >
                                    <div class="flex-1">
                                        <h4
                                            class="text-sm font-medium text-gray-900"
                                        >
                                            {{ comunicacion.asunto }}
                                        </h4>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ comunicacion.tipo }} -
                                            {{
                                                formatDate(
                                                    comunicacion.created_at
                                                )
                                            }}
                                        </p>
                                    </div>
                                    <span
                                        :class="
                                            getComunicacionEstadoClass(
                                                comunicacion.estado
                                            )
                                        "
                                        class="px-2 py-1 text-xs font-semibold rounded-full"
                                    >
                                        {{ comunicacion.estado }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-700 mb-3">
                                    {{ comunicacion.mensaje }}
                                </p>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs text-gray-500"
                                        >Por: {{ comunicacion.usuario }}</span
                                    >
                                    <div class="flex space-x-2">
                                        <button
                                            class="text-blue-600 hover:text-blue-900 text-xs font-medium inline-flex items-center"
                                            title="Ver detalles"
                                        >
                                            <svg
                                                class="w-3 h-3 mr-1"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                                ></path>
                                            </svg>
                                            Ver detalles
                                        </button>
                                        <button
                                            class="text-green-600 hover:text-green-900 text-xs font-medium inline-flex items-center"
                                            title="Responder"
                                        >
                                            <svg
                                                class="w-3 h-3 mr-1"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"
                                                ></path>
                                            </svg>
                                            Responder
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-else class="text-center py-8">
                            <svg
                                class="mx-auto h-12 w-12 text-gray-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                                ></path>
                            </svg>
                            <p class="text-gray-500 mt-2">
                                No hay comunicaciones registradas para este
                                productor.
                            </p>
                        </div>
                    </div>

                    <!-- Pestaña Historial -->
                    <div v-if="activeTab === 'historial'">
                        <div class="mb-4">
                            <h3 class="text-lg font-medium text-gray-900">
                                Historial de Actividades
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">
                                Registro cronológico de todas las actividades
                                del productor
                            </p>
                        </div>

                        <div
                            v-if="historial && historial.length > 0"
                            class="flow-root"
                        >
                            <ul class="-mb-8">
                                <li
                                    v-for="(actividad, index) in historial"
                                    :key="actividad.id"
                                    class="relative pb-8"
                                >
                                    <div
                                        v-if="index !== historial.length - 1"
                                        class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"
                                        aria-hidden="true"
                                    ></div>
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span
                                                :class="
                                                    getHistorialIconClass(
                                                        actividad.tipo
                                                    )
                                                "
                                                class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white"
                                            >
                                                <svg
                                                    class="h-4 w-4 text-white"
                                                    fill="currentColor"
                                                    viewBox="0 0 20 20"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5">
                                            <div>
                                                <p
                                                    class="text-sm text-gray-900 font-medium"
                                                >
                                                    {{ actividad.descripcion }}
                                                </p>
                                                <p
                                                    class="text-xs text-gray-500 mt-1"
                                                >
                                                    {{ actividad.detalles }}
                                                </p>
                                            </div>
                                            <div
                                                class="mt-2 text-xs text-gray-500"
                                            >
                                                <time>{{
                                                    formatDate(
                                                        actividad.created_at
                                                    )
                                                }}</time>
                                                <span class="mx-1">•</span>
                                                <span>{{
                                                    actividad.usuario
                                                }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div v-else class="text-center py-8">
                            <svg
                                class="mx-auto h-12 w-12 text-gray-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                ></path>
                            </svg>
                            <p class="text-gray-500 mt-2">
                                No hay actividades registradas en el historial.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Agregar Documento -->
        <div
            v-if="showModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
        >
            <div
                class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white"
            >
                <div class="mt-3">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            Agregar Documento
                        </h3>
                        <button
                            @click="closeModal"
                            class="text-gray-400 hover:text-gray-600"
                        >
                            <svg
                                class="w-6 h-6"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                ></path>
                            </svg>
                        </button>
                    </div>

                    <form @submit.prevent="submitDocument" class="space-y-4">
                        <!-- Nombre del Documento -->
                        <div>
                            <label
                                for="nombre"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Nombre del Documento *
                            </label>
                            <input
                                id="nombre"
                                v-model="documentForm.nombre"
                                type="text"
                                required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                :class="{
                                    'border-red-500':
                                        documentForm.errors.nombre,
                                }"
                            />
                            <div
                                v-if="documentForm.errors.nombre"
                                class="mt-2 text-sm text-red-600"
                            >
                                {{ documentForm.errors.nombre }}
                            </div>
                        </div>

                        <!-- Tipo de Documento -->
                        <div>
                            <label
                                for="tipo"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Tipo de Documento
                            </label>
                            <input
                                id="tipo"
                                v-model="documentForm.tipo"
                                type="text"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                :class="{
                                    'border-red-500': documentForm.errors.tipo,
                                }"
                            />
                            <div
                                v-if="documentForm.errors.tipo"
                                class="mt-2 text-sm text-red-600"
                            >
                                {{ documentForm.errors.tipo }}
                            </div>
                        </div>

                        <!-- Archivo -->
                        <div>
                            <label
                                for="archivo"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Archivo *
                            </label>
                            <input
                                id="archivo"
                                type="file"
                                @change="handleFileChange"
                                accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                :class="{
                                    'border-red-500':
                                        documentForm.errors.archivo,
                                }"
                            />
                            <p class="mt-1 text-sm text-gray-500">
                                Formatos permitidos: PDF, JPG, PNG, DOC, DOCX.
                                Tamaño máximo: 10MB
                            </p>
                            <div
                                v-if="documentForm.errors.archivo"
                                class="mt-2 text-sm text-red-600"
                            >
                                {{ documentForm.errors.archivo }}
                            </div>
                        </div>

                        <!-- Observaciones -->
                        <div>
                            <label
                                for="observaciones"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Observaciones
                            </label>
                            <textarea
                                id="observaciones"
                                v-model="documentForm.observaciones"
                                rows="3"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                :class="{
                                    'border-red-500':
                                        documentForm.errors.observaciones,
                                }"
                            ></textarea>
                            <div
                                v-if="documentForm.errors.observaciones"
                                class="mt-2 text-sm text-red-600"
                            >
                                {{ documentForm.errors.observaciones }}
                            </div>
                        </div>

                        <!-- Documento Requerido -->
                        <div class="flex items-center">
                            <input
                                id="es_requerido"
                                v-model="documentForm.es_requerido"
                                type="checkbox"
                                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                            />
                            <label
                                for="es_requerido"
                                class="ml-2 block text-sm text-gray-700"
                            >
                                Documento requerido
                            </label>
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-end space-x-3 pt-4">
                            <button
                                type="button"
                                @click="closeModal"
                                class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                :disabled="documentForm.processing"
                                class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50"
                            >
                                {{
                                    documentForm.processing
                                        ? "Guardando..."
                                        : "Guardar Documento"
                                }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal para Nueva Comunicación -->
        <div
            v-if="showCommunicationModal"
            class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50"
        >
            <div
                class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white"
            >
                <div class="mt-3">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">
                            Nueva Comunicación
                        </h3>
                        <button
                            @click="showCommunicationModal = false"
                            class="text-gray-400 hover:text-gray-600"
                        >
                            <svg
                                class="w-6 h-6"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                ></path>
                            </svg>
                        </button>
                    </div>

                    <form
                        @submit.prevent="submitCommunication"
                        class="space-y-4"
                    >
                        <!-- Tipo de Comunicación -->
                        <div>
                            <label
                                for="tipo_comunicacion"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Tipo de Comunicación *
                            </label>
                            <select
                                id="tipo_comunicacion"
                                v-model="communicationForm.tipo"
                                required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            >
                                <option value="">Seleccionar tipo</option>
                                <option value="Email">Email</option>
                                <option value="SMS">SMS</option>
                                <option value="Llamada">
                                    Llamada Telefónica
                                </option>
                                <option value="WhatsApp">WhatsApp</option>
                                <option value="Carta">Carta</option>
                            </select>
                        </div>

                        <!-- Asunto -->
                        <div>
                            <label
                                for="asunto"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Asunto *
                            </label>
                            <input
                                id="asunto"
                                v-model="communicationForm.asunto"
                                type="text"
                                required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            />
                        </div>

                        <!-- Mensaje -->
                        <div>
                            <label
                                for="mensaje"
                                class="block text-sm font-medium text-gray-700"
                            >
                                Mensaje *
                            </label>
                            <textarea
                                id="mensaje"
                                v-model="communicationForm.mensaje"
                                rows="4"
                                required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                            ></textarea>
                        </div>

                        <!-- Botones -->
                        <div class="flex justify-end space-x-3 pt-4">
                            <button
                                type="button"
                                @click="showCommunicationModal = false"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500"
                            >
                                Cancelar
                            </button>
                            <button
                                type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >
                                Enviar Comunicación
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link, useForm, router } from "@inertiajs/vue3";
import { ref } from "vue";

const props = defineProps({
    productor: Object,
});

const showModal = ref(false);
const showCommunicationModal = ref(false);
const activeTab = ref("documentos");

// Datos de ejemplo para comunicaciones
const comunicaciones = ref([
    {
        id: 1,
        asunto: "Documentación pendiente",
        tipo: "Email",
        estado: "Enviado",
        mensaje:
            "Se requiere la presentación del certificado AFIP actualizado para completar el expediente.",
        usuario: "Admin Sistema",
        created_at: "2024-01-15T10:30:00Z",
    },
    {
        id: 2,
        asunto: "Recordatorio vencimiento",
        tipo: "SMS",
        estado: "Pendiente",
        mensaje:
            "Su contrato de siembra vence el 30/12/2024. Favor contactar para renovación.",
        usuario: "Sistema Automático",
        created_at: "2024-01-10T14:20:00Z",
    },
]);

// Datos de ejemplo para historial
const historial = ref([
    {
        id: 1,
        tipo: "documento",
        descripcion: "Documento cargado",
        detalles: "Se cargó el Contrato de Siembra",
        usuario: "Juan Pérez",
        created_at: "2024-01-15T09:15:00Z",
    },
    {
        id: 2,
        tipo: "comunicacion",
        descripcion: "Comunicación enviada",
        detalles: "Email enviado sobre documentación pendiente",
        usuario: "Admin Sistema",
        created_at: "2024-01-15T10:30:00Z",
    },
    {
        id: 3,
        tipo: "actualizacion",
        descripcion: "Datos actualizados",
        detalles: "Se actualizó la información de contacto",
        usuario: "María García",
        created_at: "2024-01-12T16:45:00Z",
    },
]);

const documentForm = useForm({
    productor_id: props.productor.id,
    nombre: "",
    tipo: "",
    archivo: null,
    observaciones: "",
    es_requerido: false,
});

const communicationForm = useForm({
    productor_id: props.productor.id,
    tipo: "",
    asunto: "",
    mensaje: "",
});

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        // Validar tamaño del archivo (10MB máximo)
        if (file.size > 10 * 1024 * 1024) {
            alert(
                "El archivo es demasiado grande. El tamaño máximo permitido es 10MB."
            );
            event.target.value = "";
            return;
        }

        // Validar tipo de archivo
        const allowedTypes = [
            "application/pdf",
            "image/jpeg",
            "image/jpg",
            "image/png",
            "application/msword",
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
        ];
        if (!allowedTypes.includes(file.type)) {
            alert(
                "Tipo de archivo no permitido. Solo se permiten archivos PDF, JPG, PNG, DOC y DOCX."
            );
            event.target.value = "";
            return;
        }

        documentForm.archivo = file;
    }
};

const submitDocument = () => {
    documentForm.post(route("documentos.store"), {
        onSuccess: () => {
            closeModal();
            // Recargar la página para mostrar el nuevo documento
            router.reload();
        },
        onError: (errors) => {
            console.log("Errores:", errors);
        },
    });
};

const closeModal = () => {
    showModal.value = false;
    documentForm.reset();
    documentForm.clearErrors();
};

const downloadDocument = (documentoId) => {
    window.open(route("documentos.download", documentoId), "_blank");
};

const deleteDocument = (documentoId) => {
    if (confirm("¿Estás seguro de que deseas eliminar este documento?")) {
        router.delete(route("documentos.destroy", documentoId), {
            onSuccess: () => {
                router.reload();
            },
        });
    }
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

const getDocumentoEstadoClass = (estado) => {
    switch (estado) {
        case "entregado":
        case "aprobado":
            return "bg-green-100 text-green-800";
        case "pendiente":
            return "bg-yellow-100 text-yellow-800";
        case "rechazado":
        case "vencido":
            return "bg-red-100 text-red-800";
        default:
            return "bg-gray-100 text-gray-800";
    }
};

const getComunicacionEstadoClass = (estado) => {
    switch (estado) {
        case "Enviado":
            return "bg-green-100 text-green-800";
        case "Pendiente":
            return "bg-yellow-100 text-yellow-800";
        case "Error":
            return "bg-red-100 text-red-800";
        default:
            return "bg-gray-100 text-gray-800";
    }
};

const getHistorialIconClass = (tipo) => {
    switch (tipo) {
        case "documento":
            return "bg-blue-500";
        case "comunicacion":
            return "bg-green-500";
        case "actualizacion":
            return "bg-yellow-500";
        default:
            return "bg-gray-500";
    }
};

const submitCommunication = () => {
    communicationForm.post(route("comunicaciones.store"), {
        onSuccess: () => {
            showCommunicationModal.value = false;
            communicationForm.reset();
            // Recargar la página para mostrar la nueva comunicación
            router.reload();
        },
        onError: (errors) => {
            console.log("Errores:", errors);
        },
    });
};

const formatDate = (dateString) => {
    if (!dateString) return "No especificada";
    const date = new Date(dateString);
    return date.toLocaleDateString("es-ES", {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
    });
};
</script>
