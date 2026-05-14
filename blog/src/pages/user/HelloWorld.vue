<template>
  <div class="justify-center flex flex-col gap-10 items-center mt-10">
    <div
      v-for="(post, index) in posts"
      :key="index"
      class="w-full md:w-[70%] flex flex-col gap-3 justify-center mt-20 items-center"
    >
      <router-link
        :to="`/articleDetails/${post.id}`"
        class="text-3xl w-auto md:w-[50%] text-center font-bold"
        ><h1 class="w-full">
          {{ post.title }}
        </h1></router-link
      >
      <p class="text-gray-600">
        {{ "Tác giả: " + post.author + "-" + post.created_at }}
      </p>
      <p class="text-gray-600">
        {{ "Thể loại: " + post.category_slug }}
      </p>
      <router-link :to="`/articleDetails/${post.id}`">
        <div
          class="w-screen overflow-hidden md:w-[800px] md:h-[350px] h-[250px]"
        >
          <img
            :src="getFirstImage(post.content)"
            alt=""
            class="w-full h-full object-cover rounded-lg hover:scale-110 duration-500 transition-all"
          />
        </div>
      </router-link>
      <p class="leading-relaxed text-justify md:w-[50%] w-[90%] p-3">
        {{
          post.content
            ? post.content.replace(/<[^>]*>/g, "").slice(0, 750) + "..."
            : "No content"
        }}
      </p>
      <div class="flex gap-3">
        <router-link
          :to="`/articleDetails/${post.id}`"
          class="px-3 py-1 bg-white rounded-lg shadow hover:bg-gray-100 transition"
        >
          Read Mode
        </router-link>
        <button
          class="flex items-center gap-1 px-3 py-1 bg-white rounded-lg shadow hover:bg-gray-100 transition"
        >
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
              d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"
            />
          </svg>
          leave a comment
        </button>
      </div>
    </div>
    <div class="mt-10 mb-10 flex gap-2 items-center">
      <button
        class="px-3 py-1 bg-gray-200 rounded"
        :disabled="page === 1"
        @click="page--"
      >
        Prev
      </button>

      <button
        v-for="p in totalPages"
        :key="p"
        @click="page = p"
        :class="[
          'px-3 py-1 rounded',
          page === p ? 'bg-blue-500 text-white' : 'bg-gray-200',
        ]"
      >
        {{ p }}
      </button>

      <button
        class="px-3 py-1 bg-gray-200 rounded"
        :disabled="page === totalPages"
        @click="page++"
      >
        Next
      </button>
    </div>
  </div>
</template>
<script setup>
import axios from "axios";
import { onMounted, ref, watch } from "vue";
import { useRoute } from "vue-router";

const route = useRoute();

// ================= phân trang  =================
const posts = ref([]);

const page = ref(1);
const perPage = ref(5);
const totalPages = ref(1);

// ================= hàm lấy dữ liệu hiển thị =================
const handlePosts = async () => {
  try {
    const category = route.query.category;

    const res = await axios.get("http://localhost/blog/backend/api/posts.php", {
      params: {
        page: page.value,
        per_page: perPage.value,
        category: category || null,
      },
    });

    posts.value = res.data.data;
    totalPages.value = res.data.total_pages || 1;

    console.log("Posts:", posts.value);
  } catch (error) {
    console.log(error);
  }
};

// ================= ảnh =================
const defaultImg = "/img/2.png";

const getFirstImage = (content) => {
  if (!content) return defaultImg;

  const match = content.match(/<img[^>]+src="([^">]+)"/);
  return match ? match[1] : defaultImg;
};

onMounted(() => {
  handlePosts();
});

// ================= WATCH CATEGORY =================
watch(
  () => route.query.category,
  () => {
    page.value = 1; // reset page khi đổi category
    handlePosts();
  },
);
// theo dõi sự kiện khi category thay đổi thì watch chạy
watch(page, () => {
  handlePosts();
});
</script>
