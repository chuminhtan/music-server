-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th7 04, 2021 lúc 09:01 AM
-- Phiên bản máy phục vụ: 10.4.14-MariaDB
-- Phiên bản PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `music_server`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `album`
--

CREATE TABLE `album` (
  `AL_ID` int(11) NOT NULL,
  `AR_ID` int(11) NOT NULL,
  `AL_NAME` varchar(100) COLLATE utf8_vietnamese_ci DEFAULT NULL,
  `AL_IMG` varchar(500) COLLATE utf8_vietnamese_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `album_song`
--

CREATE TABLE `album_song` (
  `AL_ID` int(11) NOT NULL,
  `SO_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `artist`
--

CREATE TABLE `artist` (
  `AR_ID` int(11) NOT NULL,
  `AR_NAME` varchar(100) COLLATE utf8_vietnamese_ci DEFAULT NULL,
  `AR_STORY` text COLLATE utf8_vietnamese_ci DEFAULT NULL,
  `AR_IMG` varchar(500) COLLATE utf8_vietnamese_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `artist_song`
--

CREATE TABLE `artist_song` (
  `AR_ID` int(11) NOT NULL,
  `SO_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `collection`
--

CREATE TABLE `collection` (
  `PL_ID` int(11) NOT NULL,
  `SO_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `genre`
--

CREATE TABLE `genre` (
  `GE_ID` int(11) NOT NULL,
  `GE_NAME` varchar(100) COLLATE utf8_vietnamese_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `like_album`
--

CREATE TABLE `like_album` (
  `AL_ID` int(11) NOT NULL,
  `US_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `like_playlist`
--

CREATE TABLE `like_playlist` (
  `PL_ID` int(11) NOT NULL,
  `US_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `like_song`
--

CREATE TABLE `like_song` (
  `SO_ID` int(11) NOT NULL,
  `US_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `playlist`
--

CREATE TABLE `playlist` (
  `PL_ID` int(11) NOT NULL,
  `US_ID` int(11) DEFAULT 0 COMMENT '0: Quản trị tạo\r\nKhác 0 nếu là user có id',
  `PL_NAME` varchar(100) COLLATE utf8_vietnamese_ci DEFAULT NULL,
  `PL_DES` text COLLATE utf8_vietnamese_ci DEFAULT NULL,
  `PL_TYPE` int(11) DEFAULT NULL COMMENT '1: Chủ đề hot\r\n2: top 100\r\n3: Thư thả bên quán quen',
  `PL_IMG` varchar(500) COLLATE utf8_vietnamese_ci DEFAULT NULL COMMENT 'Ảnh Hình Vuông',
  `PL_IMG2` varchar(500) COLLATE utf8_vietnamese_ci DEFAULT NULL COMMENT 'Ảnh Chữ Nhật'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `song`
--

CREATE TABLE `song` (
  `SO_ID` int(11) NOT NULL,
  `GE_ID` int(11) NOT NULL,
  `AL_ID` int(11) DEFAULT NULL,
  `SO_NAME` varchar(100) COLLATE utf8_vietnamese_ci DEFAULT NULL,
  `SO_SRC` varchar(500) COLLATE utf8_vietnamese_ci NOT NULL,
  `SO_IMG` varchar(500) COLLATE utf8_vietnamese_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user`
--

CREATE TABLE `user` (
  `US_ID` int(11) NOT NULL,
  `US_NAME` varchar(100) COLLATE utf8_vietnamese_ci DEFAULT NULL,
  `US_EMAIL` varchar(100) COLLATE utf8_vietnamese_ci DEFAULT NULL,
  `US_PASS` varchar(100) COLLATE utf8_vietnamese_ci DEFAULT NULL,
  `US_TYPE` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_vietnamese_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `album`
--
ALTER TABLE `album`
  ADD PRIMARY KEY (`AL_ID`);

--
-- Chỉ mục cho bảng `artist`
--
ALTER TABLE `artist`
  ADD PRIMARY KEY (`AR_ID`);

--
-- Chỉ mục cho bảng `genre`
--
ALTER TABLE `genre`
  ADD PRIMARY KEY (`GE_ID`);

--
-- Chỉ mục cho bảng `like_album`
--
ALTER TABLE `like_album`
  ADD PRIMARY KEY (`AL_ID`,`US_ID`);

--
-- Chỉ mục cho bảng `like_playlist`
--
ALTER TABLE `like_playlist`
  ADD PRIMARY KEY (`PL_ID`,`US_ID`);

--
-- Chỉ mục cho bảng `like_song`
--
ALTER TABLE `like_song`
  ADD PRIMARY KEY (`SO_ID`,`US_ID`);

--
-- Chỉ mục cho bảng `playlist`
--
ALTER TABLE `playlist`
  ADD PRIMARY KEY (`PL_ID`);

--
-- Chỉ mục cho bảng `song`
--
ALTER TABLE `song`
  ADD PRIMARY KEY (`SO_ID`);

--
-- Chỉ mục cho bảng `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`US_ID`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `album`
--
ALTER TABLE `album`
  MODIFY `AL_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `artist`
--
ALTER TABLE `artist`
  MODIFY `AR_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `genre`
--
ALTER TABLE `genre`
  MODIFY `GE_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `playlist`
--
ALTER TABLE `playlist`
  MODIFY `PL_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `song`
--
ALTER TABLE `song`
  MODIFY `SO_ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `user`
--
ALTER TABLE `user`
  MODIFY `US_ID` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
