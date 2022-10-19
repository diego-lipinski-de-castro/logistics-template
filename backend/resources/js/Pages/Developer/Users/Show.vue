<template>
  <developer-layout title="Lojas">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Lojas
        </h2>

        <div>
          <Link
            :href="route('developer.users.reports', user.id)"
            class="
              ml-2
              inline-flex
              items-center
              px-4
              py-2
              bg-purple-500
              border border-transparent
              rounded-md
              font-semibold
              text-xs text-white
              uppercase
              tracking-widest
              hover:bg-purple-300
              active:bg-purple-900
              focus:outline-none
              transition
              ease-in-out
              duration-150
            "
          >
            Relatórios
          </Link>

          <Link
            :href="route('developer.users.edit', user.id)"
            class="
              ml-2
              inline-flex
              items-center
              px-4
              py-2
              bg-speedapp-orange-500
              border border-transparent
              rounded-md
              font-semibold
              text-xs text-white
              uppercase
              tracking-widest
              hover:bg-speedapp-orange-300
              active:bg-speedapp-orange-900
              focus:outline-none
              transition
              ease-in-out
              duration-150
            "
          >
            Editar
          </Link>

          <Link
            as="button"
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
            @click="goBack"
          >
            Voltar
          </Link>
        </div>
      </div>
    </template>

    <div class="py-12">
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
                      Email
                    </dt>
                    <dd
                      class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                    >
                      {{ user.email }}
                    </dd>
                  </div>

                  <div
                    class="
                        py-4
                        sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6
                      "
                  >
                    <dt class="text-sm font-medium text-gray-500">
                      Telefone
                    </dt>
                    <dd
                      class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                    >
                      {{ user.phone ?? '-' }}
                    </dd>
                  </div>

                  <div
                    class="
                        py-4
                        sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6
                      "
                  >
                    <dt class="text-sm font-medium text-gray-500">
                      CPF/CNPJ
                    </dt>
                    <dd
                      class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                    >
                      {{ user.cpf_cnpj ?? '-' }}
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
                        <Link
                          :href="route('developer.cities.show', city.id)"
                          class="font-bold"
                        >
                          {{ city.name }}
                        </Link><template v-if="i < user.cities.length-1">
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
  </developer-layout>

  <div
    aria-live="assertive"
    class="
      fixed
      inset-0
      flex
      items-end
      px-4
      py-6
      pointer-events-none
      sm:p-6 sm:items-start
    "
  >
    <div class="w-full flex flex-col items-center space-y-4 sm:items-end">
      <transition
        enter-active-class="transform ease-out duration-300 transition"
        enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
        leave-active-class="transition ease-in duration-100"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div
          v-if="status"
          class="
            max-w-sm
            w-full
            bg-white
            shadow-lg
            rounded-lg
            pointer-events-auto
            ring-1 ring-black ring-opacity-5
            overflow-hidden
          "
        >
          <div class="p-4">
            <div class="flex items-center">
              <div class="shrink-0">
                <CheckCircleIcon
                  class="h-6 w-6 text-green-400"
                  aria-hidden="true"
                />
              </div>

              <div class="ml-3 w-0 flex-1 flex justify-between items-center">
                <p class="text-sm font-medium text-gray-900">
                  {{ status }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref } from "vue";
import DeveloperLayout from "@/Layouts/DeveloperLayout.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetFormSection from "@/Jetstream/FormSection.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import { Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import { CheckCircleIcon, XCircleIcon } from "@heroicons/vue/solid";

export default defineComponent({
  components: {
    DeveloperLayout,
    JetActionMessage,
    JetButton,
    JetFormSection,
    JetInput,
    JetInputError,
    JetLabel,
    JetSecondaryButton,
    Link,
    CheckCircleIcon,
    XCircleIcon
  },

  props: {
    user: {
      type: Object,
      required: true,
    }
  },

  setup(props) {
    const status = ref(null);

    return {
      status,
    }
  }
});
</script>
