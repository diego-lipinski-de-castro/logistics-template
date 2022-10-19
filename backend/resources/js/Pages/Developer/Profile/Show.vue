<template>
  <developer-layout title="Profile">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800  leading-tight">
        Profile
      </h2>
    </template>

    <div class="py-12">
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
  </developer-layout>
</template>

<script>
import { defineComponent } from "vue";
import DeveloperLayout from "@/Layouts/DeveloperLayout.vue";
import DeleteUserForm from "@/Pages/Developer/Profile/Partials/DeleteUserForm.vue";
import JetSectionBorder from "@/Jetstream/SectionBorder.vue";
import TwoFactorAuthenticationForm from "@/Pages/Developer/Profile/Partials/TwoFactorAuthenticationForm.vue";
import UpdatePasswordForm from "@/Pages/Developer/Profile/Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "@/Pages/Developer/Profile/Partials/UpdateProfileInformationForm.vue";

export default defineComponent({

    components: {
        DeveloperLayout,
        DeleteUserForm,
        JetSectionBorder,
        TwoFactorAuthenticationForm,
        UpdatePasswordForm,
        UpdateProfileInformationForm,
    },
});
</script>
