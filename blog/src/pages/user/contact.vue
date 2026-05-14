<template>
  <div class="min-h-screen bg-gray-100 flex items-center justify-center">
    <div class="w-[600px] bg-white rounded-xl shadow-md p-6">
      <router-link to="/" class="text-sm text-purple-600 hover:underline">
        ← Quay lại Trang chủ
      </router-link>

      <h2 class="text-xl font-semibold mt-3">Đăng Ký Trở Thành Tác Giả ✍️</h2>

      <p class="text-gray-500 text-sm mt-1 mb-4">
        Hãy cho chúng tôi biết bạn muốn đóng góp nội dung gì cho cộng đồng nhé!
      </p>

      <!-- Question 1 -->
      <div class="mb-4">
        <p class="font-medium mb-2">
          1. Bạn muốn viết về chủ đề nào? (Chọn nhiều)
        </p>

        <div class="flex flex-wrap gap-2">
          <label
            class="flex items-center gap-2 border px-3 py-1 rounded cursor-pointer"
          >
            <input
              type="checkbox"
              value="Công nghệ thông tin"
              class="accent-purple-500"
              v-model="selectedTopics"
            />
            Công nghệ thông tin
          </label>

          <label
            class="flex items-center gap-2 border px-3 py-1 rounded cursor-pointer"
          >
            <input
              type="checkbox"
              class="accent-purple-500"
              value="server-test"
              v-model="selectedTopics"
            />
            server-test
          </label>

          <label
            class="flex items-center gap-2 border px-3 py-1 rounded cursor-pointer"
          >
            <input
              type="checkbox"
              value="Y tế & Sức khỏe"
              class="accent-purple-500"
              v-model="selectedTopics"
            />
            Y tế & Sức khỏe
          </label>

          <label
            class="flex items-center gap-2 border px-3 py-1 rounded cursor-pointer"
          >
            <input
              type="checkbox"
              value="Giáo dục & Đào tạo"
              class="accent-purple-500"
              v-model="selectedTopics"
            />
            Giáo dục & Đào tạo
          </label>

          <label
            class="flex items-center gap-2 border px-3 py-1 rounded cursor-pointer"
          >
            <input
              type="checkbox"
              class="accent-purple-500"
              value="Thể thao"
              v-model="selectedTopics"
            />
            Thể thao
          </label>

          <label
            class="flex items-center gap-2 border px-3 py-1 rounded cursor-pointer"
          >
            <input
              type="checkbox"
              class="accent-purple-500"
              value="Kinh tế - Tài chính"
              v-model="selectedTopics"
            />
            Kinh tế - Tài chính
          </label>

          <label
            class="flex items-center gap-2 border px-3 py-1 rounded cursor-pointer"
          >
            <input
              type="checkbox"
              class="accent-purple-500"
              v-model="selectedTopics"
              value="Kỹ năng mềm"
            />
            Kỹ năng mềm
          </label>

          <label
            class="flex items-center gap-2 border px-3 py-1 rounded cursor-pointer"
          >
            <input
              type="checkbox"
              class="accent-purple-500"
              v-model="selectedTopics"
              value="Văn hóa - Nghệ thuật"
            />
            Văn hóa - Nghệ thuật
          </label>
        </div>
      </div>

      <!-- Question 2 -->
      <div class="mb-4">
        <p class="font-medium mb-2">
          2. Tại sao bạn muốn trở thành tác giả? (Kinh nghiệm, mục tiêu...)
        </p>

        <textarea
          rows="4"
          placeholder="Mình là sinh viên IT, mình muốn chia sẻ kiến thức về lập trình web..."
          class="w-full border rounded p-2 outline-none focus:ring-2 focus:ring-purple-400"
          v-model="reason"
        ></textarea>
      </div>

      <!-- Button -->
      <button
        @click="handlecheckbox()"
        class="bg-gradient-to-r from-blue-500 to-purple-500 text-white px-5 py-2 rounded shadow hover:scale-105 transition"
      >
        Gửi Yêu Cầu Đăng Ký
      </button>
    </div>
  </div>
</template>
<script setup>
import axios from "axios";
import { ref } from "vue";

const selectedTopics = ref([]);

const reason = ref("");

const handlecheckbox = async () => {
  try {
    const token = localStorage.getItem("token");
    const res = await axios.post(
      "http://localhost/blog/backend/api/author_requests.php",
      {
        topics: selectedTopics.value,
        reason: reason.value,
      },
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      },
    );

    console.log(res.data);
  } catch (error) {
    console.log(error);
  }
};
</script>
