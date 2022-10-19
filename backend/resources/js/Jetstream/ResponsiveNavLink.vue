<template>
  <div>
    <button
      v-if="as == 'button'"
      :class="classes"
      class="w-full text-left"
    >
      <slot />
    </button>

    <Link
      v-else
      :href="href"
      :class="classes"
    >
      <slot />
    </Link>
  </div>
</template>

<script>
    import { defineComponent } from 'vue';
    import { Link } from '@inertiajs/inertia-vue3';

    export default defineComponent({
        components: {
            Link,
        },

        props: {
          active: {
            type: Boolean,
            required: false,
            default: () => false,
          },
          href: {
            type: String,
            required: false,
            default: () => '#',
          },
          as: {
            type: String,
            required: false,
            default: () => null,
          }
        },

        computed: {
            classes() {
                return this.active
                    ? 'block pl-3 pr-4 py-2 border-l-4 border-speedapp-orange-400 text-base font-medium text-speedapp-orange-700 bg-speedapp-orange-50 focus:outline-none focus:text-speedapp-orange-800 focus:bg-speedapp-orange-100 focus:border-speedapp-orange-700 transition'
                    : 'block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition'
            }
        }
    })
</script>
