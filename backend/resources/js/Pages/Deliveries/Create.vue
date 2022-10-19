<template>
  <user-layout title="Solicitar entrega">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Solicitar entrega
        </h2>
      </div>
    </template>

    <div class="py-12">
      <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div
          class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8"
        >
          <form @submit.prevent="submit">
            <div
              class="border border-gray-300 rounded-md overflow-hidden"
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
                      class="mt-1 block w-full border-gray-300 focus:border-speedapp-orange-300 focus:ring focus:ring-speedapp-orange-200 focus:ring-opacity-50 rounded-md shadow-sm"
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
                      class="flex justify-center items-center absolute inset-0 bg-gray-50 opacity-50 z-[10000]"
                    >
                      <Spinner />
                    </div>
                  </div>

                  <div
                    v-if="formattedDistance || formattedDuration"
                    class="col-span-6 -mt-2"
                  >
                    <span
                      class="
                                                                        font-medium
                                                                        text-blue-600
                                                                    "
                    >{{ formattedDistance ? `${formattedDistance}.`: '' }}{{ formattedDuration ? (formattedDistance ? ` Após a coleta estimado mais ${formattedDuration}` : formattedDuration) : '' }}</span>
                  </div>

                  <div class="col-span-6 -mt-2">
                    <span
                      v-if="error"
                      class="font-medium text-red-600"
                    >{{ error }}</span>
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
                          name="return"
                          type="checkbox"
                          class="focus:ring-speedapp-orange-500 h-4 w-4 text-speedapp-orange-600 border-gray-300 rounded"
                        >
                      </div>
                      <div class="ml-3 text-sm">
                        <label
                          for="return"
                          class="font-medium text-gray-700"
                        >Retorno</label>
                        <p class="text-gray-500">
                          Marque caso seja necessário
                          ao entregador retornar ao
                          local de coleta.
                          <br>
                          Será cobrado um valor
                          adicional de
                          <b>{{
                            user.formatted_return_fee_charged
                          }}</b>.
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
                      :message="
                        form.errors.customer_phone
                      "
                      class="mt-2"
                    />
                  </div>

                  <div class="col-span-6 mt-5">
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
                      class="block w-full border-gray-300 focus:border-speedapp-orange-300 focus:ring focus:ring-speedapp-orange-200 focus:ring-opacity-50 rounded-md shadow-sm"
                    />

                    <jet-input-error
                      :message="form.errors.public_text"
                      class="mt-2"
                    />
                  </div>
                </div>
              </div>
              <div
                class="px-4 py-3 bg-gray-50 text-right sm:px-6"
              >
                <button
                  type="submit"
                  class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-speedapp-orange-600 hover:bg-speedapp-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-speedapp-orange-500"
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
        </div>
      </div>
    </div>
  </user-layout>
</template>

<script>
import { defineComponent, onMounted, ref, computed } from "vue";
import UserLayout from "@/Layouts/UserLayout.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetFormSection from "@/Jetstream/FormSection.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import { Link, useForm } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";

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


export default defineComponent({
    components: {
        UserLayout,
        JetActionMessage,
        JetButton,
        JetFormSection,
        JetInput,
        JetInputError,
        JetLabel,
        JetSecondaryButton,
        Link,
    },

    props: {
        user: {
            type: Object,
            required: true,
        },
        result: {
            type: Object,
            required: false,
            default: () => null,
        },
    },

    setup(props) {
        let map = null;
        let autocomplete = null;
        let geocoder = null;
        let polyline = null;

        let autocompleteInput = ref(null);

        let userMarker = null;
        let targetMarker = null;

        let center = [
            props.user.address.position.coordinates[1],
            props.user.address.position.coordinates[0],
        ];

        const formattedDistance = ref(null);
        const formattedDuration = ref(null);

        const loading = ref(false);
        const error = ref(null);

        const form = useForm({
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
            if (!form.return || !props.user.return_fee_charged) {
                return form.formatted_charged;
            }

            return format(
                (form.charged + props.user.return_fee_charged) / 100,
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
            form.post(route("user.request-delivery.store"), {
                preserveScroll: true,
            });
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

                userMarker = L.marker(center, {
                    icon: L.icon({
                        iconUrl: startIcon,
                        iconSize: [40, 40],
                        iconAnchor: [20, 20],
                    }),
                }).addTo(map);

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

            let group = new L.featureGroup([userMarker, targetMarker]);

            map.fitBounds(group.getBounds(), {
                padding: [10, 10],
            });

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
            if (!form.position) return;

            loading.value = true;

            if(polyline) {
                map.removeLayer(polyline);
            }

            Inertia.post(
                route("user.request-delivery.check"),
                {
                    position: form.position,
                },
                {
                    preserveScroll: true,
                    onSuccess: (res) => {

                        if (res.props.result) {
                            form.rad = res.props.result.rad;
                            form.time = res.props.result.time;
                            form.charged = res.props.result.charged;
                            form.formatted_charged = res.props.result.formatted_charged;

                            if(res.props.result.line) {

                                form.line = res.props.result.line;

                                form.distance = res.props.result.distance;
                                form.duration = res.props.result.duration;

                                polyline = L.polyline(L.PolylineUtil.decode(res.props.result.line)).addTo(map);

                                let group = new L.featureGroup([userMarker, targetMarker, polyline]);

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
                    }
                }
            );
        };

        onMounted(initMap);

        return {
            error,
            form,
            submit,
            updateMap,
            total,
            autocompleteInput,
            formattedDistance,
            formattedDuration,
        };
    },
});
</script>
