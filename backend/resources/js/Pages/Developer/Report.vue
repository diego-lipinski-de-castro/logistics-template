<template>
  <developer-layout title="Relatório do entregas">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Relatório do entregas
      </h2>
    </template>

    <div class="py-12">
      <form @submit.prevent="submit">
        <div>
          <jet-label value="Filtrar por data" />
          <div class="flex mt-1">
            <litepie-datepicker
              ref="picker"
              v-model="form.date"
              i18n="pt-br"
              :formatter="dateFormat"
              :auto-apply="false"
              :options="dateOptions"
              :as-single="false"
            />
          </div>
        </div>

        <div class="mt-5">
          <RadioGroup v-model="form.filter">
            <RadioGroupLabel
              class="block font-medium text-sm text-gray-700"
            >
              Filtrar por
            </RadioGroupLabel>

            <div
              class="mt-1 grid grid-cols-1 gap-y-6 sm:grid-cols-3 sm:gap-x-4"
            >
              <!-- <RadioGroupOption
                                    as="template"
                                    :value="null"
                                    v-slot="{ checked, active }"
                                >
                                    <div
                                        :class="[
                                            checked
                                                ? 'border-transparent'
                                                : 'border-gray-300',
                                            active
                                                ? 'ring-2 ring-speedapp-orange-500'
                                                : '',
                                            'relative bg-white border rounded-lg shadow-sm p-4 flex cursor-pointer focus:outline-none',
                                        ]"
                                    >
                                        <div class="flex-1 flex">
                                            <div class="flex flex-col">
                                                <RadioGroupLabel
                                                    as="span"
                                                    class="block text-sm font-medium text-gray-900"
                                                >
                                                    Todos
                                                </RadioGroupLabel>
                                            </div>
                                        </div>
                                        <CheckCircleIconSolid
                                            :class="[
                                                !checked ? 'invisible' : '',
                                                'h-5 w-5 text-speedapp-orange-600',
                                            ]"
                                            aria-hidden="true"
                                        />
                                        <div
                                            :class="[
                                                active ? 'border' : 'border-2',
                                                checked
                                                    ? 'border-speedapp-orange-500'
                                                    : 'border-transparent',
                                                'absolute -inset-px rounded-lg pointer-events-none',
                                            ]"
                                            aria-hidden="true"
                                        />
                                    </div>
                                </RadioGroupOption> -->

              <RadioGroupOption
                v-for="(filter, index) in filters"
                :key="index"
                v-slot="{ checked, active }"
                as="template"
                :value="filter"
              >
                <div
                  :class="[
                    checked
                      ? 'border-transparent'
                      : 'border-gray-300',
                    active
                      ? 'ring-2 ring-speedapp-orange-500'
                      : '',
                    'relative bg-white border rounded-lg shadow-sm p-4 flex cursor-pointer focus:outline-none',
                  ]"
                >
                  <div class="flex-1 flex">
                    <div class="flex flex-col">
                      <RadioGroupLabel
                        as="span"
                        class="block text-sm font-medium text-gray-900"
                      >
                        {{ filter.title }}
                      </RadioGroupLabel>

                      <RadioGroupDescription
                        as="span"
                        class="mt-1 flex items-center text-sm text-gray-500"
                      >
                        {{ filter.quantity }}
                      </RadioGroupDescription>
                    </div>
                  </div>
                  <CheckCircleIconSolid
                    :class="[
                      !checked ? 'invisible' : '',
                      'h-5 w-5 text-speedapp-orange-600',
                    ]"
                    aria-hidden="true"
                  />
                  <div
                    :class="[
                      active ? 'border' : 'border-2',
                      checked
                        ? 'border-speedapp-orange-500'
                        : 'border-transparent',
                      'absolute -inset-px rounded-lg pointer-events-none',
                    ]"
                    aria-hidden="true"
                  />
                </div>
              </RadioGroupOption>
            </div>
          </RadioGroup>
        </div>

        <div
          v-if="form.filter"
          class="mt-5"
        >
          <label
            for="option"
            class="block text-sm font-medium text-gray-700"
          >{{ form.filter.title }}</label>
          <select
            id="option"
            v-model="form.option"
            name="option"
            class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-speedapp-orange-500 focus:border-speedapp-orange-500 sm:text-sm rounded-md"
          >
            <option :value="null">
              Todos
            </option>
            <option
              v-for="(option, index) in options"
              :key="index"
              :value="option.id"
            >
              {{ option[form.filter.field] }}
            </option>
          </select>
        </div>

        <div
          v-if="form.option === null"
          class="mt-5"
        >
          <SwitchGroup
            as="div"
            class="flex items-center"
          >
            <Switch
              v-model="form.individual"
              :class="[
                form.individual
                  ? 'bg-speedapp-orange-600'
                  : 'bg-gray-200',
                'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-speedapp-orange-500',
              ]"
            >
              <span
                aria-hidden="true"
                :class="[
                  form.individual
                    ? 'translate-x-5'
                    : 'translate-x-0',
                  'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200',
                ]"
              />
            </Switch>
            <SwitchLabel
              as="span"
              class="ml-3"
            >
              <span class="text-sm font-medium text-gray-900">Exportar individualmente
              </span>
            </SwitchLabel>
          </SwitchGroup>
        </div>

        <div class="mt-5">
          <fieldset class="space-y-2">
            <legend
              class="block text-sm font-medium text-gray-700"
            >
              Filtrar por status
            </legend>

            <div
              v-for="(label, key) in statuses"
              :key="key"
              class="relative flex items-start"
            >
              <div class="flex items-center h-5">
                <input
                  :id="key"
                  v-model="form.status"
                  :value="key"
                  :name="key"
                  type="checkbox"
                  class="focus:ring-speedapp-orange-500 h-4 w-4 text-speedapp-orange-600 border-gray-300 rounded"
                >
              </div>
              <div class="ml-3 text-sm">
                <label
                  :for="key"
                  class="text-sm text-gray-500"
                >{{ label }}</label>
              </div>
            </div>
          </fieldset>
        </div>

        <div class="mt-5 text-right">
          <button
            type="submit"
            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-speedapp-orange-600 hover:bg-speedapp-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-speedapp-orange-500"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
          >
            Exportar
          </button>
        </div>
      </form>
    </div>

    <div
      aria-live="assertive"
      class="fixed inset-0 flex items-end px-4 py-6 pointer-events-none sm:p-6 sm:items-start"
    >
      <div
        class="w-full flex flex-col items-center space-y-4 sm:items-end"
      >
        <transition
          enter-active-class="transform ease-out duration-300 transition"
          enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
          enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
          leave-active-class="transition ease-in duration-100"
          leave-from-class="opacity-100"
          leave-to-class="opacity-0"
        >
          <div
            v-if="status.messages.length > 0"
            class="max-w-sm w-full bg-white shadow-md rounded-md pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden"
          >
            <div class="p-4">
              <div class="flex">
                <div class="shrink-0">
                  <CheckCircleIcon
                    v-if="status.type == 'success'"
                    class="h-6 w-6 text-green-400"
                    aria-hidden="true"
                  />

                  <XCircleIcon
                    v-if="status.type == 'error'"
                    class="h-6 w-6 text-red-400"
                    aria-hidden="true"
                  />
                </div>

                <div class="ml-3 w-0 flex-1 flex flex-col">
                  <p
                    v-for="(message, i) in status.messages"
                    :key="i"
                    class="text-sm font-medium text-gray-900"
                  >
                    {{ message }}
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
import { computed, defineComponent, ref } from "vue";
import DeveloperLayout from "@/Layouts/DeveloperLayout.vue";
import { useForm } from "@inertiajs/inertia-vue3";
import {
    ArrowSmDownIcon,
    ArrowSmUpIcon,
    CheckCircleIcon as CheckCircleIconSolid,
} from "@heroicons/vue/solid";

import {
    UsersIcon,
    EyeIcon,
    EyeOffIcon,
    CheckCircleIcon,
    XCircleIcon,
} from "@heroicons/vue/outline";

import JetLabel from "@/Jetstream/Label.vue";
import LitepieDatepicker from "litepie-datepicker";
import confetti from "canvas-confetti";

import {
    RadioGroup,
    RadioGroupDescription,
    RadioGroupLabel,
    RadioGroupOption,
    Switch,
    SwitchGroup,
    SwitchLabel,
} from "@headlessui/vue";

export default defineComponent({
    components: {
        DeveloperLayout,
        ArrowSmDownIcon,
        ArrowSmUpIcon,
        UsersIcon,
        EyeIcon,
        EyeOffIcon,
        CheckCircleIcon,
        CheckCircleIconSolid,
        XCircleIcon,
        JetLabel,
        LitepieDatepicker,
        RadioGroup,
        RadioGroupDescription,
        RadioGroupLabel,
        RadioGroupOption,
        Switch,
        SwitchGroup,
        SwitchLabel,
    },

    props: {
        cities: { type: Array, default: () => []},
        users: { type: Array, default: () => []},
        drivers: { type: Array, default: () => []},
        statuses: {
          type: Object,
          default: () => {},
        },
    },

    setup(props) {
        const status = ref({
            type: null,
            messages: [],
        });

        const filters = [
            {
                key: "cities",
                field: "name",
                title: "Cidades",
                quantity: `${props.cities.length} disponíveis`,
            },
            {
                key: "users",
                field: "name",
                title: "Lojas",
                quantity: `${props.users.length} disponíveis`,
            },
            {
                key: "drivers",
                field: "full_name",
                title: "Entregadores",
                quantity: `${props.drivers.length} disponíveis`,
            },
        ];

        const options = computed(() => {
            if (form.filter == null) return [];

            return props[form.filter.key];
        });

        const form = useForm({
            date: [],
            filter: filters[0],
            option: null,
            status: ["COMPLETED"],
            individual: false,
        });

        const picker = ref(null);

        const dateFormat = ref({
            date: "DD/MM/YYYY",
            month: "MM",
        });

        const dateOptions = ref({
            shortcuts: {
                today: "Hoje",
                yesterday: "Ontem",
                past: (period) => `Últimos ${period} dias`,
                currentMonth: "Esse mês",
                pastMonth: "Mês passado",
            },
            footer: {
                apply: "Aplicar",
                cancel: "Cancelar",
            },
        });

        const submit = () => {
            form.transform((data) => ({
                ...data,
                filter: data.filter.key,
            })).post(route("developer.reports.generate"), {
                preserveScroll: true,
                onSuccess: (res) => {
                    console.log(res.props);

                    confetti({
                        particleCount: 80,
                        spread: 200,
                        origin: { y: 0.6 },
                    });

                    // picker.value.pickerValue = '';
                    // form.date = [];

                    setStatus("success", res.props.status);
                },
                onError: (error) => {
                    console.log(error);

                    setStatus("error", error.message);
                },
            });
        };

        const setStatus = (type, messages) => {
            status.value = { type, messages };

            setTimeout(() => {
                status.value = {
                    type: null,
                    messages: [],
                };
            }, 30000);
        };

        return {
            filters,
            options,
            picker,
            status,
            form,
            dateFormat,
            dateOptions,
            submit,
        };
    },
});
</script>
