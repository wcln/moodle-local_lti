<template>
  <div class="has-text-left">
    <Navbar
      :title="title"
      :pages="pages"
      :current-page="currentPage"
      :return-url="returnUrl"
      @pageChanged="changePage"
    ></Navbar>
    <div class="resource-content mt-5">
      <transition name="fade">
        <div v-if="resource_content && ! loading" v-html="resource_content"></div>
      </transition>
      <transition name="fade">
        <Loading v-if="loading"></Loading>
      </transition>
    </div>
  </div>
</template>

<script>
import moodleAjax from "@/mixins/moodleAjax";
import Vue from "vue";
import Navbar from "@/components/partials/Navbar";
import Loading from "@/components/partials/Loading";

export default {
  name: "Resource",
  components: {Loading, Navbar},
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

<style scoped lang="scss">
.fade-enter-active, .fade-leave-active {
  transition: opacity .5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
  opacity: 0;
}
</style>
