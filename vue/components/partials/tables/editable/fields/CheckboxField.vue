<template>
  <input @change="$emit('change', value)" type="checkbox" :checked="value">
</template>

<script>
export default {
  name: "CheckboxField",
  props: ['value', 'editing'],
}
</script>

<style scoped>

</style>
