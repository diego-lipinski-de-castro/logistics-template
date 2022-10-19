<template>
  <user-layout title="Entregas hoje">
    <template #header>
      <h2
        class="font-semibold text-xl text-gray-800  leading-tight"
      >
        Entregas hoje
      </h2>
    </template>

    <div class="py-12">
      <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div
          class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8"
        >
          <div
            class="border border-gray-300 rounded-md overflow-hidden"
          >
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th
                    scope="col"
                    class="px-2 py-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-l "
                  >
                    Código
                  </th>

                  <th
                    scope="col"
                    class="px-2 py-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-l "
                  >
                    Status
                  </th>

                  <th
                    scope="col"
                    class="px-2 py-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-l "
                  >
                    Entregador
                  </th>

                  <th
                    scope="col"
                    class="px-2 py-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-l "
                  >
                    Cliente
                  </th>

                  <th
                    scope="col"
                    class="px-2 py-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-l "
                  >
                    Endereço de entrega
                  </th>

                  <th
                    scope="col"
                    class="px-2 py-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-l "
                  >
                    Valor
                  </th>

                  <th
                    scope="col"
                    class="px-2 py-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer border-l "
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
                      <span class="ml-3">Solicitado às</span>
                    </div>
                  </th>

                  <th
                    scope="col"
                    class="border-l "
                  >
                    <span class="sr-only">Ações</span>
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr>
                  <td
                    class="px-2 py-1.5 whitespace-nowrap text-sm font-medium text-gray-900"
                  >
                    <div>
                      <label
                        for="cid"
                        class="sr-only"
                      >Código</label>
                      <input
                        id="cid"
                        v-model="filter[0].value"
                        autocomplete="off"
                        type="text"
                        name="cid"
                        class="block w-full sm:text-sm border-gray-300  border-gray-300  rounded-md placeholder-gray-400  focus:ring focus:ring-speedapp-orange-200  focus:ring-opacity-50"
                      >
                    </div>
                  </td>

                  <td
                    class="px-2 py-1.5 whitespace-nowrap text-sm font-medium text-gray-900 border-l "
                  >
                    <div>
                      <label
                        for="status"
                        class="sr-only"
                      >Status</label>
                      <select
                        id="status"
                        v-model="filter[1].value"
                        name="status"
                        class="mt-1 block w-full pl-3 pr-10 py-2 border-gray-300  border-gray-300  rounded-md placeholder-gray-400  focus:ring focus:ring-speedapp-orange-200  focus:ring-opacity-50"
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
                    class="px-2 py-1.5 whitespace-nowrap text-sm font-medium text-gray-900 border-l "
                  />

                  <td
                    class="px-2 py-1.5 whitespace-nowrap text-sm font-medium text-gray-900 border-l "
                  />
                  <td
                    class="px-2 py-1.5 whitespace-nowrap text-sm font-medium text-gray-900 border-l "
                  />
                  <td
                    class="px-2 py-1.5 whitespace-nowrap text-sm font-medium text-gray-900 border-l "
                  />
                  <td
                    class="px-2 py-1.5 whitespace-nowrap text-sm font-medium text-gray-900 border-l "
                  />

                  <td
                    class="px-2 py-1.5 whitespace-nowrap text-sm font-medium text-gray-900 border-l "
                  >
                    <button
                      type="button"
                      class="inline-flex items-center px-4 py-2.5 border border-gray-300  shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700  bg-white  hover:bg-gray-50  focus:outline-none"
                      @click="clear"
                    >
                      Limpar filtros
                    </button>
                  </td>
                </tr>

                <tr v-if="deliveries.data.length == 0">
                  <td
                    colspan="12"
                    class="p-6"
                  >
                    <div class="flex flex-col items-center">
                      <span
                        class="text-center text-sm font-medium text-gray-900"
                      >Nenhuma entrega hoje</span>

                      <Link
                        :href="
                          route(
                            'user.request-delivery'
                          )
                        "
                        class="mt-3 w-min whitespace-nowrap px-4 py-2 bg-speedapp-orange-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase hover:bg-speedapp-orange-300 active:bg-speedapp-orange-900 focus:outline-none transition ease-in-out duration-150"
                      >
                        Solicitar entrega
                      </Link>
                    </div>
                  </td>
                </tr>

                <tr
                  v-for="(delivery, index) in deliveries.data"
                  :key="index"
                >
                  <td
                    class="px-2 py-1.5 whitespace-nowrap text-sm font-medium text-gray-900 text-center"
                  >
                    <Link :href="route('user.deliveries.show', delivery.id)">
                      #{{ delivery.cid }}
                    </Link>
                  </td>

                  <td
                    class="px-2 py-1.5 whitespace-nowrap text-xs font-medium uppercase text-center border-l"
                  >
                    <span
                      :class="[
                        'px-2.5 py-1 rounded-md',
                        {
                          'bg-blue-500 text-white':
                            delivery.status ===
                            'WAITING',
                          'bg-yellow-300 text-black':
                            delivery.status ===
                            'PENDING',
                          'bg-speedapp-orange-500 text-white':
                            delivery.status ===
                            'ACCEPTED' ||
                            delivery.status ===
                            'COLLECTING' ||
                            delivery.status ===
                            'DELIVERING' ||
                            delivery.status ===
                            'CONFIRMED' ||
                            delivery.status ===
                            'RETURNING',
                          'bg-green-500 text-white':
                            delivery.status ===
                            'COMPLETED',
                          'bg-red-500 text-white':
                            delivery.status ===
                            'CANCELED',
                          'bg-pink-500 text-white':
                            delivery.status ===
                            'NOT_DELIVERED',
                        },
                      ]"
                    >{{
                      delivery.formatted_status
                    }}</span>
                  </td>

                  <td
                    class="px-2 py-1.5 whitespace-nowrap text-sm text-gray-500 border-l"
                  >
                    {{ delivery.driver?.full_name ?? "-" }}
                  </td>

                  <td
                    class="px-2 py-1.5 whitespace-nowrap text-sm text-gray-500 border-l"
                  >
                    <span class="font-medium">
                      {{ delivery.customer_name ?? "-" }}
                    </span>
                                        
                    <br>

                    <span>
                      {{ delivery.customer_phone ?? "-" }}
                    </span>
                  </td>

                  <td class="px-2 py-1.5 text-sm text-gray-500 border-l">
                    {{
                      delivery.steps[1].formatted_address
                    }}
                  </td>

                  <td
                    class="px-2 py-1.5 whitespace-nowrap text-sm text-gray-500 border-l"
                  >
                    {{
                      delivery.formatted_total_charged ??
                        "-"
                    }}
                  </td>

                  <td
                    class="px-2 py-1.5 whitespace-nowrap text-sm text-gray-500 border-l"
                  >
                    {{ delivery.formatted_created_at_time }}
                  </td>

                  <td
                    class="px-2 py-1.5 whitespace-nowrap text-sm text-gray-500 border-l text-center"
                  >
                    <span
                      class="relative z-0 inline-flex shadow-sm"
                    >
                      <Link
                        :href="
                          route(
                            'user.deliveries.show',
                            delivery.id
                          )
                        "
                        class="rounded-md py-1 relative inline-flex items-center px-3 py-2 border border-transparent bg-purple-500 text-sm font-medium text-white hover:bg-purple-700 active:bg-purple-900 focus:z-10 focus:outline-none focus:ring-1 focus:ring-purple-500 focus:border-purple-500"
                      >
                        Acompanhar
                      </Link>
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="mt-4 flex-1 flex justify-between">
            <div class="flex items-center">
              <select
                v-model="perPage"
                name="per_page"
                class="block pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
              >
                <option value="10">
                  10
                </option>
                <option value="15">
                  15
                </option>
                <option value="30">
                  30
                </option>
                <option value="50">
                  50
                </option>
              </select>

              <p
                class="text-sm text-gray-700  ml-3"
              >
                Mostrando
                {{ " " }}
                <span class="font-medium">
                  {{ deliveries.from }}
                </span>
                {{ " " }}
                até
                {{ " " }}
                <span class="font-medium">
                  {{ deliveries.to }}
                </span>
                {{ " " }}
                de
                {{ " " }}
                <span class="font-medium">
                  {{ deliveries.total }}
                </span>
                {{ " " }}
                registros
              </p>
            </div>

            <span class="relative z-0 inline-flex">
              <Link
                :href="deliveries.prev_page_url"
                :class="[
                  {
                    'hover:bg-gray-50 ':
                      deliveries.prev_page_url !== null,
                    'cursor-not-allowed':
                      deliveries.prev_page_url === null,
                  },
                  'relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300  bg-white  text-sm font-medium text-gray-500  focus:z-10 focus:outline-none',
                ]"
              >
                <span class="sr-only">Anterior</span>
                <ChevronLeftIcon
                  class="h-5 w-5"
                  aria-hidden="true"
                />
              </Link>

              <Link
                v-for="(link, index) in links"
                :key="index"
                :href="link.url"
                :class="[
                  {
                    'hover:bg-gray-50 ':
                      link.url !== null,
                    'cursor-not-allowed': link.url === null,
                  },
                  'relative inline-flex justify-center items-center w-10 py-2 border border-gray-300  bg-white  text-sm font-medium text-gray-500  focus:z-10 focus:outline-none',
                ]"
              >
                <span>{{ link.label }}</span>
              </Link>

              <Link
                :href="deliveries.next_page_url"
                :class="[
                  {
                    'hover:bg-gray-50 ':
                      deliveries.next_page_url !== null,
                    'cursor-not-allowed':
                      deliveries.next_page_url === null,
                  },
                  '-ml-px relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300  bg-white  text-sm font-medium text-gray-500  focus:z-10 focus:outline-none',
                ]"
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
  </user-layout>
</template>

<script>
import { computed, defineComponent, ref, watch } from "vue";
import UserLayout from "@/Layouts/UserLayout.vue";
import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/vue/solid";
import { Link } from "@inertiajs/inertia-vue3";
import debounce from "lodash/debounce";

import { Inertia } from "@inertiajs/inertia";

import {
    ArrowSmUpIcon,
    ArrowSmDownIcon,
    SwitchVerticalIcon,
} from "@heroicons/vue/outline";

export default defineComponent({
    components: {
        UserLayout,
        ChevronLeftIcon,
        ChevronRightIcon,
        Link,
        ArrowSmUpIcon,
        ArrowSmDownIcon,
        SwitchVerticalIcon,
    },

    props: {
        deliveries: {
            type: Object,
            required: true,
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
      
        const page = ref(params.page ?? 1);

        const perPage = ref(params.per_page ?? 15);

        const sort = ref(params.sort ?? "-created_at");

        const filter = ref([
            {
                field: "cid",
                value: ref(params["filter[cid]"] ?? ""),
            },
            {
                field: "status",
                value: ref(params["filter[status]"] ?? null),
            },
        ]);

        const links = computed(() => {
            let _links = props.deliveries.links;

            _links.shift();
            _links.pop();

            return _links;
        });

        const clear = () => {
            filter.value[0].value = "";
            filter.value[1].value = null;
        };

        const get = () => {
            Inertia.replace(route("user.deliveries"), {
                data: {
                    page: page.value,
                    per_page: perPage.value,
                    sort: sort.value,

                    [`filter[${filter.value[0].field}]`]: filter.value[0].value
                        .replace("#", "")
                        .trim(),

                    ...(filter.value[1].value !== null && {
                        [`filter[${filter.value[1].field}]`]:
                            filter.value[1].value,
                    }),
                },
            });
        };

        watch(perPage, (n, o) => get());

        watch(sort, (n, o) => get());

        watch(
            filter,
            debounce((n, o) => get(), 400),
            {
                deep: true,
            }
        );

        return {
            perPage,
            sort,
            filter,
            clear,
            links,
        };
    },
});
</script>
