<template>
  <admin-layout title="Entregas hoje">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Entregas hoje
      </h2>
    </template>

    <div class="py-12">
      <div class="sm:px-6 lg:px-8">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div
            class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8"
          >
            <div class="flex">
              <input
                v-model="search"
                type="text"
                placeholder="Pesquisar por código"
                class="block w-full border-gray-300 rounded-md placeholder-gray-400 focus:border-speedapp-orange-300 focus:ring focus:ring-speedapp-orange-200 focus:ring-opacity-50"
              >
            </div>

            <div
              class="mt-4 border border-gray-300 rounded-md overflow-hidden"
            >
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th
                      scope="col"
                      class="px-2 py-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                      Código
                    </th>

                    <th
                      scope="col"
                      class="px-2 py-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                      Status
                    </th>

                    <th
                      scope="col"
                      class="px-2 py-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                      Entregador
                    </th>

                    <th
                      scope="col"
                      class="px-2 py-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                      Loja
                    </th>

                    <th
                      scope="col"
                      class="px-2 py-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                      Cidade
                    </th>

                    <th
                      scope="col"
                      class="px-2 py-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                      Cliente
                    </th>

                    <th
                      scope="col"
                      class="px-2 py-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                      Endereço de entrega
                    </th>

                    <th
                      scope="col"
                      class="px-2 py-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                      Pago / <br> Cobrado
                    </th>

                    <th
                      scope="col"
                      class="px-2 py-1.5 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                      Criado às
                    </th>

                    <th scope="col">
                      <span class="sr-only">Ações</span>
                    </th>
                  </tr>
                </thead>
                <tbody
                  class="bg-white divide-y divide-gray-200"
                >
                  <tr v-if="deliveries.data.length == 0">
                    <td
                      colspan="12"
                      class="p-6"
                    >
                      <div
                        class="flex flex-col items-center"
                      >
                        <span
                          class="text-center text-sm font-medium text-gray-900"
                        >Nenhuma entrega hoje</span>
                      </div>
                    </td>
                  </tr>

                  <tr
                    v-for="(
                      delivery, index
                    ) in deliveries.data"
                    v-else
                    :key="index"
                  >
                    <td
                      class="px-2 py-1.5 whitespace-nowrap text-sm font-medium text-gray-900"
                    >
                      <Link
                        :href="
                          route(
                            'admin.deliveries.show',
                            delivery.id
                          )
                        "
                      >
                        #{{ delivery.cid }}
                      </Link>
                    </td>

                    <td
                      class="px-2 py-1.5 whitespace-nowrap text-xs font-medium uppercase text-center"
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
                      class="px-2 py-1.5 whitespace-nowrap text-sm text-gray-500"
                    >
                      <span v-if="delivery.driver == null">-</span>

                      <Link
                        v-else
                        :href="
                          route(
                            'admin.drivers.show',
                            delivery.driver.id
                          )
                        "
                        class="text-blue-500"
                      >
                        {{ delivery.driver.full_name }}
                      </Link>
                    </td>

                    <td
                      class="px-2 py-1.5 whitespace-nowrap text-sm text-gray-500"
                    >
                      <Link
                        :href="
                          route(
                            'admin.users.show',
                            delivery.user.id
                          )
                        "
                        class="text-blue-500"
                      >
                        {{ delivery.user.name }}
                      </Link>
                    </td>

                    <td
                      class="px-2 py-1.5 whitespace-nowrap text-sm text-gray-500"
                    >
                      {{ delivery.user.city.name }}
                    </td>

                    <td
                      class="px-2 py-1.5 whitespace-nowrap text-sm text-gray-500"
                    >
                      <span class="font-medium">
                        {{
                          delivery.customer_name ??
                            "-"
                        }}
                      </span>

                      <br>

                      <span>
                        {{
                          delivery.customer_phone ??
                            "-"
                        }}
                      </span>
                    </td>

                    <td
                      class="px-2 py-1.5 text-sm text-gray-500"
                    >
                      {{
                        delivery.steps[1]
                          .formatted_address
                      }}
                    </td>

                    <td
                      class="px-2 py-1.5 whitespace-nowrap text-sm text-gray-500"
                    >
                      {{ delivery.formatted_total_paid ?? "-" }} / <br> {{ delivery.formatted_total_charged ?? "-" }}
                    </td>

                    <td
                      class="px-2 py-1.5 whitespace-nowrap text-sm text-gray-500"
                    >
                      {{
                        delivery.formatted_created_at_time
                      }}
                    </td>

                    <td
                      class="px-2 py-1.5 whitespace-nowrap text-sm text-gray-500 text-right"
                    >
                      <Link
                        :href="
                          route(
                            'admin.deliveries.show',
                            delivery.id
                          )
                        "
                        class="rounded-md relative inline-flex items-center px-2.5 py-1 border border-transparent bg-purple-500 text-xs font-medium text-white hover:bg-purple-700 active:bg-purple-900 focus:z-10 focus:outline-none focus:ring-1 focus:ring-purple-500 focus:border-purple-500 uppercase"
                      >
                        Acompanhar
                      </Link>
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

              <span class="relative z-0 inline-flex rounded-md">
                <Link
                  :href="deliveries.prev_page_url"
                  class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 focus:z-10 focus:outline-none focus:ring-1 focus:ring-speedapp-orange-500 focus:border-speedapp-orange-500"
                >
                  <span class="sr-only">Anterior</span>
                  <ChevronLeftIcon
                    class="h-5 w-5"
                    aria-hidden="true"
                  />
                </Link>
                <Link
                  :href="deliveries.next_page_url"
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
    </div>
  </admin-layout>
</template>

<script>
import { defineComponent, ref, watch } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/vue/solid";
import { Link } from "@inertiajs/inertia-vue3";
import debounce from "lodash/debounce";
import { Inertia } from "@inertiajs/inertia";

export default defineComponent({
    components: {
        AdminLayout,
        ChevronLeftIcon,
        ChevronRightIcon,
        Link,
    },

    props: {
        deliveries: {
            type: Object,
            required: true,
        },
    },

    setup() {
      const search = ref("");

      const get = () => {
        Inertia.replace(route('admin.deliveries'), {
          data: {
            [`filter[cid]`]: search.value.replace("#", "").trim(),
          },
        })
      }

       watch(
            filter,
            debounce((n, o) => get(), 400),
            {
                deep: true,
            }
        );

      watch(search, debounce((n, o) => get(), 400));

      return {
        search,
        get,
      }
    },
});
</script>
