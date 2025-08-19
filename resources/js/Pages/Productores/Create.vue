<template>
    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Crear Productor
                </h2>
                <p class="text-sm text-gray-600">
                    Complete la información del productor
                </p>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-4xl mx-auto">
                <form @submit.prevent="submit">
                    <!-- Sección 1: Información Personal -->
                    <div
                        class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6"
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
                        class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6"
                    >
                        <div class="border-b border-gray-200 mb-8">
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
                    <div
                        class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6"
                    >
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
                            <span v-if="form.processing">Creando...</span>
                            <span v-else>Crear Productor</span>
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
        errors: Object,
    },
    setup() {
        const form = useForm({
            numero_productor: "",
            nombre_completo: "",
            cuit_cuil: "",
            telefono: "",
            email: "",
            direccion: "",
            localidad: "",
            departamento: "",
            estado_documentacion: "",
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
            form.post(route("productores.store"));
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
