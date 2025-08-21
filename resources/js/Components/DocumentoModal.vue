<template>
    <Modal
        :show="show"
        :title="modalTitle"
        :show-footer="false"
        @close="$emit('close')"
    >
        <DocumentForm
            v-if="mode !== 'view'"
            :productor-id="productorId"
            :tipos-documento="tiposDocumento"
            :documento="documento"
            :mode="mode"
            :errors="errors"
            @submitted="$emit('submitted')"
            @cancel="$emit('close')"
        />

        <DocumentShow v-else :documento="documento" />
    </Modal>
</template>

<script setup>
import { computed } from "vue";
import Modal from "@/Components/Modal.vue";
import DocumentForm from "@/Pages/Productores/DocumentForm.vue";
import DocumentShow from "@/Pages/Productores/DocumentShow.vue";

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    tiposDocumento: {
        type: Array,
        default: () => [],
    },
    productorId: {
        type: Number,
        required: true,
    },
    documento: {
        type: Object,
        default: null,
    },
    mode: {
        type: String,
        default: "create", // 'create', 'edit', or 'view'
    },
    errors: {
        type: Object,
        default: () => ({}),
    },
});

const emit = defineEmits(["close", "submitted"]);

const modalTitle = computed(() => {
    switch (props.mode) {
        case "create":
            return "Nuevo Documento";
        case "edit":
            return "Editar Documento";
        case "view":
            return "Detalles del Documento";
        default:
            return "Documento";
    }
});
</script>
