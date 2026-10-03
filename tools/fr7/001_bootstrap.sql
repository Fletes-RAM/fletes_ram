CREATE TABLE IF NOT EXISTS migration_runs (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  uuid CHAR(36) NOT NULL UNIQUE,
  source_fingerprint CHAR(64) NOT NULL,
  source_label VARCHAR(120) NOT NULL,
  started_at DATETIME NOT NULL,
  finished_at DATETIME NULL,
  status VARCHAR(20) NOT NULL,
  migrator_version VARCHAR(40) NOT NULL,
  notes TEXT NULL,
  metrics JSON NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS migration_id_map (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  migration_run_id BIGINT UNSIGNED NOT NULL,
  domain VARCHAR(60) NOT NULL,
  source_table VARCHAR(120) NOT NULL,
  source_id VARCHAR(120) NOT NULL,
  target_table VARCHAR(120) NOT NULL,
  target_id VARCHAR(120) NOT NULL,
  action VARCHAR(20) NOT NULL,
  metadata JSON NULL,
  UNIQUE KEY uq_migration_map (migration_run_id, source_table, source_id, target_table),
  CONSTRAINT fk_migration_map_run FOREIGN KEY (migration_run_id) REFERENCES migration_runs(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS migration_exceptions (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  migration_run_id BIGINT UNSIGNED NOT NULL,
  domain VARCHAR(60) NOT NULL,
  source_table VARCHAR(120) NOT NULL,
  source_id VARCHAR(120) NULL,
  code VARCHAR(80) NOT NULL,
  severity VARCHAR(20) NOT NULL,
  source_snapshot JSON NULL,
  resolution TEXT NULL,
  status VARCHAR(20) NOT NULL,
  target_table VARCHAR(120) NULL,
  target_id VARCHAR(120) NULL,
  CONSTRAINT fk_migration_exception_run FOREIGN KEY (migration_run_id) REFERENCES migration_runs(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS vehicle_types (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  legacy_id BIGINT UNSIGNED NULL UNIQUE,
  name VARCHAR(80) NOT NULL,
  percentage DECIMAL(7,2) NULL,
  notes TEXT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at DATETIME NULL,
  updated_at DATETIME NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
