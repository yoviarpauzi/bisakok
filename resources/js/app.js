import "./bootstrap";
import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/vue3";
import PrimeVue from "primevue/config";
import Aura from "@primevue/themes/aura";
import "primeicons/primeicons.css";
import { definePreset } from "@primevue/themes";
import Ripple from "primevue/ripple";
import StyleClass from "primevue/styleclass";
import Tooltip from "primevue/tooltip";
import ToastService from "primevue/toastservice";

const myPreset = definePreset(Aura, {
    semantic: {
        primary: {
            50: "{blue.50}",
            100: "{blue.100}",
            200: "{blue.200}",
            300: "{blue.300}",
            400: "{blue.400}",
            500: "{blue.500}",
            600: "{blue.600}",
            700: "{blue.700}",
            800: "{blue.800}",
            900: "{blue.900}",
            950: "{blue.950}",
        },
        colorScheme: {
            light: {
                surface: {
                    0: "#ffffff",
                    50: "{zinc.50}",
                    100: "{zinc.100}",
                    200: "{zinc.200}",
                    300: "{zinc.300}",
                    400: "{zinc.400}",
                    500: "{zinc.500}",
                    600: "{zinc.600}",
                    700: "{zinc.700}",
                    800: "{zinc.800}",
                    900: "{zinc.900}",
                    950: "{zinc.950}",
                },
            },
            dark: {
                surface: {
                    0: "#ffffff",
                    50: "{slate.50}",
                    100: "{slate.100}",
                    200: "{slate.200}",
                    300: "{slate.300}",
                    400: "{slate.400}",
                    500: "{slate.500}",
                    600: "{slate.600}",
                    700: "{slate.700}",
                    800: "{slate.800}",
                    900: "{slate.900}",
                    950: "{slate.950}",
                },
            },
        },
    },
});

createInertiaApp({
    resolve: (name) => {
        const pages = import.meta.glob("./components/**/*.vue", {
            eager: true,
        });
        return pages[`./components/${name}.vue`];
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(PrimeVue, {
                ripple: true,
                inputVariant: "filled",
                zIndex: {
                    modal: 1100, //dialog, drawer
                    overlay: 1000, //select, popover
                    menu: 1000, //overlay menus
                    tooltip: 1100, //tooltip
                },
                theme: {
                    preset: myPreset,
                    options: {
                        prefix: "my",
                        darkModeSelector: ".my-app-dark",
                        cssLayer: {
                            name: "primevue",
                            order: "tailwind-base, primevue, tailwind-utilities",
                        },
                    },
                },
            })
            .use(ToastService)
            .directive("ripple", Ripple)
            .directive("styleclass", StyleClass)
            .directive("tooltip", Tooltip)
            .mount(el);
    },
});
