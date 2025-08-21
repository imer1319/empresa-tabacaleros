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
                        <div class="flex items-center space-x-3 mb-4">
                            <div class="bg-blue-600 p-2 rounded-lg">
                                <svg
                                    class="w-5 h-5 text-white"
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
                            <h3 class="text-lg font-semibold text-gray-900">
                                Información del Productor
                            </h3>
                        </div>
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
                            <dt class="text-md font-medium text-gray-500">
                                Número de Productor
                            </dt>
                            <dd class="mt-1 text-md font-bold text-gray-900">
                                {{ productor.numero_productor }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-md font-medium text-gray-500">
                                Nombre Completo
                            </dt>
                            <dd class="mt-1 text-md text-gray-900">
                                {{ productor.nombre_completo }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-md font-medium text-gray-500">
                                CUIT/CUIL
                            </dt>
                            <dd class="mt-1 text-md text-gray-900">
                                {{ productor.cuit_cuil }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-md font-medium text-gray-500">
                                Teléfono
                            </dt>
                            <dd class="mt-1 text-md text-gray-900">
                                {{ productor.telefono || "No especificado" }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-md font-medium text-gray-500">
                                Email
                            </dt>
                            <dd class="mt-1 text-md text-gray-900">
                                {{ productor.email || "No especificado" }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-md font-medium text-gray-500">
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
                            <dt class="text-md font-medium text-gray-500">
                                Dirección
                            </dt>
                            <dd class="mt-1 text-md text-gray-900">
                                {{ productor.direccion || "No especificada" }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-md font-medium text-gray-500">
                                Localidad
                            </dt>
                            <dd class="mt-1 text-md text-gray-900">
                                {{ productor.localidad || "No especificada" }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-md font-medium text-gray-500">
                                Departamento
                            </dt>
                            <dd class="mt-1 text-md text-gray-900">
                                {{
                                    productor.departamento || "No especificado"
                                }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-md font-medium text-gray-500">
                                Fecha de Registro
                            </dt>
                            <dd class="mt-1 text-md text-gray-900">
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
                            @click="activeTab = 'citas'"
                            :class="[
                                activeTab === 'citas'
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
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                                ></path>
                            </svg>
                            Citas
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
                                @click="openDocumentoModal()"
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
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
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
                                    <div
                                        class="flex items-center justify-between space-x-3"
                                    >
                                        <h3
                                            class="text-lg font-medium text-gray-900"
                                        >
                                            {{ documento.nombre }}
                                        </h3>
                                        <!-- Estado -->
                                        <div
                                            class="flex items-center space-x-2 px-3 py-1 rounded-full text-sm font-medium text-green-600 bg-green-100"
                                        >
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
                                                    documento.estado ||
                                                    "Entregado"
                                                }}
                                            </span>
                                        </div>
                                    </div>
                                    <!-- Acciones -->
                                    <div class="flex justify-end space-x-3">
                                        <button
                                            @click="openViewModal(documento)"
                                            class="inline-flex items-center justify-center p-2 text-blue-600 hover:text-blue-700 hover:bg-blue-100 rounded-lg transition-colors"
                                            title="Ver documento"
                                        >
                                            <svg
                                                class="w-5 h-5"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg"
                                                version="1.1"
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
                                        <a
                                            :href="
                                                route(
                                                    'documentos.download',
                                                    documento.id
                                                )
                                            "
                                            class="inline-flex items-center justify-center p-2 text-green-600 hover:text-green-700 hover:bg-green-100 rounded-lg transition-colors"
                                            title="Descargar documento"
                                            target="_blank"
                                        >
                                            <svg
                                                class="w-5 h-5"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="2"
                                                viewBox="0 0 24 24"
                                                xmlns="http://www.w3.org/2000/svg"
                                            >
                                                <path
                                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                ></path>
                                            </svg>
                                        </a>
                                        <button
                                            @click="
                                                openDocumentoModal(documento)
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
                                            @click="
                                                deleteDocument(documento.id)
                                            "
                                            class="inline-flex items-center justify-center p-2 text-red-600 hover:text-red-700 hover:bg-red-100 rounded-lg transition-colors"
                                            title="Eliminar documento"
                                        >
                                            <svg
                                                class="w-5 h-5"
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
                                                formatDateOnly(
                                                    documento.fecha_entrega
                                                ) ||
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
                                                formatDateOnly(
                                                    documento.fecha_vencimiento
                                                ) || "No especificada"
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
                                            {{ documento.archivo_nombre }}
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
                            </div>
                        </div>
                        <div v-else class="text-center py-8">
                            <p class="text-gray-500">
                                No hay documentos cargados para este productor.
                            </p>
                        </div>
                    </div>

                    <!-- Pestaña Citas -->
                    <div v-if="activeTab === 'citas'">
                        <Citas :productor="productor" />
                    </div>

                    <!-- Pestaña Historial -->
                    <div v-if="activeTab === 'historial'">
                        <Historial :historial="productor.historial" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal para Documentos -->
        <DocumentoModal
            :show="showDocumentoModal"
            :tipos-documento="tiposDocumento"
            :productor-id="productor.id"
            :documento="selectedDocumento"
            :mode="modalMode"
            @close="closeDocumentoModal"
            @submitted="refreshPage"
        />

        <!-- Vista de documento individual -->
        <DocumentShow
            v-if="selectedDocumento && modalMode === 'view'"
            :documento="selectedDocumento"
        />
        <div
            v-if="selectedDocumento"
            class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6"
        >
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-4">
                    <div
                        class="w-16 h-16 bg-blue-500 rounded-lg flex items-center justify-center"
                    >
                        <svg
                            class="w-8 h-8 text-white"
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
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            {{ selectedDocumento.nombre }}
                        </h1>
                        <p class="text-gray-600">
                            Tipo:
                            {{
                                selectedDocumento.tipo_documento?.nombre ||
                                "Sin tipo"
                            }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <span
                        :class="getEstadoClass(selectedDocumento.estado)"
                        class="px-4 py-2 rounded-full text-sm font-medium"
                    >
                        {{ selectedDocumento.estado || "Pendiente" }}
                    </span>
                </div>
            </div>

            <!-- Información básica -->
            <div
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6"
            >
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">
                        Fecha de Creación
                    </h3>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ formatDate(selectedDocumento.created_at) }}
                    </p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">
                        Fecha de Entrega
                    </h3>
                    <p class="text-lg font-semibold text-gray-900">
                        {{
                            formatDateOnly(selectedDocumento.fecha_entrega) ||
                            "No especificada"
                        }}
                    </p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">
                        Fecha de Vencimiento
                    </h3>
                    <p class="text-lg font-semibold text-gray-900">
                        {{
                            formatDateOnly(
                                selectedDocumento.fecha_vencimiento
                            ) || "No especificada"
                        }}
                    </p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">
                        Fecha de Revisión
                    </h3>
                    <p class="text-lg font-semibold text-gray-900">
                        {{
                            formatDateOnly(selectedDocumento.fecha_revision) ||
                            "No revisado"
                        }}
                    </p>
                </div>
            </div>

            <!-- Información del archivo -->
            <div
                v-if="selectedDocumento.archivo_nombre"
                class="bg-blue-50 p-6 rounded-lg mb-6"
            >
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    Archivo Adjunto
                </h3>
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center"
                        >
                            <svg
                                class="w-6 h-6 text-white"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                                version="1.1"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"
                                ></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">
                                {{ selectedDocumento.archivo_nombre }}
                            </p>
                            <p class="text-xs text-gray-600">
                                Tamaño:
                                {{
                                    selectedDocumento.tamaño_formateado ||
                                    "No disponible"
                                }}
                            </p>
                        </div>
                    </div>
                    <button
                        @click="downloadDocument(selectedDocumento)"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        <svg
                            class="w-4 h-4 mr-2"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg"
                            version="1.1"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                            ></path>
                        </svg>
                        Descargar
                    </button>
                </div>
            </div>

            <!-- Observaciones -->
            <div
                v-if="selectedDocumento.observaciones"
                class="bg-yellow-50 p-6 rounded-lg mb-6"
            >
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    Observaciones
                </h3>
                <p class="text-gray-700 whitespace-pre-wrap">
                    {{ selectedDocumento.observaciones }}
                </p>
            </div>

            <!-- Información de revisión -->
            <div
                v-if="selectedDocumento.revisor"
                class="bg-gray-50 p-6 rounded-lg"
            >
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    Información de Revisión
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Revisado por:</p>
                        <p class="text-sm font-medium text-gray-900">
                            {{ selectedDocumento.revisor.name }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">
                            Fecha de revisión:
                        </p>
                        <p class="text-sm font-medium text-gray-900">
                            {{ formatDate(selectedDocumento.fecha_revision) }}
                        </p>
                    </div>
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
                                class="w-5 h-5"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg"
                                version="1.1"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
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
import DocumentoModal from "@/Components/DocumentoModal.vue";
import DocumentShow from "@/Pages/Productores/DocumentShow.vue";
import Citas from "@/Pages/Productores/Citas.vue";
import Historial from "@/Pages/Productores/Historial.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link, useForm, router } from "@inertiajs/vue3";
import { ref, computed } from "vue";

const props = defineProps({
    productor: Object,
    tiposDocumento: Array,
});

// Variables reactivas para el modal de documentos
const showDocumentoModal = ref(false);
const selectedDocumento = ref(null);
const modalMode = ref("create"); // Modos: 'create', 'edit', 'view'

// Métodos para manejar el modal de documentos
const openDocumentoModal = (documento = null) => {
    selectedDocumento.value = documento;
    modalMode.value = documento ? "edit" : "create";
    showDocumentoModal.value = true;
    documentForm.reset();

    if (documento) {
        documentForm.nombre = documento.nombre;
        documentForm.tipo_documento_id = documento.tipo_documento_id;
        documentForm.observaciones = documento.observaciones;
        documentForm.estado = documento.estado;
        documentForm.fecha_entrega = documento.fecha_entrega;
        documentForm.fecha_vencimiento = documento.fecha_vencimiento;
    }
};

const openViewModal = (documento) => {
    selectedDocumento.value = documento;
    modalMode.value = "view";
    showDocumentoModal.value = true;
};

const closeDocumentoModal = () => {
    showDocumentoModal.value = false;
    selectedDocumento.value = null;
};



const refreshPage = () => {
    router.reload();
};
const showCommunicationModal = ref(false);
const activeTab = ref("documentos");
const selectedFile = ref(null);


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

const documentForm = useForm({
    productor_id: props.productor.id,
    nombre: "",
    tipo_documento_id: "",
    archivo: null,
    observaciones: "",
    es_requerido: false,
    estado: "pendiente",
    fecha_entrega: "",
    fecha_revision: "",
    fecha_vencimiento: "",
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

        selectedFile.value = file;
        documentForm.archivo = file;
    }
};

const removeFile = () => {
    selectedFile.value = null;
    documentForm.archivo = null;
    // Reset file input if it exists
    const fileInput = document.querySelector('input[type="file"]');
    if (fileInput) {
        fileInput.value = "";
    }
};

const formatFileSize = (bytes) => {
    if (bytes === 0) return "0 Bytes";
    const k = 1024;
    const sizes = ["Bytes", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
};

const submitDocument = () => {
    documentForm.post(route("documentos.store"), {
        onSuccess: () => {
            closeDocumentoModal();
            // Recargar la página para mostrar el nuevo documento
            router.reload();
        },
        onError: (errors) => {
            console.log("Errores:", errors);
        },
    });
    selectedFile.value = null;
    documentForm.reset();
    documentForm.clearErrors();
};

const downloadDocument = (documento) => {
    if (!documento || !documento.id) return;
    window.location.href = route("documentos.download", documento.id);
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
    return date.toLocaleDateString("es-AR", {
        year: "numeric",
        month: "long",
        day: "numeric",
        timeZone: "America/Argentina/Buenos_Aires",
    });
};

const formatDateOnly = (dateString) => {
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
