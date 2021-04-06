<template>
  <footer class="card-footer">
    <Pagination
        class="lti-pagination"
        :itemsTotal="pages.length"
        :itemsPerPage="1"
        :currentPage="currentPage"
        :buttonsMax="5"
        url="app"
    ></Pagination>
  </footer>
</template>

<script>
import Pagination from 'vue-bulma-paginate';

export default {
  name: "Footer",
  components: {Pagination},
  props: ['pages', 'currentPage']
}
</script>

<style scoped lang="scss">
  .card-footer {
    border: none;
    padding: 1rem;
    justify-content: flex-end;

    .lti-pagination {
      float: right;
    }
  }
</style>
