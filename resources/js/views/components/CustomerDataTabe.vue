<script setup>

const props = defineProps({
    titulo: String,
    permisos: {
        type: Array,
        required: false, // Opcional, según necesidad
        default: () => [], // Se recomienda usar una función para valores por defecto en arrays
    },
    headers: {
        type: Array,
        required: false, // Opcional, según necesidad
        default: () => [], // Se recomienda usar una función para valores por defecto en arrays
    }
  /* isDialogVisible: {
    type: Boolean,
    required: true,
  },
  permissionName: {
    type: String,
    required: false,
    default: '',
  }, */
})

const date = ref('')

/* const {
  data: productList,
  error,
} = await useApi('pages/datatable')
 */
const search = ref('')

// headers
/* const headers = [
  {
    title: 'PRODUCT',
    key: 'name',
  },
  {
    title: 'DATE',
    key: 'date',
  },
  {
    title: 'CATEGORY',
    key: 'product.category',
  },
  {
    title: 'BUYERS',
    key: 'buyer.name',
  },
  {
    title: 'PAYMENT',
    key: 'payment',
    sortable: false,
  },
  {
    title: 'STATUS',
    key: 'status',
    sortable: false,
  },
  {
    title: 'DELETE',
    key: 'delete',
    sortable: false,
  },
] */

const deleteItem = itemId => {
  if (!productList.value)
    return
  const index = productList.value.findIndex(item => item.product.id === itemId)

  productList.value.splice(index, 1)
}

const categoryIcons = [
  {
    name: 'Mouse',
    icon: 'tabler-mouse',
    color: 'warning',
  },
  {
    name: 'Glass',
    icon: 'tabler-eyeglass',
    color: 'primary',
  },
  {
    name: 'Smart Watch',
    icon: 'tabler-device-watch',
    color: 'success',
  },
  {
    name: 'Bag',
    icon: 'tabler-briefcase',
    color: 'info',
  },
  {
    name: 'Storage Device',
    icon: 'tabler-device-audio-tape',
    color: 'warning',
  },
  {
    name: 'Bluetooth',
    icon: 'tabler-bluetooth',
    color: 'error',
  },
  {
    name: 'Gaming',
    icon: 'tabler-device-gamepad-2',
    color: 'warning',
  },
  {
    name: 'Home',
    icon: 'tabler-home',
    color: 'error',
  },
  {
    name: 'VR',
    icon: 'tabler-badge-vr',
    color: 'primary',
  },
  {
    name: 'Shoes',
    icon: 'tabler-shoe',
    color: 'success',
  },
  {
    name: 'Electronics',
    icon: 'tabler-cpu',
    color: 'info',
  },
  {
    name: 'Projector',
    icon: 'tabler-theater',
    color: 'warning',
  },
  {
    name: 'iPod',
    icon: 'tabler-device-airpods',
    color: 'error',
  },
  {
    name: 'Keyboard',
    icon: 'tabler-keyboard',
    color: 'primary',
  },
  {
    name: 'Smart Phone',
    icon: 'tabler-device-mobile',
    color: 'success',
  },
  {
    name: 'Smart TV',
    icon: 'tabler-device-tv',
    color: 'info',
  },
  {
    name: 'Google Home',
    icon: 'tabler-brand-google',
    color: 'warning',
  },
  {
    name: 'Mac',
    icon: 'tabler-brand-apple',
    color: 'error',
  },
  {
    name: 'Headphone',
    icon: 'tabler-headphones',
    color: 'primary',
  },
  {
    name: 'iMac',
    icon: 'tabler-device-imac',
    color: 'success',
  },
  {
    name: 'iPhone',
    icon: 'tabler-brand-apple',
    color: 'warning',
  },
]

const resolveStatusColor = status => {
  if (status === 'Confirmed')
    return 'primary'
  if (status === 'Completed')
    return 'success'
  if (status === 'Cancelled')
    return 'error'
}

const categoryIconFilter = categoryName => {
  const index = categoryIcons.findIndex(category => category.name === categoryName)
  if (index !== -1)
    return [{
      icon: categoryIcons[index].icon,
      color: categoryIcons[index].color,
    }]

  return [{
    icon: 'tabler-help-circle',
    color: 'primary',
  }]
}

/* if (error.value)
  console.error(error.value) */

const options = ref({
  page: 1,
  itemsPerPage: 5,
  sortBy: [''],
  sortDesc: [false],
})

// 📌 Calculamos los valores de paginación dinámicamente
/* const totalRecords = computed(() => productList.length);
const startRecord = computed(() => (options.page - 1) * options.itemsPerPage + 1);
const endRecord = computed(() => Math.min(options.page * options.itemsPerPage, totalRecords.value)); */
</script>

<template>
    <VCard>
        <VCardItem>
            <div class="d-flex align-center justify-space-between flex-wrap gap-4">
                <!-- Titulo -->
                <div class="d-flex gap-2 align-center">
                    <VCardTitle>{{titulo}}</VCardTitle>
                </div>

                <!-- Botones -->
                <div class="d-flex align-center gap-4 flex-wrap">

                    <VBtn
                        density="default"
                        prepend-icon="tabler-plus"
                        @click="isAddPermissionDialogVisible = true"
                    >
                        Agregar Permiso
                    </VBtn>

                    <VBtn
                        density="default"
                        prepend-icon="tabler-plus"
                        @click="isAddPermissionDialogVisible = true"
                    >
                        Agregar Permiso
                    </VBtn>
                </div>
            </div>

        </VCardItem>

        <div>
            <!-- Filtros -->
            <VCardText style="padding: 0px 24px;">
                <VRow>
                    <VCol
                        cols="12"
                        md="4"
                    >
                        <AppTextField
                            v-model="search"
                            placeholder="Search ..."
                            append-inner-icon="tabler-search"
                            single-line
                            hide-details
                            dense
                            outlined
                            label="Standard"
                        />
                    </VCol>
                    <VCol
                        cols="12"
                        md="4"
                    >
                        <AppSelect
                            :items="['Foo', 'Bar', 'Fizz', 'Buzz']"
                            label="Standard"
                            placeholder="Select Item"
                        />
                    </VCol>

                </VRow>
            </VCardText>

            <!-- 👉 Data Table  -->
            <VDataTable
                :headers="headers"
                :items="permisos"
                :search="search"
                :items-per-page="options.itemsPerPage"
                class="text-no-wrap"
                :page="options.page"
                :options="options"
                :next-page-label="Siguiente"
                :prev-page-label="Anterior"
            >
                <!-- product -->
                <!-- <template #item.product.name="{ item }">
                    <div class="d-flex align-center">
                    <div>
                        <VImg
                        :src="item.product.image"
                        height="40"
                        width="40"
                        />
                    </div>
                    <div class="d-flex flex-column ms-3">
                        <span class="d-block font-weight-medium text-truncate text-high-emphasis">{{ item.product.name }}</span>
                        <span class="text-xs">{{ item.product.brand }}</span>
                    </div>
                    </div>
                </template> -->

                <!-- category -->
                <template #item.product.category="{ item }">
                    <div class="d-flex align-center">
                    <VAvatar
                        v-for="(category, index) in categoryIconFilter(item.product.category)"
                        :key="index"
                        size="26"
                        :color="category.color"
                        variant="tonal"
                    >
                        <VIcon
                        size="20"
                        :color="category.color"
                        class="rounded-0"
                        >
                        {{ category.icon }}
                        </VIcon>
                    </VAvatar>
                    <span class="ms-1 text-no-wrap">{{ item.product.category }}</span>
                    </div>
                </template>

                <!-- buyer -->
                <template #item.buyer.name="{ item }">
                    <div class="d-flex align-center">
                    <VAvatar
                        size="1.875rem"
                        :color="!item.buyer.avatar ? 'primary' : undefined"
                        :variant="!item.buyer.avatar ? 'tonal' : undefined"
                    >
                        <VImg
                        v-if="item.buyer.avatar"
                        :src="item.buyer.avatar"
                        />
                        <span v-else>{{ item.buyer.name.slice(0, 2).toUpperCase() }}</span>
                    </VAvatar>
                    <span class="text-no-wrap font-weight-medium text-high-emphasis ms-2">{{ item.buyer.name }}</span>
                    </div>
                </template>

                <!-- Payment -->
                <template #item.payment="{ item }">
                    <div class="d-flex flex-column">
                    <div class="d-flex align-center">
                        <span class="text-high-emphasis font-weight-medium">${{ item.payment.paidAmount }}</span>
                        <span v-if="item.payment.paidAmount !== item.payment.total">/{{ item.payment.total }}</span>
                    </div>
                    <span class="text-xs text-no-wrap">{{ item.payment.receivedPaymentStatus }}</span>
                    </div>
                </template>

                <!-- Status -->
                <template #item.status="{ item }">
                    <VChip
                    :color="resolveStatusColor(item.payment.status)"
                    :class="`text-${resolveStatusColor(item.payment.status)}`"
                    size="small"
                    class="font-weight-medium"
                    >
                    {{ item.payment.status }}
                    </VChip>
                </template>

                <!-- Delete -->
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
                    <IconBtn @click="deleteItem(item.product.id)">
                    <VIcon icon="tabler-trash" />
                    </IconBtn>
                </template>


                <!-- Paginacion -->
                <template #bottom>
                    <VCardText class="pt-2">
                        <div class="d-flex flex-wrap justify-center justify-sm-space-between gap-y-2 mt-2">

                            <div class="d-flex gap-2 align-center">
                                <p class="text-body-1 mb-0">Mostrar</p>
                                <AppSelect
                                    :model-value="options.itemsPerPage"
                                    :items="[5, 10, 25, 50, 100]"
                                    style="inline-size: 5.5rem;"
                                    @update:model-value="options.itemsPerPage = parseInt($event, 10)"
                                />
                                <p class="text-body-1 mb-0">Registros</p>
                            </div>

                            <VPagination
                                v-model="options.page"
                                active-color="info"
                                :total-visible="$vuetify.display.smAndDown ? 3 : 5"
                                :length="Math.ceil(permisos.length / options.itemsPerPage)"
                                :show-first-last-page="false"
                            />
                        </div>

                                <!-- ✅ Agregamos la información de paginación -->
                        <!-- <p class="text-body-2 pt-2 text-muted">
                            Mostrando {{ startRecord }} al {{ endRecord }} de {{ totalRecords }} registros
                        </p> -->

                    </VCardText>
                </template>
            </VDataTable>
        </div>
    </VCard>
</template>
