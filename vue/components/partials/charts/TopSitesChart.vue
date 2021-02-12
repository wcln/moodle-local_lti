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
import {ajax} from "../../../store";

export default {
  name: 'TopSitesChart',
  components: {PieChart},
  data() {
    return {
      loaded: false,
      chartdata: {},
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
    ajax('local_lti_get_top_consumers', {}).then(sites => {

      ajax('local_lti_get_total_requests_count', []).then(count => {

        // Add an entry for total request count so we can compare top sites to this
        sites.push({name: 'Other sites', access_count: count});

        this.chartdata.labels = sites.map(site => site.name);
        this.chartdata.datasets = [{
          label: 'Top consumer sites',
          backgroundColor: [
            '#8EC63F', '#2d7dd2', '#E84855', '#eba600', '#2d3047', '#ededed'
          ],
          data: sites.map(site => site.access_count)
        }];

        this.loaded = true;
      });
    });
  }
}
</script>

<style scoped lang="scss">

</style>
