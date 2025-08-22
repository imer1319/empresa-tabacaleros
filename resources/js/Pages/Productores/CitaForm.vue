<template>
    <div>
        <form @submit.prevent="submit">
            <div class="mb-4">
                <InputLabel for="fecha" value="Fecha" />
                <TextInput
                    type="date"
                    id="fecha"
                    v-model="form.fecha"
                    class="mt-1 block w-full"
                    required
                />
                <InputError :message="form.errors.fecha" class="mt-2" />
            </div>

            <div class="mb-4">
                <InputLabel for="motivo" value="Motivo" />
                <TextArea
                    id="motivo"
                    v-model="form.motivo"
                    class="mt-1 block w-full"
                    rows="3"
                    required
                />
                <InputError :message="form.errors.motivo" class="mt-2" />
            </div>

            <div class="flex justify-end space-x-3">
                <SecondaryButton @click="$emit('close')">
                    Cancelar
                </SecondaryButton>
                <PrimaryButton :disabled="form.processing">
                    Guardar
                </PrimaryButton>
            </div>
        </form>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import TextArea from '@/Components/TextArea.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const emit = defineEmits(['close', 'submit']);

const form = useForm({
    fecha: '',
    motivo: ''
});

const submit = () => {
    emit('submit', form);
};
</script>