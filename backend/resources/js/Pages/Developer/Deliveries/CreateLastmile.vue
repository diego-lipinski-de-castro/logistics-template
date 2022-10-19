<template>
  <form @submit.prevent="submit">
    <div class="border border-gray-300 rounded-md overflow-hidden">
      <div class="px-4 py-5 bg-white sm:p-6">
        <div class="">
          <span class="block font-medium text-sm text-gray-700"
            >Tipo de entrega</span
          >

          <RadioGroup v-model="form.scheduled" class="mt-1">
            <div class="flex space-x-3">
              <RadioGroupOption
                v-slot="{ checked, active }"
                as="template"
                :value="false"
              >
                <div
                  :class="[
                    checked ? 'border-transparent' : 'border-gray-300',
                    active ? 'border-indigo-500 ring-2 ring-indigo-500' : '',
                    'relative bg-white border rounded-lg shadow-sm px-4 py-3 flex cursor-pointer focus:outline-none',
                  ]"
                >
                  <div class="flex-1 flex">
                    <div class="flex flex-col">
                      <RadioGroupLabel
                        as="span"
                        class="block text-sm font-medium text-gray-900"
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
                      checked ? 'border-indigo-500' : 'border-transparent',
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
                    checked ? 'border-transparent' : 'border-gray-300',
                    active ? 'border-indigo-500 ring-2 ring-indigo-500' : '',
                    'relative bg-white border rounded-lg shadow-sm px-4 py-3 flex cursor-pointer focus:outline-none',
                  ]"
                >
                  <div class="flex-1 flex">
                    <div class="flex flex-col">
                      <RadioGroupLabel
                        as="span"
                        class="block text-sm font-medium text-gray-900"
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
                      checked ? 'border-indigo-500' : 'border-transparent',
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
              />
            </div>
          </RadioGroup>
          <jet-input-error :message="form.errors.scheduled_at" class="mt-2" />
        </div>

        <!--
        <br/>
        {{ selectedUser?.charge_style }}
        <br/>
        {{ selectedUser?.charge_options }}
        -->

        <div class="mt-6">
          <label
            for="user"
            class="block text-sm font-medium text-gray-700"
          >Selecione uma loja</label>

          <select
            id="user"
            v-model="selectedUser"
            class="
              mt-1
              block
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
              v-for="(user, index) in lastmileUsers"
              :key="index"
              :value="user"
            >
              {{ user.name }}
            </option>
          </select>
        </div>

        <div class="mt-6 w-1/3">
          <Listbox :disabled="selectedUser == null || userDrivers.length == 0" :class="{ 'opacity-50': selectedUser == null || userDrivers.length == 0 }" v-model="selectedDriver" as="div" style="overflow: visible">
            <ListboxLabel class="block text-sm font-medium text-gray-700">
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
                          selectedDriver.metadata.status === 'ONLINE',
                        'bg-yellow-500':
                          selectedDriver &&
                          selectedDriver.metadata.status === 'BUSY',
                        'bg-red-700':
                          selectedDriver &&
                          selectedDriver.metadata.status === 'OFFLINE',
                      },
                    ]"
                  />
                  <span class="ml-3 block truncate">{{
                    selectedDriver?.full_name ?? "-"
                  }}</span>
                  <span
                    v-if="selectedDriver && selectedDriver.city"
                    class="ml-2 truncate text-gray-500"
                    >{{ selectedDriver.city.name }}</span
                  >
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
                    v-for="(driver, index) in userDrivers"
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
                                driver.metadata.status === 'OFFLINE',
                            },
                          ]"
                          aria-hidden="true"
                        />
                        <span
                          :class="[
                            selected ? 'font-semibold' : 'font-normal',
                            'ml-3 block truncate',
                          ]"
                        >
                          {{ driver.full_name }}
                          <span class="sr-only">
                            is
                            {{ driver.metadata.formatted_status }}</span
                          >
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

                      <span
                        v-if="selected"
                        :class="[
                          active ? 'text-white' : 'text-speedapp-orange-600',
                          'absolute inset-y-0 right-0 flex items-center pr-4',
                        ]"
                      >
                        <CheckIcon class="h-5 w-5" aria-hidden="true" />
                      </span>
                    </li>
                  </ListboxOption>
                </ListboxOptions>
              </transition>
            </div>
          </Listbox>

          <span
            v-if="selectedUser && selectedUser.drivers_ids.length == 0"
            class="block mt-1.5 ml-1.5 text-sm text-gray-500"
          >
            Nenhum entregador foi selecionado para essa loja. <a :href="route('developer.users.edit', { user: selectedUser.id, tab: 7 })" class="text-blue-500 hover:text-blue-700" target="_blank">Clique aqui para adicionar.</a>
          </span>
        </div>

        <div class="mt-6">
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

          <span v-if="selectedUser !== null" class="block text-sm text-gray-500 mt-2 ml-2">
            Markup: {{ selectedUser.charge_options.markup }}
          </span>

          <jet-input-error :message="form.errors.formatted_paid" class="mt-2" />
        </div>

        <div class="mt-6 flex flex-col">
          <span class="block font-medium text-sm text-gray-700"
            >Endereço de coleta</span
          >

          <span v-if="selectedUser && !form.custom_address" class="block text-gray-500 mt-1">
            {{ selectedUser.address.formatted_address }}.
            <button
              type="button"
              class="text-blue-500 hover:text-blue-700"
              @click="form.custom_address = true"
            >
              Clique aqui para escolher outro endereço
            </button>
          </span>

          <span v-else-if="!selectedUser && !form.custom_address" class="block text-gray-500 mt-1">-</span>
        </div>

        <div v-show="form.custom_address" class="mt-1 grid grid-cols-6 gap-3">
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
            />
            <button
              type="button"
              class="mt-1 ml-1 text-sm text-blue-500 hover:text-blue-700"
              @click="form.custom_address = false"
            >
              Clique aqui para usar o endereço da loja
            </button>
          </div>

          <div class="col-span-6 md:col-span-1">
            <jet-label for="street_number" value="Número" />
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
            <jet-label for="street_name" value="Rua" />
            <jet-input
              id="street_name"
              type="text"
              class="mt-1 block w-full opacity-50"
              :value="form.street_name"
              disabled
            />
            <jet-input-error :message="form.errors.street_name" class="mt-2" />
          </div>

          <div class="col-span-6 md:col-span-2">
            <jet-label for="district" value="Bairro" />
            <jet-input
              id="district"
              type="text"
              class="mt-1 block w-full opacity-50"
              :value="form.district"
              disabled
            />
            <jet-input-error :message="form.errors.district" class="mt-2" />
          </div>

          <div class="col-span-6 md:col-span-3">
            <jet-label for="city" value="Cidade" />
            <jet-input
              id="city"
              type="text"
              class="mt-1 block w-full opacity-50"
              :value="form.city"
              disabled
            />
            <jet-input-error :message="form.errors.city" class="mt-2" />
          </div>

          <div class="col-span-6 md:col-span-3">
            <jet-label for="state" value="Estado" />
            <jet-input
              id="state"
              type="text"
              class="mt-1 block w-full opacity-50"
              :value="form.state"
              disabled
            />
            <jet-input-error :message="form.errors.state" class="mt-2" />
          </div>

          <div class="col-span-6 mb-1">
            <jet-label for="info" value="Complemento" />
            <jet-input
              id="info"
              v-model="form.info"
              type="text"
              class="mt-1 block w-full"
            />
            <jet-input-error :message="form.errors.info" class="mt-2" />
          </div>
        </div>

        <div class="mt-3 overflow-hidden rounded-md relative">
          <div id="map" style="height: 30vh" class="z-0" />

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

        <div v-if="error" class="-mt-2">
          <span class="font-medium text-red-600">{{ error }}</span>
        </div>

        <div class="mt-6">
          <span class="block font-medium text-sm text-gray-700"
            >Observações</span
          >
          <textarea
            id="public_text"
            v-model="form.public_text"
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

          <jet-input-error :message="form.errors.public_text" class="mt-2" />
        </div>

        <div class="mt-6">
          <span class="block font-medium text-sm text-gray-700"
            >Observações internas</span
          >
          <textarea
            id="private_text"
            v-model="form.private_text"
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

          <jet-input-error :message="form.errors.private_text" class="mt-2" />
        </div>

        <div class="mt-6">
          <span class="block font-medium text-sm text-gray-700"
            >Informações adicionais</span
          >
          <textarea
            id="additional_info"
            v-model="form.additional_info"
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

          <jet-input-error :message="form.errors.additional_info" class="mt-2" />
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
          Solicitar
        </button>
      </div>
    </div>
  </form>
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
  RadioGroup,
  RadioGroupDescription,
  RadioGroupLabel,
  RadioGroupOption,
  Listbox,
  ListboxButton,
  ListboxLabel,
  ListboxOption,
  ListboxOptions,
} from "@headlessui/vue";
import { CheckIcon, SelectorIcon, CheckCircleIcon } from "@heroicons/vue/solid";

import { format } from "v-money3";

import L from "leaflet";
import "leaflet/dist/leaflet.css";
import "leaflet.gridlayer.googlemutant";

import startIcon from "@/assets/images/start-icon.svg";

import mapStyle from "@/assets/map-style.json";


import debounce from "lodash/debounce";

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
    CheckIcon,
    SelectorIcon,
    CheckCircleIcon,
    RadioGroup,
    RadioGroupDescription,
    RadioGroupLabel,
    RadioGroupOption,
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
    result: {
      type: Object,
      required: false,
      default: () => null,
    },
  },

  setup(props) {
    let autocompleteInitialized = false;
    let map = null;
    let autocomplete = null;
    let geocoder = null;

    let autocompleteInput = ref(null);

    let targetMarker = null;

    let center = [-26.4822945, -49.0699353];

    const loading = ref(false);
    const error = ref(null);

    const selectedUser = ref(null);
    const selectedDriver = ref(null);

    const form = useForm({
      user: null,
      driver: null,

      scheduled: false,
      scheduled_at: null,

      custom_address: false,

      position: null,
      street_number: "",
      street_name: "",
      district: "",
      city: "",
      state: "",

      info: "",

      public_text: "",
      private_text: "",
      additional_info: "",

      formatted_paid: "",
    });

    const lastmileUsers = computed(() => {
      return props.users.filter(u => u.lastmile);
    });

    const userDrivers = computed(() => {

      if(selectedUser.value == null) {
        return [];
      }

      return props.drivers.filter(d => selectedUser.value.drivers_ids.includes(d.id));
    });

    watch(selectedDriver, (n, o) => {
      if (n === o) return;

      form.driver = n === null ? null : n.id;
    });

    watch(selectedUser, (n, o) => {
      if (n == o) return;

      selectedDriver.value = null;

      form.user = n.id;

      if (n === null) {
        return;
      }

      if (targetMarker !== null) {
        map.removeLayer(targetMarker);
        targetMarker = null;
      }

      const lat = n.address.position.coordinates[1];
      const lng = n.address.position.coordinates[0];

      targetMarker = L.marker([lat, lng], {
        icon: L.icon({
          iconUrl: startIcon,
          iconSize: [40, 40],
          iconAnchor: [20, 20],
        }),
      }).addTo(map);

      map.setView([lat, lng], 14);
    });

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

    const submit = () => {
      form.post(route("developer.request-delivery.store.lastmile"), {
        preserveScroll: true,
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

    const initMap = async () => {
      window.emitter.emit("initGoogleMaps", () => {
        map = L.map("map").setView(center, 14);

        L.gridLayer
          .googleMutant({
            type: "roadmap",
            styles: mapStyle,
          })
          .addTo(map);
      });
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

    onMounted(debounce(initMap, 100));

    return {
      selectedUser,
      selectedDriver,
      error,
      form,
      submit,
      updateMap,
      autocompleteInput,
      loading,
      userDrivers,
      lastmileUsers,
    };
  },
});
</script>