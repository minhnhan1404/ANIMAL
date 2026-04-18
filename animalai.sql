-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 17, 2026 lúc 05:52 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `animalai`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `ai_detections`
--

CREATE TABLE `ai_detections` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `detected_animal` varchar(255) DEFAULT NULL,
  `confidence` float DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `animals`
--

CREATE TABLE `animals` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `scientific_name` varchar(255) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `habitat` varchar(255) DEFAULT NULL,
  `status` varchar(100) DEFAULT NULL,
  `behavior` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `animal_class` varchar(255) DEFAULT NULL COMMENT 'Lớp',
  `animal_order` varchar(255) DEFAULT NULL COMMENT 'Bộ',
  `animal_family` varchar(255) DEFAULT NULL COMMENT 'Họ',
  `animal_genus` varchar(255) DEFAULT NULL COMMENT 'Chi',
  `diet_type` enum('Ăn cỏ','Ăn thịt','Ăn tạp') DEFAULT 'Ăn tạp' COMMENT 'Chế độ ăn',
  `likes_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `animals`
--

INSERT INTO `animals` (`id`, `name`, `scientific_name`, `category`, `habitat`, `status`, `behavior`, `image_url`, `description`, `image`, `created_at`, `updated_at`, `animal_class`, `animal_order`, `animal_family`, `animal_genus`, `diet_type`, `likes_count`) VALUES
(2, 'Eagle', 'Haliaeetus leucocephalus', 'Chim', NULL, 'Ít lo ngại', NULL, 'uploads/1769324656.jpg', 'Đây là biểu tượng quốc gia của Hoa Kỳ. Chúng có sải cánh cực lớn và thị lực gấp nhiều lần con người, giúp chúng nhìn thấy con mồi từ khoảng cách hơn 1km.', NULL, '2026-01-25 00:04:16', '2026-03-28 21:02:17', 'Chim', 'Ưng', NULL, NULL, 'Ăn thịt', 1),
(5, 'Bear', 'Ursus americanus', 'Thú', NULL, 'Ít lo ngại', NULL, 'uploads/1769329437.jpg', 'Gấu đen Bắc Mỹ là loài gấu phổ biến nhất ở khu vực này, có cơ thể to lớn nhưng lại chạy rất nhanh (tới 40-50 km/h).\r\n\r\nMàu lông phổ biến nhất là đen truyền thống (như trong ảnh bạn gửi), nhưng đôi khi chúng cũng có lông màu nâu hoặc quế tùy vùng sinh sống.\r\n\r\nDù là loài ăn thịt nhưng 85% chế độ ăn của chúng thực chất là thực vật như các loại quả mọng, hạt và chồi non.', NULL, '2026-01-25 01:23:57', '2026-03-25 19:39:46', 'Thú', 'Ăn thịt', NULL, NULL, 'Ăn thịt', 1),
(6, 'Elephant', 'Loxodonta africana', 'Thú', NULL, 'Ít lo ngại', NULL, 'uploads/1769330098.jpg', 'Voi châu Phi có đôi tai lớn hình dáng giống như bản đồ châu Phi, giúp chúng tản nhiệt hiệu quả trong môi trường nắng nóng.\r\n\r\nCả voi đực và voi cái đều có ngà (răng cửa biến dạng), đây là công cụ quan trọng để đào đất tìm nước, bóc vỏ cây và tự vệ.\r\n\r\nHiện nay, loài voi đang đối mặt với nguy cơ tuyệt chủng do tình trạng săn bắn trái phép lấy ngà và mất môi trường sống tự nhiên.', NULL, '2026-01-25 01:34:58', '2026-03-25 20:18:33', 'Động vật có vú', 'Vòi', NULL, NULL, 'Ăn cỏ', 2),
(7, 'Crocodile', 'Alligator mississippiensis', 'Bò sát', NULL, 'Nguy cấp', NULL, 'uploads/1773410792.jpg', 'Cá sấu mõm ngắn Mỹ là một trong hai loài cá sấu mõm ngắn còn tồn tại. Chúng có đặc điểm nhận dạng là mõm rộng hình chữ U, màu da đen sẫm và khi ngậm miệng không lộ răng hàm dưới. Con trưởng thành có thể đạt chiều dài từ 3 đến 4,5 mét. Chúng đóng vai trò quan trọng trong việc duy trì hệ sinh thái đất ngập nước tại Đông Nam Hoa Kỳ.', NULL, '2026-03-13 07:06:32', '2026-03-25 20:16:40', 'Bò sát', 'Cá sấu', NULL, NULL, 'Ăn tạp', 0),
(9, 'Voọc chà vá chân nâu', 'Pygathrix nemaeus', 'Thú', NULL, 'Nguy cấp', 'Đặc điểm: Có bộ lông rực rỡ với 5 màu chủ đạo. Đặc trưng nhất là ống chân có màu đỏ nâu, mặt màu vàng cam với chòm râu trắng muốt dưới cằm.\r\nTập tính: Sống theo đàn từ 4–15 cá thể, chủ yếu hoạt động trên cây cao. Chúng rất hiền lành, ít khi xuống đất và có khả năng chuyền cành cực kỳ điêu luyện. Sinh sản thường diễn ra vào mùa xuân, mỗi lần chỉ đẻ một con.', 'uploads/1774765364.jpg', 'Voọc chà vá chân nâu là loài đặc hữu của khu vực Đông Dương (Việt Nam, Lào và một phần Campuchia). Tại Việt Nam, chúng tập trung nhiều nhất ở bán đảo Sơn Trà (Đà Nẵng). Loài này có vai trò quan trọng trong việc phát tán hạt giống và duy trì sự cân bằng hệ sinh thái rừng nhiệt đới. Do mất môi trường sống và nạn săn bắt, chúng đang được bảo vệ nghiêm ngặt theo pháp luật Việt Nam và quốc tế.', NULL, '2026-03-28 23:22:44', '2026-03-28 23:22:44', 'Thú', 'Linh trưởng', NULL, NULL, 'Ăn cỏ', 2),
(10, 'Cá mập trắng lớn (Great White Shark)', 'Carcharodon carcharias', 'Cá', NULL, 'Sắp nguy cấp', NULL, 'uploads/1775619535.jpg', 'Cá mập trắng lớn là loài cá săn mồi lớn nhất thế giới hiện còn tồn tại. Chúng có hàm răng răng cưa hình tam giác lởm chởm, thường xuyên rơi ra và mọc lại liên tục cấu tạo từ sụn. Chúng đóng vai trò cực kỳ quan trọng trong việc giữ cân bằng hệ sinh thái đại dương bằng cách loại bỏ các cá thể động vật biển yếu, bệnh tật.', NULL, '2026-04-07 20:38:55', '2026-04-07 20:41:06', 'Chondrichthyes (Cá sụn)', 'Cá mập', NULL, NULL, 'Ăn thịt', 0),
(11, 'Cá ngựa (Seahorse)', 'Hippocampus', 'Cá', NULL, 'Sắp nguy cấp', 'Có khả năng ngụy trang tuyệt vời. Điểm đặc biệt nhất là cá ngựa đực mới là người mang thai và sinh con chứ không phải cá mái.', 'uploads/1775619648.jpg', 'Cá ngựa là một loài cá biển độc đáo có đầu giống ngựa, mỏ hình ống mút sương và chiếc đuôi dài có khả năng cuốn chặt vào san hô. Chúng bơi thẳng đứng đi chậm chạp nhờ một vây nhỏ sau bắp lưng. Do môi trường sống rạn san hô bị phá hủy nên số lượng cá ngựa đang giảm sút.', NULL, '2026-04-07 20:40:48', '2026-04-07 20:40:48', 'Actinopterygii (Cá vây tia)', 'Cá xương', NULL, NULL, 'Ăn thịt', 0),
(12, 'Cá vàng (Goldfish)', 'Carassius auratus', 'Cá', NULL, 'Ít lo ngại', 'Hiền lành, sông theo đàn. Chúng có trí nhớ khá tốt (từ 3 đến 5 tháng), trái ngược với lời đồn \"não cá vàng 3 giây\".', 'uploads/1775619798.jpg', 'Được thuần hóa từ cá diếc Phổ cách đây hơn nghìn năm tại Á Đông. Cá vàng có màu sắc sặc sỡ và nhiều vây cực kỳ lộng lẫy, là một trong những loài cá cảnh được nuôi phổ biến nhất trên toàn thế giới.', NULL, '2026-04-07 20:42:49', '2026-04-07 20:46:38', 'Actinopterygii (Cá vây tia)', 'Cá xương', NULL, NULL, 'Ăn tạp', 0),
(13, 'Ếch xanh (Green Frog)', 'Anura', 'Lưỡng cư', NULL, 'Ít lo ngại', 'Có khả năng nhảy cực xa nhờ đôi chân sau phát triển mạnh mẽ. Chúng bắt mồi bằng chiếc lưỡi dài, dính và siêu tốc. Ếch kêu ộp ộp vào ban đêm để gọi bạn tình, đặc biệt là sau những cơn mưa lớn.', 'uploads/1775620252.jpg', 'Ếch là loài động vật lưỡng cư vô cùng phổ biến, sống cả trên cạn lẫn dưới nước. Chúng hô hấp bằng cả phổi thô sơ và qua làn da ẩm ướt của mình. Trải qua một vòng đời kỳ diệu, từ những quả trứng nở thành nòng nọc bơi dưới nước, sau đó rụng đuôi, mọc chân và tiến hóa để lên cạn sinh sống.', NULL, '2026-04-07 20:50:52', '2026-04-07 20:50:52', 'Amphibia (Lưỡng cư)', 'Không đuôi', NULL, NULL, 'Ăn thịt', 0),
(14, 'Bọ rùa (Ladybug)', 'Coccinellidae', 'Côn trùng', NULL, 'Ít lo ngại', 'Khi bị đe dọa, bọ rùa có thể tiết ra một chất dịch có mùi khó chịu từ các khớp chân và giả chết để đánh lừa kẻ thù.', 'uploads/1775620311.jpg', 'Bọ rùa là loài côn trùng nhỏ nhắn, khoác trên mình lớp vỏ cứng hình bán cầu, thường có màu đỏ rực rỡ điểm xuyết những chấm đen đặc trưng. Chúng là \"người bạn tốt của nông dân\" vì thức ăn chính của bọ rùa là rệp sáp và các loại sâu bọ phá hoại mùa màng.', NULL, '2026-04-07 20:51:51', '2026-04-07 20:51:51', 'Insecta (Côn trùng)', 'Cánh cứng', NULL, NULL, 'Ăn tạp', 0),
(15, 'Bướm (Butterfly)', 'Rhopalocera', 'Côn trùng', NULL, 'Ít lo ngại', 'Có thị giác vô cùng xuất sắc để tìm kiếm những bông hoa rực rỡ nhất. Chúng đậu trên hoa và dùng một chiếc vòi dài cuộn tròn dưới đầu để hút mật.', 'uploads/1775620371.jpg', 'Bướm là một kiệt tác sắc màu của thiên nhiên với bốn chiếc cánh được bao phủ bởi hàng triệu lớp vảy phấn siêu nhỏ. Sự sống của bướm đại diện cho quá trình lột xác ngoạn mục trong tự nhiên: từ một trứng nhỏ gọn, nở ra thành con sâu háu ăn, tạo kén hóa nhộng, và cuối cùng phá kén chui ra trở thành một sinh vật bay lượn lộng lẫy.', NULL, '2026-04-07 20:52:51', '2026-04-07 20:52:51', 'Insecta (Côn trùng)', 'Cánh phấn', NULL, NULL, 'Ăn cỏ', 0),
(16, 'Sâu bướm (Caterpillar)', 'Lepidoptera (ấu trùng)', 'Côn trùng', NULL, 'Ít lo ngại', 'Di chuyển bằng cách bò trườn chậm chạp và dành hơn 90% thời gian sống trong giai đoạn này chỉ để ăn rào rào lá non nhằm tích lũy năng lượng cho chu kỳ hóa kén.', 'uploads/1775620427.jpg', 'Sâu bướm chính là giai đoạn ấu trùng của các loài bướm. Chúng có thân hình thuôn dài với nhiều đốt, đôi khi phủ đầy lớp lông gai để ngăn chặn kẻ thù ăn thịt. Mặc dù hay bị coi là kẻ phá hoại cây cối, nhưng đây là một vòng tuần hoàn không thể thiếu để duy trì loài bướm xinh đẹp.', NULL, '2026-04-07 20:53:47', '2026-04-07 21:11:04', 'Insecta (Côn trùng)', 'Cánh phấn', NULL, NULL, 'Ăn cỏ', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `comments`
--

CREATE TABLE `comments` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `post_id` bigint(20) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `post_id`, `content`, `created_at`, `updated_at`) VALUES
(14, 3, 8, 'đẹp nha', '2026-03-07 20:48:48', '2026-03-07 20:48:48');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `detection_history`
--

CREATE TABLE `detection_history` (
  `id` int(11) NOT NULL,
  `user_name` varchar(255) DEFAULT NULL,
  `prediction_result` varchar(255) DEFAULT NULL,
  `confidence` float DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `detection_history`
--

INSERT INTO `detection_history` (`id`, `user_name`, `prediction_result`, `confidence`, `image_path`, `created_at`) VALUES
(1, 'Khách', 'Bear', 0.995383, NULL, '2026-03-15 06:12:51'),
(2, 'Khách', 'Rabbit', 0.716762, NULL, '2026-03-15 07:25:24'),
(3, 'Khách', 'Shark', 1, NULL, '2026-03-15 07:25:38'),
(4, 'Khách', 'Chicken', 0.953422, NULL, '2026-03-15 07:26:22'),
(5, 'Khách', 'Crab', 0.983375, NULL, '2026-03-15 07:26:44'),
(6, 'Khách', 'Crab', 0.983375, NULL, '2026-03-15 07:34:55'),
(7, 'Khách', 'Bear', 0.980881, NULL, '2026-03-30 09:15:56'),
(8, 'Khách', 'Bear', 0.980881, NULL, '2026-03-30 09:20:07'),
(9, 'Khách', 'Shark', 0.99515, NULL, '2026-04-08 03:39:07'),
(10, 'Khách', 'Seahorse', 0.986934, NULL, '2026-04-08 03:41:24'),
(11, 'Khách', 'Goldfish', 0.999856, NULL, '2026-04-08 03:43:25'),
(12, 'Khách', 'Goldfish', 0.999856, NULL, '2026-04-08 03:46:47'),
(13, 'Khách', 'Monkey', 0.999991, NULL, '2026-04-08 04:30:01'),
(14, 'Khách', 'Bear', 0.980881, NULL, '2026-04-08 04:37:59');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `likes`
--

CREATE TABLE `likes` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `post_id` bigint(20) DEFAULT NULL,
  `type` varchar(20) DEFAULT 'like',
  `animal_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `likes`
--

INSERT INTO `likes` (`id`, `user_id`, `post_id`, `type`, `animal_id`, `created_at`) VALUES
(11, 3, NULL, 'like', 6, '2026-03-03 22:26:20'),
(21, 3, 7, 'like', NULL, '2026-03-06 09:21:37'),
(30, 3, 8, 'like', NULL, '2026-03-15 00:30:10'),
(38, 7, NULL, 'like', 9, '2026-03-29 00:05:58'),
(49, 7, 7, 'like', NULL, '2026-04-07 21:15:08'),
(50, 7, 8, 'like', NULL, '2026-04-07 21:18:26');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `posts`
--

CREATE TABLE `posts` (
  `id` bigint(20) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `animal_id` bigint(20) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `image_hash` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `likes_count` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `posts`
--

INSERT INTO `posts` (`id`, `user_id`, `animal_id`, `content`, `image_url`, `image_hash`, `status`, `created_at`, `updated_at`, `likes_count`) VALUES
(7, 3, NULL, 'con bò', 'uploads/posts/1770352292.jpg', NULL, 1, '2026-02-05 21:31:32', '2026-03-03 22:28:39', 2),
(8, 3, NULL, 'ád', 'uploads/posts/1770514502.jpg', NULL, 1, '2026-02-07 18:35:02', '2026-03-06 08:16:57', 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `post_images`
--

CREATE TABLE `post_images` (
  `id` bigint(20) NOT NULL,
  `post_id` bigint(20) DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `reset_code` varchar(10) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `avatar`, `email`, `password`, `reset_code`, `role`, `created_at`, `updated_at`, `is_verified`) VALUES
(3, 'nhan', 'uploads/avatars/1772954450.jpg', 'admin@gmail.com', '$2y$12$zsGEyJ.Nc88KBnsq95iise4nwe1lnaep9UqSRO2YTsl8etymTDx0.', NULL, 'user', '2026-01-12 06:22:55', '2026-03-08 00:20:50', 0),
(6, 'Lê trần minh nhân', 'uploads/avatars/1774756856.jpg', 'minhnhan1442003@gmail.com', '$2y$12$0/52/aC5BaSPS3nW3KiAaekrhmULIXiVdrYOZMypk2AEOlBO8TYEG', NULL, 'user', '2026-03-28 20:06:23', '2026-03-28 21:00:56', 1),
(7, 'admin', 'uploads/avatars/1774755851.jpg', 'animalaidongvat@gmail.com', '$2y$12$VV5/YRIt0kzJRKWkmn5QNebvY/Rtji2tF6KMrtTdr09hTTDZSIjz.', '237566', 'admin', '2026-03-28 20:31:33', '2026-04-07 20:09:38', 1),
(8, 'nhan', NULL, 'minhnhanvip456@gmail.com', '$2y$12$Fw.X9AynWTL3d6KJmMqSquAa1xft90dGWDkREUF1nuXC0TdkaaxCW', NULL, 'user', '2026-03-28 20:59:55', '2026-03-28 21:00:18', 1),
(9, 'nhan', NULL, 'nhan0123@gmail.com', '$2y$12$/fBkBqvM0bk.TRzVwUkoKu3u9qioVdJzWbPc8RoqdFIzoWNRyKAii', NULL, 'user', '2026-04-07 20:19:50', '2026-04-07 20:19:50', 1);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `ai_detections`
--
ALTER TABLE `ai_detections`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Chỉ mục cho bảng `animals`
--
ALTER TABLE `animals`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Chỉ mục cho bảng `detection_history`
--
ALTER TABLE `detection_history`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `likes`
--
ALTER TABLE `likes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`post_id`),
  ADD KEY `post_id` (`post_id`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `animal_id` (`animal_id`);

--
-- Chỉ mục cho bảng `post_images`
--
ALTER TABLE `post_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `post_id` (`post_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `ai_detections`
--
ALTER TABLE `ai_detections`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `animals`
--
ALTER TABLE `animals`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `detection_history`
--
ALTER TABLE `detection_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `likes`
--
ALTER TABLE `likes`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `post_images`
--
ALTER TABLE `post_images`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `ai_detections`
--
ALTER TABLE `ai_detections`
  ADD CONSTRAINT `ai_detections_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`);

--
-- Các ràng buộc cho bảng `likes`
--
ALTER TABLE `likes`
  ADD CONSTRAINT `likes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `likes_ibfk_2` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`);

--
-- Các ràng buộc cho bảng `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `posts_ibfk_2` FOREIGN KEY (`animal_id`) REFERENCES `animals` (`id`);

--
-- Các ràng buộc cho bảng `post_images`
--
ALTER TABLE `post_images`
  ADD CONSTRAINT `post_images_ibfk_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
