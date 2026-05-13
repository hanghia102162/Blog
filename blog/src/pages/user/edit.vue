<template>
  <div class="min-h-screen bg-gray-100 flex items-center justify-center">
    <div class="w-full lg:w-[90%] xl:w-[50%] bg-white rounded-xl shadow-md p-6">
      <nav class="flex gap-3 items-center">
        <ion-icon name="pencil-outline"></ion-icon>
        <h2 class="font-medium">Sửa bài viết</h2>
      </nav>
      <form
        @submit.prevent="handelpostPosts()"
        class="w-full flex flex-col gap-3 mt-3"
      >
        <input
          type="text"
          v-model="title"
          placeholder="Tên bài viết"
          class="border p-2 border-gray-400 outline-none focus:ring-2 focus:ring-purple-400"
        />
        <select
          v-model="category_id"
          class="border p-2 border-gray-400 outline-none"
        >
          <option :value="1">Công nghệ thông tin</option>
          <option :value="2">Y tế & Sức khỏe</option>
          <option :value="3">Thể thao</option>
          <option :value="4">Kỹ năng mềm</option>
          <option :value="5">Giáo dục & Đào tạo</option>
          <option :value="6">Văn hóa - Nghệ thuật</option>
        </select>
        <div class="h-[500px] mb-14">
          <QuillEditor
            v-model:content="content"
            contentType="html"
            theme="snow"
            toolbar="full"
            class="bg-white h-full"
          />
        </div>
        <button
          class="bg-green-600 h-[45px] font-medium text-white cursor-pointer"
        >
          Lưu
        </button>
      </form>
    </div>
  </div>
</template>
<style>
.ql-container {
  height: calc(100% - 60px);
}
.ql-editor img {
  max-width: 100%;
  width: 300px;
  height: auto;
  object-fit: cover;
}
</style>
<script setup>
import axios from "axios";
import { ref, onMounted } from "vue";
import { QuillEditor } from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";
import { useRouter, useRoute } from "vue-router";

const router = useRouter();
const route = useRoute();

const title = ref("");
const content = ref("");
const category_id = ref(1);

// ====================== GET DETAIL ======================
const handalegetPost = async () => {
  try {
    const token = localStorage.getItem("token");

    const id = route.params.id;

    const res = await axios.get(
      `http://127.0.0.1/blog/backend/api/posts.php?id=${id}`,
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      },
    );

    console.log(res.data);

    if (res.data.success) {
      const post = res.data.data;

      title.value = post.title;
      content.value = post.content;
      category_id.value = post.category_id;
    }
  } catch (error) {
    console.log(error.response?.data);
    console.log("ko lấy được bài viết");
  }
};

// ====================== UPDATE ======================
const handelpostPosts = async () => {
  try {
    const token = localStorage.getItem("token");

    const id = route.params.id;

    const slug = title.value.toLowerCase().replace(/\s+/g, "-");

    const res = await axios.put(
      `http://127.0.0.1/blog/backend/api/posts.php?id=${id}`,
      {
        title: title.value,
        content: content.value,
        category_id: category_id.value,
        slug: slug,
        status: "published",
      },
      {
        headers: {
          Authorization: `Bearer ${token}`,
          "Content-Type": "application/json",
        },
      },
    );

    console.log(res.data);

    if (res.data.success) {
      router.push("/managerPost");
    }
  } catch (error) {
    console.log(error.response?.data);
  }
};

// ====================== MOUNTED ======================
onMounted(() => {
  handalegetPost();
});
</script>
