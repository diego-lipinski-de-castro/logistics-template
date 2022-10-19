<template>
  <input
    v-model="proxyChecked"
    type="checkbox"
    :value="value"
    class="rounded border-gray-300 text-speedapp-orange-600 shadow-sm focus:border-speedapp-orange-300 focus:ring focus:ring-speedapp-orange-200 focus:ring-opacity-50"
  >
</template>

<script>
import { defineComponent } from 'vue'

export default defineComponent({

    props: {
        checked: {
            type: [Array, Boolean],
            default: false,
        },
        // eslint-disable-next-line vue/require-prop-types
        value: {
            default: () => null,
        },
    },
    emits: ['update:checked'],

    computed: {
        proxyChecked: {
            get() {
                return this.checked;
            },

            set(val) {
                this.$emit("update:checked", val);
            },
        },
    },
})
</script>
