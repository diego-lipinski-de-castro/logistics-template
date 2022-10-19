<template>
  <div>
    <Head :title="title" />

    <jet-banner />

    <div class="min-h-screen bg-gray-100">
      <nav class="bg-white border-b border-gray-100">
        <!-- Primary Navigation Menu -->
        <div 
          :class="[
            {
              'max-w-7xl mx-auto': !wide,
            },
            'px-4 sm:px-6 lg:px-8',
          ]"
        >
          <div class="flex justify-between h-16">
            <div class="flex">
              <!-- Logo -->
              <div class="shrink-0 flex items-center">
                <Link :href="route('user.deliveries')">
                  <jet-application-mark class="block h-9 w-auto" />
                </Link>
              </div>

              <!-- Navigation Links -->
              <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                <jet-nav-link
                  :href="route('user.deliveries')"
                  :active="route().current('user.deliveries')"
                >
                  Entregas hoje
                </jet-nav-link>

                <jet-nav-link
                  :href="route('user.deliveries.history')"
                  :active="route().current('user.deliveries.history')"
                >
                  Histórico de entregas
                </jet-nav-link>

                <jet-nav-link
                  :href="route('user.request-delivery')"
                  :active="route().current('user.request-delivery')"
                >
                  Solicitar entrega
                </jet-nav-link>
              </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
              <!-- Settings Dropdown -->
              <div class="ml-3 relative">
                <jet-dropdown
                  align="right"
                  width="48"
                >
                  <template #trigger>
                    <button
                      v-if="$page.props.jetstream.managesProfilePhotos"
                      class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition"
                    >
                      <img
                        class="h-8 w-8 rounded-full object-cover"
                        :src="$page.props.authUser.profile_photo_url"
                        :alt="$page.props.authUser.name"
                      >
                    </button>

                    <span
                      v-else
                      class="inline-flex rounded-md"
                    >
                      <button
                        type="button"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition"
                      >
                        {{ $page.props.authUser.name }}

                        <svg
                          class="ml-2 -mr-0.5 h-4 w-4"
                          xmlns="http://www.w3.org/2000/svg"
                          viewBox="0 0 20 20"
                          fill="currentColor"
                        >
                          <path
                            fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                          />
                        </svg>
                      </button>
                    </span>
                  </template>

                  <template #content>
                    <!-- Account Management -->
                    <div class="block px-4 py-2 text-xs text-gray-400">
                      Gerenciar conta
                    </div>

                    <jet-dropdown-link :href="route('profile.show')">
                      Perfil
                    </jet-dropdown-link>

                    <jet-dropdown-link
                      v-if="!$page.props.authUser.address !== null && !$page.props.authUser.lastmile"
                      :href="route('user.area')"
                    >
                      Áreas de entrega
                    </jet-dropdown-link>

                    <jet-dropdown-link
                      v-if="$page.props.jetstream.hasApiFeatures"
                      :href="route('api-tokens.index')"
                    >
                      API Tokens
                    </jet-dropdown-link>

                    <div
                      class="border-t border-gray-100"
                    />

                    <jet-dropdown-link as="button">
                      <span
                        class="flex justify-between items-center py-1"
                      >
                        <span class="relative">
                          Ultrawide

                          <span class="absolute -top-[5px] -right-[30px] text-[10px] font-bold tracking-wider leading-6 uppercase text-black ">Beta</span>
                        </span>

                        <Switch
                          v-model="wide"
                          :class="[
                            wide
                              ? 'bg-speedapp-orange-600 '
                              : 'bg-gray-200 ',
                            'relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none',
                          ]"
                        >
                          <span class="sr-only">Ultrawide</span>
                          <span
                            aria-hidden="true"
                            :class="[
                              wide
                                ? 'translate-x-5'
                                : 'translate-x-0',
                              'pointer-events-none inline-block h-5 w-5 rounded-full bg-white shadow transform ring-0 transition ease-in-out duration-200',
                            ]"
                          />
                        </Switch>
                      </span>
                    </jet-dropdown-link>

                    <div class="border-t border-gray-100" />

                    <!-- Authentication -->
                    <form @submit.prevent="logout">
                      <jet-dropdown-link as="button">
                        Sair
                      </jet-dropdown-link>
                    </form>
                  </template>
                </jet-dropdown>
              </div>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
              <button
                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition"
                @click="showingNavigationDropdown = ! showingNavigationDropdown"
              >
                <svg
                  class="h-6 w-6"
                  stroke="currentColor"
                  fill="none"
                  viewBox="0 0 24 24"
                >
                  <path
                    :class="{'hidden': showingNavigationDropdown, 'inline-flex': ! showingNavigationDropdown }"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"
                  />
                  <path
                    :class="{'hidden': ! showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                  />
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div
          :class="{'block': showingNavigationDropdown, 'hidden': ! showingNavigationDropdown}"
          class="sm:hidden"
        >
          <div class="pt-2 pb-3 space-y-1">
            <jet-responsive-nav-link
              :href="route('user.deliveries')"
              :active="route().current('user.deliveries')"
            >
              Entregas de hoje
            </jet-responsive-nav-link>

            <jet-responsive-nav-link
              :href="route('user.deliveries.history')"
              :active="route().current('user.deliveries.history')"
            >
              Histórico de entregas
            </jet-responsive-nav-link>

            <jet-responsive-nav-link
              :href="route('user.request-delivery')"
              :active="route().current('user.request-delivery')"
            >
              Solicitar entrega
            </jet-responsive-nav-link>
          </div>

          <!-- Responsive Settings Options -->
          <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4">
              <div
                v-if="$page.props.jetstream.managesProfilePhotos"
                class="shrink-0 mr-3"
              >
                <img
                  class="h-10 w-10 rounded-full object-cover"
                  :src="$page.props.authUser.profile_photo_url"
                  :alt="$page.props.authUser.name"
                >
              </div>

              <div>
                <div class="font-medium text-base text-gray-800">
                  {{ $page.props.authUser.name }}
                </div>
                <div class="font-medium text-sm text-gray-500">
                  {{ $page.props.authUser.email }}
                </div>
              </div>
            </div>

            <div class="mt-3 space-y-1">
              <jet-responsive-nav-link
                :href="route('profile.show')"
                :active="route().current('profile.show')"
              >
                Perfil
              </jet-responsive-nav-link>

              <jet-responsive-nav-link
                v-if="!$page.props.authUser.address !== null && !$page.props.authUser.lastmile" 
                :href="route('user.area')"
                :active="route().current('user.area')"
              >
                Áreas de entrega
              </jet-responsive-nav-link>

              <jet-responsive-nav-link
                v-if="$page.props.jetstream.hasApiFeatures"
                :href="route('api-tokens.index')"
                :active="route().current('api-tokens.index')"
              >
                API Tokens
              </jet-responsive-nav-link>

              <!-- Authentication -->
              <form
                method="POST"
                @submit.prevent="logout"
              >
                <jet-responsive-nav-link as="button">
                  Sair
                </jet-responsive-nav-link>
              </form>
            </div>
          </div>
        </div>
      </nav>

      <!-- Page Heading -->
      <header
        v-if="$slots.header"
        class="bg-white shadow"
      >
        <div
          :class="[
            {
              'max-w-7xl mx-auto': !wide,
            },
            'py-6 px-4 sm:px-6 lg:px-8',
          ]"
        >
          <slot name="header" />
        </div>
      </header>

      <!-- Page Content -->
      <main
        :class="[
          {
            'max-w-7xl mx-auto': !wide,
          },
          'px-4 sm:px-6 lg:px-8',
        ]"
      >
        <slot />
      </main>
    </div>
  </div>
</template>

<script>
    import { defineComponent, onMounted, ref, watch } from 'vue'
    import JetApplicationMark from '@/Jetstream/ApplicationMark.vue'
    import JetBanner from '@/Jetstream/Banner.vue'
    import JetDropdown from '@/Jetstream/Dropdown.vue'
    import JetDropdownLink from '@/Jetstream/DropdownLink.vue'
    import JetNavLink from '@/Jetstream/NavLink.vue'
    import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink.vue'
    import { Head, Link, usePage } from '@inertiajs/inertia-vue3';
    import { Inertia } from "@inertiajs/inertia";
    import { Loader } from "@googlemaps/js-api-loader";
    import { Switch, Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from "@headlessui/vue";
    import { ClipboardIcon } from "@heroicons/vue/outline";

    export default defineComponent({

        components: {
            Head,
            JetApplicationMark,
            JetBanner,
            JetDropdown,
            JetDropdownLink,
            JetNavLink,
            JetResponsiveNavLink,
            Link,
            Switch,
            Dialog,
            DialogPanel,
            DialogTitle,
            TransitionChild,
            TransitionRoot,
            ClipboardIcon,
        },
        props: {
            title: {
              type: String,
              required: false,
              default: () => '',
            },
        },

        setup() {
            const loadingBalance = ref(false);
            const loadingQrcode = ref(false);
            const wide = ref(
              localStorage.getItem("entregas.speedapp.wide") === "true"
            );

            watch(wide, (n, o) =>
              localStorage.setItem("entregas.speedapp.wide", n)
            );

            const page = usePage().props.value;
            
            const showingNavigationDropdown = ref(false);
            const showingBalanceModal = ref(false);

            const balance = ref(0);
            const qrcode = ref(null);

            let googleMapsInitialized = false;
 
            const logout = () => {
                Inertia.post(route('logout'));
            }

            const initGoogleApi = async (cb) => {
                if (googleMapsInitialized) {
                    if (cb) cb();

                    return;
                }

                const loader = new Loader({
                    apiKey: "",
                    version: "quarterly",
                    libraries: ["places"],
                    language: 'pt-BR',
                    region: 'BR'
                });

                window.google = await loader.load();

                googleMapsInitialized = true;

                if (cb) cb();
            };

            onMounted(() => {
              window.emitter.on('initGoogleMaps', initGoogleApi);
            });

            return {
                showingNavigationDropdown,
                showingBalanceModal,
                openBalanceModal,
                logout,
                wide,
                balance,
                qrcode,
                loadingBalance,
                loadingQrcode,
                copyQrcode,
            }
        }
    });
</script>
