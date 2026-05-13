<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-100 to-gray-200 py-12">
    <div class="w-[75%] mx-auto bg-white rounded-2xl shadow-lg p-8">
      <!-- HEADER -->
      <div
        class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6"
      >
        <!-- LEFT -->
        <div>
          <h2 class="text-xl md:text-2xl font-semibold text-gray-800">
            Quản lý bài viết
          </h2>
          <p class="text-sm text-gray-400 mt-1">
            Quản lý và chỉnh sửa bài viết của bạn
          </p>
        </div>

        <!-- RIGHT -->
        <div class="flex flex-wrap items-center gap-3">
          <router-link
            to="/"
            class="text-gray-500 hover:text-blue-500 transition text-sm"
          >
            Trang chủ
          </router-link>

          <router-link
            to="/createPost"
            class="bg-gradient-to-r from-green-400 to-green-500 text-white px-4 py-2 rounded-lg shadow hover:shadow-md hover:scale-105 transition-all text-sm whitespace-nowrap"
          >
            + Soạn bài mới
          </router-link>
        </div>
      </div>

      <!-- TABLE -->
      <div
        class="hidden md:block overflow-hidden rounded-xl border border-gray-100"
      >
        <table class="w-full text-sm">
          <thead>
            <tr
              class="bg-gray-50 text-gray-500 uppercase text-xs tracking-wider"
            >
              <th class="p-4 text-left">Tên bài viết</th>
              <th class="p-4 text-left">Chuyên mục</th>
              <th class="p-4 text-left">Ngày đăng</th>
              <th class="p-4 text-right">Thao tác</th>
            </tr>
          </thead>

          <tbody class="text-gray-700">
            <tr
              v-for="(post, index) in posts"
              :key="index"
              class="border-t hover:bg-gray-50 transition duration-200"
            >
              <td class="p-4 font-medium">
                {{ post.title.replace(/<[^>]*>/g, "").slice(0, 45) + "..." }}
              </td>
              <td class="p-4">
                <span
                  class="bg-blue-50 text-blue-600 text-xs px-3 py-1 rounded-full font-medium"
                >
                  {{ post.category_name }}
                </span>
              </td>

              <td class="p-4 text-gray-400">
                {{ post.created_at }}
              </td>

              <td class="p-4 text-right space-x-4">
                <router-link
                  :to="`/edit/${post.id}`"
                  class="text-blue-500 hover:text-blue-700 font-medium"
                >
                  Sửa
                </router-link>
                <button
                  @click="handelPostDelete(post.id)"
                  class="text-red-500 hover:text-red-700 font-medium transition"
                >
                  Xóa
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- mobile -->
      <div class="md:hidden flex flex-col space-y-4">
        <div v-for="(post, index) in posts" :key="index">
          <!-- ITEM -->
          <div class="border rounded-xl p-4 shadow-sm">
            <h3 class="font-semibold text-gray-800">{{ post.title }}</h3>

            <div class="flex justify-between items-center mt-2">
              <span>{{ post.category_name }}</span>
              <td>{{ post.published_at }}</td>
            </div>

            <div class="flex justify-end gap-4 mt-3 text-sm">
              <router-link :to="`/edit/${post.id}`" class="text-blue-500"
                >Sửa</router-link
              >
              <button class="text-red-500" @click="handelPostDelete(post.id)">
                Xóa
              </button>
            </div>
          </div>
        </div>
      </div>
      <!-- phan trang -->
      <div class="flex justify-center items-center gap-3 mt-3">
        <button class="cursor-pointer" @click="handeltru()">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="size-6"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M6.75 15.75 3 12m0 0 3.75-3.75M3 12h18"
            />
          </svg>
        </button>
        <span>{{ trang }}</span>
        <button class="cursor-pointer" @click="handelcong">
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="size-6"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M17.25 8.25 21 12m0 0-3.75 3.75M21 12H3"
            />
          </svg>
        </button>
      </div>
    </div>
    <!--  -->
  </div>
</template>
<script setup>
import axios from "axios";
import { ref, onMounted, watch } from "vue";

const posts = ref([]);

// ================= PHÂN TRANG =================
const trang = ref(1);
const perPage = 10;
const totalPages = ref(1);
const totalPosts = ref(0);

const handelPost = async () => {
  try {
    const token = localStorage.getItem("token");

    const res = await axios.get(
      `http://127.0.0.1/blog/backend/api/posts.php?mine=1&page=${trang.value}&per_page=${perPage}`,
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      },
    );
    posts.value = res.data.data;

    totalPages.value = res.data.total_pages;
    totalPosts.value = res.data.total;

    console.log(res.data);
  } catch (error) {
    console.log(error.response?.data || error);
  }
};

onMounted(() => {
  handelPost();
});

watch(trang, () => {
  handelPost();
});
// =========================================================

// ================= XÓA BÀI VIẾT =================
const handelPostDelete = async (id) => {
  try {
    const token = localStorage.getItem("token");

    const res = await axios.delete(
      `http://localhost/blog/backend/api/posts.php?id=${id}`,
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      },
    );

    if (res.data.success) {
      if (posts.value.length === 1 && trang.value > 1) {
        trang.value--;
      } else {
        handelPost();
      }
    }
  } catch (error) {
    console.log(error.response?.data || error);
  }
};

// ================= CHUYỂN TRANG =================
const handelcong = () => {
  if (trang.value < totalPages.value) {
    trang.value++;
  }
};

const handeltru = () => {
  if (trang.value > 1) {
    trang.value--;
  }
};
// ===============================================
</script>
