<template>
  <form @submit.prevent="submit">
    <div class="border border-gray-300 rounded-md overflow-hidden">
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
                <span class="pr-3 bg-white text-lg font-medium text-gray-900">
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
                              is {{ driver.metadata.formatted_status }}</span>
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
                          <CheckIcon
                            class="h-5 w-5"
                            aria-hidden="true"
                          />
                        </span>
                      </li>
                    </ListboxOption>
                  </ListboxOptions>
                </transition>
              </div>
            </Listbox>
          </div>

          <div class="col-span-6 mt-5">
            <div class="relative">
              <div
                class="absolute inset-0 flex items-center"
                aria-hidden="true"
              >
                <div class="w-full border-t border-gray-300" />
              </div>
              <div class="relative flex justify-start">
                <span class="pr-3 bg-white text-lg font-medium text-gray-900">
                  Endereço de coleta
                </span>
              </div>
            </div>
          </div>

          <div class="col-span-6">
            <div>
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
                  v-for="(user, index) in users"
                  :key="index"
                  :value="user"
                >
                  {{ user.name }}
                </option>
              </select>

              <span
                v-if="selectedUser"
                class="block mt-1.5 ml-1.5 text-sm text-gray-500"
              >
                {{ selectedUser.address.formatted_address }}
              </span>
            </div>
          </div>

          <div class="col-span-6 mt-5">
            <div class="relative">
              <div
                class="absolute inset-0 flex items-center"
                aria-hidden="true"
              >
                <div class="w-full border-t border-gray-300" />
              </div>
              <div class="relative flex justify-start">
                <span class="pr-3 bg-white text-lg font-medium text-gray-900">
                  Endereço de entrega
                </span>
              </div>
            </div>
          </div>

          <div class="col-span-6">
            <jet-label
              for="address"
              value="Pesquise um endereço"
            />

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
              class="mt-1 block w-full disabled:opacity-50"
              :disabled="blankNumber"
              @keydown.enter.prevent
              @change="updateMap"
            />

            <div class="ml-1.5 mt-1.5 relative flex items-start">
              <div class="flex items-center h-5">
                <input
                  id="blank_number"
                  v-model="blankNumber"
                  name="blank_number"
                  type="checkbox"
                  class="
                    focus:ring-speedapp-orange-500
                    h-4
                    w-4
                    text-speedapp-orange-600
                    border-gray-300
                    rounded
                  "
                >
              </div>
              <div class="ml-1.5 text-sm">
                <label
                  class="text-gray-500"
                  for="blank_number"
                >Endereço sem número</label>
              </div>
            </div>

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
              v-model="form.district"
              type="text"
              class="mt-1 block w-full disabled:opacity-50"
              @keydown.enter.prevent
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

          <div class="col-span-6">
            <jet-label
              for="info"
              value="Complemento"
            />
            <jet-input
              id="info"
              v-model="form.info"
              type="text"
              class="mt-1 block w-full"
              @keydown.enter.prevent
            />
            <jet-input-error
              :message="form.errors.info"
              class="mt-2"
            />
          </div>

          <div
            class="col-span-6 overflow-hidden rounded-md relative"
          >
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
            v-if="formattedDistance || formattedDuration"
            class="col-span-6 -mt-2"
          >
            <span class="font-medium text-blue-600">{{ formattedDistance ? `${formattedDistance}.` : ""
            }}{{
              formattedDuration
                ? formattedDistance
                  ? ` Após a coleta estimado mais ${formattedDuration}`
                  : formattedDuration
                : ""
            }}</span>
          </div>

          <div class="col-span-6 -mt-2">
            <span
              v-if="error"
              class="font-medium text-red-600"
            >{{
              error
            }}</span>
          </div>

          <div class="col-span-6 md:col-span-3">
            <jet-label
              for="time"
              value="Tempo de entrega (em minutos)"
            />
            <jet-input
              id="time"
              type="text"
              class="mt-1 block w-full opacity-50"
              :value="form.time"
              disabled
            />
            <jet-input-error
              :message="form.errors.time"
              class="mt-2"
            />
          </div>

          <div class="col-span-6 md:col-span-3">
            <jet-label
              for="charged"
              value="Valor"
            />
            <jet-input
              id="charged"
              type="text"
              class="mt-1 block w-full opacity-50"
              :value="form.formatted_charged"
              disabled
            />
            <jet-input-error
              :message="form.errors.charged"
              class="mt-2"
            />
          </div>

          <div class="col-span-6 md:col-span-3 mt-3">
            <div class="relative flex items-start">
              <div class="flex items-center h-5">
                <input
                  id="return"
                  v-model="form.return"
                  type="checkbox"
                  class="
                    focus:ring-speedapp-orange-500
                    h-4
                    w-4
                    text-speedapp-orange-600
                    border-gray-300
                    rounded
                  "
                >
              </div>
              <div class="ml-3 text-sm">
                <label
                  for="return"
                  class="font-medium text-gray-700"
                >Retorno</label>
                <p class="text-gray-500">
                  Marque caso seja necessário ao entregador retornar ao local de
                  coleta.
                  <template v-if="selectedUser">
                    <br>
                    Será cobrado um valor adicional de
                    <b>{{ selectedUser.formatted_return_fee_charged }}</b>.
                  </template>
                </p>
              </div>
            </div>

            <jet-input-error
              :message="form.errors.return"
              class="mt-2"
            />
          </div>

          <div class="col-span-6 md:col-span-3 mt-3">
            <jet-label
              for="total"
              value="Total"
            />
            <jet-input
              id="total"
              type="text"
              class="mt-1 block w-full opacity-50"
              :value="total"
              disabled
            />
          </div>

          <div class="col-span-6 mt-5">
            <div class="relative">
              <div
                class="absolute inset-0 flex items-center"
                aria-hidden="true"
              >
                <div class="w-full border-t border-gray-300" />
              </div>
              <div class="relative flex justify-start">
                <span class="pr-3 bg-white text-lg font-medium text-gray-900">
                  Cliente
                </span>
              </div>
            </div>
          </div>

          <div class="col-span-6 md:col-span-3">
            <jet-label
              for="customer_name"
              value="Nome"
            />
            <jet-input
              id="customer_name"
              v-model="form.customer_name"
              type="text"
              class="mt-1 block w-full"
              @keydown.enter.prevent
            />
            <jet-input-error
              :message="form.errors.customer_name"
              class="mt-2"
            />
          </div>

          <div class="col-span-6 md:col-span-3">
            <jet-label
              for="customer_phone"
              value="Telefone"
            />
            <jet-input
              id="customer_phone"
              v-model="form.customer_phone"
              type="text"
              class="mt-1 block w-full"
              @keydown.enter.prevent
            />
            <jet-input-error
              :message="form.errors.customer_phone"
              class="mt-2"
            />
          </div>

          <div class="col-span-6 mt-5">
            <div class="relative">
              <div
                class="absolute inset-0 flex items-center"
                aria-hidden="true"
              >
                <div class="w-full border-t border-gray-300" />
              </div>
              <div class="relative flex justify-start">
                <span class="pr-3 bg-white text-lg font-medium text-gray-900">
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
                <span class="pr-3 bg-white text-lg font-medium text-gray-900">
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
                <span class="pr-3 bg-white text-lg font-medium text-gray-900">
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
  Listbox,
  ListboxButton,
  ListboxLabel,
  ListboxOption,
  ListboxOptions,
} from "@headlessui/vue";
import { CheckIcon, SelectorIcon } from "@heroicons/vue/solid";

import { format } from "v-money3";

import L from "leaflet";
import "leaflet-draw/dist/leaflet.draw";
import "leaflet/dist/leaflet.css";
import "leaflet-draw/dist/leaflet.draw.css";
import "leaflet.gridlayer.googlemutant";
import "polyline-encoded";

import startIcon from "@/assets/images/start-icon.svg";
import endIcon from "@/assets/images/end-icon.svg";

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

  setup() {
    let map = null;
    let autocomplete = null;
    let geocoder = null;
    let polyline = null;

    let autocompleteInput = ref(null);

    let userMarker = null;
    let targetMarker = null;

    let center = [-26.4822945, -49.0699353];

    const selectedUser = ref(null);
    const selectedDriver = ref(null);

    const blankNumber = ref(false);

    const formattedDistance = ref(null);
    const formattedDuration = ref(null);

    const loading = ref(false);
    const error = ref(null);

    const form = useForm({
      user: null,
      driver: null,

      position: null,
      street_number: "",
      street_name: "",
      district: "",
      city: "",
      state: "",

      info: "",

      customer_name: "",
      customer_phone: "",

      public_text: "",
      private_text: "",
      additional_info: "",

      rad: "",
      time: "",
      charged: "",
      formatted_charged: "",

      line: null,

      distance: null,
      duration: null,

      return: false,
    });

    const total = computed(() => {
      if (
        !form.return ||
        !selectedUser.value ||
        !selectedUser.value.return_fee_charged
      ) {
        return form.formatted_charged;
      }

      return format(
        (form.charged + selectedUser.value.return_fee_charged) / 100,
        {
          masked: false,
          prefix: "R$ ",
          suffix: "",
          thousands: ".",
          decimal: ",",
          precision: 2,
          disableNegative: false,
          disabled: false,
          min: null,
          max: null,
          allowBlank: true,
          minimumNumberOfCharacters: 0,
        }
      );
    });

    const resetFormAddress = () => {
      form.position = null;
      form.street_number = "";
      form.street_name = "";
      form.district = "";
      form.city = "";
      form.state = "";

      form.info = "";

      form.rad = null;
      form.time = null;
      form.charged = null;
      form.formatted_charged = null;

      form.line = null;

      form.distance = null;
      form.duration = null;

      error.value = null;
    };

    const submit = () => {
      form.post(route("developer.request-delivery.store"), {
        preserveScroll: true,
      });
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
          checkAndUpdate();
        });

        geocoder = new window.google.maps.Geocoder();
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
      checkAndUpdate();
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
          iconUrl: endIcon,
          iconSize: [40, 40],
          iconAnchor: [20, 20],
        }),
      }).addTo(map);

      if (userMarker !== null) {
        let group = new L.featureGroup([userMarker, targetMarker]);

        map.fitBounds(group.getBounds(), {
          padding: [10, 10],
        });
      } else {
        map.setView([lat, lng], 14);
      }

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

    const checkAndUpdate = () => {
      if (!form.user || !form.position) return;

      loading.value = true;

      if (polyline) {
        map.removeLayer(polyline);
      }

      Inertia.post(
        route("developer.request-delivery.check"),
        {
          position: form.position,
          user: form.user,
        },
        {
          preserveScroll: true,
          onSuccess: (res) => {
            if (res.props.result) {
              form.rad = res.props.result.rad;
              form.time = res.props.result.time;
              form.charged = res.props.result.charged;
              form.formatted_charged = res.props.result.formatted_charged;

              if (res.props.result.line) {
                form.line = res.props.result.line;

                form.distance = res.props.result.distance;
                form.duration = res.props.result.duration;

                polyline = L.polyline(
                  L.PolylineUtil.decode(res.props.result.line)
                ).addTo(map);

                let group = new L.featureGroup([
                  userMarker,
                  targetMarker,
                  polyline,
                ]);

                map.fitBounds(group.getBounds(), {
                  padding: [10, 10],
                });

                formattedDistance.value = res.props.result.formatted_distance;
                formattedDuration.value = res.props.result.formatted_duration;
              }
            } else {
              form.rad = null;
              form.time = null;
              form.charged = null;
              form.formatted_charged = null;

              form.line = null;

              form.distance = null;
              form.duration = null;

              formattedDistance.value = null;
              formattedDuration.value = null;

              error.value = "Não entrega no endereço selecionado.";
            }
          },
          onError: (err) => {
            console.log(err);

            form.rad = null;
            form.time = null;
            form.charged = null;
            form.formatted_charged = null;

            form.line = null;

            form.distance = null;
            form.duration = null;

            formattedDistance.value = null;
            formattedDuration.value = null;
          },
          onFinish: () => {
            loading.value = false;
          },
        }
      );
    };

    watch(selectedDriver, (n, o) => {
      if (n === o) return;

      form.driver = n.id;
    });

    watch(selectedUser, (n, o) => {
      if (n == o) return;

      form.user = n.id;

      if (n === null) {
        return;
      }

      if (userMarker !== null) {
        map.removeLayer(userMarker);
        userMarker = null;
      }

      const lat = n.address.position.coordinates[1];
      const lng = n.address.position.coordinates[0];

      userMarker = L.marker([lat, lng], {
        icon: L.icon({
          iconUrl: startIcon,
          iconSize: [40, 40],
          iconAnchor: [20, 20],
        }),
      }).addTo(map);

      if (targetMarker === null) {
        map.setView([lat, lng], 14);
      } else {
        let group = new L.featureGroup([userMarker, targetMarker]);

        map.fitBounds(group.getBounds(), {
          padding: [10, 10],
        });

        checkAndUpdate();
      }
    });

    watch(blankNumber, (n, o) => {
      if (n) form.street_number = "";
    });

    onMounted(debounce(initMap, 100));

    return {
      selectedUser,
      selectedDriver,
      error,
      form,
      submit,
      updateMap,
      total,
      autocompleteInput,
      blankNumber,
      formattedDistance,
      formattedDuration,
      loading,
    };
  },
});
</script>