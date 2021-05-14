<template>
  <section>
    <div class="box">
      <h4 class="title is-4">Top consumer sites</h4>
      <table class="table is-hoverable">
        <thead>
        <tr>
          <th>Site</th>
          <th>Total access count</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="site in sites">
          <td>{{ site.name }}</td>
          <td>{{ site.access_count }}</td>
        </tr>
        </tbody>
      </table>
    </div>
  </section>
</template>

<script>
import {ajax} from "../../../store";
import numberWithCommas from "../../../functions/numberWithCommas";

export default {
  name: "TopSitesTable",
  data() {
    return {
      sites: []
    }
  },
  mounted() {
    ajax('local_lti_get_top_consumers', {}).then(sites => {
      if (Array.isArray(sites)) {
        this.sites = sites.map(site => {
          site.access_count = numberWithCommas(site.access_count);
          return site;
        });
      }
    });
  }
}
</script>

<style scoped lang="scss">

#lti-dashboard-app {

  section {
    height: 100%;
    display: flex;
  }

  .box {
    width: 100%;
  }
}

</style>
