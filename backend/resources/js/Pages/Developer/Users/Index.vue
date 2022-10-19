<template>
  <developer-layout title="Lojas">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Lojas
        </h2>

        <Link
          :href="route('developer.users.create')"
          class="inline-flex items-center px-4 py-2 bg-speedapp-orange-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-speedapp-orange-300 active:bg-speedapp-orange-900 focus:outline-none transition ease-in-out duration-150"
        >
          Adicionar
        </Link>
      </div>
    </template>

    <div class="py-12">
      <div class="overflow-x-auto">
        <div
          class="py-2 align-middle inline-block min-w-full"
        >
          <div
            class="border border-gray-300 rounded-md overflow-hidden"
          >
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th
                    scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider border-l"
                    @click="
                      sort == 'name'
                        ? (sort = '-name')
                        : (sort = 'name')
                    "
                  >
                    <div class="flex flex-row">
                      <div class="h-4 w-4">
                        <ArrowSmUpIcon
                          v-if="sort == 'name'"
                        />
                        <ArrowSmDownIcon
                          v-if="sort == '-name'"
                        />
                        <SwitchVerticalIcon
                          v-if="
                            sort !== 'name' &&
                              sort !== '-name'
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
                    E-mail
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
                        for="email"
                        class="sr-only"
                      >E-mail</label>
                      <input
                        id="email"
                        v-model="filter[1].value"
                        autocomplete="off"
                        type="text"
                        name="email"
                        class="shadow-sm focus:ring-speedapp-orange-500 focus:border-speedapp-orange-500 block w-full sm:text-sm border-gray-300 rounded-md"
                      >
                    </div>
                  </td>

                  <td
                    class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 border-l"
                  />

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
                  v-for="(user, index) in users.data"
                  :key="index"
                >
                  <td
                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"
                  >
                    {{ user.name }}
                  </td>

                  <td
                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border-l"
                  >
                    {{ user.email }}
                  </td>

                  <td
                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border-l"
                  >
                    {{ user.phone ?? "-" }}
                  </td>

                  <td
                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border-l"
                  >
                    {{ user.city.name ?? "-" }}
                  </td>

                  <td
                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border-l"
                  >
                    {{ user.formatted_created_at }}
                  </td>

                  <td
                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border-l"
                  >
                    {{ user.formatted_updated_at }}
                  </td>

                  <td
                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 border-l"
                  >
                    <span
                      class="relative z-0 inline-flex shadow-sm"
                    >
                      <Link
                        :href="
                          route(
                            'developer.users.show',
                            user.id
                          )
                        "
                        class="rounded-md py-1 relative inline-flex items-center px-3 py-2 border border-transparent bg-purple-500 text-sm font-medium text-white hover:bg-purple-700 active:bg-purple-900 focus:z-10 focus:outline-none focus:ring-1 focus:ring-purple-500 focus:border-purple-500"
                      >
                        Visualizar
                      </Link>

                      <Link
                        :href="
                          route(
                            'developer.users.edit',
                            user.id
                          )
                        "
                        class="rounded-md ml-2 py-1 relative inline-flex items-center px-3 py-2 border border-transparent bg-blue-500 text-sm font-medium text-white hover:bg-blue-700 active:bg-blue-900 focus:z-10 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                      >
                        Editar
                      </Link>

                      <!-- <button @click='destroy(user.id)' type="button" class="rounded-md ml-2 relative inline-flex items-center px-3 py-2 border border-transparent bg-red-500 text-sm font-medium text-white hover:bg-red-700 active:bg-red-900 focus:z-10 focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500">
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
                  {{ users.from }}
                </span>
                {{ " " }}
                até
                {{ " " }}
                <span class="font-medium">
                  {{ users.to }}
                </span>
                {{ " " }}
                de
                {{ " " }}
                <span class="font-medium">
                  {{ users.total }}
                </span>
                {{ " " }}
                registros
              </p>
            </div>

            <span class="relative z-0 inline-flex rounded-md">
              <Link
                :href="users.prev_page_url"
                class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-speedapp-orange-500 focus:border-speedapp-orange-500"
              >
                <span class="sr-only">Anterior</span>
                <ChevronLeftIcon
                  class="h-5 w-5"
                  aria-hidden="true"
                />
              </Link>
              <Link
                :href="users.next_page_url"
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
  </developer-layout>
</template>

<script>
import { defineComponent, ref, watch } from "vue";
import DeveloperLayout from "@/Layouts/DeveloperLayout.vue";
import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/vue/solid";
import debounce from "lodash/debounce";
import { Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";

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
    },

    props: {
        users: {
          type: Object,
          required: true,
        },
        cities: {
          type: Array,
          default: () => [],
        },
    },

    setup() {
        const sort = ref("-created_at");

        const filter = ref([
            {
                field: "name",
                value: ref(""),
            },
            {
                field: "email",
                value: ref(""),
            },
            {
                field: "city.id",
                value: ref(null),
            },
        ]);

        const clear = () => {
            filter.value[0].value = "";
            filter.value[1].value = "";
            filter.value[2].value = null;
        };

        const get = () => {
            Inertia.replace(route("developer.users.index"), {
                data: {
                    sort: sort.value,

                    [`filter[${filter.value[0].field}]`]: filter.value[0].value,
                    [`filter[${filter.value[1].field}]`]: filter.value[1].value,

                    ...(filter.value[2].value !== null && {
                        [`filter[${filter.value[2].field}]`]:
                            filter.value[2].value,
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
            Inertia.delete(route("developer.users.destroy", id), {
                preserveScroll: true,
            });
        };

        return {
            sort,
            filter,
            clear,
            destroy,
        };
    },
});
</script>
