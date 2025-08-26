<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between align-middle">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Editar Productor
                </h2>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-4xl mx-auto">
                <form @submit.prevent="submit">
                    <!-- Sección 1: Información Personal -->
                    <div
                        class="bg-white rounded-lg shadow-sm border border-gray-200 mb-4"
                    >
                        <div class="border-b border-gray-200">
                            <div class="flex items-center px-6 pt-6">
                                <div
                                    class="flex items-center justify-center w-8 h-8 bg-blue-600 rounded-full mr-3"
                                >
                                    <svg
                                        class="w-4 h-4 text-white"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                            clip-rule="evenodd"
                                        ></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900">
                                    Información Personal
                                </h3>
                            </div>
                            <div class="p-6">
                                <div
                                    class="grid grid-cols-1 md:grid-cols-2 gap-6"
                                >
                                    <!-- Nombre Completo -->
                                    <div>
                                        <label
                                            for="nombre_completo"
                                            class="block text-sm font-medium text-gray-700 mb-2"
                                        >
                                            Nombre Completo *
                                        </label>
                                        <input
                                            id="nombre_completo"
                                            v-model="form.nombre_completo"
                                            type="text"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            :class="{
                                                'border-red-500 focus:ring-red-500 focus:border-red-500':
                                                    errors.nombre_completo,
                                            }"
                                            required
                                        />
                                        <div
                                            v-if="errors.nombre_completo"
                                            class="mt-2 text-sm text-red-600"
                                        >
                                            {{ errors.nombre_completo }}
                                        </div>
                                    </div>

                                    <!-- Número de Productor -->
                                    <div>
                                        <label
                                            for="numero_productor"
                                            class="block text-sm font-medium text-gray-700 mb-2"
                                        >
                                            Número de Productor *
                                        </label>
                                        <input
                                            id="numero_productor"
                                            v-model="form.numero_productor"
                                            type="text"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            :class="{
                                                'border-red-500 focus:ring-red-500 focus:border-red-500':
                                                    errors.numero_productor,
                                            }"
                                            required
                                        />
                                        <div
                                            v-if="errors.numero_productor"
                                            class="mt-2 text-sm text-red-600"
                                        >
                                            {{ errors.numero_productor }}
                                        </div>
                                    </div>

                                    <!-- CUIT/CUIL -->
                                    <div>
                                        <label
                                            for="cuit_cuil"
                                            class="block text-sm font-medium text-gray-700 mb-2"
                                        >
                                            CUIT/CUIL *
                                        </label>
                                        <input
                                            id="cuit_cuil"
                                            v-model="form.cuit_cuil"
                                            type="text"
                                            placeholder="xx-xxxxxxxx-x"
                                            maxlength="13"
                                            @input="formatCuitCuil"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            :class="{
                                                'border-red-500 focus:ring-red-500 focus:border-red-500':
                                                    errors.cuit_cuil ||
                                                    cuitCuilError,
                                            }"
                                            required
                                        />
                                        <div
                                            v-if="
                                                errors.cuit_cuil ||
                                                cuitCuilError
                                            "
                                            class="mt-2 text-sm text-red-600"
                                        >
                                            {{
                                                errors.cuit_cuil ||
                                                cuitCuilError
                                            }}
                                        </div>
                                    </div>

                                    <!-- Estado de Documentación -->
                                    <div>
                                        <label
                                            for="estado_documentacion"
                                            class="block text-sm font-medium text-gray-700 mb-2"
                                        >
                                            Estado de Documentación *
                                        </label>
                                        <select
                                            id="estado_documentacion"
                                            v-model="form.estado_documentacion"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            :class="{
                                                'border-red-500 focus:ring-red-500 focus:border-red-500':
                                                    errors.estado_documentacion,
                                            }"
                                            required
                                        >
                                            <option value="">
                                                Seleccione un estado
                                            </option>
                                            <option value="En proceso">
                                                En proceso
                                            </option>
                                            <option value="Aprobado">
                                                Aprobado
                                            </option>
                                            <option value="Faltante">
                                                Faltante
                                            </option>
                                        </select>
                                        <div
                                            v-if="errors.estado_documentacion"
                                            class="mt-2 text-sm text-red-600"
                                        >
                                            {{ errors.estado_documentacion }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección 2: Información de Contacto -->
                    <div
                        class="bg-white rounded-lg shadow-sm border border-gray-200 mb-4"
                    >
                        <div class="border-b border-gray-200">
                            <div class="flex items-center px-6 pt-6">
                                <div
                                    class="flex items-center justify-center w-8 h-8 bg-green-600 rounded-full mr-3"
                                >
                                    <svg
                                        class="w-4 h-4 text-white"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"
                                        ></path>
                                        <path
                                            d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"
                                        ></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900">
                                    Información de Contacto
                                </h3>
                            </div>
                            <div class="p-6">
                                <div
                                    class="grid grid-cols-1 md:grid-cols-2 gap-6"
                                >
                                    <!-- Teléfono -->
                                    <div>
                                        <label
                                            for="telefono"
                                            class="block text-sm font-medium text-gray-700 mb-2"
                                        >
                                            Teléfono *
                                        </label>
                                        <input
                                            id="telefono"
                                            v-model="form.telefono"
                                            type="text"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            :class="{
                                                'border-red-500 focus:ring-red-500 focus:border-red-500':
                                                    errors.telefono,
                                            }"
                                        />
                                        <div
                                            v-if="errors.telefono"
                                            class="mt-2 text-sm text-red-600"
                                        >
                                            {{ errors.telefono }}
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <label
                                            for="email"
                                            class="block text-sm font-medium text-gray-700 mb-2"
                                        >
                                            Email *
                                        </label>
                                        <input
                                            id="email"
                                            v-model="form.email"
                                            type="email"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            :class="{
                                                'border-red-500 focus:ring-red-500 focus:border-red-500':
                                                    errors.email,
                                            }"
                                        />
                                        <div
                                            v-if="errors.email"
                                            class="mt-2 text-sm text-red-600"
                                        >
                                            {{ errors.email }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Sección 3: Información de Ubicación -->
                    <div class="bg-white rounded-lg shadow-sm border mb-4">
                        <div>
                            <div class="flex items-center px-6 pt-6">
                                <div
                                    class="flex items-center justify-center w-8 h-8 bg-orange-600 rounded-full mr-3"
                                >
                                    <svg
                                        class="w-4 h-4 text-white"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                            clip-rule="evenodd"
                                        ></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900">
                                    Información de Ubicación
                                </h3>
                            </div>
                            <div class="p-6">
                                <div class="space-y-6">
                                    <!-- Dirección -->
                                    <div>
                                        <label
                                            for="direccion"
                                            class="block text-sm font-medium text-gray-700 mb-2"
                                        >
                                            Dirección *
                                        </label>
                                        <input
                                            id="direccion"
                                            v-model="form.direccion"
                                            type="text"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            :class="{
                                                'border-red-500 focus:ring-red-500 focus:border-red-500':
                                                    errors.direccion,
                                            }"
                                        />
                                        <div
                                            v-if="errors.direccion"
                                            class="mt-2 text-sm text-red-600"
                                        >
                                            {{ errors.direccion }}
                                        </div>
                                    </div>

                                    <div
                                        class="grid grid-cols-1 md:grid-cols-2 gap-6"
                                    >
                                        <!-- Localidad -->
                                        <div>
                                            <label
                                                for="localidad"
                                                class="block text-sm font-medium text-gray-700 mb-2"
                                            >
                                                Localidad *
                                            </label>
                                            <input
                                                id="localidad"
                                                v-model="form.localidad"
                                                type="text"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                :class="{
                                                    'border-red-500 focus:ring-red-500 focus:border-red-500':
                                                        errors.localidad,
                                                }"
                                            />
                                            <div
                                                v-if="errors.localidad"
                                                class="mt-2 text-sm text-red-600"
                                            >
                                                {{ errors.localidad }}
                                            </div>
                                        </div>

                                        <!-- Departamento -->
                                        <div>
                                            <label
                                                for="departamento"
                                                class="block text-sm font-medium text-gray-700 mb-2"
                                            >
                                                Departamento *
                                            </label>
                                            <input
                                                id="departamento"
                                                v-model="form.departamento"
                                                type="text"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                                :class="{
                                                    'border-red-500 focus:ring-red-500 focus:border-red-500':
                                                        errors.departamento,
                                                }"
                                            />
                                            <div
                                                v-if="errors.departamento"
                                                class="mt-2 text-sm text-red-600"
                                            >
                                                {{ errors.departamento }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección 4: Información Productiva y Laboral -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-4">
                        <div class="border-b border-gray-200">
                            <div class="flex items-center px-6 pt-6">
                                <div class="flex items-center justify-center w-8 h-8 bg-purple-600 rounded-full mr-3">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900">Información Productiva y Laboral</h3>
                            </div>
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Kilos Entregados -->
                                    <div>
                                        <label for="kilos_entregados" class="block text-sm font-medium text-gray-700 mb-2">Kilos Entregados</label>
                                        <input
                                            id="kilos_entregados"
                                            v-model="form.kilos_entregados"
                                            type="number"
                                            step="0.01"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            :class="{'border-red-500 focus:ring-red-500 focus:border-red-500': errors.kilos_entregados}"
                                        />
                                        <div v-if="errors.kilos_entregados" class="mt-2 text-sm text-red-600">{{ errors.kilos_entregados }}</div>
                                    </div>

                                    <!-- Superficie Medida -->
                                    <div>
                                        <label for="superficie_medida" class="block text-sm font-medium text-gray-700 mb-2">Superficie Medida (has)</label>
                                        <input
                                            id="superficie_medida"
                                            v-model="form.superficie_medida"
                                            type="number"
                                            step="0.01"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            :class="{'border-red-500 focus:ring-red-500 focus:border-red-500': errors.superficie_medida}"
                                        />
                                        <div v-if="errors.superficie_medida" class="mt-2 text-sm text-red-600">{{ errors.superficie_medida }}</div>
                                    </div>

                                    <!-- Empleados Convenio -->
                                    <div>
                                        <label for="cant_empleados_convenio" class="block text-sm font-medium text-gray-700 mb-2">Cantidad Empleados Convenio</label>
                                        <input
                                            id="cant_empleados_convenio"
                                            v-model="form.cant_empleados_convenio"
                                            type="number"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            :class="{'border-red-500 focus:ring-red-500 focus:border-red-500': errors.cant_empleados_convenio}"
                                        />
                                        <div v-if="errors.cant_empleados_convenio" class="mt-2 text-sm text-red-600">{{ errors.cant_empleados_convenio }}</div>
                                    </div>

                                    <!-- Total Salario Convenio -->
                                    <div>
                                        <label for="total_salario_convenio" class="block text-sm font-medium text-gray-700 mb-2">Total Salario Convenio</label>
                                        <input
                                            id="total_salario_convenio"
                                            v-model="form.total_salario_convenio"
                                            type="number"
                                            step="0.01"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            :class="{'border-red-500 focus:ring-red-500 focus:border-red-500': errors.total_salario_convenio}"
                                        />
                                        <div v-if="errors.total_salario_convenio" class="mt-2 text-sm text-red-600">{{ errors.total_salario_convenio }}</div>
                                    </div>

                                    <!-- Empleados Fuera Convenio -->
                                    <div>
                                        <label for="cant_empleados_fuera_convenio" class="block text-sm font-medium text-gray-700 mb-2">Cantidad Empleados Fuera Convenio</label>
                                        <input
                                            id="cant_empleados_fuera_convenio"
                                            v-model="form.cant_empleados_fuera_convenio"
                                            type="number"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            :class="{'border-red-500 focus:ring-red-500 focus:border-red-500': errors.cant_empleados_fuera_convenio}"
                                        />
                                        <div v-if="errors.cant_empleados_fuera_convenio" class="mt-2 text-sm text-red-600">{{ errors.cant_empleados_fuera_convenio }}</div>
                                    </div>

                                    <!-- Total Salario Fuera Convenio -->
                                    <div>
                                        <label for="total_salario_fuera_convenio" class="block text-sm font-medium text-gray-700 mb-2">Total Salario Fuera Convenio</label>
                                        <input
                                            id="total_salario_fuera_convenio"
                                            v-model="form.total_salario_fuera_convenio"
                                            type="number"
                                            step="0.01"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            :class="{'border-red-500 focus:ring-red-500 focus:border-red-500': errors.total_salario_fuera_convenio}"
                                        />
                                        <div v-if="errors.total_salario_fuera_convenio" class="mt-2 text-sm text-red-600">{{ errors.total_salario_fuera_convenio }}</div>
                                    </div>

                                    <!-- Jornales y Cálculos -->
                                    <div>
                                        <label for="jornal_promedio" class="block text-sm font-medium text-gray-700 mb-2">Jornal Promedio</label>
                                        <input
                                            id="jornal_promedio"
                                            v-model="form.jornal_promedio"
                                            type="number"
                                            step="0.01"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            :class="{'border-red-500 focus:ring-red-500 focus:border-red-500': errors.jornal_promedio}"
                                        />
                                        <div v-if="errors.jornal_promedio" class="mt-2 text-sm text-red-600">{{ errors.jornal_promedio }}</div>
                                    </div>

                                    <div>
                                        <label for="ayc_sobre_jornal" class="block text-sm font-medium text-gray-700 mb-2">AyC sobre Jornal</label>
                                        <input
                                            id="ayc_sobre_jornal"
                                            v-model="form.ayc_sobre_jornal"
                                            type="number"
                                            step="0.0001"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            :class="{'border-red-500 focus:ring-red-500 focus:border-red-500': errors.ayc_sobre_jornal}"
                                        />
                                        <div v-if="errors.ayc_sobre_jornal" class="mt-2 text-sm text-red-600">{{ errors.ayc_sobre_jornal }}</div>
                                    </div>

                                    <div>
                                        <label for="formula" class="block text-sm font-medium text-gray-700 mb-2">Fórmula</label>
                                        <input
                                            id="formula"
                                            v-model="form.formula"
                                            type="number"
                                            step="0.0001"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            :class="{'border-red-500 focus:ring-red-500 focus:border-red-500': errors.formula}"
                                        />
                                        <div v-if="errors.formula" class="mt-2 text-sm text-red-600">{{ errors.formula }}</div>
                                    </div>

                                    <div>
                                        <label for="total_ayc" class="block text-sm font-medium text-gray-700 mb-2">Total AyC</label>
                                        <input
                                            id="total_ayc"
                                            v-model="form.total_ayc"
                                            type="number"
                                            step="0.01"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            :class="{'border-red-500 focus:ring-red-500 focus:border-red-500': errors.total_ayc}"
                                        />
                                        <div v-if="errors.total_ayc" class="mt-2 text-sm text-red-600">{{ errors.total_ayc }}</div>
                                    </div>

                                    <div>
                                        <label for="ts_determinada" class="block text-sm font-medium text-gray-700 mb-2">TS Determinada</label>
                                        <input
                                            id="ts_determinada"
                                            v-model="form.ts_determinada"
                                            type="number"
                                            step="0.01"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            :class="{'border-red-500 focus:ring-red-500 focus:border-red-500': errors.ts_determinada}"
                                        />
                                        <div v-if="errors.ts_determinada" class="mt-2 text-sm text-red-600">{{ errors.ts_determinada }}</div>
                                    </div>

                                    <div>
                                        <label for="total_ayc_unitario" class="block text-sm font-medium text-gray-700 mb-2">Total AyC Unitario</label>
                                        <input
                                            id="total_ayc_unitario"
                                            v-model="form.total_ayc_unitario"
                                            type="number"
                                            step="0.01"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            :class="{'border-red-500 focus:ring-red-500 focus:border-red-500': errors.total_ayc_unitario}"
                                        />
                                        <div v-if="errors.total_ayc_unitario" class="mt-2 text-sm text-red-600">{{ errors.total_ayc_unitario }}</div>
                                    </div>

                                    <div>
                                        <label for="total_jornales" class="block text-sm font-medium text-gray-700 mb-2">Total Jornales</label>
                                        <input
                                            id="total_jornales"
                                            v-model="form.total_jornales"
                                            type="number"
                                            step="0.01"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            :class="{'border-red-500 focus:ring-red-500 focus:border-red-500': errors.total_jornales}"
                                        />
                                        <div v-if="errors.total_jornales" class="mt-2 text-sm text-red-600">{{ errors.total_jornales }}</div>
                                    </div>

                                    <div>
                                        <label for="jornales_x_hectarea" class="block text-sm font-medium text-gray-700 mb-2">Jornales por Hectárea</label>
                                        <input
                                            id="jornales_x_hectarea"
                                            v-model="form.jornales_x_hectarea"
                                            type="number"
                                            step="0.0001"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            :class="{'border-red-500 focus:ring-red-500 focus:border-red-500': errors.jornales_x_hectarea}"
                                        />
                                        <div v-if="errors.jornales_x_hectarea" class="mt-2 text-sm text-red-600">{{ errors.jornales_x_hectarea }}</div>
                                    </div>

                                    <div>
                                        <label for="jornales_x_hectarea_sin_ayc" class="block text-sm font-medium text-gray-700 mb-2">Jornales por Hectárea sin AyC</label>
                                        <input
                                            id="jornales_x_hectarea_sin_ayc"
                                            v-model="form.jornales_x_hectarea_sin_ayc"
                                            type="number"
                                            step="0.0001"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            :class="{'border-red-500 focus:ring-red-500 focus:border-red-500': errors.jornales_x_hectarea_sin_ayc}"
                                        />
                                        <div v-if="errors.jornales_x_hectarea_sin_ayc" class="mt-2 text-sm text-red-600">{{ errors.jornales_x_hectarea_sin_ayc }}</div>
                                    </div>

                                    <div>
                                        <label for="jornales_x_hectarea_acumulados" class="block text-sm font-medium text-gray-700 mb-2">Jornales por Hectárea Acumulados</label>
                                        <input
                                            id="jornales_x_hectarea_acumulados"
                                            v-model="form.jornales_x_hectarea_acumulados"
                                            type="number"
                                            step="0.0001"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            :class="{'border-red-500 focus:ring-red-500 focus:border-red-500': errors.jornales_x_hectarea_acumulados}"
                                        />
                                        <div v-if="errors.jornales_x_hectarea_acumulados" class="mt-2 text-sm text-red-600">{{ errors.jornales_x_hectarea_acumulados }}</div>
                                    </div>

                                    <div>
                                        <label for="dif_jornales_x_has" class="block text-sm font-medium text-gray-700 mb-2">Diferencia Jornales por Hectárea</label>
                                        <input
                                            id="dif_jornales_x_has"
                                            v-model="form.dif_jornales_x_has"
                                            type="number"
                                            step="0.0001"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            :class="{'border-red-500 focus:ring-red-500 focus:border-red-500': errors.dif_jornales_x_has}"
                                        />
                                        <div v-if="errors.dif_jornales_x_has" class="mt-2 text-sm text-red-600">{{ errors.dif_jornales_x_has }}</div>
                                    </div>

                                    <!-- Actividades -->
                                    <div class="col-span-2">
                                        <label for="actividades" class="block text-sm font-medium text-gray-700 mb-2">Actividades</label>
                                        <textarea
                                            id="actividades"
                                            v-model="form.actividades"
                                            rows="3"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            :class="{'border-red-500 focus:ring-red-500 focus:border-red-500': errors.actividades}"
                                        ></textarea>
                                        <div v-if="errors.actividades" class="mt-2 text-sm text-red-600">{{ errors.actividades }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones -->
                    <div
                        class="bg-gray-50 px-6 py-4 flex items-center justify-end space-x-4"
                    >
                        <Link
                            :href="route('productores.index')"
                            class="px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                        >
                            Cancelar
                        </Link>
                        <button
                            type="submit"
                            class="px-6 py-3 bg-green-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition-colors"
                            :class="{
                                'opacity-50 cursor-not-allowed':
                                    form.processing,
                            }"
                            :disabled="form.processing"
                        >
                            <span v-if="form.processing">Actualizando...</span>
                            <span v-else>Actualizar Productor</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";

export default {
    components: {
        AuthenticatedLayout,
        Link,
    },
    props: {
        productor: Object,
        errors: Object,
    },
    setup(props) {
        const form = useForm({
            numero_productor: props.productor.numero_productor,
            nombre_completo: props.productor.nombre_completo,
            cuit_cuil: props.productor.cuit_cuil,
            telefono: props.productor.telefono,
            email: props.productor.email,
            direccion: props.productor.direccion,
            localidad: props.productor.localidad,
            departamento: props.productor.departamento,
            estado_documentacion: props.productor.estado_documentacion,
            kilos_entregados: props.productor.kilos_entregados,
            superficie_medida: props.productor.superficie_medida,
            cant_empleados_convenio: props.productor.cant_empleados_convenio,
            total_salario_convenio: props.productor.total_salario_convenio,
            cant_empleados_fuera_convenio: props.productor.cant_empleados_fuera_convenio,
            total_salario_fuera_convenio: props.productor.total_salario_fuera_convenio,
            jornal_promedio: props.productor.jornal_promedio,
            ayc_sobre_jornal: props.productor.ayc_sobre_jornal,
            formula: props.productor.formula,
            total_ayc: props.productor.total_ayc,
            ts_determinada: props.productor.ts_determinada,
            total_ayc_unitario: props.productor.total_ayc_unitario,
            total_jornales: props.productor.total_jornales,
            jornales_x_hectarea: props.productor.jornales_x_hectarea,
            jornales_x_hectarea_sin_ayc: props.productor.jornales_x_hectarea_sin_ayc,
            jornales_x_hectarea_acumulados: props.productor.jornales_x_hectarea_acumulados,
            dif_jornales_x_has: props.productor.dif_jornales_x_has,
            actividades: props.productor.actividades,
        });

        const cuitCuilError = ref("");

        const formatCuitCuil = (event) => {
            let value = event.target.value.replace(/\D/g, ""); // Solo números

            // Limitar a 11 dígitos máximo
            if (value.length > 11) {
                value = value.substring(0, 11);
            }

            // Formatear como xx-xxxxxxxx-x
            let formattedValue = "";
            if (value.length >= 2) {
                formattedValue = value.substring(0, 2) + "-";
                if (value.length > 2) {
                    formattedValue += value.substring(2, 10);
                    if (value.length > 10) {
                        formattedValue += "-" + value.substring(10, 11);
                    }
                }
            } else {
                formattedValue = value;
            }

            form.cuit_cuil = formattedValue;

            // Validar solo cuando tengamos 11 dígitos completos
            if (value.length === 11) {
                if (!validateCuitCuil(value)) {
                    cuitCuilError.value = "CUIT/CUIL inválido";
                } else {
                    cuitCuilError.value = "";
                }
            } else {
                cuitCuilError.value = "";
            }
        };

        const validateCuitCuil = (cuit) => {
            if (cuit.length !== 11) return false;

            const multipliers = [5, 4, 3, 2, 7, 6, 5, 4, 3, 2];
            let sum = 0;

            for (let i = 0; i < 10; i++) {
                sum += parseInt(cuit[i]) * multipliers[i];
            }

            const remainder = sum % 11;
            const checkDigit = remainder < 2 ? remainder : 11 - remainder;

            return checkDigit === parseInt(cuit[10]);
        };

        const submit = () => {
            if (cuitCuilError.value) {
                return; // No enviar si hay errores de validación
            }
            form.put(route("productores.update", props.productor.id));
        };

        return {
            form,
            submit,
            formatCuitCuil,
            cuitCuilError,
        };
    },
};
</script>
