<template>
  <input @change="$emit('change', valueModel | 0)" type="checkbox" v-model="valueModel">
</template>

<script>
export default {
  name: "CheckboxField",
  props: ['value', 'editing'],
  data() {
    return {
      valueModel: null
    }
  },
  mounted() {
    this.valueModel = this.value | 0;
  }
}
</script>

<style scoped>

</style>
