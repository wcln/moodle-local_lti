<template>
  <div>
    <Navbar
      title="Error"
      :return-url="returnUrl"
      :pages="[]"
      :error="true"
    ></Navbar>
    <div class="columns is-centered">
      <div class="column is-half-desktop">
        <article class="message is-danger mt-3">
          <div class="message-header">
            <span class="icon-text">
              <span class="icon">
                <i class="fas fa-exclamation-triangle"></i>
              </span>
              <span>An error occurred</span>
            </span>
          </div>
          <div class="message-body">
            <p class="mb-2">{{message}}<p>
            <p><i>Contact a course administrator, teacher or wcln.ca to fix this issue.</i></p>
            <p v-if="code !== null"><b>Error code:</b> {{code}}</p>
            <p v-else><b>Unknown error code</b></p>
          </div>
        </article>
      </div>
    </div>
  </div>
</template>

<script>
import Navbar from "@/components/partials/Navbar";
export default {
  name: "Error",
  components: {Navbar},
  props: ['code', 'message', 'returnUrl']
}
</script>

<style scoped lang="scss">
@import '~bulma/sass/utilities/all';

.message {

  margin-left: 1rem;
  margin-right: 1rem;

  @include desktop {
    margin: 0;
  }
}
</style>
