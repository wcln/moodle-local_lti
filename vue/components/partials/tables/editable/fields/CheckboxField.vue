<template>
  <input type="checkbox" :checked="value">
</template>

<script>
export default {
  name: "CheckboxField",
  props: ['value', 'editing'],
}
</script>

<style scoped>

</style>
