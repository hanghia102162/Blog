<template>
  <div class="bg-[#FAF3DD] h-screen">
    <div class="bg-[#FAF3DD] min-h-[80%] flex justify-center items-center">
      <div class="grid md:grid-cols-2 grid-cols-1 gap-4">
        <div class="flex justify-center items-center">
          <div
            class="w-[300px] h-[300px] md:w-[400px] md:h-[400px] transition-all duration-500 hover:shadow-[0_0_15px_rgba(255,215,0,0.6)] lg:h-[500px] lg:w-[500px] rounded-full overflow-hidden bg-red-300"
          >
            <img
              src="../img/1.jpg"
              alt=""
              class="w-full h-full rounded-full object-center hover:scale-110 transition-all duration-500"
            />
          </div>
        </div>
        <div
          class="flex flex-col gap-3 justify-center items-center xl:items-start"
        >
          <h2 class="text-3xl font-black">
            Xin chào
            <br />
            Nhóm tôi là DEV VMU
          </h2>
          <p class="lg:w-[65%] w-[85%] text-xl">
            Nhóm sinh viên chúng tôi được thành lập với mục tiêu học tập và phát
            triển kỹ năng trong lĩnh vực thiết kế và lập trình web. Mỗi thành
            viên đều đảm nhận những vai trò khác nhau như xây dựng giao diện, xử
            lý dữ liệu và hoàn thiện chức năng hệ thống. Trong quá trình thực
            hiện dự án, nhóm đã áp dụng các kiến thức đã học về HTML, CSS,
            JavaScript cũng như các công cụ hỗ trợ để tạo ra một website hoàn
            chỉnh. Bên cạnh đó,
          </p>
          <div class="flex gap-3">
            <nav
              class="hover:text-blue-500 hover:scale-110 hover:translate-y-[-5px] transition-all duration-500"
            >
              <ion-icon name="logo-facebook" class="text-[25px]"></ion-icon>
            </nav>
            <nav
              class="hover:scale-110 hover:translate-y-[-5px] transition-all duration-500"
            >
              <ion-icon name="logo-tiktok" class="text-[25px]"></ion-icon>
            </nav>
            <nav
              class="hover:text-red-500 hover:scale-110 hover:translate-y-[-5px] transition-all duration-500"
            >
              <ion-icon name="logo-youtube" class="text-[25px]"></ion-icon>
            </nav>
          </div>
          <button
            class="p-3 rounded-[15px] bg-black text-white duration-500 transition-all font-medium hover:bg-blue-400 hover:text-black hover:-translate-y-1 hover:shadow-lg"
          >
            Hire Me
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
