<template>
  <div class="relative bg-white overflow-hidden shadow-xl rounded-lg">
    <div
      id="map"
      style="height: 70vh"
    />

    <div
      style="max-height: calc(70vh - 2.5rem); z-index: 99999"
      class="absolute m-5 top-0 left-0"
    >
      <div class="border border-gray-300 rounded-md shadow-sm">
        <div
          class="rounded-tl-md rounded-tr-md bg-gray-100 px-6 py-5 border-b border-gray-300"
        >
          <h3 class="font-bold text-sm text-gray-700">
            Área de entrega
          </h3>
        </div>

        <div class="bg-white px-6 border-b border-gray-200">
          <nav class="-mb-px flex space-x-8">
            <button
              :class="[
                'whitespace-nowrap py-4 px-1 border-b-2 font-bold',
                {
                  'border-transparent text-gray-500':
                    currentTab != 1,
                  'border-speedapp-orange-500 text-speedapp-orange-600':
                    currentTab == 1,
                },
              ]"
              @click="currentTab = 1"
            >
              Minha área
            </button>

            <button
              :disabled="isEditingArea || !user.area"
              :class="[
                'whitespace-nowrap py-4 px-1 border-b-2 font-bold disabled:text-gray-300 disabled:cursor-not-allowed',
                {
                  'border-transparent text-gray-500':
                    currentTab != 2,
                  'border-speedapp-orange-500 text-speedapp-orange-600':
                    currentTab == 2,
                },
              ]"
              @click="currentTab = 2"
            >
              Taxas e tempo
            </button>
          </nav>
        </div>

        <div
          v-if="currentTab == 1"
          class="flex flex-col rounded-bl-md rounded-br-md bg-white px-6 py-5"
        >
          <button
            v-if="!isEditingArea"
            type="button"
            class="inline-flex justify-center items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-speedapp-orange-600 hover:bg-speedapp-orange-700 focus:outline-none"
            @click="editArea"
          >
            Editar área de entrega
          </button>

          <span
            v-else
            class="inline-flex justify-center items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700"
          >
            Editando área de entrega
          </span>

          <button
            v-if="isEditingArea"
            :disabled="drawLayer === null"
            type="button"
            class="mt-4 inline-flex justify-center items-center px-3 py-2 border text-sm leading-4 font-medium rounded-md text-speedapp-orange-600 hover:text-speedapp-orange-700 focus:outline-none disabled:opacity-50 disabled:text-gray-300 disabled:cursor-not-allowed"
            @click="finishEditing(true)"
          >
            Salvar
          </button>

          <button
            v-if="isEditingArea"
            type="button"
            class="mt-2 inline-flex justify-center items-center px-3 py-2 border text-sm leading-4 font-medium rounded-md text-gray-600 hover:text-gray-700 focus:outline-none"
            @click="finishEditing(false)"
          >
            Cancelar
          </button>
        </div>

        <div
          v-if="currentTab == 2"
          class="relative flex flex-col rounded-bl-md rounded-br-md bg-white overflow-hidden"
        >
          <div  v-if="
              user.charge_style == 'LINE' ||
                user.charge_style == 'ROUTE'
            " class="overflow-y-scroll" style="height: calc(50vh - 56px);">
            <table
           
            class="border-collapse"
          >
            <thead class="bg-gray-50">
              <tr>
                <th
                  class="py-3 px-2.5 pl-12 text-sm font-bold text-gray-700 sticky top-0 z-10 bg-gray-50 bg-opacity-75 backdrop-blur backdrop-filter"
                >
                  Alcance
                </th>
                <th class="py-3 px-2.5 text-sm text-gray-700 sticky top-0 z-10 bg-gray-50 bg-opacity-75 backdrop-blur backdrop-filter">
                  <span class="font-bold">Tempo</span>&nbsp;<span class="font-normal">(mins)</span>
                </th>
                <th
                  class="py-3 px-2.5 text-sm font-bold text-gray-700 sticky top-0 z-10 bg-gray-50 bg-opacity-75 backdrop-blur backdrop-filter"
                >
                  Valor entregador
                </th>
                <th
                  class="py-3 px-2.5 text-sm font-bold text-gray-700 sticky top-0 z-10 bg-gray-50 bg-opacity-75 backdrop-blur backdrop-filter"
                >
                  Valor cobrado da loja
                </th>
                <th
                  class="py-3 px-2.5 pr-12 text-sm font-bold text-gray-700 sticky top-0 z-10 bg-gray-50 bg-opacity-75 backdrop-blur backdrop-filter"
                >
                  Markup
                </th>
              </tr>
            </thead>

            <tbody
              class="bg-white"
            >
              <tr
                v-for="(radius, index) in radiusesForm"
                :key="index"
                class="border-2 border-transparent hover:bg-gray-100"
                @mouseenter="
                  paintPolygon($event, index, radius.rad)
                "
                @mouseleave="
                  unpaintPolygon($event, index, radius.rad)
                "
              >
                <td
                  class="py-2 px-2.5 pl-12 whitespace-nowrap text-sm text-center text-gray-900"
                >
                  <div class="flex justify-center">
                    <span v-if="radius.rad == null">km adicional</span>
                    <span v-else>Até {{ radius.rad }}km</span>
                  </div>
                </td>

                <td class="py-2 px-2.5">
                  <div class="flex justify-center">
                    <input
                      v-model="radius.time"
                      type="number"
                      class="p-1 w-20 shadow-sm focus:ring-speedapp-orange-500 focus:border-speedapp-orange-500 block text-center sm:text-sm border-gray-300 rounded-md"
                    >
                  </div>
                </td>

                <td class="py-2 px-2.5">
                  <div class="flex justify-center">
                    <input
                      v-model="radius.formatted_paid"
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
                      class="p-1 w-20 shadow-sm focus:ring-speedapp-orange-500 focus:border-speedapp-orange-500 block text-center sm:text-sm border-gray-300 rounded-md"
                    >
                  </div>
                </td>

                <td class="py-2 px-2.5">
                  <div class="flex justify-center">
                    <input
                      v-model="radius.formatted_charged"
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
                      class="p-1 w-20 shadow-sm focus:ring-speedapp-orange-500 focus:border-speedapp-orange-500 block text-center sm:text-sm border-gray-300 rounded-md"
                    >
                  </div>
                </td>

                <td class="py-2 px-2.5 pr-12">
                  <div class="flex justify-center">
                    <span>
                      {{
                        $filters.currency(
                          $filters.rawCurrency(
                            radius.formatted_charged
                          ) -
                            $filters.rawCurrency(
                              radius.formatted_paid
                            )
                        )
                      }}
                    </span>
                  </div>
                </td>
              </tr>
            </tbody>

            <tfoot v-if="hasChanges" class="bg-gray-50">
              <tr>
                <th colspan="5"
                  class="py-3 px-2.5 text-sm font-bold text-gray-700 sticky bottom-0 z-10 bg-gray-50 bg-opacity-75 backdrop-blur backdrop-filter"
                >
                  <div class="flex space-x-2.5">
                    <button
                      @click="undoForm"
                      type="button"
                      class="w-full inline-flex justify-center items-center px-3 py-2 border border-gray-200 text-sm leading-4 font-medium rounded-md text-gray-600 hover:text-gray-700 focus:outline-none"
                    >
                      Cancelar
                    </button>

                    <button
                      @click="saveForm"
                      type="button"
                      class="w-full inline-flex justify-center items-center px-3 py-2 border border-gray-200 text-sm leading-4 font-medium rounded-md text-speedapp-orange-600 hover:text-speedapp-orange-700 focus:outline-none"
                    >
                      Salvar
                    </button>
                  </div>
                </th>
              </tr>
            </tfoot>
            </table>
          </div>

          <table v-if="user.charge_style == 'DAILY'" class="px-6 py-5">
            <thead>
              <tr>
                <th class="pt-5 pr-2.5 pl-6 text-sm text-gray-700">
                  <span class="font-bold">Período</span>&nbsp;<span class="font-normal">(mins)</span>
                </th>
                <th
                  class="pt-5 px-2.5 text-sm font-bold text-gray-700"
                >
                  Valor pago pela loja
                </th>
                <th
                  class="pt-5 pl-2.5 pr-6 text-sm font-bold text-gray-700"
                >
                  Markup
                </th>
              </tr>
            </thead>

            <tbody>
              <tr>
                <td class="py-3 pr-2.5 pl-6">
                  <div class="flex justify-center">
                    <input
                      v-model="chargeOptionsForm.period"
                      type="number"
                      max="720"
                      min="120"
                      class="p-1 w-20 shadow-sm focus:ring-speedapp-orange-500 focus:border-speedapp-orange-500 block text-center sm:text-sm border-gray-300 rounded-md"
                    >
                  </div>
                </td>

                <td class="py-3 px-2.5">
                  <div class="flex justify-center">
                    <input
                      v-model.lazy="
                        chargeOptionsForm.price
                      "
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
                      class="p-1 w-20 shadow-sm focus:ring-speedapp-orange-500 focus:border-speedapp-orange-500 block text-center sm:text-sm border-gray-300 rounded-md"
                    >
                  </div>
                </td>

                <td class="py-3 pl-2.5 pr-6">
                  <div class="flex justify-center">
                    <input
                      v-model.lazy="
                        chargeOptionsForm.markup
                      "
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
                      class="p-1 w-20 shadow-sm focus:ring-speedapp-orange-500 focus:border-speedapp-orange-500 block text-center sm:text-sm border-gray-300 rounded-md"
                    >
                  </div>
                </td>
              </tr>
            </tbody>

            <tfoot v-if="hasChanges">
              <tr>
                <th colspan="3"
                  class="py-3 px-2.5 text-sm font-bold text-gray-700"
                >
                  <div class="flex flex-col space-y-2.5">
                    <button
                      @click="saveForm"
                      type="button"
                      class="w-full inline-flex justify-center items-center px-3 py-2 border border-gray-200 text-sm leading-4 font-medium rounded-md text-speedapp-orange-600 hover:text-speedapp-orange-700 focus:outline-none"
                    >
                      Salvar
                    </button>

                    <button
                      @click="undoForm"
                      type="button"
                      class="w-full inline-flex justify-center items-center px-3 py-2 border border-gray-200 text-sm leading-4 font-medium rounded-md text-gray-600 hover:text-gray-700 focus:outline-none"
                    >
                      Cancelar
                    </button>
                  </div>
                </th>
              </tr>
            </tfoot>
          </table>

          <table v-if="user.charge_style == 'PACKAGE'" class="px-6 py-5">
            <thead>
              <tr>
                <th
                  class="pt-5 pr-2.5 pl-12 text-sm font-bold text-gray-700"
                >
                  Valor pago pela loja (por pacote)
                </th>
                <th
                  class="pt-5 pl-2.5 pr-12 text-sm font-bold text-gray-700"
                >
                  Markup (por pacote)
                </th>
              </tr>
            </thead>

            <tbody>
              <tr>
                <td class="py-3">
                  <div class="flex justify-center">
                    <input
                      v-model.lazy="
                        chargeOptionsForm.price
                      "
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
                      class="p-1 w-20 shadow-sm focus:ring-speedapp-orange-500 focus:border-speedapp-orange-500 block text-center sm:text-sm border-gray-300 rounded-md"
                    >
                  </div>
                </td>

                <td class="py-3">
                  <div class="flex justify-center">
                    <input
                      v-model.lazy="
                        chargeOptionsForm.markup
                      "
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
                      class="p-1 w-20 shadow-sm focus:ring-speedapp-orange-500 focus:border-speedapp-orange-500 block text-center sm:text-sm border-gray-300 rounded-md"
                    >
                  </div>
                </td>
              </tr>
            </tbody>

            <tfoot v-if="hasChanges">
              <tr>
                <th colspan="2"
                  class="py-3 px-2.5 text-sm font-bold text-gray-700"
                >
                  <div class="flex flex-col space-y-2.5">
                    <button
                      @click="saveForm"
                      type="button"
                      class="w-full inline-flex justify-center items-center px-3 py-2 border border-gray-200 text-sm leading-4 font-medium rounded-md text-speedapp-orange-600 hover:text-speedapp-orange-700 focus:outline-none"
                    >
                      Salvar
                    </button>

                    <button
                      @click="undoForm"
                      type="button"
                      class="w-full inline-flex justify-center items-center px-3 py-2 border border-gray-200 text-sm leading-4 font-medium rounded-md text-gray-600 hover:text-gray-700 focus:outline-none"
                    >
                      Cancelar
                    </button>
                  </div>
                </th>
              </tr>
            </tfoot>
          </table>

          <table v-if="user.charge_style == 'OPEN'">
            <thead>
              <tr>
                <th
                  class="pt-5 px-2.5 text-sm font-bold text-gray-700"
                >
                  Markup (por entrega)
                </th>
              </tr>
            </thead>

            <tbody>
              <tr>
                <td class="py-3">
                  <div class="flex justify-center">
                    <input
                      v-model.lazy="
                        chargeOptionsForm.markup
                      "
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
                      class="p-1 w-20 shadow-sm focus:ring-speedapp-orange-500 focus:border-speedapp-orange-500 block text-center sm:text-sm border-gray-300 rounded-md"
                    >
                  </div>
                </td>
              </tr>
            </tbody>

            <tfoot v-if="hasChanges">
              <tr>
                <th
                  class="py-3 px-2.5 text-sm font-bold text-gray-700"
                >
                  <div class="flex flex-col space-y-2.5">
                    <button
                      @click="saveForm"
                      type="button"
                      class="w-full inline-flex justify-center items-center px-3 py-2 border border-gray-200 text-sm leading-4 font-medium rounded-md text-speedapp-orange-600 hover:text-speedapp-orange-700 focus:outline-none"
                    >
                      Salvar
                    </button>

                    <button
                      @click="undoForm"
                      type="button"
                      class="w-full inline-flex justify-center items-center px-3 py-2 border border-gray-200 text-sm leading-4 font-medium rounded-md text-gray-600 hover:text-gray-700 focus:outline-none"
                    >
                      Cancelar
                    </button>
                  </div>
                </th>
              </tr>
            </tfoot>
          </table>

        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Inertia } from "@inertiajs/inertia";

import { defineComponent, onMounted, ref, watch } from "vue";

import L from "leaflet";
import "leaflet-draw/dist/leaflet.draw";
import "leaflet/dist/leaflet.css";
import "leaflet-draw/dist/leaflet.draw.css";
import "leaflet.gridlayer.googlemutant";

import startIcon from "@/assets/images/start-icon.svg";
import homeIcon from "@/assets/images/anchor.svg";
import plusIcon from "@/assets/images/plus.svg";
import minusIcon from "@/assets/images/minus.svg";

import mapStyle from "@/assets/map-style.json";


import differenceWith from "lodash/differenceWith";
import isEqual from "lodash/isEqual";

// TODO: https://stackoverflow.com/questions/60549528/cut-holes-in-polygon-with-leaflet-geoman
// TODO: detect if drawLayer has changed
// TODO: highlight line and radius when inputting values
export default defineComponent({
    props: {
        user: {
            type: Object,
            required: true,
        },
    },
    emits: ["setStatus"],
    setup(props, { emit }) {
        const radiusesForm = ref(
            JSON.parse(JSON.stringify(props.user.radiuses))
        );

        const chargeOptionsForm = ref(
            JSON.parse(JSON.stringify(props.user.charge_options))
        );

        const hasChanges = ref(false);

        const errors = ref(null);

        const currentTab = ref(1);

        let circles = [];

        const isEditingArea = ref(false);

        let center = [
            props.user.address.position.coordinates[1],
            props.user.address.position.coordinates[0],
        ];

        let editableLayers = null;

        let drawControl = null;
        const drawLayer = ref(null);

        let map = null;
        let polygon = null;

        const editArea = () => {
            isEditingArea.value = true;

            if (polygon) {
                polygon.editing.enable();
            } else {
                drawControl = new L.Draw.Polygon(map, {
                    shapeOptions: {
                        color: "#F57C00",
                    },
                });

                drawControl.enable();
            }
        };

        const finishEditing = (save) => {
            isEditingArea.value = false;

            if (polygon) {
                polygon.editing.disable();
            }

            if (drawLayer.value === null) {
                // Display popup saying the user needs to draw a polygon before saving
                // Disable save button if the user has not draw anything?
                return;
            }

            if (save) {
                Inertia.post(
                    route("developer.users.area.store", props.user.id),
                    {
                        coordinates:
                            drawLayer.value.toGeoJSON().geometry.coordinates,
                    },
                    {
                        onSuccess: async (res) => {
                            emit("setStatus", res.props.status);

                            // if(drawControl) {
                            //     drawControl.disable();
                            // }

                            Inertia.reload(["user", "radiuses"]);

                            if (polygon) {
                                map.removeLayer(polygon);
                            }

                            const points = props.user.area.coordinates[0].map(
                                (point) => [point[1], point[0]]
                            );

                            polygon = new L.polygon([points], {
                                color: "#F57C00",
                            });

                            if (editableLayers) {
                                editableLayers.remove(drawLayer.value);
                            }

                            map.addLayer(polygon);

                            drawLayer.value = null;

                            polygon.on("edit", (e) => {
                                drawLayer.value = e.target;
                            });

                            if (props.user.charge_style == "LINE") {
                                drawRadiuses();
                            } else {
                                // map.fitBounds(polygon.getBounds());
                            }

                            radiusesForm.value = JSON.parse(
                                JSON.stringify(props.user.radiuses)
                            );
                        },
                    }
                );
            } else {
                if (drawLayer.value) {
                    if (editableLayers) {
                        editableLayers.removeLayer(drawLayer.value);
                    } else {
                        const points = props.user.area.coordinates[0].map(
                            (point) => [point[1], point[0]]
                        );

                        polygon = new L.polygon([points], {
                            color: "#F57C00",
                        });

                        drawLayer.value.remove();

                        map.addLayer(polygon);

                        polygon.on("edit", (e) => {
                            drawLayer.value = e.target;
                        });
                    }

                    drawLayer.value = null;
                } else {
                    if (drawControl) {
                        drawControl.disable();
                    }
                }
            }
        };

        const saveForm = () => {
            if (props.user.lastmile) {
                Inertia.post(
                    route(
                        "developer.users.area.chargeOptions.store",
                        props.user.id
                    ),
                    {
                        charge_style: props.user.charge_style,
                        charge_options: chargeOptionsForm.value,
                    },
                    {
                        preserveScroll: true,
                        onSuccess: (res) => {
                            emit("setStatus", res.props.status);

                            Inertia.reload(["user"]);

                            chargeOptionsForm.value = JSON.parse(
                                    JSON.stringify(props.user.charge_options)
                                );
                        },
                        onError: (err) => {
                            console.log(err);
                            errors.value = err;
                        },
                    }
                );
            }

            if (!props.user.lastmile) {
                Inertia.post(
                    route("developer.users.area.radiuses.store", props.user.id),
                    {
                        radiuses: radiusesForm.value,
                    },
                    {
                        preserveScroll: true,
                        onSuccess: (res) => {
                            emit("setStatus", res.props.status);

                            Inertia.reload(["user", "radiuses"]);

                            radiusesForm.value = JSON.parse(
                                JSON.stringify(props.user.radiuses)
                            );
                        },
                        onError: (err) => {
                            console.log(err);
                            errors.value = err;
                        },
                    }
                );
            }
        };

        const undoForm = async () => {
            if (props.user.lastmile) {
                chargeOptionsForm.value = JSON.parse(
                    JSON.stringify(props.user.charge_options)
                );
            }

            if (!props.user.lastmile) {
                radiusesForm.value = JSON.parse(
                    JSON.stringify(props.user.radiuses)
                );
            }
        };

        const paintPolygon = (event, index, radius) => {
            if (radius == null) return;

            const tr = event.target;

            let otherTr =
                index === 0 ? (otherTr = tr.nextSibling) : tr.previousSibling;

            tr.classList.add("border-gray-300");
            tr.classList.remove("border-transparent");

            if (index !== 0) {
                otherTr.classList.add(
                    "border-b-2",
                    "border-l-2",
                    "border-r-2",
                    "border-t-2",
                    "border-b-gray-300",
                    "border-l-transparent",
                    "border-r-transparent",
                    "border-t-transparent"
                );
                otherTr.classList.remove("border-2", "border-transparent");
            }

            const polygon = circles.find(
                (c) => c.getRadius() === radius * 1000
            );

            if (polygon) {
                polygon.setStyle({
                    fillOpacity: 0.2,
                });
            }
        };

        const unpaintPolygon = (event, index, radius) => {
            if (radius == null) return;

            const tr = event.target;

            let otherTr =
                index === 0 ? (otherTr = tr.nextSibling) : tr.previousSibling;

            tr.classList.add("border-transparent");
            tr.classList.remove("border-gray-300");

            if (index !== 0) {
                otherTr.classList.add("border-2", "border-transparent");
                otherTr.classList.remove(
                    "border-b-2",
                    "border-l-2",
                    "border-r-2",
                    "border-t-2",
                    "border-b-gray-300",
                    "border-l-transparent",
                    "border-r-transparent",
                    "border-t-transparent"
                );
            }

            const polygon = circles.find(
                (c) => c.getRadius() === radius * 1000
            );

            if (polygon) {
                polygon.setStyle({
                    fillOpacity: 0.0,
                });
            }
        };

        const drawRadiuses = () => {
            circles.forEach((c) => c.remove());

            circles = [];

            const rads = JSON.parse(JSON.stringify(props.user.radiuses));

            const circleOptions = {
                stroke: true,
                fill: true,
                fillOpacity: 0.0,
                fillColor: "#F57C00",
                color: "#000",
                weight: 1,
                opacity: 0.5,
                dashArray: "5, 5",
                dashOffset: "0",
                // bubblingMouseEvents: false,
            };

            rads.reverse().forEach((radius) => {
                const circle = L.circle(center, {
                    ...circleOptions,
                    radius: radius.rad * 1000,
                })
                    .on("mouseover", function () {
                        const el = document.querySelectorAll(
                            `[data-polygon="${radius.rad}"]`
                        );

                        if (el.length == 1) {
                            el[0].scrollIntoViewIfNeeded();
                            el[0].classList.add(
                                "bg-gray-100",
                                "border-gray-300"
                            );
                            el[0].classList.remove(
                                "bg-white",
                                "border-transparent"
                            );
                        }

                        this.setStyle({
                            fillOpacity: 0.2,
                        });
                    })
                    .on("mouseout", function () {
                        const el = document.querySelectorAll(
                            `[data-polygon="${radius.rad}"]`
                        );

                        if (el.length == 1) {
                            el[0].classList.add(
                                "bg-white",
                                "border-transparent"
                            );
                            el[0].classList.remove(
                                "bg-gray-100",
                                "border-gray-300"
                            );
                        }

                        this.setStyle({
                            fillOpacity: 0.0,
                        });
                    })
                    .addTo(map);

                circles.push(circle);
            });

            if (circles.length > 0) {
                map.fitBounds(circles[1].getBounds(), {
                    padding: [10, 10],
                });
            } else {
                map.fitBounds(polygon.getBounds());
            }
        };

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
                    if (circles.length > 0) {
                        map.fitBounds(circles[1].getBounds(), {
                            padding: [10, 10],
                        });
                    } else {
                        map.fitBounds(polygon.getBounds());
                    }
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

                const myIcon = L.icon({
                    iconUrl: startIcon,
                    iconSize: [40, 40],
                    iconAnchor: [20, 20],
                });

                L.marker(center, {
                    icon: myIcon,
                }).addTo(map);

                if (!props.user.area) {
                    editableLayers = new L.FeatureGroup();
                    map.addLayer(editableLayers);

                    map.on("draw:created", (e) => {
                        const type = e.layerType;
                        const layer = e.layer;

                        if (type === "polygon") {
                            editableLayers.addLayer(layer);
                            drawLayer.value = layer;
                        }
                    });
                } else {
                    const points = props.user.area.coordinates[0].map(
                        (point) => [point[1], point[0]]
                    );

                    polygon = new L.polygon([points], {
                        color: "#F57C00",
                    });

                    polygon.on("edit", (e) => {
                        drawLayer.value = e.target;
                    });

                    map.addLayer(polygon);

                    if (
                        props.user.radiuses &&
                        props.user.charge_style == "LINE"
                    ) {
                        drawRadiuses();
                    } else {
                        map.fitBounds(polygon.getBounds());
                    }
                }

                // map.on('layerremove', (e) => {
                //     console.log('layerremove');
                //     console.log(e);
                // });
            });

            if (props.user.lastmile) {
                watch(
                    chargeOptionsForm,
                    (n, o) => {
                        hasChanges.value = !isEqual(
                            n,
                            props.user.charge_options
                        );
                    },
                    {
                        deep: true,
                    }
                );
            }

            if (!props.user.lastmile) {
                watch(
                    radiusesForm,
                    (n, o) => {
                        hasChanges.value =
                            differenceWith(n, props.user.radiuses, isEqual)
                                .length > 0;
                    },
                    {
                        deep: true,
                    }
                );
            }
        };

        onMounted(initMap);

        return {
            currentTab,
            isEditingArea,
            editArea,
            finishEditing,
            saveForm,
            undoForm,
            paintPolygon,
            unpaintPolygon,
            radiusesForm,
            hasChanges,
            drawLayer,
            chargeOptionsForm,
        };
    },
});
</script>
