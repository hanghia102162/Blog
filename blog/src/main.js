import { createApp } from "vue";
import "./style.css";
import App from "./App.vue";
import { createRouter, createWebHistory } from "vue-router";

//
import HelloWorld from "./components/HelloWorld.vue";
import Contact from "./components/contact.vue";

const routes = [
  { path: "/", component: HelloWorld }, // Trang chủ
  { path: "/contact", component: Contact },
];
const router = createRouter({
  history: createWebHistory(),
  routes,
});
const app = createApp(App);
app.use(router); // Kích hoạt hệ thống đường ray
app.mount("#app");
