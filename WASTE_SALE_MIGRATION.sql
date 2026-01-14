-- Waste Sale System - Database Migration
-- Date: January 13, 2026
-- Status: Ready to apply

-- ============================================
-- CREATE TABLE: waste_sale
-- ============================================

CREATE TABLE IF NOT EXISTS `waste_sale` (
  `waste_sale_id` int(11) NOT NULL AUTO_INCREMENT,
  `waste_sale_type_id` int(11) DEFAULT NULL,
  `waste_sale_weight` decimal(10,3) DEFAULT NULL COMMENT 'Weight in kilograms with 3 decimal places',
  `waste_sale_actual_price` decimal(12,2) DEFAULT NULL COMMENT 'Actual price received for the sale',
  `waste_sale_buyer` varchar(100) DEFAULT NULL COMMENT 'Name of the buyer (company or individual)',
  `waste_sale_date` date DEFAULT NULL COMMENT 'Date when the waste was sold',
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP COMMENT 'Record creation timestamp',
  PRIMARY KEY (`waste_sale_id`),
  KEY `fk_waste_sale_type_id` (`waste_sale_type_id`),
  KEY `idx_waste_sale_date` (`waste_sale_date`),
  KEY `idx_waste_sale_created_at` (`created_at`),
  CONSTRAINT `fk_waste_sale_type_id` FOREIGN KEY (`waste_sale_type_id`) REFERENCES `waste_type` (`waste_type_id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Table for recording waste sales at the waste center';

-- ============================================
-- INDEXES FOR PERFORMANCE
-- ============================================

-- Index for date-based queries
ALTER TABLE `waste_sale` ADD INDEX `idx_waste_sale_date_type` (`waste_sale_date`, `waste_sale_type_id`);

-- Index for buyer search
ALTER TABLE `waste_sale` ADD INDEX `idx_waste_sale_buyer` (`waste_sale_buyer`);

-- Index for created date (useful for reports)
ALTER TABLE `waste_sale` ADD INDEX `idx_waste_sale_created_at_type` (`created_at`, `waste_sale_type_id`);

-- ============================================
-- SAMPLE DATA (for testing)
-- ============================================

-- INSERT INTO `waste_sale` (
--   `waste_sale_type_id`,
--   `waste_sale_weight`,
--   `waste_sale_actual_price`,
--   `waste_sale_buyer`,
--   `waste_sale_date`,
--   `created_at`
-- ) VALUES
-- (8, 5.500, 11.00, 'Company ABC', '2026-01-13', NOW()),
-- (9, 3.200, 12.80, 'Company ABC', '2026-01-13', NOW()),
-- (4, 2.000, 4.00, 'Company XYZ', '2026-01-13', NOW()),
-- (5, 1.500, 1.50, 'Company XYZ', '2026-01-12', NOW()),
-- (8, 10.000, 20.00, 'Recycling Corp', '2026-01-11', NOW());

-- ============================================
-- VERIFICATION QUERIES
-- ============================================

-- Check if table was created
-- SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = 'waste_sale';

-- Check table structure
-- DESCRIBE waste_sale;

-- Check indexes
-- SHOW INDEXES FROM waste_sale;

-- ============================================
-- SUMMARY QUERY EXAMPLES
-- ============================================

-- Get sales summary by type
-- SELECT
--   wt.waste_type_id,
--   wt.waste_type_name,
--   SUM(ws.waste_sale_weight) as total_weight,
--   SUM(ws.waste_sale_actual_price) as total_revenue,
--   COUNT(*) as transaction_count
-- FROM waste_sale ws
-- LEFT JOIN waste_type wt ON ws.waste_sale_type_id = wt.waste_type_id
-- WHERE DATE(ws.waste_sale_date) = CURDATE()
-- GROUP BY wt.waste_type_id, wt.waste_type_name
-- ORDER BY total_revenue DESC;

-- Get sales by date range
-- SELECT
--   ws.waste_sale_date,
--   wt.waste_type_name,
--   ws.waste_sale_weight,
--   ws.waste_sale_actual_price,
--   ws.waste_sale_buyer
-- FROM waste_sale ws
-- LEFT JOIN waste_type wt ON ws.waste_sale_type_id = wt.waste_type_id
-- WHERE ws.waste_sale_date BETWEEN '2026-01-01' AND '2026-01-31'
-- ORDER BY ws.waste_sale_date DESC, ws.created_at DESC;

-- Get total revenue by buyer
-- SELECT
--   waste_sale_buyer,
--   COUNT(*) as transactions,
--   SUM(waste_sale_weight) as total_weight,
--   SUM(waste_sale_actual_price) as total_revenue
-- FROM waste_sale
-- WHERE waste_sale_buyer IS NOT NULL
-- GROUP BY waste_sale_buyer
-- ORDER BY total_revenue DESC;

-- ============================================
-- NOTES
-- ============================================
--
-- 1. The waste_sale_weight field uses decimal(10,3) for precision
--    This allows values like 5.500 kg
--
-- 2. The waste_sale_actual_price uses decimal(12,2) for currency
--    This allows values like 1234567.89
--
-- 3. Foreign key is set to ON DELETE RESTRICT to prevent
--    deleting waste types that have sales records
--
-- 4. Indexes are created for:
--    - waste_sale_date (common filter)
--    - waste_sale_type_id (join and filter)
--    - waste_sale_buyer (search functionality)
--    - created_at (audit trail)
--
-- 5. All timestamps are in server timezone (+07:00 by default)
--
-- 6. UTF-8 character set allows Thai language support
--
-- ============================================
