import { useTheme } from 'vuetify'
import { useConfigStore } from '@core/stores/config'

// composable function to return the image variant as per the current theme and skin
export const useGenerateImageVariant = (imgLight, imgDark, imgLightBordered, imgDarkBordered, bordered = false) => {
  const configStore = useConfigStore()
  const { global } = useTheme()

  return computed(() => {

    console.log('global', global.name.value);
    if (global.name.value === 'light') {
      if (configStore.skin === 'bordered' && bordered)
      {
        console.log('imgLightBordered', imgLightBordered);
        return imgLightBordered
      }
      else {
        console.log('imgLight', imgLight);
        return imgLight
      }

    }
    if (global.name.value === 'dark') {
      if (configStore.skin === 'bordered' && bordered)
      {
        console.log('imgDarkBordered', imgDarkBordered);
        return imgDarkBordered
      }
      else
        {
            console.log('imgDark', imgDark);
            return imgDark
        }
    }

    // Add a default return statement
    return imgLight
  })
}
