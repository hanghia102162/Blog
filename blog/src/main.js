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
      { path: "articleDetails/:id", component: ArticleDetails },
      { path: "about", component: About },
      { path: "managerPost", component: ManagePost },
      { path: "edit/:id", component: Edit },
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
// ============================================
router.beforeEach((to, from, next) => {
  const user = JSON.parse(localStorage.getItem("user"));

  const role = user?.role;

  // ===== USER =====
  // user không được vào các trang này
  const userBlockRoutes = ["/managerPost", "/createPost"];

  const isEditPage = to.path.startsWith("/edit/");

  if (role === "reader" && (userBlockRoutes.includes(to.path) || isEditPage)) {
    alert("ko du tham quyen");
    next(from.fullPath || "/");
    return;
  }

  // ===== AUTHOR =====
  // author không được vào admin
  if (
    (role === "author" || role === "reader") &&
    to.path.startsWith("/admin")
  ) {
    alert("ko du tham quyen");
    next(from.fullPath || "/");
    return;
  }

  if (
    (role === "admin" || role === "author") &&
    to.path.startsWith("/contact")
  ) {
    alert("bạn đã là " + role);
    next(from.fullPath || "/");
    return;
  }

  // ===== CHƯA LOGIN =====
  if (!user && (userBlockRoutes.includes(to.path) || isEditPage)) {
    next("/login");
    return;
  }

  next();
});
// ===================================
const app = createApp(App);
app.use(router);
app.mount("#app");
