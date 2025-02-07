<script setup>

</script>
<template>
  <VRow>


    <VCol cols="12">
        <CustomerDataTabe endpoint="permisos" :dynamic-component="AddEditPermissionDialog">

        </CustomerDataTabe>
    </VCol>
  </VRow>
</template>
