<template>
  <div>
    <Tabs @tabChanged="changeTab" :tabs="tabs"></Tabs>
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
          key: "overview",
          icon: "fa-tachometer-alt",
          isActive: true,
          component: Overview
        },
        {
          title: "Consumers",
          key: "consumers",
          icon: "fa-sitemap",
          isActive: false,
          component: Consumers
        },
        {
          title: "Error log",
          key: "errors",
          icon: "fa-bug",
          isActive: false,
          component: ErrorLog
        },
      ],
    }
  },
  methods: {
    changeTab(index) {
      if (!isNaN(index)) {
        let currentTab = this.tabs.find((tab, tabIndex) => tabIndex === index);
        this.tabs.map(tab => tab.isActive = (currentTab === tab));
        if (this.$route.params.tab !== currentTab.key) {
          this.$router.push({path: '/' + currentTab.key});
        }
      }
    }
  },
  mounted() {
    if (this.$route.params.tab !== undefined) {
      this.changeTab(this.tabs.findIndex(tab => tab.key === this.$route.params.tab));
    }
  }
}
</script>

<style scoped lang="scss">

</style>
