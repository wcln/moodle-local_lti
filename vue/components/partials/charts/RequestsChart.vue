<template>
  <div class="requests-chart box">
    <h4 class="title is-4">Requests by month</h4>
    <line-chart
        v-if="loaded"
        :chartdata="chartdata"
        :options="options"
        :styles="styles"
    />
  </div>
</template>

<script>
import LineChart from "./base/LineChart";

export default {
  name: 'RequestsChart',
  components: {LineChart},
  data() {
    return {
      loaded: true,
      chartdata: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July'],
        datasets: [{
          label: 'Requests by month',
          backgroundColor: '#2D7DD2',
          borderColor: '#474647',
          data: [0, 10, 5, 2, 20, 30, 45]
        }]
      },
      options: {
        legend: {
          display: false
        },
        responsive: true,
        maintainAspectRatio: false,
      },
      styles: {
        height: "20rem",
        position: 'relative'
      }
    }
  },
  async mounted() {
    // TODO make an API call and load the chart data
  }
}
</script>

<style scoped lang="scss">

</style>
