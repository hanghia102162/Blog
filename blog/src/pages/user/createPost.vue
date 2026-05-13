<template>
  <div class="min-h-screen bg-gray-100 flex items-center justify-center">
    <div class="w-full lg:w-[90%] xl:w-[50%] bg-white rounded-xl shadow-md p-6">
      <nav class="flex gap-3 items-center">
        <ion-icon name="pencil-outline"></ion-icon>
        <h2 class="font-medium">Soạn thảo bài viết mới</h2>
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
          Đăng bài viết
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
import axios, { formToJSON } from "axios";
import { ref } from "vue";
import { QuillEditor } from "@vueup/vue-quill";
import "@vueup/vue-quill/dist/vue-quill.snow.css";
import { useRouter } from "vue-router";
const router = useRouter();
const title = ref("");
const content = ref("");
const updateContent = (e) => {
  content.value = e.target.innerHTML;
};
const category_id = ref(1);
const handelpostPosts = async () => {
  try {
    const token = localStorage.getItem("token");

    const slug = title.value.toLowerCase().replace(/\s+/g, "-");

    const res = await axios.post(
      "http://127.0.0.1/blog/backend/api/posts.php",
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
    if (res.data.success) {
      router.push("/managerPost");
    }
    console.log(localStorage.getItem("token"));
    console.log(res.data);
  } catch (error) {
    console.log(error.response?.data);
  }
};
</script>
