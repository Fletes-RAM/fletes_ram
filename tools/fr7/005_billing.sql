CREATE TABLE financial_accounts (
 id BIGINT UNSIGNED PRIMARY KEY,legacy_id BIGINT UNSIGNED NOT NULL UNIQUE,
 name VARCHAR(50) NOT NULL,account_number VARCHAR(25) NOT NULL,banking_code VARCHAR(25) NOT NULL,notes TEXT NOT NULL,
 created_at DATETIME NULL,updated_at DATETIME NULL,deleted_at DATETIME NULL,is_active TINYINT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE financial_categories (
 id BIGINT UNSIGNED PRIMARY KEY,legacy_id BIGINT UNSIGNED NOT NULL UNIQUE,name VARCHAR(100) NOT NULL,
 created_at DATETIME NULL,updated_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE financial_subcategories (
 id BIGINT UNSIGNED PRIMARY KEY,legacy_id BIGINT UNSIGNED NOT NULL UNIQUE,name VARCHAR(100) NOT NULL,
 category_id BIGINT UNSIGNED NULL,legacy_category_id BIGINT UNSIGNED NOT NULL,
 created_at DATETIME NULL,updated_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE financial_movements (
 id BIGINT UNSIGNED PRIMARY KEY,legacy_id BIGINT UNSIGNED NOT NULL UNIQUE,
 legacy_period VARCHAR(15) NOT NULL,description VARCHAR(50) NOT NULL,reference VARCHAR(50) NOT NULL,notes TEXT NOT NULL,
 account_id BIGINT UNSIGNED NULL,legacy_account_id BIGINT UNSIGNED NOT NULL,
 category_id BIGINT UNSIGNED NULL,legacy_category_id BIGINT UNSIGNED NOT NULL,
 subcategory_id BIGINT UNSIGNED NULL,legacy_subcategory_id VARCHAR(255) NOT NULL,
 business_date DATE NULL,legacy_business_date VARCHAR(10) NOT NULL,
 legacy_quantity DECIMAL(15,2) NOT NULL,legacy_type VARCHAR(8) NOT NULL,amount DECIMAL(15,2) NOT NULL,direction VARCHAR(10) NULL,
 created_at DATETIME NULL,updated_at DATETIME NULL,
 KEY ix_financial_reference (reference)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE vehicle_controls (
 id BIGINT UNSIGNED PRIMARY KEY,legacy_id BIGINT UNSIGNED NOT NULL UNIQUE,
 legacy_user_id BIGINT UNSIGNED NOT NULL,legacy_percentage INT UNSIGNED NOT NULL,
 invoice_id BIGINT UNSIGNED NULL,legacy_invoice_id BIGINT NULL,
 business_date DATE NULL,legacy_business_date VARCHAR(10) NOT NULL,
 reference VARCHAR(255) NOT NULL,legacy_origin VARCHAR(255) NOT NULL,legacy_paid_status VARCHAR(6) NOT NULL,
 tonnage DECIMAL(18,4) NULL,legacy_tonnage VARCHAR(255) NOT NULL,
 rate DECIMAL(18,4) NULL,legacy_rate VARCHAR(255) NOT NULL,
 amount DECIMAL(18,4) NULL,legacy_amount VARCHAR(255) NOT NULL,
 tax_amount DECIMAL(18,4) NULL,legacy_tax_amount VARCHAR(255) NOT NULL,
 created_at DATETIME NULL,updated_at DATETIME NULL,
 KEY ix_controls_invoice (invoice_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE invoices (
 id BIGINT UNSIGNED PRIMARY KEY,legacy_id BIGINT UNSIGNED NOT NULL,legacy_source VARCHAR(20) NOT NULL,
 UNIQUE KEY uq_invoice_legacy (legacy_source,legacy_id),
 reference VARCHAR(255) NOT NULL,notes TEXT NOT NULL,
 subtotal DECIMAL(15,2) NOT NULL,handling_amount DECIMAL(15,2) NOT NULL,other_amount DECIMAL(15,2) NOT NULL,
 tax_amount DECIMAL(15,2) NOT NULL,withholding_amount DECIMAL(15,2) NOT NULL,total_amount DECIMAL(15,2) NOT NULL,
 is_paid TINYINT NULL,legacy_paid_flag VARCHAR(4) NOT NULL,payment_date DATE NULL,legacy_payment_date VARCHAR(10) NULL,
 quotation_id BIGINT UNSIGNED NULL,legacy_quotation_id BIGINT UNSIGNED NULL,
 client_id BIGINT UNSIGNED NULL,legacy_client_id BIGINT UNSIGNED NULL,
 legacy_control_id BIGINT UNSIGNED NULL,is_grouped TINYINT NOT NULL,
 created_at DATETIME NULL,updated_at DATETIME NULL,
 KEY ix_invoice_reference (reference)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE invoice_sources (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,invoice_id BIGINT UNSIGNED NOT NULL,
 source_type VARCHAR(20) NOT NULL,legacy_source_id BIGINT UNSIGNED NOT NULL,
 quotation_id BIGINT UNSIGNED NULL,control_id BIGINT UNSIGNED NULL,evidence_flags TINYINT NOT NULL,
 UNIQUE KEY uq_invoice_source (invoice_id,source_type,legacy_source_id),KEY ix_invoice_control (control_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE collections (
 id BIGINT UNSIGNED PRIMARY KEY,invoice_id BIGINT UNSIGNED NOT NULL UNIQUE,
 record_kind VARCHAR(30) NOT NULL,claimed_amount DECIMAL(15,2) NOT NULL,paid_on DATE NULL,
 financial_movement_id BIGINT UNSIGNED NULL UNIQUE,reconciliation_status VARCHAR(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE collection_movement_candidates (
 collection_id BIGINT UNSIGNED NOT NULL,financial_movement_id BIGINT UNSIGNED NOT NULL,
 PRIMARY KEY (collection_id,financial_movement_id),KEY ix_candidate_movement (financial_movement_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
