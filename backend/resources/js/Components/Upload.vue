<template>
  <div
    :id="`dropbox-${name}`"
    class="flex justify-center items-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md"
  >
    <div class="w-full space-y-1">
      <div
        v-if="preview || doc"
        class="relative"
      >
        <div class="flex justify-between items-center">
          <span
            class="
                        block
                        text-sm
                        font-medium
                        text-gray-700
                      "
          >{{ label }}</span>

          <button
            type="button"
            class="top-2 right-2 bg-red-500 text-white text-sm font-medium px-2 py-0.5 rounded-md"
            @click="remove"
          >
            Remover
          </button>
        </div>

        <img
          :src="preview || route('developer.drivers.showDocument', [doc.driver_id, doc.id])"
          class="overflow-hidden rounded-md mt-2"
        >
      </div>

      <svg
        v-if="!preview && !doc"
        class="mx-auto h-12 w-12 text-gray-400"
        stroke="currentColor"
        fill="none"
        viewBox="0 0 48 48"
        aria-hidden="true"
      >
        <path
          d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
          stroke-width="2"
          stroke-linecap="round"
          stroke-linejoin="round"
        />
      </svg>

      <div
        v-if="!preview && !doc"
        class="text-center text-sm text-gray-600"
      >
        <label
          :for="name"
          class="relative cursor-pointer bg-white rounded-md font-medium text-speedapp-orange-600 hover:text-speedapp-orange-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-speedapp-orange-500"
        >
          <span>Enviar {{ label.toLowerCase() }}</span>
          <input
            :id="name"
            :name="name"
            type="file"
            class="sr-only"
            @input="updateFile($event)"
          >
        </label>
      </div>
    </div>
  </div>
</template>

<script>
import { defineComponent, ref, onMounted } from "vue";

export default defineComponent({
    props: {
      name: {
        type: String,
        required: true,
      },
      label: {
        type: String,
        required: true,
      },
      doc: {
        type: Object,
        required: false,
        default: () => null
      }
    },

    emits: ['changed', 'deleted'],

    setup(props, { emit }) {
        let initialized = false;

        let fileReader = null;

        const file = ref(null);
        const preview = ref(null);

        const remove = () => {

            if(props.doc) {
                emit('deleted', props.doc);
                return;
            }

            file.value = null;
            preview.value = null;

            emit('changed', null);
        }

        const updateFile = (e) => {
            file.value = e.target.files[0];
            
            fileReader.readAsDataURL(file.value);

            emit('changed', file.value);
        }

        const init = () => {
            if (initialized) return;

            fileReader = new FileReader();

            fileReader.onload = (e) => {
                preview.value = e.target.result;
            }

            setTimeout(() => {
                let dropbox = document.getElementById(`dropbox-${props.name}`);
                let dropboxTarget = undefined;

                dropbox.addEventListener(
                    "dragenter",
                    (e) => {
                        dropboxTarget = e.target;

                        e.stopPropagation();
                        e.preventDefault();

                        dropbox.classList.add("border-green-300");
                    },
                    false
                );

                dropbox.addEventListener(
                    "dragleave",
                    (e) => {
                        if (dropboxTarget !== e.target) return;

                        e.stopPropagation();
                        e.preventDefault();

                        dropbox.classList.remove("border-green-300");
                    },
                    false
                );

                dropbox.addEventListener(
                    "drop",
                    (e) => {
                        e.stopPropagation();
                        e.preventDefault();

                        dropbox.classList.remove("border-green-300");

                        file.value = e.dataTransfer.files[0];

                        fileReader.readAsDataURL(file.value);

                        emit('changed', file.value);
                    },
                    false
                );

                dropbox.addEventListener(
                    "dragover",
                    (e) => {
                        e.stopPropagation();
                        e.preventDefault();
                    },
                    false
                );

                initialized = true;
            }, 100);
        };

        onMounted(init);

        return {
            file,
            preview,
            updateFile,
            remove
        }
    },
});
</script>
