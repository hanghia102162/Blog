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
            <tr class="border-t hover:bg-gray-50 transition duration-200">
              <td class="p-4 font-medium">meotest5</td>

              <td class="p-4">
                <span
                  class="bg-blue-50 text-blue-600 text-xs px-3 py-1 rounded-full font-medium"
                >
                  server-test
                </span>
              </td>

              <td class="p-4 text-gray-400">27/04/2026</td>

              <td class="p-4 text-right space-x-4">
                <router-link
                  to="/edit"
                  class="text-blue-500 hover:text-blue-700 font-medium"
                >
                  Sửa
                </router-link>
                <button
                  class="text-red-500 hover:text-red-700 font-medium transition"
                >
                  Xóa
                </button>
              </td>
            </tr>

            <tr class="border-t hover:bg-gray-50 transition duration-200">
              <td class="p-4 font-medium">meotest4</td>

              <td class="p-4">
                <span
                  class="bg-purple-50 text-purple-600 text-xs px-3 py-1 rounded-full font-medium"
                >
                  server-test
                </span>
              </td>

              <td class="p-4 text-gray-400">27/04/2026</td>

              <td class="p-4 text-right space-x-4">
                <router-link
                  to="/edit"
                  class="text-blue-500 hover:text-blue-700 font-medium"
                >
                  Sửa
                </router-link>
                <button class="text-red-500 hover:text-red-700 font-medium">
                  Xóa
                </button>
              </td>
            </tr>

            <tr class="border-t hover:bg-gray-50 transition duration-200">
              <td class="p-4 font-medium">
                Hướng dẫn sử dụng và Nội quy Hệ thống
              </td>

              <td class="p-4">
                <span
                  class="bg-green-50 text-green-600 text-xs px-3 py-1 rounded-full font-medium"
                >
                  Quy định chung
                </span>
              </td>

              <td class="p-4 text-gray-400">18/04/2026</td>

              <td class="p-4 text-right space-x-4">
                <router-link
                  to="/edit"
                  class="text-blue-500 hover:text-blue-700 font-medium"
                >
                  Sửa
                </router-link>
                <button class="text-red-500 hover:text-red-700 font-medium">
                  Xóa
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="md:hidden flex flex-col gap-4">
        <!-- ITEM -->
        <div class="border rounded-xl p-4 shadow-sm">
          <h3 class="font-semibold text-gray-800">meotest5</h3>

          <div class="flex justify-between items-center mt-2">
            <span
              class="bg-blue-50 text-blue-600 text-xs px-2 py-1 rounded-full"
            >
              server-test
            </span>
            <span class="text-gray-400 text-sm">27/04/2026</span>
          </div>

          <div class="flex justify-end gap-4 mt-3 text-sm">
            <router-link to="/edit" class="text-blue-500">Sửa</router-link>
            <button class="text-red-500">Xóa</button>
          </div>
        </div>

        <!-- ITEM -->
        <div class="border rounded-xl p-4 shadow-sm">
          <h3 class="font-semibold text-gray-800">
            Hướng dẫn sử dụng và Nội quy Hệ thống
          </h3>

          <div class="flex justify-between items-center mt-2">
            <span
              class="bg-green-50 text-green-600 text-xs px-2 py-1 rounded-full"
            >
              Quy định chung
            </span>
            <span class="text-gray-400 text-sm">18/04/2026</span>
          </div>

          <div class="flex justify-end gap-4 mt-3 text-sm">
            <router-link to="/edit" class="text-blue-500">Sửa</router-link>
            <button class="text-red-500">Xóa</button>
          </div>
        </div>
      </div>
    </div>
    <!--  -->
  </div>
</template>
