<template>
  <developer-layout title="Solicitar entrega">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800  leading-tight">
          Solicitar entrega
        </h2>
      </div>
    </template>

    <div class="py-12">
      <nav
        class="
          flex flex-col
          md:flex-row
          relative
          z-0
          rounded-lg
          shadow
          divide-x divide-gray-200
        "
      >
        <template
          v-for="(tab, i) in tabs"
          :key="tab.name"
        >
          <template v-if="tab.enabled">
            <button
              :class="[
                i === tabIndex
                  ? 'text-gray-900'
                  : 'text-gray-500 hover:text-gray-700',
                i === 0 ? 'md:rounded-l-lg' : '',
                i === tabs.length - 1 ? 'md:rounded-r-lg' : '',
                'cursor-pointer group relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4 text-sm font-medium text-center hover:bg-gray-50 focus:z-10',
              ]"
              @click="tabIndex = i"
            >
              <span class="relative">
                {{ tab.name }}

                <span v-if="tab.beta" class="absolute -top-[5px] -right-[30px] text-[10px] font-bold tracking-wider leading-6 uppercase text-black ">Beta</span>
              </span>

              <span
                :class="[
                  i === tabIndex ? 'bg-speedapp-orange-500' : 'bg-transparent',
                  'absolute inset-x-0 bottom-0 h-0.5',
                ]"
              />
            </button>
          </template>

          <template v-else>
            <button
              disabled
              :class="[
                'opacity-50 text-gray-500 hover:text-gray-700',
                i === 0 ? 'md:rounded-l-lg' : '',
                i === tabs.length - 1 ? 'md:rounded-r-lg' : '',
                'cursor-pointer group relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4 text-sm font-medium text-center focus:z-10',
              ]"
            >
              <span>{{ tab.name }}</span>
              <span class="bg-transparent absolute inset-x-0 bottom-0 h-0.5" />
            </button>
          </template>
        </template>
      </nav>

      <div class="mt-6 flex flex-col">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div
            class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8"
          >
            <CreateDefault
              v-if="tabIndex == 0"
              :users="users"
              :drivers="drivers"
            />
            <CreateLastmile
              v-if="tabIndex == 1"
              :users="users"
              :drivers="drivers"
            />
          </div>
        </div>
      </div>
    </div>
  </developer-layout>
</template>

<script>
import { defineComponent, onMounted, ref } from "vue";
import DeveloperLayout from "@/Layouts/DeveloperLayout.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetFormSection from "@/Jetstream/FormSection.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import { Link } from "@inertiajs/inertia-vue3";
import CreateDefault from '@/Pages/Developer/Deliveries/CreateDefault';
import CreateLastmile from '@/Pages/Developer/Deliveries/CreateLastmile';

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
    CreateDefault,
    CreateLastmile,
  },

  props: {
    users: {
      type: Array,
      default: () => [],
    },
    drivers: {
      type: Array,
      default: () => [],
    },
  },

  setup() {
    const tabIndex = ref(0);

    const tabs = [
      {
        name: "Padrão",
        enabled: true,
        beta: false,
      },
      {
        name: "Lastmile",
        enabled: true,
        beta: true,
      },
    ];

    return {
      tabIndex,
      tabs,
    };
  },
});
</script>