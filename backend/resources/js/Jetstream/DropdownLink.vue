<template>
  <div>
    <button
      v-if="as == 'button'"
      type="submit"
      class="block w-full px-4 py-2 text-sm leading-5 text-gray-700 text-left hover:bg-gray-100 focus:outline-none transition"
    >
      <slot />
    </button>

    <a
      v-else-if="as =='a'"
      :href="href"
      class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none transition"
      target="_blank"
    >
      <slot />
    </a>

    <Link
      v-else
      :href="href"
      class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none transition"
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
          href: {
            type: String,
            required: false,
            default: () => '#'
          },
          as: {
            type: String,
            required: false,
            default: () => null
          }
        }
    })
</script>
