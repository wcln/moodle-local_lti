<template>
  <div>
    <div class="table-container">

      <a @click="expanded = ! expanded" class="button is-info" id="expand">
            <span class="icon is-small">
              <i :class="'fas ' + (expanded ? 'fa-compress-alt' : 'fa-expand-alt')"></i>
            </span>
        <span>{{ expanded ? "Collapse" : "Expand" }}</span>
      </a>

      <table class="table is-hoverable">
        <thead>
        <tr>
          <th v-for="heading in tableHeadings">{{ heading }}</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="row in rows">
          <td v-for="column in row">
            <EditableField
              :value="column.value"
              :type="column.type"
              :editing="false"
            >
            </EditableField>
          </td>
        </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination bar -->
    <Pagination
        v-if="pagination"
        :itemsTotal="rows.length"
        :itemsPerPage="itemsPerPage"
        :currentPage="currentPage"
        :buttonsMax="5"
        :url="paginationUrl"
    ></Pagination>
  </div>
</template>

<script>
import Pagination from "vue-bulma-paginate";
import EditableField from "./fields/EditableField";

export default {
  name: "EditableTable",
  components: {
    EditableField,
    Pagination
  },
  computed: {
    tableHeadings() {
      if (this.expanded) {
        return this.headings.concat(this.expandedHeadings);
      }
      return this.headings;
    }
  },
  props: {
    headings: {
      type: Array,
      required: true
    },
    expandedHeadings: Array,
    rows: Array,
    pagination: {
      type: Boolean,
      required: false,
      default: false
    },
    itemsPerPage: Number,
    currentPage: Number,
    paginationUrl: String,
    expanded: {
      type: Boolean,
      required: false,
      default: false
    }
  }
}
</script>

<style scoped lang="scss">
  #expand {
    float: right;
    height: 2rem;
  }
</style>
