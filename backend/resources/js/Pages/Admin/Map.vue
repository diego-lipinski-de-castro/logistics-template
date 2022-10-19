<template>
  <admin-layout title="Mapa">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Mapa
      </h2>
    </template>

    <div class="py-12">
      <div class="px-4 sm:px-6 lg:px-8">
        <div>
          <div
            class="grid gap-6 grid-cols-1 sm:grid-cols-2 md:grid-cols-4"
          >
            <div
              v-for="(item, index) in stats"
              :key="index"
              class="bg-white p-5 shadow-md rounded-md overflow-hidden flex justify-between items-center select-none"
            >
              <div class="flex items-center">
                <img
                  :src="item.icon"
                  class="rounded-md h-10 w-10"
                  aria-hidden="true"
                >

                <div class="ml-5">
                  <p
                    class="text-sm font-medium text-gray-500 truncate"
                  >
                    {{ item.name }}
                  </p>

                  <p
                    class="text-2xl font-semibold text-gray-900"
                  >
                    {{ item.stat }}
                  </p>
                </div>
              </div>

              <EyeIcon
                v-if="item.layerVisible"
                class="w-6 h-6 text-gray-500 hover:text-gray-900 cursor-pointer"
                @click="toggleLayer(item)"
              />
              <EyeOffIcon
                v-else
                class="w-6 h-6 text-gray-500 hover:text-gray-900 cursor-pointer"
                @click="toggleLayer(item)"
              />
            </div>
          </div>
        </div>

        <div class="grid gap-6 grid-cols-1 md:grid-cols-4 mt-5">
          <div
            class="col-span-3 relative bg-white overflow-hidden shadow-md rounded-md"
          >
            <div
              id="map"
              class="z-40"
              style="height: 60vh"
            />

            <span
              class="flex items-center absolute top-2 left-2 rounded-md z-50 px-2.5 py-1.5 text-sm font-medium bg-white shadow-sm"
            >
              Atualizando automaticamente

              <Spinner class="ml-2.5" />
            </span>
          </div>

          <div class="col-span-1 hidden md:block">
            <div
              style="height: 60vh"
              class="flow-root bg-white overflow-hidden shadow-md rounded-md"
            >
              <nav
                class="flex flex-col md:flex-row relative z-0 divide-x divide-gray-200"
              >
                <span
                  class="cursor-pointer group relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4 text-sm font-medium text-center hover:bg-gray-50 focus:z-10 text-gray-900"
                >
                  <span>Entregadores</span>
                  <span
                    class="absolute inset-x-0 bottom-0 h-0.5 bg-speedapp-orange-500"
                  />
                </span>
              </nav>
                            
              <div class="p-5">
                <input
                  v-model="search"
                  type="text"
                  placeholder="Pesquisar"
                  class="
                                        block
                                        w-full
                                        border-gray-300
                                        rounded-md
                                        placeholder-gray-400
                                        focus:border-speedapp-orange-300 focus:ring focus:ring-speedapp-orange-200 focus:ring-opacity-50
                                    "
                >
              </div>

              <ul
                role="list"
                style="height: calc(60vh - 134px + 1.25rem)"
                class="-mt-5 divide-y divide-gray-200 overflow-y-scroll px-5"
              >
                <li
                  v-for="(driver, index) in driversByName"
                  :key="index"
                  class="py-4"
                >
                  <div class="flex items-center space-x-4">
                    <div class="shrink-0">
                      <span
                        :class="[
                          'shrink-0 inline-block h-2 w-2 rounded-full',
                          {
                            'bg-green-400':
                              driver.metadata
                                .status ===
                              'ONLINE',
                            'bg-yellow-500':
                              driver.metadata
                                .status ===
                              'BUSY',
                            'bg-red-700':
                              driver.metadata
                                .status ===
                              'OFFLINE',
                          },
                        ]"
                      />
                    </div>
                    <div class="flex-1 min-w-0">
                      <p
                        class="text-sm font-medium text-gray-900 truncate"
                      >
                        {{ driver.full_name }}
                      </p>
                      <p
                        class="text-sm text-gray-500 truncate"
                      >
                        Atualizado {{
                          driver.formatted_updated_at
                        }}
                      </p>
                    </div>
                    <div>
                      <button
                        v-if="driver.metadata.location !== null"
                        class="mr-5 inline-flex items-center shadow-sm px-2.5 py-0.5 border border-gray-300 text-sm leading-5 font-medium rounded-full text-gray-700 bg-white hover:bg-gray-50"
                        @click="selectDriver(driver)"
                      >
                        Ver
                      </button>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
          </div>
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
            v-if="status.message"
            class="max-w-sm w-full bg-white shadow-md rounded-md pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden"
          >
            <div class="p-4">
              <div class="flex items-center">
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

                <div
                  class="ml-3 w-0 flex-1 flex justify-between items-center"
                >
                  <p
                    class="text-sm font-medium text-gray-900"
                  >
                    {{ status.message }}
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
import {
    computed,
    defineComponent,
    onBeforeUnmount,
    onMounted,
    ref,
} from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Inertia } from "@inertiajs/inertia";

import L from "leaflet";
import "leaflet-draw/dist/leaflet.draw";
import "leaflet/dist/leaflet.css";
import "leaflet-draw/dist/leaflet.draw.css";
import "leaflet.gridlayer.googlemutant";
import "leaflet.marker.slideto";
import "leaflet.fullscreen";
import "leaflet.fullscreen/Control.FullScreen.css";

import driverIcon from "@/assets/images/driver-icon.svg";
import driverIdleIcon from "@/assets/images/driver-idle-icon.svg";
import driverOfflineIcon from "@/assets/images/driver-offline-icon.svg";

import homeIcon from "@/assets/images/anchor.svg";
import plusIcon from "@/assets/images/plus.svg";
import minusIcon from "@/assets/images/minus.svg";

import mapStyle from "@/assets/map-style.json";

import { ArrowSmDownIcon, ArrowSmUpIcon } from "@heroicons/vue/solid";
import {
    UsersIcon,
    EyeIcon,
    EyeOffIcon,
    CheckCircleIcon,
    XCircleIcon,
} from "@heroicons/vue/outline";

export default defineComponent({
    components: {
        AdminLayout,
        ArrowSmDownIcon,
        ArrowSmUpIcon,
        UsersIcon,
        EyeIcon,
        EyeOffIcon,
        CheckCircleIcon,
        XCircleIcon,
    },

    props: {
        drivers: {
          type: Array,
          default: () => [],
        },
        bounds: {
          type: Array,
          default: () => [],
        },
    },

    setup(props) {
        let timer = null;
        let timeout = 15000;

        const status = ref({
            type: null,
            message: null,
        });

        const search = ref(null);

        const selectedDriver = ref(null);

        let map = null;

        let busyLayer = new L.LayerGroup();
        let idleLayer = new L.LayerGroup();
        let offlineLayer = new L.LayerGroup();

        let drivers = [];

        const busyDriversCount = computed(() => {
            return props.drivers.filter((driver) => {
                return driver.metadata.status === "BUSY";
            }).length;
        });

        const idleDriversCount = computed(() => {
            return props.drivers.filter((driver) => {
                return driver.metadata.status === "ONLINE";
            }).length;
        });

        const offlineDriversCount = computed(() => {
            return props.drivers.filter((driver) => {
                return driver.metadata.status === "OFFLINE";
            }).length;
        });

        const tabIndex = ref(0);

        const driversByName = computed(() => {

            if(tabIndex.value !== 0) return props.drivers;

            if(!search.value) return props.drivers;

            return props.drivers.filter((driver) => {
                return driver.full_name.toLowerCase().includes(search.value.toLowerCase())
            })
        });

        const selectDriver = (driver) => {
            if(driver.metadata.location === null) return;

            const point = [
                driver.metadata.location.coordinates[1],
                driver.metadata.location.coordinates[0],
            ];

            map.setView(point, 17);

            let item = drivers.find((d) => d.id == driver.id);

            item.marker.openPopup();
        }

        const stats = ref([
            {
                name: "Ocupados",
                stat: busyDriversCount.value,
                icon: driverIcon,
                layer: busyLayer,
                layerVisible: true,
            },
            {
                name: "Aguardando",
                stat: idleDriversCount.value,
                icon: driverIdleIcon,
                layer: idleLayer,
                layerVisible: true,
            },
            {
                name: "Offline",
                stat: offlineDriversCount.value,
                icon: driverOfflineIcon,
                layer: offlineLayer,
                layerVisible: true,
            },
        ]);

        const toggleLayer = (item) => {
            if (map.hasLayer(item.layer)) {
                map.removeLayer(item.layer);
            } else {
                map.addLayer(item.layer);
            }

            item.layerVisible = !item.layerVisible;
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

                    if(props.bounds.length == 1) {
                        map.setView(props.bounds[0], 14);
                    } else {
                        map.fitBounds(props.bounds, {
                            padding: [10, 10],
                        });
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

        // status
        const setStatus = (type, message) => {
            status.value = { type, message };

            setTimeout(() => {
                status.value = {
                    type: null,
                    message: null,
                };
            }, 2000);
        };

        const updateDriverStatus = (event) => {
            const [driver, status] = event;

            Inertia.post(route("admin.drivers.updateStatus", driver), {
                status: status === 1 ? 'ONLINE' : 'OFFLINE'
            }, {
                onSuccess: (res) => {
                    console.log("res", res);

                    setStatus("success", res.props.status);

                    update();
                },
                onError: (error) => {
                    console.log("error", error);

                    setStatus("error", error.message);
                },
            });
        }

        const addDriverToMap = (driver) => {
            if (driver.metadata.location == null) return;

            const point = [
                driver.metadata.location.coordinates[1],
                driver.metadata.location.coordinates[0],
            ];

            const popup = L.popup().setContent(getDriverPopupContent(driver));

            const marker = L.marker(point, {
                icon: L.icon({
                    iconUrl: getDriverIcon(driver.metadata.status),
                    iconSize: [30, 30],
                    iconAnchor: [15, 15],
                }),
            });

            marker.bindPopup(popup).addTo(getDriverLayer(driver.metadata.status));

            drivers.push({
                id: driver.id,
                status: driver.metadata.status,
                marker,
                popup,
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
                });

               if(props.bounds.length == 1) {
                    map.setView(props.bounds[0], 14);
                } else {
                    map.fitBounds(props.bounds, {
                        padding: [10, 10],
                    });
                }

                L.gridLayer
                    .googleMutant({
                        type: "roadmap",
                        styles:mapStyle,
                    })
                    .addTo(map);

                addZoomControl();

                map.addControl(new L.Control.controls());

                map.addLayer(busyLayer);
                map.addLayer(idleLayer);
                map.addLayer(offlineLayer);

                props.drivers.forEach((driver) => {
                    addDriverToMap(driver);
                });

                update();
            });
        };

        const getDriverIcon = (status) => {
            if (status == "ONLINE") return driverIdleIcon;
            if (status == "OFFLINE") return driverOfflineIcon;
            if (status == "BUSY") return driverIcon;

            return null;
        };

        const getDriverLayer = (status) => {
            if (status == "ONLINE") return idleLayer;
            if (status == "OFFLINE") return offlineLayer;
            if (status == "BUSY") return busyLayer;

            return null;
        };

        const getDriverPopupContent = (driver) => {
            return `
                <div>
                    <span>Nome: ${driver.full_name}</span>
                    <br>
                    <span>Status: ${driver.metadata.formatted_status}</span>
                    <br>
                    <span>Bateria: ${driver.metadata.battery_level ?? '-'}</span>
                    <br>
                    <span>Atualizado às: ${driver.formatted_updated_at}</span>
                    <br>
                    <button class='mt-2 inline-flex justify-center py-1 px-2 border border-transparent text-xs font-medium rounded-md text-white bg-red-600' onclick='window.emitter.emit("updateDriverStatus", [${driver.id}, 0])'>Offline</button>
                    <button class='ml-1 inline-flex justify-center py-1 px-2 border border-transparent text-xs font-medium rounded-md text-white bg-green-600' onclick='window.emitter.emit("updateDriverStatus", [${driver.id}, 1])'>Online</button>
                </div>
            `;
        };

        const update = () => {
            if (timer) clearTimeout(timer);

            Inertia.reload({
                onFinish: () => {
                    props.drivers.forEach((driver) => {
                        let item = drivers.find((d) => d.id == driver.id);

                        if (item) {
                            if(item.status !== driver.metadata.status) {
                              
                              getDriverLayer(item.status).removeLayer(item.marker)

                              drivers.splice(drivers.indexOf(item), 1);

                              addDriverToMap(driver);

                              return;
                            }

                            if (driver.metadata.location == null) {

                              map.removeLayer(item.marker);

                              drivers.splice(drivers.indexOf(item), 1);

                              return;
                            }

                            const point = [
                                driver.metadata.location.coordinates[1],
                                driver.metadata.location.coordinates[0],
                            ];

                            item.marker.setIcon(
                                L.icon({
                                    iconUrl: getDriverIcon(driver.metadata.status),
                                    iconSize: [30, 30],
                                    iconAnchor: [15, 15],
                                })
                            );

                            item.marker.slideTo(point, {
                                duration: 200,
                                keepAtCenter: false,
                            });

                            item.popup
                                .setContent(getDriverPopupContent(driver))
                                .update();
                        } else {
                            addDriverToMap(driver);
                        }
                    });

                    stats.value[0].stat = busyDriversCount.value;
                    stats.value[1].stat = idleDriversCount.value;
                    stats.value[2].stat = offlineDriversCount.value;

                    timer = setTimeout(() => {
                        update();
                    }, timeout);
                },
            });
        };

        onMounted(() => {
            initMap();

            window.emitter.on("updateDriverStatus", updateDriverStatus);
        });

        onBeforeUnmount(() => {
          window.emitter.off("updateDriverStatus", updateDriverStatus);
        })

        return {
            idleDriversCount,
            busyDriversCount,
            offlineDriversCount,
            stats,
            toggleLayer,
            status,
            search,
            driversByName,
            tabIndex,
            selectDriver,
        };
    },
});
</script>
