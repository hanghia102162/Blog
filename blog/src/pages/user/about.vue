<template>
  <div
    class="min-h-screen bg-[#FAF3DD] relative overflow-hidden flex items-center justify-center px-6 py-10"
  >
    <div
      class="absolute top-[-100px] left-[-100px] w-[350px] h-[350px] bg-yellow-300 opacity-30 blur-[120px] rounded-full"
    ></div>

    <div
      class="absolute bottom-[-100px] right-[-100px] w-[350px] h-[350px] bg-pink-300 opacity-30 blur-[120px] rounded-full"
    ></div>

    <!--  Container -->
    <div
      class="relative z-10 max-w-7xl w-full grid lg:grid-cols-2 gap-10 items-center"
    >
      <!-- LEFT -->
      <div class="flex justify-center">
        <div
          class="group relative w-[300px] h-[300px] md:w-[420px] md:h-[420px] lg:w-[520px] lg:h-[520px]"
        >
          <div
            class="absolute inset-0 rounded-full bg-gradient-to-r from-yellow-300 to-orange-400 blur-2xl opacity-40 group-hover:opacity-70 transition-all duration-700"
          ></div>

          <div
            class="relative w-full h-full rounded-full overflow-hidden border-[8px] border-white shadow-2xl"
          >
            <img
              src="/img/image2.png"
              alt=""
              class="w-full h-full object-cover transition-all duration-700 group-hover:scale-110 group-hover:rotate-2"
            />
          </div>
        </div>
      </div>

      <!-- right -->
      <div
        class="backdrop-blur-xl bg-white/40 border border-white/30 shadow-2xl rounded-[35px] p-8 lg:p-12 flex flex-col gap-6"
      >
        <div class="space-y-2">
          <p class="uppercase tracking-[5px] text-gray-500 font-medium">
            Welcome To Our Team
          </p>

          <h1 class="text-4xl md:text-5xl lg:text-6xl font-black leading-tight">
            Xin chào 👋 <br />
            Chúng tôi là
            <span
              class="bg-gradient-to-r from-blue-500 to-purple-500 bg-clip-text text-transparent"
            >
              DEV VMU
            </span>
          </h1>
        </div>

        <p class="text-gray-700 leading-[35px] text-[17px] md:text-[18px]">
          Nhóm sinh viên chúng tôi được thành lập với mục tiêu học tập và phát
          triển kỹ năng trong lĩnh vực thiết kế và lập trình web. Mỗi thành viên
          đều đảm nhận những vai trò khác nhau như thiết kế giao diện, xử lý dữ
          liệu và xây dựng các chức năng cho hệ thống.
          <br /><br />
          Trong quá trình thực hiện dự án, nhóm đã vận dụng những kiến thức đã
          học về HTML, CSS, JavaScript cùng các công cụ hỗ trợ khác để tạo nên
          một website hoàn chỉnh và thân thiện với người dùng.
        </p>

        <div class="flex flex-wrap gap-3">
          <span
            class="hover:-translate-y-1/4 hover:shadow-[0_10px_35px_rgba(168,85,247,0.45)] duration-500 transition-all px-5 py-2 rounded-full bg-black text-white font-medium shadow-lg"
          >
            HTML
          </span>

          <span
            class="hover:-translate-y-1/4 hover:shadow-[0_10px_35px_rgba(168,85,247,0.45)] duration-500 transition-all px-5 py-2 rounded-full bg-blue-500 text-white font-medium shadow-lg"
          >
            CSS
          </span>

          <span
            class="hover:-translate-y-1/4 hover:shadow-[0_10px_35px_rgba(168,85,247,0.45)] duration-500 transition-all px-5 py-2 rounded-full bg-yellow-400 text-black font-medium shadow-lg"
          >
            JavaScript
          </span>

          <span
            class="hover:-translate-y-1/4 hover:shadow-[0_10px_35px_rgba(168,85,247,0.45)] duration-500 transition-all px-5 py-2 rounded-full bg-green-500 text-white font-medium shadow-lg"
          >
            VueJS
          </span>

          <span
            class="hover:-translate-y-1/4 hover:shadow-[0_10px_35px_rgba(168,85,247,0.45)] duration-500 transition-all px-5 py-2 rounded-full bg-purple-500 text-white font-medium shadow-lg"
          >
            TailwindCSS
          </span>
          <span
            class="hover:-translate-y-1/4 hover:shadow-[0_10px_35px_rgba(168,85,247,0.45)] duration-500 transition-all px-5 py-2 rounded-full bg-red-500 text-white font-medium shadow-lg"
          >
            Laravel
          </span>
        </div>

        <!-- Social -->
        <div class="flex gap-5">
          <!-- Facebook -->
          <a
            href="https://www.facebook.com/HTN.Dev34?locale=vi_VN"
            target="_blank"
            class="w-[50px] h-[50px] rounded-full bg-white shadow-lg flex justify-center items-center text-[24px] hover:bg-blue-500 hover:text-white hover:-translate-y-2 hover:scale-110 transition-all duration-500"
          >
            <ion-icon name="logo-facebook"></ion-icon>
          </a>

          <!-- TikTok -->
          <a
            href="https://www.tiktok.com/@mewmew17105"
            target="_blank"
            class="w-[50px] h-[50px] rounded-full bg-white shadow-lg flex justify-center items-center text-[24px] hover:bg-black hover:text-white hover:-translate-y-2 hover:scale-110 transition-all duration-500"
          >
            <ion-icon name="logo-tiktok"></ion-icon>
          </a>

          <!-- YouTube -->
          <a
            href=""
            class="w-[50px] h-[50px] rounded-full bg-white shadow-lg flex justify-center items-center text-[24px] hover:bg-red-500 hover:text-white hover:-translate-y-2 hover:scale-110 transition-all duration-500"
          >
            <ion-icon name="logo-youtube"></ion-icon>
          </a>
        </div>

        <!-- Buttons -->
        <div class="flex flex-wrap gap-5 pt-2">
          <button
            class="px-8 py-4 rounded-full bg-gradient-to-r from-blue-500 to-purple-500 text-white font-semibold shadow-xl hover:scale-105 hover:shadow-2xl transition-all duration-500"
          >
            Explore More
          </button>

          <button
            class="px-8 py-4 rounded-full border-2 border-black font-semibold hover:bg-black hover:text-white transition-all duration-500"
          >
            Contact Us
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
