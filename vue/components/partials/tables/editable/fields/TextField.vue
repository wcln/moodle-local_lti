<template>
  <span>
      <input @keyup.enter="$event.target.blur()" @change="$emit('change', value)" type="text" :value="value" :disabled="! editable">
  </span>
</template>

<script>
export default {
  name: "TextField",
  props: ['value', 'editing', 'editable'],
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
