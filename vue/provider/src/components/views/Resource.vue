<template>
  <div class="has-text-left">
    <Navbar
      :title="title"
      :pages="pages"
      :current-page="currentPage"
      :return-url="returnUrl"
      @pageChanged="changePage"
    ></Navbar>
    <a :href="returnUrl">Return to course</a>
    <ul>
      <li v-for="(page, index) in pages" :key="index">
        <a @click="changePage(page.pagenum)">{{ page.name }}</a>
      </li>
    </ul>
    <div v-if="resource_content && ! loading" v-html="resource_content"></div>
    <div v-if="loading">
      <p>Loading...</p>
    </div>
  </div>
</template>

<script>
import moodleAjax from "@/mixins/moodleAjax";
import Vue from "vue";
import Navbar from "@/components/partials/Navbar";

export default {
  name: "Resource",
  components: {Navbar},
  props: ['token', 'returnUrl'],
  mixins: [moodleAjax],
  data() {
    return {
      resource_content: null,
      title: "",
      loading: true,
      pages: [],
      currentPage: 1
    }
  },
  methods: {
    loadContent() {
      this.moodleAjax('local_lti_get_content', this.token, {pagenum: this.currentPage}).then(response => {
        this.resource_content = response.raw_content;
        this.title = response.title;
        this.pages = response.pages;
        this.loading = false;

        Vue.nextTick(() => {
          this.$emit('updated');
        });
      });
    },
    changePage(pagenum) {
      this.loading = true;
      this.currentPage = pagenum;
      this.loadContent();
    }
  },
  mounted() {
    this.loadContent();
  }
}
</script>

<style scoped>

</style>
