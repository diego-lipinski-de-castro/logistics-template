<template>
  <developer-layout title="Dashboard">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dashboard
      </h2>
    </template>

    <div class="py-12">
      <div>
        <h3 class="text-lg leading-6 font-medium text-gray-900">Last 30 days</h3>

        <div class="mt-5 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-2">
          <div v-for="(item, index) in stats" :key="index" class="relative bg-white px-6 py-4 shadow rounded-lg overflow-hidden">
            <div class="flex items-center space-x-4">
              <div class="flex flex-col">
                <p class="text-lg font-medium text-gray-700  truncate">{{ item.label }}</p>
                
                <p :class="[item.changeType === 'increase' ? 'text-green-600' : 'text-red-600', '-ml-1 flex text-sm font-semibold space-x-1']">
                  <ArrowSmUpIcon v-if="item.changeType === 'increase'" class="self-center flex-shrink-0 h-4 w-4 text-green-500" aria-hidiven="true" />
                  <ArrowSmDownIcon v-else class="self-center flex-shrink-0 h-4 w-4 text-red-500" aria-hidden="true" />
                  <span>{{ item.change }}</span>
                  <span class="text-gray-400">&nbsp;{{ item.changeType === 'increase' ? 'more' : 'less' }} than last week</span>
                </p>
              </div>
            </div>

            <div class="flex flex-col">
              <div class="my-4">
                <canvas :id="`chart-${index}`" height="100"></canvas>
              </div>

              <p class="text-2xl font-semibold text-gray-900">
                {{ item.stat }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <div class="mt-12">
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-2">
          
          <div class="col-span-1 -my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <h3 class="text-lg leading-6 font-medium text-gray-900 md:px-6 lg:px-8">Top drivers</h3>

            <div class="mt-5 inline-block min-w-full align-middle md:px-6 lg:px-8 pb-4">
              <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                  <tbody class="divide-y divide-gray-200 bg-white">
                    <tr v-for="(driver, index) in drivers" :key="index">
                      <td class="whitespace-nowrap py-3 pl-4 pr-3 text-sm text-gray-500 sm:pl-6">
                        <div class="flex items-center space-x-2">
                          <ChevronUpIcon v-if="driver.position === 'same'" class="self-center flex-shrink-0 h-4 w-4 text-transparent" aria-hidiven="true" />
                          <ChevronUpIcon v-if="driver.position === 'up'" class="self-center flex-shrink-0 h-4 w-4 text-green-500" aria-hidiven="true" />
                          <ChevronDownIcon v-if="driver.position === 'down'" class="self-center flex-shrink-0 h-4 w-4 text-red-500" aria-hidden="true" />
                          <span>{{ index + 1 }}</span>
                        </div>
                      </td>
                      <td class="whitespace-nowrap px-2 py-3 text-sm font-medium text-gray-900">{{ driver.full_name }}</td>
                      <td class="whitespace-nowrap px-2 py-3 text-sm text-gray-900"><span class="font-semibold">{{ driver.deliveries_count }}</span> entregas</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <div class="col-span-1 -my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <h3 class="text-lg leading-6 font-medium text-gray-900 md:px-6 lg:px-8">Top users</h3>

            <div class="mt-5 inline-block min-w-full align-middle md:px-6 lg:px-8 pb-4">
              <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                <table class="min-w-full divide-y divide-gray-300">
                  <tbody class="divide-y divide-gray-200 bg-white">
                    <tr v-for="(user, index) in users" :key="index">
                      <td class="whitespace-nowrap py-3 pl-4 pr-3 text-sm text-gray-500 sm:pl-6">
                        <div class="flex items-center space-x-2">
                          <ChevronUpIcon v-if="user.position === 'same'" class="self-center flex-shrink-0 h-4 w-4 text-transparent" aria-hidiven="true" />
                          <ChevronUpIcon v-if="user.position === 'up'" class="self-center flex-shrink-0 h-4 w-4 text-green-500" aria-hidiven="true" />
                          <ChevronDownIcon v-if="user.position === 'down'" class="self-center flex-shrink-0 h-4 w-4 text-red-500" aria-hidden="true" />
                          <span>{{ index + 1 }}</span>
                        </div>
                      </td>
                      <td class="whitespace-nowrap px-2 py-3 text-sm font-medium text-gray-900">{{ user.name }}</td>
                      <td class="whitespace-nowrap px-2 py-3 text-sm text-gray-900"><span class="font-semibold">{{ user.deliveries_count }}</span> entregas</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

        </div>
      </div>
      
    </div>
  </developer-layout>
</template>

<script>
    import { defineComponent, onMounted, toRef } from 'vue'
    import DeveloperLayout from '@/Layouts/DeveloperLayout.vue'
    import { ArrowSmDownIcon, ArrowSmUpIcon, ChevronDownIcon, ChevronUpIcon } from '@heroicons/vue/solid'
    import { CurrencyDollarIcon } from '@heroicons/vue/outline'
    import { Chart, registerables } from "chart.js";

    export default defineComponent({
        components: {
          DeveloperLayout,
          CurrencyDollarIcon,
          ArrowSmDownIcon,
          ArrowSmUpIcon,
          ChevronDownIcon,
          ChevronUpIcon,
        },

        props: {
          stats: {
            type: Array,
            default: () => [],
          },
          routes: {
            type: Array,
            default: () => [],
          },
          drivers: {
            type: Array,
            default: () => [],
          },
          users: {
            type: Array,
            default: () => [],
          },
        },

        setup(props) {

          const init = () => {
            Chart.register(...registerables)

            props.stats.forEach((item, index) => {
              const chartCtx = document.getElementById(`chart-${index}`).getContext("2d");

              const gradient = chartCtx.createLinearGradient(0, 0, 0, 200);
              gradient.addColorStop(0, item.changeType === 'increase' ? 'rgba(5, 150, 105, 0.25)' : 'rgba(200, 38, 38, 0.25)');
              // gradient.addColorStop(0.5, 'rgba(255, 255, 255, 1)');
              gradient.addColorStop(1, 'rgba(255, 255, 255, 1)');

              const DATA_COUNT = 5;
              
              const labels = [];
              let datapoints = [];

              for (let i = 0; i < DATA_COUNT; ++i) {
                labels.push(i.toString())
                datapoints.push(Math.random() * (props.stats[0].percent - 0) + 0)
              }

              datapoints.sort((a, b) => a - b)

              const data = {
                labels: labels, // item.line.labels,
                datasets: [
                  {
                    data: datapoints, // item.line.points,
                    fill: true,
                    backgroundColor : gradient,
                    borderColor : item.changeType === 'increase' ? '#059669' : '#C82626',
                    tension: 0.5,
                    pointRadius: 0, 
                  }
                ]
              };

              const chart = new Chart(
                chartCtx,
                {
                  type: 'line',
                  data: data,
                  options: {
                    responsive: true,
                    datasetStrokeWidth : 3,
                    plugins: {
                      title: {
                        display: false,
                      },
                      legend: {
                        display: false,
                      }
                    },
                    scales: {
                      x: {
                        display: false,
                        title: {
                          display: false
                        }
                      },
                      y: {
                        display: false,
                        title: {
                          display: false,
                        },
                      },
                    }
                  },
                }
              );
            })
          }

          onMounted(init)

          return {
            
          }
        }
    })
</script>
