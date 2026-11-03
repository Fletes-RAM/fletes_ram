CREATE TABLE suppliers (
 id BIGINT UNSIGNED PRIMARY KEY,legacy_id BIGINT UNSIGNED NOT NULL UNIQUE,
 name VARCHAR(200) NOT NULL,contact_name VARCHAR(200) NOT NULL,email VARCHAR(200) NOT NULL,phone VARCHAR(15) NOT NULL,notes TEXT NOT NULL,
 created_at DATETIME NULL,updated_at DATETIME NULL,deleted_at DATETIME NULL,is_active TINYINT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE supplier_invoice_candidates (
 candidate_key CHAR(64) PRIMARY KEY,review_status VARCHAR(20) NOT NULL,item_count BIGINT UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE legacy_supplier_payment_items (
 id BIGINT UNSIGNED PRIMARY KEY,legacy_id BIGINT UNSIGNED NOT NULL UNIQUE,
 legacy_receipt_reference VARCHAR(255) NOT NULL,legacy_invoice_reference VARCHAR(255) NOT NULL,legacy_payment_date_text VARCHAR(255) NOT NULL,
 legacy_reported_amount VARCHAR(255) NOT NULL,reported_amount DECIMAL(15,2) NULL,paid_on DATE NULL,
 legacy_account_id VARCHAR(255) NOT NULL,legacy_category_id VARCHAR(255) NOT NULL,legacy_subcategory_id VARCHAR(255) NOT NULL,
 account_id BIGINT UNSIGNED NULL,category_id BIGINT UNSIGNED NULL,subcategory_id BIGINT UNSIGNED NULL,
 fuel_load_id BIGINT UNSIGNED NULL,fuel_legacy_source VARCHAR(20) NULL,fuel_legacy_id BIGINT UNSIGNED NULL,fuel_station_legacy_id BIGINT UNSIGNED NULL,
 notes VARCHAR(255) NOT NULL,candidate_key CHAR(64) NOT NULL,reconciliation_status VARCHAR(30) NOT NULL,
 created_at DATETIME NULL,updated_at DATETIME NULL,
 KEY ix_payment_candidate (candidate_key),KEY ix_legacy_receipt (legacy_receipt_reference)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE supplier_invoices (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,supplier_id BIGINT UNSIGNED NULL,fuel_station_id BIGINT UNSIGNED NULL,
 reference VARCHAR(255) NOT NULL,total_amount DECIMAL(15,2) NOT NULL,issued_on DATE NULL,due_on DATE NULL,
 CHECK(total_amount>=0),CHECK(supplier_id IS NOT NULL OR fuel_station_id IS NOT NULL),
 FOREIGN KEY(supplier_id) REFERENCES suppliers(id),FOREIGN KEY(fuel_station_id) REFERENCES fuel_stations(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE supplier_invoice_items (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,supplier_invoice_id BIGINT UNSIGNED NOT NULL,fuel_load_id BIGINT UNSIGNED NULL,
 description VARCHAR(255) NOT NULL,amount DECIMAL(15,2) NOT NULL,
 FOREIGN KEY(supplier_invoice_id) REFERENCES supplier_invoices(id),FOREIGN KEY(fuel_load_id) REFERENCES fuel_loads(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE TABLE supplier_payments (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,supplier_invoice_id BIGINT UNSIGNED NOT NULL,
 amount DECIMAL(15,2) NOT NULL,paid_on DATE NOT NULL,financial_movement_id BIGINT UNSIGNED NOT NULL UNIQUE,
 reference VARCHAR(255) NOT NULL,notes TEXT NOT NULL,idempotency_key VARCHAR(64) NOT NULL UNIQUE,reversed_at DATETIME NULL,
 CHECK(amount>0),FOREIGN KEY(supplier_invoice_id) REFERENCES supplier_invoices(id),FOREIGN KEY(financial_movement_id) REFERENCES financial_movements(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
