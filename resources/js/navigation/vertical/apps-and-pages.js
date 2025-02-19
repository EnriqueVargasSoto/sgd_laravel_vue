// TODO: Get type from backend
const userData = useCookie('userData')
const valor = localStorage.getItem("menu");
console.log('valor', valor);
const menu = JSON.parse(valor);
console.log('valor', menu);
const nuevoMenu = transformMenuData(menu);

function transformMenuData(modules) {
    return modules.map(module => {
        let transformedModule = {
            title: module.nombre,
            icon: module.icono ? { icon: module.icono } : undefined,
        };

        if (module.ruta) {
            transformedModule.to = module.ruta;
        }

        if (module.submodulos && module.submodulos.length > 0) {
            transformedModule.children = module.submodulos.map(sub => transformSubmodule(sub));
        }

        return transformedModule;
    });
}

function transformSubmodule(submodule) {
    let transformedSubmodule = {
        title: submodule.nombre,
    };

    if (submodule.ruta) {
        transformedSubmodule.to = submodule.ruta;
    }

    if (submodule.submodulos && submodule.submodulos.length > 0) {
        transformedSubmodule.children = submodule.submodulos.map(sub => transformSubmodule(sub));
    }

    return transformedSubmodule;
}
console.log('nuevoMenu', nuevoMenu);
export default nuevoMenu;

