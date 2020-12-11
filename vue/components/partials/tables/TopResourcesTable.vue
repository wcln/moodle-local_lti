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
        <td>{{ resource.name }}</td>
        <td>{{ resource.course }}</td>
        <td>{{ resource.accessCount }}</td>
      </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  name: "TopResourcesTable",
  data() {
    return {
      resources: [
        {name: "Unit 1 Learning Guide", course: "Computer Science 11", accessCount: 401},
        {name: "2.2 Velocity", course: "Physics 11", accessCount: 323},
        {name: "Pythagorean Theorem", course: "Math 8", accessCount: 321},
        {name: "Gravity 1.2", course: "Physics 11", accessCount: 220},
        {name: "Gravity 1.2", course: "Physics 11", accessCount: 220},
        {name: "Gravity 1.2", course: "Physics 11", accessCount: 220},
        {name: "Gravity 1.2", course: "Physics 11", accessCount: 220},
        {name: "Gravity 1.2", course: "Physics 11", accessCount: 220},
        {name: "Gravity 1.2", course: "Physics 11", accessCount: 220},
      ]
    }
  }
}
</script>

<style scoped lang="scss">

</style>
