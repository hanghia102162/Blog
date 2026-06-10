<template>
  <div
    class="min-h-screen bg-gray-100 flex items-center justify-center py-10 relative"
  >
    <div
      class="w-[90%] lg:w-[90%] xl:w-[60%] bg-white rounded-xl shadow-md p-6"
    >
      <nav class="flex gap-3 items-center mb-4">
        <ion-icon name="pencil-outline" class="text-xl"></ion-icon>
        <h2 class="font-medium text-lg">Soạn thảo bài viết mới</h2>
      </nav>
      <form
        @submit.prevent="handelpostPosts()"
        class="w-full flex flex-col gap-4"
      >
        <input
          type="text"
          v-model="title"
          placeholder="Tên bài viết"
          class="border p-2 border-gray-400 rounded outline-none focus:ring-2 focus:ring-green-400"
        />
        <select
          v-model="category_id"
          class="border p-2 border-gray-400 rounded outline-none focus:ring-2 focus:ring-green-400"
        >
          <option :value="1">Công nghệ thông tin</option>
          <option :value="2">Y tế & Sức khỏe</option>
          <option :value="3">Thể thao</option>
          <option :value="4">Kỹ năng mềm</option>
          <option :value="5">Giáo dục & Đào tạo</option>
          <option :value="6">Văn hóa - Nghệ thuật</option>
        </select>

        <div class="h-[600px] mb-4">
          <Editor
            v-model="content"
            api-key="erxnm0nxncqfujgof3posl2bs0ei975kvumxak9rhgnq60r9"
            :init="editorConfig"
          />
        </div>

        <button
          class="bg-green-600 h-[45px] font-medium text-white rounded cursor-pointer hover:bg-green-800 transition duration-300"
        >
          Đăng bài viết
        </button>
      </form>
    </div>

    <div
      v-if="showMathModal"
      class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-[9999]"
    >
      <div class="bg-white p-6 rounded-xl shadow-2xl w-[90%] max-w-3xl">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-xl font-medium">Nhập công thức toán học</h3>
          <button
            @click="showMathModal = false"
            type="button"
            class="text-gray-500 hover:text-red-500 font-bold text-xl"
          >
            &times;
          </button>
        </div>

        <p class="text-sm text-gray-500 mb-3">
          Bạn có thể sử dụng bàn phím ảo bên dưới để nhấp chọn các ký hiệu (Phân
          số, căn bậc 2, tích phân...)
        </p>

        <math-field
          ref="mathFieldRef"
          class="w-full text-2xl border-2 border-gray-300 p-4 rounded-lg bg-gray-50 focus:outline-none focus:border-blue-500"
          style="min-height: 80px"
        ></math-field>

        <div class="flex justify-end gap-3 mt-6">
          <button
            @click="showMathModal = false"
            type="button"
            class="px-5 py-2.5 bg-gray-200 text-gray-700 font-medium rounded hover:bg-gray-300 transition"
          >
            Hủy
          </button>
          <button
            @click="insertMathToEditor"
            type="button"
            class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded hover:bg-blue-700 transition"
          >
            Chèn vào bài viết
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
<style>
body {
  --keyboard-zindex: 100000 !important;
}
math-field {
  font-size: 1.5rem;
}
math-field::part(virtual-keyboard-toggle) {
  color: #2563eb;
}
</style>
<script setup>
import axios from "axios";
import { ref, onMounted, onUnmounted } from "vue";
import { useRouter } from "vue-router";
import Editor from "@tinymce/tinymce-vue";
import "mathlive";

const router = useRouter();
const title = ref("");
const content = ref("");
const category_id = ref(1);

const showMathModal = ref(false);
const mathFieldRef = ref(null);
const tinymceInstance = ref(null);

const editorConfig = {
  height: 600,
  menubar: "file edit view insert format tools table help",
  resize: false,
  plugins: [
    "advlist",
    "autolink",
    "lists",
    "link",
    "image",
    "charmap",
    "preview",
    "anchor",
    "searchreplace",
    "visualblocks",
    "code",
    "fullscreen",
    "insertdatetime",
    "media",
    "table",
    "help",
    "wordcount",
  ],
  toolbar:
    "undo redo | blocks fontfamily fontsize | " +
    "bold italic underline strikethrough | alignleft aligncenter " +
    "alignright alignjustify | bullist numlist outdent indent | " +
    "table charmap math | removeformat | help",
  font_size_formats: "8pt 10pt 12pt 14pt 16pt 18pt 24pt 36pt",
  content_style:
    "body { font-family:Helvetica,Arial,sans-serif; font-size:16px }",
  language: "vi",
  branding: false,

  setup: (editor) => {
    tinymceInstance.value = editor;
    editor.ui.registry.addButton("math", {
      text: "√x (Toán)",
      tooltip: "Chèn công thức toán học trực quan",
      onAction: () => {
        showMathModal.value = true;
      },
    });

    editor.on("keydown", (e) => {
      if (e.key === "Escape" || e.keyCode === 27) {
        if (document.body.classList.contains("tox-fullscreen")) {
          editor.execCommand("mceFullScreen");
        }
      }
    });
  },
};

const insertMathToEditor = () => {
  if (mathFieldRef.value && tinymceInstance.value) {
    const latexFormula = mathFieldRef.value.value;

    if (latexFormula) {
      const encodedEquation = encodeURIComponent(latexFormula);
      const imgHtml = `<img src="https://latex.codecogs.com/svg.image?${encodedEquation}" alt="${latexFormula}" style="vertical-align: middle; padding: 0 4px;" />`;
      tinymceInstance.value.insertContent(imgHtml);
    }
  }
  showMathModal.value = false;
  if (mathFieldRef.value) mathFieldRef.value.value = "";
};

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
  } catch (error) {
    console.log(error.response?.data);
    alert(error.response?.data?.message || "Thiếu dữ liệu / lỗi server");
  }
};
const handleGlobalKeyDown = (e) => {
  if ((e.key === "Escape" || e.keyCode === 27) && showMathModal.value) {
    showMathModal.value = false;
  }
};

onMounted(() => {
  window.addEventListener("keydown", handleGlobalKeyDown);
});

onUnmounted(() => {
  window.removeEventListener("keydown", handleGlobalKeyDown);
});
</script>
