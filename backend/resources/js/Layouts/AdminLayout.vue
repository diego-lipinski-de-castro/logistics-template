<template>
  <div>
    <Head :title="title" />

    <jet-banner />

    <audio
      ref="audio"
      preload="auto"
      loop
    >
      <source
        :src="$page.props.alert"
        type="audio/mpeg"
      >
    </audio>

    <div class="min-h-screen bg-gray-100">
      <nav class="bg-white border-b border-gray-100">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between h-16">
            <div class="flex">
              <!-- Logo -->
              <div class="shrink-0 flex items-center">
                <Link :href="route('admin.deliveries')">
                  <jet-application-mark class="block h-9 w-auto" />
                </Link>
              </div>

              <!-- Navigation Links -->
              <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                <jet-nav-link
                  :href="route('admin.deliveries')"
                  :active="route().current('admin.deliveries')"
                >
                  Entregas hoje
                </jet-nav-link>

                <jet-nav-link
                  :href="route('admin.deliveries.history')"
                  :active="route().current('admin.deliveries.history')"
                >
                  Histórico de entregas
                </jet-nav-link>

                <jet-nav-link
                  :href="route('admin.map')"
                  :active="route().current('admin.map')"
                >
                  Mapa
                </jet-nav-link>
              </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
              <!-- Settings Dropdown -->
              <button
                v-show="isPlaying"
                @click="stopAudio"
              >
                <VolumeOffIcon class="h-5 w-5 text-red-500 hover:text-red-700" />
              </button>

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

                    <jet-dropdown-link :href="route('admin.profile.show')">
                      Perfil
                    </jet-dropdown-link>

                    <div class="border-t border-gray-100" />

                    <div class="block px-4 py-2 text-xs text-gray-400">
                      Gerenciar aplicação
                    </div>

                    <jet-dropdown-link :href="route('admin.drivers.index')">
                      Entregadores
                    </jet-dropdown-link>

                    <jet-dropdown-link :href="route('admin.users.index')">
                      Lojas
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
              :href="route('admin.deliveries')"
              :active="route().current('admin.deliveries')"
            >
              Entregas de hoje
            </jet-responsive-nav-link>

            <jet-responsive-nav-link
              :href="route('admin.deliveries.history')"
              :active="route().current('admin.deliveries.history')"
            >
              Histórico de entregas
            </jet-responsive-nav-link>

            <jet-responsive-nav-link
              :href="route('admin.map')"
              :active="route().current('admin.map')"
            >
              Mapa
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
                :href="route('admin.profile.show')"
                :active="route().current('admin.profile.show')"
              >
                Perfil
              </jet-responsive-nav-link>

              <jet-responsive-nav-link
                :href="route('admin.drivers.index')"
                :active="route().current('admin.drivers.*')"
              >
                Entregadores
              </jet-responsive-nav-link>

              <jet-responsive-nav-link
                :href="route('admin.users.index')"
                :active="route().current('admin.users.*')"
              >
                Lojas
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
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
          <slot name="header" />
        </div>
      </header>

      <!-- Page Content -->
      <main>
        <slot />
      </main>
    </div>
  </div>
</template>

<script>
    import { defineComponent, onMounted, ref, onBeforeUnmount } from 'vue'
    import JetApplicationMark from '@/Jetstream/ApplicationMark.vue'
    import JetBanner from '@/Jetstream/Banner.vue'
    import JetDropdown from '@/Jetstream/Dropdown.vue'
    import JetDropdownLink from '@/Jetstream/DropdownLink.vue'
    import JetNavLink from '@/Jetstream/NavLink.vue'
    import JetResponsiveNavLink from '@/Jetstream/ResponsiveNavLink.vue'
    import { Head, Link, usePage } from '@inertiajs/inertia-vue3';
    import { Inertia } from "@inertiajs/inertia";
    import { Loader } from "@googlemaps/js-api-loader";
    import { VolumeOffIcon } from "@heroicons/vue/outline";

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
            VolumeOffIcon
        },
        props: {
            title: {
            type: String,
            required: false,
            default: () => '',
          },
        },

        setup() {
            const page = usePage().props.value;

            const showingNavigationDropdown = ref(false);
            let googleMapsInitialized = false;

            const audio = ref(null);
            const isPlaying = ref(false);

            const logout = () => {
                Inertia.post(route('admin.logout'));
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

            const stopAudio = () => {
                audio.value.pause();
            };

            const play = () => {
                audio.value.play();
            }

            onBeforeUnmount(() => {
                window.emitter.off('initGoogleMaps', initGoogleApi);
            });

            onMounted(() => {
                window.emitter.on('initGoogleMaps', initGoogleApi);

                audio.value.addEventListener('play', () => {
                    isPlaying.value = !audio.value.paused
                });

                audio.value.addEventListener('pause', () => {
                    isPlaying.value = !audio.value.paused
                });
            });

            return {
                showingNavigationDropdown,
                logout,
                audio,
                stopAudio,
                isPlaying,
                play
            }
        }
    });
</script>
