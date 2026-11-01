CREATE TABLE IF NOT EXISTS clients (
 id BIGINT UNSIGNED PRIMARY KEY, legacy_id BIGINT UNSIGNED NOT NULL UNIQUE,
 name VARCHAR(200) NOT NULL, contact_name VARCHAR(200) NOT NULL,
 email VARCHAR(200) NOT NULL, phone VARCHAR(15) NOT NULL, notes TEXT NOT NULL,
 administrative_percentage DECIMAL(14,4) NULL,
 legacy_administrative_percentage VARCHAR(10) NOT NULL,
 created_at DATETIME NULL, updated_at DATETIME NULL, deleted_at DATETIME NULL,
 is_active TINYINT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS vehicles (
 id BIGINT UNSIGNED PRIMARY KEY, legacy_id BIGINT UNSIGNED NOT NULL UNIQUE,
 name VARCHAR(15) NOT NULL, license_plate VARCHAR(20) NOT NULL,
 serial_number VARCHAR(30) NOT NULL, insurance_policy VARCHAR(30) NOT NULL,
 insurer VARCHAR(50) NOT NULL, notes TEXT NOT NULL,
 initial_odometer DECIMAL(15,2) NOT NULL, valid_until DATE NULL,
 vehicle_type_id BIGINT UNSIGNED NULL, legacy_vehicle_type_id BIGINT UNSIGNED NOT NULL,
 created_at DATETIME NULL, updated_at DATETIME NULL, deleted_at DATETIME NULL,
 is_active TINYINT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE IF NOT EXISTS operators (
 id BIGINT UNSIGNED PRIMARY KEY, legacy_id BIGINT UNSIGNED NOT NULL UNIQUE,
 legacy_user_id BIGINT UNSIGNED NOT NULL,
 social_security_number VARCHAR(11) NOT NULL, phone VARCHAR(15) NOT NULL,
 emergency_contact VARCHAR(100) NOT NULL, emergency_phone VARCHAR(15) NOT NULL,
 license VARCHAR(255) NOT NULL, valid_until DATE NULL, medical_valid_until DATE NULL,
 vehicle_id BIGINT UNSIGNED NULL, legacy_vehicle_id BIGINT UNSIGNED NOT NULL,
 client_id BIGINT UNSIGNED NULL, legacy_client_id BIGINT UNSIGNED NOT NULL,
 current_origin VARCHAR(255) NOT NULL, current_destination VARCHAR(255) NOT NULL,
 legacy_status VARCHAR(255) NOT NULL, notes TEXT NOT NULL,
 created_at DATETIME NULL, updated_at DATETIME NULL, deleted_at DATETIME NULL,
 is_active TINYINT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
