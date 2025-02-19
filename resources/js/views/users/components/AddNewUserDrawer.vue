<script setup>
    import { PerfectScrollbar } from 'vue3-perfect-scrollbar'
    import Swal from 'sweetalert2';
import { ref } from 'vue';

    const props = defineProps({
        endpoint: String, // Ruta API
            isDialogVisible: {
                type: Boolean,
                required: true,
            },
            dato: {
                type: Object,
                default: () => ({}),
            },
        })

        const emit = defineEmits([
            'update:isDialogVisible',
            'userData',
        ])

        const usuario = ref({
            //datos persona
            unidad_organica_id: null,
            nombres: null,
            apellidos: null,
            tipo_documento_identidad_id: null,
            numero_documento: null,
            edad: null,
            telefono: null,
            direccion: null,
            //datos usuario
            email: null,
            host: null,
            mac: null,
            ip: null,
            password: null,
        })

        const unidades_organicas = ref([])
        const tipos_documento_identidad = ref([])

    const isFormValid = ref(false)
    const refForm = ref()
    const fullName = ref('')
    const name = ref('')
    const lastname = ref('')
    const rol_id = ref()
    const userName = ref('')
    const email = ref('')
    const company = ref('')
    const country = ref()
    const contact = ref('')
    const role = ref()
    const plan = ref()
    const status = ref()

    const password = ref();
    const isPasswordVisible = ref(false)

    const roles = ref([]);

    // 👉 drawer close
    const closeNavigationDrawer = () => {
        emit('update:isDialogVisible', false)
        nextTick(() => {
            refForm.value?.reset()
            refForm.value?.resetValidation()
        })
    }

    const onSubmit = async() => {
        refForm.value?.validate().then(async({ valid }) => {
            const dataForm = {
                name: name.value,
                lastname: lastname.value,
                email: email.value,
                rol_id: rol_id.value,
                password: password.value
            };
            console.log('data: ', dataForm);

            try {
                if (!props.dato) {
                    const { data, error } = await useApi(`/${props.endpoint}`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({
                            name: name.value,
                            lastname: lastname.value,
                            email: email.value,
                            rol_id: rol_id.value,
                            password: password.value
                        }),
                    });


                    Swal.fire({
                        title: '¡Éxito!',
                        text: 'El permiso se ha agregado correctamente.',
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
                        body: JSON.stringify({
                            name: name.value,
                            lastname: lastname.value,
                            email: email.value,
                            rol_id: rol_id.value,
                            password: password.value
                        }),
                    });


                    Swal.fire({
                        title: '¡Éxito!',
                        text: 'El permiso se ha actualizado correctamente.',
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
                    text: 'Hubo un problema al agregar el permiso.',
                    icon: 'error',
                    confirmButtonText: 'Intentar de nuevo',
                });
            } finally{
                /* emit('refreshTable');
                emit('update:isDialogVisible', false)
                emit('update:permissionName', '') */
                emit('userData', {
                    id: 0,
                    fullName: fullName.value,
                    company: company.value,
                    role: role.value,
                    country: country.value,
                    contact: contact.value,
                    email: email.value,
                    currentPlan: plan.value,
                    status: status.value,
                    avatar: '',
                    billing: 'Auto Debit',
                })
                emit('update:isDialogVisible', false)
                nextTick(() => {
                    refForm.value?.reset()
                    refForm.value?.resetValidation()
                })
            }

            /* if (valid) {
                emit('userData', {
                    id: 0,
                    fullName: fullName.value,
                    company: company.value,
                    role: role.value,
                    country: country.value,
                    contact: contact.value,
                    email: email.value,
                    currentPlan: plan.value,
                    status: status.value,
                    avatar: '',
                    billing: 'Auto Debit',
                })
                emit('update:isDialogVisible', false)
                nextTick(() => {
                    refForm.value?.reset()
                    refForm.value?.resetValidation()
                })
            } */
        })
    }

    const handleDrawerModelValueUpdate = val => {
        emit('update:isDialogVisible', val)
    }

    watch(() => props.dato, (newDato) => {

        console.log(newDato);
        name.value = newDato?.name || ''
        lastname.value = newDato?.lastname || ''
        email.value = newDato?.email || ''
        rol_id.value = (newDato?.roles && newDato.roles.length > 0) ? newDato.roles[0].id : null;//newDato?.roles[0].id || null
    }, { immediate: true }) // `immediate: true` para actualizar al inicio

    const fetchRoles = async () => {
        //isSelectAll.value = false
        try {
            const { data } = await useApi(`/roles`);

            roles.value = data.value.data;

            console.log('roles:', roles.value[0])
        } catch (error) {
            console.error("Error al cargar la configuración de la tabla:", error);
        }
    };

    const fetchUnidadesOrganicas = async () => {
        //isSelectAll.value = false
        try {
            const { data } = await useApi(`/unidades-organicas`);

            unidades_organicas.value = data.value.data;


        } catch (error) {
            console.error("Error al cargar la configuración de la tabla:", error);
        }
    };

    const fetchTiposDocumentoIdentidad = async () => {
        //isSelectAll.value = false
        try {
            const { data } = await useApi(`/tipos-documentos-identidad`);

            tipos_documento_identidad.value = data.value.data;


        } catch (error) {
            console.error("Error al cargar la configuración de la tabla:", error);
        }
    };

    // Llamar `fetchInitTabla` una vez al montar el componente
    onMounted(async () => {await fetchRoles(); await fetchUnidadesOrganicas(); await fetchTiposDocumentoIdentidad();});
</script>

<template>
    <VNavigationDrawer
        data-allow-mismatch
        temporary
        :width="400"
        location="end"
        class="scrollable-content"
        :model-value="props.isDialogVisible"
        @update:model-value="handleDrawerModelValueUpdate"
    >
        <!-- 👉 Title -->
        <AppDrawerHeaderSection
            :title="props.dato ? 'Editar Usuario' : 'Agregar Nuevo Usuario'"
            @cancel="closeNavigationDrawer"
        />

        <VDivider />

        <PerfectScrollbar :options="{ wheelPropagation: false }">
            <VCard flat>
                <VCardText>
                    <!-- 👉 Form -->
                    <VForm
                        ref="refForm"
                        v-model="isFormValid"
                        @submit.prevent="onSubmit"
                    >
                        <VRow>

                            <!-- 👉 Full name -->
                            <VCol cols="12">
                                <AppTextField
                                    v-model="usuario.apellidos"
                                    :rules="[requiredValidator]"
                                    label="Apellidos"
                                    placeholder="Apellidos"
                                />
                            </VCol>

                            <!-- 👉 Full name -->
                            <VCol cols="12">
                                <AppTextField
                                    v-model="usuario.nombres"
                                    :rules="[requiredValidator]"
                                    label="Nombres"
                                    placeholder="Nombres"
                                />
                            </VCol>

                            <!-- 👉 Full name -->
                            <VCol cols="12">
                                <AppSelect
                                    v-model="usuario.tipo_documento_identidad_id"
                                    :items="tipos_documento_identidad"
                                    :item-title="item => `${item.slug} - ${item.tipo}`"
                                    item-value="id"
                                    label="Tipo de Documento de Identidad"
                                    placeholder="Tipo de Documento de Identidad"
                                    :rules="[requiredValidator]"
                                />
                            </VCol>

                            <!-- 👉 Full name -->
                            <VCol cols="12">
                                <AppTextField
                                    v-model="usuario.numero_documento"
                                    :rules="[requiredValidator]"
                                    label="Numero de Documento"
                                    placeholder="Numero de Documento"
                                />
                            </VCol>


                            <!-- 👉 Full name -->
                            <VCol cols="12">
                                <AppSelect
                                    v-model="usuario.unidad_organica_id"
                                    :items="unidades_organicas"
                                    item-title="nombre"
                                    item-value="id"
                                    label="Unidad Organica"
                                    placeholder="Unidad Organica"
                                    :rules="[requiredValidator]"
                                />
                            </VCol>
                            <!-- 👉 Username -->
                            <!-- <VCol cols="12">
                                <AppTextField
                                    v-model="userName"
                                    :rules="[requiredValidator]"
                                    label="Email"
                                    placeholder="Email"
                                />
                            </VCol> -->

                            <!-- 👉 Email -->
                            <VCol cols="12">
                                <AppTextField
                                    v-model="email"
                                    :rules="[requiredValidator, emailValidator]"
                                    label="Email"
                                    placeholder="johndoe@email.com"
                                />
                            </VCol>

                            <!-- 👉 company -->
                            <!-- <VCol cols="12">
                                <AppTextField
                                    v-model="company"
                                    :rules="[requiredValidator]"
                                    label="Company"
                                    placeholder="PixInvent"
                                />
                            </VCol> -->

                            <!-- 👉 Country -->
                            <VCol cols="12">
                                <AppSelect
                                    v-model="rol_id"
                                    label="Rol"
                                    placeholder="Rol"
                                    :rules="[requiredValidator]"
                                    :items="roles"
                                    item-title="name"
                                    item-value="id"
                                />
                            </VCol>

                            <VCol cols="12">
                                <AppTextField
                                    v-model="password"
                                    label="Password"
                                    placeholder="············"
                                    :rules="[requiredValidator]"
                                    :type="isPasswordVisible ? 'text' : 'password'"


                                    :append-inner-icon="isPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                                    @click:append-inner="isPasswordVisible = !isPasswordVisible"
                                />
                            </VCol>


                            <!-- 👉 Contact -->
                            <!-- <VCol cols="12">
                                <AppTextField
                                    v-model="contact"
                                    type="number"
                                    :rules="[requiredValidator]"
                                    label="Contact"
                                    placeholder="+1-541-754-3010"
                                />
                            </VCol> -->

                            <!-- 👉 Role -->
                            <!-- <VCol cols="12">
                                <AppSelect
                                    v-model="role"
                                    label="Select Role"
                                    placeholder="Select Role"
                                    :rules="[requiredValidator]"
                                    :items="['Admin', 'Author', 'Editor', 'Maintainer', 'Subscriber']"
                                />
                            </VCol> -->

                            <!-- 👉 Plan -->
                            <!-- <VCol cols="12">
                                <AppSelect
                                    v-model="plan"
                                    label="Select Plan"
                                    placeholder="Select Plan"
                                    :rules="[requiredValidator]"
                                    :items="['Basic', 'Company', 'Enterprise', 'Team']"
                                />
                            </VCol> -->

                            <!-- 👉 Status -->
                            <!-- <VCol cols="12">
                                <AppSelect
                                    v-model="status"
                                    label="Select Status"
                                    placeholder="Select Status"
                                    :rules="[requiredValidator]"
                                    :items="[{ title: 'Active', value: 'active' }, { title: 'Inactive', value: 'inactive' }, { title: 'Pending', value: 'pending' }]"
                                />
                            </VCol> -->

                            <!-- 👉 Submit and Cancel -->
                            <VCol cols="12">
                                <VBtn
                                    type="submit"
                                    class="me-3"
                                >
                                    {{ props.dato ? 'Actualizar' : 'Agregar' }}
                                </VBtn>
                                <VBtn
                                    type="reset"
                                    variant="tonal"
                                    color="error"
                                    @click="closeNavigationDrawer"
                                >
                                    Cancelar
                                </VBtn>
                            </VCol>
                        </VRow>
                    </VForm>
                </VCardText>
            </VCard>
        </PerfectScrollbar>
    </VNavigationDrawer>
</template>
<style lang="scss">
    .permission-table {
        td {
            border-block-end: 1px solid rgba(var(--v-border-color), var(--v-border-opacity));
            padding-block: 0.5rem;
            padding-inline: 0;
        }
    }

    .custom-ok-button {
        background-color: #28a745 !important; /* Verde éxito */
        color: white !important;
        font-weight: bold !important;
        padding: 10px 20px !important;
        border-radius: 5px !important;
        border: none !important;
    }
</style>
