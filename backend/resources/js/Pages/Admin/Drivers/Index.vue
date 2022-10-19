<template>
  <admin-layout title="Entregadores">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Entregadores
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
          <div
            class="
                            py-2
                            align-middle
                            inline-block
                            min-w-full
                            sm:px-6
                            lg:px-8
                        "
          >
            <div class="flex">
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

            <div
              class="
                                mt-4
                                border border-gray-300
                                rounded-md
                                overflow-hidden
                            "
            >
              <table
                class="min-w-full divide-y divide-gray-200"
              >
                <thead class="bg-gray-50">
                  <tr>
                    <th
                      scope="col"
                      class="
                                                px-6
                                                py-3
                                                text-left text-xs
                                                font-medium
                                                text-gray-500
                                                uppercase
                                                tracking-wider
                                            "
                    >
                      Nome
                    </th>

                    <th
                      scope="col"
                      class="
                                                px-6
                                                py-3
                                                text-left text-xs
                                                font-medium
                                                text-gray-500
                                                uppercase
                                                tracking-wider
                                            "
                    >
                      Criado às
                    </th>
                                        
                    <th
                      scope="col"
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
                  <tr
                    v-for="(
                      driver, index
                    ) in drivers.data"
                    :key="index"
                  >
                    <td
                      class="
                                                px-6
                                                py-4
                                                whitespace-nowrap
                                                text-sm
                                                font-medium
                                                text-gray-900
                                            "
                    >
                      {{ driver.full_name }}
                    </td>

                    <td
                      class="
                                                px-6
                                                py-4
                                                whitespace-nowrap
                                                text-sm text-gray-500
                                            "
                    >
                      {{ driver.formatted_created_at }}
                    </td>

                    <td
                      class="
                                                px-6
                                                py-4
                                                whitespace-nowrap
                                                text-sm text-gray-500
                                                text-right
                                            "
                    >
                      <span class="relative z-0 inline-flex shadow-sm">
                        <Link
                          :href="route('admin.drivers.show', driver.id)"
                          class="rounded-md py-1 relative inline-flex items-center px-3 py-2 border border-transparent bg-purple-500 text-sm font-medium text-white hover:bg-purple-700 active:bg-purple-900 focus:z-10 focus:outline-none focus:ring-1 focus:ring-purple-500 focus:border-purple-500"
                        >
                          Visualizar
                        </Link>
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

              <span
                class="relative z-0 inline-flex rounded-md"
              >
                <Link
                  :href="drivers.prev_page_url"
                  class="
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
                                        hover:bg-gray-50
                                        focus:z-10
                                        focus:outline-none
                                        focus:ring-1
                                        focus:ring-speedapp-orange-500
                                        focus:border-speedapp-orange-500
                                    "
                >
                  <span class="sr-only">Anterior</span>
                  <ChevronLeftIcon
                    class="h-5 w-5"
                    aria-hidden="true"
                  />
                </Link>
                <Link
                  :href="drivers.next_page_url"
                  class="
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
                                        hover:bg-gray-50
                                        focus:z-10
                                        focus:outline-none
                                        focus:ring-1
                                        focus:ring-speedapp-orange-500
                                        focus:border-speedapp-orange-500
                                    "
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
    import { defineComponent } from 'vue'
    import AdminLayout from '@/Layouts/AdminLayout.vue'
    import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/vue/solid";
    import debounce from 'lodash/debounce';
    import { Link } from '@inertiajs/inertia-vue3';

    export default defineComponent({
        components: {
            AdminLayout,
            ChevronLeftIcon,
            ChevronRightIcon,
            Link,
        },

        props: {
            drivers: {
              type: Object,
              required: true,
            },
        },

        data() {
            return {
                search: '',
            }
        },

        watch: {
            search: debounce(function(n, o) {
                this.get()
            }, 400),
        },

        methods: {
            get() {
                this.$inertia.replace(this.route('admin.drivers.index'), {
                    data: {
                        [`filter[full_name]`]: this.search,
                    }
                });
            }
        },
    });
</script>
