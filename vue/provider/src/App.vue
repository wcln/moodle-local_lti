<template>
  <div class="rendered-resource">
    <Resource @updated="resizeIframe" v-if="! displayError" :token="token" :return-url="returnUrl"></Resource>
    <Error v-else :code="errorCode" :message="errorMessage"></Error>
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
      return this.errorCode !== "" || this.errorMessage !== "";
    }
  },
  mounted() {
    this.removeIframeBorder();
  }
}
</script>

<style lang="scss">

</style>
