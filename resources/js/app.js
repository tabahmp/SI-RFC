import '../css/app.css'
import { createApp, h } from 'vue'
import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { Icon } from '@iconify/vue'

createInertiaApp({
  title: title => `${title} — SI-RFC`,
  resolve: name => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
  setup({ el, App, props, plugin }) {
    const app = createApp({ render: () => h(App, props) })
    app.use(plugin)
    app.component('Icon', Icon)
    app.mount(el)
    return app
  },
  progress: { color: '#0757a8' },
})
