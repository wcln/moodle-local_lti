<template>
  <component :is="getField()" :value="value" :editing="editing"></component>
</template>

<script>
import CheckboxField from "./CheckboxField";
import TextField from "./TextField";

export default {
  name: "EditableField",
  components: {
    CheckboxField, TextField
  },
  props: {
    editing: {
      type: Boolean,
      required: false,
      default: false
    },
    value: {},
    type: {
      type: String,
      required: false,
      default: "text"
    }
  },
  methods: {
    getField() {
      switch (this.type) {
        case 'text': return "TextField";
        case 'checkbox': return "CheckboxField";
        default: return "TextField";
      }
    }
  }
}
</script>

<style scoped>

</style>
