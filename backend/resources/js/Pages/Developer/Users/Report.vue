<template>
  <form @submit.prevent="submit">
    <div class="border border-gray-300 rounded-md overflow-hidden">
      <div class="px-4 py-5 bg-white sm:p-6">
        <div class="grid grid-cols-6 gap-6">
          <div class="col-span-6">
            <RadioGroup v-model="selected">
              <RadioGroupLabel
                class="block font-medium text-sm text-gray-700"
              >
                Período de fechamento
              </RadioGroupLabel>
              <div class="bg-white rounded-md -space-y-px mt-2">
                <RadioGroupOption
                  v-slot="{ checked, disabled }"
                  as="div"
                  value="DAY"
                >
                  <div
                    :class="[
                      checked
                        ? 'border-gray-200 z-10'
                        : 'border-gray-200',
                      disabled ? 'opacity-50': '',
                      'rounded-tl-md rounded-tr-md relative border p-4 flex cursor-pointer focus:outline-none',
                    ]"
                  >
                    <span
                      :class="[
                        checked
                          ? 'bg-speedapp-orange-600 border-transparent'
                          : 'bg-white border-gray-300',
                        'h-4 w-4 mt-0.5 cursor-pointer rounded-full border flex items-center justify-center',
                      ]"
                      aria-hidden="true"
                    >
                      <span
                        class="rounded-full bg-white w-1.5 h-1.5"
                      />
                    </span>
                    <div class="ml-3 flex flex-col">
                      <RadioGroupLabel
                        as="span"
                        :class="[
                          checked
                            ? 'text-gray-900'
                            : 'text-gray-900',
                          'block text-sm font-medium',
                        ]"
                      >
                        Dia de fechamento
                      </RadioGroupLabel>
                      <RadioGroupDescription
                        class="block text-sm"
                      >
                        <span
                          :class="[
                            checked
                              ? 'text-gray-500'
                              : 'text-gray-500',
                          ]"
                        >O relatório será fechado no dia escolhido pelo usuário</span>

                        <div class="mt-2">
                          <label
                            for="day"
                            class="sr-only block text-sm font-medium text-gray-700"
                          >Escolha o dia do mês</label>
                          <select
                            id="day"
                            v-model="selectedOption"
                            :disabled="!checked"
                            name="day"
                            class="disabled:opacity-50 mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-speedapp-orange-500 focus:border-speedapp-orange-500 sm:text-sm rounded-md"
                          >
                            <option
                              v-for="day in 28"
                              :key="day"
                              :value="day"
                            >
                              {{ day }}
                            </option>
                          </select>
                        </div>
                      </RadioGroupDescription>
                    </div>
                  </div>
                </RadioGroupOption>

                <RadioGroupOption
                  v-slot="{ checked, disabled }"
                  as="div"
                  value="WEEKLY"
                >
                  <div
                    :class="[
                      checked
                        ? 'border-gray-200 z-10'
                        : 'border-gray-200',
                      disabled ? 'opacity-50': '',
                      'relative border p-4 flex cursor-pointer focus:outline-none',
                    ]"
                  >
                    <span
                      :class="[
                        checked
                          ? 'bg-speedapp-orange-600 border-transparent'
                          : 'bg-white border-gray-300',
                        'h-4 w-4 mt-0.5 cursor-pointer rounded-full border flex items-center justify-center',
                      ]"
                      aria-hidden="true"
                    >
                      <span
                        class="rounded-full bg-white w-1.5 h-1.5"
                      />
                    </span>
                    <div class="ml-3 flex flex-col">
                      <RadioGroupLabel
                        as="span"
                        :class="[
                          checked
                            ? 'text-gray-900'
                            : 'text-gray-900',
                          'block text-sm font-medium',
                        ]"
                      >
                        Semanal
                      </RadioGroupLabel>
                      <RadioGroupDescription
                        class="block text-sm"
                      >
                        <span
                          :class="[
                            checked
                              ? 'text-gray-500'
                              : 'text-gray-500',
                          ]"
                        >O relatório será fechado no dia da semana escolhido pelo usuário</span>

                        <div
                          class="mt-2"
                        >
                          <label
                            for="day"
                            class="sr-only block text-sm font-medium text-gray-700"
                          >Escolha o dia do mês</label>
                          <select
                            id="day"
                            v-model="selectedOption"
                            :disabled="!checked"
                            name="day"
                            class="disabled:opacity-50 mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-speedapp-orange-500 focus:border-speedapp-orange-500 sm:text-sm rounded-md"
                          >
                            <option value="MONDAY">
                              Segunda-feira
                            </option>
                            <option value="TUESDAY">
                              Terça-feira
                            </option>
                            <option value="WEDNESDAY">
                              Quarta-feira
                            </option>
                            <option value="THURSDAY">
                              Quinta-feira
                            </option>
                            <option value="FRIDAY">
                              Sexta-feira
                            </option>
                            <option value="SATURDAY">
                              Sábado
                            </option>
                            <option value="SUNDAY">
                              Domingo
                            </option>
                          </select>
                        </div>
                      </RadioGroupDescription>
                    </div>
                  </div>
                </RadioGroupOption>

                <RadioGroupOption
                  v-slot="{ checked, disabled }"
                  as="template"
                  value="MONTHLY"
                >
                  <div
                    :class="[
                      checked
                        ? 'border-gray-200 z-10'
                        : 'border-gray-200',
                      disabled ? 'opacity-50': '',
                      'rounded-bl-md rounded-br-md relative border p-4 flex cursor-pointer focus:outline-none',
                    ]"
                  >
                    <span
                      :class="[
                        checked
                          ? 'bg-speedapp-orange-600 border-transparent'
                          : 'bg-white border-gray-300',
                        'h-4 w-4 mt-0.5 cursor-pointer rounded-full border flex items-center justify-center',
                      ]"
                      aria-hidden="true"
                    >
                      <span
                        class="rounded-full bg-white w-1.5 h-1.5"
                      />
                    </span>
                    <div class="ml-3 flex flex-col">
                      <RadioGroupLabel
                        as="span"
                        :class="[
                          checked
                            ? 'text-gray-900'
                            : 'text-gray-900',
                          'block text-sm font-medium',
                        ]"
                      >
                        Mensal
                      </RadioGroupLabel>
                      <RadioGroupDescription
                        class="block text-sm"
                      >
                        <span
                          :class="[
                            checked
                              ? 'text-gray-500'
                              : 'text-gray-500',
                          ]"
                        >O relatório será fechado do primeiro ao último dia do mês</span>
                      </RadioGroupDescription>
                    </div>
                  </div>
                </RadioGroupOption>
              </div>
            </RadioGroup>
          </div>
        </div>
      </div>

      <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
        <button
          type="submit"
          class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-speedapp-orange-600 hover:bg-speedapp-orange-700 focus:outline-none"
        >
          Salvar
        </button>
      </div>
    </div>
  </form>
</template>

<script>
import { defineComponent, onMounted, ref, watch } from "vue";
import DeveloperLayout from "@/Layouts/DeveloperLayout.vue";
import UserArea from "@/Pages/Developer/Users/Area.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetFormSection from "@/Jetstream/FormSection.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import { Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import {
    RadioGroup,
    RadioGroupDescription,
    RadioGroupLabel,
    RadioGroupOption,
} from "@headlessui/vue";

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
        UserArea,
        RadioGroup,
        RadioGroupDescription,
        RadioGroupLabel,
        RadioGroupOption,
    },

    props: {
        user: {
            type: Object,
            required: true,
        },
    },
    emits: ["setStatus"],

    setup(props, { emit }) {
        const selected = ref(props.user.report_period ?? 'DAY');
        const selectedOption = ref(props.user.report_period_option ?? null);

        watch(selected, (n, o) => {
          selectedOption.value = null;
        })

        const submit = () => {
          Inertia.post(route('developer.users.updateReport', props.user.id), {
            _method: 'PUT',
            report_period: selected.value,
            report_period_option: selectedOption.value,
          }, {
            preserveScroll: true,
            onSuccess(res) {
              emit("setStatus", res.props.status);
            }
          })
        }

        return {
            selected,
            selectedOption,
            submit,
        };
    },
});
</script>
