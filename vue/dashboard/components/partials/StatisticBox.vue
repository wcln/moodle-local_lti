<template>
  <div class="box has-background-primary">
    <article class="media">
      <div v-if="icon !== false && icon.length > 0" class="media-left">
            <i :class="'has-text-white fas fa-3x ' + icon"></i>
      </div>
      <div class="media-content">
        <p class="is-size-4 has-text-white">
          <strong class="has-text-white">{{number}}</strong> {{text}}
        </p>
      </div>
    </article>
  </div>
</template>

<script>
export default {
  name: "StatisticBox",
  props: {
    icon: {
      type: String,
      default: false
    },
    text: {
      type: String
    },
    number: {
      type: String
    }
  }
}
</script>

<style scoped lang="scss">

</style>
