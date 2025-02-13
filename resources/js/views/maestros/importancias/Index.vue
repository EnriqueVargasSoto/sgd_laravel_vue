<script setup>
import AddEditImportanciaComponentDialog from './components/AddEditImportanciaComponentDialog.vue'; // Asegúrate de importar el componente

</script>

<template>
    <VRow>
        <VCol cols="12">
            <CustomerDataTabe endpoint="importancias"
                :dynamic-component="AddEditImportanciaComponentDialog"
                :component-props="{
                    isDialogVisible: false,
                    permissionName: 'Agregar Importancia',
                    endpoint: 'importancias'
                }"
                @refreshTable="reloadTable"
            />
        </VCol>
    </VRow>
</template>
<style scoped>

</style>
