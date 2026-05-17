<template>
  <div
    class="w-screen relative h-screen flex flex-col justify-center items-center gap-3"
  >
    <Loading v-if="loading" />
    <h1 class="text-blue-500 text-5xl md:text-6xl font-medium">Blog</h1>
    <h2 class="font-normal text-2xl md:text-4xl">
      Đăng nhập với tài khoản BLog
    </h2>

    <h2 class="font-normal text-2xl md:text-4xl">để kết nối dứng dụng Blog</h2>
    <div
      class="relative bg-white p-4 rounded-xl shadow-[0_2px_12px_-4px_rgba(0,0,0,0.2)] p-3"
    >
      <form
        action=""
        @submit.prevent="handleGmail"
        class="flex flex-col gap-3 w-[300px] h-[360px] justify-around items-center"
      >
        <h1 class="font-medium text-2xl">Đăng nhập với mật khẩu</h1>
        <div class="w-full relative">
          <input
            id="otp1"
            type="text"
            placeholder=" "
            v-model="email"
            class="w-full peer p-2 shadow focus:outline-none"
          />
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
            class="size-6 absolute top-1/2 -translate-y-1/2 right-0"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"
            />
          </svg>
          <label
            for="otp1"
            class="absolute left-2 top-1/2 -translate-y-1/2 peer-focus:top-[-10px] peer-not-placeholder-shown:top-[-10px] peer-not-placeholder-shown:text-base transition-all duration-500 ease-linear peer-placeholder-shown:top-1/2 peer-placeholder-shown:-translate-y-1/2 peer-placeholder-shown:text-base"
            >Email</label
          >
          <p
            v-if="message"
            class="left-2 mt-1 absolute text-red-500 text-sm flex items-center gap-1 transition-all duration-100 animate-pulse"
          >
            {{ message }}
          </p>
        </div>
        <button
          class="w-full h-[45px] rounded-xl bg-blue-400 text-white cursor-pointer hover:bg-blue-500"
        >
          Click để nhân OTP
        </button>
        <router-link to="/register">Đăng kí tài khoản</router-link>
        <router-link to="/login">Quay lại</router-link>
      </form>
    </div>
  </div>
</template>
<script setup>
import { ref } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import Loading from "../../components/load.vue";
const loading = ref(false);

const router = useRouter();

const email = ref("");
const message = ref("");

const handleGmail = async () => {
  loading.value = true;

  try {
    const res = await axios.post(
      "http://localhost/blog/backend/api/autherAPI.php",
      {
        action: "email",
        email: email.value,
      },
      {
        withCredentials: true,
        // sảy ra khi gửi kèm session
      },
    );
    if (res.data.success) {
      router.push("/reissue");
      console.log(res.data);
    } else {
      message.value = res.data.message;
    }
  } catch (error) {
    console.log(error);
  } finally {
    loading.value = false;
  }
};
</script>
