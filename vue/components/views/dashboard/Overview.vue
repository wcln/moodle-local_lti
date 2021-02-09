<template>
  <div class="overview-tab">

    <!-- Statistic boxes -->
    <div class="columns is-centered">
      <div class="column" v-for="statBox in statisticBoxes">
        <StatisticBox class="statbox" :icon="statBox.icon" :number="statBox.number" :text="statBox.text">
          <p class="is-size-4 has-text-white" v-html="statBox.text"></p>
        </StatisticBox>
      </div>
    </div>

    <!-- Requests by month line chart -->
    <RequestsChart></RequestsChart>

    <!-- Top sites by month -->
    <div class="columns">
      <div class="column">
        <TopSitesTable></TopSitesTable>
      </div>
      <div class="column">
        <TopSitesChart></TopSitesChart>
      </div>
    </div>

    <!-- Most requested resources -->
    <TopResourcesTable></TopResourcesTable>
  </div>
</template>

<script>
import StatisticBox from "../../partials/StatisticBox";
import RequestsChart from "../../partials/charts/RequestsChart";
import TopSitesTable from "../../partials/tables/TopSitesTable";
import TopSitesChart from "../../partials/charts/TopSitesChart";
import TopResourcesTable from "../../partials/tables/TopResourcesTable";
import {ajax} from "../../../store";

export default {
  name: "Overview",
  components: {TopResourcesTable, TopSitesChart, TopSitesTable, RequestsChart, StatisticBox},
  data() {
    return {
      statisticBoxes: [], // To be loaded in mounted() function below
    }
  },
  methods: {
      numberWithCommas(x) {
        return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
      }
  },
  mounted() {

    ajax('local_lti_get_total_requests_count', []).then(count => {
      this.statisticBoxes.push({
        number: this.numberWithCommas(count),
        text: "requests",
        icon: "fa-exchange-alt"
      });
    });

    ajax('local_lti_get_total_consumers_count', []).then(count => {
      this.statisticBoxes.push({
        number: this.numberWithCommas(count),
        text: "active consumer sites",
        icon: "fa-sitemap"
      });
    });

    ajax('local_lti_get_total_resources_count', []).then(count => {
      this.statisticBoxes.push({
        number: this.numberWithCommas(count),
        text: "resources requested",
        icon: "fa-book"
      });
    });

    ajax('local_lti_get_errors_count', []).then(count => {
      this.statisticBoxes.push({
        number: this.numberWithCommas(count),
        text: "errors in last 24 hours",
        icon: "fa-bug"
      });
    });

  }
}
</script>

<style scoped lang="scss">

</style>
