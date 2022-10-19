<template>
  <developer-layout title="Entregadores">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Entregadores
        </h2>

        <div>
          <Link
            v-if="!driver.banned"
            as="button"
            class="inline-flex items-center px-4 py-2 bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-300 active:bg-red-900 focus:outline-none transition ease-in-out duration-150"
            @click="ban"
          >
            Banir
          </Link>

          <Link
            :href="route('developer.drivers.index')"
            class="ml-2 inline-flex items-center px-4 py-2 bg-speedapp-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-speedapp-gray-300 active:bg-speedapp-gray-900 focus:outline-none transition ease-in-out duration-150"
          >
            Voltar
          </Link>
        </div>
      </div>
    </template>

    <div class="py-12">
      <nav
        class="flex flex-col md:flex-row relative z-0 rounded-lg shadow divide-x divide-gray-200"
      >
        <template
          v-for="(tab, i) in tabs"
          :key="tab.name"
        >
          <template v-if="tab.enabled">
            <button
              :class="[
                i === tabIndex
                  ? 'text-gray-900'
                  : 'text-gray-500 hover:text-gray-700',
                i === 0 ? 'md:rounded-l-lg' : '',
                i === tabs.length - 1 ? 'md:rounded-r-lg' : '',
                'cursor-pointer group relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4 text-sm font-medium text-center hover:bg-gray-50 focus:z-10',
              ]"
              @click="tabIndex = i"
            >
              <span>{{ tab.name }}</span>
              <span
                :class="[
                  i === tabIndex
                    ? 'bg-speedapp-orange-500'
                    : 'bg-transparent',
                  'absolute inset-x-0 bottom-0 h-0.5',
                ]"
              />
            </button>
          </template>

          <template v-else>
            <button
              disabled
              :class="[
                'opacity-50 text-gray-500 hover:text-gray-700',
                i === 0 ? 'md:rounded-l-lg' : '',
                i === tabs.length - 1 ? 'md:rounded-r-lg' : '',
                'cursor-pointer group relative min-w-0 flex-1 overflow-hidden bg-white py-4 px-4 text-sm font-medium text-center focus:z-10',
              ]"
            >
              <span>{{ tab.name }}</span>
              <span
                class="bg-transparent absolute inset-x-0 bottom-0 h-0.5"
              />
            </button>
          </template>
        </template>
      </nav>

      <div class="mt-6 flex flex-col">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div
            class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8"
          >
            <form
              v-if="tabIndex === 0"
              @submit.prevent="submit"
            >
              <div
                class="border border-gray-300 rounded-md overflow-hidden"
              >
                <div class="px-4 py-5 bg-white sm:p-6">
                  <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6">
                      <jet-label
                        for="state"
                        value="Cidade"
                      />
                      <select
                        id="state"
                        v-model="form.city_id"
                        name="state"
                        class="mt-1 block w-full pl-3 pr-10 py-2 border-gray-300 focus:outline-none focus:ring-speedapp-orange-500 focus:border-speedapp-orange-500 rounded-md shadow-sm"
                      >
                        <option
                          v-for="(
                            city, index
                          ) in cities"
                          :key="index"
                          :value="city.id"
                        >
                          {{ city.name }}
                        </option>
                      </select>
                      <jet-input-error
                        :message="form.errors.city_id"
                        class="mt-2"
                      />
                    </div>

                    <div class="col-span-6 md:col-span-3">
                      <jet-label
                        for="first_name"
                        value="Primeiro nome"
                      />
                      <jet-input
                        id="first_name"
                        v-model="form.first_name"
                        type="text"
                        class="mt-1 block w-full"
                        autocomplete="first_name"
                      />
                      <jet-input-error
                        :message="
                          form.errors.first_name
                        "
                        class="mt-2"
                      />
                    </div>

                    <div class="col-span-6 md:col-span-3">
                      <jet-label
                        for="last_name"
                        value="Último nome"
                      />
                      <jet-input
                        id="last_name"
                        v-model="form.last_name"
                        type="text"
                        class="mt-1 block w-full"
                        autocomplete="last_name"
                      />
                      <jet-input-error
                        :message="form.errors.last_name"
                        class="mt-2"
                      />
                    </div>

                    <div class="col-span-6">
                      <jet-label
                        for="birthday"
                        value="Data de nascimento"
                      />
                      <jet-input
                        id="birthday"
                        v-model="form.birthday"
                        v-maska="'##/##/####'"
                        type="text"
                        class="mt-1 block w-full"
                        autocomplete="birthday"
                      />
                      <jet-input-error
                        :message="form.errors.birthday"
                        class="mt-2"
                      />
                    </div>

                    <div class="col-span-6">
                      <jet-label
                        for="cpf"
                        value="CPF"
                      />
                      <jet-input
                        id="cpf"
                        v-model="form.cpf"
                        v-maska="'###.###.###-##'"
                        type="text"
                        class="mt-1 block w-full"
                        autocomplete="cpf"
                      />
                      <jet-input-error
                        :message="form.errors.cpf"
                        class="mt-2"
                      />
                    </div>

                    <div class="col-span-6">
                      <jet-label
                        for="email"
                        value="E-mail"
                      />
                      <jet-input
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="mt-1 block w-full"
                        autocomplete="email"
                      />
                      <jet-input-error
                        :message="form.errors.email"
                        class="mt-2"
                      />
                    </div>

                    <div class="col-span-6">
                      <jet-label
                        for="phone"
                        value="Telefone"
                      />
                      <jet-input
                        id="phone"
                        v-model="form.phone"
                        v-maska="'(##) #####-####'"
                        type="text"
                        class="mt-1 block w-full"
                        autocomplete="phone"
                        placeholder="Exemplo: (99) 99999-9999"
                      />
                      <jet-input-error
                        :message="form.errors.phone"
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
                    Salvar
                  </button>
                </div>
              </div>
            </form>

            <form
              v-if="tabIndex === 1"
              @submit.prevent="submitTransport"
            >
              <div
                class="border border-gray-300 rounded-md overflow-hidden"
              >
                <div class="px-4 py-5 bg-white sm:p-6">
                  <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6">
                      <jet-label
                        for="cnh"
                        value="CNH"
                      />
                      <jet-input
                        id="cnh"
                        v-model="transportForm.cnh"
                        type="text"
                        class="mt-1 block w-full"
                        autocomplete="cnh"
                      />
                      <jet-input-error
                        :message="
                          transportForm.errors.cnh
                        "
                        class="mt-2"
                      />
                    </div>

                    <div class="col-span-6 md:col-span-3">
                      <jet-label
                        for="vehicle_name"
                        value="Modelo do veículo"
                      />
                      <jet-input
                        id="vehicle_name"
                        v-model="
                          transportForm.vehicle_name
                        "
                        type="text"
                        class="mt-1 block w-full"
                        autocomplete="vehicle_name"
                      />
                      <jet-input-error
                        :message="
                          transportForm.errors
                            .vehicle_name
                        "
                        class="mt-2"
                      />
                    </div>

                    <div class="col-span-6 md:col-span-3">
                      <jet-label
                        for="vehicle_plate"
                        value="Placa do veículo"
                      />
                      <jet-input
                        id="vehicle_plate"
                        v-model="
                          transportForm.vehicle_plate
                        "
                        type="text"
                        class="mt-1 block w-full"
                        autocomplete="vehicle_plate"
                      />
                      <jet-input-error
                        :message="
                          transportForm.errors
                            .vehicle_plate
                        "
                        class="mt-2"
                      />
                    </div>

                    <div class="col-span-6 md:col-span-3">
                      <jet-label
                        for="mode"
                        value="Veículo"
                      />
                      <select
                        id="mode"
                        v-model="
                          transportForm.metadata.mode
                        "
                        name="mode"
                        class="mt-1 block w-full pl-3 pr-10 py-2 border-gray-300 focus:outline-none focus:ring-speedapp-orange-500 focus:border-speedapp-orange-500 rounded-md shadow-sm"
                      >
                        <option
                          v-for="(mode, i) in modes"
                          :key="i"
                          :value="i"
                        >
                          {{ mode }}
                        </option>
                      </select>
                    </div>

                    <div class="col-span-6 md:col-span-3">
                      <jet-label
                        for="bag"
                        value="Bag"
                      />
                      <select
                        id="bag"
                        v-model="
                          transportForm.metadata.bag
                        "
                        name="bag"
                        class="mt-1 block w-full pl-3 pr-10 py-2 border-gray-300 focus:outline-none focus:ring-speedapp-orange-500 focus:border-speedapp-orange-500 rounded-md shadow-sm"
                      >
                        <option
                          v-for="(bag, i) in bags"
                          :key="i"
                          :value="i"
                        >
                          {{ bag }}
                        </option>
                      </select>
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
                      'opacity-25':
                        transportForm.processing,
                    }"
                    :disabled="transportForm.processing"
                  >
                    Salvar
                  </button>
                </div>
              </div>
            </form>

            <form
              v-if="tabIndex === 2"
              @submit.prevent="submitCloud"
            >
              <div
                class="border border-gray-300 rounded-md overflow-hidden"
              >
                <div class="px-4 py-5 bg-white sm:p-6">
                  <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6">
                      <SwitchGroup
                        as="div"
                        class="flex items-center"
                      >
                        <Switch
                          v-model="cloudForm.cloud"
                          :class="[
                            !cloudForm.cloud
                              ? 'bg-speedapp-orange-600'
                              : 'bg-gray-200',
                            'relative inline-flex shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-speedapp-orange-500',
                          ]"
                        >
                          <span
                            aria-hidden="true"
                            :class="[
                              !cloudForm.cloud
                                ? 'translate-x-5'
                                : 'translate-x-0',
                              'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200',
                            ]"
                          />
                        </Switch>
                        <SwitchLabel
                          as="span"
                          class="ml-3"
                        >
                          <span
                            class="text-sm font-medium text-gray-900"
                          >Habilitar fixo</span>
                        </SwitchLabel>
                      </SwitchGroup>
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
                      'opacity-25':
                        transportForm.processing,
                    }"
                    :disabled="transportForm.processing"
                  >
                    Salvar
                  </button>
                </div>
              </div>
            </form>

            <form
              v-if="tabIndex === 3"
              @submit.prevent="submitBank"
            >
              <div
                class="border border-gray-300 rounded-md overflow-hidden"
              >
                <div class="px-4 py-5 bg-white sm:p-6">
                  <div class="grid grid-cols-6 gap-6">
                    <div class="col-span-6">
                      <jet-label
                        for="pix"
                        value="Chave PIX"
                      />
                      <jet-input
                        id="pix"
                        v-model="bankForm.pix"
                        type="text"
                        class="mt-1 block w-full"
                        autocomplete="pix"
                      />
                      <jet-input-error
                        :message="bankForm.errors.email"
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
                      'opacity-25': bankForm.processing,
                    }"
                    :disabled="bankForm.processing"
                  >
                    Salvar
                  </button>
                </div>
              </div>
            </form>

            <form
              v-show="tabIndex === 4"
              @submit.prevent="storeDocuments"
            >
              <div
                class="overflow-hidden border border-gray-300 rounded-md px-4 py-5 sm:p-6 bg-white"
              >
                <div
                  class="grid gap-6 grid-cols-1 sm:grid-cols-2 md:grid-cols-3"
                >
                  <Upload
                    key="dropbox-profile-picture"
                    name="dropbox-profile-picture"
                    label="Foto de perfil"
                    :doc="
                      getDocumentByFolder(
                        'profile-picture'
                      )
                    "
                    @changed="
                      changed($event, 'profile-picture')
                    "
                    @deleted="deleteDocument($event)"
                  />

                  <Upload
                    key="dropbox-rg-front"
                    name="dropbox-rg-front"
                    label="RG - Frente"
                    :doc="getDocumentByFolder('rg-front')"
                    @changed="changed($event, 'rg-front')"
                    @deleted="deleteDocument($event)"
                  />

                  <Upload
                    key="dropbox-rg-back"
                    name="dropbox-rg-back"
                    label="RG - Verso"
                    :doc="getDocumentByFolder('rg-back')"
                    @changed="changed($event, 'rg-back')"
                    @deleted="deleteDocument($event)"
                  />

                  <Upload
                    key="dropbox-cnh-front"
                    name="dropbox-cnh-front"
                    label="CNH - Frente"
                    :doc="getDocumentByFolder('cnh-front')"
                    @changed="changed($event, 'cnh-front')"
                    @deleted="deleteDocument($event)"
                  />

                  <Upload
                    key="dropbox-cnh-back"
                    name="dropbox-cnh-back"
                    label="CNH - Verso"
                    :doc="getDocumentByFolder('cnh-back')"
                    @changed="changed($event, 'cnh-back')"
                    @deleted="deleteDocument($event)"
                  />

                  <Upload
                    key="dropbox-vehicle-doc"
                    name="dropbox-vehicle-doc"
                    label="Documento veículo"
                    :doc="
                      getDocumentByFolder('vehicle-doc')
                    "
                    @changed="
                      changed($event, 'vehicle-doc')
                    "
                    @deleted="deleteDocument($event)"
                  />
                </div>

                <div class="mt-5 text-right">
                  <button
                    type="submit"
                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-speedapp-orange-600 hover:bg-speedapp-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-speedapp-orange-500"
                    :class="{
                      'opacity-25':
                        documentsForm.processing ||
                        documentsForm.documents
                          .length == 0,
                    }"
                    :disabled="
                      documentsForm.processing ||
                        documentsForm.documents.length == 0
                    "
                  >
                    Enviar
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
import { defineComponent } from "vue";
import DeveloperLayout from "@/Layouts/DeveloperLayout.vue";
import Upload from "@/Components/Upload.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetFormSection from "@/Jetstream/FormSection.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import { Link } from "@inertiajs/inertia-vue3";
import { Switch, SwitchGroup, SwitchLabel } from "@headlessui/vue";

import { CheckCircleIcon } from "@heroicons/vue/solid";

import { ExternalLinkIcon } from "@heroicons/vue/outline";

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
        CheckCircleIcon,
        ExternalLinkIcon,
        Upload,
    },

    props: {
        cities: {
          type: Array,
          default: () => [],
        },

        driver: {
          type: Object,
          required: true,
        },

        modes: {
          type: Object,
          default: () => {},
        },
        bags: {
          type: Object,
          default: () => {},
        },
        driverStatuses: {
          type: Object,
          default: () => {},
        },
    },

    data() {
        return {
            status: null,

            tabIndex: 0,
            tabs: [
                {
                    name: "Informações pessoais",
                    enabled: true,
                },
                {
                    name: "Transporte",
                    enabled: true,
                },
                {
                    name: "Turnos",
                    enabled: true,
                },
                {
                    name: "Informações bancárias",
                    enabled: true,
                },
                {
                    name: "Documentos",
                    enabled: true,
                },
            ],

            form: this.$inertia.form({
                _method: "PUT",

                city_id: this.driver.city_id,

                first_name: this.driver.first_name,
                last_name: this.driver.last_name,

                birthday: this.driver.birthday,
                cpf: this.driver.cpf,

                email: this.driver.email,
                phone: this.driver.phone,
            }),

            transportForm: this.$inertia.form({
                _method: "PUT",

                cnh: this.driver.cnh,
                vehicle_name: this.driver.vehicle_name,
                vehicle_plate: this.driver.vehicle_plate,

                metadata: {
                    mode: this.driver.metadata.mode,
                    bag: this.driver.metadata.bag,
                },
            }),

            cloudForm: this.$inertia.form({
                _method: "PUT",

                cloud: this.driver.metadata.cloud,
            }),

            bankForm: this.$inertia.form({
                _method: "PUT",
                pix: this.driver.pix,
            }),

            documentsForm: this.$inertia.form({
                documents: [],
            }),
        };
    },

    methods: {
        setStatus(status) {
            this.status = status;

            setTimeout(() => {
                this.status = null;
            }, 2000);
        },

        submit() {
            this.form.post(route("developer.drivers.update", this.driver.id), {
                preserveScroll: true,
                onSuccess: (res) => {
                    this.setStatus(res.props.status);
                },
            });
        },

        submitBank() {
            this.bankForm.post(
                route("developer.drivers.updateBank", this.driver.id),
                {
                    preserveScroll: true,
                    onSuccess: (res) => {
                        this.setStatus(res.props.status);
                    },
                }
            );
        },

        submitTransport() {
            this.transportForm.post(
                route("developer.drivers.updateTransport", this.driver.id),
                {
                    preserveScroll: true,
                    onSuccess: (res) => {
                        this.setStatus(res.props.status);
                    },
                }
            );
        },

        submitCloud() {
            this.cloudForm.post(
                route("developer.drivers.updateCloud", this.driver.id),
                {
                    preserveScroll: true,
                    onSuccess: (res) => {
                        this.setStatus(res.props.status);
                    },
                }
            );
        },

        ban() {
            this.$inertia.post(route("developer.drivers.ban", this.driver.id));
        },

        // documents
        changed(file, folder) {
            console.log("changed", [file, folder]);

            const document = this.documentsForm.documents.find(
                (f) => f.folder === folder
            );

            if (!document && file) {
                this.documentsForm.documents.push({
                    folder,
                    file,
                });
            }

            if (document && !file) {
                const documents = this.documentsForm.documents;

                const index = this.documentsForm.documents.indexOf(document);

                if (index === -1) return;

                documents.splice(index, 1);

                this.documentsForm.documents = documents;
            }
        },

        storeDocuments() {
            this.documentsForm.post(
                route("developer.drivers.storeDocuments", this.driver.id),
                {
                    forceFormData: true,
                    preserveScroll: true,
                    onSuccess: (res) => {
                        console.log("onSuccess", res);
                        this.setStatus(res.props.status);

                        this.documentsForm.files = [];
                    },
                    onError: (error) => {
                        console.log("error", error);
                    },
                }
            );
        },

        deleteDocument(document) {
            this.$inertia.delete(
                this.route("developer.drivers.deleteDocument", [
                    this.driver.id,
                    document.id,
                ]),
                {
                    preserveScroll: true,
                    onSuccess: (res) => {
                        console.log("onSuccess", res);
                        this.setStatus(res.props.status);
                    },
                    onError: (error) => {
                        console.log("onError", error);
                        this.errors = error;
                    },
                }
            );
        },

        getDocumentByFolder(folder) {
            return this.driver.documents.find((d) => d.folder === folder);
        },
    },
});
</script>
