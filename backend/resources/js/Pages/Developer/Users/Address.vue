<template>
  <form @submit.prevent="submitAddress">
    <div class="border border-gray-300 rounded-md overflow-hidden">
      <div class="px-4 py-5 bg-white sm:p-6">
        <div class="grid grid-cols-6 gap-6">
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
              v-model="addressForm.street_number"
              type="text"
              class="mt-1 block w-full"
              @change="updateMap"
              @keydown.enter.prevent
            />
            <jet-input-error
              :message="addressForm.errors.street_number"
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
              v-model="addressForm.street_name"
              type="text"
              class="mt-1 block w-full"
            />
            <jet-input-error
              :message="addressForm.errors.street_name"
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
              v-model="addressForm.district"
              type="text"
              class="mt-1 block w-full"
            />
            <jet-input-error
              :message="addressForm.errors.district"
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
              v-model="addressForm.city"
              type="text"
              class="mt-1 block w-full"
            />
            <jet-input-error
              :message="addressForm.errors.city"
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
              v-model="addressForm.state"
              type="text"
              class="mt-1 block w-full"
            />
            <jet-input-error
              :message="addressForm.errors.state"
              class="mt-2"
            />
          </div>

          <div class="col-span-6 overflow-hidden rounded-lg">
            <div
              id="map"
              style="height: 30vh"
            />
          </div>
        </div>
      </div>
      <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
        <button
          type="submit"
          class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-speedapp-orange-600 hover:bg-speedapp-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-speedapp-orange-500"
        >
          Salvar
        </button>
      </div>
    </div>
  </form>
</template>

<script>
import { defineComponent, onMounted, ref } from "vue";
import DeveloperLayout from "@/Layouts/DeveloperLayout.vue";
import UserArea from "@/Pages/Developer/Users/Area.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetFormSection from "@/Jetstream/FormSection.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import { Link, useForm } from "@inertiajs/inertia-vue3";
import { Switch, SwitchGroup, SwitchLabel } from "@headlessui/vue";

import L from "leaflet";
import "leaflet/dist/leaflet.css";
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
        Switch,
        SwitchGroup,
        SwitchLabel,
        UserArea,
    },

    props: {
      user: {
          type: Object,
          required: true,
        },
    },
    emits: ["setStatus", 'enableTab'],

    setup(props, { emit }) {
        let map = null;
        let autocomplete = null;
        let geocoder = null;

        let autocompleteInput = ref(null);

        let marker = null;

        const myIcon = L.icon({
            iconUrl: startIcon,
            iconSize: [40, 40],
            iconAnchor: [20, 20],
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

        const resetAddressForm = () => {
            addressForm.position = null;
            addressForm.street_number = "";
            addressForm.street_name = "";
            addressForm.district = "";
            addressForm.city = "";
            addressForm.state = "";
        };

        const submitAddress = () => {
            addressForm.post(
                route("developer.users.updateAddress", props.user.id),
                {
                    preserveScroll: true,
                    onSuccess(res) {
                        emit("setStatus", res.props.status);
                        emit("enableTab", 5);
                    },
                    onError(err) {
                      console.log(err)
                    }
                }
            );
        };

        const initMap = async () => {
            window.emitter.emit("initGoogleMaps", () => {
                map = L.map("map").setView([0, 0], 1);

                L.gridLayer
                    .googleMutant({
                        type: "roadmap",
                        styles:mapStyle,
                    })
                    .addTo(map);

                let center = null;

                if (props.user.address) {
                    center = [
                        props.user.address.position.coordinates[1],
                        props.user.address.position.coordinates[0],
                    ];
                } else {
                    center = [0, 0];
                }

                if (props.user.address) {
                    marker = L.marker(center, {
                        icon: myIcon,
                    }).addTo(map);

                    map.setView(center, 14);
                }

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
                    if (marker !== null) {
                        map.removeLayer(marker);
                        marker = null;
                    }

                    const place = autocomplete.getPlace();

                    setPlace(place);
                });

                geocoder = new window.google.maps.Geocoder();
            });
        };

        const updateMap = async () => {
            if (!addressForm.street_name || !addressForm.street_number) return;

            const { results } = await geocoder.geocode({
                address: `${addressForm.street_name}, ${addressForm.street_number} - ${addressForm.district}, ${addressForm.city} - ${addressForm.state}`,
            });

            if (results.length == 0) return;

            if (marker !== null) {
                map.removeLayer(marker);
                marker = null;
            }

            const place = results[0];

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

            const lat = place.geometry.location.lat();
            const lng = place.geometry.location.lng();

            marker = L.marker([lat, lng], {
                icon: myIcon,
            }).addTo(map);

            map.setView([lat, lng], 14);

            resetAddressForm();

            addressForm.position = [lat, lng];

            for (const component of place.address_components) {
                const type = component.types[0];

                switch (type) {
                    case "street_number":
                        addressForm.street_number = component.long_name;
                        break;
                    case "route":
                        addressForm.street_name = component.long_name;
                        break;
                    case "political":
                    case "sublocality":
                    case "sublocality_level_1":
                        addressForm.district = component.long_name;
                        break;
                    case "administrative_area_level_2":
                        addressForm.city = component.long_name;
                        break;
                    case "administrative_area_level_1":
                        addressForm.state = component.long_name;
                        break;
                    default:
                        break;
                }
            }
        };

        onMounted(initMap);

        return {
            addressForm,
            submitAddress,
            updateMap,
            autocompleteInput,
        };
    },
});
</script>
