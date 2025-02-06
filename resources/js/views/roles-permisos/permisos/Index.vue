<script setup>
import * as demoCode from '@/views/demos/forms/tables/data-table/demoCodeDataTable'
import dayjs from "dayjs";

//const headers = ref([])

const search = ref('')

// Data table options
const itemsPerPage = ref(10)
const page = ref(1)
const sortBy = ref()
const orderBy = ref()

const updateOptions = options => {
  sortBy.value = options.sortBy[0]?.key
  orderBy.value = options.sortBy[0]?.order
}

const isPermissionDialogVisible = ref(false)
const isAddPermissionDialogVisible = ref(false)
const permissionName = ref('')

const colors = {
  'Editor': {
    color: 'info',
    text: 'Editor'//'Support',
  },
  'users': {
    color: 'success',
    text: 'Users',
  },
  'manager': {
    color: 'warning',
    text: 'Manager',
  },
  'Admin': {
    color: 'primary',
    text: 'Admin',
  },
  'restricted-user': {
    color: 'error',
    text: 'Restricted User',
  },
}

const { data: permissionsData } = await useApi(createUrl('/permisos', {
  query: {
    search,
    per_page: itemsPerPage,
    page,
    //sortBy,
    //orderBy,
  },
}))

// Función para formatear la fecha
const formatDate = (timestamp) => {
  return dayjs(timestamp).format("DD/MM/YYYY");
};

const permissions = computed(() => permissionsData.value.data)
const headers = computed(() => permissionsData.value.headers)
const totalPermissions = computed(() => permissionsData.value.recordsTotal)

const editPermission = name => {
  isPermissionDialogVisible.value = true
  permissionName.value = name
}
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard>
        <VCardText class="d-flex align-center justify-space-between flex-wrap gap-4">
          <div class="d-flex gap-2 align-center">
            <p class="text-body-1 mb-0">
              Ver
            </p>
            <AppSelect
              :model-value="itemsPerPage"
              :items="[
                { value: 5, title: '5' },
                { value: 25, title: '25' },
                { value: 50, title: '50' },
                { value: 100, title: '100' },
                { value: totalPermissions, title: 'Todos' },
              ]"
              style="inline-size: 7.0rem;"
              @update:model-value="itemsPerPage = parseInt($event, 10)"
            />
          </div>

          <div class="d-flex align-center gap-4 flex-wrap">
            <AppTextField
              v-model="search"
              placeholder="Buscar..."
              style="inline-size: 15.625rem;"
            />
            <VBtn
              density="default"
              prepend-icon="tabler-plus"
              @click="isAddPermissionDialogVisible = true"
            >
              Agregar Permiso
            </VBtn>
          </div>
        </VCardText>

        <VDivider />

        <VDataTableServer
          v-model:items-per-page="itemsPerPage"
          v-model:page="page"
          :items-length="totalPermissions"
          :items-per-page-options="[
            { value: 5, title: '5' },
            { value: 10, title: '10' },
            { value: -1, title: '$vuetify.dataFooter.itemsPerPageAll' },
          ]"
          :headers="headers"
          :items="permissions"
          item-value="name"
          class="text-no-wrap"
          @update:options="updateOptions"
        >
          <!-- Name -->
          <template #item.name="{ item }">
            <div class="text-high-emphasis text-body-1">
              {{ item.name }}
            </div>
          </template>

          <!-- Assigned To -->
          <template #item.assignedTo="{ item }">
            <div class="d-flex gap-4">
              <VChip
                v-for="text in item.roles"
                :key="text"
                label
                size="small"
                :color="colors[text.name].color"
                class="font-weight-medium"
              >
                {{ colors[text.name].text }}
                  <!-- {{ text.name }} -->
              </VChip>
            </div>
          </template>

          <!-- Name -->
          <template #item.created_at="{ item }">
            <!-- <div class="text-high-emphasis text-body-1"> -->
              {{ formatDate(item.created_at) }}
            <!-- </div> -->
          </template>

          <template #bottom>
            <TablePagination
              v-model:page="page"
              :items-per-page="itemsPerPage"
              :total-items="totalPermissions"
            />
          </template>

          <!-- Actions -->
          <template #item.actions="{ item }">
            <VBtn
              icon
              size="small"
              color="medium-emphasis"
              variant="text"
              @click="editPermission(item.name)"
            >
              <VIcon
                size="22"
                icon="tabler-edit"
              />
            </VBtn>
            <IconBtn>
              <VIcon
                icon="tabler-dots-vertical"
                size="22"
              />
            </IconBtn>
          </template>
        </VDataTableServer>

      </VCard>

      <AddEditPermissionComponentDialog
        v-model:is-dialog-visible="isPermissionDialogVisible"
        v-model:permission-name="permissionName"
      />
      <AddEditPermissionComponentDialog v-model:is-dialog-visible="isAddPermissionDialogVisible" />
    </VCol>

    <!-- 👉 Kitchen Sink  -->
    <VCol cols="12">
        <!-- <VCard>
            <VCardItem>
                <VCardTitle>Kitchen Sink</VCardTitle>

            </VCardItem>

        <DemoDataTableKitchenSink />

        </VCard> -->
        <CustomerDataTabe titulo="Permisos" :permisos="permissions || []" :headers="headers"/>

    </VCol>
  </VRow>
</template>
