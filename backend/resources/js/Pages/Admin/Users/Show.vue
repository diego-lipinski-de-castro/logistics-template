<template>
  <admin-layout title="Lojas">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Lojas
        </h2>

        <Link
          :href="route('admin.users.index')"
          class="
            ml-2
            inline-flex
            items-center
            px-4
            py-2
            bg-speedapp-gray-500
            border border-transparent
            rounded-md
            font-semibold
            text-xs text-white
            uppercase
            tracking-widest
            hover:bg-speedapp-gray-300
            active:bg-speedapp-gray-900
            focus:outline-none
            transition
            ease-in-out
            duration-150
          "
        >
          Voltar
        </Link>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex flex-col">
          <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div
              class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8"
            >
              <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:p-0">
                  <dl class="sm:divide-y sm:divide-gray-200">
                    <div
                      class="
                        py-4
                        sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6
                      "
                    >
                      <dt class="text-sm font-medium text-gray-500">
                        Nome
                      </dt>
                      <dd
                        class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                      >
                        {{ user.name }}
                      </dd>
                    </div>
                    <div
                      class="
                        py-4
                        sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6
                      "
                    >
                      <dt class="text-sm font-medium text-gray-500">
                        Criado às
                      </dt>
                      <dd
                        class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                      >
                        {{ user.formatted_created_at }}
                      </dd>
                    </div>
                    <div
                      class="
                        py-4
                        sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6
                      "
                    >
                      <dt class="text-sm font-medium text-gray-500">
                        Cidades
                      </dt>
                      <dd
                        class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                      >
                        <template
                          v-for="(city, i) in user.cities"
                          :key="i"
                        >
                          <span>{{ city.name }}</span><template v-if="i < user.cities.length-1">
                            ,&nbsp;
                          </template>
                        </template>
                      </dd>
                    </div>
                  </dl>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </admin-layout>
</template>

<script>
import { defineComponent } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetFormSection from "@/Jetstream/FormSection.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import { Link } from "@inertiajs/inertia-vue3";

export default defineComponent({
  components: {
    AdminLayout,
    JetActionMessage,
    JetButton,
    JetFormSection,
    JetInput,
    JetInputError,
    JetLabel,
    JetSecondaryButton,
    Link,
  },

  props: {
    user: {
      type: Object,
              required: true
    },
  },
});
</script>
