<template>
  <div
    v-if="PostDetail"
    class="justify-center min-w-full flex flex-col gap-10 items-center mt-10"
  >
    <div
      class="w-full md:w-[70%] flex flex-col gap-3 justify-center mt-20 items-center"
    >
      <h2 class="text-3xl w-auto md:w-[50%] text-center font-bold">
        {{ PostDetail.title }}
      </h2>
      <p class="text-gray-600">{{ "Thể loại: " + PostDetail.category_name }}</p>
      <p class="text-gray-600">
        {{ "Tác giả: " + PostDetail.author_name }}
      </p>
      <div class="w-full md:w-[900px] md:h-[450px] h-[250px]">
        <img
          :src="getFirstImage(PostDetail.content)"
          alt=""
          class="w-full h-full object-cover rounded-lg"
        />
      </div>
      <p class="text-gray-600">
        {{ PostDetail.created_at }}
      </p>

      <div
        v-html="PostDetail.content"
        class="blog-content leading-relaxed text-justify md:w-[90%] xl:w-[70%] w-[90%] p-3"
      ></div>
    </div>
    <!--  -->
    <div class="md:w-[70%] w-[90%] flex flex-col gap-3">
      <p class="text-gray-500">Comment This Blog</p>
      <div class="relative flex mt-3 w-full gap-3">
        <textarea
          type="text"
          v-model="comment"
          class="border peer outline-none w-[300px] h-[100px] px-1"
          placeholder=" "
        />
        <label
          for="email"
          class="absolute top-1/2 peer-not-placeholder-shown:top-[-10px] -translate-y-1/2 peer-placeholder-shown:top-1/2 left-1 transition-all duration-500 peer-focus:top-[-10px]"
          >Email</label
        >
      </div>
      <button
        @click="handelComment()"
        class="border w-[100px] hover:bg-black hover:text-white"
      >
        Yeahhh!
      </button>
      <!--  -->
    </div>
    <div class="md:w-[70%] w-[90%] flex flex-col gap-3">
      <h2>Share this:</h2>
      <div class="flex gap-3">
        <nav
          class="hover:text-blue-500 hover:scale-110 hover:translate-y-[-5px] transition-all duration-500"
        >
          <a
            href="https://www.facebook.com/sharer/sharer.php?u=http://localhost:5173/articleDetails/1"
            ><ion-icon name="logo-facebook" class="text-[20px]"></ion-icon
          ></a>
        </nav>
        <nav
          class="hover:scale-110 hover:translate-y-[-5px] transition-all duration-500"
        >
          <a href="https://www.tiktok.com/share/video/123456789">
            <ion-icon name="logo-tiktok" class="text-[20px]"></ion-icon>
          </a>
        </nav>
        <nav
          class="hover:text-red-500 hover:scale-110 hover:translate-y-[-5px] transition-all duration-500"
        >
          <a
            href="https://www.youtube.com/share?url=http://localhost:5173/articleDetails/1"
            ><ion-icon name="logo-youtube" class="text-[20px]"></ion-icon
          ></a>
        </nav>
      </div>
    </div>
    <div class="md:w-[70%] w-[90%] flex flex-col gap-3">
      <h2 class="font-medium text-xl">Danh sách comment</h2>

      <div
        v-for="(comment, index) in comMents"
        :key="index"
        class="my-5 flex flex-col gap-2"
      >
        <div class="flex gap-3 items-center">
          <div
            class="w-[45px] h-[45px] rounded-full overflow-hidden bg-gray-200"
          >
            <img
              :src="
                comment.author_avatar || '/img/anh-vo-danh-avatar-trang-5.jpg'
              "
              alt=""
              class="w-full h-full object-cover"
            />
          </div>

          <div class="flex flex-col">
            <p class="font-medium text-sm">
              {{ comment.author }}
            </p>

            <p class="text-xs text-gray-500">
              {{ comment.created_at }}
            </p>
          </div>
        </div>

        <p class="ml-[58px] text-gray-700">
          {{ comment.content }}
        </p>
      </div>
    </div>
    <div class="mt-10 mb-10 flex gap-3">
      <a href="">1</a>
      <a href="">2</a>
      ...
      <a href="">24</a>
      <a href="">Next</a>
    </div>
  </div>
</template>
<style scoped>
.blog-content :deep(strong) {
  font-weight: 700;
}

.blog-content :deep(b) {
  font-weight: 700;
}
/* :deep la dùng để style các phần tử đc render từ v-html
@media sao lam cho giao diẹn thay đổi theo màn hình */
.blog-content :deep(h1),
.blog-content :deep(h2),
.blog-content :deep(h3) {
  font-weight: 700;
}
.blog-content :deep(img) {
  width: 100%;
  max-width: 900px;
  height: 450px;
  object-fit: cover;
  border-radius: 12px;
  margin: 20px auto;
  display: block;
}

@media (max-width: 1024px) {
  .blog-content :deep(img) {
    width: 100%;
    max-width: 100%;
    height: 350px;
  }
}

@media (max-width: 768px) {
  .blog-content :deep(img) {
    height: 250px;
  }
}
</style>
<script setup>
import axios from "axios";
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";

const route = useRoute();

// const comMents = [
//   {
//     img: "../img/1.jpg",
//     time: "23p",
//     comment: `Biết rồi, biết rồi! – giọng con gái ngang ngang hối hả nói rồi dập
//             điện thoại, không kịp để tôi mắng thêm câu nào. Tôi bỏ điện thoại vào
//             túi xách, đứng nép vào tường một cửa hiệu bánh gần đó. Mùi bánh thơm
//             phức ứa nhè nhẹ trong không khí, nhưng tôi chẳng thấy đói mấy. Bao lâu
//             rồi New York nhỉ? Tao đã không nghĩ tất cả những điều này sẽ thành sự
//             thật.`,
//   },

//   {
//     img: "../img/1.jpg",
//     time: "23p",
//     comment: `Biết rồi, biết rồi! – giọng con gái ngang ngang hối hả nói rồi dập
//             điện thoại, không kịp để tôi mắng thêm câu nào. Tôi bỏ điện thoại vào
//             túi xách, đứng nép vào tường một cửa hiệu bánh gần đó. Mùi bánh thơm
//             phức ứa nhè nhẹ trong không khí, nhưng tôi chẳng thấy đói mấy. Bao lâu
//             rồi New York nhỉ? Tao đã không nghĩ tất cả những điều này sẽ thành sự
//             thật.`,
//   },
//   {
//     img: "../img/1.jpg",
//     time: "23p",
//     comment: `Biết rồi, biết rồi! – giọng con gái ngang ngang hối hả nói rồi dập
//             điện thoại, không kịp để tôi mắng thêm câu nào. Tôi bỏ điện thoại vào
//             túi xách, đứng nép vào tường một cửa hiệu bánh gần đó. Mùi bánh thơm
//             phức ứa nhè nhẹ trong không khí, nhưng tôi chẳng thấy đói mấy. Bao lâu
//             rồi New York nhỉ? Tao đã không nghĩ tất cả những điều này sẽ thành sự
//             thật.`,
//   },

//   {
//     img: "../img/1.jpg",
//     time: "23p",
//     comment: `Biết rồi, biết rồi! – giọng con gái ngang ngang hối hả nói rồi dập
//             điện thoại, không kịp để tôi mắng thêm câu nào. Tôi bỏ điện thoại vào
//             túi xách, đứng nép vào tường một cửa hiệu bánh gần đó. Mùi bánh thơm
//             phức ứa nhè nhẹ trong không khí, nhưng tôi chẳng thấy đói mấy. Bao lâu
//             rồi New York nhỉ? Tao đã không nghĩ tất cả những điều này sẽ thành sự
//             thật.`,
//   },
//   {
//     img: "../img/1.jpg",
//     time: "23p",
//     comment: `Biết rồi, biết rồi! – giọng con gái ngang ngang hối hả nói rồi dập
//             điện thoại, không kịp để tôi mắng thêm câu nào. Tôi bỏ điện thoại vào
//             túi xách, đứng nép vào tường một cửa hiệu bánh gần đó. Mùi bánh thơm
//             phức ứa nhè nhẹ trong không khí, nhưng tôi chẳng thấy đói mấy. Bao lâu
//             rồi New York nhỉ? Tao đã không nghĩ tất cả những điều này sẽ thành sự
//             thật.`,
//   },

//   {
//     img: "../img/1.jpg",
//     time: "23p",
//     comment: `Biết rồi, biết rồi! – giọng con gái ngang ngang hối hả nói rồi dập
//             điện thoại, không kịp để tôi mắng thêm câu nào. Tôi bỏ điện thoại vào
//             túi xách, đứng nép vào tường một cửa hiệu bánh gần đó. Mùi bánh thơm
//             phức ứa nhè nhẹ trong không khí, nhưng tôi chẳng thấy đói mấy. Bao lâu
//             rồi New York nhỉ? Tao đã không nghĩ tất cả những điều này sẽ thành sự
//             thật.`,
//   },
// ];
const PostDetail = ref({});

const getPostDetail = async () => {
  try {
    const res = await axios.get(
      `http://localhost/blog/backend/api/posts.php?id=${route.params.id}`,
    );

    PostDetail.value = res.data.data;
    console.log(PostDetail.value);
    console.log(res.data);
    console.log(PostDetail.value);
  } catch (error) {
    console.log(error);
  }
};
// =======================
const defaultImg = "/img/2.png";

const getFirstImage = (content) => {
  if (!content) return defaultImg;

  const match = content.match(/<img[^>]+src="([^">]+)"/);

  return match ? match[1] : defaultImg;
};
onMounted(() => {
  getPostDetail();
  handleHienTHiComment();
});
// ====================comments===========================
const comMents = ref([]);

const handleHienTHiComment = async () => {
  try {
    const postId = route.params.id;

    const res = await axios.get(
      `http://localhost/blog/backend/api/comments.php?post_id=${postId}`,
    );

    console.log(res.data);
    console.log("postId:", postId);
    if (res.data.success) {
      comMents.value = res.data.data;
    }
    console.log("content", comMents.value);
  } catch (error) {
    console.log(error.response?.data);
  }
};
// =====================comment=====================
const comment = ref("");

const handelComment = async () => {
  if (comment.value === "") {
    alert("vui lòng nhập dữ liêu!");
    return;
  }
  try {
    const token = localStorage.getItem("token");
    const postId = route.params.id;

    const res = await axios.post(
      "http://localhost/blog/backend/api/comments.php",
      {
        post_id: postId,
        content: comment.value,
      },
      {
        headers: {
          Authorization: `Bearer ${token}`,
          "Content-Type": "application/json",
        },
      },
    );

    console.log(res.data);

    if (res.data.success) {
      comment.value = "";
      handleHienTHiComment();
    }
  } catch (error) {
    alert("Hãy đăng nhập trước đã!");
    console.log(error.response?.data);
  }
};
</script>
