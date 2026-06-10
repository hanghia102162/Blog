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
          <button
            @click="exportPDF"
            class="bg-blue-600 hover:bg-blue-500 text-white px-3 py-1 rounded transition-all duration-300"
          >
            Xuất PDF
          </button>
        </div>
      </div>
      <div
        class="min-h-[30%] max-h-[60vh] overflow-auto w-full py-3 shadow-[0_0_10px_rgba(0,0,0,0.5)] mt-[15px] rounded-[5px] flex flex-col gap-3 p-3"
      >
        <h2 class="text-xl font-medium">Đơn tác giả xin chờ duyệt</h2>
        <table class="w-full min-w-[700px] border table-fixed">
          <thead class="bg-black text-white text-center">
            <tr class="">
              <td class="border p-2">Ứng viên</td>
              <td class="border p-2">Chủ đề mong muốn</td>
              <td class="border p-2">Lí do đăng kí</td>
              <td class="border p-2">Thời gian hành động</td>
              <td class="border p-2">Hành động</td>
            </tr>
          </thead>

          <tbody class="text-center">
            <tr v-for="(item, index) in Author" :key="index">
              <td
                class="border p-2 break-words whitespace-normal max-w-[180px]"
              >
                <h2 class="font-medium">{{ item.uv }}</h2>
                <p class="text-gray-400 text-sm">{{ item.email }}</p>
              </td>

              <td
                class="border p-2 break-words whitespace-normal max-w-[200px]"
              >
                <p class="px-2 py-1 rounded">
                  {{ item.topics }}
                </p>
              </td>

              <td
                class="border p-2 break-words whitespace-normal max-w-[300px]"
              >
                <p>{{ item.reason }}</p>
              </td>
              <td class="border p-2 whitespace-normal max-w-[180px]">
                <p>{{ item.updated_at }}</p>
              </td>
              <td class="border p-2">
                <div class="flex justify-center gap-2">
                  <button
                    @click="handelPheDuyet(item.id)"
                    class="bg-green-600 hover:bg-green-500 cursor-pointer px-3 py-1 rounded text-white"
                  >
                    Duyệt
                  </button>

                  <button
                    @click="handelTuChoi(item.id)"
                    class="bg-red-600 hover:bg-red-500 cursor-pointer px-3 py-1 rounded text-white"
                  >
                    Từ chối
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!--  -->
      <div
        class="min-h-[30%] overflow-auto w-full py-3 shadow-[0_0_10px_rgba(0,0,0,0.5)] mt-[15px] rounded-[5px] flex flex-col gap-3 p-3"
      >
        <div class="flex justify-between">
          <h2 class="text-xl font-medium">Quản lí phân quyền (Roles)</h2>
          <p class="text-red-500 font-medium">{{ message }}</p>
        </div>
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
            <tr
              v-for="item in users"
              :key="item.id"
              :class="
                item.role === 'admin'
                  ? 'bg-blue-300'
                  : item.role === 'author'
                    ? 'bg-yellow-200'
                    : 'bg-white'
              "
            >
              <td class="border">
                <h2>{{ item.id }}</h2>
              </td>
              <td class="border">
                <p>{{ item.username }}</p>
              </td>
              <td class="border">
                <p>{{ item.email }}</p>
              </td>
              <td class="border">
                <p>{{ item.role }}</p>
              </td>
              <td class="border py-1">
                <select v-model="item.role" class="border outline-none mr-3">
                  <option value="admin">Admin</option>
                  <option value="reader">Reader</option>
                  <option value="author">Author</option>
                </select>
                <button
                  @click="handleUpdateRole(item.id)"
                  class="p-1 bg-blue-600 rounded-[5px] hover:bg-blue-500 cursor-pointer text-white"
                >
                  Đổi quyền
                </button>

                <button
                  @click="confirmDelete(item.id)"
                  class="p-1 bg-red-600 ml-3 rounded-[5px] hover:bg-blue-500 cursor-pointer text-white"
                >
                  Xóa user
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
import axios from "axios";
import { ref, onMounted } from "vue";

const Author = ref([]);

const handelGetPheDuyet = async () => {
  const token = localStorage.getItem("token"); //
  try {
    const res = await axios.get(
      "http://localhost/blog/backend/api/author_requests.php",
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      },
    );

    Author.value = res.data.data.filter((item) => item.status === "pending");
    console.log(Author.value);
  } catch (error) {
    console.log(error);
  }
};

onMounted(() => {
  handelGetPheDuyet();
  getAllUsers();
});
// =================phê duyệt ===========================
const handelPheDuyet = async (id) => {
  const token = localStorage.getItem("token");

  try {
    const res = await axios.put(
      `http://localhost/blog/backend/api/author_requests.php?id=${id}&action=approve`,
      {},
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      },
    );
    if (res.data.success) {
      window.location.reload();
    }
    console.log(res.data);

    // await handelGetPheDuyet();
  } catch (error) {
    console.log(error);
  }
};
// ========================từ chối=====================
const handelTuChoi = async (id) => {
  const token = localStorage.getItem("token");

  try {
    const res = await axios.put(
      `http://localhost/blog/backend/api/author_requests.php?id=${id}&action=reject`,
      {},
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      },
    );
    if (res.data.success) {
      window.location.reload();
    }
    console.log(res.data);
  } catch (error) {
    console.log(error.response?.data || error.message);
  }
};
// =================hiển thị user=======

const users = ref([]);

const getAllUsers = async () => {
  const token = localStorage.getItem("token");

  try {
    const res = await axios.get(
      "http://localhost/blog/backend/api/userAPI.php?action=getUser",
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      },
    );

    users.value = res.data.data;
    console.log(users.value);
    console.log("FULL RESPONSE:", res.data);
  } catch (error) {
    console.log(error.response?.data || error.message);
  }
};

onMounted(() => {
  getAllUsers();
});
// ==============================đổi quyền================================
const handleUpdateRole = async (id) => {
  try {
    const token = localStorage.getItem("token");

    const res = await axios.put(
      `http://localhost/blog/backend/api/author_requests.php?id=${id}&action=revokeAuthor`,
      {},
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      },
    );

    console.log(res.data);

    if (res.data.success) {
      await getAllUsers();
    }
  } catch (error) {
    console.log(error.response?.data || error.message);
  }
};
// xoa user
const confirmDelete = (id) => {
  const ok = confirm("Bạn có chắc muốn xóa user này không?");

  if (!ok) return;

  handleDelete(id);
};
// ======xoa=================
const message = ref("");
const handleDelete = async (id) => {
  const token = localStorage.getItem("token");
  try {
    const res = await axios.post(
      `http://localhost/blog/backend/api/userAPI.php`,
      {
        action: "deleteUser",
        id: id,
      },
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      },
    );
    message.value = res.data.message;
    console.log(res.data);
    console.log(id);
    if (res.data.status) {
      window.location.reload();
    }
  } catch (error) {
    console.log(error);
  }
};
// ====================xuat file======================
import { jsPDF } from "jspdf";
import autoTable from "jspdf-autotable";
const exportPDF = () => {
  const doc = new jsPDF();

  const today = new Date().toLocaleString();

  doc.setFontSize(16);
  doc.text("ADMIN REPORT SYSTEM", 14, 15);

  doc.setFontSize(10);
  doc.text(`Export: ${today}`, 14, 22);

  // 1. AUTHOR REQUESTS

  autoTable(doc, {
    startY: 30,
    head: [["Ứng viên", "Email", "Topics", "Reason"]],
    body: Author.value.map((a) => [a.uv, a.email, a.topics, a.reason]),
  });

  // 2. USERS ROLES

  autoTable(doc, {
    startY: doc.lastAutoTable.finalY + 10,
    head: [["ID", "Username", "Email", "Role"]],
    body: users.value.map((u) => [u.id, u.username, u.email, u.role]),
  });

  // 3. LOGS (cứng)

  autoTable(doc, {
    startY: doc.lastAutoTable.finalY + 10,
    head: [["Time", "User", "Action", "Detail", "IP"]],
    body: [["2026-04-29", "Admin", "Login", "Login system", "1.1.1.255"]],
  });

  doc.save("admin-report.pdf");
};
</script>

// odd:bg-... → áp dụng cho hàng lẻ odd:bg-gray-800 // even:bg-... → áp dụng cho
hàng chẵn // odd:bg-red-800 even:bg-gray-700
