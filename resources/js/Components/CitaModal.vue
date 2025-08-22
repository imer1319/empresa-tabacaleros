<template>
    <Modal :show="show" @close="closeModal" :closeable="true" :maxWidth="'2xl'">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <!-- Encabezado del formulario -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-4">
                    <div
                        class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center"
                    >
                        <svg
                            class="w-6 h-6 text-white"
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
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-900">
                            {{ isEditing ? "Editar Cita" : "Nueva Cita" }}
                        </h2>
                        <p class="text-sm text-gray-500">
                            Complete los campos requeridos (*)
                        </p>
                    </div>
                </div>
            </div>

            <form @submit.prevent="submitForm" class="space-y-6">
                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-200 p-4"
                >
                    <InputLabel
                        for="descripcion"
                        value="Descripción"
                        class="text-gray-700 font-semibold"
                    />
                    <TextArea
                        id="descripcion"
                        v-model="form.descripcion"
                        type="text"
                        class="mt-2 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        required
                        placeholder="Ingrese la descripción de la cita..."
                    />
                    <InputError
                        :message="form.errors.descripcion"
                        class="mt-2 text-sm"
                    />
                </div>

                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-200 p-4"
                >
                    <InputLabel
                        for="fecha_visita"
                        value="Fecha de Visita *"
                        class="text-gray-700 font-semibold"
                    />
                    <TextInput
                        id="fecha_visita"
                        v-model="form.fecha_visita"
                        type="date"
                        class="mt-2 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        required
                    />
                    <InputError
                        :message="form.errors.fecha_visita"
                        class="mt-2 text-sm text-red-600"
                    />
                </div>

                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-200 p-4"
                >
                    <InputLabel
                        for="fecha_proxima_cita"
                        value="Fecha de Próxima Cita"
                        class="text-gray-700 font-semibold"
                    />
                    <TextInput
                        id="fecha_proxima_cita"
                        v-model="form.fecha_proxima_cita"
                        type="date"
                        class="mt-2 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    />
                    <InputError
                        :message="form.errors.fecha_proxima_cita"
                        class="mt-2 text-sm text-red-600"
                    />
                </div>

                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-200 p-4"
                >
                    <InputLabel
                        for="estado"
                        value="Estado *"
                        class="text-gray-700 font-semibold"
                    />
                    <SelectInput
                        id="estado"
                        v-model="form.estado"
                        class="mt-2 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        required
                    >
                        <option value="pendiente">Pendiente</option>
                        <option value="asistio">Asistió</option>
                        <option value="no_asistio">No Asistió</option>
                    </SelectInput>
                    <InputError
                        :message="form.errors.estado"
                        class="mt-2 text-sm text-red-600"
                    />
                </div>

                <div
                    class="flex justify-end space-x-4 pt-6 mt-6 border-t border-gray-200"
                >
                    <SecondaryButton @click="closeModal" class="px-6 py-2">
                        Cancelar
                    </SecondaryButton>

                    <PrimaryButton
                        class="px-6 py-2"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        {{ isEditing ? "Actualizar" : "Guardar" }}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>

<script setup>
import { ref, watch, computed } from "vue";
import Modal from "@/Components/Modal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import SelectInput from "@/Components/SelectInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    cita: {
        type: Object,
        default: null,
    },
    productorId: {
        type: Number,
        required: true,
    },
});

const emit = defineEmits(["close", "saved"]);

const isEditing = computed(() => props.cita !== null);

const form = useForm({
    productor_id: props.productorId,
    descripcion: "",
    fecha_visita: new Date().toISOString().split("T")[0],
    fecha_proxima_cita: "",
    estado: "pendiente",
});

watch(
    () => props.cita,
    (newCita) => {
        if (newCita) {
            form.descripcion = newCita.descripcion;
            form.fecha_visita = newCita.fecha_visita
                ? new Date(newCita.fecha_visita).toISOString().split("T")[0]
                : new Date().toISOString().split("T")[0];
            form.fecha_proxima_cita = newCita.fecha_proxima_cita
                ? new Date(newCita.fecha_proxima_cita)
                      .toISOString()
                      .split("T")[0]
                : "";
            form.estado = newCita.estado;
        } else {
            form.reset();
            form.productor_id = props.productorId;
            form.fecha_visita = new Date().toISOString().split("T")[0];
            form.estado = "pendiente";
        }
    },
    { immediate: true }
);

const closeModal = () => {
    form.reset();
    form.productor_id = props.productorId;
    form.fecha_visita = new Date().toISOString().split("T")[0];
    form.estado = "pendiente";
    emit("close");
};

const submitForm = () => {
    if (isEditing.value) {
        form.put(route("citas.update", props.cita.id), {
            preserveScroll: true,
            onSuccess: () => {
                emit("saved");
                closeModal();
            },
        });
    } else {
        form.post(route("citas.store"), {
            preserveScroll: true,
            onSuccess: () => {
                emit("saved");
                closeModal();
            },
        });
    }
};
</script>
