<template>
  <developer-layout title="Lojas">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Lojas
        </h2>

        <div>
          <Link
            :href="route('developer.users.index')"
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
            <form
              v-if="tabIndex === 0"
              @submit.prevent="submit"
            >
              <div class="border border-gray-300 rounded-md overflow-hidden">
                <div class="px-4 py-5 bg-white sm:p-6">
                  <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6">
                      <jet-label
                        for="city"
                        value="Cidade"
                      />
                      <select
                        id="city"
                        v-model="form.city_id"
                        name="city"
                        class="
                          mt-1
                          block
                          w-full
                          pl-3
                          pr-10
                          py-2
                          border-gray-300
                          focus:outline-none
                          focus:ring-speedapp-orange-500
                          focus:border-speedapp-orange-500
                          rounded-md
                          shadow-sm
                        "
                      >
                        <option
                          v-for="(city, index) in cities"
                          :key="index"
                          :value="city.id"
                        >
                          {{ city.name }}
                        </option>
                      </select>
                      <jet-input-error
                        :message="form.errors.city_id"
                        class="mt-2"
                      />
                    </div>

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

                    <div class="col-span-6">
                      <jet-label
                        for="phone"
                        value="Telefone"
                      />
                      <jet-input
                        id="phone"
                        v-model="form.phone"
                        type="text"
                        class="mt-1 block w-full"
                        autocomplete="phone"
                      />
                      <jet-input-error
                        :message="form.errors.phone"
                        class="mt-2"
                      />
                    </div>

                    <div class="col-span-6">
                      <jet-label
                        for="cpf_cnpj"
                        value="CPF/CNPJ"
                      />
                      <jet-input
                        v-maska="['###.###.###-##', '##.###.###/####-##']"
                        id="cpf_cnpj"
                        v-model="form.cpf_cnpj"
                        type="text"
                        class="mt-1 block w-full"
                        autocomplete="cpf_cnpj"
                      />
                      <jet-input-error
                        :message="form.errors.cpf_cnpj"
                        class="mt-2"
                      />
                    </div>

                    <div class="col-span-6">
                      <jet-label
                        for="company_type"
                        value="Tipo"
                      />
                      <select
                        id="company_type"
                        v-model="form.company_type"
                        name="company_type"
                        class="
                          mt-1
                          block
                          w-full
                          pl-3
                          pr-10
                          py-2
                          border-gray-300
                          focus:outline-none
                          focus:ring-speedapp-orange-500
                          focus:border-speedapp-orange-500
                          rounded-md
                          shadow-sm
                        "
                      >
                        <option value="MEI">MEI</option>
                        <option value="LIMITED">LIMITED</option>
                        <option value="INDIVIDUAL">INDIVIDUAL</option>
                        <option value="ASSOCIATION">ASSOCIATION</option>
                      </select>
                      <jet-input-error
                        :message="form.errors.company_type"
                        class="mt-2"
                      />
                    </div>

                    <div class="col-span-6">
                      <jet-label
                        for="info"
                        value="Informações"
                      />

                      <textarea
                        id="info"
                        v-model="form.info"
                        rows="5"
                        class="
                          mt-1
                          block
                          w-full
                          border-gray-300
                          focus:border-speedapp-orange-300
                          focus:ring
                          focus:ring-speedapp-orange-200
                          focus:ring-opacity-50
                          rounded-md
                          shadow-sm
                        "
                      />

                      <jet-input-error
                        :message="form.errors.info"
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
                    :class="{
                      'opacity-25': form.processing,
                    }"
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
                    :class="{
                      'opacity-25': passwordForm.processing,
                    }"
                    :disabled="passwordForm.processing"
                  >
                    Salvar
                  </button>
                </div>
              </div>
            </form>

            <form
              v-if="tabIndex === 2"
              @submit.prevent="submitOptions"
            >
              <div class="border border-gray-300 rounded-md overflow-hidden">
                <div class="px-4 py-5 bg-white sm:p-6">
                  <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6">
                      <jet-label
                        for="charge_style"
                        value="Formato de cobrança"
                      />

                      <select
                        id="charge_style"
                        v-model="optionsForm.charge_style"
                        name="charge_style"
                        class="
                          mt-1
                          block
                          w-full
                          pl-3
                          pr-10
                          py-2
                          border-gray-300
                          focus:outline-none
                          focus:ring-speedapp-orange-500
                          focus:border-speedapp-orange-500
                          rounded-md
                          shadow-sm
                        "
                      >
                        <option
                          v-for="(label, key) in chargeStyleOptions"
                          :key="key"
                          :value="key"
                        >
                          {{ label }}
                        </option>
                      </select>

                      <jet-input-error
                        :message="optionsForm.errors.charge_style"
                        class="mt-2"
                      />
                    </div>

                    <div class="col-span-6">
                      <jet-label
                        for="receipt_confirmation"
                        value="Confirmação de entrega"
                      />

                      <select
                        id="receipt_confirmation"
                        v-model="optionsForm.receipt_confirmation"
                        name="receipt_confirmation"
                        class="
                          mt-1
                          block
                          w-full
                          pl-3
                          pr-10
                          py-2
                          border-gray-300
                          focus:outline-none
                          focus:ring-speedapp-orange-500
                          focus:border-speedapp-orange-500
                          rounded-md
                          shadow-sm
                        "
                      >
                        <option
                          v-for="(label, key) in receiptConfirmationOptions"
                          :key="key"
                          :value="key"
                        >
                          {{ label }}
                        </option>
                      </select>

                      <jet-input-error
                        :message="optionsForm.errors.receipt_confirmation"
                        class="mt-2"
                      />
                    </div>

                    <div class="col-span-6">
                      <jet-label
                        for="delivery_constraint"
                        value="Bloqueio de entregas"
                      />

                      <select
                        id="delivery_constraint"
                        v-model="optionsForm.delivery_constraint"
                        name="delivery_constraint"
                        class="
                          mt-1
                          block
                          w-full
                          pl-3
                          pr-10
                          py-2
                          border-gray-300
                          focus:outline-none
                          focus:ring-speedapp-orange-500
                          focus:border-speedapp-orange-500
                          rounded-md
                          shadow-sm
                        "
                      >
                        <option value="OPEN">
                          Pode aceitar outras entregas
                        </option>
                        <option value="PARTIAL">
                          Somente entregas do mesmo estabelecimento
                        </option>
                        <option value="BLOCK">
                          Não pode aceitar outras entregas
                        </option>
                      </select>

                      <jet-input-error
                        :message="optionsForm.errors.delivery_constraint"
                        class="mt-2"
                      />
                    </div>

                    <div class="col-span-6 md:col-span-3">
                      <jet-label
                        for="return_fee_paid"
                        value="Taxa de retorno (pago)"
                      />
                      <jet-input
                        id="return_fee_paid"
                        v-model="optionsForm.return_fee_paid"
                        v-money3="{
                          prefix: 'R$ ',
                          suffix: '',
                          thousands: '.',
                          decimal: ',',
                          precision: 2,
                          disableNegative: true,
                          disabled: false,
                          min: null,
                          max: null,
                          allowBlank: false,
                          minimumNumberOfCharacters: 0,
                        }"
                        type="text"
                        class="mt-1 block w-full"
                      />
                      <jet-input-error
                        :message="optionsForm.errors.return_fee_paid"
                        class="mt-2"
                      />
                    </div>

                    <div class="col-span-6 md:col-span-3">
                      <jet-label
                        for="return_fee_charged"
                        value="Taxa de retorno (cobrado)"
                      />
                      <jet-input
                        id="return_fee_charged"
                        v-model="optionsForm.return_fee_charged"
                        v-money3="{
                          prefix: 'R$ ',
                          suffix: '',
                          thousands: '.',
                          decimal: ',',
                          precision: 2,
                          disableNegative: true,
                          disabled: false,
                          min: null,
                          max: null,
                          allowBlank: false,
                          minimumNumberOfCharacters: 0,
                        }"
                        type="text"
                        class="mt-1 block w-full"
                      />
                      <jet-input-error
                        :message="optionsForm.errors.return_fee_charged"
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
                    :class="{
                      'opacity-25': optionsForm.processing,
                    }"
                    :disabled="optionsForm.processing"
                  >
                    Salvar
                  </button>
                </div>
              </div>
            </form>

            <form
              v-if="tabIndex === 3"
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
                        class="
                          divide-y divide-gray-200
                          border border-gray-300
                          rounded-md
                          mt-2
                        "
                      >
                        <li
                          v-for="(city, i) in availableCities"
                          :key="i"
                          class="p-2 cursor-pointer select-none"
                          @click="addCity(city)"
                        >
                          <span class="p-2 text-gray-500">{{ city.name }}</span>
                        </li>
                      </ul>

                      <span
                        v-else
                        class="
                          block
                          text-center
                          rounded-md
                          mt-4
                          p-2
                          text-gray-500
                        "
                      >
                        Nenhuma cidade disponível
                      </span>
                    </div>

                    <div class="col-span-1 p-2">
                      <span class="block font-medium text-sm text-gray-700">Cidades ativas</span>

                      <ul
                        v-if="selectedCities.length > 0"
                        role="list"
                        class="
                          divide-y divide-gray-200
                          border border-gray-300
                          rounded-md
                          mt-2
                        "
                      >
                        <li
                          v-for="(city, i) in selectedCities"
                          :key="i"
                          class="p-2 cursor-pointer select-none"
                          @click="removeCity(city)"
                        >
                          <span class="p-2 text-gray-500">{{ city.name }}</span>
                        </li>
                      </ul>

                      <span
                        v-else
                        class="
                          block
                          text-center
                          rounded-md
                          mt-4
                          p-2
                          text-gray-500
                        "
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

            <div v-if="tabIndex === 4">
              <user-address
                :user="user"
                @set-status="setStatus"
                @enable-tab="enableTab"
              />
            </div>

            <div v-if="tabIndex === 5">
              <user-area
                :user="user"
                @set-status="setStatus"
              />
            </div>

            <div v-if="tabIndex === 6">
              <user-report
                :user="user"
                @set-status="setStatus"
              />
            </div>

            <form
              v-if="tabIndex === 7"
              @submit.prevent="submitDrivers"
            >
              <div class="border border-gray-300 rounded-md overflow-hidden">
                <div class="px-4 py-5 bg-white sm:p-6">
                  <div class="grid grid-cols-2">
                    <div class="col-span-1 p-2">
                      <span class="block font-medium text-sm text-gray-700">Entregadores disponíveis</span>

                      <ul
                        v-if="availableDrivers.length > 0"
                        role="list"
                        class="
                          divide-y divide-gray-200
                          border border-gray-300
                          rounded-md
                          mt-2
                        "
                      >
                        <li
                          v-for="(driver, i) in availableDrivers"
                          :key="i"
                          class="p-2 cursor-pointer select-none"
                          @click="addDriver(driver)"
                        >
                          <span class="p-2 text-gray-500">{{ driver.full_name }}</span>
                        </li>
                      </ul>

                      <span
                        v-else
                        class="
                          block
                          text-center
                          rounded-md
                          mt-4
                          p-2
                          text-gray-500
                        "
                      >
                        Nenhum entregador disponível
                      </span>
                    </div>

                    <div class="col-span-1 p-2">
                      <span class="block font-medium text-sm text-gray-700">Entregadores ativos</span>

                      <ul
                        v-if="selectedDrivers.length > 0"
                        role="list"
                        class="
                          divide-y divide-gray-200
                          border border-gray-300
                          rounded-md
                          mt-2
                        "
                      >
                        <li
                          v-for="(driver, i) in selectedDrivers"
                          :key="i"
                          class="p-2 cursor-pointer select-none"
                          @click="removeDriver(driver)"
                        >
                          <span class="p-2 text-gray-500">{{ driver.full_name }}</span>
                        </li>
                      </ul>

                      <span
                        v-else
                        class="
                          block
                          text-center
                          rounded-md
                          mt-4
                          p-2
                          text-gray-500
                        "
                      >
                        Nenhum entregador disponível
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
import { computed, defineComponent, ref, watch } from "vue";
import DeveloperLayout from "@/Layouts/DeveloperLayout.vue";
import UserArea from "@/Pages/Developer/Users/Area.vue";
import UserAddress from "@/Pages/Developer/Users/Address.vue";
import UserReport from "@/Pages/Developer/Users/Report.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetFormSection from "@/Jetstream/FormSection.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import { Link, useForm } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import differenceBy from "lodash/differenceBy";
import { Switch, SwitchGroup, SwitchLabel } from "@headlessui/vue";

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
    UserArea,
    UserAddress,
    UserReport,
    CheckCircleIcon,
  },

  props: {
    cities: {
      type: Array,
      default: () => [],
    },
    drivers: {
      type: Array,
      default: () => [],
    },
    user: {
      type: Object,
      required: true,
    },
    chargeStyleOptions: {
      type: Object,
      default: () => {},
    },
    receiptConfirmationOptions: {
      type: Object,
      default: () => {},
    },
  },

  setup(props) {
    const status = ref(null);

    const params = new Proxy(new URLSearchParams(window.location.search), {
      get: (searchParams, prop) => searchParams.get(prop),
    });
    
    const tabs = ref([
      {
          name: "Conta",
          enabled: true,
        },
        {
          name: "Senha",
          enabled: true,
        },
        {
          name: "Opções",
          enabled: true,
        },
        {
          name: "Cidades",
          enabled: true,
        },
        {
          name: "Localização",
          enabled: true,
        },
        {
          name: "Áreas de entrega",
          enabled: props.user.address !== null,
        },
        {
          name: "Financeiro",
          enabled: true,
        },
        {
          name: "Entregadores",
          enabled: props.user.lastmile,
        },
    ]);

    const tabIndex = ref(params.tab && tabs.value[params.tab] && tabs.value[params.tab].enabled ? parseInt(params.tab) : 0);

    watch(tabIndex, (n, o) => {
      const searchParams = new URLSearchParams(window.location.search)
      searchParams.set('tab', n);
      const newRelativePathQuery = window.location.pathname + '?' + searchParams.toString();
      history.pushState(null, '', newRelativePathQuery);
    });

    const selectedCities = ref(props.user.cities);
      const selectedDrivers = ref(props.user.drivers);

      const availableCities = computed(() => {
        return differenceBy(props.cities, props.selectedCities, "id");
      });

      const availableDrivers = computed(() => {
          return differenceBy(props.drivers, props.selectedDrivers, "id");
      });

    const form = useForm({
      _method: "PUT",
      city_id: props.user.city_id,
      name: props.user.name,
      email: props.user.email,
      phone: props.user.phone,
      cpf_cnpj: props.user.cpf_cnpj,
      company_type: props.user.company_type,
      info: props.user.info,
    });

    const passwordForm=  useForm({
      _method: "PUT",
        password: "",
    });

    const optionsForm = useForm({
       _method: "PUT",
        charge_style: props.user.charge_style,
        receipt_confirmation: props.user.receipt_confirmation,
        delivery_constraint: props.user.delivery_constraint,
        return_fee_paid: props.user.return_fee_paid,
        return_fee_charged: props.user.return_fee_charged,
    });
    const addressForm = useForm({
      _method: "PUT",
        position: null,
        street_number: props.user.address?.street_number ?? "",
        street_name: props.user.address?.street_name ?? "",
        district: props.user.address?.district ?? "",
        city: props.user.address?.city ?? "",
        state: props.user.address?.state ?? "",
    });

    const enableTab = (index, enable = true) => {
      tabs.value[index].enabled = enable;
    }

    const setStatus = (stts) => {
       status.value = stts;

        Inertia.reload();

        setTimeout(() => {
          status.value = null;
        }, 2000);
    }

    const submit = () => {
      form.post(route('developer.users.update', props.user.id), {
        preserveScroll: true,
        onSuccess: (res) => {
          setStatus(res.props.status)
        }
      })
    }

     const submitPassword=() =>{
      passwordForm.post(
        route("developer.users.updatePassword", props.user.id),
        {
          preserveScroll: true,
          onSuccess: (res) => {
            setStatus(res.props.status);
          },
        }
      );
    }

    const submitOptions =() =>{
      optionsForm.post(
        route("developer.users.updateOptions", props.user.id),
        {
          preserveScroll: true,
          onSuccess: (res) => {
            setStatus(res.props.status);
            enableTab(7, props.user.lastmile);
          },
        }
      );
    }

    const submitCities = () =>{
      Inertia.put(
        route("developer.users.updateCities", props.user.id),
        {
          cities: selectedCities.value.map((c) => c.id),
        },
        {
          preserveScroll: true,
          onSuccess: (res) => {
            setStatus(res.props.status);
          },
        }
      );
    }

    const submitDrivers= () =>{
      Inertia.put(
        route("developer.users.updateDrivers", props.user.id),
        {
          drivers: selectedDrivers.value.map((c) => c.id),
        },
        {
          preserveScroll: true,
          onSuccess: (res) => {
            setStatus(res.props.status);
          },
        }
      );
    }

     const addCity = (city) => {
      if (!selectedCities.value.includes(city)) {
        selectedCities.value.push(city);
      }
    }

    const removeCity = (city)  =>{
      selectedCities.value.splice(selectedCities.value.indexOf(city), 1);
    }

    const addDriver =(driver) =>{
      if (!selectedDrivers.value.includes(driver)) {
        selectedDrivers.value.push(driver);
      }
    }

    const removeDriver=(driver) =>{
      selectedDrivers.value.splice(selectedDrivers.value.indexOf(driver), 1);
    }

    return {
      status,
      tabIndex,
      tabs,
      selectedCities,
      selectedDrivers,
      availableCities,
      availableDrivers,
      form,
      passwordForm,
      optionsForm,
      addressForm,
      enableTab,
      setStatus,
      submit,
      submitPassword,
      submitOptions,
      submitCities,
      submitDrivers,
      addCity ,
      removeCity ,
      addDriver ,
      removeDriver,
    }
  },
});
</script>
