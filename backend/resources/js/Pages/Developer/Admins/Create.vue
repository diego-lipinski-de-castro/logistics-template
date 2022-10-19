<template>
  <developer-layout title="Admins">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Admins
        </h2>

        <Link
          :href="route('developer.admins.index')"
          class="inline-flex items-center px-4 py-2 bg-speedapp-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-speedapp-gray-300 active:bg-speedapp-gray-900 focus:outline-none transition ease-in-out duration-150"
        >
          Voltar
        </Link> 
      </div>
    </template>

    <div class="py-12">
      <div class="flex flex-col">
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
            <form @submit.prevent="submit">
              <div
                class="
                                        border border-gray-300
                                        rounded-md
                                        overflow-hidden
                                    "
              >
                <div
                  class="
                                            px-4
                                            py-5
                                            bg-white
                                            sm:p-6
                                        "
                >
                  <div
                    class="
                                                grid grid-cols-6
                                                gap-6
                                            "
                  >
                    <div class="col-span-6">
                      <jet-label
                        for="name"
                        value="Nome"
                      />
                      <jet-input
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="mt-1 block w-full"
                        autocomplete="name"
                      />
                      <jet-input-error
                        :message="form.errors.name"
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
                        for="password"
                        value="Senha"
                      />
                      <jet-input
                        id="password"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-full"
                      />
                      <jet-input-error
                        :message="form.errors.password"
                        class="mt-2"
                      />
                    </div>
                  </div>
                </div>
                <div
                  class="
                                            px-4
                                            py-3
                                            bg-gray-50
                                            text-right
                                            sm:px-6
                                        "
                >                                    
                  <button
                    type="submit"
                    class="
                                                inline-flex
                                                justify-center
                                                py-2
                                                px-4
                                                border
                                                border-transparent
                                                shadow-sm
                                                text-sm
                                                font-medium
                                                rounded-md
                                                text-white
                                                bg-speedapp-orange-600
                                                hover:bg-speedapp-orange-700
                                                focus:outline-none
                                                focus:ring-2
                                                focus:ring-offset-2
                                                focus:ring-speedapp-orange-500
                                            "
                                            
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                  >
                    Salvar
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </developer-layout>
</template>

<script>
    import { defineComponent } from 'vue'
    import DeveloperLayout from '@/Layouts/DeveloperLayout.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetFormSection from '@/Jetstream/FormSection.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetInputError from '@/Jetstream/InputError.vue'
    import JetLabel from '@/Jetstream/Label.vue'
    import JetActionMessage from '@/Jetstream/ActionMessage.vue'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue'
    import { Link } from '@inertiajs/inertia-vue3';

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
        },

        props: {
            cities: {
              type: Array,
              default: () => [],
            },
        },

        data() {
            return {
                form: this.$inertia.form({
                    name: '',
                    email: '',
                    password: '',
                }),
            }
        },

        methods: {
            submit() {
                this.form.post(route('developer.admins.store'), {
                    preserveScroll: true
                });
            },
        },
    });
</script>
