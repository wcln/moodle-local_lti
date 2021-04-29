<template>
  <div class="card" id="rendered-resource">
    <Resource @error="handleError" @updated="resizeIframe" v-if="! displayError" :token="token" :return-url="returnUrl"></Resource>
    <Error v-else :code="error.code" :message="error.message" :return-url="returnUrl"></Error>
  </div>
</template>

<script>

import Resource from "@/components/views/Resource";
import Error from "@/components/views/Error";
import {iframeMessageSender} from "@/mixins/iframeMessageSender";

export default {
  name: 'App',
  components: {Error, Resource},
  mixins: [iframeMessageSender],
  props: ['token', 'returnUrl', 'errorCode', 'errorMessage'],
  computed: {
    displayError() {
      return this.error.code !== "" || this.error.message !== "";
    }
  },
  data() {
    return {
      error: {
        code: null,
        message: null
      }
    }
  },
  methods: {
    handleError(error) {
      this.error = error;
    }
  },
  mounted() {
    this.error.code = this.errorCode;
    this.error.message = this.errorMessage;
    this.removeIframeBorder();
  }
}
</script>

<style lang="scss">
@import '~bulma/sass/utilities/all';

#rendered-resource {
  @include desktop {
    margin: .5rem;
  }
}
</style>
