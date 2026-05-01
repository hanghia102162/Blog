<template>
  <div class="w-auto min-h-screen flex justify-center p-3 md:p-0">
    <div class="w-full md:w-[90%] md:h-[90%] py-9">
      <div
        class="flex sticky top-0 justify-between items-center bg-black text-white px-1 rounded-[5px]"
      >
        <div>
          <router-link
            to="/"
            class="underline text-yellow-600 hover:text-purple-300 duration-500 cursor-pointer transition-all"
            >Quay lại trang chủ</router-link
          >
          <h2 class="font-medium text-2xl">Trang quản trị hệ thống</h2>
        </div>
        <div class="flex gap-3">
          <a href="" class="hover:text-yellow-500">Phê duyệt</a>
          <a href="" class="hover:text-yellow-500">Phân quyền</a>
          <a href="" class="hover:text-yellow-500">Nhật kí hoạt động</a>
        </div>
      </div>
      <div
        class="min-h-[30%] max-h-[60vh] overflow-auto w-full py-3 shadow-[0_0_10px_rgba(0,0,0,0.5)] mt-[15px] rounded-[5px] flex flex-col gap-3 p-3"
      >
        <h2 class="text-xl font-medium">Đơn tác giả xin chờ duyệt</h2>
        <table class="w-full border w-full border min-w-[700px]">
          <thead class="bg-black text-white text-center">
            <tr>
              <td class="border">Ứng viên</td>
              <td class="border">Chủ đề mong muốn</td>
              <td class="border">Lí do đăng kí</td>
              <td class="border">Thời gian hành động</td>
              <td class="border">hành động</td>
            </tr>
          </thead>
          <tbody class="text-center">
            <tr v-for="(item, index) in Author" :key="index">
              <td class="border">
                <h2>{{ item.uv }}</h2>
                <p class="text-gray-400">{{ item.email }}</p>
              </td>
              <td class="border">
                <p class="bg-yellow-400">{{ item.chude }}</p>
              </td>
              <td class="border">
                <p>{{ item.lido }}</p>
              </td>
              <td class="border">
                <p>{{ item.thoigian }}</p>
              </td>
              <td class="border">
                <button
                  class="bg-green-600 hover:bg-green-500 cursor-pointer mx-2 p-1 rounded-[5px] text-white"
                >
                  Duyệt
                </button>
                <button
                  class="bg-red-600 hover:bg-red-500 cursor-pointer p-1 rounded-[5px] text-white"
                >
                  Từ chối
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!--  -->
      <div
        class="min-h-[30%] overflow-auto w-full py-3 shadow-[0_0_10px_rgba(0,0,0,0.5)] mt-[15px] rounded-[5px] flex flex-col gap-3 p-3"
      >
        <h2 class="text-xl font-medium">Quản lí phân quyền (Roles)</h2>
        <table class="w-full border w-full border min-w-[700px]">
          <thead class="bg-black text-white text-center">
            <tr>
              <td class="border">Id</td>
              <td class="border">Username</td>
              <td class="border">Email</td>
              <td class="border">Quyền hiện tại</td>
              <td class="border">hành động</td>
            </tr>
          </thead>
          <tbody class="text-center">
            <tr>
              <td class="border">
                <h2>01</h2>
              </td>
              <td class="border">
                <p>Hà Trọng Nghĩa</p>
              </td>
              <td class="border"><p>a@gmail.com</p></td>
              <td class="border"><p>Admin</p></td>
              <td class="border py-1">
                <select name="" id="" class="border outline-none mr-3">
                  <option value="">Admin</option>
                  <option value="">User</option>
                  <option value="">Author</option>
                </select>
                <button
                  class="p-1 bg-blue-600 rounded-[5px] hover:bg-blue-500 cursor-pointer text-white"
                >
                  Đổi quyền
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!--  -->
      <div
        class="min-h-[30%] max-h-[100%] overflow-auto w-full py-3 shadow-[0_0_10px_rgba(0,0,0,0.5)] mt-[15px] rounded-[5px] flex flex-col gap-3 p-3"
      >
        <h2 class="text-xl font-medium">Nhật kí hoạt đông( System-Logs)</h2>
        <table class="w-full border w-full border min-w-[700px]">
          <thead class="bg-black text-white text-center">
            <tr>
              <td class="border">Thời gian</td>
              <td class="border">User</td>
              <td class="border">Hành động</td>
              <td class="border">Tri tiết</td>
              <td class="border">Ip</td>
            </tr>
          </thead>
          <tbody class="text-center">
            <tr>
              <td class="border">
                <h2>2026-04-29:07:39:42</h2>
              </td>
              <td class="border">
                <p>Admin</p>
              </td>
              <td class="border"><p>a@gmail.com</p></td>
              <td class="border"><p>Login</p></td>
              <td class="border"><p>1.1.1.255</p></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref } from "vue";
const Author = [
  {
    uv: "datatech",
    email: "a@gmail.com",
    chude: "thethao",
    lido: "t muon",
    thoigian: "18/04:17:29",
  },
  {
    uv: "datatech",
    email: "a@gmail.com",
    chude: "thethao",
    lido: "t muon",
    thoigian: "18/04:17:29",
  },
  {
    uv: "datatech",
    email: "a@gmail.com",
    chude: "thethao",
    lido: "t muon",
    thoigian: "18/04:17:29",
  },
  {
    uv: "datatech",
    email: "a@gmail.com",
    chude: "thethao",
    lido: "t muon",
    thoigian: "18/04:17:29",
  },
  {
    uv: "datatech",
    email: "a@gmail.com",
    chude: "thethao",
    lido: "t muon",
    thoigian: "18/04:17:29",
  },
  {
    uv: "datatech",
    email: "a@gmail.com",
    chude: "thethao",
    lido: "t muon",
    thoigian: "18/04:17:29",
  },
  {
    uv: "datatech",
    email: "a@gmail.com",
    chude: "thethao",
    lido: "t muon",
    thoigian: "18/04:17:29",
  },
  {
    uv: "datatech",
    email: "a@gmail.com",
    chude: "thethao",
    lido: "t muon",
    thoigian: "18/04:17:29",
  },
  {
    uv: "datatech",
    email: "a@gmail.com",
    chude: "thethao",
    lido: "t muon",
    thoigian: "18/04:17:29",
  },
  {
    uv: "datatech",
    email: "a@gmail.com",
    chude: "thethao",
    lido: "t muon",
    thoigian: "18/04:17:29",
  },
];
</script>
