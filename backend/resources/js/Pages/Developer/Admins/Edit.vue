<template>
  <developer-layout title="Admins">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Admins
        </h2>

        <div>
          <Link
            :href="route('developer.admins.index')"
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
      </div>
    </template>

    <div class="py-12">
      <div
        :class="['mx-auto sm:px-6 lg:px-8', { 'max-w-7xl': tabIndex !== 5 }]"
      >
        <div
          :class="[
            'mx-auto',
            {
              'max-w-7xl sm:px-6 lg:px-8': tabIndex === 5,
            },
          ]"
        >
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
                  <span>{{ tab.name }}</span>
                  <span
                    :class="[
                      i === tabIndex
                        ? 'bg-speedapp-orange-500'
                        : 'bg-transparent',
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
                  <span
                    class="bg-transparent absolute inset-x-0 bottom-0 h-0.5"
                  />
                </button>
              </template>
            </template>
          </nav>
        </div>

        <div class="mt-6 flex flex-col">
          <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div
              class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8"
            >
              <form
                v-if="tabIndex === 0"
                @submit.prevent="submit"
              >
                <div class="border border-gray-300 rounded-md overflow-hidden">
                  <div class="px-4 py-5 bg-white sm:p-6">
                    <div class="grid grid-cols-6 gap-6">
                      <div class="col-span-6">
                        <jet-label
                          for="name"
                          value="Nome"
                        />
                        <jet-input
                          id="name"
                          v-model="form.name"
                          type="text"
                          class="mt-1 block w-full"
                          autocomplete="name"
                        />
                        <jet-input-error
                          :message="form.errors.name"
                          class="mt-2"
                        />
                      </div>

                      <div class="col-span-6">
                        <jet-label
                          for="email"
                          value="E-mail"
                        />
                        <jet-input
                          id="email"
                          v-model="form.email"
                          type="email"
                          class="mt-1 block w-full"
                          autocomplete="email"
                        />
                        <jet-input-error
                          :message="form.errors.email"
                          class="mt-2"
                        />
                      </div>
                    </div>
                  </div>
                  <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <button
                      type="submit"
                      class="
                        inline-flex
                        justify-center
                        py-2
                        px-4
                        border border-transparent
                        shadow-sm
                        text-sm
                        font-medium
                        rounded-md
                        text-white
                        bg-speedapp-orange-600
                        hover:bg-speedapp-orange-700
                        focus:outline-none
                        focus:ring-2
                        focus:ring-offset-2
                        focus:ring-speedapp-orange-500
                      "
                      :class="{ 'opacity-25': form.processing }"
                      :disabled="form.processing"
                    >
                      Salvar
                    </button>
                  </div>
                </div>
              </form>

              <form
                v-if="tabIndex === 1"
                @submit.prevent="submitPassword"
              >
                <div class="border border-gray-300 rounded-md overflow-hidden">
                  <div class="px-4 py-5 bg-white sm:p-6">
                    <div class="grid grid-cols-6 gap-6">
                      <div class="col-span-6">
                        <jet-label
                          for="password"
                          value="Nova senha"
                        />
                        <jet-input
                          id="password"
                          v-model="passwordForm.password"
                          type="password"
                          class="mt-1 block w-full"
                          autocomplete="new-password"
                        />
                        <jet-input-error
                          :message="passwordForm.errors.password"
                          class="mt-2"
                        />
                      </div>
                    </div>
                  </div>
                  <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <button
                      type="submit"
                      class="
                        inline-flex
                        justify-center
                        py-2
                        px-4
                        border border-transparent
                        shadow-sm
                        text-sm
                        font-medium
                        rounded-md
                        text-white
                        bg-speedapp-orange-600
                        hover:bg-speedapp-orange-700
                        focus:outline-none
                        focus:ring-2
                        focus:ring-offset-2
                        focus:ring-speedapp-orange-500
                      "
                      :class="{ 'opacity-25': passwordForm.processing }"
                      :disabled="passwordForm.processing"
                    >
                      Salvar
                    </button>
                  </div>
                </div>
              </form>

              <form
                v-if="tabIndex === 2"
                @submit.prevent="submitCities"
              >
                <div class="border border-gray-300 rounded-md overflow-hidden">
                  <div class="px-4 py-5 bg-white sm:p-6">
                    <div class="grid grid-cols-2">
                      <div class="col-span-1 p-2">
                        <span class="block font-medium text-sm text-gray-700">Cidades disponíveis</span>

                        <ul
                          v-if="availableCities.length > 0"
                          role="list"
                          class="divide-y divide-gray-200 border border-gray-300 rounded-md mt-2"
                        >
                          <li
                            v-for="(city, i) in availableCities"
                            :key="i"
                            class="p-2 cursor-pointer select-none"
                            @click="add(city)"
                          >
                            <span class="p-2 text-gray-500">{{ city.name }}</span>
                          </li>
                        </ul>

                        <span
                          v-else
                          class="block text-center rounded-md mt-4 p-2 text-gray-500"
                        >
                          Nenhuma cidade disponível
                        </span>
                      </div>

                      <div class="col-span-1 p-2">
                        <span class="block font-medium text-sm text-gray-700">Cidades ativas</span>

                        <ul
                          v-if="selectedCities.length > 0"
                          role="list"
                          class="divide-y divide-gray-200 border border-gray-300 rounded-md mt-2"
                        >
                          <li
                            v-for="(city, i) in selectedCities"
                            :key="i"
                            class="p-2 cursor-pointer select-none"
                            @click="remove(city)"
                          >
                            <span class="p-2 text-gray-500">{{ city.name }}</span>
                          </li>
                        </ul>

                        <span
                          v-else
                          class="block text-center rounded-md mt-4 p-2 text-gray-500"
                        >
                          Nenhuma cidade disponível
                        </span>
                      </div>
                    </div>
                  </div>

                  <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
                    <button
                      type="submit"
                      class="
                        inline-flex
                        justify-center
                        py-2
                        px-4
                        border border-transparent
                        shadow-sm
                        text-sm
                        font-medium
                        rounded-md
                        text-white
                        bg-speedapp-orange-600
                        hover:bg-speedapp-orange-700
                        focus:outline-none
                        focus:ring-2
                        focus:ring-offset-2
                        focus:ring-speedapp-orange-500
                      "
                    >
                      Salvar
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>

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
import differenceBy from "lodash/differenceBy";
import { Switch, SwitchGroup, SwitchLabel } from "@headlessui/vue";

import L from "leaflet";
import "leaflet-draw/dist/leaflet.draw";
import "leaflet/dist/leaflet.css";
import "leaflet-draw/dist/leaflet.draw.css";
import "leaflet.gridlayer.googlemutant";

import startIcon from "@/assets/images/start-icon.svg";
import homeIcon from "@/assets/images/anchor.svg";
import plusIcon from "@/assets/images/plus.svg";
import minusIcon from "@/assets/images/minus.svg";

import { Loader } from "@googlemaps/js-api-loader";
import { CheckCircleIcon } from "@heroicons/vue/solid";

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
    Switch,
    SwitchGroup,
    SwitchLabel,
    CheckCircleIcon,
  },

  props: {
    cities: {
      type: Array,
      default: () => []
    },
    admin: {
      type: Object,
      required: true,
    },
  },

  data() {
    return {
      status: null,

      tabIndex: 0,
      tabs: [
        {
          name: "Conta",
          enabled: true,
        },
        {
          name: "Senha",
          enabled: true,
        },
        {
          name: "Cidades",
          enabled: true,
        },
      ],

      form: this.$inertia.form({
        _method: "PUT",
        name: this.admin.name,
        email: this.admin.email,
      }),

      passwordForm: this.$inertia.form({
        _method: "PUT",
        password: "",
      }),

      selectedCities: this.admin.cities,
    };
  },

  computed: {
    availableCities() {
      return differenceBy(this.cities, this.selectedCities, "id");
    },
  },

  methods: {
    setStatus(status) {
      this.status = status;

      setTimeout(() => {
        this.status = null;
      }, 2000);
    },

    submit() {
      this.form.post(route("developer.admins.update", this.admin.id), {
        preserveScroll: true,
        onSuccess: (res) => {
          console.log(res);
          this.setStatus(res.props.status);
        },
      });
    },

    submitPassword() {
      this.passwordForm.post(
        route("developer.admins.updatePassword", this.admin.id),
        {
          preserveScroll: true,
          onSuccess: (res) => {
            this.setStatus(res.props.status);
          },
        }
      );
    },

    submitCities() {
      this.$inertia.put(
        route("developer.admins.updateCities", this.admin.id),
        {
          cities: this.selectedCities.map((c) => c.id),
        },
        {
          preserveScroll: true,
          onSuccess: (res) => {
            this.setStatus(res.props.status);
          },
        }
      );
    },

    add(city) {
      if (!this.selectedCities.includes(city)) {
        this.selectedCities.push(city);
      }
    },

    remove(city) {
      this.selectedCities.splice(this.selectedCities.indexOf(city), 1);
    },
  },
});
</script>
