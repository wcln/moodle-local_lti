<template>
  <nav class="navbar is-info" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
      <span class="navbar-item" href="https://bulma.io">
        <img src="../../assets/logo.png" height="28" alt="WCLN logo">
      </span>

      <h1 class="navbar-item">
        {{ title }}
      </h1>

      <a role="button" :class="{'navbar-burger': true, 'is-active': showMenu}" aria-label="menu" aria-expanded="false" data-target="resourceNavbar" @click="showMenu = ! showMenu">
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
        <span aria-hidden="true"></span>
      </a>
    </div>

    <div id="resourceNavbar" :class="{'navbar-menu': true, 'is-active': showMenu}">
      <div class="navbar-end">

        <div class="navbar-item" v-if="hasPages && ! isFirstPage && ! showMenu">
          <a @click="$emit('pageChanged', currentPage - 1)" class="has-text-light">
            <i class="fa fa-angle-left fa-2x"></i>
          </a>
        </div>

        <div class="navbar-item has-dropdown is-hoverable" v-if="hasPages">
          <a class="navbar-link">
            Table of Contents
          </a>

          <div class="navbar-dropdown is-right">
            <a @click="$emit('pageChanged', page.pagenum)"
               :class="{'navbar-item': true, 'is-active': index === currentPage - 1}" v-for="(page, index) in pages"
               :key="index">
              {{ page.name }}
            </a>
            <hr class="navbar-divider">
            <a @click="$emit('print')" class="navbar-item">
              <span class="icon-text">
                <span class="icon">
                  <i class="fas fa-print"></i>
                </span>
                <span>Print</span>
              </span>
            </a>
            <a :href="returnUrl" class="navbar-item">
              <span class="icon-text">
                <span class="icon">
                  <i class="fas fa-sign-out-alt"></i>
                </span>
                <span>Return to course</span>
              </span>
            </a>
          </div>
        </div>

        <div class="navbar-item" v-if="hasPages && ! isLastPage && ! showMenu">
          <a @click="$emit('pageChanged', currentPage + 1)" class="has-text-light">
            <i class="fa fa-angle-right fa-2x"></i>
          </a>
        </div>

        <div class="navbar-item" v-if="! hasPages">
          <a @click="$emit('print')" class="has-text-light">
            <i class="fa fa-print"></i>
          </a>
        </div>

        <div class="navbar-item" v-if="(hasPages && isLastPage) || ! hasPages">
          <a :href="returnUrl" class="has-text-light">
            <i class="fa fa-sign-out-alt"></i>
          </a>
        </div>

      </div>

    </div>

  </nav>
</template>

<script>
export default {
  name: "Navbar",
  props: ['title', 'pages', 'currentPage', 'returnUrl'],
  data() {
    return {
      showMenu: false
    }
  },
  computed: {
    isLastPage() {
      return this.currentPage === this.pages.length;
    },
    isFirstPage() {
      return this.currentPage === 1;
    },
    hasPages() {
      return this.pages !== null && this.pages.length > 0;
    }
  }
}
</script>

<style scoped lang="scss">
@import '~bulma/sass/utilities/all';

.navbar {
  @include desktop {
    border-top-right-radius: .25rem;
    border-top-left-radius: .25rem;
  }
}

.navbar-brand h1 {
  max-width: 230px;

  @include desktop {
    max-width: 800px;;
  }
}

</style>
