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
          <td>{{ site.accessCount }}</td>
        </tr>
        </tbody>
      </table>
    </div>
  </section>
</template>

<script>
export default {
  name: "TopSitesTable",
  data() {
    return {
      sites: [
        {name: "Southeast Kootenay", accessCount: 7392},
        {name: "Rocky Mountain", accessCount: 3389},
        {name: "Burnaby", accessCount: 3091},
        {name: "Central Okanagan", accessCount: 1066},
        {name: "Vernon", accessCount: 739},
      ]
    }
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
