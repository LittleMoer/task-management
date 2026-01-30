-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping data for table task-management.migrations: ~1 rows (approximately)
REPLACE INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(1, '0001_01_01_000000_create_users_table', 1),
	(2, '2026_01_30_021309_create_tasks_table', 1);

-- Dumping data for table task-management.tasks: ~1 rows (approximately)
REPLACE INTO `tasks` (`task_id`, `user_id`, `title`, `description`, `status`, `deadline`, `created_by`, `created_at`, `updated_at`) VALUES
	(2, 1, 'tugas 1', 'Mengisi waktu', 'todo', '2026-01-31', 'Mursetyo Ardiyan Nugroho', '2026-01-30 06:19:41', '2026-01-30 06:34:38'),
	(3, 1, 'tugas 2', 'mencari bug', 'in_progress', '2026-02-01', 'Mursetyo Ardiyan Nugroho', '2026-01-30 07:35:00', '2026-01-30 07:35:00'),
	(4, 1, 'tugas 3', 'mendeploy aplikasi', 'done', '2026-01-30', 'Mursetyo Ardiyan Nugroho', '2026-01-30 07:35:14', '2026-01-30 07:35:14');

-- Dumping data for table task-management.users: ~2 rows (approximately)
REPLACE INTO `users` (`user_id`, `name`, `username`, `password`, `created_at`, `updated_at`) VALUES
	(1, 'Mursetyo Ardiyan Nugroho', 'Ardiyan', '$2y$12$y2DviIkRNnPDlVl8oHkXeOxFxTan1nkHJW8X3PUSdaDPh236QUVS6', '2026-01-30 06:06:48', '2026-01-30 06:06:48'),
	(2, 'Mursetyo Ardiyas Nugroho', 'Ardiyas', '$2y$12$JwNaCnqQEy0P1skukDGNzuABBj8TWDJprCT5bTY66EB12Ynvefc.a', '2026-01-30 06:21:16', '2026-01-30 06:21:16');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
