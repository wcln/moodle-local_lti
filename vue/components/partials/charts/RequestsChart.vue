<template>
  <div class="requests-chart box">
    <h4 class="title is-4">Requests by month</h4>
    <transition name="fade">

      <line-chart
          v-if="loaded"
          :chartdata="chartdata"
          :options="options"
          :styles="styles"
      />
    </transition>
  </div>
</template>

<script>
import LineChart from "./base/LineChart";
import {ajax} from "../../../store";

export default {
  name: 'RequestsChart',
  components: {LineChart},
  data() {
    return {
      loaded: true,
      chartdata: {}, // To be loaded in mounted()
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
    this.loaded = false;

    ajax('local_lti_get_requests_by_month', []).then(response => {
      this.chartdata.labels = response.map(log => {
        let date = new Date(log.year, log.month - 1); // Need to subtract 1 here because months start at 0 in JS
        return date.toLocaleString('default', {month: 'long'});
      }).reverse();
      this.chartdata.datasets = [{
        label: 'Requests by month',
        backgroundColor: '#2D7DD2',
        borderColor: '#474647',
        pointBackgroundColor: "#eba600",
        pointBorderColor: "#474647",
        hoverBackgroundColor: "#8ec63f",
        data: response.map(log => log.access_count).reverse()
      }];

      this.loaded = true;
    });
  }
}
</script>

<style scoped lang="scss">
.fade-enter-active, .fade-leave-active {
  transition: opacity .5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
</style>
