CREATE TABLE inventory_materials (
    id BIGINT UNSIGNED PRIMARY KEY,
    legacy_id BIGINT UNSIGNED NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    unit_price DECIMAL(15,2) NOT NULL,
    legacy_value DECIMAL(15,2) NOT NULL,
    legacy_stock BIGINT UNSIGNED NOT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE inventory_materials_receipts (
    id BIGINT UNSIGNED PRIMARY KEY,
    legacy_id BIGINT UNSIGNED NOT NULL UNIQUE,
    quantity BIGINT UNSIGNED NOT NULL,
    notes TEXT NOT NULL,
    unit_price DECIMAL(15,2) NOT NULL,
    legacy_material_id BIGINT UNSIGNED NOT NULL,
    material_id BIGINT UNSIGNED NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE inventory_materials_issues (
    id BIGINT UNSIGNED PRIMARY KEY,
    legacy_id BIGINT UNSIGNED NOT NULL UNIQUE,
    quantity BIGINT UNSIGNED NOT NULL,
    notes TEXT NOT NULL,
    legacy_vehicle_id BIGINT UNSIGNED NOT NULL,
    legacy_material_id BIGINT UNSIGNED NOT NULL,
    material_id BIGINT UNSIGNED NULL,
    vehicle_id BIGINT UNSIGNED NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE inventory_tires (
    id BIGINT UNSIGNED PRIMARY KEY,
    legacy_id BIGINT UNSIGNED NOT NULL UNIQUE,
    code VARCHAR(100) NOT NULL,
    brand VARCHAR(100) NOT NULL,
    size VARCHAR(100) NOT NULL,
    type VARCHAR(100) NOT NULL,
    legacy_stock BIGINT UNSIGNED NOT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE inventory_tires_receipts (
    id BIGINT UNSIGNED PRIMARY KEY,
    legacy_id BIGINT UNSIGNED NOT NULL UNIQUE,
    quantity BIGINT UNSIGNED NOT NULL,
    notes TEXT NOT NULL,
    unit_price DECIMAL(15,2) NOT NULL,
    legacy_tire_id BIGINT UNSIGNED NOT NULL,
    tire_id BIGINT UNSIGNED NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE inventory_tires_issues (
    id BIGINT UNSIGNED PRIMARY KEY,
    legacy_id BIGINT UNSIGNED NOT NULL UNIQUE,
    quantity BIGINT UNSIGNED NOT NULL,
    notes TEXT NOT NULL,
    legacy_vehicle_id BIGINT UNSIGNED NOT NULL,
    legacy_tire_id BIGINT UNSIGNED NOT NULL,
    tire_id BIGINT UNSIGNED NULL,
    vehicle_id BIGINT UNSIGNED NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
