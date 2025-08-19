<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Crear Nuevo Productor
            </h2>
        </template>

        <div class="py-12">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form @submit.prevent="submit">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Número de Productor -->
                            <div>
                                <label
                                    for="numero_productor"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Número de Productor *
                                </label>
                                <input
                                    id="numero_productor"
                                    v-model="form.numero_productor"
                                    type="text"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    :class="{
                                        'border-red-500':
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

                            <!-- Nombre Completo -->
                            <div>
                                <label
                                    for="nombre_completo"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Nombre Completo *
                                </label>
                                <input
                                    id="nombre_completo"
                                    v-model="form.nombre_completo"
                                    type="text"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    :class="{
                                        'border-red-500':
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

                            <!-- CUIT/CUIL -->
                            <div>
                                <label
                                    for="cuit_cuil"
                                    class="block text-sm font-medium text-gray-700"
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
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    :class="{
                                        'border-red-500':
                                            errors.cuit_cuil || cuitCuilError,
                                    }"
                                    required
                                />
                                <div
                                    v-if="errors.cuit_cuil || cuitCuilError"
                                    class="mt-2 text-sm text-red-600"
                                >
                                    {{ errors.cuit_cuil || cuitCuilError }}
                                </div>
                            </div>

                            <!-- Teléfono -->
                            <div>
                                <label
                                    for="telefono"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Teléfono
                                </label>
                                <input
                                    id="telefono"
                                    v-model="form.telefono"
                                    type="text"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    :class="{
                                        'border-red-500': errors.telefono,
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
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Email
                                </label>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    :class="{
                                        'border-red-500': errors.email,
                                    }"
                                />
                                <div
                                    v-if="errors.email"
                                    class="mt-2 text-sm text-red-600"
                                >
                                    {{ errors.email }}
                                </div>
                            </div>

                            <!-- Dirección -->
                            <div class="md:col-span-2">
                                <label
                                    for="direccion"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Dirección
                                </label>
                                <input
                                    id="direccion"
                                    v-model="form.direccion"
                                    type="text"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    :class="{
                                        'border-red-500': errors.direccion,
                                    }"
                                />
                                <div
                                    v-if="errors.direccion"
                                    class="mt-2 text-sm text-red-600"
                                >
                                    {{ errors.direccion }}
                                </div>
                            </div>

                            <!-- Localidad -->
                            <div>
                                <label
                                    for="localidad"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Localidad
                                </label>
                                <input
                                    id="localidad"
                                    v-model="form.localidad"
                                    type="text"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    :class="{
                                        'border-red-500': errors.localidad,
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
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Departamento
                                </label>
                                <input
                                    id="departamento"
                                    v-model="form.departamento"
                                    type="text"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    :class="{
                                        'border-red-500': errors.departamento,
                                    }"
                                />
                                <div
                                    v-if="errors.departamento"
                                    class="mt-2 text-sm text-red-600"
                                >
                                    {{ errors.departamento }}
                                </div>
                            </div>

                            <!-- Estado de Documentación -->
                            <div class="md:col-span-2">
                                <label
                                    for="estado_documentacion"
                                    class="block text-sm font-medium text-gray-700"
                                >
                                    Estado de Documentación *
                                </label>
                                <select
                                    id="estado_documentacion"
                                    v-model="form.estado_documentacion"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    :class="{
                                        'border-red-500':
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
                                    <option value="Aprobado">Aprobado</option>
                                    <option value="Faltante">Faltante</option>
                                </select>
                                <div
                                    v-if="errors.estado_documentacion"
                                    class="mt-2 text-sm text-red-600"
                                >
                                    {{ errors.estado_documentacion }}
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div
                            class="mt-6 flex items-center justify-end space-x-4"
                        >
                            <Link
                                :href="route('productores.index')"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
                            >
                                Cancelar
                            </Link>
                            <button
                                type="submit"
                                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                            >
                                Crear Productor
                            </button>
                        </div>
                    </form>
                </div>
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
