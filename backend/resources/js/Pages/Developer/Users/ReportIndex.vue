<template>
  <developer-layout title="Lojas">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Lojas
        </h2>

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
    </template>

    <div class="py-12">
      <div class="flex flex-col">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div
            class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8"
          >
            <div
              v-if="reports.data.length == 0"
              class="flex-1 flex items-center justify-center"
            >
              <span class="text-sm text-gray-500 italic">Nenhum relatório disponível ainda.</span>
            </div>

            <ul
              v-else
              role="list"
              class="px-4 py-5 sm:p-0 space-y-5"
            >
              <li
                v-for="(report, index) in reports.data"
                :key="index"
                class="border border-gray-200 bg-white rounded-md"
              >
                <div
                  class="block"
                >
                  <div class="px-4 py-4 flex items-center sm:px-6">
                    <div class="min-w-0 flex-1 sm:flex sm:items-center sm:justify-between">
                      <div class="truncate">
                        <div class="flex text-sm">
                          <p class="font-medium text-gray-700 truncate">
                            {{ report.filename }}
                          </p>
                        </div>
                        <div class="mt-2 flex">
                          <div class="flex items-center text-sm text-gray-500">
                            <time :datetime="report.created_at">{{ report.formatted_created_at }}</time>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="ml-5 flex-shrink-0">
                      <a
                        :href="route('developer.users.reports.download', {
                          user: user.id,
                          _query: {
                            filename: report.filename,
                          }
                        })"
                        class="inline-flex
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
                        duration-150"
                      >
                        Baixar
                      </a>
                    </div>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </developer-layout>
</template>

<script>
import { defineComponent } from "vue";
import DeveloperLayout from "@/Layouts/DeveloperLayout.vue";
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
    DeveloperLayout,
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
      required: true,
    },
    reports: {
      type: Object,
      required: true,
    },
  },
});
</script>
