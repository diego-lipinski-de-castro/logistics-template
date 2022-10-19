<template>
  <admin-layout title="Profile">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Profile
      </h2>
    </template>

    <div>
      <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
        <div>
          <jet-form-section>
            <template #title>
              Cidades
            </template>

            <template #description>
              Lista de cidades vinculadas à sua conta.
            </template>

            <template #form>
              <div class="col-span-6 sm:col-span-4">
                <template
                  v-for="(city, i) in $page.props.cities"
                  :key="i"
                >
                  <span>{{ city.name }}</span><template v-if="i < $page.props.cities.length-1">
                    ,&nbsp;
                  </template>
                </template>
              </div>
            </template>
          </jet-form-section>

          <jet-section-border />
        </div>

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
    </div>
  </admin-layout>
</template>

<script>
import { defineComponent } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import DeleteUserForm from "@/Pages/Admin/Profile/Partials/DeleteUserForm.vue";
import JetSectionBorder from "@/Jetstream/SectionBorder.vue";
import TwoFactorAuthenticationForm from "@/Pages/Admin/Profile/Partials/TwoFactorAuthenticationForm.vue";
import UpdatePasswordForm from "@/Pages/Admin/Profile/Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "@/Pages/Admin/Profile/Partials/UpdateProfileInformationForm.vue";
import JetFormSection from '@/Jetstream/FormSection.vue'

export default defineComponent({

  components: {
    AdminLayout,
    DeleteUserForm,
    JetSectionBorder,
    TwoFactorAuthenticationForm,
    UpdatePasswordForm,
    UpdateProfileInformationForm,
    JetFormSection,
  },
  props: {
    cities: {
      type: Array,
      default: () => [],
    }
  },
});
</script>
