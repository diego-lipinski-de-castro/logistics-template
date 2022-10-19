<template>
  <Link :href="'/'">
    <jet-application-logo class="block h-16 w-auto" />
  </Link>
</template>

<script>
    import { defineComponent } from 'vue'
    import { Link } from '@inertiajs/inertia-vue3';
    import JetApplicationLogo from '@/Jetstream/ApplicationLogo.vue'

    export default defineComponent({
        components: {
            Link,
            JetApplicationLogo,
        },
    })
</script>
