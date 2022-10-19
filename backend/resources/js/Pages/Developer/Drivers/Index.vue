<template>
  <developer-layout title="Entregadores">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Entregadores
        </h2>

        <Link
          :href="route('developer.drivers.create')"
          class="inline-flex items-center px-4 py-2 bg-speedapp-orange-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-speedapp-orange-300 active:bg-speedapp-orange-900 focus:outline-none transition ease-in-out duration-150"
        >
          Adicionar
        </Link>
      </div>
    </template>

    <div class="py-12">
      <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div
          class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8"
        >
          <div
            class="mt-6 border border-gray-300 rounded-md overflow-hidden"
          >
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th
                    scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-l"
                    @click="
                      sort == 'full_name'
                        ? (sort = '-full_name')
                        : (sort = 'full_name')
                    "
                  >
                    <div class="flex flex-row">
                      <div class="h-4 w-4">
                        <ArrowSmUpIcon
                          v-if="sort == 'full_name'"
                        />
                        <ArrowSmDownIcon
                          v-if="sort == '-full_name'"
                        />
                        <SwitchVerticalIcon
                          v-if="
                            sort !== 'full_name' &&
                              sort !== '-full_name'
                          "
                        />
                      </div>
                      <span class="ml-3">Nome</span>
                    </div>
                  </th>

                  <th
                    scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-l"
                  >
                    Telefone
                  </th>

                  <th
                    scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-l"
                  >
                    Cidade
                  </th>

                  <th
                    scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-l"
                  >
                    Status
                  </th>

                  <th
                    scope="col"
                    class="px-4 py-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer border-l"
                    @click="
                      sort == 'registered'
                        ? (sort = '-registered')
                        : (sort = 'registered')
                    "
                  >
                    <div class="flex flex-row">
                      <div class="h-4 w-4">
                        <ArrowSmUpIcon
                          v-if="sort == 'registered'"
                        />
                        <ArrowSmDownIcon
                          v-if="sort == '-registered'"
                        />
                        <SwitchVerticalIcon
                          v-if="
                            sort !== 'registered' &&
                              sort !== '-registered'
                          "
                        />
                      </div>
                      <span class="ml-3">Cadastrado</span>
                    </div>
                  </th>

                  <th
                    scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-l"
                  >
                    Fixo
                  </th>

                  <th
                    scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-l"
                    @click="
                      sort == 'created_at'
                        ? (sort = '-created_at')
                        : (sort = 'created_at')
                    "
                  >
                    <div class="flex flex-row">
                      <div class="h-4 w-4">
                        <ArrowSmUpIcon
                          v-if="sort == 'created_at'"
                        />
                        <ArrowSmDownIcon
                          v-if="sort == '-created_at'"
                        />
                        <SwitchVerticalIcon
                          v-if="
                            sort !== 'created_at' &&
                              sort !== '-created_at'
                          "
                        />
                      </div>
                      <span class="ml-3">Criado às</span>
                    </div>
                  </th>

                  <th
                    scope="col"
                    class="px-4 py-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer border-l"
                    @click="
                      sort == 'updated_at'
                        ? (sort = '-updated_at')
                        : (sort = 'updated_at')
                    "
                  >
                    <div class="flex flex-row">
                      <div class="h-4 w-4">
                        <ArrowSmUpIcon
                          v-if="sort == 'updated_at'"
                        />
                        <ArrowSmDownIcon
                          v-if="sort == '-updated_at'"
                        />
                        <SwitchVerticalIcon
                          v-if="
                            sort !== 'updated_at' &&
                              sort !== '-updated_at'
                          "
                        />
                      </div>
                      <span class="ml-3">Atualizado às</span>
                    </div>
                  </th>

                  <th
                    scope="col"
                    class="border-l"
                  >
                    <span class="sr-only">Ações</span>
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr>
                  <td
                    class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900"
                  >
                    <div>
                      <label
                        for="full_name"
                        class="sr-only"
                      >Nome</label>
                      <input
                        id="full_name"
                        v-model="filter[0].value"
                        autocomplete="off"
                        type="text"
                        name="full_name"
                        class="shadow-sm focus:ring-speedapp-orange-500 focus:border-speedapp-orange-500 block w-full sm:text-sm border-gray-300 rounded-md"
                      >
                    </div>
                  </td>

                  <td
                    class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 border-l"
                  >
                    <div>
                      <label
                        for="phone"
                        class="sr-only"
                      >Telefone</label>
                      <input
                        id="phone"
                        v-model="filter[1].value"
                        autocomplete="off"
                        role="presentation"
                        type="text"
                        name="phone"
                        class="shadow-sm focus:ring-speedapp-orange-500 focus:border-speedapp-orange-500 block w-full sm:text-sm border-gray-300 rounded-md"
                      >
                    </div>
                  </td>

                  <td
                    class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 border-l"
                  >
                    <div>
                      <label
                        for="city"
                        class="sr-only"
                      >Cidade</label>
                      <select
                        id="city"
                        v-model="filter[2].value"
                        name="city"
                        class="mt-1 block w-full pl-3 pr-10 py-2 border-gray-300 focus:outline-none focus:ring-speedapp-orange-500 focus:border-speedapp-orange-500 rounded-md shadow-sm"
                      >
                        <option :value="null">
                          Todos
                        </option>

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
                    </div>
                  </td>

                  <td
                    class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 border-l"
                  >
                    <div>
                      <label
                        for="status"
                        class="sr-only"
                      >Status</label>
                      <select
                        id="status"
                        v-model="filter[3].value"
                        name="status"
                        class="mt-1 block w-full pl-3 pr-10 py-2 border-gray-300 focus:outline-none focus:ring-speedapp-orange-500 focus:border-speedapp-orange-500 rounded-md shadow-sm"
                      >
                        <option :value="null">
                          Todos
                        </option>

                        <option
                          v-for="(
                            label, key
                          ) in statuses"
                          :key="key"
                          :value="key"
                        >
                          {{ label }}
                        </option>
                      </select>
                    </div>
                  </td>

                  <td
                    class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 border-l"
                  >
                    <div>
                      <label
                        for="registered"
                        class="sr-only"
                      >Cadastrado</label>
                      <select
                        id="registered"
                        v-model="filter[4].value"
                        name="registered"
                        class="mt-1 block w-full pl-3 pr-10 py-2 border-gray-300 focus:outline-none focus:ring-speedapp-orange-500 focus:border-speedapp-orange-500 rounded-md shadow-sm"
                      >
                        <option :value="null">
                          Todos
                        </option>
                        <option :value="true">
                          Sim
                        </option>
                        <option :value="false">
                          Não
                        </option>
                      </select>
                    </div>
                  </td>

                  <td
                    class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 border-l"
                  />
                  <td
                    class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 border-l"
                  />
                  <td
                    class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 border-l"
                  />

                  <td
                    class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 border-l"
                  >
                    <button
                      type="button"
                      class="inline-flex items-center px-4 py-2.5 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-speedapp-orange-500"
                      @click="clear"
                    >
                      Limpar filtros
                    </button>
                  </td>
                </tr>

                <tr
                  v-for="(driver, index) in drivers.data"
                  :key="index"
                >
                  <td
                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                  >
                    {{ driver.full_name ?? "-" }}
                  </td>

                  <td
                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-l"
                  >
                    {{ driver.formatted_phone }}
                  </td>

                  <td
                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border-l"
                  >
                    {{ driver.city?.name ?? "-" }}
                  </td>

                  <td
                    :class="[
                      {},
                      'px-6 py-4 whitespace-nowrap text-xs font-medium uppercase text-center border-l',
                    ]"
                  >
                    <span
                      :class="[getStatusColor(driver.status), 'px-2.5 py-1 rounded-md']"
                    >
                      {{ driver.formatted_status ?? "-" }}
                    </span>
                  </td>

                  <td
                    :class="[
                      {},
                      'px-6 py-4 whitespace-nowrap text-xs font-medium uppercase text-center border-l',
                    ]"
                  >
                    <span
                      :class="[driver.registered ? 'bg-green-500' : 'bg-red-500', 'text-white px-2.5 py-1 rounded-md']"
                    >
                      {{ driver.registered ? 'Sim' : 'Não' }}
                    </span>
                  </td>

                  <td
                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border-l"
                  >
                    <SwitchGroup
                      as="div"
                      class="flex items-center"
                    >
                      <Switch
                        v-model="driver.metadata.cloud"
                        @update:modelValue="updateDriverMetadata(driver.id, driver.metadata.cloud)"
                        :class="[
                          !driver.metadata.cloud
                            ? 'bg-speedapp-orange-600'
                            : 'bg-gray-200',
                          'relative inline-flex shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none',
                        ]"
                      >
                        <span
                          aria-hidden="true"
                          :class="[
                            !driver.metadata.cloud
                              ? 'translate-x-5'
                              : 'translate-x-0',
                            'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200',
                          ]"
                        />
                      </Switch>
                    </SwitchGroup>
                  </td>

                  <td
                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border-l"
                  >
                    {{ driver.formatted_created_at }}
                  </td>

                  <td
                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border-l"
                  >
                    {{ driver.formatted_updated_at }}
                  </td>

                  <td
                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-left border-l"
                  >
                    <span
                      class="relative z-0 inline-flex shadow-sm"
                    >
                      <Link
                        :href="
                          route(
                            'developer.drivers.show',
                            driver.id
                          )
                        "
                        class="rounded-md py-1 relative inline-flex items-center px-3 py-2 border border-transparent bg-purple-500 text-sm font-medium text-white hover:bg-purple-700 active:bg-purple-900 focus:z-10 focus:outline-none focus:ring-1 focus:ring-purple-500 focus:border-purple-500"
                      >
                        Visualizar
                      </Link>

                      <Link
                        v-if="
                          !driver.banned &&
                            driver.status === 'APPROVED'
                        "
                        :href="
                          route(
                            'developer.drivers.edit',
                            driver.id
                          )
                        "
                        class="rounded-md ml-2 py-1 relative inline-flex items-center px-3 py-2 border border-transparent bg-blue-500 text-sm font-medium text-white hover:bg-blue-700 active:bg-blue-900 focus:z-10 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                      >
                        Editar
                      </Link>

                      <!-- <button @click='destroy(driver.id)' type="button" class="rounded-md ml-2 relative inline-flex items-center px-3 py-2 border border-transparent bg-red-500 text-sm font-medium text-white hover:bg-red-700 active:bg-red-900 focus:z-10 focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500">
                                                    Apagar
                                                </button> -->
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="mt-4 flex-1 flex justify-between">
            <div>
              <p class="text-sm text-gray-700">
                Mostrando
                {{ " " }}
                <span class="font-medium">
                  {{ drivers.from }}
                </span>
                {{ " " }}
                até
                {{ " " }}
                <span class="font-medium">
                  {{ drivers.to }}
                </span>
                {{ " " }}
                de
                {{ " " }}
                <span class="font-medium">
                  {{ drivers.total }}
                </span>
                {{ " " }}
                registros
              </p>
            </div>

            <span class="relative z-0 inline-flex rounded-md">
              <Link
                :href="drivers.prev_page_url"
                class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-speedapp-orange-500 focus:border-speedapp-orange-500"
              >
                <span class="sr-only">Anterior</span>
                <ChevronLeftIcon
                  class="h-5 w-5"
                  aria-hidden="true"
                />
              </Link>
              <Link
                :href="drivers.next_page_url"
                class="-ml-px relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-speedapp-orange-500 focus:border-speedapp-orange-500"
              >
                <span class="sr-only">Próximo</span>
                <ChevronRightIcon
                  class="h-5 w-5"
                  aria-hidden="true"
                />
              </Link>
            </span>
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
import { defineComponent, onMounted, reactive, ref, watch } from "vue";
import DeveloperLayout from "@/Layouts/DeveloperLayout.vue";
import { ChevronLeftIcon, ChevronRightIcon, CheckCircleIcon } from "@heroicons/vue/solid";
import debounce from "lodash/debounce";
import { Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import { Switch, SwitchGroup } from "@headlessui/vue";

import {
    ArrowSmUpIcon,
    ArrowSmDownIcon,
    SwitchVerticalIcon,
} from "@heroicons/vue/outline";

export default defineComponent({
    components: {
        DeveloperLayout,
        ChevronLeftIcon,
        ChevronRightIcon,
        Link,
        ArrowSmUpIcon,
        ArrowSmDownIcon,
        SwitchVerticalIcon,
        Switch,
        SwitchGroup,
        CheckCircleIcon,
    },

    props: {
        drivers: {
          type: Object,
          required: true,
        },
        cities: {
          type: Array,
          default: () => [],
        },
        statuses: {
          type: Object,
          default: () => {},
        },
    },

    setup(props) {

        const params = new Proxy(new URLSearchParams(window.location.search), {
          get: (searchParams, prop) => searchParams.get(prop),
        });

        const status = ref(null);

        const setStatus = (stts) => {
            status.value = stts;

            setTimeout(() => {
                status.value = null;
            }, 2000);
        }

        const getStatusColor = (status) => {
            if (status === "APPROVED") return "bg-green-500 text-white";
            if (status === "PENDING") return "bg-yellow-300 text-black";
            if (status === "REJECTED") return "bg-red-500 text-white";
        };
        
        const page = ref(params.page ?? 1);

    const perPage = ref(params.per_page ?? 15);

    const sort = ref(params.sort ?? "-created_at");

        const filter = ref([
            {
                field: "full_name",
                value: ref(params['filter[full_name]'] ?? ""),
            },
            {
                field: "phone",
                value: ref(params['filter[phone]'] ?? ""),
            },
            {
                field: "city.id",
                value: ref(params['filter[city.id]'] ?? null),
            },
            {
                field: "status",
                value: ref(params['filter[status]'] ?? null),
            },
            {
                field: "registered",
                value: ref(params['filter[registered]'] ?? null),
            },
        ]);

        const clear = () => {
            filter.value[0].value = "";
            filter.value[1].value = "";
            filter.value[2].value = null;
            filter.value[3].value = null;
            filter.value[4].value = null;
        };

        const get = () => {
            Inertia.replace(route("developer.drivers.index"), {
                data: {
                    page: page.value,
                    per_page: perPage.value,
                    sort: sort.value,

                    [`filter[${filter.value[0].field}]`]: filter.value[0].value,
                    [`filter[${filter.value[1].field}]`]: filter.value[1].value.replace('/\D/g', ''),

                    ...(filter.value[2].value !== null && {
                        [`filter[${filter.value[2].field}]`]:
                            filter.value[2].value,
                    }),

                    ...(filter.value[3].value !== null && {
                        [`filter[${filter.value[3].field}]`]:
                            filter.value[3].value,
                    }),

                    ...(filter.value[4].value !== null && {
                        [`filter[${filter.value[4].field}]`]:
                            filter.value[4].value,
                    }),
                },
            });
        };

        watch(sort, (n, o) => get());

        watch(
            filter,
            debounce((n, o) => get(), 400),
            {
                deep: true,
            }
        );

        const destroy = (id) => {
            Inertia.delete(route("developer.drivers.destroy", id), {
                preserveScroll: true,
            });
        };

        const updateDriverMetadata = (id, cloud) => {
          Inertia.put(route("developer.drivers.updateCloud", id), {
            cloud
          }, {
            preserveScroll: true,
            onSuccess: (res) => {
              console.log('res', res)
              Inertia.reload();

              setStatus(res.props.status);
            },
            onError: (err) => {
              console.log('err', err);
              Inertia.reload();
            }
          })
        }

        return {
            sort,
            filter,
            clear,
            destroy,
            getStatusColor,
            updateDriverMetadata,
            status,
            setStatus,
        };
    },
});
</script>
