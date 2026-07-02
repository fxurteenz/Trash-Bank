-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: database
-- Generation Time: Jul 02, 2026 at 07:21 AM
-- Server version: 12.1.2-MariaDB-ubu2404
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+07:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `waste_bank`
--

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `member_id` int(6) UNSIGNED ZEROFILL NOT NULL,
  `member_personal_id` varchar(20) DEFAULT NULL COMMENT 'รหัสนักศึกษา/อาจารย์',
  `member_name` varchar(50) DEFAULT NULL,
  `member_phone` varchar(20) NOT NULL COMMENT 'ใช้เป็น username',
  `member_password` varchar(255) NOT NULL COMMENT 'hashed',
  `member_email` varchar(100) DEFAULT NULL,
  `faculty_id` int(3) UNSIGNED ZEROFILL DEFAULT NULL,
  `major_id` int(3) UNSIGNED ZEROFILL DEFAULT NULL,
  `role_id` int(2) UNSIGNED ZEROFILL NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`member_id`, `member_personal_id`, `member_name`, `member_phone`, `member_password`, `member_email`, `faculty_id`, `major_id`, `role_id`, `created_at`, `updated_at`) VALUES
(000001, '1309902669455', 'admin', '0816047264', '$2y$12$eHf3/jMRxH9BAfjZHN.G8.yozUERW747FNpQkJACrJakx9Zr9PwqC', 'admin@gogreen.com', 022, NULL, 06, NULL, NULL),
(000044, NULL, 'ศูนย์ใหญ่', '0634122301', '$2y$12$ojRsEtSNEO52vpAfsVkdCO7Jf2HaMse.Dh1./s.dAqGC.Ja.wqNjW', NULL, 022, NULL, 05, '2026-01-02 23:02:14', NULL),
(000046, NULL, 'เจ้าหน้าที่คณะวิทย์', '0912345678', '$2y$12$efplvrv7noSANjwrFtRBg.RPKUNPDnFqnmRjPn66lUj35QwQtKI22', 'staff.sci@gogreen.com', 001, NULL, 04, '2026-01-08 23:07:10', NULL),
(000049, NULL, 'admin@sci', '06142514', '$2y$12$eHf3/jMRxH9BAfjZHN.G8.yozUERW747FNpQkJACrJakx9Zr9PwqC', 'admin.sci@gogreen.com', 022, NULL, 06, '2026-04-22 15:38:31', NULL),
(000149, '660112421001', 'เบ็ญญทิพย์ การณรงค์', '0820737456', '$2y$12$9tLpx3s5n8GxFdkr6JXPPOprQC5spKDNDUzkt3Ig1CZggs6M39DlC', 'mnw1214@gmail.com', 001, 009, 01, '2026-06-08 09:52:20', NULL),
(000150, '660112421002', 'พรพิมล สุทธิมาตร', '0924314208', '$2y$12$AjejPihB3/PqAy1jGqFA1.h3SoghBIwOK5VMGoZbmVuhKew7NgT9O', 'phonphimon1155@gmail.com', 001, 009, 01, '2026-06-08 09:52:55', NULL),
(000151, '660112421003', 'อริศรา พรสันเทียะ', '0936329140', '$2y$12$al0dipS.Km5sTfiQerQ8i.nBkb4fdfDnmtcqIEAZf39tcYNjLJnny', 'uthai261973@gmail.com', 001, 009, 01, '2026-06-08 09:53:07', NULL),
(000152, '660112421004', 'อริษา โจมรัมย์', '0627305843', '$2y$12$WJh1cJMCQOf0zKH.PHAE7ejjgV.2gUoiAvWAaykwhgQdxkfffCc82', 'comraxrisa@gmail.com', 001, 009, 01, '2026-06-08 09:53:10', NULL),
(000153, '680112418026', 'กฤษณะ ประเมินชัย', '0615408736', '$2y$12$gTJD8nzrmORSPOQ7jFGYb.dq8Z2DnuE28GQEqIPs9OP4Sq7o5Bykm', 'armarfc32@gmail.com', 001, 004, 01, '2026-06-08 13:27:23', NULL),
(000156, '680112418029', 'จิระพงษ์ คาดไธสง', '0800689800', '$2y$12$TVbCgIydS0Uc8QqY9KHjCuf6weCFSYkMrQod/Fosl8sluSTUDqJf2', 'jirapong1212549@gmail.com', 001, 004, 01, '2026-06-08 14:52:15', NULL),
(000158, '680112418028', 'ไกรวิชญ์ อินทร์สิลา', '0968589515', '$2y$12$Qr2rPyUbGGjGIyn/uCyqzug4NvcEqwL2ELYxonpF/zNqF2.rKPfse', 'kraiwit.insila@gmail.com', 001, 004, 01, '2026-06-08 17:42:03', NULL),
(000159, '670112421003', 'กุลนันทน์ ชาลี', '0838400622', '$2y$12$6C0gtswwy/sQ781AV7qTLugECovfoqogSA4A4yfIVr.NH5Dg7AnP.', 'kullanan2899@gmail.com', 001, 009, 01, '2026-06-10 10:09:10', NULL),
(000160, '670112421012', 'นิธินันท์ ชัยจิตติจินดาพร', '0637483388', '$2y$12$18/wOSMfsP.tAgskxt1zvusRpKYW5al//uO0xapaV.ru4u36E.7Ry', 'itsninjazaza@gmail.com', 001, 009, 01, '2026-06-10 10:09:17', NULL),
(000161, '670112421007', 'นางสาวปาลิตา ดีศรี', '0947638363', '$2y$12$15TqyZ2Ba6fjlW.ESB8AnOJPKMIlEC4JUpT7DmlJLYcNbq/oW5W2K', 'pta5698@gmail.com', 001, 009, 01, '2026-06-10 10:09:18', NULL),
(000162, '670112421004', 'ธารวิมล เวชรัมย์', '0950519001', '$2y$12$TTAvj3xRsRhxR1dBBIN5WOG/Ch48MfICSZFzNLlkuEFcJbUVuGZe6', 'twechramy@gmail.com', 001, 009, 01, '2026-06-10 10:09:19', NULL),
(000163, '670112421006', 'ปรียานันท์ อวดคร่อง', '0642906354', '$2y$12$qyy3.drSgayECab.gWlnSuOOc3OF9cSgi9mbuiKAO4c78WcYJ8di.', 'preeyanun5500@gmail.com', 001, 009, 01, '2026-06-10 10:09:45', NULL),
(000164, '670112421002', 'กรวรรณ สินธุกุม', '0655211154', '$2y$12$RKxdfdwrZFyFgiO6Oa8UnePlVpOic8HkdNFkxRHTdad0hRtAOUEvW', 'krwrrnsinthukum@gmail.com', 001, 009, 01, '2026-06-10 10:11:12', NULL),
(000165, '670112421010', 'ศิริพรรณ จงใจงาม', '0918827193', '$2y$12$1td225nnBtmNA8vMW3pN1OBw4KkM0grCkruYTKHPva.zQfuUQUla6', 'siriphan726@gmail.com', 001, NULL, 01, '2026-06-10 10:11:18', NULL),
(000166, '670112421008', 'มณฑิรา ภูมิไธสง', '0936600196', '$2y$12$ZtQHbEOzNKCReRkw26gWku9POVy77P4OsmS1dXs87dn3iAPebEN2y', 'monthirapoomthaisong@gmail.com', 001, 009, 01, '2026-06-10 10:11:31', NULL),
(000171, NULL, 'Thippawan Saenkham', '0818799128', '$2y$12$ezk8rzTmm9ORihQcdmm.7.iA3yAAmRFYBL01JFV2Yl3gIGJDckm.i', 'thippawan.sk@bru.ac.th', 001, 001, 02, '2026-06-12 11:06:24', NULL),
(000172, NULL, 'เอกชัย ธีรภัคสิริ ', '0817292240', '$2y$12$hR2vWGB.rOpextkdqtH3TuZ6guAoQQhfBG7SwEgXgGXIttfbwY2Ge', 'akachai.tp@bru.ac.th', 002, 019, 02, '2026-06-12 12:11:08', NULL),
(000173, NULL, 'ธนพัฒน์ จงมีสุข', '0898460950', '$2y$12$Nmu8ayf6Kx6KJW4QwDbigeezhn.JNpF1A.0t6HA.mNtIuiBoG0FkC', 'thanapat.jm@bru.ac.th', 006, 051, 02, '2026-06-12 12:18:28', NULL),
(000174, NULL, 'ธิติ ปัญญาอินทร์', '0883775826', '$2y$12$6gTn1m1ZPdl9la69UpwBTOOgT4//uXSqlNUaQlUhgLWIPIy8LhxIS', 'thiti.py@bru.ac.th', 002, 019, 02, '2026-06-12 12:18:53', NULL),
(000175, NULL, 'พนาสินธุ์ ศรีวิเศษ', '0910168016', '$2y$12$q9ZLTifkAOmhhRAcrIV45.sNoLqRuAfmHJHEXQKdItrdyjcNo8.dG', 'panasinsriviset@gmail.com', 002, 019, 02, '2026-06-12 12:19:06', NULL),
(000176, NULL, 'Phuangphet Rachprakhon', '0813935445', '$2y$12$Ng3yHL8YF17DctLmZZCemuzJWD63qIBY3ayP2ABu37.D/J28FnT3m', 'puangpet.rp@bru.ac.th', 001, 023, 02, '2026-06-12 12:22:01', NULL),
(000177, NULL, 'วิสาข์ แฝงเวียง', '0804867453', '$2y$12$6WVqcw8CD31.LUjgbcPe3uHs2ghwb/qRahw5O5nNCP8NyflJOZnpu', 'jojoplant@gmail.com', 004, 026, 02, '2026-06-12 12:35:34', NULL),
(000178, NULL, 'สมศักดิ์ จีวัฒนา', '0921567841', '$2y$12$fVoVlRmp6WPTnbPoG3jyd.ZiLmzk6l.UMHshBgybdrfOi3vOeqS5u', 'somsak.je@bru.ac.th', 001, 001, 02, '2026-06-12 12:37:19', NULL),
(000179, NULL, 'Wilaiwan ', '0899456180', '$2y$12$6WxiJEI5XVOT/1N.gmUXeutNi7xPlLjmo9T8VivnkD1ydARtqjZ4K', 'Wilaiwan.sr@bru.ac.th', 021, NULL, 02, '2026-06-12 12:57:56', NULL),
(000180, NULL, ' มณีนุช ให้ศิริกุล', '0994741115', '$2y$12$fbnvh6/AUtar8WFwZjpoE.LLGyB5CA2XGg8VmleVDVfwRE0QtbaBa', 'maneenuch2025@gmail.com', 001, 010, 02, '2026-06-12 15:58:15', NULL),
(000181, '660112418021', 'รัฐพล เชี่ยวบัญชี', '0610809347', '$2y$12$v.9ACr5gTtqC28SG1W.hn.P3//fIMMq/VhdH/PGb6o8TtnjoSkzpS', 'nayp55224@gmail.com', 001, 004, 01, '2026-06-14 17:46:45', NULL),
(000182, NULL, 'อาจารย์เปรม อิงคเวชชากุล', '0853373537', '$2y$12$bK85AwpWd3UHFyZ6b/xG1.VezGlt0RYO90JjsGQpH2PCVEK9TwfSi', 'csbongga@gmail.com', 001, 004, 02, '2026-06-15 00:41:48', NULL),
(000183, NULL, 'Natthawut Tananthaisong', '0637620999', '$2y$12$LqNtY6F5uQQz2QohTtOTvu3FPss2Vdv5z20YXYIJZGbx5k46jJwZy', 'natthawut.ta@bru.ac.th', 001, 007, 02, '2026-06-15 12:22:45', NULL),
(000184, NULL, 'วรินทร์พิพัชร  วัชรพงษ์เกษม', '0800599986', '$2y$12$iFy5Ybv/xgV6itKfVg8b.u8sCjD6oCgyawVpnkcGUx/ZCiKYEd/tq', 'ิwarinpiphat.wp@bru.ac.th', 001, 004, 02, '2026-06-15 23:05:29', NULL),
(000185, NULL, 'Sirorat', '0992041155', '$2y$12$8TXTDdgBRWEs9fMClZEOzu2KAblfP1Y95KguEdCL9E9c.d5MmmxTy', 'sirorat.kw@bru.ac.th', 001, 004, 02, '2026-06-15 23:07:31', NULL),
(000186, NULL, 'สุธีรา  สุนทรารักษ์', '0885812766', '$2y$12$9H0HTkvZECGjQVmNGRywhuRknTuNFwL7.sdp.6WzNc/3Bmlcyush.', 'suteera.sr@bru.ac.th', 001, 009, 02, '2026-06-18 11:18:52', NULL),
(000187, '680112480058', 'อมรรัตน์ ทัศนัยนา', '0611285442', '$2y$12$QgqXW/poe97BVSPPiHnpu.UGHI31lfRG2gGPo4nkZMIClF079O0am', 'thatsanaiyana@gmail.com', 001, 010, 01, '2026-06-18 14:26:31', NULL),
(000188, '680112418011', 'ปิยะพงษ์ ใหญ่สมพงษ์', '0972248653', '$2y$12$AqD49RfbH9QHkqB2lt11tOIkEYFAPDOSlJOSsgUr.Ji7os.QlP5Em', 'ovenvon@gmail.com', 001, 004, 01, '2026-06-18 16:29:59', NULL),
(000190, '680112418037', 'ภัทรสวันต์ ศรีทัด', '0123456789', '$2y$12$WOCO/5TeBfgtY9R5UyMl.OkuhtqAOzBbU/.xyuTdumMoC2znO9wU.', 'pattarasawan.sritad@gmail.com', 001, 004, 01, '2026-06-19 14:02:21', '2026-06-30 20:25:30'),
(000191, '670112418041', 'ธัญเทพ สุนทอง', '0805719011', '$2y$12$WxMth4m2BryKxHUCnoE77uo5623CXGc5E56siaR3lVT9ZdsL8QdP2', 'p.m2u10294@gmail.com', 001, 004, 01, '2026-06-22 08:50:48', NULL),
(000192, '670112480022', 'นิธินันท์ ศรีสุดา', '0917834243', '$2y$12$rgfofQYxU/k3phM7oo7pVeIuN8fK7B0QQ7ODLf7LPU/2OE2GesQLi', 'nithinansrisuda@gmail.com', 001, NULL, 01, '2026-06-22 09:38:31', NULL),
(000193, '690112373013', 'Boonserm', '0621457194', '$2y$12$S1Db08mLRqDF4bs6o6zThe/JEeoGDDM9p4LEEOS9lL.7OeR2Iul7u', 'sure27380@gmail.com', 007, 038, 01, '2026-06-22 09:46:33', NULL),
(000194, '670112323013', 'น่ารัก นะจ้ะ', '0981254367', '$2y$12$0IGwxaN9nO/YDRkpFIIlguBjwm7q7LfeRt11gLJvOeh1z6XVppx0u', 'cccccccc@emil.com', 006, NULL, 01, '2026-06-22 10:16:04', NULL),
(000195, '670112323019', 'บุญเพิ่ม บุญน้อย', '0987654321', '$2y$12$mJ9.o.Ge.9yrEnu6DeXi5OxgxvJI/McsRpx550L03dsuHKqwLeBDG', 'cmilll@emil.com', 021, NULL, 01, '2026-06-22 10:17:47', NULL),
(000201, '670112323999', 'นายบังคับ ไม่อยากทำ', '0987655678', '$2y$12$M8g4yJObXfa0shC2O433l.ikrDCrJcbBhnEDXzxUGX4hNxOW20XL2', 'cc@emil.com', 006, NULL, 01, '2026-06-22 10:21:59', NULL),
(000202, '680112321321', 'นาย เพชราวุธ พันธ์บุตร', '0622804828', '$2y$12$ZQ2RMseAW4Gyt1nOQeTrSOIVlmQBBTf1QXYwJ7r/TBcCetEEp7xMG', 'c@emil.com', 006, NULL, 01, '2026-06-22 10:25:00', NULL),
(000203, '679112480010', 'ฐิจิชญา ประสิทธิ์ปัญญากร', '0836814698', '$2y$12$4mTqmx9We0EPoHEmVC40xOKRC3iRUzUrPFbyLAQLz7X0VkoQBAhsS', 'thitichaya2662@gmail.com', 001, 010, 01, '2026-06-22 10:37:04', NULL),
(000204, NULL, 'ทดสอบ ชวนเพื่อน', '0234567891', '$2y$12$7XBquk4rQOyE4qDBuShqf.xJBblsKJXltzbNguJtaegNBu0Kv14XS', NULL, 004, NULL, 01, '2026-06-25 15:40:02', NULL),
(000205, '660112418026', 'ศุภณัฐ มีผิว', '0973389216', '$2y$12$qGqLk/dAjla97AohwiKbe.9FFWbJNx2D97zmOYimrKjBwT/v2SGiG', 'suphanut2589@gmail.com', 001, 004, 01, '2026-06-25 20:05:25', NULL),
(000206, '660112418032', 'Yada Kleebmuang ', '0967189083', '$2y$12$WersCrf2zEP0xu4EgDPL8uP2O2XDSSOGDgZ5DiDcTSXY9LW9B..Yq', 'yada@gmail.com', 001, 004, 01, '2026-06-25 20:18:41', NULL),
(000207, '660112418042', 'นายจีรศักดิ์ เอกจันทึก', '0948294693', '$2y$12$Ujg2rjwz7OqS2CmFV5LgAO1Iw7e7LTydPp4kvVpo2cJBBr9VQ4GEG', 'giresnext@gmail.com', 001, 004, 01, '2026-06-25 20:24:13', NULL),
(000208, '660112418019', 'ภานุพงษ์ ศรีสมศักดิ์', '0973078156', '$2y$12$VD.FsaxSRECTn6ucPMYIgu4gbbXF7LO43ZVj80dsxewvFw321aaNu', 'phanuphong4725@gmail.com', 001, 004, 01, '2026-06-25 22:34:50', NULL),
(000209, '660112418018', 'นายพีรพัฒน์  ตั้งวิชิต', '0800420533', '$2y$12$pOVLhIMCUcqhSaGaQZ.qTOzOEvhvUUmtxEcp.QzIkSuQgXh/1RiZ2', 'phirphathntangwichit832@gmail.com', 001, 004, 01, '2026-06-25 23:20:03', NULL),
(000210, '660112418006', 'ณรงค์ชัย บุตรไทย', '0952081216', '$2y$12$AV1Ozm.FeuJjFB0eGeCUDuewNyyIcOzS.kMAmrOhfwAy1N6sJB1uG', 'narongchai11500@gmail.com', 001, 004, 01, '2026-06-26 09:39:51', NULL),
(000211, '680112204009', 'พีระพงษ์ กอสุวรรณ์', '0637486633', '$2y$12$xNHflqYXJgYjUfqEgKGUY.DaDlLA1H37eCiBP/QFnhXtR4V/246.O', 'mongkom9014@gmail.com', 006, NULL, 01, '2026-06-26 09:52:41', NULL),
(000212, '680112418009', 'นัฐวุฒิ ชุดกลาง', '0823812828', '$2y$12$zXoRcINPyTFlsGEvDs82QuEYNIlOqVCBb55rXVCL1eo7gy087jg3e', 'skycholl2549@gmail.com', 001, 004, 01, '2026-06-26 09:54:15', NULL),
(000213, '660112418027', 'สุชัจจ์ งามฉลาด', '0980207284', '$2y$12$pDMS1/7tjxlnhSv9ScdLcuZ8SIETlGhfgSEu9uwF3ZtrmSH4NHgFy', 'suchatngamchalad@gmail.com', 001, 004, 01, '2026-06-26 09:55:40', NULL),
(000214, '660112418050', 'บัณฑิต พละสุ', '0918342629', '$2y$12$9BnF7rSWDvDTqOgAC6C6eur.qiOTftJUUhigi16RkjuR0ZqA.ZzQ2', 'thebundit368@gmail.com', 001, 004, 01, '2026-06-26 10:06:05', NULL),
(000215, '680112418085', 'นายสุกฤษฏิ์ ลินทอง', '0620383099', '$2y$12$98ccYdJQKa/qW4n3jepJ1e3oSc9lBbVuRPI5H4hS2.MfN29FmHQF6', 'sukritzaasd123@gmail.com', 001, 004, 01, '2026-06-26 10:29:45', NULL),
(000216, '660112418009', 'ธีรภัทร สีดา', '0832791804', '$2y$12$MhaM6aVEvKYB.xCo1A9SkuB9AAXwnKspxzHpIUhyo0gFWOzlqzK8u', 'xenxng26@gmail.com', 001, 004, 01, '2026-06-26 10:31:15', NULL),
(000217, '680112204037', 'dalakubbb', '0855172317', '$2y$12$G6L1mAuYmKQHpgmGCypMiu5uxfQ7.R5EaDvVQhNlc8nH2tN4NK.jq', '18dala04@gmail.com', 006, 050, 01, '2026-06-26 10:50:07', NULL),
(000219, NULL, 'ดรัสวิน วงศ์ปรเมษฐ์', '0857540228', '$2y$12$Korzv25hOPWYov2qUWg/fOsMePlg6VctElfBnhJI.XxpYaCFQe3u.', 'drusawin.vp@bru.ac.th', 001, 004, 02, '2026-06-26 10:59:47', NULL),
(000220, '660112418017', 'Pitak Aroka', '0658968340', '$2y$12$TxglnBXqYTLQiq4dRxy07emvHYxGNY7F1lCt4kOr8gOZwnlccbC7q', 'giog12345p@gmail.com', 001, 004, 01, '2026-06-26 11:17:06', NULL),
(000221, '660112418007', 'ณัฐภัทร พาสมบูรณ์', '0858261809', '$2y$12$LAFqJgUB9ThJu1X0a2W65eF4UYAdDaJQcF/N5ZbNy8ELAMTNM4/lu', '660112418007@live.bru.ac.th', 001, 004, 01, '2026-06-26 12:44:26', NULL),
(000222, '680112418081', 'กัณณิกา จอดทอง', '0836823150', '$2y$12$4pIEmoUDAVatdtCVnb8dR.nz0lIJpQG8DQ6wFjvS5eQvclL7RXxPa', 'kannikacxkthxng@gmail.com', 001, 004, 01, '2026-06-26 12:47:59', NULL),
(000223, '660112418056', 'ภูรัฐเศรษฐ์ สิมงาม', '0986544624', '$2y$12$sL5ZDlgrG8kWxavU0/shDOeAm33bPfCxbXx2.hbrmLk5ku.s6sMRu', 'phuratthasatsimngam@gmail.com', 001, 004, 01, '2026-06-26 15:21:14', NULL),
(000224, '680112418095', 'ทินกร อุตสารัมย์', '0832543940', '$2y$12$hObyvzZL0reCcBGWCkcYx.ier5Cwj0ti3mRrSuIFaXLTWzQDEvXN6', 'tinnakonzazaza@gmail.com', 001, NULL, 01, '2026-06-26 15:29:21', NULL),
(000226, '660112418058', 'รามภัทร โพธิ์งาม', '0960852396', '$2y$12$OQuKsQVb97QVYHJXlgZcuO.SPUIStws9bnJUW0qEpYsIOloHlqj2W', 'rammaphat207@gmail.com', 001, 004, 01, '2026-06-26 15:30:56', NULL),
(000227, '660112418063', 'สิรวิชญ์ สมัคาสมาน', '0927870666', '$2y$12$ZjuaiFaoA8GUbNPDCCJsA.K3MzpZKY3yEx6onrfdtbUxbcJ3hUnsy', 'fod4848@gmail.com', 001, 004, 01, '2026-06-26 15:59:33', NULL),
(000228, '670112418005', 'เฉลิมวงศ์ ศรีมาศ', '0616264388', '$2y$12$ULaxDv5uVCyhpegP4qCUcOF4B6Qidk.TC8IREXLE14ajvrtsKTkYS', 'sunee29sr@gmail.com', 001, 004, 01, '2026-06-26 19:19:31', NULL),
(000229, '670112418050', 'ยุทธ์กรณ์ แจ่นประโคน', '0834042763', '$2y$12$Xyfl62COfRAH0x4ywuqrJOgcYtEHRB/Qrn8Gxe.coRHiWKWWjwlhG', NULL, 001, 004, 01, '2026-06-27 01:10:45', NULL),
(000230, '670112418031', 'สุวรรณา ศรีประชัย', '0824353297', '$2y$12$KylV1W43klnojbAogwcQYOoThHwRXK1jh0G/RR92rntgSEIP43iyu', 'suwannasiprachai@gmail.com', 001, 004, 01, '2026-06-27 12:16:17', NULL),
(000231, '660112418005', 'นาย จิธพนธ์ สอนประเทศ', '0839267122', '$2y$12$ERJ0JnRr7EZQOxHKF.w/S.FP9yYZ8VH4GS17LMwA5KMCZojz2Nt8q', '660112418005@live.bru.ac.th', 001, 004, 01, '2026-06-27 16:34:54', NULL),
(000232, '660112418036', 'ศิริลักษณ์ แพไธสง', '0800077579', '$2y$12$6q5aBxWH3bQCXDRqpAIbpuFILmVhKNGMvgkYBY2oC619tvMaokqk6', '660112418036@live.bru.ac.th', 001, 004, 01, '2026-06-28 09:49:41', NULL),
(000233, '660112418040', 'กิตติพงศ์ เกียนประโคน', '0946590784', '$2y$12$lany2bdukFLNjHVNxe0R6.pCpav8lw4XU.Dfrs3Tt5TlXLqbEZyqS', 'derfi.5042@gmail.com', 001, 004, 01, '2026-06-28 11:18:50', NULL),
(000234, '670112480067', 'นฤมลชาธิพา', '0838738264', '$2y$12$Xr5Zb4.v.rEJJ94/K26VdeDmGzFJMkCiypAPGViOuAAuZA0M0WlMK', 'narimool3350@gmail.com', 001, 010, 01, '2026-06-28 12:47:13', NULL),
(000235, '680112204032', 'วรรณวรี แย้มยืนยงค์', '0832277589', '$2y$12$RWXvYbxAwU6wCixFZZNJxeOFBKBla.BLAvAYmf5omR0c6FHGTNrHC', 'supaphon.creammy@gmail.com', 006, 050, 01, '2026-06-28 13:41:20', NULL),
(000236, '680112204035', 'อลิสา  พันธ์ประภา', '0832188635', '$2y$12$v0OcTmbwfI91gWqcz6/npORfBSUs2quVIegzVNpaMvbK1WWnb8GGG', 'phing0971708088@gmail.com', 006, 050, 01, '2026-06-28 20:14:43', NULL),
(000237, '660112418011', 'ธีรวัฒน์ สวัสดี', '0619239358', '$2y$12$JmA0OEUnSsckEjulXOupt.530aWkiVuV/8//49.SJFzxLWNS0RUnm', 'teerawatsawatdee02062547@gmail.com', 001, 004, 01, '2026-06-29 00:14:34', NULL),
(000238, '660112418074', 'Alissada Pungkamnoi', '0811877917', '$2y$12$N9kGd6aXH52RjOlRMXzlKeO8L4bnH1GkI8.Vg3UN0AVqOvVzj6hOe', 'apungkamnoi7917@gmail.com', 001, 004, 01, '2026-06-29 09:29:13', NULL),
(000239, '660112418041', 'Jakkaphop Sonsee', '0632717213', '$2y$12$Ec/OJ/c5mmUXwbpzohbkJOaTKx0xdgWFp4ohXnswRKaV8fH/1FwAS', 'nott8111@gmail.com', 001, 004, 01, '2026-06-29 09:30:01', NULL),
(000240, '660112418071', 'พิมพ์อร บุญเคลิ้ม', '0931088174', '$2y$12$t46iDdrak3vOgq.Bjo82teJ/30cTEaTZ6HF25XZcnCR8ZIuxLPidC', '660112418071@live.bru.ac.th', 001, 004, 01, '2026-06-29 10:40:29', NULL),
(000241, '680112480012', 'ธนากานต์ อาศัยสุข', '0638272607', '$2y$12$rlL9O4J7oRHGJPkkaFILCeST2pLeb1pLiGc0CpfyFuy31F0VPYh6u', 'thanakanarsaisuk@gmail.com', 001, 010, 01, '2026-06-29 18:42:27', NULL),
(000242, NULL, 'Zagon Bussabong', '0914354359', '$2y$12$cjqsy74fF1c37FWEx0vfluwOz8qbNp6b8y4beZZ2UqOROKSx126pa', 'zagon.bb@bru.ac.th', 001, 001, 02, '2026-06-29 20:39:41', NULL),
(000243, '650112418012', 'TANARAT SUTJAI', '0641742127', '$2y$12$mBba5rk7/rRKNbI5EnwnN.l0DVCbO.83O9sRGCNMosL46G8gZc7q6', '650112418012@bru.ac.th', 001, 004, 01, '2026-06-29 21:15:07', NULL),
(000244, NULL, 'ชาติวุฒิ ธนาจิรันธร', '0619395455', '$2y$12$RfVnFCPf9hqoHzwjP90hwOyycQV5SN82rfCtg30bDSEGHjZBz6LGi', 'chartwut@bru.ac.th', 001, 001, 02, '2026-06-29 21:40:56', NULL),
(000245, '670112480065', 'ธัญญทิพย์ ดัชถุยาวัตร', '0936286386', '$2y$12$K5/1qPT9aHbNo55XSbgTke7C.7ygyo0h/P4wQmQvK.wyFndiKdlp2', NULL, 001, 010, 01, '2026-06-30 10:51:23', NULL),
(000247, '670112480091', 'สุพรรษา ผิวอ่อน', '0811604705', '$2y$12$64D1VwmltHi3dDarv7s74u.wDagungmmdrz8Najt6FCQAWVKYbV6.', 'supansapiwon00@gmail.com', 001, 010, 01, '2026-06-30 10:51:39', NULL),
(000248, '670112480079', 'แพรวา หวังแววกลาง', '0629960466', '$2y$12$5B9sROGSCPO4wDWXrpg0tOG37PpEakYqiCQG/fMo2jHwqcTLtqhRi', 'homeoppo0466ok@gmail.com', 001, 010, 01, '2026-06-30 10:52:01', NULL),
(000249, '670112480082', 'Pimnipha Phitphongphan', '0929718691', '$2y$12$ydcgSTQIxRsPL8va3dNQKe3ahTaHeTOSRKH960pr0OJpGkMPFt5l.', 'meenwannapornn@gmail.com', 001, 010, 01, '2026-06-30 10:52:14', NULL),
(000250, '670112480059', 'ณัฐฐาพร สุรทิตย์', '0614180657', '$2y$12$gaR0goFdmz/qSAvOslY2Zehp/dxQe7lDJG6HtenjG8iBsvtLMmbZ2', NULL, 001, NULL, 01, '2026-06-30 10:52:17', NULL),
(000251, '670112480056', 'จิราภรณ์ เงางาม', '0958176455', '$2y$12$mCtwMJ2Zrrs9BxFWgs6qEehSc90FDhPh8D.IIgb2pN.dXvrLZW2Ti', 'jiraporn11club@gmail.com', 001, NULL, 01, '2026-06-30 10:52:20', NULL),
(000252, '670112480088', 'สุกัญญา บุญครอบ', '0800965157', '$2y$12$efhgGaIRj1KExIGGioTVdOhkEkWBpRSA7MU1V8lkbvNWqSmj/a03u', 'peecha45za@gmail.com', 001, 010, 01, '2026-06-30 10:52:25', NULL),
(000253, '670112480070', 'นิตติยา ศรีธาตุ', '0650324135', '$2y$12$wFVc6CoJiFHYJ7Eoei/9EO/btfTqs2YlweX7AnaYOehn0c/teTYPe', 'nittiyasritat@gmail.com', 001, 010, 01, '2026-06-30 10:52:27', NULL),
(000254, '670112480087', 'สราศิณี เขียวสี', '0944631023', '$2y$12$cTudeSnjjTOcsemKr6X/J.T/o2rEG1SVPIymL46Fbvrd9J24kp4A2', 'sarasinee1959keawseer@gamil.com', 001, 010, 01, '2026-06-30 10:52:45', NULL),
(000255, '670112480093', 'หทัยทิพย์ โอโลรัมย์', '0653328477', '$2y$12$Tt2fQ24pVi6n4dtYdsTV2.B7BcIc3DEBrxi/AnMQIHaqlhSvPZtQy', 'minthailand2511@gmail.com', 001, 010, 01, '2026-06-30 10:52:48', NULL),
(000256, '670112480053', 'กรกนก นิลวิจิตร', '0990217526', '$2y$12$0K4jj50Sqyxg7WQnpLAZ9upU3vEew5t9NZVqSf9tuW6MJtv0pZSzK', 'knxlwicitr@gmail.com', 001, 010, 01, '2026-06-30 10:53:03', NULL),
(000257, '670112480072', 'นางสาวเบญญาภา ทรงพระ', '0981498201', '$2y$12$tfFmpTC1eDB2PxGeQSTzTOxSEjiVuF0DZab1C/fVsmAZrP.PAR0dW', 'benyapa.cp@gmail.com', 001, 010, 01, '2026-06-30 10:53:10', NULL),
(000258, '670112480068', 'นันทิชา ดาดผารัมย์', '0967134270', '$2y$12$UpQNRLdbQyGqMS/SatI.ZOmfEPSSDk1YLBi7zZOSrZZGinvItOOaW', 'nanthichadadpharamy@gmail.com', 001, 010, 01, '2026-06-30 10:53:46', NULL),
(000259, '670112480075', 'พรสุดา รักษาภักดี', '0985875190', '$2y$12$AauWXAaCGeFqNNs54AW5AeCFjRGT0RrpI11gBHMZEDCMco535fOU2', 'Pupae375@gmail.com', 001, 010, 01, '2026-06-30 10:56:13', NULL),
(000260, '670112480073', 'ปรียามาศ  กองอาษา', '0810393709', '$2y$12$kEzaZ57nIweTm71Bu5If/OeKyi/kaOBTBLoqPLgKGPJgPVo8g9Mq.', NULL, 001, NULL, 01, '2026-06-30 11:00:48', NULL),
(000261, '670112480090', 'นางสาวสุทธินี จันทร์นอก', '0931410705', '$2y$12$9fb4AE.xeq9fsMNx8xYf7.QjNYNrfKTAERaPCX3sGNkSxUTDXqVb2', 'suttineejannok@gmail.com', 001, 010, 01, '2026-06-30 11:05:47', NULL),
(000262, '670112480066', 'ธีวรา จันทำ', '0923796427', '$2y$12$X3f5PbjPym.cxxvT9MAIrO8XT6Xeg5eLi374bGaA/vHT3.tgVRPmO', 'bam.theewara728@gmail.com', 001, 010, 01, '2026-06-30 11:15:23', NULL),
(000263, '670112480089', 'สุฐิตา เข็มทอง', '0650713731', '$2y$12$I9ZZokjhANZknTG0eB4g8e2lwrWSeLwPfiCfwvaebvzzkZWegacQ6', 'suthitakhemthong05@gmail.com', 001, 010, 01, '2026-06-30 11:43:27', NULL),
(000264, NULL, 'Koysupa', '0896192261', '$2y$12$ySHUw3uQHeX.qnbWpnW8M.R8tc1XdqzIwk9B0ylnV0thsMthJP./W', 'supaporn.bm@bru.ac.th', 001, 010, 02, '2026-06-30 11:45:07', NULL),
(000265, NULL, 'กิตติศักดิ์ นามวิชา', '0846514749', '$2y$12$4HMxq5sg9p8JwYdD.WT53.JUE3Y69syols.NPEaVOdY2elMNOBZRm', 'kittisak.nv@bru.ac.th', 001, 010, 02, '2026-06-30 11:50:35', NULL),
(000266, NULL, 'ชนัดดา รัตนา', '0812621104', '$2y$12$nWHbC/VFPo8Bw3/QI0leKuXcTEbW8zf8nMrFHRwXFK6cuI6FgZiey', 'chanatda.rn@bru.ac.th', 001, NULL, 02, '2026-06-30 11:58:45', NULL),
(000267, '670112480076', 'พัชราภา เสงี่ยมทรัพย์', '0630517767', '$2y$12$rlbUdPoHVYxQHBNJGJQdWeE1Emf0DJ12D424sX392EJR4icxQR50O', '1821.phanomdim@gmail.com', 001, 010, 01, '2026-06-30 12:13:33', NULL),
(000268, '680112480083', 'Soranun', '0624961208', '$2y$12$LsEJNE6l36jliYdRpYeOied/cJckhQjn0niUBgU0a6P/yPCfRQF/e', 'kagoolooo@gmail.com', 001, 010, 01, '2026-06-30 13:04:46', NULL),
(000269, '680112480076', 'พิจิตตรา สมานใจ', '0936890095', '$2y$12$vqhOIiGhDjbnk/GFQPFXr.LDmQhBrofQJrORsgNgyd0SF4or/tcFm', NULL, 001, 010, 01, '2026-06-30 13:05:03', NULL),
(000270, '680112480082', 'Siriporn', '0825062108', '$2y$12$0hBH.b2v2eb.6sf1cyUX2.pVMWQb7JSyGPYBc5xRETEqx9FbT43zO', 'siri3644632@gmail.com', 001, 010, 01, '2026-06-30 13:05:27', NULL),
(000271, '680112480080', 'ลักษิกา ทำนองดี', '0986721796', '$2y$12$muxUYLs4a7zuat4zpFnqwOs6RPySwc9W2RBG2nwjrPWL6.vWn/L9y', 'laksikanadia@gmail.com', 001, NULL, 01, '2026-06-30 13:06:06', NULL),
(000272, '680112480071', 'นางสาวธัญชนก จัดรัมย์', '0863677302', '$2y$12$SNrLsqjtV8ljpAP9BH7TtOQYuaEkRzPJvqSSz1x8FTKOVHKigfcmS', 'thanchanok.c@gmail.com', 001, 010, 01, '2026-06-30 13:06:17', NULL),
(000273, '680112480072', 'นภสร', '0803053192', '$2y$12$OnTRgJjQBhdPZY40oRRkH.GNEz2306UzC2tVK5D5hUYc4fB2wKYvO', 'Napasorn@gmail.com', 001, 010, 01, '2026-06-30 13:06:19', NULL),
(000274, '670112480011', 'ณัฐชยา คำทะเนตร', '0842429570', '$2y$12$b/8rRB4pkCn.2BNf4nN6YerPtsQwSIzxbCwhAbWaDfoBQdf2CWblm', 'natchayakamta5@gmail.com', 001, 010, 01, '2026-06-30 13:06:59', NULL),
(000275, '670112480028', 'นางสาวพัชรินทร์ กลิ่นกล่อม', '0944120112', '$2y$12$q/mxPfsuHpQ/vaePGDUeI.s/WdnzByQKKJ0HLwgkZc6irPEzgMFtG', 'phatcharin122948@gmail.com', 001, 010, 01, '2026-06-30 13:07:03', NULL),
(000276, '670112480008', 'จารุวรรณ ตั้งมั่น', '0644638975', '$2y$12$DTDiHsVF80UXz74m.79nVu4Rf6n8imofao4u2SNUUIU9RFYBZq04C', 'xafasza@gmail.com', 001, 010, 01, '2026-06-30 13:07:12', NULL),
(000277, '680112480065', 'จุฑาทิพย์ จิตรัมย์', '0803598389', '$2y$12$KNXyd1jrCv77wq6Hqv.XVOsisvBPyr4nIZUC2s9W34lcKZJT6ogxO', 'chitram.2005na@gmail.com', 001, 010, 01, '2026-06-30 13:07:17', NULL),
(000278, '670112480019', 'นัดตยา ญาติทอง', '0652436835', '$2y$12$lqDKVdfkaDsQpbFYPXPHX.EHK72GQi9WGIxDbxSnXc0xwyP1Jx12O', 'nadtaya12345nat@gmail.com', 001, 010, 01, '2026-06-30 13:07:17', NULL),
(000279, '670112480034', 'วรัญญา เผื่อแผ่', '0807496521', '$2y$12$jp/b.IJKrvTaisfMqwDvd.167AYXEex4xG0/Sij82G3o4yof5WZWm', 'opan31184@gmail.com', 001, NULL, 01, '2026-06-30 13:07:22', NULL),
(000280, '680112480067', 'ชนิภา วัชระกวีศิลป์', '0625968803', '$2y$12$0JlutrKk5jVZOKkIVm7U6.F1Hugq9CXfpj/PA6v3fm0GaQFIK3Tp2', NULL, 001, 010, 01, '2026-06-30 13:07:22', NULL),
(000281, '670112480040', 'Sukanlayanee ', '0957784567', '$2y$12$YLwwKIMQp7SBEu4emMacJ.CaYOvu/2iZirrycmqDpJ9juNSAWaoBm', 'kamkam232e@gmail.com', 001, 010, 01, '2026-06-30 13:07:28', NULL),
(000282, '670112480006', 'กุมภา จากรัมย์', '0621609020', '$2y$12$73JR87ZpdKVxAu/dN6/qeeJp5yfV7hTqkj2h9cnL85yyYlKKqh8oe', 'kumphajakram@gmail.com', 001, 010, 01, '2026-06-30 13:07:35', NULL),
(000283, '670112480013', 'ณัฐพร แดนกระโทก', '0932228435', '$2y$12$KX7WdRsc11FtnX1dKqsfbOfkGFa6nfTfw6ghCrEsG5ymOrv/Fx0Cq', '06193@nhp.ac.th', 001, 010, 01, '2026-06-30 13:07:39', NULL),
(000284, '681002480077', 'ภัทรวรินทร์ พรมทองพันธ์', '0621766930', '$2y$12$DuZHWdjJTAW6muVIEV4LIOG1PZ1gXYS1LFU0uYRwQHSbReBgupjgC', 'p8312007@gmail.com', 001, 010, 01, '2026-06-30 13:07:42', NULL),
(000285, '680112480078', 'รริดา บูรณ์เจริญ', '0637519469', '$2y$12$o4ZQAjwM02gn6woCrAsS4u5f1Xas/q4H/olU2Zbz7FitR6rCk6lQG', 'kawinthip280649@gmail.com', 001, 010, 01, '2026-06-30 13:07:48', NULL),
(000286, '680112480064', 'Jarinya Lamthisong ', '0643605143', '$2y$12$/Hb0aCD/3wHxXyZnzv92peU0UnsFKyZLR/L6Qp9ffS08HyGriqHZ2', 'jarinyalam459@gmail.com', 001, 010, 01, '2026-06-30 13:07:49', NULL),
(000287, '680112480086', 'อนันตญา ถิ่นเจริญภานนท์', '0835076913', '$2y$12$jkYcPwwu9iZgvLRuD8gjg.IRCdHkqo9oKZ4sDJGuhlvAwqszsTVg.', 'donut.anantaya16@gmail.com', 001, 010, 01, '2026-06-30 13:07:51', NULL),
(000288, '670112480015', 'ดวงกานดา นราสันต์', '0840424929', '$2y$12$DGVG5u28tCU24gVSjc1YuetUhemWro8oFGHKDiH3ymGpqHc1Bm/MS', '21942@satuk.ac.th', 001, 010, 01, '2026-06-30 13:07:53', NULL),
(000289, '670112480005', 'กชกร ทวยประเสริฐ', '0969171948', '$2y$12$tDDl3ulY6BAYRw5MSw0eMuRRWAnD6WzOZQIIPFu78aajo0qNchJDK', 'kotchakorn2436mm@gmail.com', 001, 010, 01, '2026-06-30 13:08:02', NULL),
(000290, '670112480047', 'LI SAD', '0659019469', '$2y$12$EtARHQ9cs0hhTFUk1/LDFO/N5Y2N0r7q3BTKfzJCRe50xWsdb0yWy', 'amonratnoikerd@gmail.com', 001, 010, 01, '2026-06-30 13:08:03', NULL),
(000291, '680112480061', 'กนกพร พลอยรัมย์', '0969349594', '$2y$12$Kvs6Et9ppy8G4srU4ZU90OpdFirdJzWvMPcGfMJFPRkTSPJhDP476', 'kanokpornployram@gmil.com', 001, 010, 01, '2026-06-30 13:08:11', NULL),
(000292, '670112480012', 'นางสาว ณัฐธิดา ราศีเฟือง', '0636256225', '$2y$12$CrW/WYHDDHc6erlMjuvek.lRINTbb3hpOl8vy691CGnQ1ZfozM33a', 'ppraenattida03@gmail.com', 001, 010, 01, '2026-06-30 13:08:20', NULL),
(000293, '670112480024', 'Papanida Chomram', '0889479496', '$2y$12$EOMnhvGmdRnEe1VRBri/Wef5ljynTrponmr5qZ5y8a1uFTslxkIfG', NULL, 001, 010, 01, '2026-06-30 13:08:25', NULL),
(000294, '680112480081', 'วีรวรรณ พระบุราณเมือง', '0935980119', '$2y$12$xhnSZCHfEn1kluQT3snasuON94Q5kfuzF4vL7z3EPMAxauLo6F4my', 'bowwi250349@gmail.com', 001, 010, 01, '2026-06-30 13:08:26', NULL),
(000295, '680112480074', 'ปริยานุช กัญญาสาย', '0820052504', '$2y$12$qyPpo913nUe1WcMBB7UdoeRDS0NPCbot36h0iAUq8Gp3TDMnTjTni', 'priyanuchkayyasay@gmail.com', 001, 010, 01, '2026-06-30 13:08:30', NULL),
(000296, '670112480046', 'นางสาวอภัสรา  โคตรศรีเมือง', '0631050921', '$2y$12$aDr8FWu8HocEZ1zZL8WVXuooyEj4wL.glbuyvMxAO46o5hLEIF.2C', 'aphatsarakhotsrimueang@gmail.com', 001, 010, 01, '2026-06-30 13:08:33', NULL),
(000297, '680112480087', 'อรจิรา สมใจ', '0972614276', '$2y$12$5RFW3cG0ILYsPg68GN11keGxoGelY7K8.9prv7S43VQo5lF9htNvu', 'xrcirasmci@gmail.com', 001, 010, 01, '2026-06-30 13:08:35', NULL),
(000298, '670112480035', 'วันวิสา', '0985476623', '$2y$12$NOnVjrBZqb1/lyGLPEVvdOEUBxhcibxDjtlBb5/wxRV50OYN5MVE6', 'wanwisahhachanram@gmail.com', 001, 010, 01, '2026-06-30 13:08:40', NULL),
(000299, '670112480041', 'สุทธิดา จันทะยุทธ', '0642017018', '$2y$12$e5YED8TpKaqljuOBs0F7G.IOK6WOKSSMBJjJdqpung2F2v1lxlm86', 'suthida22@gmail.com', 001, 010, 01, '2026-06-30 13:08:46', NULL),
(000300, '670112480044', 'นางสาวสุวภัทร เส็งไทยเเท้ ', '0933742672', '$2y$12$75yZMH8/.P./bPG8CA.U3e90E3xUSZcULK1ITV3XTWJVxIXW8rUaG', 'k.suwapat22@gmail.com', 001, 010, 01, '2026-06-30 13:08:51', NULL),
(000301, '670112480026', 'นางสาวพรชนก การณรงค์', '0835143936', '$2y$12$gtDjxidHqzG5GkpqRqUWpuQLWWybxSPuoIi58K1Qawo78nINfzmz6', 'Parichat22club@gmail.com', 001, 010, 01, '2026-06-30 13:08:54', NULL),
(000302, '680112480075', 'นางสาวปาลิตา เต้นปักษี ', '0972631439', '$2y$12$LuhFoY9Jzg1N.wWm29s9.eAO1bH5ntQK/pKtsZUpk/kWhv..N422.', 'palita291049@gmail.com', 001, 010, 01, '2026-06-30 13:09:01', NULL),
(000303, '670112480002', 'ปัญญา ยินดีชาติ', '0926372755', '$2y$12$4WIAowsJtLFSBbb2f/deheZFu9dgN1B0hd3hmXn.viaq.l74uPrlW', 'panyayindichat@gmail.com', 001, 010, 01, '2026-06-30 13:09:03', NULL),
(000304, '670112480023', 'บุษกร พึ่งสลุด', '0918236450', '$2y$12$C8Ng0gB2zySnoerlyE5sD.BH79CMbD96pPzX31KorinFna/n70sjm', 'budsakonpungsalud@gmail.com', 001, 010, 01, '2026-06-30 13:09:03', NULL),
(000305, '670112480009', 'ชนัญชิดา ผ่าโผน', '0987468167', '$2y$12$mSwGpt.lSMvF/8yr1SD4I..Nj/.xMLgR.jGgYOcIUT6IwTL/EviZa', NULL, 001, 010, 01, '2026-06-30 13:09:12', NULL),
(000306, '680112480073', 'นิลธิดา ยืนยาว', '0958602064', '$2y$12$6QMlsMGChZiK2JkLjgFGcuKlvzA1IgKXpayoXfAp6S1tX/0FYkxoO', 'nilthida11club@gmail.com', 001, 010, 01, '2026-06-30 13:09:17', NULL),
(000307, '680112480063', 'กัลยวรรธน์ แก้วมาตร์', '0936731106', '$2y$12$XWP4INlC9gvOFWrgiBxRE.4rIR4MdS7cSLH010Yg9VryESSNqDweW', 'kanyawat01292550@gmail.com', 001, 010, 01, '2026-06-30 13:09:18', NULL),
(000308, '670112480029', 'นางสาวพีระดา อาสานอก', '0810717812', '$2y$12$eAZGWaqodl1Rg.9Z6AmW9.WRniwjdd3mxXuciaXtJ7xIet9r7/54m', 'peeradaarsanok79@gmail.com', 001, 010, 01, '2026-06-30 13:09:20', NULL),
(000309, '680112480079', 'รัชนีกร บุญค้ำ', '0956465797', '$2y$12$RWaLovjWzpK.PxeUhm1jfOlJre1XbD/hmROt1y6xjwTO8O5zbH102', 'Rat14za@gmail.com', 001, 010, 01, '2026-06-30 13:09:21', NULL),
(000310, '680112480060', 'เจษฎาภรณ์ ปะโนรัมย์', '0801509859', '$2y$12$V1MzXfJ34/pfr5suw.upwu.xufcCKEz69sxpTHJC/tiR2sK4ObgAq', 'jesdapornpanoram@gmail.com', 001, 010, 01, '2026-06-30 13:09:22', NULL),
(000311, '670112480045', 'หนึ่งฤทัย วิลัยริด', '0840430278', '$2y$12$dLNzL3pm36UuHlJipIFyreSxpEDqCjSgJgzb3hJYCcnR8YO/K3xsi', 'hwilayrid6@gmail.com', 001, 010, 01, '2026-06-30 13:09:25', NULL),
(000312, '670112480003', 'พรชัย สานนท์', '0611483917', '$2y$12$X9HuQ.4aifYS/X9wuojY/OSeigeJViu6pYsIIhVN6kc74OeKfUXuO', 'kkwan4563@gmail.com', 001, 010, 01, '2026-06-30 13:09:43', NULL),
(000314, '670112480038', 'ศุภฤดี เที่ยงเหลา', '0962513203', '$2y$12$gw0caNYVRYprW5x22ytYXOvFFfpDWgDv0slwJHRrBrjz1NHlyaDf.', 'chpmpoosupharuedee@gmail.com', 001, 010, 01, '2026-06-30 13:09:52', NULL),
(000315, '680112480066', 'จุรีมาศ วิยาสิงห์', '0621910046', '$2y$12$rxFzaI3dvZmKH/ByLyYT2.ZZtaqfo6U4M/TBQmzQakTyEGl1qSIUO', 'curimaswiyasingh@gmail.com', 001, 010, 01, '2026-06-30 13:10:02', NULL),
(000316, '670112480014', 'ณิชนันทน์ ใจงาม', '0813211564', '$2y$12$KW0sJfC4VwslTQ7E6SlHWeIFwZ.ExFMwHIGqQzgiJ2gBWhgdK.Zn6', 'Nidchanan014@gmail.com', 001, 010, 01, '2026-06-30 13:11:00', NULL),
(000318, '670112480007', 'เกตุศิรินทร์ บูคะธรรม', '0933050271', '$2y$12$FMluSjwQuqvEoNjKWJHLwOPIz1CIUzgugtgdlrkE6vKIsEBbdC4Mq', 'katsirinbukatam@gmail.com', 001, 010, 01, '2026-06-30 13:11:07', NULL),
(000319, '670112480025', 'ปิยะธิดา พูนกลาง', '0836837607', '$2y$12$USOLVgaRXrF12H7M7rGq9uEf8iZGUhs8TWwNsAubBbDxLUMJdSWHS', 'realme.naoun@gmail.com', 001, 010, 01, '2026-06-30 13:11:51', NULL),
(000320, '670112480018', 'นางสาวนภัสนันท์ ทุมสาน', '0821502367', '$2y$12$1wryQlATK77jqyYVmcwrIe0FO377k75mx7Zo12e4PuRwtXds/JUZ.', 'naplussanan@gmil.com', 001, 010, 01, '2026-06-30 13:11:57', NULL),
(000321, '650112502003', 'นาย ชนะชัย วัดจะโป๊ะ', '0626907058', '$2y$12$QnAzPguhIgY.4AvzyeJLFe2VRD4mZIx5jeENVBszQCeXKpVjhLz2e', 'banksoengsang@gmail.com', 010, 043, 01, '2026-06-30 13:12:08', NULL),
(000322, NULL, 'นฤมล ประครองรักษ์', '0868641859', '$2y$12$cRCJr.tOietQ7e/OuIlsD.YlQfzR5cBRU2oKp8bVk1zN6Y7dMgpy.', 'naruemon.pk@bru.ac.th', 001, NULL, 02, '2026-06-30 13:14:12', NULL),
(000323, '680112480062', 'กนิษฐา จะริบรัมย์', '0634488681', '$2y$12$6YWarG3anw1/pUeZnD9tLu9UzNj5zsku29yYDpJhzHhYuGr3pBy8C', 'kanidtajaripram@gamil.com', 001, 010, 01, '2026-06-30 13:15:03', NULL),
(000324, '670112502006', 'ณภัทร ชำนาญดู', '0956123577', '$2y$12$57TtafyvVA45bPx7vhdDnO7xZ/PYZa.Xc7Fn8S/cYK9CmET1YG/Hy', 'nwkchannel52@gmail.com', 010, 043, 01, '2026-06-30 13:21:10', NULL),
(000325, '680112267007', 'ศิริชล เอ็มประโคน', '0638560866', '$2y$12$bs39EdupXkbSNhS0UioTUu8YYEOFXcAeW62dOv0kTfCU8IpMBFzry', 'sirichlxemprakhon90@gmail.com', 001, 025, 01, '2026-06-30 13:23:19', NULL),
(000326, '680112267005', 'นัทธมน พันธุ์ฉลาด', '0928663721', '$2y$12$2XZfF6bqBAqxbA8GjkTA../BWCIu73i3Lbys0QqioCqyx.aGp2gYe', 'nutty25n@gmail.com', 001, 025, 01, '2026-06-30 13:23:41', NULL),
(000327, '680112267012', 'สุวิมล วงค์ณรงค์', '0986702486', '$2y$12$GR95PgGV4U22ygNLvhrizeWaYC67loZzzjVzybjdPWWhLr7EGcwgG', 'suwimolwongnarong@gmail.com', 001, 025, 01, '2026-06-30 13:23:48', NULL),
(000328, '680112267008', 'ศุภนุช ภาพันธ์', '0948353906', '$2y$12$m41dkLIonG9tHG/kpZGAf.3NFOhxwkZt5N7Tn/5yvXgu0gpqNNK0W', 'supanuch99999@gmail.com', 001, NULL, 01, '2026-06-30 13:24:55', NULL),
(000329, '680112267009', 'สวรรยา ปะวันเตา', '0986072152', '$2y$12$vbgWHiZdC9148mRO32PD3.L43E7vRz8rs3GEGrvu3BznOUWUmiJVi', 'beam12345678@gmail.com', 001, 025, 01, '2026-06-30 13:25:35', NULL),
(000330, '640112265002', 'Pongphet Yotphet', '0968081470', '$2y$12$IaWpT/USdrdVqlcgGQv5oelEW6rOHQ6fARg0T/gImoTemF.6JT1F.', 'mag64375@gmail.com', 001, 010, 01, '2026-06-30 13:27:05', NULL),
(000331, '680112267006', 'มณีกานต์วิเศษสัตย์', '0630135591', '$2y$12$KbLV0N3ZH8j7sQBzbnBICO/qN/GfVWS75vzLbDKUN2enW13lc..tq', 'maneekanwisetsat100@gmail.com', 001, 025, 01, '2026-06-30 13:27:27', NULL),
(000332, '680112267013', 'พุทธิณี ลุกลาม', '0650939074', '$2y$12$h9/vyh9OEJZ8Jk1yd8aSCuKkgwMDfd9Yo.cfwMbuBEKHwfi.G8CU.', 'phutthineelooking@gmail.com', 001, 025, 01, '2026-06-30 13:30:11', NULL),
(000333, '680112267010', 'สวิชญา สำเร็จรัมย์', '0935495181', '$2y$12$usVj3gYJeeAaiiicnFKUm.oH5riE3RZF83dr8APeBZY/SuWP/yPRO', 'sawitchayasamretram@gmail.com', 001, 025, 01, '2026-06-30 13:31:13', NULL),
(000334, NULL, 'สิริณี จิรเจษฎา', '0818791112', '$2y$12$hKJOTckytbmtraa8LMIs6uyqo0vMeg5orHkgrjvDjngZWqOYjDfxm', 'sirinee.ym@bru.ac.th', 001, 025, 02, '2026-06-30 13:40:03', NULL),
(000335, '680112267004', 'ญาณิศา อารมรัมย์', '0910788546', '$2y$12$YQyxogEPXJ7KQ5j8hc2/t.ZqudD8lKp/kwEtS0BPuYL6XGXOBRU7i', 'yanisa89188@gmail.com', 001, NULL, 01, '2026-06-30 13:55:38', NULL),
(000336, NULL, 'ประภาพันธ์ ศิริขันธ์แสง', '0996496995', '$2y$12$kNWYainQclogi8GyXDmvVOj5Dm6dJsjvJnLccbONovHBGz0GJZabS', 'prapaparn.sk@bru.ac.th', 001, 025, 02, '2026-06-30 14:52:38', NULL),
(000337, '670112480036', 'วิมลทิพย์ สังข์ทอง', '0957587155', '$2y$12$ORQGThHyJjmWjuXbBS8VaOORZUxgCLeU3V6XuwaMMpFd982Zp1Vay', 'wimonthip27tan@gmail.com', 001, 010, 01, '2026-06-30 15:15:47', NULL),
(000338, NULL, 'วรนุช', '0814229439', '$2y$12$14Ny1hibxxLXQFfysM/a4OGT1YC3EsLov2sUqiaAjEEz6fBjx4B6e', 'earn_cheng@hotmail.com', 001, 025, 02, '2026-06-30 15:38:33', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`member_id`),
  ADD UNIQUE KEY `member_phone_UNIQUE` (`member_phone`),
  ADD UNIQUE KEY `member_student_id` (`member_personal_id`),
  ADD UNIQUE KEY `member_email_UNIQUE` (`member_email`),
  ADD KEY `fk_member_faculty_id` (`faculty_id`),
  ADD KEY `fk_member_role_id` (`role_id`),
  ADD KEY `fk_member_major_id` (`major_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `member_id` int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=339;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
