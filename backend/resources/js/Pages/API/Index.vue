<template>
  <user-layout title="API Tokens">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        API Tokens
      </h2>
    </template>

    <div>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <api-token-manager
          :tokens="tokens"
          :available-permissions="availablePermissions"
          :default-permissions="defaultPermissions"
        />
      </div>
    </div>
  </user-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import ApiTokenManager from '@/Pages/API/Partials/ApiTokenManager.vue'
    import UserLayout from '@/Layouts/UserLayout.vue'

    export default defineComponent({

        components: {
            ApiTokenManager,
            UserLayout,
        },
        props: {
          tokens: {
            type: Array,
            default: () => [],
        },
        availablePermissions: {
            type: Array,
            default: () => [],
        },
        defaultPermissions: {
            type: Array,
            default: () => [],
        },
        }
    })
</script>
