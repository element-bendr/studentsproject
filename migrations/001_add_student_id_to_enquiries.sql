-- Migration: Add student_id FK to enquiries table
-- Run this against an existing database that was created before this change.
-- Safe to run multiple times (ADD COLUMN IF NOT EXISTS / IF NOT EXISTS pattern).

ALTER TABLE `enquiries`
  ADD COLUMN IF NOT EXISTS `student_id` INT UNSIGNED DEFAULT NULL AFTER `id`,
  ADD KEY IF NOT EXISTS `idx_enquiries_student` (`student_id`),
  ADD CONSTRAINT IF NOT EXISTS `fk_enquiries_student`
    FOREIGN KEY (`student_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
