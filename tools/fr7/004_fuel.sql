CREATE TABLE IF NOT EXISTS fuel_loads (
 id BIGINT UNSIGNED PRIMARY KEY,legacy_id BIGINT UNSIGNED NOT NULL,
 legacy_source VARCHAR(20) NOT NULL,
 UNIQUE KEY uq_fuel_legacy (legacy_source,legacy_id),
 assignment_id BIGINT UNSIGNED NULL,legacy_assignment_id BIGINT UNSIGNED NULL,
 vehicle_id BIGINT UNSIGNED NULL,legacy_vehicle_id BIGINT UNSIGNED NULL,
 legacy_user_id BIGINT UNSIGNED NULL,
 fuel_station_id BIGINT UNSIGNED NULL,legacy_fuel_station_id BIGINT UNSIGNED NOT NULL,
 is_other_fuel_station TINYINT NOT NULL,
 business_date DATE NULL,legacy_business_date VARCHAR(10) NULL,
 payment_due_date DATE NULL,legacy_payment_due_date VARCHAR(10) NULL,
 ticket VARCHAR(150) NOT NULL,
 liters DECIMAL(15,2) NOT NULL,unit_price DECIMAL(15,2) NOT NULL,total_amount DECIMAL(15,2) NOT NULL,
 odometer DECIMAL(15,2) NOT NULL,kilometers_per_liter DECIMAL(15,2) NOT NULL,
 ticket_photo_path VARCHAR(250) NOT NULL,dashboard_before_path VARCHAR(250) NOT NULL,
 dashboard_after_path VARCHAR(250) NOT NULL,dashboard_odometer_path VARCHAR(250) NOT NULL,
 legacy_supplier_invoice VARCHAR(50) NULL,credit_days INT NULL,
 created_at DATETIME NULL,updated_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
