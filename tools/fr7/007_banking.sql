CREATE TABLE IF NOT EXISTS legacy_bank_period_settings (
    id BIGINT UNSIGNED PRIMARY KEY,
    legacy_id BIGINT UNSIGNED NOT NULL UNIQUE,
    legacy_period VARCHAR(25) NOT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS legacy_bank_loans (
    id BIGINT UNSIGNED PRIMARY KEY,
    legacy_id BIGINT UNSIGNED NOT NULL UNIQUE,
    legacy_account_id BIGINT UNSIGNED NOT NULL,
    account_id BIGINT UNSIGNED NULL,
    legacy_period VARCHAR(15) NOT NULL,
    legacy_category_id BIGINT UNSIGNED NOT NULL,
    legacy_subcategory_reference VARCHAR(255) NOT NULL,
    description VARCHAR(50) NOT NULL,
    reference VARCHAR(50) NOT NULL,
    legacy_user_id BIGINT UNSIGNED NOT NULL,
    legacy_direction VARCHAR(8) NOT NULL,
    occurred_on DATE NULL,
    legacy_occurred_on VARCHAR(10) NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    notes TEXT NOT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    KEY (legacy_account_id, legacy_period)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS legacy_bank_closings (
    id BIGINT UNSIGNED PRIMARY KEY,
    legacy_id BIGINT UNSIGNED NOT NULL UNIQUE,
    legacy_account_id BIGINT UNSIGNED NOT NULL,
    account_id BIGINT UNSIGNED NULL,
    legacy_period VARCHAR(15) NOT NULL,
    legacy_closed_flag TINYINT NOT NULL,
    opening_balance DECIMAL(15,2) NOT NULL,
    closing_balance DECIMAL(15,2) NOT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    KEY (legacy_account_id, legacy_period)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
