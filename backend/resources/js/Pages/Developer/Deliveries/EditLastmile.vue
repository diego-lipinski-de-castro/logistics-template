<template>
  <developer-layout title="Editar entrega">
    <template #header>
      <div class="flex justify-between items-center">
        <h2
          class="
            font-semibold
            text-xl text-gray-800
            
            leading-tight
          "
        >
          Editar entrega
        </h2>

        <div>
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
            <form @submit.prevent="submitStatus">
              <div class="border border-gray-300 rounded-md overflow-auto">
                <div class="px-4 py-5 bg-white sm:p-6">
                  <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6">
                      <div class="relative">
                        <div
                          class="absolute inset-0 flex items-center"
                          aria-hidden="true"
                        >
                          <div class="w-full border-t border-gray-300" />
                        </div>
                        <div class="relative flex justify-start">
                          <span
                            class="
                              pr-3
                              bg-white
                              text-lg
                              font-medium
                              text-gray-900
                            "
                          >
                            Status
                          </span>
                        </div>
                      </div>
                    </div>

                    <div class="col-span-6">
                      <div>
                        <label
                          for="user"
                          class="block text-sm font-medium text-gray-700"
                        >Selecione um status</label>

                        <select
                          id="status"
                          v-model="selectedStatus"
                          class="
                            mt-1
                            block
                            w-full
                            pl-3
                            pr-10
                            py-2
                            text-base
                            border-gray-300
                            focus:outline-none
                            focus:ring-speedapp-orange-500
                            focus:border-speedapp-orange-500
                            sm:text-sm
                            rounded-md
                          "
                        >
                          <option
                            v-for="(label, key) in statuses"
                            :key="key"
                            :value="key"
                            :class="[getStatusColor(key)]"
                          >
                            {{ label }}
                          </option>
                        </select>
                      </div>
                    </div>
                  </div>
                </div>
                <div
                  v-if="selectedStatus !== delivery.status"
                  class="px-4 py-3 bg-gray-50 text-right sm:px-6"
                >
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
          </div>

          <div
            class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8"
          >
            <form @submit.prevent>
              <div class="border border-gray-300 rounded-md">
                <div class="px-4 py-5 bg-white sm:p-6">
                  <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6">
                      <div class="relative">
                        <div
                          class="absolute inset-0 flex items-center"
                          aria-hidden="true"
                        >
                          <div class="w-full border-t border-gray-300" />
                        </div>
                        <div class="relative flex justify-start">
                          <span
                            class="
                              pr-3
                              bg-white
                              text-lg
                              font-medium
                              text-gray-900
                            "
                          >
                            Entregador
                          </span>
                        </div>
                      </div>
                    </div>

                    <div class="col-span-6">
                      <Listbox
                        v-model="selectedDriver"
                        as="div"
                        style="overflow: visible"
                      >
                        <ListboxLabel
                          class="block text-sm font-medium text-gray-700"
                        >
                          Selecione um entregador
                        </ListboxLabel>

                        <div class="mt-1 relative">
                          <ListboxButton
                            class="
                              relative
                              w-full
                              bg-white
                              border border-gray-300
                              rounded-md
                              shadow-sm
                              pl-3
                              pr-10
                              py-2
                              text-left
                              cursor-default
                              focus:outline-none
                              focus:ring-1
                              focus:ring-speedapp-orange-500
                              focus:border-speedapp-orange-500
                              sm:text-sm
                            "
                          >
                            <div class="flex items-center">
                              <span
                                :class="[
                                  'shrink-0 inline-block h-2 w-2 rounded-full',
                                  {
                                    'bg-gray-200': !selectedDriver,
                                    'bg-green-400':
                                      selectedDriver &&
                                      selectedDriver.metadata.status ===
                                      'ONLINE',
                                    'bg-yellow-500':
                                      selectedDriver &&
                                      selectedDriver.metadata.status === 'BUSY',
                                    'bg-red-700':
                                      selectedDriver &&
                                      selectedDriver.metadata.status ===
                                      'OFFLINE',
                                  },
                                ]"
                              />
                              <span class="ml-3 block truncate">{{
                                selectedDriver?.full_name ?? "Nenhum"
                              }}</span>
                              <span
                                v-if="selectedDriver?.city"
                                class="ml-2 truncate text-gray-500"
                              >{{ selectedDriver.city.name }}</span>
                            </div>

                            <span
                              class="
                                absolute
                                inset-y-0
                                right-0
                                flex
                                items-center
                                pr-2
                                pointer-events-none
                              "
                            >
                              <SelectorIcon
                                class="h-5 w-5 text-gray-400"
                                aria-hidden="true"
                              />
                            </span>
                          </ListboxButton>

                          <transition
                            leave-active-class="transition ease-in duration-100"
                            leave-from-class="opacity-100"
                            leave-to-class="opacity-0"
                          >
                            <ListboxOptions
                              class="
                                absolute
                                z-10
                                mt-1
                                w-full
                                bg-white
                                shadow-lg
                                max-h-60
                                rounded-md
                                py-1
                                text-base
                                ring-1 ring-black ring-opacity-5
                                overflow-auto
                                focus:outline-none
                                sm:text-sm
                              "
                            >
                              <ListboxOption
                                v-slot="{ active, selected }"
                                as="template"
                                :value="null"
                              >
                                <li
                                  :class="[
                                    active
                                      ? 'text-white bg-speedapp-orange-600'
                                      : 'text-gray-900',
                                    'cursor-default select-none relative py-2 pl-3 pr-9',
                                  ]"
                                >
                                  <div class="flex items-center">
                                    <span
                                      class="
                                        shrink-0
                                        inline-block
                                        h-2
                                        w-2
                                        rounded-full
                                        bg-gray-200
                                      "
                                      aria-hidden="true"
                                    />
                                    <span
                                      :class="[
                                        !selected
                                          ? 'font-semibold'
                                          : 'font-normal',
                                        'ml-3 block truncate',
                                      ]"
                                    >
                                      Nenhum
                                    </span>
                                  </div>
                                </li>
                              </ListboxOption>

                              <ListboxOption
                                v-for="(driver, index) in drivers"
                                :key="index"
                                v-slot="{ active, selected }"
                                as="template"
                                :value="driver"
                              >
                                <li
                                  :class="[
                                    active
                                      ? 'text-white bg-speedapp-orange-600'
                                      : 'text-gray-900',
                                    'cursor-default select-none relative py-2 pl-3 pr-9',
                                  ]"
                                >
                                  <div class="flex items-center">
                                    <span
                                      :class="[
                                        'shrink-0 inline-block h-2 w-2 rounded-full',
                                        {
                                          'bg-green-400':
                                            driver.metadata.status === 'ONLINE',
                                          'bg-yellow-500':
                                            driver.metadata.status === 'BUSY',
                                          'bg-red-700':
                                            driver.metadata.status ===
                                            'OFFLINE',
                                        },
                                      ]"
                                      aria-hidden="true"
                                    />
                                    <span
                                      :class="[
                                        selected
                                          ? 'font-semibold'
                                          : 'font-normal',
                                        'ml-3 block truncate',
                                      ]"
                                    >
                                      {{ driver.full_name }}
                                      <span class="sr-only">
                                        is
                                        {{
                                          driver.metadata.formatted_status
                                        }}</span>
                                    </span>

                                    <span
                                      v-if="driver.city"
                                      :class="[
                                        active
                                          ? 'text-speedapp-orange-200'
                                          : 'text-gray-500',
                                        'ml-2 truncate',
                                      ]"
                                    >
                                      {{ driver.city.name }}
                                    </span>
                                  </div>
                                </li>
                              </ListboxOption>
                            </ListboxOptions>
                          </transition>
                        </div>
                      </Listbox>
                    </div>
                  </div>
                </div>

                <!-- change this if -->
                <div
                  v-if="
                    (delivery?.driver == null && selectedDriver !== null) ||
                      (delivery.driver !== selectedDriver &&
                        delivery?.driver.id !== selectedDriver?.id)
                  "
                  class="px-4 py-3 bg-gray-50 text-right sm:px-6"
                >
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
                    @click="submitDriver($event, true)"
                  >
                    Salvar e redisparar

                    <template
                      v-if="selectedDriver === null && delivery.driver !== null"
                    >
                      para todos
                    </template>

                    <template v-else>
                      para {{ selectedDriver.full_name }}
                    </template>
                  </button>

                  <button
                    type="submit"
                    class="
                      ml-2
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
                    @click="submitDriver"
                  >
                    Salvar
                  </button>
                </div>
              </div>
            </form>
          </div>

          <div
            class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8"
          >
            <form @submit.prevent="submitAddress">
              <div class="border border-gray-300 rounded-md overflow-hidden">
                <div class="px-4 py-5 bg-white sm:p-6">
                  <div>
                    <span class="block font-medium text-sm text-gray-700">Tipo de entrega</span>

                    <RadioGroup
                      v-model="form.scheduled"
                      class="mt-1"
                    >
                      <div class="flex space-x-3">
                        <RadioGroupOption
                          v-slot="{ checked, active }"
                          as="template"
                          :value="false"
                        >
                          <div
                            :class="[
                              checked
                                ? 'border-transparent'
                                : 'border-gray-300',
                              active
                                ? 'border-indigo-500 ring-2 ring-indigo-500'
                                : '',
                              'relative bg-white border rounded-lg shadow-sm px-4 py-3 flex cursor-pointer focus:outline-none',
                            ]"
                          >
                            <div class="flex-1 flex">
                              <div class="flex flex-col">
                                <RadioGroupLabel
                                  as="span"
                                  class="
                                    block
                                    text-sm
                                    font-medium
                                    text-gray-900
                                  "
                                >
                                  Entrega imediata
                                </RadioGroupLabel>
                              </div>
                            </div>
                            <CheckCircleIcon
                              :class="[
                                !checked ? 'text-gray-300' : 'text-indigo-600',
                                'h-5 w-5 ml-2',
                              ]"
                              aria-hidden="true"
                            />
                            <div
                              :class="[
                                active ? 'border' : 'border-2',
                                checked
                                  ? 'border-indigo-500'
                                  : 'border-transparent',
                                'absolute -inset-px rounded-lg pointer-events-none',
                              ]"
                              aria-hidden="true"
                            />
                          </div>
                        </RadioGroupOption>

                        <RadioGroupOption
                          v-slot="{ checked, active, disabled }"
                          as="template"
                          :value="true"
                        >
                          <div
                            :class="[
                              disabled ? 'opacity-50' : '',
                              checked
                                ? 'border-transparent'
                                : 'border-gray-300',
                              active
                                ? 'border-indigo-500 ring-2 ring-indigo-500'
                                : '',
                              'relative bg-white border rounded-lg shadow-sm px-4 py-3 flex cursor-pointer focus:outline-none',
                            ]"
                          >
                            <div class="flex-1 flex">
                              <div class="flex flex-col">
                                <RadioGroupLabel
                                  as="span"
                                  class="
                                    block
                                    text-sm
                                    font-medium
                                    text-gray-900
                                  "
                                >
                                  Entrega agendada
                                </RadioGroupLabel>
                              </div>
                            </div>
                            <CheckCircleIcon
                              :class="[
                                !checked ? 'text-gray-300' : 'text-indigo-600',
                                'h-5 w-5 ml-2',
                              ]"
                              aria-hidden="true"
                            />
                            <div
                              :class="[
                                active ? 'border' : 'border-2',
                                checked
                                  ? 'border-indigo-500'
                                  : 'border-transparent',
                                'absolute -inset-px rounded-lg pointer-events-none',
                              ]"
                              aria-hidden="true"
                            />
                          </div>
                        </RadioGroupOption>

                        <input
                          id="scheduled_at"
                          v-model="form.scheduled_at"
                          :disabled="!form.scheduled"
                          type="datetime-local"
                          name="scheduled_at"
                          class="
                            shadow-sm
                            focus:ring-indigo-500 focus:border-indigo-500
                            sm:text-sm
                            border-gray-300
                            rounded-md
                            disabled:opacity-50
                          "
                        >
                      </div>
                    </RadioGroup>
                    <jet-input-error
                      :message="form.errors.scheduled_at"
                      class="mt-2"
                    />
                  </div>

                  <div class="flex mt-6">
                    <div>
                      <span class="block font-medium text-sm text-gray-700">Valor</span>
                      <jet-input
                        id="formatted_paid"
                        v-model="form.formatted_paid"
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
                        class="block mt-1"
                      />
                      <jet-input-error
                        :message="form.errors.formatted_paid"
                        class="mt-2"
                      />
                    </div>

                    <div class="ml-4">
                      <span class="block font-medium text-sm text-gray-700">Markup</span>
                      <jet-input
                        id="formatted_charged"
                        v-model="form.formatted_charged"
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
                        class="block mt-1"
                      />
                      <jet-input-error
                        :message="form.errors.formatted_charged"
                        class="mt-2"
                      />
                    </div>
                  </div>

                  <div class="mt-6 flex flex-col">
                    <span class="block font-medium text-sm text-gray-700">Endereço de coleta</span>

                    <span
                      v-show="!form.custom_address"
                      class="block text-gray-500 mt-1"
                    >
                      {{ delivery.steps[0].formatted_address }}.
                      <button
                        type="button"
                        class="text-blue-500 hover:text-blue-700"
                        @click="form.custom_address = true"
                      >
                        Clique aqui para escolher outro endereço
                      </button>
                    </span>
                  </div>

                  <div
                    v-show="form.custom_address"
                    class="mt-1 grid grid-cols-6 gap-3"
                  >
                    <div class="col-span-6">
                      <input
                        id="address"
                        ref="autocompleteInput"
                        type="text"
                        role="presentation"
                        autocomplete="off"
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
                        @keydown.enter.prevent
                      >
                      <button
                        type="button"
                        class="
                          mt-1
                          ml-1
                          text-sm text-blue-500
                          hover:text-blue-700
                        "
                        @click="form.custom_address = false"
                      >
                        Clique aqui para usar seu endereço
                      </button>
                    </div>

                    <div class="col-span-6 md:col-span-1">
                      <jet-label
                        for="street_number"
                        value="Número"
                      />
                      <jet-input
                        id="street_number"
                        v-model="form.street_number"
                        type="text"
                        class="mt-1 block w-full"
                        @keydown.enter.prevent
                        @change="updateMap"
                      />
                      <jet-input-error
                        :message="form.errors.street_number"
                        class="mt-2"
                      />
                    </div>

                    <div class="col-span-6 md:col-span-3">
                      <jet-label
                        for="street_name"
                        value="Rua"
                      />
                      <jet-input
                        id="street_name"
                        type="text"
                        class="mt-1 block w-full opacity-50"
                        :value="form.street_name"
                        disabled
                      />
                      <jet-input-error
                        :message="form.errors.street_name"
                        class="mt-2"
                      />
                    </div>

                    <div class="col-span-6 md:col-span-2">
                      <jet-label
                        for="district"
                        value="Bairro"
                      />
                      <jet-input
                        id="district"
                        type="text"
                        class="mt-1 block w-full opacity-50"
                        :value="form.district"
                        disabled
                      />
                      <jet-input-error
                        :message="form.errors.district"
                        class="mt-2"
                      />
                    </div>

                    <div class="col-span-6 md:col-span-3">
                      <jet-label
                        for="city"
                        value="Cidade"
                      />
                      <jet-input
                        id="city"
                        type="text"
                        class="mt-1 block w-full opacity-50"
                        :value="form.city"
                        disabled
                      />
                      <jet-input-error
                        :message="form.errors.city"
                        class="mt-2"
                      />
                    </div>

                    <div class="col-span-6 md:col-span-3">
                      <jet-label
                        for="state"
                        value="Estado"
                      />
                      <jet-input
                        id="state"
                        type="text"
                        class="mt-1 block w-full opacity-50"
                        :value="form.state"
                        disabled
                      />
                      <jet-input-error
                        :message="form.errors.state"
                        class="mt-2"
                      />
                    </div>

                    <div class="col-span-6 mb-1">
                      <jet-label
                        for="info"
                        value="Complemento"
                      />
                      <jet-input
                        id="info"
                        v-model="form.info"
                        type="text"
                        class="mt-1 block w-full"
                      />
                      <jet-input-error
                        :message="form.errors.info"
                        class="mt-2"
                      />
                    </div>
                  </div>

                  <div class="mt-3 overflow-hidden rounded-md relative">
                    <div
                      id="map"
                      style="height: 30vh"
                    />

                    <div
                      v-if="loading"
                      class="
                        flex
                        justify-center
                        items-center
                        absolute
                        inset-0
                        bg-gray-50
                        opacity-50
                        z-[10000]
                      "
                    >
                      <Spinner />
                    </div>
                  </div>

                  <div
                    v-if="error"
                    class="-mt-2"
                  >
                    <span class="font-medium text-red-600">{{ error }}</span>
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
          </div>

          <div
            class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8"
          >
            <form @submit.prevent="submitInfo">
              <div class="border border-gray-300 rounded-md overflow-hidden">
                <div class="px-4 py-5 bg-white sm:p-6">
                  <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6 mt-5">
                      <div class="relative">
                        <div
                          class="absolute inset-0 flex items-center"
                          aria-hidden="true"
                        >
                          <div class="w-full border-t border-gray-300" />
                        </div>
                        <div class="relative flex justify-start">
                          <span
                            class="
                              pr-3
                              bg-white
                              text-lg
                              font-medium
                              text-gray-900
                            "
                          >
                            Observações
                          </span>
                        </div>
                      </div>
                    </div>

                    <div class="col-span-6">
                      <textarea
                        id="public_text"
                        v-model="form.public_text"
                        rows="5"
                        class="
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
                        :message="form.errors.public_text"
                        class="mt-2"
                      />
                    </div>

                    <!--  -->

                    <div class="col-span-6 mt-5">
                      <div class="relative">
                        <div
                          class="absolute inset-0 flex items-center"
                          aria-hidden="true"
                        >
                          <div class="w-full border-t border-gray-300" />
                        </div>
                        <div class="relative flex justify-start">
                          <span
                            class="
                              pr-3
                              bg-white
                              text-lg
                              font-medium
                              text-gray-900
                            "
                          >
                            Observações internas
                          </span>
                        </div>
                      </div>
                    </div>

                    <div class="col-span-6">
                      <textarea
                        id="private_text"
                        v-model="form.private_text"
                        rows="5"
                        class="
                          block
                          w-full
                          border-gray-300
                          focus:border-speedapp-orange-300
                          focus:ring
                          focus:ring-speedapp-orange-200
                          focus:ring-opacity-50
                          rounded-md
                          shadow-sm
                          disabled:opacity-50
                        "
                      />

                      <jet-input-error
                        :message="form.errors.private_text"
                        class="mt-2"
                      />
                    </div>

                    <!--  -->

                    <div class="col-span-6 mt-5">
                      <div class="relative">
                        <div
                          class="absolute inset-0 flex items-center"
                          aria-hidden="true"
                        >
                          <div class="w-full border-t border-gray-300" />
                        </div>
                        <div class="relative flex justify-start">
                          <span
                            class="
                              pr-3
                              bg-white
                              text-lg
                              font-medium
                              text-gray-900
                            "
                          >
                            Informações adicionais
                          </span>
                        </div>
                      </div>
                    </div>

                    <div class="col-span-6">
                      <textarea
                        id="additional_info"
                        v-model="form.additional_info"
                        rows="5"
                        class="
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
                        :message="form.errors.additional_info"
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
import { defineComponent, onMounted, ref, watch, computed } from "vue";
import DeveloperLayout from "@/Layouts/DeveloperLayout.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetFormSection from "@/Jetstream/FormSection.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import { Link, useForm } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";

import {
  Listbox,
  ListboxButton,
  ListboxLabel,
  ListboxOption,
  ListboxOptions,
  RadioGroup,
  RadioGroupDescription,
  RadioGroupLabel,
  RadioGroupOption,
} from "@headlessui/vue";
import { SelectorIcon, CheckCircleIcon } from "@heroicons/vue/solid";

import { format, unformat } from "v-money3";

import L from "leaflet";
import "leaflet-draw/dist/leaflet.draw";
import "leaflet/dist/leaflet.css";
import "leaflet-draw/dist/leaflet.draw.css";
import "leaflet.gridlayer.googlemutant";

import startIcon from "@/assets/images/start-icon.svg";

import mapStyle from "@/assets/map-style.json";


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
    Listbox,
    ListboxButton,
    ListboxLabel,
    ListboxOption,
    ListboxOptions,
    SelectorIcon,
    CheckCircleIcon,
    RadioGroup,
    RadioGroupDescription,
    RadioGroupLabel,
    RadioGroupOption,
  },

  props: {
    delivery: {
      type: Object,
      required: true,
    },
    statuses: {
      type: Object,
      default: () => {},
    },
    drivers: {
      type: Array,
      default: () => [],
    },
  },

  setup(props) {
    const status = ref(null);

    let autocompleteInitialized = false;
    let map = null;
    let autocomplete = null;
    let geocoder = null;

    let autocompleteInput = ref(null);

    let targetMarker = null;

    let center = [
      props.delivery.steps[0].location.coordinates[1],
      props.delivery.steps[0].location.coordinates[0],
    ];

    const selectedStatus = ref(props.delivery.status);
    const selectedDriver = ref(props.delivery.driver);

    const loading = ref(false);
    const error = ref(null);

    let scheduledAt;

    if (props.delivery.scheduled_at !== null) {
      scheduledAt = new Date(props.delivery.scheduled_at);
      scheduledAt.setHours(scheduledAt.getHours() - 3);
      scheduledAt = scheduledAt.toISOString().slice(0, -1);
    }

    const form = useForm({
      scheduled: props.delivery.scheduled_at !== null,
      scheduled_at: props.delivery.scheduled_at !== null ? scheduledAt : null,

      custom_address: false,

      position: [
        props.delivery.steps[0].location.coordinates[0],
        props.delivery.steps[0].location.coordinates[0],
      ],

      street_number: props.delivery.steps[0].street_number,
      street_name: props.delivery.steps[0].street_name,
      district: props.delivery.steps[0].district,
      city: props.delivery.steps[0].city,
      state: props.delivery.steps[0].state,

      info: props.delivery.steps[0].info,

      paid: props.delivery.paid,
      charged: props.delivery.charged,

      formatted_paid: props.delivery.formatted_paid,
      formatted_charged: props.delivery.formatted_charged,

      public_text: props.delivery.public_text,
      private_text: props.delivery.private_text,
      additional_info: props.delivery.additional_info,
    });

    const resetFormAddress = () => {
      form.position = null;
      form.street_number = "";
      form.street_name = "";
      form.district = "";
      form.city = "";
      form.state = "";

      form.info = "";

      error.value = null;
    };

    const submitStatus = () => {
      Inertia.post(
        route("developer.deliveries.update", [props.delivery.id, "status"]),
        {
          _method: "PUT",
          status: selectedStatus.value,
        },
        {
          preserveScroll: true,
          onSuccess: (res) => {
            setStatus(res.props.status);
          },
        }
      );
    };

    const submitDriver = ($event, redispatch = false) => {
      Inertia.post(
        route("developer.deliveries.update", [props.delivery.id, "driver"]),
        {
          _method: "PUT",
          driver: selectedDriver.value ? selectedDriver.value.id : null,
          redispatch: redispatch,
        },
        {
          preserveScroll: true,
          onSuccess: (res) => {
            setStatus(res.props.status);
          },
        }
      );
    };

    const submitAddress = () => {
      Inertia.post(
        route("developer.deliveries.update", [props.delivery.id, "address"]),
        {
          _method: "PUT",

          scheduled: form.scheduled,
          scheduled_at: form.scheduled_at,

          // 
          custom_address: form.custom_address,
          position: form.position,
          street_number: form.street_number,
          street_name: form.street_name,
          district: form.district,
          city: form.city,
          state: form.state,
          info: form.info,
          // 

          paid: form.formatted_paid == null ? 0 : unformat(form.formatted_paid),
          charged:
            form.formatted_charged == null
              ? 0
              : unformat(form.formatted_charged),
        },
        {
          preserveScroll: true,
          onSuccess: (res) => {
            setStatus(res.props.status);
          },
        }
      );
    };

    const submitInfo = () => {
      Inertia.post(
        route("developer.deliveries.update", [props.delivery.id, "info"]),
        {
          _method: "PUT",

          public_text: form.public_text,
          private_text: form.private_text,
          additional_info: form.additional_info,
        },
        {
          preserveScroll: true,
          onSuccess: (res) => {
            setStatus(res.props.status);
          },
        }
      );
    };

    const getStatusColor = (stts) => {
      if (stts === "WAITING") return "text-blue-500";

      if (stts === "PENDING") return "text-yellow-300";

      if (
        stts === "ACCEPTED" ||
        stts === "COLLECTING" ||
        stts === "DELIVERING" ||
        stts === "CONFIRMED" ||
        stts === "RETURNING"
      )
        return "text-speedapp-orange-500";

      if (stts === "COMPLETED") return "text-green-500";

      if (stts === "CANCELED") return "text-red-500";

      if (stts === "NOT_DELIVERED") return "text-pink-500";
    };

    const initMap = async () => {
      window.emitter.emit("initGoogleMaps", () => {
        map = L.map("map").setView(center, 14);

        L.gridLayer
          .googleMutant({
            type: "roadmap",
            styles:mapStyle,
          })
          .addTo(map);

        targetMarker = L.marker(center, {
          draggable: true,
          icon: L.icon({
            iconUrl: startIcon,
            iconSize: [40, 40],
            iconAnchor: [20, 20],
          }),
        })
          .bindPopup(
            L.popup({
              maxWidth: 200,
            }).setContent(`
          <div>
              <span>Endereço de coleta: </span> <br>
              <span><strong>${props.delivery.steps[0].formatted_address}</strong></span>
            </div>
          `)
          )
          .addTo(map);
      });
    };

    const initAutocomplete = () => {
      autocomplete = new window.google.maps.places.Autocomplete(
        autocompleteInput.value,
        {
          fields: [
            "formatted_address",
            "address_components",
            "geometry",
            "name",
          ],
          strictBounds: false,
        }
      );

      autocomplete.addListener("place_changed", () => {
        if (targetMarker !== null) {
          map.removeLayer(targetMarker);
          targetMarker = null;
        }

        const place = autocomplete.getPlace();

        setPlace(place);
      });

      geocoder = new window.google.maps.Geocoder();
    };

    const updateMap = async () => {
      if (!form.street_name || !form.street_number) return;

      loading.value = true;

      const { results } = await geocoder.geocode({
        address: `${form.street_name}, ${form.street_number} - ${form.district}, ${form.city} - ${form.state}`,
      });

      if (results.length == 0) {
        loading.value = false;
        return;
      }

      if (targetMarker !== null) {
        map.removeLayer(targetMarker);
        targetMarker = null;
      }

      const place = results[0];

      loading.value = false;

      setPlace(place);
    };

    const setPlace = (place) => {
      if (place === undefined) {
        console.log("Returned place is undefined");
        return;
      }

      if (!place.geometry || !place.geometry.location) {
        console.log("Returned place contains no geometry");
        return;
      }

      resetFormAddress();

      const lat = place.geometry.location.lat();
      const lng = place.geometry.location.lng();

      targetMarker = L.marker([lat, lng], {
        icon: L.icon({
          iconUrl: startIcon,
          iconSize: [40, 40],
          iconAnchor: [20, 20],
        }),
      }).addTo(map);

      map.setView([lat, lng], 14);

      form.position = [lat, lng];

      for (const component of place.address_components) {
        const type = component.types[0];

        switch (type) {
          case "street_number":
            form.street_number = component.long_name;
            break;
          case "route":
            form.street_name = component.long_name;
            break;
          case "political":
          case "sublocality":
          case "sublocality_level_1":
            form.district = component.long_name;
            break;
          case "administrative_area_level_2":
            form.city = component.long_name;
            break;
          case "administrative_area_level_1":
            form.state = component.long_name;
            break;
          default:
            break;
        }
      }
    };

    watch(
      () => form.custom_address,
      (n, o) => {
        if (!autocompleteInitialized) {
          autocompleteInitialized = true;

          initAutocomplete();
        }

        resetFormAddress();

        if (!n) {
          map.removeLayer(targetMarker);

          targetMarker = L.marker(center, {
            icon: L.icon({
              iconUrl: startIcon,
              iconSize: [40, 40],
              iconAnchor: [20, 20],
            }),
          }).addTo(map);

          map.setView(center, 14);
        }
      }
    );

    onMounted(initMap);

    const setStatus = (stts) => {
      status.value = stts;

      Inertia.reload();

      setTimeout(() => {
        status.value = null;
      }, 2000);
    };

    return {
      selectedDriver,
      selectedStatus,
      form,
      getStatusColor,
      submitStatus,
      submitDriver,
      submitAddress,
      submitInfo,
      status,
      error,
      updateMap,
      autocompleteInput,
      loading,
    };
  },
});
</script>
