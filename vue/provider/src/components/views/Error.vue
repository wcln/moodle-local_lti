<template>
  <div>
    <h2>Error view</h2>
    <p>Code: {{code}}</p>
    <p>Message: {{message}}</p>
  </div>
</template>

<script>
export default {
  name: "Error",
  props: ['code', 'message']
}
</script>

<style scoped>

</style>
