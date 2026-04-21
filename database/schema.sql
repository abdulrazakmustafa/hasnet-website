-- ============================================================
-- Hasnet ICT Solution – Database Schema
-- Run this once on your MySQL server (cPanel phpMyAdmin etc.)
-- ============================================================

CREATE DATABASE IF NOT EXISTS `hasnet_db`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `hasnet_db`;

-- ── Users ────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `users` (
  `id`          INT UNSIGNED     NOT NULL AUTO_INCREMENT,
  `name`        VARCHAR(100)     NOT NULL,
  `email`       VARCHAR(150)     NOT NULL UNIQUE,
  `password`    VARCHAR(255)     NOT NULL,
  `role`        ENUM('super_admin','admin','editor') NOT NULL DEFAULT 'editor',
  `status`      ENUM('active','inactive')            NOT NULL DEFAULT 'active',
  `avatar`      VARCHAR(500)     DEFAULT NULL,
  `last_login`  DATETIME         DEFAULT NULL,
  `created_at`  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Portfolio Items ──────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `portfolio_items` (
  `id`             INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `title`          VARCHAR(255)  NOT NULL,
  `slug`           VARCHAR(255)  NOT NULL UNIQUE,
  `description`    TEXT          DEFAULT NULL,
  `content`        LONGTEXT      DEFAULT NULL,
  `featured_image` VARCHAR(500)  DEFAULT NULL,
  `gallery`        TEXT          DEFAULT NULL COMMENT 'JSON array of image paths',
  `category`       VARCHAR(100)  DEFAULT NULL,
  `tags`           VARCHAR(500)  DEFAULT NULL,
  `client`         VARCHAR(150)  DEFAULT NULL,
  `project_url`    VARCHAR(500)  DEFAULT NULL,
  `completed_date` DATE          DEFAULT NULL,
  `status`         ENUM('published','draft') NOT NULL DEFAULT 'draft',
  `sort_order`     INT           NOT NULL DEFAULT 0,
  `created_by`     INT UNSIGNED  DEFAULT NULL,
  `created_at`     TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_status`   (`status`),
  KEY `idx_category` (`category`),
  CONSTRAINT `fk_portfolio_user` FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Blog Posts ───────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `blog_posts` (
  `id`             INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `title`          VARCHAR(255)  NOT NULL,
  `slug`           VARCHAR(255)  NOT NULL UNIQUE,
  `excerpt`        TEXT          DEFAULT NULL,
  `content`        LONGTEXT      DEFAULT NULL,
  `featured_image` VARCHAR(500)  DEFAULT NULL,
  `category`       VARCHAR(100)  DEFAULT NULL,
  `tags`           VARCHAR(500)  DEFAULT NULL,
  `status`         ENUM('published','draft') NOT NULL DEFAULT 'draft',
  `views`          INT           NOT NULL DEFAULT 0,
  `created_by`     INT UNSIGNED  DEFAULT NULL,
  `published_at`   DATETIME      DEFAULT NULL,
  `created_at`     TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at`     TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_status`   (`status`),
  KEY `idx_category` (`category`),
  CONSTRAINT `fk_blog_user` FOREIGN KEY (`created_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Subscribers ──────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `subscribers` (
  `id`            INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `email`         VARCHAR(150)  DEFAULT NULL,
  `whatsapp`      VARCHAR(30)   DEFAULT NULL,
  `source`        VARCHAR(50)   DEFAULT 'popup' COMMENT 'popup, footer, etc.',
  `ip_address`    VARCHAR(45)   DEFAULT NULL,
  `subscribed_at` TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Website Settings ─────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `settings` (
  `id`            INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `setting_key`   VARCHAR(150)  NOT NULL UNIQUE,
  `setting_value` LONGTEXT      DEFAULT NULL,
  `setting_group` VARCHAR(100)  NOT NULL DEFAULT 'general',
  `updated_at`    TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_group` (`setting_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Media Library ────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `media` (
  `id`          INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `filename`    VARCHAR(255)  NOT NULL,
  `path`        VARCHAR(500)  NOT NULL,
  `mime_type`   VARCHAR(100)  DEFAULT NULL,
  `size`        INT           DEFAULT NULL,
  `alt_text`    VARCHAR(255)  DEFAULT NULL,
  `uploaded_by` INT UNSIGNED  DEFAULT NULL,
  `created_at`  TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_media_user` FOREIGN KEY (`uploaded_by`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Quote Requests ───────────────────────────────────────────
CREATE TABLE IF NOT EXISTS `quote_requests` (
  `id`             INT UNSIGNED  NOT NULL AUTO_INCREMENT,
  `name`           VARCHAR(100)  NOT NULL,
  `email`          VARCHAR(150)  NOT NULL,
  `phone`          VARCHAR(30)   DEFAULT NULL,
  `location`       VARCHAR(150)  DEFAULT NULL,
  `service_option` VARCHAR(150)  DEFAULT NULL,
  `message`        TEXT          DEFAULT NULL,
  `status`         ENUM('new','read','replied') NOT NULL DEFAULT 'new',
  `created_at`     TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ── Seed: Super Admin ────────────────────────────────────────
-- Password: @Dulleycubic1  (bcrypt hash – change after first login)
INSERT IGNORE INTO `users` (`name`, `email`, `password`, `role`, `status`)
VALUES (
  'Abdulrazak Mustafa',
  'abdulrazak.jmus@gmail.com',
  '$2y$12$K8wGf9Y0RmQN2XDv3Lp1BuOxJtZsWnCeH7kVqYiAmFdP6r4gU5oEl',
  'super_admin',
  'active'
);

-- ── Seed: Default Settings ───────────────────────────────────
INSERT IGNORE INTO `settings` (`setting_key`, `setting_value`, `setting_group`) VALUES
('site_name',          'Hasnet ICT Solution',                         'general'),
('site_tagline',       'Driven by Innovation, Powered by Engineering! Enginnovation', 'general'),
('site_email',         'info@hasnet.co.tz',                           'general'),
('site_phone',         '+255 777 019 901',                            'general'),
('site_phone2',        '+255 718 019901',                             'general'),
('site_address',       'Taveta street, Fuoni Road, Zanzibar',         'general'),
('site_address2',      'Muembetanga, Mtendeni Road, Zanzibar',        'general'),
('site_whatsapp',      '+255777019901',                               'general'),
('primary_color',      '#27235f',                                     'appearance'),
('secondary_color',    '#f04f23',                                     'appearance'),
('logo',               'assets/img/logo.png',                         'appearance'),
('logo_white',         'assets/img/logo-white-2.png',                 'appearance'),
('favicon',            'assets/img/favicon.png',                      'appearance'),
('footer_about',       'Hasnet ICT Solution is a leading ICT company in Zanzibar providing innovative technology solutions.', 'footer'),
('subscribe_popup_enabled', '1',                                      'popups'),
('subscribe_popup_delay',   '15',                                     'popups'),
('subscribe_popup_title',   'Stay Updated with Hasnet!',              'popups'),
('subscribe_popup_text',    'Subscribe to get updates about our seasonal discounts and daily offers from Hasnet ICT Solution.', 'popups'),
('social_facebook',    'https://www.facebook.com/hasnet.ict.solution/', 'social'),
('social_instagram',   'https://www.instagram.com/ict.hasnet/',       'social'),
('social_twitter',     'https://x.com/i/flow/login?redirect_after_login=%2FICT_HasNet', 'social'),
('social_linkedin',    'https://tz.linkedin.com/in/hasnet-ict-solution-b80246210', 'social'),
('social_youtube',     'https://www.youtube.com/@hasnetictsolution7334', 'social'),
('social_whatsapp',    'https://wa.me/+255777019901',                 'social');
