<template>
  <span>
      <input v-if="editing" type="text" value="value">
      <template v-else>{{value}}</template>
  </span>
</template>

<script>
import EditableField from "./EditableField";

export default {
  name: "TextField",
  props: ['value', 'editing']
}
</script>

<style scoped>

</style>
