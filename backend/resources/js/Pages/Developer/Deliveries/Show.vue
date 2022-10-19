<template>
  <developer-layout title="Acompanhar entrega">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Acompanhar entrega
        </h2>

        <div>
          <Link
            v-if="
              delivery.status !== 'COMPLETED' &&
                delivery.status !== 'CANCELED' &&
                delivery.status !== 'NOT_DELIVERED'
            "
            as="button"
            class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-300 active:bg-blue-900 focus:outline-none transition ease-in-out duration-150"
            @click="redispatch"
          >
            Redisparar
          </Link>
          
          <Link
            :href="route('developer.deliveries.edit', delivery.id)"
            class="ml-2 inline-flex items-center px-4 py-2 bg-speedapp-orange-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-speedapp-orange-300 active:bg-speedapp-orange-900 focus:outline-none transition ease-in-out duration-150"
          >
            Editar
          </Link>

          <Link
            as="button"
            class="ml-2 inline-flex items-center px-4 py-2 bg-speedapp-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-speedapp-gray-300 active:bg-speedapp-gray-900 focus:outline-none transition ease-in-out duration-150"
            @click="goBack"
          >
            Voltar
          </Link>
        </div>
      </div>
    </template>

    <div class="py-12">
      <span
        :class="[
          'mb-5 block text-center font-bold tracking-widest text-xl uppercase',
          {
            'text-blue-500': delivery.status === 'WAITING',
            'text-yellow-300': delivery.status === 'PENDING',
            'text-speedapp-orange-500':
              delivery.status === 'ACCEPTED' ||
              delivery.status === 'COLLECTING' ||
              delivery.status === 'DELIVERING' ||
              delivery.status === 'CONFIRMED' ||
              delivery.status === 'RETURNING',
            'text-green-500': delivery.status === 'COMPLETED',
            'text-red-500': delivery.status === 'CANCELED',
            'text-pink-500': delivery.status === 'NOT_DELIVERED',
          },
        ]"
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
          v-if="
            delivery.status !== 'COMPLETED' &&
              delivery.status !== 'CANCELED' &&
              delivery.status !== 'NOT_DELIVERED'
          "
          class="flex items-center absolute top-2 left-2 rounded-md z-50 px-2.5 py-1.5 text-sm font-medium  bg-white shadow-sm"
        >
          Atualizando automaticamente

          <Spinner class="ml-2.5" />
        </span>
      </div>

      <div class="flex flex-col mt-4">
        <dl class="grid grid-cols-1 gap-5 md:grid-cols-4">
          <div
            class="relative bg-white  p-5 shadow rounded-md overflow-hidden"
          >
            <dt>
              <p
                class="text-sm font-medium text-gray-500  truncate"
              >
                Disparo
              </p>
            </dt>
            <dd class="flex items-baseline">
              <p
                class="text-2xl font-semibold text-gray-900 "
              >
                {{ delivery.formatted_pending_at ?? "-" }}
              </p>
            </dd>
          </div>

          <div
            class="relative bg-white  p-5 shadow rounded-md overflow-hidden"
          >
            <dt>
              <p
                class="text-sm font-medium text-gray-500  truncate"
              >
                Aceito
              </p>
            </dt>
            <dd class="flex items-baseline">
              <p
                class="text-2xl font-semibold text-gray-900 "
              >
                {{ delivery.formatted_accepted_at ?? "-" }}
              </p>
            </dd>
          </div>

          <div
            class="relative bg-white  p-5 shadow rounded-md overflow-hidden"
          >
            <dt>
              <p
                class="text-sm font-medium text-gray-500  truncate"
              >
                Entregue
              </p>
            </dt>
            <dd class="flex items-baseline">
              <p
                class="text-2xl font-semibold text-gray-900 "
              >
                {{ delivery.formatted_delivered_at ?? "-" }}
              </p>
            </dd>
          </div>

          <div
            class="relative bg-white  p-5 shadow rounded-md overflow-hidden"
          >
            <dt>
              <p
                class="text-sm font-medium text-gray-500  truncate"
              >
                Total
              </p>
            </dt>
            <dd class="flex items-baseline">
              <p
                class="text-2xl font-semibold text-gray-900 "
              >
                {{ delivery.elapsed_time ?? "-" }}
              </p>
            </dd>
          </div>
        </dl>
      </div>

      <div class="flex flex-col mt-2">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div
            class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8"
          >
            <div
              class="bg-white  shadow-md overflow-hidden sm:rounded-md"
            >
              <div class="px-4 py-5 sm:p-0">
                <dl class="sm:divide-y sm:divide-gray-200">
                  <div
                    class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6"
                  >
                    <dt
                      class="text-sm font-medium text-gray-500 "
                    >
                      Código
                    </dt>
                    <dd
                      class="mt-1 text-sm text-gray-900  sm:mt-0 sm:col-span-2"
                    >
                      #{{ delivery.cid }}
                    </dd>

                    <dt
                      class="text-sm font-medium text-gray-500 "
                    >
                      Entregador
                    </dt>
                    <dd
                      class="mt-1 text-sm sm:mt-0 sm:col-span-2"
                    >
                      <Link v-if="delivery.driver" :href="route('developer.drivers.show', delivery.driver.id)" class="text-blue-500 hover:text-blue-700">
                        {{ delivery.driver.full_name }}
                      </Link>

                      <span v-else class="text-gray-500 ">-</span>
                    </dd>

                    <dt
                      class="text-sm font-medium text-gray-500 "
                    >
                      Loja
                    </dt>
                    <dd
                      class="mt-1 text-sm text-blue-500 hover:text-blue-700 sm:mt-0 sm:col-span-2"
                    >
                      <Link :href="route('developer.users.show', delivery.user.id)">
                        {{ delivery.user.name }}
                      </Link>
                    </dd>

                    <dt
                      class="text-sm font-medium text-gray-500 "
                    >
                      Cidade
                    </dt>
                    <dd
                      class="mt-1 text-sm text-blue-500 hover:text-blue-700 sm:mt-0 sm:col-span-2"
                    >
                      <Link :href="route('developer.cities.show', delivery.user.city.id)">
                        {{ delivery.user.city.name }}
                      </Link>
                    </dd>

                    <dt
                      class="text-sm font-medium text-gray-500 "
                    >
                      Nome do cliente
                    </dt>
                    <dd
                      class="mt-1 text-sm text-gray-900  sm:mt-0 sm:col-span-2"
                    >
                      {{ delivery.customer_name ?? "-" }}
                    </dd>

                    <dt
                      class="text-sm font-medium text-gray-500 "
                    >
                      Número do cliente
                    </dt>
                    <dd
                      class="mt-1 text-sm text-gray-900  sm:mt-0 sm:col-span-2"
                    >
                      {{ delivery.customer_phone ?? "-" }}
                    </dd>

                    <dt
                      class="text-sm font-medium text-gray-500 "
                    >
                      Endereço de entrega
                    </dt>
                    <dd
                      class="mt-1 text-sm text-blue-500 hover:text-blue-700 sm:mt-0 sm:col-span-2"
                    >
                      <a :href="`https://maps.google.com/?q=${delivery.steps[1].location.coordinates[1]},${delivery.steps[1].location.coordinates[0]}`" target="_blank">
                        {{ delivery.steps[1].formatted_address }}
                      </a>
                    </dd>

                    <dt
                      class="text-sm font-medium text-gray-500 "
                    >
                      Pago
                    </dt>
                    <dd
                      class="mt-1 text-sm text-gray-900  sm:mt-0 sm:col-span-2"
                    >
                      {{ delivery.formatted_total_paid }}
                    </dd>

                    <dt
                      class="text-sm font-medium text-gray-500 "
                    >
                      Cobrado
                    </dt>
                    <dd
                      class="mt-1 text-sm text-gray-900  sm:mt-0 sm:col-span-2"
                    >
                      {{
                        delivery.formatted_total_charged
                      }}
                    </dd>

                    <dt
                      class="text-sm font-medium text-gray-500 "
                    >
                      Raio
                    </dt>
                    <dd
                      class="mt-1 text-sm text-gray-900  sm:mt-0 sm:col-span-2"
                    >
                      <span v-if="!delivery.lastmile">{{ delivery.rad }}km</span>
                      <span v-else>-</span>
                    </dd>

                    <dt
                      class="text-sm font-medium text-gray-500 "
                    >
                      Tempo
                    </dt>
                    <dd
                      class="mt-1 text-sm text-gray-900  sm:mt-0 sm:col-span-2"
                    >
                      <span v-if="!delivery.lastmile">{{ delivery.time }}m</span>
                      <span v-else>-</span>
                    </dd>

                    <dt
                      class="text-sm font-medium text-gray-500 "
                    >
                      Retorno
                    </dt>
                    <dd
                      class="mt-1 text-sm text-gray-900  sm:mt-0 sm:col-span-2"
                    > 
                      <span v-if="null == delivery.formatted_return_fee_charged">-</span>

                      <span v-else>
                        {{ delivery.formatted_return_fee_paid }}  (pago)
                        <br>
                        {{ delivery.formatted_return_fee_charged }} (cobrado)
                      </span>
                    </dd>

                    <dt
                      class="text-sm font-medium text-gray-500 "
                    >
                      Confirmação
                    </dt>
                    <dd
                      class="mt-1 text-sm text-gray-900  sm:mt-0 sm:col-span-2"
                    >
                      {{ delivery.receipt_confirmation }}
                      <br>

                      <div v-if="delivery.receipt">
                        Nome: {{ delivery.receipt.customer_name ?? '-' }}
                        <br>
                        Telefone: {{ delivery.receipt.customer_phone ?? '-' }}
                        <br>
                      
                        <img :src="delivery.receipt.full_url">
                      </div>
                    </dd>

                    <dt
                      class="text-sm font-medium text-gray-500 "
                    >
                      Observações
                    </dt>
                    <dd
                      class="mt-1 text-sm text-gray-900  sm:mt-0 sm:col-span-2"
                    >
                      {{ delivery.public_text ?? "-" }}
                    </dd>

                    <dt
                      class="text-sm font-medium text-gray-500 "
                    >
                      Observações internas
                    </dt>
                    <dd
                      class="mt-1 text-sm text-gray-900  sm:mt-0 sm:col-span-2"
                    >
                      {{ delivery.private_text ?? "-" }}
                    </dd>

                    <dt
                      class="text-sm font-medium text-gray-500 "
                    >
                      Informações adicionais
                    </dt>
                    <dd
                      class="mt-1 text-sm text-gray-900  sm:mt-0 sm:col-span-2 whitespace-pre-wrap"
                    >
                      {{
                        delivery.additional_info ?? "-"
                      }}
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
            :class="[
              'px-3 py-2 rounded-md shadow-md',
              {
                'bg-blue-500': shot.action === 'SENT',
                'bg-speedapp-orange-500':
                  shot.action === 'RECEIVED',
                'bg-green-500': shot.action === 'ACCEPTED',
                'bg-red-500': shot.action === 'REFUSED',
              },
            ]"
          >
            <div
              class="flex items-center justify-between flex-wrap"
            >
              <div class="w-0 flex-1 flex items-center">
                <span class="tracking-wider text-white text-sm">
                  Entregador {{ shot.driver.full_name }}
                  {{ shot.formatted_action }} às
                  {{ shot.formatted_created_at }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div
        v-if="delivery.audits && delivery.audits.length"
        class="mt-10 py-5 sm:p-0"
      >
        <div class="flow-root">
          <ul
            role="list"
            class="-mb-8"
          >
            <li
              v-for="(audit, index) in delivery.audits"
              :key="index"
            >
              <div class="relative pb-8">
                <span
                  v-if="index !== delivery.audits.length - 1"
                  class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"
                  aria-hidden="true"
                />
                <div class="relative flex space-x-3">
                  <div>
                    <span
                      v-if="
                        audit.data.metadata.audit_event ==
                          'created'
                      "
                      class="bg-blue-500 h-8 w-8 rounded-full flex items-center justify-center"
                    >
                      <PlusIcon
                        class="h-5 w-5 text-white"
                        aria-hidden="true"
                      />
                    </span>

                    <span
                      v-if="
                        audit.data.metadata.audit_event ==
                          'updated'
                      "
                      class="bg-yellow-500 h-8 w-8 rounded-full flex items-center justify-center"
                    >
                      <PencilIcon
                        class="h-5 w-5 text-white"
                        aria-hidden="true"
                      />
                    </span>

                    <span
                      v-if="
                        audit.data.metadata.audit_event ==
                          'deleted'
                      "
                      class="bg-red-500 h-8 w-8 rounded-full flex items-center justify-center"
                    >
                      <TrashIcon
                        class="h-5 w-5 text-white"
                        aria-hidden="true"
                      />
                    </span>

                    <span
                      v-if="
                        audit.data.metadata.audit_event ==
                          'restored'
                      "
                      class="bg-green-500 h-8 w-8 rounded-full flex items-center justify-center"
                    >
                      <RefreshIcon
                        class="h-5 w-5 text-white"
                        aria-hidden="true"
                      />
                    </span>
                  </div>

                  <div class="min-w-0 flex-1">
                    <div>
                      <span
                        class="font-medium text-sm text-gray-900 "
                      >{{
                        audit.data.metadata
                          .audit_event_name
                      }}</span>
                      <p
                        class="mt-0.5 text-sm text-gray-500 "
                      >
                        {{
                          audit.data.metadata
                            .audit_created_at
                        }}
                      </p>
                      <p
                        class="mt-0.5 text-sm text-gray-500 "
                      >
                        {{
                          audit.data.metadata.audit_url
                        }}
                      </p>
                      <p
                        class="mt-0.5 text-sm text-gray-500 "
                      >
                        {{ audit.data.metadata.audit_user_agent }}
                      </p>
                      <p
                        class="mt-0.5 text-sm text-gray-500 "
                      >
                        <span v-if="audit.data.metadata.user_type == 'App\\Models\\Developer'">Admin: {{ audit.data.metadata.user_name }}</span>
                        <span v-if="audit.data.metadata.user_type == 'App\\Models\\User'">Loja: {{ audit.data.metadata.user_name }}</span>
                        <span v-if="audit.data.metadata.user_type == 'App\\Models\\Driver'">Entregador: {{ audit.data.metadata.user_full_name }}</span>
                      </p>
                    </div>
                    <div
                      class="mt-2 text-sm text-gray-700  space-y-2"
                    >
                      <div
                        v-for="(
                          value, attribute
                        ) in audit.data.modified"
                        :key="attribute"
                      >
                        <template v-if="!value.old">
                          <span
                            v-if="value.new"
                            class="break-words"
                          >
                            O campo
                            <strong>{{
                              attribute
                            }}</strong>
                            foi atualizado para
                            <strong>{{
                              value.new
                            }}</strong>.
                          </span>

                          <span
                            v-else
                            class="break-words"
                          >
                            O campo
                            <strong>{{
                              attribute
                            }}</strong>
                            foi atualizado para em
                            branco.
                          </span>
                        </template>

                        <span
                          v-else-if="!value.new"
                          class="break-words"
                        >
                          O campo
                          <strong>{{
                            attribute
                          }}</strong>
                          foi atualizado de
                          <strong>{{
                            value.old
                          }}</strong>
                          para em branco.
                        </span>

                        <span
                          v-else
                          class="break-words"
                        >
                          O campo
                          <strong>{{
                            attribute
                          }}</strong>
                          foi atualizado de
                          <strong>{{
                            value.old
                          }}</strong>
                          para
                          <strong>{{
                            value.new
                          }}</strong>.
                        </span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </li>
          </ul>
        </div>
      </div>
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
            v-if="status"
            class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden"
          >
            <div class="p-4">
              <div class="flex items-center">
                <div class="shrink-0">
                  <CheckCircleIcon
                    class="h-6 w-6 text-green-400"
                    aria-hidden="true"
                  />
                </div>

                <div
                  class="ml-3 w-0 flex-1 flex justify-between items-center"
                >
                  <p
                    class="text-sm font-medium text-gray-900"
                  >
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
import { defineComponent, onBeforeUnmount, onMounted, ref } from "vue";
import DeveloperLayout from "@/Layouts/DeveloperLayout.vue";
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
    Listbox,
    ListboxButton,
    ListboxLabel,
    ListboxOption,
    ListboxOptions,
} from "@headlessui/vue";

import {
    RefreshIcon,
    TrashIcon,
    PencilIcon,
    PlusIcon,
    CheckCircleIcon,
    SelectorIcon,
} from "@heroicons/vue/solid";

import L from "leaflet";
import "leaflet-draw/dist/leaflet.draw";
import "leaflet/dist/leaflet.css";
import "leaflet-draw/dist/leaflet.draw.css";
import "leaflet.gridlayer.googlemutant";
import "leaflet.marker.slideto";
import "leaflet.fullscreen";
import "leaflet.fullscreen/Control.FullScreen.css";
import "leaflet-ant-path";
import "polyline-encoded";

import startIcon from "@/assets/images/start-icon.svg";
import endIcon from "@/assets/images/end-icon.svg";
import homeIcon from "@/assets/images/anchor.svg";
import plusIcon from "@/assets/images/plus.svg";
import minusIcon from "@/assets/images/minus.svg";

import driverIcon from "@/assets/images/driver-icon.svg";

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
        SelectorIcon,
    },

    props: {
      delivery: {
        type: Object,
        required: true,
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
                    var container = L.DomUtil.create(
                        "div",
                        controlName + " leaflet-bar"
                    );
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
                    map.on(
                        "zoomend zoomlevelschange",
                        this._updateDisabled,
                        this
                    );

                    return container;
                },

                onRemove: function (map) {
                    map.off(
                        "zoomend zoomlevelschange",
                        this._updateDisabled,
                        this
                    );
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

                _createButton: function (
                    html,
                    title,
                    className,
                    container,
                    fn
                ) {
                    var link = L.DomUtil.create("a", className, container);
                    link.innerHTML = html;
                    link.href = "#";
                    link.title = title;

                    L.DomEvent.on(
                        link,
                        "mousedown dblclick",
                        L.DomEvent.stopPropagation
                    )
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
                        position: "topright",
                        forceSeparateButton: true,
                        forcePseudoFullscreen: true,
                        fullscreenElement: false,
                    },
                }).setView(center, 14);

                L.gridLayer
                    .googleMutant({
                        type: "roadmap",
                        styles:mapStyle,
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
                            maxWidth: 200,
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
                })
                    .bindPopup(
                        L.popup({
                            maxWidth: 200,
                        }).setContent(`
          <div>
              <span>Endereço de entrega: </span> <br>
              <span><strong>${props.delivery.steps[1].formatted_address}</strong></span>
            </div>
          `)
                    )
                    .addTo(map);

                markers = new L.featureGroup([userMarker, endMarker]);

                if(props.delivery.steps[1].line !== null) {
                  let polyline = L.polyline(L.PolylineUtil.decode(props.delivery.steps[1].line)).addTo(map);

                  markers.addLayer(polyline);
                }

                map.fitBounds(markers.getBounds(), {
                    padding: [10, 10],
                });

                if (
                    props.delivery.driver &&
                    props.delivery.status !== "COMPLETED" &&
                    props.delivery.status !== "CANCELED" &&
                    props.delivery.status !== "NOT_DELIVERED"
                ) {
                    if(props.delivery.driver.metadata.location) {
                      driverMarker = L.marker(
                        [
                            props.delivery.driver.metadata.location
                                .coordinates[1],
                            props.delivery.driver.metadata.location
                                .coordinates[0],
                        ],
                        {
                            icon: L.icon({
                                iconUrl: driverIcon,
                                iconSize: [30, 30],
                                iconAnchor: [15, 15],
                            }),
                        }
                    )
                        .bindPopup(
                            L.popup({
                                maxWidth: 200,
                            }).setContent(`
              <div>
                  <span>Nome: <strong>${props.delivery.driver.full_name}</strong></span>
                  <br>
                  <span>Status: <strong>${props.delivery.driver.metadata.formatted_status}</strong></span>
                  <br>
                  <span>Atualizado às: <strong>${props.delivery.driver.formatted_updated_at}</strong></span>
              </div>
            `)
                        )
                        .addTo(map);

                      markers.addLayer(driverMarker);

                      map.fitBounds(markers.getBounds(), {
                          padding: [10, 10],
                      }); 
                    }
                }

                if (
                    props.delivery.status !== "COMPLETED" &&
                    props.delivery.status !== "CANCELED" &&
                    props.delivery.status !== "NOT_DELIVERED"
                ) {
                    update();
                }

                if (props.delivery.status === "COMPLETED") {
                    if (props.delivery.route) {
                        setTimeout(() => {
                            const { route } = props.delivery.route;

                            const coordinates = [...route.map(point => point.coordinates)];

                            const polyline = L.polyline.antPath(coordinates, {
                                delay: 1500,
                                // color: '#F57C00',
                                // pulseColor: '#FFFFFF',
                                // stroke: true,
                                // weight: 4,
                                // lineCap: "round",
                                // lineJoin: "round",
                                // dashArray: "10, 15",
                                // dashOffset: "30",
                            }).addTo(map);

                            markers.addLayer(polyline);

                            map.fitBounds(markers.getBounds(), {
                                padding: [10, 10],
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
                    if (
                        props.delivery.status !== "COMPLETED" &&
                        props.delivery.status !== "CANCELED" &&
                        props.delivery.status !== "NOT_DELIVERED"
                    ) {
                        if (props.delivery.driver) {
                            if (!driverMarker) {

                                if(null == props.delivery.driver.metadata.location) return;

                                driverMarker = L.marker(
                                    [
                                        props.delivery.driver.metadata.location
                                            .coordinates[1],
                                        props.delivery.driver.metadata.location
                                            .coordinates[0],
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
                              if(null == props.delivery.driver.metadata.location) return;
                              
                                driverMarker.slideTo(
                                    [
                                        props.delivery.driver.metadata.location
                                            .coordinates[1],
                                        props.delivery.driver.metadata.location
                                            .coordinates[0],
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

        onMounted(initMap);

        onBeforeUnmount(() => {
          if (timer) clearTimeout(timer);
        });

        const setStatus = (stts) => {
            status.value = stts;

            Inertia.reload();

            setTimeout(() => {
                status.value = null;
            }, 2000);
        }

        const redispatch = () => {
          Inertia.post(route('developer.deliveries.redispatch', props.delivery.id), null, {
            preserveScroll: true,
            onSuccess: (res) => {
                setStatus(res.props.status);
            },
          });
        }

        return {
          status,
          redispatch,
        };
    },
});
</script>
