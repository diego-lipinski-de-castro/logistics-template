<template>
  <label class="block font-medium text-sm text-gray-700">
    <span v-if="value">{{ value }}</span>
    <span v-else><slot /></span>
  </label>
</template>

<script>
    import { defineComponent } from 'vue'

    export default defineComponent({
        props: {
          value: {
            type: String,
            required: false,
            default: () => null,
          }
        }
    })
</script>
