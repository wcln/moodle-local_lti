<template>
  <div>
    <Tabs @tabChanged="tabChanged" :tabs="tabs"></Tabs>
    <component v-bind:is="tabContent"/>
  </div>
</template>

<script>
import Tabs from "../partials/Tabs";
import Overview from "./dashboard/Overview";
import Consumers from "./dashboard/Consumers";
import ErrorLog from "./dashboard/ErrorLog";

export default {
  name: "Dashboard",
  components: {Tabs},
  computed: {
    tabContent() {
      return this.tabs.find(tab => tab.isActive).component;
    }
  },
  data() {
    return {
      tabs: [
        {
          title: "Overview",
          icon: "fa-tachometer-alt",
          isActive: true,
          component: Overview
        },
        {
          title: "Consumers",
          icon: "fa-sitemap",
          isActive: false,
          component: Consumers
        },
        {
          title: "Error log",
          icon: "fa-bug",
          isActive: false,
          component: ErrorLog
        },
      ],
    }
  },
  methods: {
    tabChanged(index) {
      if (!isNaN(index)) {
        this.tabs.map((tab, tabIndex) => {
          tab.isActive = tabIndex === index;
        });
      }
    }
  }
}
</script>

<style scoped lang="scss">

</style>
