<template>
  <div class="loader"></div>
</template>

<script>
export default {
  name: "Loading"
}
</script>

<style scoped lang="scss">
.loader {
  border: 4px solid #f3f3f3;
  border-top: 4px solid #3498db;
  border-radius: 50%;
  width: 60px;
  height: 60px;
  animation: spin 1s linear infinite;

  margin: 5rem auto auto;
  position: absolute;
  right: 0;
  left: 0;
  top: 0;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>
