<?php

//cách tạo API
//
//lưu ý: API phải bắt đầu bằng "rest"
//vd: API sẽ phải có đường dẫn như sau: /rest/post
//
//B1: khai báo API trong file /config/webapi.php
//B2: tạo class controller trong thư mục /application/controllers/API
//- tên file viết thường
//- tên class viết hoa chứ cái đầu và giống như đã khai báo trong file webapi.php
//- khai báo các hàm GET, POST, PUT, DELETE để xử lý các request method tương ứng, nếu không khai báo cũng không sao
//- các hàm trên đều nhận vào 1 tham số là $args, tham số này chứ 1 string là string query trong url
//- nếu muốn lấy body cúa request thì dùng $this->body để lấy.
//- file controller phải extend VanillaApiController


// Định dạng: "<đừng dẫn>" => "<tên class controller>"

define("API_LIST", [
    "/rest/test"=> "TestApi"
]);