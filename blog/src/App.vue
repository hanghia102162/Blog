<script setup>
import Header from "./components/header.vue";
import Footer from "./components/footer.vue";
import { useRoute } from "vue-router";
import { computed } from "vue";
const route = useRoute();
const isNotLoginPage = computed(() => route.path !== "/login");
</script>

<template>
  <Header v-if="isNotLoginPage" />
  <router-view />

  <Footer v-if="isNotLoginPage" />
</template>
