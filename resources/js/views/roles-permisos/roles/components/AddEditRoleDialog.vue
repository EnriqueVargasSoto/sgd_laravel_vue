<script setup>
    import Swal from 'sweetalert2';

    const props = defineProps({
        endpoint: String, // Ruta API
        isDialogVisible: {
            type: Boolean,
            required: true,
        },
        /* permisos: {
            type: Object,
            default: () => ([]),
        }, */
        dato: {
            type: Object,
            default: () => ({}),
        },
    })

    const emit = defineEmits([
    'update:isDialogVisible',
    'update:rolePermissions',
    ])

    const permissions = ref([]);// await useApi(createUrl(`/modulos`))

    const isSelectAll = ref(false)
    const role = ref('')
    const name = ref('')
    const refPermissionForm = ref()

    const checkedCount = computed(() => {
        let counter = 0
        permissions.value.forEach(permission => {
            Object.entries(permission).forEach(([key, value]) => {
            if (key !== 'name' && value)
                counter++
            })
        })

        return counter
    })

    const isIndeterminate = computed(() => checkedCount.value > 0 && checkedCount.value < permissions.value.length * 3)

    watch(isSelectAll, val => {
        console.log('val: ', val);
        permissions.value = permissions.value.map( modulo => ({
            ...modulo,
            permisos: modulo.permisos.map( permiso => ({
                ...permiso,
                selected: val
            }))
        }));
    })

    watch(isIndeterminate, () => {
        if (!isIndeterminate.value)
            isSelectAll.value = false
    })

    watch(permissions, () => {
    /* if (checkedCount.value === permissions.value.length * 3)
        isSelectAll.value = true */
    }, { deep: true })

    watch(() => props, () => {
        if (props.dato && props.dato.permisos.length) {
            role.value = props.rolePermissions.name
            console.log('rol: ', props.rolePermissions);
            /* permissions.value = permissions.value.map(permission => {
            const rolePermission = props.rolePermissions?.permissions.find(item => item.name === permission.name)
            if (rolePermission) {
                return {
                ...permission,
                ...rolePermission,
                }
            }

            return permission
            }) */
        }
    })

    const onSubmit = async () => {
        const rolePermissions = {
            name: name.value,
            permissions: permissions.value,
        }
        console.log('permiso para mandar a guardar: ', rolePermissions);
        try {
            if (!props.dato) {
                const { data, error } = await useApi(`/${props.endpoint}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(rolePermissions),
                });


                Swal.fire({
                    title: '¡Éxito!',
                    text: 'El rol se ha agregado correctamente.',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    buttonsStyling: false, // Desactiva los estilos predeterminados
                    customClass: {
                        confirmButton: 'custom-ok-button'
                    }
                });

                emit('refreshTable'); // Actualiza la tabla

            } else {
                const { data, error } = await useApi(`/${props.endpoint}/${props.dato.id}`, {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(rolePermissions),
                });


                Swal.fire({
                    title: '¡Éxito!',
                    text: 'El rol se ha actualizado correctamente.',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    buttonsStyling: false, // Desactiva los estilos predeterminados
                    customClass: {
                        confirmButton: 'custom-ok-button'
                    }
                });

                emit('refreshTable'); // Actualiza la tabla
            }
        } catch (error) {
            Swal.fire({
                title: 'Error',
                text: 'Hubo un problema al agregar el rol.',
                icon: 'error',
                confirmButtonText: 'Intentar de nuevo',
            });
        } finally{
            emit('update:rolePermissions', rolePermissions)
            emit('update:isDialogVisible', false)
            isSelectAll.value = false
            refPermissionForm.value?.reset()
        }
        /* emit('update:rolePermissions', rolePermissions)
        emit('update:isDialogVisible', false)
        isSelectAll.value = false
        refPermissionForm.value?.reset() */
    }

    const onReset = () => {
        emit('update:isDialogVisible', false)
        isSelectAll.value = false
        refPermissionForm.value?.reset()
    }

    watch(() => props.dato, (newDato) => {
        console.log('dato: ', newDato?.permissions);
        name.value = newDato?.name || ''

        permissions.value = permissions.value.map( modulo => ({
            ...modulo,
            permisos: modulo.permisos.map( permiso => {
                const rolePermission = newDato?.permissions.find(item => item.id === permiso.id)
                console.log('permission: ', permiso);
                console.log('se encontro el rol: ', rolePermission);

                if (rolePermission) {

                   permiso.selected = true;
                }

                return permiso
            })
        }));

    }, { immediate: true }) // `immediate: true` para actualizar al inicio

    const fetchPermisos = async () => {
        //isSelectAll.value = false
        try {
            const { data } = await useApi(`/modulos`);

            permissions.value = data.value.data.map( modulo => ({
                ...modulo,
                permisos: modulo.permisos.map( permiso => ({
                    ...permiso,
                    selected: false
                }))
            }));

            console.log('permisos:', permissions)
        } catch (error) {
            console.error("Error al cargar la configuración de la tabla:", error);
        }
    };

    // Llamar `fetchInitTabla` una vez al montar el componente
    onMounted(async () => {await fetchPermisos();});

</script>

<template>
    <VDialog
        :width="$vuetify.display.smAndDown ? 'auto' : 900"
        :model-value="props.isDialogVisible"
        @update:model-value="onReset"
    >
        <!-- 👉 Dialog close btn -->
        <DialogCloseBtn @click="onReset" />

        <VCard class="pa-sm-10 pa-2">
            <VCardText>
                <!-- 👉 Title -->
                <h4 class="text-h4 text-center mb-2">{{ props.dato ? 'Editar' : 'Agregar Nuevo' }} Rol</h4>

                <p class="text-body-1 text-center mb-6">Establecer Permisos de Rol</p>

                <!-- 👉 Form -->
                <VForm ref="refPermissionForm">
                    <!-- 👉 Role name -->
                    <AppTextField
                        v-model="name"
                        label="Nombre de Rol"
                        placeholder="Rol"
                    />

                    <h5 class="text-h5 my-6">Permisos de Rol</h5>

                    <!-- 👉 Role Permissions -->

                    <VTable class="permission-table text-no-wrap mb-6">
                        <!-- 👉 Admin  -->
                        <tr>
                            <td>
                                <h6 class="text-h6">
                                    Administrator Access
                                </h6>
                            </td>
                            <td colspan="4">
                                <div class="d-flex justify-end">
                                    <VCheckbox
                                        v-model="isSelectAll"
                                        v-model:indeterminate="isIndeterminate"
                                        label="Select All"
                                    />
                                </div>
                            </td>
                        </tr>

                        <!-- 👉 Other permission loop -->
                        <!-- <template
                            v-for="permission in permissions"
                            :key="permission.name"
                        >
                            <tr>
                                <td>
                                    <h6 class="text-h6">
                                        {{ permission.name }}
                                    </h6>
                                </td>
                                <td>
                                    <div class="d-flex justify-end">
                                        <VCheckbox
                                        v-model="permission.read"
                                        label="Read"
                                        />
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-end">
                                        <VCheckbox
                                        v-model="permission.write"
                                        label="Write"
                                        />
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-end">
                                        <VCheckbox
                                        v-model="permission.create"
                                        label="Create"
                                        />
                                    </div>
                                </td>
                            </tr>
                        </template> -->

                        <!-- 👉 Other permission loop -->
                        <template
                            v-for="modulo in permissions"

                            :key="modulo.name"
                        >
                            <tr v-if="modulo.submodulos.length == 0">
                                <td>
                                    <h6 class="text-h6">
                                        {{ modulo.name }}
                                    </h6>
                                </td>
                                <td
                                    v-for="permission in modulo.permisos"
                                    :key="permission.id"
                                >
                                    <div class="d-flex justify-end">
                                        <VCheckbox
                                        v-model="permission.selected"
                                        :label="permission.slug"
                                        />
                                    </div>
                                </td>
                                <!-- <td>
                                    <div class="d-flex justify-end">
                                        <VCheckbox
                                        v-model="permission.read"
                                        label="Read"
                                        />
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-end">
                                        <VCheckbox
                                        v-model="permission.write"
                                        label="Write"
                                        />
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-end">
                                        <VCheckbox
                                        v-model="permission.create"
                                        label="Create"
                                        />
                                    </div>
                                </td> -->
                            </tr>
                        </template>



                    </VTable>

                    <!-- 👉 Actions button -->
                    <div class="d-flex align-center justify-center gap-4">
                        <VBtn @click="onSubmit">
                            Submit
                        </VBtn>

                        <VBtn
                            color="secondary"
                            variant="tonal"
                            @click="onReset"
                        >
                            Cancel
                        </VBtn>
                    </div>
                </VForm>
            </VCardText>
        </VCard>
    </VDialog>
</template>

<style lang="scss">
    .permission-table {
        td {
            border-block-end: 1px solid rgba(var(--v-border-color), var(--v-border-opacity));
            padding-block: 0.5rem;

            .v-checkbox {
            min-inline-size: 4.75rem;
            }

            &:not(:first-child) {
            padding-inline: 0.5rem;
            }

            .v-label {
            white-space: nowrap;
            }
        }
    }
</style>
