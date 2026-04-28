<template>
  <div
    v-for="(PostDetail, index) in PostDetails"
    :key="index"
    class="justify-center min-w-full flex flex-col gap-10 items-center mt-10"
  >
    <div
      class="w-full md:w-[70%] flex flex-col gap-3 justify-center mt-20 items-center"
    >
      <h2 class="text-3xl w-auto md:w-[50%] text-center font-bold">
        {{ PostDetail.Title }}
      </h2>
      <p class="text-gray-600">{{ PostDetail.Meta }}</p>
      <div class="w-full md:w-[900px] md:h-[450px] h-[250px]">
        <img
          src="../img/1.jpg"
          alt=""
          class="w-full h-full object-cover rounded-lg"
        />
      </div>
      <p class="text-gray-600">Published July 30, 2013 by Rio Lam</p>
      <p class="leading-relaxed text-justify md:w-[90%] xl:w-[70%] w-[90%] p-3">
        {{ PostDetail.Content }}
      </p>
    </div>
    <div class="md:w-[70%] w-[90%] flex flex-col gap-3">
      <p class="text-gray-500">Comment This Blog</p>
      <div class="relative flex mt-3 w-full gap-3">
        <textarea
          type="text"
          class="border peer outline-none w-[300px] h-[100px] px-1"
          placeholder=" "
        />
        <label
          for="email"
          class="absolute top-1/2 peer-not-placeholder-shown:top-[-10px] -translate-y-1/2 peer-placeholder-shown:top-1/2 left-1 transition-all duration-500 peer-focus:top-[-10px]"
          >Email</label
        >
      </div>
      <button class="border w-[100px] hover:bg-black hover:text-white">
        Yeahhh!
      </button>
    </div>
    <div class="md:w-[70%] w-[90%] flex flex-col gap-3">
      <h2>Share this:</h2>
      <div class="flex gap-3">
        <nav
          class="hover:text-blue-500 hover:scale-110 hover:translate-y-[-5px] transition-all duration-500"
        >
          <ion-icon name="logo-facebook" class="text-[20px]"></ion-icon>
        </nav>
        <nav
          class="hover:scale-110 hover:translate-y-[-5px] transition-all duration-500"
        >
          <ion-icon name="logo-tiktok" class="text-[20px]"></ion-icon>
        </nav>
        <nav
          class="hover:text-red-500 hover:scale-110 hover:translate-y-[-5px] transition-all duration-500"
        >
          <ion-icon name="logo-youtube" class="text-[20px]"></ion-icon>
        </nav>
      </div>
    </div>
    <div class="md:w-[70%] w-[90%] flex flex-col gap-3">
      <h2 class="font-medium text-xl">Danh sách comment</h2>
      <div class="my-5" v-for="(comment, index) in comMents" :key="index">
        <div class="flex gap-3 items-center">
          <div class="w-[50px] h-[50px] object-center rounded-full">
            <img :src="comment.img" alt="" class="w-full h-full rounded-full" />
          </div>
          <p>{{ comment.time }}</p>
        </div>
        <p>{{ comment.comment }}</p>
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
<script setup>
import { ref } from "vue";
const comMents = [
  {
    img: "../img/1.jpg",
    time: "23p",
    comment: `Biết rồi, biết rồi! – giọng con gái ngang ngang hối hả nói rồi dập
            điện thoại, không kịp để tôi mắng thêm câu nào. Tôi bỏ điện thoại vào
            túi xách, đứng nép vào tường một cửa hiệu bánh gần đó. Mùi bánh thơm
            phức ứa nhè nhẹ trong không khí, nhưng tôi chẳng thấy đói mấy. Bao lâu
            rồi New York nhỉ? Tao đã không nghĩ tất cả những điều này sẽ thành sự
            thật.`,
  },

  {
    img: "../img/1.jpg",
    time: "23p",
    comment: `Biết rồi, biết rồi! – giọng con gái ngang ngang hối hả nói rồi dập
            điện thoại, không kịp để tôi mắng thêm câu nào. Tôi bỏ điện thoại vào
            túi xách, đứng nép vào tường một cửa hiệu bánh gần đó. Mùi bánh thơm
            phức ứa nhè nhẹ trong không khí, nhưng tôi chẳng thấy đói mấy. Bao lâu
            rồi New York nhỉ? Tao đã không nghĩ tất cả những điều này sẽ thành sự
            thật.`,
  },
  {
    img: "../img/1.jpg",
    time: "23p",
    comment: `Biết rồi, biết rồi! – giọng con gái ngang ngang hối hả nói rồi dập
            điện thoại, không kịp để tôi mắng thêm câu nào. Tôi bỏ điện thoại vào
            túi xách, đứng nép vào tường một cửa hiệu bánh gần đó. Mùi bánh thơm
            phức ứa nhè nhẹ trong không khí, nhưng tôi chẳng thấy đói mấy. Bao lâu
            rồi New York nhỉ? Tao đã không nghĩ tất cả những điều này sẽ thành sự
            thật.`,
  },

  {
    img: "../img/1.jpg",
    time: "23p",
    comment: `Biết rồi, biết rồi! – giọng con gái ngang ngang hối hả nói rồi dập
            điện thoại, không kịp để tôi mắng thêm câu nào. Tôi bỏ điện thoại vào
            túi xách, đứng nép vào tường một cửa hiệu bánh gần đó. Mùi bánh thơm
            phức ứa nhè nhẹ trong không khí, nhưng tôi chẳng thấy đói mấy. Bao lâu
            rồi New York nhỉ? Tao đã không nghĩ tất cả những điều này sẽ thành sự
            thật.`,
  },
  {
    img: "../img/1.jpg",
    time: "23p",
    comment: `Biết rồi, biết rồi! – giọng con gái ngang ngang hối hả nói rồi dập
            điện thoại, không kịp để tôi mắng thêm câu nào. Tôi bỏ điện thoại vào
            túi xách, đứng nép vào tường một cửa hiệu bánh gần đó. Mùi bánh thơm
            phức ứa nhè nhẹ trong không khí, nhưng tôi chẳng thấy đói mấy. Bao lâu
            rồi New York nhỉ? Tao đã không nghĩ tất cả những điều này sẽ thành sự
            thật.`,
  },

  {
    img: "../img/1.jpg",
    time: "23p",
    comment: `Biết rồi, biết rồi! – giọng con gái ngang ngang hối hả nói rồi dập
            điện thoại, không kịp để tôi mắng thêm câu nào. Tôi bỏ điện thoại vào
            túi xách, đứng nép vào tường một cửa hiệu bánh gần đó. Mùi bánh thơm
            phức ứa nhè nhẹ trong không khí, nhưng tôi chẳng thấy đói mấy. Bao lâu
            rồi New York nhỉ? Tao đã không nghĩ tất cả những điều này sẽ thành sự
            thật.`,
  },
];
const PostDetails = [
  {
    Title:
      "Và cái chết không thể nào thống trị | And death shall have no dominion – Dylan Thomas",
    Meta: "Published March 18, 2020 by Rio Lam",
    Image: "../img/1.jpg",
    Content: `Đây có thể là một câu chuyện rất dở. Nhưng cứ viết thôi nào.

Chiếc xe Greyhound dừng ngang ở một trạm bên đường. Lạy trời, New York vẫn tởm như thế, chưa có thành phố nào tôi đi ngang qua mà xe đò Grey Hound lại dừng dọc đường thế này. Với tay lấy chiếc backpack trên ngăn hành lý xuống, tôi hít một hơi thật sâu, đưa mắt nhìn ra ngoài cửa sổ xe bus. Những chiếc taxi màu vàng nối đuôi nhau. Những con người hối hả cắm mặt xuống vỉa hè và đi bộ thật nhanh.


Hey, chào New York!

Tôi kiểm tra lại mọi thứ thật cẩn thận. Trong ngăn hành lý. Trong túi nhỏ phía trước. Đã đi vệ sinh. Lần cuối đến New York, tôi nghĩ nơi này không khác gì Dallas, nghĩa là bạn có thể hỏi nhà vệ sinh trong bất kỳ hàng quán nào, kèm theo nụ cười hơi bẽn lẽn một chút. Nhưng trời ạ, người New York làm gì có được thứ hạnh phúc xa xỉ đó. Lần ấy tôi phải đi bộ hơn 10 block, bắt một chuyến tàu điện ngầm, leo cầu thang lên một studio nhỏ xíu với cái bụng ấm ách nước. Muốn khóc!

Xuống khỏi xe bus, tôi lần mò tìm điện thoại trong túi xách.

– Alo! Đến đón tao nhé!

– Mày đang ở đâu? – đầu dây bên kia là giọng con gái hơi ngang ngang một chút, không lẫn vào đâu được.

– Đường 33…ơ, để coi…giữa avenue 7 với 8

– Ok! Tao đang trên subway. Gần tới rồi.

– Nhanh, mập! Đúng ra mày phải có mặt ở đây rồi chứ.

– Biết rồi, biết rồi! – giọng con gái ngang ngang hối hả nói rồi dập điện thoại, không kịp để tôi mắng thêm câu nào.

Tôi bỏ điện thoại vào túi xách, đứng nép vào tường một cửa hiệu bánh gần đó. Mùi bánh thơm phức ứa nhè nhẹ trong không khí, nhưng tôi chẳng thấy đói mấy. Bao lâu rồi New York nhỉ? Tao đã không nghĩ tất cả những điều này sẽ thành sự thật.

Người đến đón tôi là Khanh. Tôi đang cố gắng hình dung cô ấy trong đầu mình. Hẳn đã ốm hơn, ở đây toàn đi bộ thế này mà. Không biết có mang máy ảnh theo không. Lần cuối tôi gặp Khanh là khi cả hai chúng tôi còn ở Dallas. Rồi cô ấy nhận được công việc chụp ảnh gì đấy ở New York. Đêm sau ngày cô ấy nhận email thông báo chính thức, chúng tôi uống hết 28 chai beer các loại. Chụp ảnh thời trang ở New York đã luôn là ước mơ của Khanh, và thề có ông trời, chẳng mấy ai tin rằng một con bé du học sinh Việt Nam lại có thể thực hiện điều đó.  Thật lòng mà nói, tôi cũng không rõ Khanh đã thực hiện điều ấy như thế nào. Thời trang là một thứ xa lạ đối với tôi. Nếu không quen Khanh, tôi thậm chí còn nghĩ đó là một thứ rất phù phiếm và nhảm nhí.

Có những ngày ở Dallas chúng tôi đã khóc. Kiểu như Khanh nói với tôi rằng cô ấy muốn khóc, hoặc tôi nói cho cô ấy biết mình đang cần khóc. Mặc dù thế giới hẳn đã nương nhẹ tay với chúng tôi lắm rồi, bằng chứng là nó đã cho chúng tôi đi du học (“Đi du học là nhà phải giàu lắm!”), có phương tiện đi lại và chưa chết đói; nhưng có những lúc tuyệt vọng tiền xăng ngày mai không biết trông vào đâu, chúng tôi lại tự hỏi vì sao phải theo đuổi ước mơ làm gì?! Tại sao không đơn giản chọn một ngành học thật dễ kiếm việc? Chúng tôi đang đặt tương lai và tài sản của cả gia đình vào tay ai vậy chứ?

Ba Khanh mất khi cô ấy đang học dang dở đại học ở Mỹ. Hôm đó tôi đang đứng tính tiền ở chợ, một kiểu công việc làm chui không quá cực nhọc nhưng thu nhập cũng bèo bọt. Giờ nghỉ trưa nhìn thấy cuộc gọi nhỡ của Khanh, tôi tự nhủ cô ấy lại rủ mình đi chơi. Bực bội gọi điện lại, tôi quát vào máy:

– Hâm à? Đã biết cuối tuần tao đi làm, rủ gì mà rủ hoài…

Đầu dây bên kia, giọng Khanh bình thản một cách kỳ quặc, như thể trên nấc thang cảm xúc, ai đó đã vặn chỉnh xuống mức dưới 0.

– Ba tao mất rồi.

– Ơ…

Sau đó là một quãng im lặng. Tôi không biết nói gì. Người ta thường sẽ nói gì trong những lúc như vậy chứ?

Sau này Khanh kể lại, lúc nghe bác báo tin đó, cô ấy đã khóc thét lên. Tôi luôn tự hỏi Khanh đã vượt qua chuyện này như thế nào. Có những thứ chỉ diễn ra trong đầu của một người, mà ngay cả những người thân cận nhất cũng không thể nhìn thấy. Về phần Khanh, cô ấy chỉ nói rằng không còn cách nào khác ngoài việc làm cho ba của cô tự hào.

Sau cái đêm say xỉn nôn ói như vòi phun trong nhà vệ sinh, tôi không đụng đến một giọt cồn nào nữa. Mãi cho đến ngày tiễn Khanh ở sân bay, tôi uống thêm một chai Blue Moon trộn Dr. Pepper, Khanh là người giữ bản quyền công thức đó. Cô ấy phát minh ra thức uống ấy trong một đêm nhậu khuya ở nhà tôi. Mãi cho đến ngày tôi nhận thư thông báo rằng mình có học bổng thạc sĩ ở Boston, tôi lại uống thêm sáu chai Smirnoff. Đêm đó chúng tôi nhậu qua Skype.

– Thế… lên thăm tao nhé? – Khanh lè nhè trong lúc ôm cây guitar, búng búng sợi dây đàn nốt sol. Tôi biết là cô ấy không bao giờ đàn cho tử tế được. Cũng như tôi chẳng bao giờ thổi harmonica được bài nào khác ngoài “Grandfather’s clock.” Nhưng dù chuyển nhà hơn chục bận, lúc nào tôi cũng giữ cây Hohner Ocean Star bên cạnh. Nó phù phiếm thật đấy, tôi đặt hàng từ hẳn bên Đức, lúc nào cũng khoe khoang là nó hơn hẳn thứ hàng Yamaha 300 ngàn một cây. Điều đó hoàn toàn vớ vẩn và thiển cận, nhưng cũng như cây guitar đối với Khanh, nó nhắc tôi về những điều chưa bao giờ được hoàn thiện, những thứ chúng tôi đã bỏ ngang, nhưng vẫn luôn hy vọng một ngày nào đó sẽ quay trở lại. Một cây harmonica của Đức khiến tôi cảm thấy một phần nào đó trong mình có thể đã trở thành nghệ sĩ thổi harmonica, và một ngày không xa, trước một quảng trường nào đó của nước Ý, tôi sẽ hồn nhiên tấu khúc nhạc réo rắt mua vui miễn phí cho người qua kẻ lại.

– Còn phải nói. Qua đó ổn định nhà cửa xong là lên thăm mày ngay.

– Chị em có nhau thế là tốt.

Nếu tóm tắt quan hệ giữa tôi và Khanh, nó chỉ đơn giản vài dòng thế này. Quen nhau từ cấp III. Không thân lắm. Giữa năm 11, Khanh đi du học và chứng minh sự ngẫu hứng điên cuồng vô trách nhiệm của mình bằng việc nhảy từ Finance sang Marketing sang Design rồi quay ngược lại Marketing, nhưng lại quyết tâm theo đuổi Fashion Photography. Còn tôi, tôi cũng chứng minh sự khó chịu và bảo thủ của mình bằng việc lên Facebook công khai chế giễu quyết định nghệ thuật của Khanh, bởi tôi cho rằng con bé chẳng có chút tài năng gì khả dĩ, nó chỉ đang bị đám bạn bè chụp choẹt này nọ dụ dỗ. Ba năm sau đó chúng tôi không nhìn mặt nhau. Năm 1 Đại học, tôi sang Mỹ, bắt đầu bằng ngành Communications, một thời gian sau lại nghĩ mình đang phí phạm tiền ba mẹ, thế là chuyển sang Accounting hy vọng sau này dễ kiếm việc ở lại, một thời gian sau nhận ra ba mẹ bỏ tiền ra không phải để mình chôn vùi cuộc đời một cách cay đắng với mấy con số, tôi quay ngược lại Communications. Ngày tôi chuyển xuống Dallas, Khanh lái xe qua nhà đón tôi đi chơi như chưa hề có gì xảy ra. Sau đó, mọi thứ cứ như vậy mà thành. Một hai lần, tôi vẫn lặp lại lời xin lỗi đối với Khanh.

Chúng tôi có một ước mơ. Đôi khi chúng tôi lại an ủi nhau, “Thôi ráng! Chả phải ai cũng may mắn mà có ước mơ như tụi mình.”

***

Khanh xuất hiện, tóc dài hơn xưa, lại còn xoăn lọn sóng. Cô ấy đúng là gầy đi nhiều, đủ gầy để mặc skinny jeans và giày converse màu xanh navy mà hai chân trông không giống hai khúc đùi gà. Thời tiết sang thu của New York mát mẻ kiểu thơ thới, và cái áo khoác kiểu quân đội của cô ấy ánh lên một màu xanh lá mạ kỳ quặc. Chúng tôi ôm nhau một cái không chặt lắm. Những lúc thế này, tôi cảm thấy mọi thứ đều không đủ, không chặt, không sâu. Cái sự thật là chúng tôi đang gặp nhau ở New York là một điều quá ngọt ngào và thơm phức mà không một cái bánh donut hay bagel nào có thể diễn tả được. Hãy lướt nhanh nhanh qua phần ôm ấp này thôi nào.

– Đói không? Ta có mua bagel cho mi – Khanh chìa ra một túi giấy màu nâu. – Xe bán bánh ngon nhứt khu Brooklyn đó.

– Mi đang ở Brooklyn hả?

– Ừ. Chừ về nhà ta hĩ? Tắm rửa chi chi miếng đã.

– Ừ. Xa không? – tôi vừa hỏi, vừa đưa tay cảm ơn một chiếc taxi nhường đường cho mình, trước khi kịp nhớ ra, à, đây là New York, làm vậy kỳ lắm.

– Subway một đoạn, thêm mấy block thôi. Tối còn đi đón con bé Anh.

– Sân bay mô rứa?

– Newark.

– À, hắn được nhận internship ở Zappos rồi đó.

-Tao biết rồi – Khanh cười hớn hở – Hắn sẽ chết vì sung sướng trong kho giày ở đó.

Kể ra cũng kì quặc khi Anh bắt đầu ước mơ mở công ty truyền thông quảng cáo của riêng mình từ một hãng giày. Nhưng Zappos không chỉ là một công ty giày; Zappos là một trong những hãng giày tuyệt nhất quả đất, nổi tiếng với huyền thoại tặng không cho nhân viên thử việc 4.000 đô-la để họ nghỉ việc nếu “cảm thấy nơi này không thích hợp với bạn” và yêu tất cả các vị trí từ trực điện thoại đến giám đốc phải học qua một khóa chăm sóc khách hàng. Kì quặc! Tôi có cảm giác là Anh đã bắt đầu ở đúng nơi. Mà nói gì thì nói, xin được internship ở công ty ngon lành như vậy là tốt rồi.

Nếu thế giới có ngày tận thế, tốt nhất nên là hôm nay, vì tôi đang nghĩ New York không thể chịu đựng được tất cả những sự tuyệt vời này. Tôi cũng gần như không thể chịu được nữa.

***

Quãng đường về nhà không đủ dài cho hàng tỉ từ ngữ kiểu con gái và mấy chiếc bánh bagel thực sự ngon. Tôi hít căng một lồng ngực đầy mùi New York, mùi của những bao rác ở trên đường, của những vũng nước, của khói từ nắp cống, mùi của những tiệm Starbucks và những chiếc xe hàng rong, của những viên gạch màu nâu đỏ cũ kỹ và những lề đường dính đầy bã kẹo cao su đen xì. Lần đầu tiên đến New York với người yêu cũ cách đây ba năm, khoảnh khắc rời khỏi New Jersey qua cầu vào thành phố, suy nghĩ đầu tiên hiện ra ngay khi ánh mắt tôi chạm nóc những tòa nhà chọc trời của khu Manhattan là “Đây đúng là nơi của Khanh.”

Lần đầu tiên đến Boston với những hàng cây xanh mướt, bầu không khí hiền hòa đầy mùi sách vở và hẳn một dòng sông, tôi cũng nghĩ như thế, “Đây đúng là nơi của mình.”

Tôi bắt đầu kể cho Khanh nghe về Boston, về nhà cửa đắt đỏ, mọi thứ đều sạch hơn New York. Hẳn một con sông. Nó khiến tôi nhớ đến Đà Nẵng.

– Mi không biết lần đầu đến New York, tao ghét nó như thế nào đâu. – tôi nói với Khanh trong lúc len lỏi trong tàu điện ngầm, ôm khư khư backpack chỉ sợ bị móc túi – Subway chật kín người, dơ bẩn, lộn xộn. Bồi bàn thì thô lỗ.

Khanh gục gặc:

– Ừ thì đó là New York. Mi kể ta nghe hoài mà. Nhưng rốt cuộc mi không ghét nó.

– Ừ, lúc đi khỏi đó, ta cũng thấy nó hay hay. Chắc nhờ mi. Cái cách mà mi yêu New York…

Cái cách mà Khanh yêu New York. Cô ấy yêu New York thậm chí trước cả khi có cơ hội đặt chân đến đó, và sau khi đã đặt chân đến đó, cô ấy nói rằng, “Chỉ cần được thả ra giữa lòng New York là đã thấy vui rồi.” Trong mắt tôi cách đây 4 năm, đó là một thứ tình yêu hoàn toàn vô nghĩa và phù phiếm. Sau đó tôi bắt đầu yêu một anh chàng qua mạng internet. Sau đó nữa, tôi phải lòng Boston ngay từ cái nhìn đầu tiên.`,
  },
];
</script>
