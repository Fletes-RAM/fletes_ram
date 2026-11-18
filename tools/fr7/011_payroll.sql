CREATE TABLE legacy_attendance (
    id BIGINT UNSIGNED PRIMARY KEY,
    legacy_id BIGINT UNSIGNED NOT NULL UNIQUE,
    legacy_user_id BIGINT NOT NULL,
    user_reference_exists TINYINT NOT NULL,
    legacy_type VARCHAR(255) NOT NULL,
    attendance_type VARCHAR(10) NULL,
    attendance_on DATE NULL,
    legacy_attendance_on VARCHAR(10) NOT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    KEY (legacy_user_id, attendance_on)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE legacy_settlement_periods (
    id BIGINT UNSIGNED PRIMARY KEY,
    legacy_id BIGINT UNSIGNED NOT NULL UNIQUE,
    legacy_user_id BIGINT UNSIGNED NOT NULL,
    user_reference_exists TINYINT NOT NULL,
    starts_on DATE NULL,
    legacy_starts_on VARCHAR(10) NOT NULL,
    ends_on DATE NULL,
    legacy_ends_on VARCHAR(10) NOT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    KEY (legacy_user_id, starts_on, ends_on)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
