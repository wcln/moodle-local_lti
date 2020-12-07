<template>
  <div class="overview-tab">
    <div class="columns is-centered">
      <div class="column" v-for="statBox in statisticBoxes">
        <StatisticBox class="statbox" :icon="statBox.icon" :number="statBox.number" :text="statBox.text">
          <p class="is-size-4 has-text-white" v-html="statBox.text"></p>
        </StatisticBox>
      </div>
    </div>
  </div>
</template>

<script>
import StatisticBox from "../../partials/StatisticBox";

export default {
  name: "Overview",
  components: {StatisticBox},
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
      ]
    }
  }
}
</script>

<style scoped lang="scss">

</style>
