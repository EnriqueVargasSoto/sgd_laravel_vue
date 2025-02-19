<!-- ❗Errors in the form are set on line 60 -->
<script setup>
    import { VForm } from 'vuetify/components/VForm'

    import { useGenerateImageVariant } from '@core/composable/useGenerateImageVariant'
    import authV2LoginIllustrationBorderedDark from '@images/pages/auth-v2-login-illustration-bordered-dark.png'
    import authV2LoginIllustrationBorderedLight from '@images/pages/auth-v2-login-illustration-bordered-light.png'
    import authV2LoginIllustrationDark from '@images/pages/auth-v2-login-illustration-dark.png'
    import authV2LoginIllustrationLight from '@images/pages/auth-v2-login-illustration-light.png'
    import authV2MaskDark from '@images/pages/misc-mask-dark.png'
    import authV2MaskLight from '@images/pages/misc-mask-light.png'
    import { VNodeRenderer } from '@layouts/components/VNodeRenderer'
    import { themeConfig } from '@themeConfig'

    const authThemeImg = useGenerateImageVariant(authV2LoginIllustrationLight, authV2LoginIllustrationDark, authV2LoginIllustrationBorderedLight, authV2LoginIllustrationBorderedDark, true)
    const authThemeMask = useGenerateImageVariant(authV2MaskLight, authV2MaskDark)

    definePage({
        meta: {
            layout: 'blank',
            unauthenticatedOnly: true,
        },
    })

    const isPasswordVisible = ref(false)
    const route = useRoute()
    const router = useRouter()
    const ability = useAbility()

    const errors = ref({
        email: undefined,
        password: undefined,
    })

    const refVForm = ref()

    const credentials = ref({
        email: 'admin@demo.com',
        password: 'admin',
    })

    const rememberMe = ref(false)

    const login = async () => {
        try {
            const res = await $api('/login', {
                method: 'POST',
                body: {
                    email: credentials.value.email,
                    password: credentials.value.password,
                },
                onResponseError({ response }) {
                    console.log('respuesta del login: ',response);
                    errors.value.email = response._data.message
                },
            })

            console.log('res del login: ',res.value);

            //const { accessToken, userData, userAbilityRules } = res

            const userAbilityRules = [
                {
                action: 'manage',
                subject: 'all',
                },
            ];

            const user =
                {
                    "name" : 'admin',
                "id": 1,
                "persona_id": 1,
                "host": null,
                "mac": null,
                "ip": null,
                "email": "admin@gmail.com",
                "email_verified_at": null,
                "status": 1,
                "created_at": "2025-02-18T21:13:22.000000Z",
                "updated_at": "2025-02-18T21:13:22.000000Z",
                "deleted_at": null,
                "roles": [
                    {
                        "id": 1,
                        "name": "Admin",
                        "guard_name": "web",
                        "description": null,
                        "created_at": "2025-02-18T21:13:22.000000Z",
                        "updated_at": "2025-02-18T21:13:22.000000Z",
                        "deleted_at": null,
                        "pivot": {
                            "model_type": "App\\Models\\User",
                            "model_id": 1,
                            "role_id": 1
                        },

                    }
                ]
            };


            useCookie('userAbilityRules').value = userAbilityRules//res.user//
            ability.update(userAbilityRules/*res.user*/)
            useCookie('userData').value = res.user//userData
            useCookie('accessToken').value = res.token//accessToken
            useCookie('accessMenu').value = res.menu//accessToken

            localStorage.setItem('menu', JSON.stringify(res.menu));
            await nextTick(async() => {
                console.log('query: ', route.query.to);
                //router.replace(route.query.to ? String(route.query.to) : '/')
                const ruta = getFirstChildRoute(res.menu);
                console.log('ruta: ', ruta);
                await router.push({ name: ruta});
            })
        } catch (err) {
            console.error(err)
        }
    }

    const onSubmit = () => {
    refVForm.value?.validate().then(({ valid: isValid }) => {
        if (isValid)
        login()
    })
    }

    const getFirstChildRoute = (menu) => {
        // Función recursiva para obtener el primer hijo con ruta en el último nivel
        const findDeepestRoute = (modulo) => {
            if (modulo.submodulos && modulo.submodulos.length > 0) {
                return findDeepestRoute(modulo.submodulos[0]); // Explora el primer submódulo
            }
            return modulo.ruta; // Retorna la ruta cuando ya no tiene más hijos
        };

        // Toma el primer módulo del menú
        if (menu.length > 0) {
            return findDeepestRoute(menu[0]);
        }
        return null; // Si el menú está vacío, retorna null
    };
</script>

<template>
    <RouterLink to="/">
        <div class="auth-logo d-flex align-center gap-x-3">
            <VNodeRenderer :nodes="themeConfig.app.logo" />
            <h1 class="auth-title">
                Tramite Documentario
            </h1>
        </div>
    </RouterLink>

    <VRow
        no-gutters
        class="auth-wrapper bg-surface"
    >
        <VCol
            md="8"
            class="d-none d-md-flex"
        >
            <div class="position-relative bg-background w-100 me-0">
                <div
                    class="d-flex align-center justify-center w-100 h-100"
                    style="padding-inline: 6.25rem;"
                >
                    <VImg
                        max-width="613"
                        :src="authThemeImg"
                        class="auth-illustration mt-16 mb-2"
                    />
                </div>

                <img
                    class="auth-footer-mask"
                    :src="authThemeMask"
                    alt="auth-footer-mask"
                    height="280"
                    width="100"
                >
            </div>
        </VCol>

        <VCol
            cols="12"
            md="4"
            class="auth-card-v2 d-flex align-center justify-center"
        >
            <VCard
                flat
                :max-width="500"
                class="mt-12 mt-sm-0 pa-4"
            >
                <VCardText>
                    <h4 class="text-h4 mb-1">
                        Welcome to <span class="text-capitalize"> Sistema de Tramite Documentario </span>! 👋🏻
                    </h4>
                    <p class="mb-0">Inicie sesión en su cuenta y comience la aventura.</p>
                </VCardText>
                <!-- <VCardText>
                    <VAlert
                        color="primary"
                        variant="tonal"
                    >
                        <p class="text-sm mb-2">Admin Email: <strong>admin@demo.com</strong> / Pass: <strong>admin</strong></p>
                        <p class="text-sm mb-0">Client Email: <strong>client@demo.com</strong> / Pass: <strong>client</strong></p>
                    </VAlert>
                </VCardText> -->
                <VCardText>
                    <VForm
                        ref="refVForm"
                        @submit.prevent="onSubmit"
                    >
                        <VRow>
                            <!-- email -->
                            <VCol cols="12">
                                <AppTextField
                                    v-model="credentials.email"
                                    label="Email"
                                    placeholder="johndoe@email.com"
                                    type="email"
                                    autofocus
                                    :rules="[requiredValidator, emailValidator]"
                                    :error-messages="errors.email"
                                />
                            </VCol>

                            <!-- password -->
                            <VCol cols="12">
                                <AppTextField
                                    v-model="credentials.password"
                                    label="Password"
                                    placeholder="············"
                                    :rules="[requiredValidator]"
                                    :type="isPasswordVisible ? 'text' : 'password'"
                                    autocomplete="password"
                                    :error-messages="errors.password"
                                    :append-inner-icon="isPasswordVisible ? 'tabler-eye-off' : 'tabler-eye'"
                                    @click:append-inner="isPasswordVisible = !isPasswordVisible"
                                />

                                <div class="d-flex align-center flex-wrap justify-space-between my-6">
                                    <VCheckbox
                                        v-model="rememberMe"
                                        label="Recuerdame"
                                    />
                                <!--  <RouterLink
                                    class="text-primary ms-2 mb-1"
                                    :to="{ name: 'forgot-password' }"
                                >
                                    Forgot Password?
                                </RouterLink> -->
                                </div>

                                <VBtn
                                    block
                                    type="submit"
                                >
                                    Login
                                </VBtn>
                            </VCol>

                        </VRow>
                    </VForm>
                </VCardText>
            </VCard>
        </VCol>
    </VRow>
</template>

<style lang="scss">
@use "@core-scss/template/pages/page-auth";
</style>
