<template>
  <admin-layout title="Acompanhar entrega">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Acompanhar entrega
        </h2>

        <div>
          <Link
            v-if="delivery.status !== &quot;COMPLETED&quot; && delivery.status !== &quot;CANCELED&quot; && delivery.status !== &quot;NOT_DELIVERED&quot;"
            as="button"
            class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-300 active:bg-blue-900 focus:outline-none transition ease-in-out duration-150"
            @click="redispatch"
          >
            Redisparar
          </Link>

          <Link
            :href="route('admin.deliveries')"
            class="ml-2 inline-flex items-center px-4 py-2 bg-speedapp-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-speedapp-gray-300 active:bg-speedapp-gray-900 focus:outline-none transition ease-in-out duration-150"
          >
            Voltar
          </Link>
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <span
          :class="['mb-5 block text-center font-bold tracking-widest text-xl uppercase', {
            'text-blue-500': delivery.status === 'WAITING',
            'text-yellow-300': delivery.status === 'PENDING',
            'text-speedapp-orange-500': delivery.status === 'ACCEPTED' || delivery.status === 'COLLECTING' || delivery.status === 'DELIVERING' || delivery.status === 'CONFIRMED' || delivery.status === 'RETURNING',
            'text-green-500': delivery.status === 'COMPLETED',
            'text-red-500': delivery.status === 'CANCELED',
            'text-pink-500': delivery.status === 'NOT_DELIVERED',
          }]"
        >
          {{ delivery.formatted_status }}
        </span>

        <div class="relative col-span-6 shadow overflow-hidden rounded-md">
          <div
            id="map"
            class="z-40"
            style="height: 300px"
          />
          <span
            v-if="delivery.status !== 'COMPLETED' && delivery.status !== 'CANCELED' && delivery.status !== 'NOT_DELIVERED'"
            class="
              flex
              items-center
              absolute
              top-2
              left-2
              rounded-md
              z-50
              px-2.5
              py-1.5
              text-sm
              font-medium
              bg-white
              shadow-sm
            "
          >
            Atualizando automaticamente

            <Spinner class="ml-2.5" />
          </span>
        </div>

        <div class="flex flex-col mt-4">
          <dl class="grid grid-cols-1 gap-5 md:grid-cols-4">
            <div
              class="relative bg-white p-5 shadow rounded-md overflow-hidden"
            >
              <dt>
                <p class="text-sm font-medium text-gray-500 truncate">
                  Disparo
                </p>
              </dt>
              <dd class="flex items-baseline">
                <p class="text-2xl font-semibold text-gray-900">
                  {{ delivery.formatted_pending_at ?? "-" }}
                </p>
              </dd>
            </div>

            <div
              class="relative bg-white p-5 shadow rounded-md overflow-hidden"
            >
              <dt>
                <p class="text-sm font-medium text-gray-500 truncate">
                  Aceito
                </p>
              </dt>
              <dd class="flex items-baseline">
                <p class="text-2xl font-semibold text-gray-900">
                  {{ delivery.formatted_accepted_at ?? "-" }}
                </p>
              </dd>
            </div>

            <div
              class="relative bg-white p-5 shadow rounded-md overflow-hidden"
            >
              <dt>
                <p class="text-sm font-medium text-gray-500 truncate">
                  Entregue
                </p>
              </dt>
              <dd class="flex items-baseline">
                <p class="text-2xl font-semibold text-gray-900">
                  {{ delivery.formatted_delivered_at ?? "-" }}
                </p>
              </dd>
            </div>

            <div
              class="relative bg-white p-5 shadow rounded-md overflow-hidden"
            >
              <dt>
                <p class="text-sm font-medium text-gray-500 truncate">
                  Total
                </p>
              </dt>
              <dd class="flex items-baseline">
                <p class="text-2xl font-semibold text-gray-900">
                  {{ delivery.elapsed_time ?? "-" }}
                </p>
              </dd>
            </div>
          </dl>
        </div>
        
        <div
          class="align-middle inline-block min-w-full mt-4"
        >
          <form @submit.prevent>
            <div
              class="shadow-md rounded-md"
            >
              <div class="px-4 py-5 bg-white sm:p-6">
                <div class="grid grid-cols-6 gap-6">
                  <div class="col-span-6">
                    <div class="relative">
                      <div
                        class="absolute inset-0 flex items-center"
                        aria-hidden="true"
                      >
                        <div
                          class="w-full border-t border-gray-300"
                        />
                      </div>
                      <div
                        class="relative flex justify-start"
                      >
                        <span
                          class="pr-3 bg-white text-lg font-medium text-gray-900"
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
                          class="relative w-full bg-white border border-gray-300 rounded-md shadow-sm pl-3 pr-10 py-2 text-left cursor-default focus:outline-none focus:ring-1 focus:ring-speedapp-orange-500 focus:border-speedapp-orange-500 sm:text-sm"
                        >
                          <div
                            class="flex items-center"
                          >
                            <span
                              :class="[
                                'shrink-0 inline-block h-2 w-2 rounded-full',
                                {
                                  'bg-gray-200':
                                    !selectedDriver,
                                  'bg-green-400':
                                    selectedDriver &&
                                    selectedDriver
                                      .metadata
                                      .status ===
                                    'ONLINE',
                                  'bg-yellow-500':
                                    selectedDriver &&
                                    selectedDriver
                                      .metadata
                                      .status ===
                                    'BUSY',
                                  'bg-red-700':
                                    selectedDriver &&
                                    selectedDriver
                                      .metadata
                                      .status ===
                                    'OFFLINE',
                                },
                              ]"
                            />
                            <span
                              class="ml-3 block truncate"
                            >{{
                              selectedDriver?.full_name ??
                                "Nenhum"
                            }}</span>
                          </div>

                          <span
                            class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none"
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
                            class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm"
                          >
                            <ListboxOption
                              v-slot="{
                                active,
                                selected,
                              }"
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
                                <div
                                  class="flex items-center"
                                >
                                  <span
                                    class="shrink-0 inline-block h-2 w-2 rounded-full bg-gray-200"
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
                              v-for="(
                                driver,
                                index
                              ) in drivers"
                              :key="index"
                              v-slot="{
                                active,
                                selected,
                              }"
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
                                <div
                                  class="flex items-center"
                                >
                                  <span
                                    :class="[
                                      'shrink-0 inline-block h-2 w-2 rounded-full',
                                      {
                                        'bg-green-400':
                                          driver
                                            .metadata
                                            .status ===
                                          'ONLINE',
                                        'bg-yellow-500':
                                          driver
                                            .metadata
                                            .status ===
                                          'BUSY',
                                        'bg-red-700':
                                          driver
                                            .metadata
                                            .status ===
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
                                    {{
                                      driver.full_name
                                    }}
                                    <span
                                      class="sr-only"
                                    >
                                      is
                                      {{
                                        driver
                                          .metadata
                                          .formatted_status
                                      }}</span>
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
                v-if="(delivery?.driver == null && selectedDriver !== null) || ((delivery.driver !== selectedDriver) && (delivery?.driver.id !== selectedDriver?.id))"
                class="px-4 py-3 bg-gray-50 text-right sm:px-6"
              >
                <button
                  type="submit"
                  class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-speedapp-orange-600 hover:bg-speedapp-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-speedapp-orange-500"
                  @click="submitDriver($event, true)"
                >
                  Salvar e redisparar 

                  <template v-if="selectedDriver === null && delivery.driver !== null">
                    para todos
                  </template>

                  <template v-else>
                    para {{ selectedDriver.full_name }}
                  </template>
                </button>

                <button
                  type="submit"
                  class="ml-2 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-speedapp-orange-600 hover:bg-speedapp-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-speedapp-orange-500"
                  @click="submitDriver"
                >
                  Salvar
                </button>
              </div>
            </div>
          </form>
        </div>

        <div class="flex flex-col mt-2">
          <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div
              class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8"
            >
              <div class="bg-white shadow-md overflow-hidden rounded-md">
                <div class="px-4 py-5 sm:p-0">
                  <dl class="sm:divide-y sm:divide-gray-200">
                    <div
                      class="
                        py-4
                        sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6
                      "
                    >
                      <dt class="text-sm font-medium text-gray-500">
                        Código
                      </dt>
                      <dd
                        class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                      >
                        #{{ delivery.cid }}
                      </dd>

                      <dt class="text-sm font-medium text-gray-500">
                        Entregador
                      </dt>
                      <dd
                        class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                      >
                        {{ delivery.driver?.full_name ?? "-" }}
                      </dd>

                      <dt class="text-sm font-medium text-gray-500">
                        Loja
                      </dt>
                      <dd
                        class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                      >
                        {{ delivery.user.name }}
                      </dd>

                      <dt class="text-sm font-medium text-gray-500">
                        Cidade
                      </dt>
                      <dd
                        class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                      >
                        {{ delivery.user.city.name }}
                      </dd>

                      <dt class="text-sm font-medium text-gray-500">
                        Nome do cliente
                      </dt>
                      <dd
                        class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                      >
                        {{ delivery.customer_name ?? "-" }}
                      </dd>

                      <dt class="text-sm font-medium text-gray-500">
                        Número do cliente
                      </dt>
                      <dd
                        class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                      >
                        {{ delivery.customer_phone ?? "-" }}
                      </dd>

                      <dt class="text-sm font-medium text-gray-500">
                        Endereço de entrega
                      </dt>
                      <dd
                        class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                      >
                        {{ delivery.steps[1].formatted_address }}
                      </dd>

                      <dt class="text-sm font-medium text-gray-500">
                        Pago
                      </dt>
                      <dd
                        class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                      >
                        {{ delivery.formatted_total_paid }}
                      </dd>

                      <dt class="text-sm font-medium text-gray-500">
                        Cobrado
                      </dt>
                      <dd
                        class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                      >
                        {{ delivery.formatted_total_charged }}
                      </dd>

                      <dt class="text-sm font-medium text-gray-500">
                        Raio
                      </dt>
                      <dd
                        class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                      >
                        {{ delivery.rad }}km
                      </dd>

                      <dt class="text-sm font-medium text-gray-500">
                        Tempo
                      </dt>
                      <dd
                        class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                      >
                        {{ delivery.time }}m
                      </dd>

                      <dt class="text-sm font-medium text-gray-500">
                        Retorno
                      </dt>
                      <dd
                        class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                      >
                        {{ delivery.formatted_return_fee ?? "-" }}
                      </dd>

                      <dt class="text-sm font-medium text-gray-500">
                        Confirmação
                      </dt>
                      <dd
                        class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                      >
                        {{ delivery.receipt_required ? "Sim" : "Não" }}
                      </dd>

                      <dt class="text-sm font-medium text-gray-500">
                        Observações
                      </dt>
                      <dd
                        class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                      >
                        {{ delivery.public_text ?? '-' }}
                      </dd>

                      <dt class="text-sm font-medium text-gray-500">
                        Informações adicionais
                      </dt>
                      <dd
                        class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                      >
                        {{ delivery.private_text ?? '-' }}
                      </dd>
                    </div>
                  </dl>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="w-full mt-5">
          <div class="space-y-2.5">
            <div
              v-for="(shot, index) in delivery.shots"
              :key="index" 
              :class="['px-3 py-2 rounded-md shadow-md', {
                'bg-blue-500': shot.action === 'SENT',
                'bg-speedapp-orange-500': shot.action === 'RECEIVED',
                'bg-green-500': shot.action === 'ACCEPTED',
                'bg-red-500': shot.action === 'REFUSED',
              }]"
            >
              <div class="flex items-center justify-between flex-wrap">
                <div class="w-0 flex-1 flex items-center">
                  <span class="tracking-wider text-white text-sm">
                    Entregador {{ shot.driver.full_name }} {{ shot.formatted_action }} às {{ shot.formatted_created_at }}
                  </span>
                </div>
              </div>
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
  </admin-layout>
</template>

<script>
import { defineComponent, onMounted, ref } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetFormSection from "@/Jetstream/FormSection.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import { Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";

import { Listbox, ListboxButton, ListboxLabel, ListboxOption, ListboxOptions } from '@headlessui/vue'

import {
  RefreshIcon,
  TrashIcon,
  PencilIcon,
  PlusIcon,
  CheckCircleIcon,
  CheckIcon,
  SelectorIcon
} from "@heroicons/vue/solid";

import L from "leaflet";
import "leaflet-draw/dist/leaflet.draw";
import "leaflet/dist/leaflet.css";
import "leaflet-draw/dist/leaflet.draw.css";
import "leaflet.gridlayer.googlemutant";
import "leaflet.marker.slideto";
import "leaflet.fullscreen";
import "leaflet.fullscreen/Control.FullScreen.css";

import startIcon from "@/assets/images/start-icon.svg";
import endIcon from "@/assets/images/end-icon.svg";
import homeIcon from "@/assets/images/anchor.svg";
import plusIcon from "@/assets/images/plus.svg";
import minusIcon from "@/assets/images/minus.svg";

import driverIcon from "@/assets/images/driver-icon.svg";

import mapStyle from "@/assets/map-style.json";


export default defineComponent({
  components: {
    AdminLayout,
    JetActionMessage,
    JetButton,
    JetFormSection,
    JetInput,
    JetInputError,
    JetLabel,
    JetSecondaryButton,
    Link,
    RefreshIcon,
    TrashIcon,
    PencilIcon,
    PlusIcon,
    CheckCircleIcon,
    Listbox,
		ListboxButton,
		ListboxLabel,
		ListboxOption,
		ListboxOptions,
		CheckIcon,
		SelectorIcon,
  },

  props: {
    delivery: {
      type: Object,
      required: true
    },
    drivers: {
      type: Array,
      default: () => [],
    }
  },

  setup(props) {
    const status = ref(null);

    let map = null;

    let userMarker = null;
    let endMarker = null;
    let driverMarker = null;

    let markers = null;

    let timer = null;
    let timeout = 15000;

    let center = [
      props.delivery.steps[0].location.coordinates[1],
      props.delivery.steps[0].location.coordinates[0],
    ];

    let end = [
      props.delivery.steps[1].location.coordinates[1],
      props.delivery.steps[1].location.coordinates[0],
    ];

    const selectedDriver = ref(props.delivery.driver);

    const addZoomControl = () => {
      L.Control.controls = L.Control.extend({
        options: {
          position: "topright",
          zoomInText: `<img src="${plusIcon}"/>`,
          zoomInTitle: "Ampliar",
          zoomOutText: `<img src="${minusIcon}"/>`,
          zoomOutTitle: "Reduzir",
          zoomHomeText: `<img src="${homeIcon}"/>`,
          zoomHomeTitle: "Recentralizar",
        },

        onAdd: function (map) {
          var controlName = "gin-control-zoom";
          var container = L.DomUtil.create("div", controlName + " leaflet-bar");
          var options = this.options;

          this._zoomInButton = this._createButton(
            options.zoomInText,
            options.zoomInTitle,
            controlName + "-in",
            container,
            this._zoomIn
          );

          this._zoomOutButton = this._createButton(
            options.zoomOutText,
            options.zoomOutTitle,
            controlName + "-out",
            container,
            this._zoomOut
          );

          this._zoomHomeButton = this._createButton(
            options.zoomHomeText,
            options.zoomHomeTitle,
            controlName + "-home",
            container,
            this._zoomHome
          );

          this._updateDisabled();
          map.on("zoomend zoomlevelschange", this._updateDisabled, this);

          return container;
        },

        onRemove: function (map) {
          map.off("zoomend zoomlevelschange", this._updateDisabled, this);
        },

        _zoomIn: function (e) {
          this._map.zoomIn(e.shiftKey ? 3 : 1);
        },

        _zoomOut: function (e) {
          this._map.zoomOut(e.shiftKey ? 3 : 1);
        },

        _zoomHome: function (e) {
          map.fitBounds(markers.getBounds(), {
            padding: [10, 10],
          });
        },

        _createButton: function (html, title, className, container, fn) {
          var link = L.DomUtil.create("a", className, container);
          link.innerHTML = html;
          link.href = "#";
          link.title = title;

          L.DomEvent.on(link, "mousedown dblclick", L.DomEvent.stopPropagation)
            .on(link, "click", L.DomEvent.stop)
            .on(link, "click", fn, this)
            .on(link, "click", this._refocusOnMap, this);

          return link;
        },

        _updateDisabled: function () {
          var map = this._map,
            className = "leaflet-disabled";

          L.DomUtil.removeClass(this._zoomInButton, className);
          L.DomUtil.removeClass(this._zoomOutButton, className);

          if (map._zoom === map.getMinZoom()) {
            L.DomUtil.addClass(this._zoomOutButton, className);
          }
          if (map._zoom === map.getMaxZoom()) {
            L.DomUtil.addClass(this._zoomInButton, className);
          }
        },
      });
    };

    const initMap = async () => {
      window.emitter.emit("initGoogleMaps", () => {
        map = L.map("map", {
          zoomControl: false,
          scrollWheelZoom: true,
          touchZoom: false,
          dragging: true,
          keyboard: false,
          fullscreenControl: !window.isSafari,
          fullscreenControlOptions: {
              title: "Tela cheia",
              titleCancel: "Sair",
              position: 'topright',
              forceSeparateButton: true,
              forcePseudoFullscreen: true,
              fullscreenElement: false,
          }
        }).setView(center, 14);

        L.gridLayer
          .googleMutant({
            type: "roadmap",
            styles: mapStyle,
          })
          .addTo(map);

        addZoomControl();

        const controls = new L.Control.controls();

        controls.addTo(map);

        userMarker = L.marker(center, {
          icon: L.icon({
            iconUrl: startIcon,
            iconSize: [40, 40],
            iconAnchor: [20, 20],
          }),
        })
          .bindPopup(
            L.popup({
              maxWidth: 200
            }).setContent(`
              <div>
                <span>Endereço de coleta: </span> <br>
                <span><strong>${props.delivery.steps[0].formatted_address}</strong></span>
              </div>
            `)
          )
          .addTo(map);

        endMarker = L.marker(end, {
          icon: L.icon({
            iconUrl: endIcon,
            iconSize: [40, 40],
            iconAnchor: [20, 20],
          }),
        }).bindPopup(
          L.popup({
            maxWidth: 200
          }).setContent(`
          <div>
              <span>Endereço de entrega: </span> <br>
              <span><strong>${props.delivery.steps[1].formatted_address}</strong></span>
            </div>
          `)
        ).addTo(map);

        markers = new L.featureGroup([userMarker, endMarker]);

        map.fitBounds(markers.getBounds(), {
          padding: [10, 10],
        });

        if (props.delivery.driver && props.delivery.status !== "COMPLETED" && props.delivery.status !== "CANCELED" && props.delivery.status !== "NOT_DELIVERED") {
          driverMarker = L.marker(
            [
              props.delivery.driver.metadata.location.coordinates[1],
              props.delivery.driver.metadata.location.coordinates[0],
            ],
            {
              icon: L.icon({
                iconUrl: driverIcon,
                iconSize: [30, 30],
                iconAnchor: [15, 15],
              }),
            }
          ).bindPopup(
            L.popup({
              maxWidth: 200
            }).setContent(`
              <div>
                  <span>Nome: <strong>${props.delivery.driver.full_name}</strong></span>
                  <br>
                  <span>Status: <strong>${props.delivery.driver.metadata.formatted_status}</strong></span>
                  <br>
                  <span>Atualizado às: <strong>${props.delivery.driver.formatted_updated_at}</strong></span>
              </div>
            `)
          ).addTo(map);

          markers.addLayer(driverMarker);

          map.fitBounds(markers.getBounds(), {
            padding: [10, 10],
          });
        }

        if (props.delivery.status !== "COMPLETED" && props.delivery.status !== "CANCELED" && props.delivery.status !== "NOT_DELIVERED") {
          update();
        }

        if (props.delivery.status === 'COMPLETED') {

          if(props.delivery.route) {
            setTimeout(() => {
              const { route } = props.delivery.route;

              const polyline = L.polyline(route.coordinates, {
                color: '#F57C00',
                stroke: true,
                weight: 5,
                lineCap: 'round',
                lineJoin: 'round',
                dashArray: '10, 10',
                dashOffset: '30'
              }).addTo(map);

              markers.addLayer(polyline);

              map.fitBounds(markers.getBounds(), {
                padding: [15, 15],
              });
            }, 500);
          }
        }
      });
    };

    const update = () => {
      if (timer) clearTimeout(timer);

      Inertia.reload({
        onFinish: () => {
          if (props.delivery.status !== "COMPLETED" && props.delivery.status !== "CANCELED" && props.delivery.status !== "NOT_DELIVERED") {
            if(props.delivery.driver) {
              if (!driverMarker) {
                driverMarker = L.marker(
                  [
                    props.delivery.driver.metadata.location.coordinates[1],
                    props.delivery.driver.metadata.location.coordinates[0],
                  ],
                  {
                    icon: L.icon({
                      iconUrl: driverIcon,
                      iconSize: [30, 30],
                      iconAnchor: [15, 15],
                    }),
                  }
                ).addTo(map);

                markers.addLayer(driverMarker);
              } else {
                driverMarker.slideTo(
                  [
                    props.delivery.driver.metadata.location.coordinates[1],
                    props.delivery.driver.metadata.location.coordinates[0],
                  ],
                  {
                    duration: 200,
                    keepAtCenter: false,
                  }
                );
              }
            }

            map.fitBounds(markers.getBounds(), {
              padding: [10, 10],
            });

            timer = setTimeout(() => {
              update();
            }, timeout);
          } else {
            if (driverMarker) {
              driverMarker.remove();
            }
          }
        },
      });
    };

    const submitDriver = ($event, redispatch = false) => {
        Inertia.post(route("admin.deliveries.updateDriver", props.delivery.id), {
          _method: 'PUT',
          driver: selectedDriver.value ? selectedDriver.value.id : null,
          redispatch: redispatch,
        }, {
          preserveScroll: true,
            onSuccess: (res) => {
                setStatus(res.props.status);
            },
        })
    };

    const redispatch = () => {
      Inertia.post(route('admin.deliveries.redispatch', props.delivery.id), null, {
        preserveScroll: true,
        onSuccess: (res) => {
          setStatus(res.props.status);
        },
      });
    }

    const setStatus = (stts) => {
      status.value = stts;

      Inertia.reload();

      setTimeout(() => {
        status.value = null;
      }, 2000);
    }

    onMounted(initMap);

    return {
      selectedDriver,
      status,
      submitDriver,
      redispatch
    }
  },
});
</script>
