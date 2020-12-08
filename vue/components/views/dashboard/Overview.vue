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

export default {
  name: "Overview",
  components: {TopResourcesTable, TopSitesChart, TopSitesTable, RequestsChart, StatisticBox},
  data() {
    return {
      statisticBoxes: [
        {
          number: "1.25 million",
          text: "requests",
          icon: "fa-exchange-alt"
        },
        {
          number: "768",
          text: "resources requested",
          icon: "fa-book"
        },
        {
          number: "40",
          text: "active consumer sites",
          icon: "fa-sitemap"
        },
        {
          number: "0",
          text: "errors in last 24 hours",
          icon: "fa-bug"
        },
      ],
      chartdata: {
        labels: ['January', 'February'],
        datasets: [
          {
            label: 'Data One',
            backgroundColor: '#f87979',
            data: [40, 20]
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false
      }
    }
  }
}
</script>

<style scoped lang="scss">

</style>
