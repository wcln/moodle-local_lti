<template>
  <div>
    <Resource v-if="! displayError" :token="token"></Resource>
    <Error v-else :code="errorCode" :message="errorMessage"></Error>
  </div>
</template>

<script>

import Resource from "@/components/views/Resource";
import Error from "@/components/views/Error";
export default {
  name: 'App',
  components: {Error, Resource},
  props: ['token', 'errorCode', 'errorMessage'],
  computed: {
    displayError() {
      return this.errorCode !== "" || this.errorMessage !== "";
    }
  },
}
</script>

<style lang="scss">
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>
