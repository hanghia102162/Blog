<template>
  <div class="min-h-screen bg-gray-100 py-10">
    <div class="w-[70%] mx-auto bg-white rounded-xl shadow-md p-6">
      <!-- TITLE -->
      <h2 class="text-xl font-semibold text-gray-800 mb-4">
        Chỉnh sửa bài viết
      </h2>

      <!-- INPUT TITLE -->
      <input
        type="text"
        placeholder="Tiêu đề bài viết"
        class="w-full border rounded px-3 py-2 mb-3 outline-none focus:ring-2 focus:ring-blue-400"
      />

      <!-- SELECT CATEGORY -->
      <select
        class="w-full border rounded px-3 py-2 mb-3 outline-none focus:ring-2 focus:ring-blue-400"
      >
        <option>Quy định chung</option>
        <option>Frontend</option>
        <option>Backend</option>
      </select>

      <!-- ADD IMAGE -->
      <button
        class="mb-3 text-sm border px-3 py-1 rounded hover:bg-gray-100 transition"
      >
        + Thêm ảnh mới
      </button>

      <!-- CONTENT -->
      <textarea
        rows="12"
        class="w-full border rounded px-3 py-2 outline-none focus:ring-2 focus:ring-blue-400 text-sm"
      >
Chào mừng các bạn đến với Hệ thống Quản lý Kiến thức!

Để đảm bảo hệ thống vận hành trơn tru, Admin xin gửi tới các bạn hướng dẫn chi tiết như sau:

1. VỀ PHÂN QUYỀN:
- Thành viên (User): Quyền mặc định khi đăng ký. Có thể xem bài viết và để lại bình luận.
- Tác giả (Author): Có quyền viết bài và quản lý bài viết của mình.
- Quản trị viên (Admin): Toàn quyền quản lý người dùng, danh mục và hệ thống.

2. CÁCH ĐĂNG KÝ LÊN TÁC GIẢ:
- Bước 1: Tại trang chủ, nhấn vào nút "Trở thành Tác giả".
- Bước 2: Chọn các chủ đề bạn muốn viết bài và lý do mong muốn đóng góp.
- Bước 3: Đợi Admin phê duyệt.

3. QUY TRÌNH ĐĂNG BÀI:
- Chỉ dành cho Author và Admin.
- Nhấn vào nút "Viết bài" trên thanh công cụ.

Chúc các bạn có trải nghiệm tuyệt vời!
    </textarea
      >

      <!-- ACTION -->
      <div class="flex items-center gap-4 mt-5">
        <button
          class="bg-green-500 text-white px-5 py-2 rounded hover:bg-green-600 transition"
        >
          LƯU THAY ĐỔI
        </button>

        <button class="text-gray-500 hover:underline" @click="back()">
          Hủy bỏ
        </button>
      </div>
    </div>
  </div>
</template>
<script setup>
import { useRouter } from "vue-router";
const router = useRouter();
const back = () => {
  router.back(); // quay lại trang trước
};
</script>
