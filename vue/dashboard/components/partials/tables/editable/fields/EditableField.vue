<template>
  <td @click="editing = true">
    <component @change="updateCellValue" :is="getField()" :value="value" :editing="editing" :editable="editable"></component>
  </td>
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
    editable: {
      type: Boolean,
      required: false,
      default: true
    },
    value: {},
    type: {
      type: String,
      required: false,
      default: "text"
    }
  },
  data() {
    return {
      editing: false
    }
  },
  methods: {
    getField() {
      switch (this.type) {
        case 'text': return "TextField";
        case 'checkbox': return "CheckboxField";
        default: return "TextField";
      }
    },
    updateCellValue(value) {
      this.editing = false;
      this.$emit('cellUpdated', {value: value});
    }
  }
}
</script>

<style scoped>

</style>
