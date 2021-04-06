<template>
  <div class="has-text-left">
    <Navbar
        :title="title"
        :pages="pages"
        :current-page="currentPage"
        :return-url="returnUrl"
        :key="navBarKey"
        @pageChanged="changePage"
        @print="print"
    ></Navbar>
    <div class="card-content">
      <transition name="fade">
        <div v-if="resource_content && ! loading" v-html="resource_content"></div>
      </transition>
      <transition name="fade">
        <Loading v-if="loading"></Loading>
      </transition>
    </div>
    <Footer
        v-if="pages !== undefined && pages.length > 0"
        :pages="pages"
        :current-page="currentPage"
    ></Footer>
  </div>
</template>

<script>
import moodleAjax from "@/mixins/moodleAjax";
import Navbar from "@/components/partials/Navbar";
import Loading from "@/components/partials/Loading";
import Footer from "@/components/partials/Footer";

export default {
  name: "Resource",
  components: {Footer, Loading, Navbar},
  props: ['token', 'returnUrl'],
  mixins: [moodleAjax],
  data() {
    return {
      resource_content: null,
      title: "",
      loading: true,
      pages: [],
      currentPage: 1,
      navBarKey: 0,
    }
  },
  methods: {
    loadContent(pagenum) {
      this.moodleAjax('local_lti_get_content', this.token, {pagenum: pagenum}).then(response => {
        this.resource_content = response.raw_content;
        this.title = response.title;
        this.pages = response.pages;
        this.currentPage = pagenum;
        this.navBarKey += 1;
        this.loading = false;
      });
    },
    changePage(pagenum) {
      this.loading = true;
      this.loadContent(pagenum);
    },
    print() {
      window.print();
    }
  },
  mounted() {
    this.loadContent(this.currentPage);
  },
  watch: {
    $route() {
      if (this.$route.query.page !== undefined) {
        this.changePage(this.$route.query.page);
      }
    }
  }
}
</script>

<style scoped lang="scss">
.fade-enter-active, .fade-leave-active {
  transition: opacity .5s;
}

.fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */
{
  opacity: 0;
}

.card-content {
  min-height: 20rem;
}
</style>
