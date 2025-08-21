<template>
    <Modal :show="show" @close="closeModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                {{ isEditing ? 'Editar Cita' : 'Nueva Cita' }}
            </h2>

            <div class="mt-6">
                <form @submit.prevent="submitForm">
                    <div class="mt-6">
                        <InputLabel for="descripcion" value="Descripción" />
                        <TextArea
                            id="descripcion"
                            v-model="form.descripcion"
                            type="text"
                            class="mt-1 block w-full"
                            required
                        />
                        <InputError :message="form.errors.descripcion" class="mt-2" />
                    </div>

                    <div class="mt-6">
                        <InputLabel for="fecha_visita" value="Fecha de Visita" />
                        <TextInput
                            id="fecha_visita"
                            v-model="form.fecha_visita"
                            type="date"
                            class="mt-1 block w-full"
                            required
                        />
                        <InputError :message="form.errors.fecha_visita" class="mt-2" />
                    </div>

                    <div class="mt-6">
                        <InputLabel for="fecha_proxima_cita" value="Fecha de Próxima Cita" />
                        <TextInput
                            id="fecha_proxima_cita"
                            v-model="form.fecha_proxima_cita"
                            type="date"
                            class="mt-1 block w-full"
                        />
                        <InputError :message="form.errors.fecha_proxima_cita" class="mt-2" />
                    </div>

                    <div class="mt-6">
                        <InputLabel for="estado" value="Estado" />
                        <SelectInput
                            id="estado"
                            v-model="form.estado"
                            class="mt-1 block w-full"
                            required
                        >
                            <option value="pendiente">Pendiente</option>
                            <option value="asistio">Asistió</option>
                            <option value="no_asistio">No Asistió</option>
                        </SelectInput>
                        <InputError :message="form.errors.estado" class="mt-2" />
                    </div>

                    <div class="mt-6 flex justify-end">
                        <SecondaryButton @click="closeModal"> Cancelar </SecondaryButton>

                        <PrimaryButton
                            class="ml-3"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            {{ isEditing ? 'Actualizar' : 'Guardar' }}
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </div>
    </Modal>
</template>

<script setup>
import { ref, defineProps, defineEmits, watch, computed } from 'vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextArea from '@/Components/TextArea.vue';
import SelectInput from '@/Components/SelectInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { useForm } from '@inertiajs/vue3';

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

const emit = defineEmits(['close']);

const isEditing = computed(() => props.cita !== null);

const form = useForm({
    productor_id: props.productorId,
    descripcion: '',
    fecha_visita: new Date().toISOString().split('T')[0],
    fecha_proxima_cita: '',
    estado: 'pendiente',
});

watch(
    () => props.cita,
    (newCita) => {
        if (newCita) {
            form.descripcion = newCita.descripcion;
            form.fecha_visita = newCita.fecha_visita?.split('T')[0] || new Date().toISOString().split('T')[0];
            form.fecha_proxima_cita = newCita.fecha_proxima_cita?.split('T')[0] || '';
            form.estado = newCita.estado;
        } else {
            form.reset();
            form.productor_id = props.productorId;
            form.fecha_visita = new Date().toISOString().split('T')[0];
            form.estado = 'pendiente';
        }
    },
    { immediate: true }
);

const closeModal = () => {
    form.reset();
    form.productor_id = props.productorId;
    form.fecha_visita = new Date().toISOString().split('T')[0];
    form.estado = 'pendiente';
    emit('close');
};

const submitForm = () => {
    if (isEditing.value) {
        form.put(route('citas.update', props.cita.id), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    } else {
        form.post(route('citas.store'), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
        });
    }
};
</script>