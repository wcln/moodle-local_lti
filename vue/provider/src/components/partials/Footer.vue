<template>
  <footer>
    <Pagination
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
  footer {
    padding: 1rem;
  }
</style>
