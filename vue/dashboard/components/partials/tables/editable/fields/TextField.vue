<template>
  <span>
      <input @keyup.enter="$event.target.blur()" @change="$emit('change', valueModel)" type="text" v-model="valueModel" :disabled="! editable">
  </span>
</template>

<script>
export default {
  name: "TextField",
  props: ['value', 'editing', 'editable'],
  data() {
    return {
      valueModel: null // Avoid changing prop value
    }
  },
  mounted() {
    this.valueModel = this.value;
  }
}
</script>

<style scoped lang="scss">
  input {
    background: none;
    border: none;
    transition: none;
    box-shadow: none;
    padding: 0;

    &:focus {
      background: none;
      border: none;
      box-shadow: none;
    }
  }
</style>
