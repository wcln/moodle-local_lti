<template>
  <div class="box">
    <h4 class="title is-4">Most requested resources</h4>
    <table class="table is-hoverable">
      <thead>
      <tr>
        <th>Resource</th>
        <th>Course</th>
        <th>Total access count</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="resource in resources">
        <td><a :href="resource.url">{{ resource.name }}</a></td>
        <td><a :href="resource.course_url">{{ resource.course }}</a></td>
        <td>{{ resource.access_count }}</td>
      </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
import {ajax} from "../../../store";
import numberWithCommas from "../../../functions/numberWithCommas";

export default {
  name: "TopResourcesTable",
  data() {
    return {
      resources: []
    }
  },
  mounted() {
    ajax('local_lti_get_top_resources', {}).then(resources => {
      this.resources = resources.map(resource => {
        resource.access_count = numberWithCommas(resource.access_count);
        return resource;
      });
    });
  }
}
</script>

<style scoped lang="scss">

</style>
