<template>
  <developer-layout title="Gráficos">
    <template #header>
      <div class="flex items-center justify-between">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Gráficos
        </h2>

        <div class="flex items-center">
          <div>
            <select
              id="location"
              v-model="form.filter"
              name="location"
              class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
            >
              <option
                v-for="(filter, index) in filters"
                :key="index"
                :value="filter"
              >
                {{ filter.title }}
              </option>
            </select>
          </div>

          <div 
            v-if="form.filter"
            class="ml-2"
          >
            <select
              id="option"
              v-model="form.option"
              name="option"
              class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-speedapp-orange-500 focus:border-speedapp-orange-500 sm:text-sm rounded-md"
            >
              <option :value="null">
                Todos
              </option>
              <option
                v-for="(option, index) in options"
                :key="index"
                :value="option.id"
              >
                {{ option[form.filter.field] }}
              </option>
            </select>
          </div>

          <button
            class="ml-2 inline-flex items-center px-4 py-2 bg-speedapp-orange-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-speedapp-orange-300 active:bg-speedapp-orange-900 focus:outline-none transition ease-in-out duration-150"
            @click="update"
          >
            Aplicar
          </button>
        </div>
      </div>
    </template>

    <!-- py-12 -->
    <div>
      <div class="grid grid-cols-1">
        <div class="col-span-1">
          <canvas 
            id="averageDeliveriesByDayChart" 
          />
        </div>
      </div>

      <div class="w-full h-2 bg-gray-600" />

      <div class="mt-6 grid grid-cols-2 gap-6">
        <div
          v-for="(day, dayIndex) in averageDeliveriesByDayByHour"
          :key="dayIndex"
          class="col-span-1"
        >
          <canvas :id="`averageDeliveriesByDayByHour-${dayIndex}`" />
        </div>
      </div>
    </div>
  </developer-layout>
</template>

<script>
import { defineComponent, onMounted, computed } from "vue";
import DeveloperLayout from "@/Layouts/DeveloperLayout.vue";
import { useForm } from "@inertiajs/inertia-vue3";

import { Chart, registerables } from "chart.js";
import ChartDataLabels from "chartjs-plugin-datalabels";
import { Inertia } from "@inertiajs/inertia";

export default defineComponent({
    components: {
        DeveloperLayout,
    },

    props: {
        cities: {
            type: Array,
            default: () => [],
        },
        users: {
            type: Array,
            default: () => [],
        },
        drivers: {
            type: Array,
            default: () => [],
        },
        statuses: {
            type: Object,
            default: () => {},
        },

        averageDeliveriesByDay: {
            type: Array,
            default: () => [],
        },
        averageDeliveriesByHour: {
            type: Array,
            default: () => [],
        },

        averageDeliveriesByDayByHour: {
            type: Array,
            default: () => [],
        },
    },

    setup(props) {

        let averageDeliveriesByDayChartCtx;

        let averageDeliveriesByDayChart;

        let averageDeliveriesByDayByHourCharts = [];

        const filters = [
            {
                key: "cities",
                field: "name",
                title: "Cidades",
            },
            {
                key: "users",
                field: "name",
                title: "Lojas",
            },
            {
                key: "drivers",
                field: "full_name",
                title: "Entregadores",
            },
        ];

        const options = computed(() => {
            if (form.filter == null) return [];

            return props[form.filter.key];
        });

        const form = useForm({
            filter: filters[0],
            option: null,
            // status: ["COMPLETED"],
        });

        const update = async () => {
            // Inertia.replace(route(route().current(), route().params), {
            //     method: 'GET',
            //     data: {
            //         model: form.data().filter.key,
            //         id: form.data().option,
            //     },
            //     replace: false,
            //     preserveState: true,
            //     preserveScroll: true,
            // });

            // return;

            averageDeliveriesByDayChart.data.labels =
                props.averageDeliveriesByDay.map((d) => d.weekday);

            averageDeliveriesByDayChart.data.datasets[0].data =
                props.averageDeliveriesByDay.map((d) => d.average);

            averageDeliveriesByDayChart.update();

            props.averageDeliveriesByDayByHour.forEach((day, i) => {

                averageDeliveriesByDayByHourCharts[i].data.labels = day.hours.map((d) => d.hour);
                averageDeliveriesByDayByHourCharts[i].data.datasets[0].data = day.hours.map((d) => d.average);

                averageDeliveriesByDayByHourCharts[i].update();
             })

            // averageDeliveriesByDayByHourCharts.forEach(chart => {
            //     chart.data.labels = day.hours.map((d) => d.hour);
            //     chart.data.datasets[0].data = day.hours.map((d) => d.average);

            //     chart.update();
            // });
        };

        const initChart = () => {
            Chart.register(...registerables);
            Chart.register(ChartDataLabels);

            averageDeliveriesByDayChartCtx = document
                .getElementById("averageDeliveriesByDayChart")
                .getContext("2d");

            props.averageDeliveriesByDayByHour.forEach((day, i) => {
                const ctx =  document.getElementById(`averageDeliveriesByDayByHour-${i}`).getContext("2d");
                
                const chart = new Chart(
                    ctx,
                    {
                        type: "bar",
                        data: {
                            labels: [],
                            datasets: [
                                {
                                    label: "Média de entrega",
                                    backgroundColor: "rgba(245, 124, 0, 0.5)",
                                    borderColor: "rgba(245, 124, 0, 0.8)",
                                    pointBackgroundColor: "rgba(245, 124, 0, 1.0)",
                                },
                            ],
                        },
                        options: {
                            responsive: true,
                            layout: {
                                padding: 50,
                            },
                            scales: {
                                y: {
                                    title: {
                                        text: "Média",
                                        display: true,
                                    },
                                },
                                x: {
                                    title: {
                                        text: "Horário",
                                        display: true,
                                    },
                                },
                            },
                            plugins: {
                                title: {
                                    display: true,
                                    text: `${day.weekday}`,
                                },
                                datalabels: {
                                    backgroundColor: function (context) {
                                        return context.dataset.pointBackgroundColor;
                                    },
                                    borderRadius: 4,
                                    color: "white",
                                    font: {
                                        weight: "bold",
                                    },
                                    formatter: Math.round,
                                    padding: 6,
                                },
                            },
                        },
                        plugins: [ChartDataLabels],
                    }
                );

                chart.data.labels = day.hours.map((d) => d.hour);
                chart.data.datasets[0].data = day.hours.map((d) => d.average);

                averageDeliveriesByDayByHourCharts.push(chart);
            })

            averageDeliveriesByDayChart = new Chart(
                averageDeliveriesByDayChartCtx,
                {
                    type: "bar",
                    data: {
                        labels: [],
                        datasets: [
                            {
                                label: "Média de entrega",
                                backgroundColor: "rgba(245, 124, 0, 0.5)",
                                borderColor: "rgba(245, 124, 0, 0.8)",
                                pointBackgroundColor: "rgba(245, 124, 0, 1.0)",
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        layout: {
                            padding: 50,
                        },
                        scales: {
                            y: {
                                title: {
                                    text: "Média",
                                    display: true,
                                },
                            },
                            x: {
                                title: {
                                    text: "Dia",
                                    display: true,
                                },
                            },
                        },
                        plugins: {
                            title: {
                                display: true,
                                text: "Média de entregas por dia da semana",
                            },
                            datalabels: {
                                backgroundColor: function (context) {
                                    return context.dataset.pointBackgroundColor;
                                },
                                borderRadius: 5,
                                color: "white",
                                font: {
                                    weight: "bold",
                                },
                                formatter: Math.round,
                                padding: 5,
                            },
                        },
                    },
                    plugins: [ChartDataLabels],
                }
            );

            averageDeliveriesByDayChart.data.labels =
                props.averageDeliveriesByDay.map((d) => d.weekday);
            averageDeliveriesByDayChart.data.datasets[0].data =
                props.averageDeliveriesByDay.map((d) => d.average);
            averageDeliveriesByDayChart.update();
            
            averageDeliveriesByDayByHourCharts.forEach(c => c.update());
        };

        onMounted(initChart);

        return {
            filters,
            options,
            form,
            update,
        };
    },
});
</script>
