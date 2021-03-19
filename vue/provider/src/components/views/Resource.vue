<template>
  <div>
    <h2>Resource view</h2>
    <div v-if="resource_content && ! loading" v-html="resource_content"></div>
    <div v-if="loading">
      <p>Loading...</p>
    </div>
  </div>
</template>

<script>
import moodleAjax from "@/mixins/moodleAjax";

export default {
  name: "Resource",
  props: ['token'],
  mixins: [moodleAjax],
  data() {
    return {
      resource_content: null,
      loading: true,
      pages: []
    }
  },
  methods: {
    loadContent() {
      console.log('Loading content...');
      this.moodleAjax('local_lti_get_content', this.token, {}).then(response => {

        this.resource_content = response.raw_content;
        // TODO set this.pages
        this.loading = false;
      });
    }
  },
  mounted() {
    this.loadContent();
  }
}
</script>

<style scoped>

</style>
