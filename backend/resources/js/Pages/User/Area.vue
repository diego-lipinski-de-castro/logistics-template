<template>
  <user-layout title="Áreas de entrega">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Áreas de entrega
      </h2>
    </template>

    <div class="py-12">
      <div
        class="relative bg-white overflow-hidden shadow-xl sm:rounded-lg"
      >
        <div
          id="map"
          style="height: 70vh"
        />

        <div
          v-if="user.charge_style != 'OPEN'"
          style="max-height: calc(70vh - 2.5rem); z-index: 99999"
          class="absolute m-5 top-0 left-0 overflow-y-scroll"
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
                <span
                  class="whitespace-nowrap py-4 px-1 border-b-2 font-bold disabled:text-gray-300 disabled:cursor-not-allowed border-speedapp-orange-500 text-speedapp-orange-600"
                >
                  Taxas e tempo
                </span>
              </nav>
            </div>

            <div
              class="flex flex-col rounded-bl-md rounded-br-md bg-white px-6 py-5"
            >
              <table
                v-if="
                  user.charge_style == 'LINE' ||
                    user.charge_style == 'ROUTE'
                "
                class="border-collapse"
              >
                <thead>
                  <tr>
                    <th
                      class="py-1 px-2.5 text-sm font-bold text-gray-700"
                    >
                      Alcance
                    </th>
                    <th
                      class="py-1 px-2.5 text-sm text-gray-700"
                    >
                      <span class="font-bold">Tempo</span>&nbsp;<span class="font-normal">(mins)</span>
                    </th>
                    <th
                      class="py-1 px-2.5 text-sm font-bold text-gray-700"
                    >
                      Valor
                    </th>
                  </tr>
                </thead>

                <tbody
                  style="
                                        max-height: calc(70vh - 5rem - 280px);
                                    "
                  class="overflow-y-scroll overscroll-y-contain"
                >
                  <tr
                    v-for="(radius, index) in user.radiuses"
                    :key="index"
                    class="border-2 border-transparent hover:bg-gray-100"
                    @mouseenter="
                      paintPolygon(
                        $event,
                        index,
                        radius.rad
                      )
                    "
                    @mouseleave="
                      unpaintPolygon(
                        $event,
                        index,
                        radius.rad
                      )
                    "
                  >
                    <td
                      class="py-2 whitespace-nowrap text-sm text-center text-gray-900"
                    >
                      <div class="flex justify-center">
                        <span>Até
                          {{ radius.rad }}km</span>
                      </div>
                    </td>

                    <td class="py-2">
                      <div class="flex justify-center">
                        <span>{{ radius.time }}</span>
                      </div>
                    </td>

                    <td class="py-2 px-2.5">
                      <div class="flex justify-center">
                        <span>{{
                          radius.formatted_charged
                        }}</span>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>

              <table v-if="user.charge_style == 'DAILY'">
                <thead>
                  <tr>
                    <th
                      class="py-1 px-2.5 text-sm text-gray-700"
                    >
                      <span class="font-bold">Período</span>&nbsp;<span class="font-normal">(mins)</span>
                    </th>
                    <th
                      class="py-1 px-2.5 text-sm font-bold text-gray-700"
                    >
                      Valor pago
                    </th>
                  </tr>
                </thead>

                <tbody>
                  <tr>
                    <td class="py-2">
                      <div class="flex justify-center">
                        <input
                          v-model="
                            chargeOptionsForm.period
                          "
                          type="number"
                          max="720"
                          min="120"
                          class="p-1 w-20 shadow-sm focus:ring-speedapp-orange-500 focus:border-speedapp-orange-500 block text-center sm:text-sm border-gray-300 rounded-md"
                        >
                      </div>
                    </td>

                    <td class="py-2">
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
                  </tr>
                </tbody>
              </table>

              <table v-if="user.charge_style == 'PACKAGE'">
                <thead>
                  <tr>
                    <th
                      class="py-1 px-2.5 text-sm font-bold text-gray-700"
                    >
                      Valor pago (por pacote)
                    </th>
                  </tr>
                </thead>

                <tbody>
                  <tr>
                    <td class="py-2">
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
                  </tr>
                </tbody>
              </table>

              <div class="mt-2.5">
                <div
                  v-if="hasChanges"
                  class="mt-6 flex justify-between items-center bg-white rounded-md border-2 border-transparent"
                  @click="saveForm"
                >
                  <button
                    type="button"
                    class="w-full inline-flex justify-center items-center px-3 py-2 border text-sm leading-4 font-medium rounded-md text-speedapp-orange-600 hover:text-speedapp-orange-700 focus:outline-none"
                  >
                    Salvar
                  </button>
                </div>

                <div
                  v-if="hasChanges"
                  class="mt-2 flex justify-between items-center bg-white rounded-md border-2 border-transparent"
                  @click="undoForm"
                >
                  <button
                    type="button"
                    class="w-full inline-flex justify-center items-center px-3 py-2 border text-sm leading-4 font-medium rounded-md text-gray-600 hover:text-gray-700 focus:outline-none"
                  >
                    Cancelar
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </user-layout>
</template>

<script>
import { defineComponent, onMounted, ref, watch } from "vue";
import UserLayout from "@/Layouts/UserLayout.vue";

import L from "leaflet";
import "leaflet-draw/dist/leaflet.draw";
import "leaflet/dist/leaflet.css";
import "leaflet-draw/dist/leaflet.draw.css";
import "leaflet.gridlayer.googlemutant";
import "leaflet.fullscreen";
import "leaflet.fullscreen/Control.FullScreen.css";

import startIcon from "@/assets/images/start-icon.svg";
import homeIcon from "@/assets/images/anchor.svg";
import plusIcon from "@/assets/images/plus.svg";
import minusIcon from "@/assets/images/minus.svg";

import mapStyle from "@/assets/map-style.json";


import isEqual from "lodash/isEqual";
import { Inertia } from "@inertiajs/inertia";

// TODO: https://stackoverflow.com/questions/60549528/cut-holes-in-polygon-with-leaflet-geoman
// TODO: highlight line and radius when inputting values
export default defineComponent({
    components: {
        UserLayout,
    },
    props: {
        user: {
            type: Object,
            required: true,
        },
    },
    setup(props) {
        const chargeOptionsForm = ref(
            JSON.parse(JSON.stringify(props.user.charge_options))
        );

        const hasChanges = ref(false);

        let circles = [];

        let center = [
            props.user.address.position.coordinates[1],
            props.user.address.position.coordinates[0],
        ];

        let map = null;
        let polygon = null;

        const saveForm = () => {
            if (
                props.user.charge_style == "DAILY" ||
                props.user.charge_style == "PACKAGE"
            ) {
                Inertia.post(
                    route("user.area.chargeOptions.store"),
                    {
                        charge_style: props.user.charge_style,
                        charge_options: chargeOptionsForm.value,
                    },
                    {
                        onSuccess: (res) => {
                            console.log(res);
                            Inertia.reload(["user"]);

                            chargeOptionsForm.value = JSON.parse(
                                JSON.stringify(props.user.charge_options)
                            );
                        },
                        onError: (err) => {
                            console.log(err);
                        },
                    }
                );
            }
        };

        const undoForm = async () => {
            if (
                props.user.charge_style == "DAILY" ||
                props.user.charge_style == "PACKAGE"
            ) {
                chargeOptionsForm.value = JSON.parse(
                    JSON.stringify(props.user.charge_options)
                );
            }
        };

        const paintPolygon = (event, index, radius) => {
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
                    var controlName = "gin-control-zoom",
                        container = L.DomUtil.create(
                            "div",
                            controlName + " leaflet-bar"
                        ),
                        options = this.options;

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
                        styles: mapStyle,
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

                if (props.user.area) {
                    const points = props.user.area.coordinates[0].map(
                        (point) => [point[1], point[0]]
                    );

                    polygon = new L.polygon([points], {
                        color: "#F57C00",
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
            });

            if (
                props.user.charge_style == "DAILY" ||
                props.user.charge_style == "PACKAGE"
            ) {
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
        };

        onMounted(initMap);

        return {
            paintPolygon,
            unpaintPolygon,
            chargeOptionsForm,
            saveForm,
            undoForm,
            hasChanges,
        };
    },
});
</script>
