<template>
  <div class="tabs is-boxed">
    <ul>
      <li v-for="(tab, index) in tabs" :class="{'is-active': tab.isActive}">
        <a @click="tabChanged(tab, index)">
          <span class="icon is-small"><i :class="'fas ' + tab.icon" aria-hidden="true"></i></span>
          <span>{{ tab.title }}</span>
        </a>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: "Tabs",
  props: {
    tabs: {
      type: Array,
      default: [],
      required: true
    }
  },
  methods: {
    tabChanged(tab, index) {
      window.console.log(index);
      if (! tab.isActive) {
        this.$emit('tabChanged', index);
      }
    }
  }
}
</script>

<style scoped>

</style>
