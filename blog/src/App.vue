<script setup>
import HelloWorld from "./components/HelloWorld.vue";
import Header from "./components/header.vue";
import Footer from "./components/footer.vue";
import Contact from "./components/contact.vue";
import Login from "./components/login.vue";
</script>

<template>
  <Header />
  <router-view />

  <Footer />
</template>
