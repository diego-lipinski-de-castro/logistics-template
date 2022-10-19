<template>
  <user-layout title="Profile">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Profile
      </h2>
    </template>

    <div class="py-10">
      <div v-if="$page.props.jetstream.canUpdateProfileInformation">
        <update-profile-information-form :user="$page.props.authUser" />

        <jet-section-border />
      </div>

      <div v-if="$page.props.jetstream.canUpdatePassword">
        <update-password-form class="mt-10 sm:mt-0" />

        <!-- <jet-section-border /> -->
      </div>

      <div v-if="$page.props.jetstream.canManageTwoFactorAuthentication">
        <two-factor-authentication-form class="mt-10 sm:mt-0" />

        <jet-section-border />
      </div>

      <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
        <jet-section-border />

        <delete-user-form class="mt-10 sm:mt-0" />
      </template>
    </div>
  </user-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import UserLayout from '@/Layouts/UserLayout.vue'
    import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm.vue'
    import JetSectionBorder from '@/Jetstream/SectionBorder.vue'
    import TwoFactorAuthenticationForm from '@/Pages/Profile/Partials/TwoFactorAuthenticationForm.vue'
    import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue'
    import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue'

    export default defineComponent({

        components: {
            UserLayout,
            DeleteUserForm,
            JetSectionBorder,
            TwoFactorAuthenticationForm,
            UpdatePasswordForm,
            UpdateProfileInformationForm,
        },
    })
</script>
