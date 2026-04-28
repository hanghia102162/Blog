<template>
  <div class="min-h-screen bg-gray-100 flex items-center justify-center">
    <div class="w-full lg:w-[90%] xl:w-[50%] bg-white rounded-xl shadow-md p-6">
      <nav class="flex gap-3 items-center">
        <ion-icon name="pencil-outline"></ion-icon>
        <h2 class="font-medium">Soạn thảo bài viết mới</h2>
      </nav>
      <div class="w-full flex flex-col gap-3 mt-3">
        <input
          type="text"
          placeholder="Tên bài viết"
          class="border p-2 border-gray-400 outline-none focus:ring-2 focus:ring-purple-400"
        />
        <select
          name=""
          id=""
          class="border p-2 border-gray-400 outline-none focus:ring-2 focus:ring-purple-400"
        >
          <option value="">Công nghệ thông tin</option>
          <option value="">Y tế & Sức khỏe</option>
          <option value="">Thể thao</option>
          <option value="">Kỹ năng mềm</option>
          <option value="">Giáo dục & Đào tạo</option>
          <option value="">Văn hóa - Nghệ thuật</option>
        </select>
        <div class="relative inline-block">
          <button
            class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600 transition"
          >
            + Chèn ảnh
          </button>
          <input
            type="file"
            accept="image/*"
            class="absolute inset-0 opacity-0 cursor-pointer"
            @change="handleImage"
          />
        </div>
        <div
          ref="editor"
          contenteditable="true"
          class="w-full h-[350px] border p-3 mt-3 overflow-auto"
        ></div>
        <button
          class="bg-green-600 h-[45px] font-medium text-white cursor-pointer"
        >
          Đăng bài viết
        </button>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref } from "vue";

const editor = ref(null);
const handleImage = (e) => {
  const file = e.target.files[0];
  if (!file) return;
  //   API của JavaScript dùng để đọc file (ảnh, txt, pdf…) chuyển thành dạng có thể dùng trong web:
  const reader = new FileReader();
  reader.onload = () => {
    const img = document.createElement("img");
    img.src = reader.result;
    img.style.maxWidth = "50%";

    img.classList.add("my-2");

    editor.value.appendChild(img);
  };

  reader.readAsDataURL(file);
};
</script>
