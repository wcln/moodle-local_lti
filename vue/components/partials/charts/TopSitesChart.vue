<template>
  <div class="box">
    <pie-chart
        v-if="loaded"
        :chartdata="chartdata"
        :options="options"
        :styles="styles"
    />
  </div>
</template>

<script>
import PieChart from "./base/PieChart";

export default {
  name: 'TopSitesChart',
  components: {PieChart},
  data() {
    return {
      loaded: true,
      chartdata: {
        labels: ['Southeast Kootenay', 'Rocky Mountain', 'Burnaby', 'Central Okanagan', 'Vernon', 'Other sites'],
        datasets: [{
          label: 'Top consumer sites',
          data: [7392, 3389, 3091, 1066, 739, 5004],
          backgroundColor: [
            '#8EC63F', '#2d7dd2', '#E84855', '#eba600', '#2d3047'
          ]
        }]
      },
      options: {
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
