<template>
  <div
    v-show="loading"
    class="v-spinner"
  >
    <div
      class="v-moon v-moon1"
      :style="spinnerStyle"
    >
      <div
        :class="['v-moon v-moon2', moonColor]"
        :style="[spinnerMoonStyle, animationStyle2]"
      />
      <div
        class="v-moon v-moon3 border-color-gray-300"
        :style="[spinnerStyle, animationStyle3]"
      />
    </div>
  </div>
</template>

<script>
export default {
    name: "Spinner",

    props: {
        loading: {
            type: Boolean,
            default: true,
        },
        size: {
            type: String,
            default: "15px",
        },
        radius: {
            type: String,
            default: "100%",
        },
        moonColor: {
            type: String,
            default: "bg-black"
        },
    },
    data() {
        return {
            spinnerStyle: {
                height: this.size,
                width: this.size,
                borderRadius: this.radius,
            },
        };
    },
    computed: {
        moonSize() {
            return parseFloat(this.size) / 7;
        },
        spinnerMoonStyle() {
            return {
                height: this.moonSize + "px",
                width: this.moonSize + "px",
                borderRadius: this.radius,
            };
        },
        animationStyle2() {
            return {
                top: parseFloat(this.size) / 2 - this.moonSize / 2 + "px",
            };
        },
        animationStyle3() {
            return {
                border: this.moonSize + "px solid",
            };
        },
    },
};
</script>

<style>
.v-spinner .v-moon1 {
    -webkit-animation: v-moonStretchDelay 1s 0s infinite linear;
    animation: v-moonStretchDelay 1s 0s infinite linear;
    -webkit-animation-fill-mode: forwards;
    animation-fill-mode: forwards;
    position: relative;
}

.v-spinner .v-moon2 {
    -webkit-animation: v-moonStretchDelay 1s 0s infinite linear;
    animation: v-moonStretchDelay 1s 0s infinite linear;
    -webkit-animation-fill-mode: forwards;
    animation-fill-mode: forwards;
    opacity: 0.8;
    position: absolute;
}

.v-spinner .v-moon3 {
    opacity: 0.2;
}

@-webkit-keyframes v-moonStretchDelay {
    100% {
        -webkit-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}

@keyframes v-moonStretchDelay {
    100% {
        -webkit-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}
</style>
