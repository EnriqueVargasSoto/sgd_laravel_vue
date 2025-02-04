<template>
    <!-- <div id="app">
      <Header />
      <div class="main-content">
        <Sidebar />
        <div class="content">
          <router-view></router-view>
        </div>
      </div>
      <Footer />
    </div> -->
    <VLocaleProvider :rtl="configStore.isAppRTL">
        <!-- ℹ️ This is required to set the background color of active nav link based on currently active global theme's primary -->
        <VApp :style="`--v-global-theme-primary: ${hexToRgb(global.current.value.colors.primary)}`">
            <Component
                v-bind="layoutAttrs"
                :is="configStore.appContentLayoutNav === AppContentLayoutNav.Vertical ? DefaultLayoutWithVerticalNav : DefaultLayoutWithHorizontalNav"
            >
                <AppLoadingIndicator ref="refLoadingIndicator" />
                <RouterView v-slot="{ Component }">
                    <Suspense
                        :timeout="0"
                        @fallback="isFallbackStateActive = true"
                        @resolve="isFallbackStateActive = false"
                    >
                        <Component :is="Component" />
                    </Suspense>
                </RouterView>
                <ScrollToTop />
            </Component>

        </VApp>
    </VLocaleProvider>
  </template>

  <script setup>
    import { useTheme } from 'vuetify'
    import ScrollToTop from '@core/components/ScrollToTop.vue'
    import initCore from '@core/initCore'
    import {
    initConfigStore,
    useConfigStore,
    } from '@core/stores/config'
    import { hexToRgb } from '@core/utils/colorConverter'


    const { global } = useTheme()

    // ℹ️ Sync current theme with initial loader theme
    initCore()
    initConfigStore()

    const configStore = useConfigStore()

    /* import { useConfigStore } from '@core/stores/config' */
import { AppContentLayoutNav } from '@layouts/enums'
import { switchToVerticalNavOnLtOverlayNavBreakpoint } from '@layouts/utils'

const DefaultLayoutWithHorizontalNav = defineAsyncComponent(() => import('./layouts/components/DefaultLayoutWithHorizontalNav.vue'))
const DefaultLayoutWithVerticalNav = defineAsyncComponent(() => import('./layouts/components/DefaultLayoutWithVerticalNav.vue'))
/* const configStore = useConfigStore() */

// ℹ️ This will switch to vertical nav when define breakpoint is reached when in horizontal nav layout

// Remove below composable usage if you are not using horizontal nav layout in your app
switchToVerticalNavOnLtOverlayNavBreakpoint()

const { layoutAttrs, injectSkinClasses } = useSkins()

injectSkinClasses()

// SECTION: Loading Indicator
const isFallbackStateActive = ref(false)
const refLoadingIndicator = ref(null)

watch([
  isFallbackStateActive,
  refLoadingIndicator,
], () => {
  if (isFallbackStateActive.value && refLoadingIndicator.value)
    refLoadingIndicator.value.fallbackHandle()
  if (!isFallbackStateActive.value && refLoadingIndicator.value)
    refLoadingIndicator.value.resolveHandle()
}, { immediate: true })
// !SECTION
  </script>

<style lang="scss">
// As we are using `layouts` plugin we need its styles to be imported
@use "./@layouts/styles/default-layout";
</style>
