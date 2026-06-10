<template>
  <div
    class="min-h-screen bg-[#050a15] text-slate-300 p-3 sm:p-5 lg:p-8"
    v-if="user"
  >
    <Loading2 v-if="opentLoading" />
    <div class="max-w-7xl mx-auto flex flex-col xl:flex-row gap-5 lg:gap-8">
      <!-- Sidebar -->
      <aside class="w-full xl:w-[320px] flex flex-col gap-4 lg:gap-6">
        <!-- User Info -->
        <div
          class="bg-[#0b1426] border border-blue-900/30 rounded-2xl p-5 lg:p-6 text-center shadow-2xl"
        >
          <img
            :src="user.avatar || '/img/sontung.jpg'"
            alt="Avatar"
            class="w-24 h-24 sm:w-28 sm:h-28 lg:w-32 lg:h-32 rounded-2xl mx-auto object-cover mb-4 grayscale hover:grayscale-0 transition-all duration-500"
          />

          <h2 class="text-lg sm:text-xl font-bold text-blue-50 mb-1">
            {{ user.username }}
          </h2>

          <p class="text-xs text-blue-400/60 break-all mb-4">
            {{ user.email }}
          </p>

          <span
            class="bg-blue-950 text-blue-400 text-[10px] uppercase tracking-widest px-4 py-1.5 rounded-lg border border-blue-800/50"
          >
            {{ user.role }}
          </span>
        </div>

        <!-- Menu -->
        <nav
          class="bg-[#0b1426] border border-blue-900/20 rounded-2xl p-3 shadow-xl"
        >
          <ul class="space-y-2">
            <li
              class="bg-blue-600/20 text-blue-400 border border-blue-600/30 p-3 rounded-xl flex items-center gap-3 cursor-pointer"
            >
              <i class="fas fa-user-circle"></i>
              <span class="text-sm">Thông tin</span>
            </li>

            <router-link
              to="/managerPost"
              class="p-3 rounded-xl flex items-center gap-3 hover:bg-blue-900/20 text-slate-500 hover:text-slate-300 transition"
            >
              <i class="fas fa-file-alt"></i>
              <span class="text-sm">Bài đăng</span>
            </router-link>

            <router-link
              to="/contact"
              class="p-3 rounded-xl flex items-center gap-3 hover:bg-blue-900/20 text-slate-500 hover:text-slate-300 transition"
            >
              <i class="fas fa-pen-nib"></i>
              <span class="text-sm">Đăng ký tác giả</span>
            </router-link>
            <li
              @click="handleLogout"
              class="p-3 rounded-xl flex items-center gap-3 hover:bg-red-900/20 text-red-400 hover:text-red-300 cursor-pointer transition"
            >
              <i class="fas fa-sign-out-alt"></i>
              <span class="text-sm">Đăng xuất</span>
            </li>
          </ul>
        </nav>
      </aside>

      <!-- Main -->
      <main class="flex-1 space-y-5 lg:space-y-6">
        <!-- Account -->
        <section
          class="bg-[#0b1426] border border-blue-900/20 rounded-2xl p-4 sm:p-6 lg:p-8 shadow-xl"
        >
          <!-- top -->
          <div
            class="flex flex-col lg:flex-row lg:items-center justify-between gap-5 mb-8"
          >
            <div class="flex items-center gap-3">
              <div
                class="bg-blue-600/10 p-2.5 rounded-xl border border-blue-600/20"
              >
                <i class="fas fa-id-card text-blue-500"></i>
              </div>

              <h3 class="text-base sm:text-lg font-semibold text-blue-50">
                Thông tin cơ bản
              </h3>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
              <button
                class="text-slate-500 hover:text-white text-sm transition"
              >
                Hủy
              </button>

              <button
                @click="handleUpdateProfile()"
                class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-3 rounded-xl text-sm font-semibold shadow-lg shadow-blue-600/20 transition-all"
              >
                Lưu thay đổi
              </button>
            </div>
          </div>

          <!-- form -->
          <div
            class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5 lg:gap-6"
          >
            <!-- username -->
            <div>
              <label
                class="block text-[10px] font-bold text-blue-400/50 uppercase mb-3 tracking-[0.2em]"
              >
                Tên User
              </label>

              <div class="relative">
                <span
                  class="absolute inset-y-0 left-4 flex items-center text-blue-900"
                >
                  <i class="fas fa-user"></i>
                </span>

                <input
                  type="text"
                  v-model="name"
                  :placeholder="user.username"
                  class="w-full bg-[#050a15] border border-blue-900/30 rounded-xl py-3.5 pl-12 pr-4 focus:border-blue-500/50 focus:ring-4 focus:ring-blue-500/5 outline-none text-slate-200 transition-all"
                />
              </div>
            </div>

            <!-- email -->
            <div>
              <label
                class="block text-[10px] font-bold text-blue-400/50 uppercase mb-3 tracking-[0.2em]"
              >
                Email
              </label>

              <div class="relative">
                <span
                  class="absolute inset-y-0 left-4 flex items-center text-blue-900"
                >
                  <i class="fas fa-envelope"></i>
                </span>

                <input
                  type="email"
                  :placeholder="user.email"
                  v-model="email"
                  class="w-full bg-[#050a15] border border-blue-900/30 rounded-xl py-3.5 pl-12 pr-4 focus:border-blue-500/50 focus:ring-4 focus:ring-blue-500/5 outline-none text-slate-200 transition-all"
                />
              </div>
            </div>

            <!-- phone -->
            <div>
              <label
                class="block text-[10px] font-bold text-blue-400/50 uppercase mb-3 tracking-[0.2em]"
              >
                Số điện thoại
              </label>

              <div class="relative">
                <span
                  class="absolute inset-y-0 left-4 flex items-center text-blue-900"
                >
                  <i class="fas fa-phone"></i>
                </span>

                <input
                  type="text"
                  :placeholder="user.hostline"
                  v-model="hostline"
                  class="w-full bg-[#050a15] border border-blue-900/30 rounded-xl py-3.5 pl-12 pr-4 focus:border-blue-500/50 focus:ring-4 focus:ring-blue-500/5 outline-none text-slate-200 transition-all"
                />
              </div>
            </div>
          </div>
        </section>

        <!-- Password -->
        <section
          class="bg-[#0b1426] border border-blue-900/20 rounded-2xl p-4 sm:p-6 lg:p-8 shadow-xl"
        >
          <div class="flex items-center gap-3 mb-8">
            <div
              class="bg-red-500/10 p-2.5 rounded-xl border border-red-500/20"
            >
              <i class="fas fa-lock text-red-500"></i>
            </div>

            <h3 class="text-base sm:text-lg font-semibold text-blue-50">
              Đổi mật khẩu
            </h3>
          </div>

          <div class="flex flex-col xl:flex-row gap-8">
            <!-- left -->
            <div class="flex-1 space-y-5 relative">
              <div>
                <label class="block text-sm text-slate-500 mb-2">
                  Mật khẩu hiện tại
                </label>

                <input
                  required
                  :type="passWord ? 'text' : 'password'"
                  v-model="password"
                  placeholder="********"
                  class="w-full bg-[#050a15] border border-blue-900/20 rounded-xl py-3.5 px-5 focus:border-blue-500/50 focus:ring-4 focus:ring-blue-500/5 outline-none text-slate-300 transition-all"
                />
                <button
                  class="absolute h-1/8 right-5 cursor-pointer"
                  @click="passWord = !passWord"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="size-6"
                    v-if="!passWord"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"
                    />
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                    />
                  </svg>

                  <!-- ==== -->
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="size-6"
                    v-if="passWord"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"
                    />
                  </svg>
                </button>
              </div>

              <div>
                <label class="block text-sm text-slate-500 mb-2">
                  Mật khẩu mới
                </label>

                <input
                  required
                  :type="showPassword ? 'text' : 'password'"
                  v-model="newPassword"
                  placeholder="Tối thiểu 8 ký tự"
                  class="w-full bg-[#050a15] border border-blue-900/20 rounded-xl py-3.5 px-5 focus:border-blue-500/50 focus:ring-4 focus:ring-blue-500/5 outline-none text-slate-300 transition-all"
                />
                <button
                  class="absolute h-1/8 right-5 cursor-pointer"
                  @click="showPassword = !showPassword"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="size-6"
                    v-if="!showPassword"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"
                    />
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                    />
                  </svg>

                  <!-- ==== -->
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="size-6"
                    v-if="showPassword"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"
                    />
                  </svg>
                </button>
              </div>

              <div>
                <label class="block text-sm text-slate-500 mb-2">
                  Xác nhận mật khẩu mới
                </label>

                <input
                  required
                  :type="showNewPassword ? 'text' : 'password'"
                  v-model="ConfirmNewpassword"
                  placeholder="Nhập lại mật khẩu mới"
                  class="w-full bg-[#050a15] border border-blue-900/20 rounded-xl py-3.5 px-5 focus:border-blue-500/50 focus:ring-4 focus:ring-blue-500/5 outline-none text-slate-300 transition-all"
                />
                <button
                  class="absolute h-1/8 right-5 cursor-pointer"
                  @click="showNewPassword = !showNewPassword"
                >
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="size-6"
                    v-if="!showNewPassword"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"
                    />
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"
                    />
                  </svg>

                  <!-- ==== -->
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="size-6"
                    v-if="showNewPassword"
                  >
                    <path
                      stroke-linecap="round"
                      stroke-linejoin="round"
                      d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88"
                    />
                  </svg>
                </button>
              </div>

              <button
                class="w-full sm:w-auto bg-blue-600 hover:bg-blue-500 text-white px-8 py-3 rounded-xl font-medium transition"
                @click="handelUpdatePassword()"
              >
                Đổi mật khẩu
              </button>
            </div>

            <!-- right -->
            <div
              class="w-full xl:w-[350px] bg-blue-950/20 border border-blue-900/10 rounded-2xl p-5 lg:p-6"
            >
              <h4
                class="text-blue-400 text-xs font-bold uppercase mb-4 tracking-widest"
              >
                Tiêu chuẩn mật khẩu
              </h4>

              <ul class="space-y-4">
                <li
                  class="flex items-start gap-3 text-xs text-slate-400 leading-relaxed"
                >
                  <i class="fas fa-check-circle text-emerald-500/50 mt-1"></i>
                  Mật khẩu nên có cả chữ hoa, chữ thường và số.
                </li>

                <li
                  class="flex items-start gap-3 text-xs text-slate-400 leading-relaxed"
                >
                  <i class="fas fa-check-circle text-emerald-500/50 mt-1"></i>
                  Tránh sử dụng ngày sinh hoặc tên của bạn.
                </li>

                <li
                  class="flex items-start gap-3 text-xs text-slate-400 leading-relaxed"
                >
                  <i class="fas fa-check-circle text-emerald-500/50 mt-1"></i>
                  Không nên dùng lại mật khẩu cũ.
                </li>
              </ul>

              <div class="mt-5">
                <h2
                  class="text-blue-400 text-xs font-bold uppercase mb-4 tracking-widest"
                >
                  lỗi Định dạng
                </h2>
                <ul>
                  <template v-for="(item, key) in errors" :key="key">
                    <template v-if="Array.isArray(item)">
                      <li
                        v-for="(msg, index) in item"
                        :key="index"
                        class="text-red-500"
                      >
                        {{ msg }}
                      </li>
                    </template>

                    <li v-else class="text-red-500">
                      {{ item }}
                    </li>
                  </template>
                </ul>
              </div>
            </div>
          </div>
        </section>
      </main>
    </div>
  </div>
</template>

<style scoped>
::-webkit-scrollbar {
  width: 6px;
}

::-webkit-scrollbar-thumb {
  background: #1e293b;
  border-radius: 10px;
}
</style>
<script setup>
import axios from "axios";
import { onMounted, ref } from "vue";
import { useRouter } from "vue-router";
import Loading2 from "../../components/loading2.vue";
const router = useRouter();
const name = ref("");
const email = ref("");
const hostline = ref("");
const token = localStorage.getItem("token");
const opentLoading = ref(false);
// =======================update profile==========================
const handleUpdateProfile = async () => {
  opentLoading.value = true;
  try {
    const user = JSON.parse(localStorage.getItem("user"));
    const res = await axios.post(
      "http://localhost/blog/backend/api/userAPI.php",
      {
        action: "updateProfile",
        name: name.value || user.username,
        email: email.value || user.email,
        hostline: hostline.value || user.hostline,
      },
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      },
    );
    console.log(res.data);
    window.location.reload();
    opentLoading.value = false;
  } catch (error) {
    console.log(error);
  }
};
// =========update password==========
const showPassword = ref(false);
const showNewPassword = ref(false);
const passWord = ref(false);
//
const password = ref("");
const newPassword = ref("");
const ConfirmNewpassword = ref("");
const errors = ref({});
const handelUpdatePassword = async () => {
  opentLoading.value = true;
  try {
    const res = await axios.post(
      "http://localhost/blog/backend/api/userAPI.php",

      {
        action: "changePassword",
        password: password.value,
        newPassword: newPassword.value,
        ConfirmNewpassword: ConfirmNewpassword.value,
      },
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      },
    );
    console.log("hien ra cai gi");
    console.log(res.data);
    errors.value = res.data.errors;
    opentLoading.value = false;
  } catch (error) {
    console.log(error);
  }
};
// ======================== lấy dũ liệu ra ========================
const user = ref(null);
const handleGetUser = async () => {
  try {
    const res = await axios.get(
      "http://localhost/blog/backend/api/userAPI.php?action=test",
      {
        headers: {
          Authorization: `Bearer ${token}`,
        },
      },
    );
    user.value = res.data.data;
    user.value.hostline = "0" + user.value.hostline;
    console.log(localStorage.getItem("token"));
    console.log("aaa");
    console.log(user.value);
    console.log(res.data);
  } catch (error) {
    console.log(error);
  }
};

onMounted(() => {
  handleGetUser();
});
// ===logout===========================
const handleLogout = () => {
  localStorage.removeItem("token");

  localStorage.removeItem("user");
  router.push("/login");
};
</script>
