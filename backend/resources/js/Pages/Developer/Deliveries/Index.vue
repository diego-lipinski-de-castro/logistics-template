<template>
  <developer-layout title="Entregas hoje">
    <template #header>
      <h2
        class="
          font-semibold
          text-xl text-gray-800
          
          leading-tight
        "
      >
        Entregas hoje
      </h2>
    </template>

    <div class="py-12">
      <div class="overflow-x-auto">
        <div class="py-2 align-middle inline-block min-w-full">
          <div>
            <ul
              role="list"
              class="grid gap-3 grid-cols-1 lg:grid-cols-5"
            >
              <li
                v-for="(item, index) in stats"
                :key="index"
                class="col-span-1 flex rounded-md"
              >
                <div
                  :class="[
                    item.color,
                    'flex-shrink-0 flex items-center justify-center w-16 font-medium rounded-l-md',
                  ]"
                >
                  {{ item.stat }}
                </div>

                <div
                  class="
                    flex-1 flex
                    items-center
                    border-t border-r border-b border-gray-300
                    
                    bg-white
                    
                    rounded-r-md
                    truncate
                  "
                >
                  <div class="flex-1 px-4 py-3 text-sm">
                    <span
                      class="text-gray-900  font-medium"
                    >{{ item.name }}</span>
                  </div>
                </div>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <div class="mt-6 overflow-x-auto">
        <div class="py-2 align-middle inline-block min-w-full">
          <div
            class="
              border border-gray-300
              
              rounded-md
              overflow-hidden
            "
          >
            <table
              class="min-w-full divide-y divide-gray-200"
            >
              <thead class="bg-gray-50 ">
                <tr>
                  <th
                    scope="col"
                    class="
                      px-2
                      py-1.5
                      text-left text-xs
                      font-medium
                      text-gray-500
                      uppercase
                      tracking-wider
                    "
                  >
                    Código
                  </th>

                  <th
                    scope="col"
                    class="
                      px-2
                      py-1.5
                      text-left text-xs
                      font-medium
                      text-gray-500
                      uppercase
                      tracking-wider
                      border-l
                      
                    "
                  >
                    Status
                  </th>

                  <th
                    scope="col"
                    class="
                      px-2
                      py-1.5
                      text-left text-xs
                      font-medium
                      text-gray-500
                      uppercase
                      tracking-wider
                      border-l
                      
                    "
                  >
                    Entregador
                  </th>

                  <th
                    scope="col"
                    class="
                      px-2
                      py-1.5
                      text-left text-xs
                      font-medium
                      text-gray-500
                      uppercase
                      tracking-wider
                      border-l
                      
                    "
                  >
                    Loja
                  </th>

                  <th
                    scope="col"
                    class="
                      px-2
                      py-1.5
                      text-left text-xs
                      font-medium
                      text-gray-500
                      uppercase
                      tracking-wider
                      border-l
                      
                    "
                  >
                    Cidade
                  </th>

                  <th
                    scope="col"
                    class="
                      px-2
                      py-1.5
                      text-left text-xs
                      font-medium
                      text-gray-500
                      uppercase
                      tracking-wider
                      border-l
                      
                    "
                  >
                    Cliente
                  </th>

                  <th
                    scope="col"
                    class="
                      px-2
                      py-1.5
                      text-left text-xs
                      font-medium
                      text-gray-500
                      uppercase
                      tracking-wider
                      border-l
                      
                    "
                  >
                    Endereço de entrega
                  </th>

                  <th
                    scope="col"
                    class="
                      px-2
                      py-1.5
                      text-left text-xs
                      font-medium
                      text-gray-500
                      uppercase
                      tracking-wider
                      border-l
                      
                    "
                  >
                    Pago / <br>
                    Cobrado
                  </th>

                  <th
                    scope="col"
                    class="
                      px-2
                      py-1.5
                      text-left text-xs
                      font-medium
                      text-gray-500
                      uppercase
                      tracking-wider
                      cursor-pointer
                      border-l
                      
                    "
                    @click="
                      sort == 'created_at'
                        ? (sort = '-created_at')
                        : (sort = 'created_at')
                    "
                  >
                    <div class="flex flex-row">
                      <div class="h-4 w-4">
                        <ArrowSmUpIcon v-if="sort == 'created_at'" />
                        <ArrowSmDownIcon v-if="sort == '-created_at'" />
                        <SwitchVerticalIcon
                          v-if="sort !== 'created_at' && sort !== '-created_at'"
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
              <tbody
                class="
                  bg-white
                  divide-y divide-gray-200
                "
              >
                <tr>
                  <td
                    class="
                      px-2
                      py-1.5
                      whitespace-nowrap
                      text-sm
                      font-medium
                      text-gray-900
                    "
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
                        class="
                          block
                          w-full
                          sm:text-sm
                          border-gray-300
                          rounded-md
                          placeholder-gray-400
                          focus:ring focus:ring-speedapp-orange-200
                          focus:ring-opacity-50
                        "
                      >
                    </div>
                  </td>

                  <td
                    class="
                      px-2
                      py-1.5
                      whitespace-nowrap
                      text-sm
                      font-medium
                      text-gray-900
                      border-l
                      
                    "
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
                        class="
                          mt-1
                          block
                          w-full
                          pl-3
                          pr-10
                          py-2
                          border-gray-300
                          
                          border-gray-300
                          
                          rounded-md
                          placeholder-gray-400
                          
                          focus:ring focus:ring-speedapp-orange-200
                          
                          focus:ring-opacity-50
                        "
                      >
                        <option :value="null">
                          Todos
                        </option>

                        <option
                          v-for="(label, key) in statuses"
                          :key="key"
                          :value="key"
                        >
                          {{ label }}
                        </option>
                      </select>
                    </div>
                  </td>

                  <td
                    class="
                      px-2
                      py-1.5
                      whitespace-nowrap
                      text-sm
                      font-medium
                      text-gray-900
                      border-l
                      
                    "
                  >
                    <div>
                      <label
                        for="driver"
                        class="sr-only"
                      >Entregador</label>
                      <select
                        id="driver"
                        v-model="filter[2].value"
                        name="driver"
                        class="
                          mt-1
                          block
                          w-full
                          pl-3
                          pr-10
                          py-2
                          border-gray-300
                          
                          border-gray-300
                          
                          rounded-md
                          placeholder-gray-400
                          
                          focus:ring focus:ring-speedapp-orange-200
                          
                          focus:ring-opacity-50
                        "
                      >
                        <option :value="null">
                          Todos
                        </option>

                        <option
                          v-for="(driver, index) in drivers"
                          :key="index"
                          :value="driver.id"
                        >
                          {{ driver.full_name }}
                        </option>
                      </select>
                    </div>
                  </td>

                  <td
                    class="
                      px-2
                      py-1.5
                      whitespace-nowrap
                      text-sm
                      font-medium
                      text-gray-900
                      border-l
                      
                    "
                  >
                    <div>
                      <label
                        for="user"
                        class="sr-only"
                      >Lojas</label>
                      <select
                        id="user"
                        v-model="filter[3].value"
                        name="user"
                        class="
                          mt-1
                          block
                          w-full
                          pl-3
                          pr-10
                          py-2
                          border-gray-300
                          
                          
                          rounded-md
                          placeholder-gray-400
                          
                          focus:ring focus:ring-speedapp-orange-200
                          
                          focus:ring-opacity-50
                        "
                      >
                        <option :value="null">
                          Todos
                        </option>

                        <option
                          v-for="(user, index) in users"
                          :key="index"
                          :value="user.id"
                        >
                          {{ user.name }}
                        </option>
                      </select>
                    </div>
                  </td>

                  <td
                    class="
                      px-2
                      py-1.5
                      whitespace-nowrap
                      text-sm
                      font-medium
                      text-gray-900
                      border-l
                      
                    "
                  >
                    <div>
                      <label
                        for="user"
                        class="sr-only"
                      >Cidade</label>
                      <select
                        id="user"
                        v-model="filter[4].value"
                        name="user"
                        class="
                          mt-1
                          block
                          w-full
                          pl-3
                          pr-10
                          py-2
                          border-gray-300
                          
                          border-gray-300
                          
                          rounded-md
                          placeholder-gray-400
                          
                          focus:ring focus:ring-speedapp-orange-200
                          
                          focus:ring-opacity-50
                        "
                      >
                        <option :value="null">
                          Todas
                        </option>

                        <option
                          v-for="(city, index) in cities"
                          :key="index"
                          :value="city.id"
                        >
                          {{ city.name }}
                        </option>
                      </select>
                    </div>
                  </td>

                  <td
                    class="
                      px-2
                      py-1.5
                      whitespace-nowrap
                      text-sm
                      font-medium
                      text-gray-900
                      border-l
                      
                    "
                  />
                  <td
                    class="
                      px-2
                      py-1.5
                      whitespace-nowrap
                      text-sm
                      font-medium
                      text-gray-900
                      border-l
                      
                    "
                  />
                  <td
                    class="
                      px-2
                      py-1.5
                      whitespace-nowrap
                      text-sm
                      font-medium
                      text-gray-900
                      border-l
                      
                    "
                  />
                  <td
                    class="
                      px-2
                      py-1.5
                      whitespace-nowrap
                      text-sm
                      font-medium
                      text-gray-900
                      border-l
                      
                    "
                  />

                  <td
                    class="
                      px-2
                      py-1.5
                      whitespace-nowrap
                      text-sm
                      font-medium
                      text-gray-900
                      border-l
                      
                    "
                  >
                    <button
                      type="button"
                      class="
                        inline-flex
                        items-center
                        px-4
                        py-2.5
                        border border-gray-300
                        
                        shadow-sm
                        text-sm
                        leading-4
                        font-medium
                        rounded-md
                        text-gray-700
                        
                        bg-white
                        
                        hover:bg-gray-50
                        
                        focus:outline-none
                      "
                      @click="clear"
                    >
                      Limpar filtros
                    </button>
                  </td>
                </tr>

                <tr v-if="deliveries.data.length == 0">
                  <td
                    colspan="13"
                    class="p-6"
                  >
                    <div class="flex flex-col items-center">
                      <span
                        class="
                          text-center text-sm
                          font-medium
                          text-gray-900
                          
                        "
                      >Nenhuma entrega hoje</span>

                      <Link
                        :href="route('developer.request-delivery')"
                        class="
                          mt-3
                          w-min
                          whitespace-nowrap
                          px-4
                          py-2
                          bg-speedapp-orange-500
                          border border-transparent
                          rounded-md
                          font-semibold
                          text-xs text-white
                          uppercase
                          hover:bg-speedapp-orange-300
                          active:bg-speedapp-orange-900
                          focus:outline-none
                          transition
                          ease-in-out
                          duration-150
                        "
                      >
                        Solicitar entrega
                      </Link>
                    </div>
                  </td>
                </tr>

                <tr
                  v-for="(delivery, index) in deliveries.data"
                  v-else
                  :key="index"
                >
                  <td
                    class="
                      px-2
                      py-1.5
                      whitespace-nowrap
                      text-sm
                      font-medium
                      text-gray-900
                      
                    "
                  >
                    <div class="flex flex-col items-center">
                      <Link
                        :href="route('developer.deliveries.show', delivery.id)"
                      >
                        #{{ delivery.cid }}
                      </Link>

                      <div class="flex space-x-2 mt-1">
                        <LightningBoltIcon
                          v-if="!delivery.lastmile"
                          class="h-5 w-5 text-speedapp-orange-500"
                        />
                        <CalendarIcon
                          v-if="delivery.scheduled_at !== null"
                          class="h-5 w-5 text-speedapp-orange-500"
                        />
                      </div>
                    </div>
                  </td>

                  <td
                    class="
                      px-2
                      py-1.5
                      whitespace-nowrap
                      text-xs
                      font-medium
                      uppercase
                      text-center
                      border-l
                      
                    "
                  >
                    <span
                      :class="[
                        getStatusColor(delivery.status),
                        'px-2.5 py-1 rounded-md',
                      ]"
                    >{{ delivery.formatted_status }}</span>
                  </td>

                  <td
                    class="
                      px-2
                      py-1.5
                      whitespace-nowrap
                      text-sm text-gray-500
                      border-l
                      
                    "
                  >
                    <span
                      v-if="delivery.driver == null"
                      class="text-gray-900 "
                    >-</span>

                    <Link
                      v-else
                      :href="
                        route('developer.drivers.show', delivery.driver.id)
                      "
                      class="text-blue-500"
                    >
                      {{ delivery.driver.full_name }}
                    </Link>
                  </td>

                  <td
                    class="
                      px-2
                      py-1.5
                      whitespace-nowrap
                      text-sm text-gray-500
                      border-l
                      
                    "
                  >
                    <Link
                      :href="route('developer.users.show', delivery.user.id)"
                      class="text-blue-500"
                    >
                      {{ delivery.user.name }}
                    </Link>
                  </td>

                  <td
                    class="
                      px-2
                      py-1.5
                      whitespace-nowrap
                      text-sm text-gray-500
                      border-l
                       
                    "
                  >
                    {{ delivery.user.city.name }}
                  </td>

                  <td
                    class="
                      px-2
                      py-1.5
                      text-sm text-gray-500
                      border-l
                       
                    "
                  >
                    <span class="font-medium">
                      {{ delivery.customer_name ?? "-" }}
                    </span>

                    <br>

                    <span>
                      {{ delivery.customer_phone ?? "-" }}
                    </span>
                  </td>

                  <td
                    class="
                      px-2
                      py-1.5
                      text-sm text-gray-500
                      border-l
                       
                    "
                  >
                    {{ delivery.steps[1].formatted_address }}
                  </td>

                  <td
                    class="
                      px-2
                      py-1.5
                      whitespace-nowrap
                      text-sm text-gray-500
                      border-l
                       
                    "
                  >
                    {{ delivery.formatted_total_paid ?? "-" }} / <br>
                    {{ delivery.formatted_total_charged ?? "-" }}
                  </td>

                  <td
                    class="
                      px-2
                      py-1.5
                      whitespace-nowrap
                      text-sm text-gray-500
                      border-l
                       
                    "
                  >
                    {{ delivery.formatted_created_at_time }}
                  </td>

                  <td
                    class="
                      px-2
                      py-1.5
                      whitespace-nowrap
                      text-sm text-gray-500 text-center
                      border-l
                      
                    "
                  >
                    <Link
                      :href="route('developer.deliveries.show', delivery.id)"
                      class="
                        rounded-md
                        relative
                        inline-flex
                        items-center
                        px-2.5
                        py-1
                        border border-transparent
                        bg-purple-500
                        text-xs
                        font-medium
                        text-white
                        hover:bg-purple-700
                        active:bg-purple-900
                        focus:z-10
                        focus:outline-none
                        focus:ring-1
                        focus:ring-purple-500
                        focus:border-purple-500
                        uppercase
                      "
                    >
                      Acompanhar
                    </Link>
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
                class="
                  block
                  pl-3
                  pr-10
                  py-2
                  text-base
                  border-gray-300
                  focus:outline-none
                  focus:ring-indigo-500
                  focus:border-indigo-500
                  sm:text-sm
                  rounded-md
                "
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

              <p class="text-sm text-gray-700  ml-3">
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
              <span
                v-if="deliveries.prev_page_url === null"
                class="
                  cursor-not-allowed
                  relative
                  inline-flex
                  items-center
                  px-2
                  py-2
                  rounded-l-md
                  border border-gray-300
                  
                  bg-white
                  
                  text-sm
                  font-medium
                  text-gray-500
                  
                  focus:z-10 focus:outline-none
                "
              >
                <ChevronLeftIcon
                  class="h-5 w-5"
                  aria-hidden="true"
                />
              </span>

              <Link
                v-else
                :href="deliveries.prev_page_url"
                :class="[
                  {
                    'hover:bg-gray-50 ':
                      deliveries.prev_page_url !== null,
                    'cursor-not-allowed': deliveries.prev_page_url === null,
                  },
                  'relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300  bg-white  text-sm font-medium text-gray-500  focus:z-10 focus:outline-none',
                ]"
              >
                <ChevronLeftIcon
                  class="h-5 w-5"
                  aria-hidden="true"
                />
              </Link>

              <template
                v-for="(link, index) in links"
                :key="index"
              >
                <span
                  v-if="link.active || !link.url"
                  class="
                    cursor-not-allowed
                    relative
                    inline-flex
                    justify-center
                    items-center
                    w-10
                    py-2
                    border border-gray-300
                    
                    bg-white
                    
                    text-sm
                    font-medium
                    text-gray-500
                    
                    focus:z-10 focus:outline-none
                  "
                >{{ link.label }}</span>
                <Link
                  v-else
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
                  {{ link.label }}
                </Link>
              </template>

              <span
                v-if="deliveries.next_page_url === null"
                class="
                  cursor-not-allowed
                  -ml-px
                  relative
                  inline-flex
                  items-center
                  px-2
                  py-2
                  rounded-r-md
                  border border-gray-300
                  
                  bg-white
                  
                  text-sm
                  font-medium
                  text-gray-500
                  
                  focus:z-10 focus:outline-none
                "
              >
                <ChevronRightIcon
                  class="h-5 w-5"
                  aria-hidden="true"
                />
              </span>

              <Link
                v-else
                :href="deliveries.next_page_url"
                :class="[
                  {
                    'hover:bg-gray-50 ':
                      deliveries.next_page_url !== null,
                    'cursor-not-allowed': deliveries.next_page_url === null,
                  },
                  '-ml-px relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300  bg-white  text-sm font-medium text-gray-500  focus:z-10 focus:outline-none',
                ]"
              >
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
  </developer-layout>
</template>

<script>
import {
  defineComponent,
  onMounted,
  ref,
  watch,
  onBeforeUnmount,
  computed,
} from "vue";
import DeveloperLayout from "@/Layouts/DeveloperLayout.vue";
import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/vue/solid";
import { Link } from "@inertiajs/inertia-vue3";
import debounce from "lodash/debounce";
import { Inertia } from "@inertiajs/inertia";

import {
  ArrowSmUpIcon,
  ArrowSmDownIcon,
  SwitchVerticalIcon,
  LightningBoltIcon,
  CalendarIcon,
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
    LightningBoltIcon,
    CalendarIcon,
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
    drivers: {
      type: Array,
      default: () => [],
    },
    users: {
      type: Array,
      default: () => [],
    },
    cities: {
      type: Array,
      default: () => [],
    },
    stats: {
      type: Array,
      default: () => [],
    },
  },

  setup(props) {
    let timer = null;
    let timeout = 15000;

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
      {
        field: "driver.id",
        value: ref(params["filter[driver.id]"] ?? null),
      },
      {
        field: "user.id",
        value: ref(params["filter[user.id]"] ?? null),
      },
      {
        field: "user.city.id",
        value: ref(params["filter[user.city.id]"] ?? null),
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
      filter.value[2].value = null;
      filter.value[3].value = null;
    };

    const get = () => {
      Inertia.replace(route("developer.deliveries"), {
        data: {
          page: page.value,
          per_page: perPage.value,
          sort: sort.value,

          [`filter[${filter.value[0].field}]`]: filter.value[0].value
            .replace("#", "")
            .trim(),

          ...(filter.value[1].value !== null && {
            [`filter[${filter.value[1].field}]`]: filter.value[1].value,
          }),

          ...(filter.value[2].value !== null && {
            [`filter[${filter.value[2].field}]`]: filter.value[2].value,
          }),

          ...(filter.value[3].value !== null && {
            [`filter[${filter.value[3].field}]`]: filter.value[3].value,
          }),

          ...(filter.value[4].value !== null && {
            [`filter[${filter.value[4].field}]`]: filter.value[4].value,
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

    const getStatusColor = (status) => {
      if (status === "WAITING") return "bg-blue-500 text-white";

      if (status === "PENDING") return "bg-yellow-300 text-black";

      if (
        status === "ACCEPTED" ||
        status === "COLLECTING" ||
        status === "DELIVERING" ||
        status === "CONFIRMED" ||
        status === "RETURNING"
      )
        return "bg-speedapp-orange-500 text-white";

      if (status === "COMPLETED") return "bg-green-500 text-white";

      if (status === "CANCELED") return "bg-red-500 text-white";

      if (status === "NOT_DELIVERED") return "bg-pink-500 text-white";
    };

    const update = () => {
      if (timer) clearTimeout(timer);

      Inertia.reload({
        onFinish: () => {
          timer = setTimeout(() => {
            update();
          }, timeout);
        },
      });
    };

    onMounted(() => {
      window.emitter.on("newDelivery", get);

      // update();
    });

    onBeforeUnmount(() => {
      window.emitter.off("newDelivery", get);
    });

    return {
      perPage,
      sort,
      filter,
      clear,
      getStatusColor,
      links,
    };
  },
});
</script>
