<template>
  <developer-layout title="Entregadores">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Entregadores
        </h2>

        <div class="flex">
          <div v-if="driver.status == 'PENDING'">
            <Link
              as="button"
              class="
                ml-2
                inline-flex
                items-center
                px-4
                py-2
                bg-green-500
                border border-transparent
                rounded-md
                font-semibold
                text-xs text-white
                uppercase
                tracking-widest
                hover:bg-green-300
                active:bg-green-900
                focus:outline-none
                transition
                ease-in-out
                duration-150
              "
              @click="approve"
            >
              Aprovar
            </Link>

            <Link
              as="button"
              class="
                ml-2
                inline-flex
                items-center
                px-4
                py-2
                bg-red-500
                border border-transparent
                rounded-md
                font-semibold
                text-xs text-white
                uppercase
                tracking-widest
                hover:bg-red-300
                active:bg-red-900
                focus:outline-none
                transition
                ease-in-out
                duration-150
              "
              @click="reject"
            >
              Rejeitar
            </Link>
          </div>

          <Link
            v-if="driver.status == 'REJECTED'"
            as="button"
            class="
              ml-2
              inline-flex
              items-center
              px-4
              py-2
              bg-red-500
              border border-transparent
              rounded-md
              font-semibold
              text-xs text-white
              uppercase
              tracking-widest
              hover:bg-red-300
              active:bg-red-900
              focus:outline-none
              transition
              ease-in-out
              duration-150
            "
            @click="unreject"
          >
            Desrejeitar
          </Link>

          <Link
            v-else-if="driver.status == 'APPROVED' && !driver.banned"
            :href="route('developer.drivers.edit', driver.id)"
            class="
              ml-2
              inline-flex
              items-center
              px-4
              py-2
              bg-speedapp-orange-500
              border border-transparent
              rounded-md
              font-semibold
              text-xs text-white
              uppercase
              tracking-widest
              hover:bg-speedapp-orange-300
              active:bg-speedapp-orange-900
              focus:outline-none
              transition
              ease-in-out
              duration-150
            "
          >
            Editar
          </Link>

          <Link
            v-if="driver.banned"
            as="button"
            class="
              inline-flex
              items-center
              px-4
              py-2
              bg-green-500
              border border-transparent
              rounded-md
              font-semibold
              text-xs text-white
              uppercase
              tracking-widest
              hover:bg-green-300
              active:bg-green-900
              focus:outline-none
              transition
              ease-in-out
              duration-150
            "
            @click="unban"
          >
            Desbanir
          </Link>

          <Link
            :href="route('developer.drivers.index')"
            class="
              ml-2
              inline-flex
              items-center
              px-4
              py-2
              bg-speedapp-gray-500
              border border-transparent
              rounded-md
              font-semibold
              text-xs text-white
              uppercase
              tracking-widest
              hover:bg-speedapp-gray-300
              active:bg-speedapp-gray-900
              focus:outline-none
              transition
              ease-in-out
              duration-150
            "
          >
            Voltar
          </Link>
        </div>
      </div>
    </template>

    <div class="py-12">
      <div>
        <span
          v-if="driver.banned"
          class="
            mb-5
            block
            text-center
            font-bold
            tracking-widest
            text-xl text-red-500
            uppercase
          "
        >
          BANIDO
        </span>

        <span
          v-if="driver.status == 'REJECTED'"
          class="
            mb-5
            block
            text-center
            font-bold
            tracking-widest
            text-xl text-red-500
            uppercase
          "
        >
          {{ driver.formatted_status }}
        </span>

        <div class="flex flex-col">
          <div class="overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div
              class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8"
            >
              <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:p-0">
                  <dl class="sm:divide-y sm:divide-gray-200">
                    <div
                      class="
                        py-4
                        sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6
                      "
                    >
                      <dt class="text-sm font-medium text-gray-500">
                        Primeiro nome
                      </dt>
                      <dd
                        class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                      >
                        {{ driver.first_name ?? '-' }}
                      </dd>
                    </div>

                    <div
                      class="
                        py-4
                        sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6
                      "
                    >
                      <dt class="text-sm font-medium text-gray-500">
                        Último nome
                      </dt>
                      <dd
                        class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                      >
                        {{ driver.last_name ?? '-' }}
                      </dd>
                    </div>

                    <div
                      class="
                        py-4
                        sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6
                      "
                    >
                      <dt class="text-sm font-medium text-gray-500">
                        Telefone
                      </dt>
                      <dd
                        class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                      >
                        {{ driver.formatted_phone }}
                      </dd>
                    </div>

                    <div
                      class="
                        py-4
                        sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6
                      "
                    >
                      <dt class="text-sm font-medium text-gray-500">
                        E-mail
                      </dt>
                      <dd
                        class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                      >
                        {{ driver.email }}
                      </dd>
                    </div>

                    <div
                      class="
                        py-4
                        sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6
                      "
                    >
                      <dt class="text-sm font-medium text-gray-500">
                        Criado às
                      </dt>
                      <dd
                        class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                      >
                        {{ driver.formatted_created_at }}
                      </dd>
                    </div>
                    <div
                      class="
                        py-4
                        sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6
                      "
                    >
                      <dt class="text-sm font-medium text-gray-500">
                        Cidade
                      </dt>
                      <dd
                        class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"
                      >
                        <Link
                          v-if="driver.city_id"
                          :href="route('developer.cities.show', driver.city_id)"
                          class="font-bold"
                        >
                          {{ driver.city?.name ?? '-' }}
                        </Link>
                        <span v-else>-</span>
                      </dd>
                    </div>
                  </dl>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </developer-layout>

  <div
    aria-live="assertive"
    class="
      fixed
      inset-0
      flex
      items-end
      px-4
      py-6
      pointer-events-none
      sm:p-6 sm:items-start
    "
  >
    <div class="w-full flex flex-col items-center space-y-4 sm:items-end">
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
          class="
            max-w-sm
            w-full
            bg-white
            shadow-lg
            rounded-lg
            pointer-events-auto
            ring-1 ring-black ring-opacity-5
            overflow-hidden
          "
        >
          <div class="p-4">
            <div class="flex items-center">
              <div class="shrink-0">
                <CheckCircleIcon
                  class="h-6 w-6 text-green-400"
                  aria-hidden="true"
                />
              </div>

              <div class="ml-3 w-0 flex-1 flex justify-between items-center">
                <p class="text-sm font-medium text-gray-900">
                  {{ status }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref } from "vue";
import DeveloperLayout from "@/Layouts/DeveloperLayout.vue";
import JetButton from "@/Jetstream/Button.vue";
import JetFormSection from "@/Jetstream/FormSection.vue";
import JetInput from "@/Jetstream/Input.vue";
import JetInputError from "@/Jetstream/InputError.vue";
import JetLabel from "@/Jetstream/Label.vue";
import JetActionMessage from "@/Jetstream/ActionMessage.vue";
import JetSecondaryButton from "@/Jetstream/SecondaryButton.vue";
import { Link } from "@inertiajs/inertia-vue3";
import { Inertia } from "@inertiajs/inertia";
import { CheckCircleIcon, XCircleIcon } from "@heroicons/vue/solid";

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
    CheckCircleIcon,
    XCircleIcon,
  },

  props: {
    driver: {
      type: Object,
      required: true,
    },
  },

  setup(props) {

    const status = ref(null);

    const approve = () => {
      Inertia.put(route("developer.drivers.approve", props.driver.id));
    }

    const reject = () => {
      Inertia.put(route("developer.drivers.reject", props.driver.id));
    }

    const unban = () => {
      Inertia.post(route("developer.drivers.unban", props.driver.id));
    }

    const unreject = () => {
      Inertia.put(route("developer.drivers.unreject", props.driver.id));
    }
        
    return {
      status,
      approve,
      reject,
      unban,
      unreject,
    }
  }
});
</script>
