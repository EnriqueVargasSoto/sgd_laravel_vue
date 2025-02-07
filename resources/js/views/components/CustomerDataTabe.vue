<script setup>
import dayjs from "dayjs";
import { ref } from "vue";

const props = defineProps({
  endpoint: String, // Ruta API
  dynamicComponent: {
    type: Object,
    required: true,
  },
});



//Variables reactivas
const search = ref('')
const itemsPerPage = ref(10)
const totalItems = computed(() => data.value.recordsTotal)
const page = ref(1)
const sortBy = ref()
const orderBy = ref()
const check = ref(false);

//data obtenida del api
const { data } = await useApi(createUrl(`/${props.endpoint}`, {
  query: {
    search,
    per_page: itemsPerPage,
    page,
    //sortBy,
    //orderBy,
  },
}))

// Variables para la tabla
const headers = ref([]);
const buttons = ref([]);
const filters = ref([]);
const title = ref("Tabla");
const tableData = computed(() => data.value.data)


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

// Función para inicializar la tabla (obtener configuración inicial)
const fetchInitTabla = async () => {
  try {
    const { data } = await useApi(`/${props.endpoint}-inicializa-tabla`);

    headers.value = data?.value.data?.headers || [];
    buttons.value = data?.value.data?.buttons || [];
    filters.value = data?.value.data?.filters || [];
    title.value = data?.value.data?.title || "Tabla";
    itemsPerPage.value = data?.value.data?.par_page || 10;
    page.value = data?.value.data?.page || 1;
    check.value = data?.value.data?.check || false;
  } catch (error) {
    console.error("Error al cargar la configuración de la tabla:", error);
  }
};

// Función para obtener los datos paginados
/* const fetchData = async () => {
  try {
    //loading.value = true;
    const { data } = await useApi(`/${props.endpoint}`, {
      query: {
        search: search.value,
        per_page: itemsPerPage.value,
        page: page.value,
      },
    });

  } catch (error) {
    console.error("Error al cargar los datos:", error);
  } finally {
    //loading.value = false;
  }
}; */

// Función para formatear la fecha
const formatDate = (timestamp) => {
  return dayjs(timestamp).format("DD/MM/YYYY");
};






//const headers = computed(() => permissionsData.value.headers)


const editPermission = name => {
  isPermissionDialogVisible.value = true
  permissionName.value = name
}

const handleAction = (action) => {
    console.log(action);
    if (action === 'create') {
        console.log(action);
        isAddPermissionDialogVisible.value = true
    }
    // Puedes agregar más acciones aquí
};

// Llamar `fetchInitTabla` una vez al montar el componente
onMounted(async () => {
  await fetchInitTabla();

});
/* computed(async () => {
    await fetchData();
}); */
</script>

<template>
  <VRow>
    <VCol cols="12">
      <VCard>
        <VCardItem>
            <div class="d-flex align-center justify-space-between flex-wrap gap-4">
                <!-- Titulo -->
                <div class="d-flex gap-2 align-center">
                    <VCardTitle>{{title}}</VCardTitle>
                </div>

                <!-- Botones -->
                <div class="d-flex align-center gap-4 flex-wrap">

                    <!-- <VBtn
                        density="default"
                        prepend-icon="tabler-plus"
                        @click="isAddPermissionDialogVisible = true"
                    >
                        Agregar Permiso
                    </VBtn> -->

                    <VBtn
                        v-for="btn in buttons"
                        :key="btn.action"
                        :density="btn.density"
                        :prepend-icon="btn.icon"
                        :color="btn.color"
                        @click="handleAction(btn.action)"
                    >
                        {{ btn.label }}
                    </VBtn>
                </div>
            </div>

        </VCardItem>
        <VCardText class="d-flex align-center justify-space-between flex-wrap gap-4">
          <!-- <div class="d-flex gap-2 align-center">
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
          </div> -->

          <div class="d-flex align-center gap-4 flex-wrap">
            <AppTextField
              v-model="search"
              placeholder="Buscar..."
              style="inline-size: 15.625rem;"
            />
           <!--  <VBtn
              density="default"
              prepend-icon="tabler-plus"
              @click="isAddPermissionDialogVisible = true"
            >
              Agregar Permiso
            </VBtn> -->
          </div>
        </VCardText>

        <VDivider />

        <VDataTableServer
          v-model:items-per-page="itemsPerPage"
          v-model:page="page"
          :items-length="totalItems"
          :items-per-page-options="[
            { value: 5, title: '5' },
            { value: 10, title: '10' },
            { value: -1, title: '$vuetify.dataFooter.itemsPerPageAll' },
          ]"
          :headers="headers"
          :items="tableData"
          :show-select="check"
          prev-page-label="'Previous'"
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
            <div class="d-flex flex-column pa-4" style="padding-left: 24px!important;padding-right: 24px!important;">
                <!-- Select para la cantidad de registros por página -->
                <div class="d-flex gap-2 align-center">
                        <p class="text-body-1 mb-0">
                        Ver
                        </p>
                        <AppSelect
                        :model-value="itemsPerPage"
                        :items="[
                            { value: 5, title: '5' },
                            { value: 10, title: '10' },
                            { value: 25, title: '25' },
                            { value: 50, title: '50' },
                            { value: 100, title: '100' },
                            { value: totalItems, title: 'Todos' },
                        ]"
                        style="inline-size: 7.0rem;"
                        @update:model-value="itemsPerPage = parseInt($event, 10)"
                        />
                    </div>

                <!-- Texto de información y la paginación -->
                <div class="d-flex justify-space-between align-center w-100">
                <!-- Texto de "Mostrando X al Y de Z registros" -->
                <span class="text-caption text-secondary">
                    Mostrando {{ (page - 1) * itemsPerPage + 1 }} al
                    {{ Math.min(page * itemsPerPage, totalItems) }} de {{ totalItems }} registros
                </span>

                <!-- Paginación -->
                <VPagination
                    v-model="page"
                    :length="Math.ceil(totalItems / itemsPerPage)"
                    :total-visible="5"
                    :show-first-last-page="false"
                    active-color="info"
                />
                </div>
            </div>
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

      <component :is="dynamicComponent" v-model:is-dialog-visible="isAddPermissionDialogVisible"/>


      <!-- <AddEditPermissionComponentDialog
        v-model:is-dialog-visible="isPermissionDialogVisible"
        v-model:permission-name="permissionName"
      />
      <AddEditPermissionComponentDialog v-model:is-dialog-visible="isAddPermissionDialogVisible" /> -->
    </VCol>


  </VRow>
</template>
