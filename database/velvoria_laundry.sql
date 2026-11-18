-- Velvoria Laundry database schema
-- MySQL 8.0+ / MariaDB compatible. Schema matches app/Database/Migrations.
CREATE DATABASE IF NOT EXISTS velvoria_laundry CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE velvoria_laundry;
SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE IF NOT EXISTS users (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(150) NOT NULL,
  phone VARCHAR(25) NULL,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('customer','admin','staff') NOT NULL DEFAULT 'customer',
  status ENUM('active','inactive','blocked') NOT NULL DEFAULT 'active',
  email_verified_at DATETIME NULL,
  avatar VARCHAR(255) NULL,
  created_at DATETIME NULL,
  updated_at DATETIME NULL,
  deleted_at DATETIME NULL,
  PRIMARY KEY (id), UNIQUE KEY uq_users_email (email), KEY idx_users_phone (phone)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS customer_addresses (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  label VARCHAR(50) NOT NULL,
  recipient_name VARCHAR(120) NOT NULL,
  phone VARCHAR(25) NOT NULL,
  address TEXT NOT NULL,
  district VARCHAR(100) NULL,
  city VARCHAR(100) NULL,
  postal_code VARCHAR(10) NULL,
  notes TEXT NULL,
  is_primary TINYINT(1) NOT NULL DEFAULT 0,
  created_at DATETIME NULL,
  updated_at DATETIME NULL,
  PRIMARY KEY (id), KEY idx_addresses_user (user_id),
  CONSTRAINT fk_addresses_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS service_categories (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  slug VARCHAR(120) NOT NULL,
  description TEXT NULL,
  sort_order INT NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NULL,
  updated_at DATETIME NULL,
  PRIMARY KEY (id), UNIQUE KEY uq_categories_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS services (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  category_id BIGINT UNSIGNED NOT NULL,
  code VARCHAR(30) NOT NULL,
  name VARCHAR(150) NOT NULL,
  description TEXT NULL,
  unit ENUM('kg','pcs','pasang') NOT NULL DEFAULT 'kg',
  price BIGINT UNSIGNED NOT NULL,
  minimum_quantity DECIMAL(10,2) NOT NULL DEFAULT 1.00,
  estimated_hours INT NOT NULL DEFAULT 48,
  is_express TINYINT(1) NOT NULL DEFAULT 0,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  image VARCHAR(255) NULL,
  sort_order INT NOT NULL DEFAULT 0,
  created_at DATETIME NULL,
  updated_at DATETIME NULL,
  PRIMARY KEY (id), UNIQUE KEY uq_services_code (code), KEY idx_services_category_active (category_id,is_active),
  CONSTRAINT fk_services_category FOREIGN KEY (category_id) REFERENCES service_categories(id) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS orders (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  order_number VARCHAR(40) NOT NULL,
  user_id BIGINT UNSIGNED NOT NULL,
  address_id BIGINT UNSIGNED NULL,
  handover_method ENUM('drop_off','pickup_delivery') NOT NULL DEFAULT 'drop_off',
  pickup_schedule DATETIME NULL,
  delivery_schedule DATETIME NULL,
  estimated_weight DECIMAL(10,2) NULL,
  actual_weight DECIMAL(10,2) NULL,
  subtotal BIGINT UNSIGNED NOT NULL DEFAULT 0,
  discount_amount BIGINT UNSIGNED NOT NULL DEFAULT 0,
  delivery_fee BIGINT UNSIGNED NOT NULL DEFAULT 0,
  additional_fee BIGINT UNSIGNED NOT NULL DEFAULT 0,
  grand_total BIGINT UNSIGNED NOT NULL DEFAULT 0,
  promo_code VARCHAR(40) NULL,
  order_status VARCHAR(30) NOT NULL DEFAULT 'awaiting_payment',
  payment_status VARCHAR(30) NOT NULL DEFAULT 'unpaid',
  customer_note TEXT NULL,
  internal_note TEXT NULL,
  cancel_reason TEXT NULL,
  created_at DATETIME NULL,
  updated_at DATETIME NULL,
  completed_at DATETIME NULL,
  PRIMARY KEY (id), UNIQUE KEY uq_orders_number (order_number),
  KEY idx_orders_user_created (user_id,created_at), KEY idx_orders_status_created (order_status,created_at), KEY idx_orders_payment_created (payment_status,created_at),
  CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE RESTRICT,
  CONSTRAINT fk_orders_address FOREIGN KEY (address_id) REFERENCES customer_addresses(id) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS order_items (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  order_id BIGINT UNSIGNED NOT NULL,
  service_id BIGINT UNSIGNED NULL,
  service_code_snapshot VARCHAR(30) NOT NULL,
  service_name_snapshot VARCHAR(150) NOT NULL,
  unit_snapshot VARCHAR(20) NOT NULL,
  unit_price BIGINT UNSIGNED NOT NULL,
  quantity DECIMAL(10,2) NOT NULL,
  line_total BIGINT UNSIGNED NOT NULL,
  created_at DATETIME NULL,
  updated_at DATETIME NULL,
  PRIMARY KEY (id), KEY idx_order_items_order (order_id),
  CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_order_items_service FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS payments (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  order_id BIGINT UNSIGNED NOT NULL,
  provider VARCHAR(30) NOT NULL DEFAULT 'midtrans',
  provider_order_id VARCHAR(60) NOT NULL,
  transaction_id VARCHAR(100) NULL,
  payment_type VARCHAR(50) NULL,
  snap_token VARCHAR(255) NULL,
  gross_amount BIGINT UNSIGNED NOT NULL,
  transaction_status VARCHAR(40) NULL,
  fraud_status VARCHAR(40) NULL,
  payment_status VARCHAR(30) NOT NULL DEFAULT 'unpaid',
  paid_at DATETIME NULL,
  created_at DATETIME NULL,
  updated_at DATETIME NULL,
  PRIMARY KEY (id), UNIQUE KEY uq_payments_provider_order (provider_order_id), KEY idx_payments_transaction (transaction_id), KEY idx_payments_order (order_id),
  CONSTRAINT fk_payments_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS payment_notifications (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  payment_id BIGINT UNSIGNED NULL,
  provider_order_id VARCHAR(60) NOT NULL,
  notification_hash VARCHAR(128) NOT NULL,
  event_status VARCHAR(40) NOT NULL,
  payload_json JSON NULL,
  processed_at DATETIME NULL,
  processing_result TEXT NULL,
  created_at DATETIME NULL,
  PRIMARY KEY (id), UNIQUE KEY uq_payment_notifications_hash (notification_hash), KEY idx_payment_notifications_payment (payment_id),
  CONSTRAINT fk_payment_notifications_payment FOREIGN KEY (payment_id) REFERENCES payments(id) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS order_status_histories (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  order_id BIGINT UNSIGNED NOT NULL,
  old_status VARCHAR(30) NULL,
  new_status VARCHAR(30) NOT NULL,
  changed_by BIGINT UNSIGNED NULL,
  note TEXT NULL,
  created_at DATETIME NULL,
  PRIMARY KEY (id), KEY idx_order_status_histories_order (order_id),
  CONSTRAINT fk_order_histories_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_order_histories_user FOREIGN KEY (changed_by) REFERENCES users(id) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS promotions (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  code VARCHAR(40) NOT NULL,
  name VARCHAR(120) NOT NULL,
  discount_type ENUM('fixed','percent') NOT NULL DEFAULT 'fixed',
  discount_value BIGINT UNSIGNED NOT NULL,
  minimum_order BIGINT UNSIGNED NOT NULL DEFAULT 0,
  maximum_discount BIGINT UNSIGNED NULL,
  usage_limit INT NULL,
  usage_per_user INT NULL,
  starts_at DATETIME NOT NULL,
  ends_at DATETIME NOT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NULL,
  updated_at DATETIME NULL,
  PRIMARY KEY (id), UNIQUE KEY uq_promotions_code (code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS promotion_usages (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  promotion_id BIGINT UNSIGNED NOT NULL,
  user_id BIGINT UNSIGNED NOT NULL,
  order_id BIGINT UNSIGNED NOT NULL,
  discount_applied BIGINT UNSIGNED NOT NULL,
  created_at DATETIME NULL,
  PRIMARY KEY (id), KEY idx_promo_usage_promo_user (promotion_id,user_id), KEY idx_promo_usage_order (order_id),
  CONSTRAINT fk_promo_usage_promo FOREIGN KEY (promotion_id) REFERENCES promotions(id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_promo_usage_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_promo_usage_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS store_settings (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  setting_key VARCHAR(100) NOT NULL,
  setting_value TEXT NULL,
  updated_at DATETIME NULL,
  PRIMARY KEY (id), UNIQUE KEY uq_store_settings_key (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS admin_activity_logs (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NULL,
  action VARCHAR(100) NOT NULL,
  entity_type VARCHAR(80) NULL,
  entity_id BIGINT UNSIGNED NULL,
  description TEXT NULL,
  ip_address VARCHAR(45) NULL,
  created_at DATETIME NULL,
  PRIMARY KEY (id), KEY idx_activity_user_created (user_id,created_at),
  CONSTRAINT fk_activity_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL ON UPDATE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS ci_sessions (
  id VARCHAR(128) NOT NULL,
  ip_address VARCHAR(45) NOT NULL,
  timestamp INT UNSIGNED NOT NULL DEFAULT 0,
  data BLOB NOT NULL,
  PRIMARY KEY (id,ip_address), KEY idx_ci_sessions_timestamp (timestamp)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS password_reset_tokens (
  id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id BIGINT UNSIGNED NOT NULL,
  token_hash CHAR(64) NOT NULL,
  expires_at DATETIME NOT NULL,
  used_at DATETIME NULL,
  created_at DATETIME NOT NULL,
  PRIMARY KEY (id), UNIQUE KEY uq_password_reset_token_hash (token_hash), KEY idx_password_reset_user_expiry (user_id,expires_at),
  CONSTRAINT fk_password_reset_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
