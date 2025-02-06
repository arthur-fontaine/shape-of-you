import { startStimulusApp, registerControllers } from "vite-plugin-symfony/stimulus/helpers"
import { registerSvelteControllerComponents } from "vite-plugin-symfony/stimulus/helpers/svelte"
registerSvelteControllerComponents(import.meta.glob('./**/*.svelte'), '.');

const app = startStimulusApp();
registerControllers(
  app,
  import.meta.glob(
    "../*_controller.js",
    {
      query: "?stimulus",
      eager: true,
    },
  ),
);
