<template>
  <developer-layout title="Admins">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Admins
        </h2>

        <Link
          :href="route('developer.admins.create')"
          class="inline-flex items-center px-4 py-2 bg-speedapp-orange-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-speedapp-orange-300 active:bg-speedapp-orange-900 focus:outline-none transition ease-in-out duration-150"
        >
          Adicionar
        </Link>
      </div>
    </template>

    <div class="py-12">
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
              placeholder="Pesquisar por nome ou e-mail"
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
                    E-mail
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
                    Atualizado às
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
                    admin, index
                  ) in admins.data"
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
                    {{ admin.name }}
                  </td>

                  <td
                    class="
                                                px-6
                                                py-4
                                                whitespace-nowrap
                                                text-sm text-gray-500
                                            "
                  >
                    {{ admin.email }}
                  </td>

                  <td
                    class="
                                                px-6
                                                py-4
                                                whitespace-nowrap
                                                text-sm text-gray-500
                                            "
                  >
                    {{ admin.formatted_created_at }}
                  </td>

                  <td
                    class="
                                                px-6
                                                py-4
                                                whitespace-nowrap
                                                text-sm text-gray-500
                                            "
                  >
                    {{ admin.formatted_updated_at }}
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
                        :href="route('developer.admins.show', admin.id)"
                        class="rounded-md py-1 relative inline-flex items-center px-3 py-2 border border-transparent bg-purple-500 text-sm font-medium text-white hover:bg-purple-700 active:bg-purple-900 focus:z-10 focus:outline-none focus:ring-1 focus:ring-purple-500 focus:border-purple-500"
                      >
                        Visualizar
                      </Link>

                      <Link
                        :href="route('developer.admins.edit', admin.id)"
                        class="rounded-md ml-2 py-1 relative inline-flex items-center px-3 py-2 border border-transparent bg-blue-500 text-sm font-medium text-white hover:bg-blue-700 active:bg-blue-900 focus:z-10 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                      >
                        Editar
                      </Link>

                      <!-- <button @click='destroy(admin.id)' type="button" class="rounded-md ml-2 relative inline-flex items-center px-3 py-2 border border-transparent bg-red-500 text-sm font-medium text-white hover:bg-red-700 active:bg-red-900 focus:z-10 focus:outline-none focus:ring-1 focus:ring-red-500 focus:border-red-500">
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
                  {{ admins.from }}
                </span>
                {{ " " }}
                até
                {{ " " }}
                <span class="font-medium">
                  {{ admins.to }}
                </span>
                {{ " " }}
                de
                {{ " " }}
                <span class="font-medium">
                  {{ admins.total }}
                </span>
                {{ " " }}
                registros
              </p>
            </div>

            <span
              class="relative z-0 inline-flex rounded-md"
            >
              <Link
                :href="admins.prev_page_url"
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
                :href="admins.next_page_url"
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
  </developer-layout>
</template>

<script>
    import { defineComponent, ref, watch } from 'vue'
    import DeveloperLayout from '@/Layouts/DeveloperLayout.vue'
    import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/vue/solid";
    import debounce from 'lodash/debounce';
    import { Link } from '@inertiajs/inertia-vue3';
    import { Inertia } from "@inertiajs/inertia";

    export default defineComponent({
        components: {
            DeveloperLayout,
            ChevronLeftIcon,
            ChevronRightIcon,
            Link,
        },

        props: {
            admins: {
              type: Object,
              required: true,
            },
        },

        setup() {
          const params = new Proxy(new URLSearchParams(window.location.search), {
            get: (searchParams, prop) => searchParams.get(prop),
          });

          const search = ref(params['filter[name_email]'] ?? '');

          const get = () => {
              Inertia.replace(route('developer.admins.index'), {
                  data: {
                      [`filter[name_email]`]: search.value,
                  }
              });
          };

          const destroy = (id) => {
              Inertia.delete(route('developer.admins.destroy', id), {
                  preserveScroll: true,
              });
          };

          watch(search, debounce((n, o) => get(), 400));

          return {
            search,
            destroy,
          }
        },
    });
</script>
