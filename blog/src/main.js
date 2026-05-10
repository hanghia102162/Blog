import { compile, createApp } from "vue";
import "./style.css";
import App from "./App.vue";
import { createRouter, createWebHistory } from "vue-router";
import { defineCustomElements } from "ionicons/loader";

defineCustomElements(window);
//
import UserLayout from "./layouts/UserLayout.vue";
import AdminLayout from "./layouts/AdminLayout.vue";
//
import HelloWorld from "./pages/user/HelloWorld.vue";
import Contact from "./pages/user/contact.vue";
import Login from "./pages/auth/login.vue";
import Register from "./pages/auth/register.vue";
import Reissue from "./pages/auth/reissue.vue";
import Gmail from "./pages/auth/gmail.vue";
import IT from "./pages/user/IT.vue";
import ArticleDetails from "./pages/user/articleDetails.vue";
import About from "./pages/user/about.vue";
import ManagePost from "./pages/user/managerPost.vue";
import Edit from "./pages/user/edit.vue";
import CreatePost from "./pages/user/createPost.vue";
import PersonalInformation from "./pages/user/PersonalInformation.vue";
const routes = [
  {
    path: "/",
    component: UserLayout,
    children: [
      { path: "", component: HelloWorld },
      { path: "contact", component: Contact },
      { path: "IT", component: IT },
      { path: "articleDetails", component: ArticleDetails },
      { path: "about", component: About },
      { path: "managerPost", component: ManagePost },
      { path: "edit", component: Edit },
      { path: "createPost", component: CreatePost },
      { path: "PersonalInformation", component: PersonalInformation },
    ],
  },
  {
    path: "/admin",
    component: AdminLayout,
    children: [],
  },

  { path: "/login", component: Login },
  { path: "/register", component: Register },
  { path: "/reissue", component: Reissue },
  { path: "/gmail", component: Gmail },
];
const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() {
    return { top: 0 }; // luôn về đầu trang
  },
});

const app = createApp(App);
app.use(router);
app.mount("#app");
