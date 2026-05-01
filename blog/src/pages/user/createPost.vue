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
          v-model="category"
          class="border p-2 border-gray-400 outline-none focus:ring-2 focus:ring-purple-400"
        >
          <option value="Công nghệ thông tin">Công nghệ thông tin</option>
          <option value="Y tế & Sức khỏe">Y tế & Sức khỏe</option>
          <option value="Thể thao">Thể thao</option>
          <option value="Kỹ năng mềm">Kỹ năng mềm</option>
          <option value="Giáo dục & Đào tạo">Giáo dục & Đào tạo</option>
          <option value="Văn hóa - Nghệ thuật">Văn hóa - Nghệ thuật</option>
        </select>
        <div class="h-[500px]">
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

const title = ref("");
const content = ref("");
const updateContent = (e) => {
  content.value = e.target.innerHTML;
};
const category = ref("");
const handelpostPosts = async () => {
  try {
    const res = await axios.post("http://127.0.0.1:8000", {
      title: title.value,
      content: content.value,
    });

    console.log(res.data);
  } catch (error) {
    console.log(error);
  }
};
</script>
