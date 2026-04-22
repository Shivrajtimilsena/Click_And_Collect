-- click_collect_dev_queries.sql
-- Purpose: Day-to-day Oracle development queries for the Click & Collect case study
-- Target schema: CC_APP
-- Recommended local project path:
--   C:\Users\timil\Herd\clickandcollect\database\sql\click_collect_dev_queries.sql
--
-- Usage inside VS Code Oracle SQL Developer extension:
--   1) Open this file
--   2) Connect to CC_APP_LOCAL
--   3) Run a single statement or highlighted block
--
-- Notes:
--   - Replace placeholder values like trader emails or dates as needed.
--   - This file is for development / inspection queries only.

--------------------------------------------------------------------------------
-- 0) BASIC HEALTH / SCHEMA CHECKS
--------------------------------------------------------------------------------

-- Current user/schema
SELECT USER AS current_schema FROM dual;

-- All tables in this schema
SELECT table_name
FROM user_tables
ORDER BY table_name;

-- All constraints in this schema
SELECT table_name, constraint_name, constraint_type, status
FROM user_constraints
ORDER BY table_name, constraint_name;

--------------------------------------------------------------------------------
-- 1) TABLE STRUCTURE INSPECTION
--------------------------------------------------------------------------------

-- Columns for a specific table (change table name as needed)
SELECT column_name, data_type, data_length, data_precision, data_scale, nullable
FROM user_tab_columns
WHERE table_name = UPPER('products')
ORDER BY column_id;

-- Quick count of rows in core tables
SELECT 'USERS' AS table_name, COUNT(*) AS total_rows FROM users
UNION ALL
SELECT 'CUSTOMERS', COUNT(*) FROM customers
UNION ALL
SELECT 'TRADERS', COUNT(*) FROM traders
UNION ALL
SELECT 'ADMINS', COUNT(*) FROM admins
UNION ALL
SELECT 'SHOPS', COUNT(*) FROM shops
UNION ALL
SELECT 'PRODUCT_CATEGORIES', COUNT(*) FROM product_categories
UNION ALL
SELECT 'PRODUCTS', COUNT(*) FROM products
UNION ALL
SELECT 'COLLECTION_SLOTS', COUNT(*) FROM collection_slots
UNION ALL
SELECT 'COUPONS', COUNT(*) FROM coupons
UNION ALL
SELECT 'ORDERS', COUNT(*) FROM orders
UNION ALL
SELECT 'ORDER_ITEMS', COUNT(*) FROM order_items
UNION ALL
SELECT 'PAYMENTS', COUNT(*) FROM payments;

--------------------------------------------------------------------------------
-- 2) SEE SEEDED DATA QUICKLY
--------------------------------------------------------------------------------

SELECT user_id, full_name, email, role, status
FROM users
ORDER BY user_id;

SELECT trader_id, user_id, shop_type, is_active
FROM traders
ORDER BY trader_id;

SELECT admin_id, user_id, access_level, last_login
FROM admins
ORDER BY admin_id;

SELECT shop_id, trader_id, shop_name, register_date, is_active
FROM shops
ORDER BY shop_id;

SELECT product_category_id, category_name, is_active
FROM product_categories
ORDER BY product_category_id;

SELECT product_id,
       shop_id,
       product_category_id,
       product_name,
       price,
       quantity_per_item,
       stock,
       min_order,
       max_order,
       allergy_information,
       product_status
FROM products
ORDER BY product_id;

SELECT collection_slot_id,
       slot_date,
       slot_day,
       slot_label,
       start_time,
       end_time,
       capacity,
       total_order,
       is_active
FROM collection_slots
ORDER BY slot_date, slot_label;

SELECT coupon_id, coupon_code, amount, discount_percent, start_date, end_date, is_active
FROM coupons
ORDER BY coupon_id;

--------------------------------------------------------------------------------
-- 3) CASE-STUDY BROWSING QUERIES (CUSTOMER SIDE)
--------------------------------------------------------------------------------

-- Browse products by shop
SELECT s.shop_name,
       p.product_id,
       p.product_name,
       c.category_name,
       p.price,
       p.quantity_per_item,
       p.stock,
       p.allergy_information
FROM products p
JOIN shops s
  ON s.shop_id = p.shop_id
JOIN product_categories c
  ON c.product_category_id = p.product_category_id
WHERE p.product_status = 'ACTIVE'
  AND s.is_active = 'Y'
ORDER BY s.shop_name, p.product_name;

-- Browse products by product type/category
SELECT c.category_name,
       s.shop_name,
       p.product_id,
       p.product_name,
       p.price,
       p.quantity_per_item,
       p.stock
FROM products p
JOIN product_categories c
  ON c.product_category_id = p.product_category_id
JOIN shops s
  ON s.shop_id = p.shop_id
WHERE p.product_status = 'ACTIVE'
ORDER BY c.category_name, p.product_name;

-- Search product by keyword
SELECT p.product_id,
       p.product_name,
       s.shop_name,
       c.category_name,
       p.price,
       p.quantity_per_item
FROM products p
JOIN shops s ON s.shop_id = p.shop_id
JOIN product_categories c ON c.product_category_id = p.product_category_id
WHERE UPPER(p.product_name) LIKE UPPER('%bread%')
   OR UPPER(p.description) LIKE UPPER('%bread%')
ORDER BY p.product_name;

--------------------------------------------------------------------------------
-- 4) TRADER-SPECIFIC QUERIES (TRADER DASHBOARD)
--------------------------------------------------------------------------------

-- Trader profile + shop by trader email (change email)
SELECT u.user_id,
       u.full_name,
       u.email,
       t.trader_id,
       t.shop_type,
       s.shop_id,
       s.shop_name,
       s.description,
       s.is_active
FROM users u
JOIN traders t ON t.user_id = u.user_id
JOIN shops s   ON s.trader_id = t.trader_id
WHERE u.email = 'butcher@clickcollect.local';

-- All products belonging to one trader (change email)
SELECT u.full_name AS trader_name,
       s.shop_name,
       p.product_id,
       p.product_name,
       c.category_name,
       p.price,
       p.quantity_per_item,
       p.stock,
       p.min_order,
       p.max_order,
       p.product_status
FROM users u
JOIN traders t ON t.user_id = u.user_id
JOIN shops s   ON s.trader_id = t.trader_id
JOIN products p ON p.shop_id = s.shop_id
JOIN product_categories c ON c.product_category_id = p.product_category_id
WHERE u.email = 'butcher@clickcollect.local'
ORDER BY p.product_name;

-- Low stock products for a trader (change email and threshold)
SELECT s.shop_name,
       p.product_id,
       p.product_name,
       p.stock,
       p.product_status
FROM users u
JOIN traders t ON t.user_id = u.user_id
JOIN shops s   ON s.trader_id = t.trader_id
JOIN products p ON p.shop_id = s.shop_id
WHERE u.email = 'butcher@clickcollect.local'
  AND p.stock <= 10
ORDER BY p.stock, p.product_name;

-- Trader monthly product sales summary (works once orders/order_items exist)
-- Change trader email and date range as needed.
SELECT s.shop_name,
       p.product_name,
       SUM(oi.quantity) AS total_qty_sold,
       SUM(oi.line_total) AS total_income
FROM users u
JOIN traders t ON t.user_id = u.user_id
JOIN shops s   ON s.trader_id = t.trader_id
JOIN products p ON p.shop_id = s.shop_id
JOIN order_items oi ON oi.product_id = p.product_id
JOIN orders o ON o.order_id = oi.order_id
WHERE u.email = 'butcher@clickcollect.local'
  AND o.order_date >= DATE '2026-04-01'
  AND o.order_date <  DATE '2026-05-01'
GROUP BY s.shop_name, p.product_name
ORDER BY total_income DESC, p.product_name;

--------------------------------------------------------------------------------
-- 5) ADMIN-SPECIFIC QUERIES (ADMIN DASHBOARD)
--------------------------------------------------------------------------------

-- All traders and their shops
SELECT u.full_name AS trader_name,
       u.email,
       t.shop_type,
       t.is_active AS trader_active,
       s.shop_name,
       s.is_active AS shop_active
FROM users u
JOIN traders t ON t.user_id = u.user_id
LEFT JOIN shops s ON s.trader_id = t.trader_id
ORDER BY s.shop_name;

-- All products across all traders
SELECT s.shop_name,
       c.category_name,
       p.product_name,
       p.price,
       p.quantity_per_item,
       p.stock,
       p.product_status
FROM products p
JOIN shops s ON s.shop_id = p.shop_id
JOIN product_categories c ON c.product_category_id = p.product_category_id
ORDER BY s.shop_name, p.product_name;

-- Collection slot utilization
SELECT collection_slot_id,
       slot_date,
       slot_day,
       slot_label,
       capacity,
       total_order,
       (capacity - total_order) AS remaining_capacity
FROM collection_slots
ORDER BY slot_date, slot_label;

--------------------------------------------------------------------------------
-- 6) CASE-STUDY COLLECTION SLOT RULE QUERIES
--------------------------------------------------------------------------------

-- Available collection slots at least 24 hours from now, with remaining capacity
SELECT collection_slot_id,
       slot_date,
       slot_day,
       slot_label,
       capacity,
       total_order,
       (capacity - total_order) AS remaining_capacity
FROM collection_slots
WHERE slot_date >= TRUNC(SYSDATE + 1)
  AND is_active = 'Y'
  AND total_order < capacity
ORDER BY slot_date, slot_label;

-- Slots only on Wed/Thu/Fri
SELECT collection_slot_id, slot_date, slot_day, slot_label
FROM collection_slots
WHERE slot_day IN ('Wednesday', 'Thursday', 'Friday')
ORDER BY slot_date, slot_label;

--------------------------------------------------------------------------------
-- 7) ORDER / PAYMENT REPORT STARTERS
--------------------------------------------------------------------------------

-- Daily trader order report (works once orders/order_items exist)
-- Shows goods and quantities ordered for a trader by collection slot/date.
SELECT s.shop_name,
       cs.slot_date,
       cs.slot_label,
       o.order_id,
       c.customer_id,
       p.product_name,
       oi.quantity,
       oi.unit_price,
       oi.line_total
FROM orders o
JOIN collection_slots cs ON cs.collection_slot_id = o.collection_slot_id
JOIN customers c ON c.customer_id = o.customer_id
JOIN order_items oi ON oi.order_id = o.order_id
JOIN products p ON p.product_id = oi.product_id
JOIN shops s ON s.shop_id = o.shop_id
WHERE s.shop_name = 'Heritage Butcher'
  AND cs.slot_date = TRUNC(SYSDATE + 1)
ORDER BY cs.slot_date, cs.slot_label, o.order_id, p.product_name;

-- Weekly finance report per trader (only delivered orders)
SELECT s.shop_name,
       TRUNC(o.order_date) AS order_day,
       COUNT(DISTINCT o.order_id) AS total_orders,
       SUM(oi.line_total) AS gross_sales
FROM orders o
JOIN order_items oi ON oi.order_id = o.order_id
JOIN shops s ON s.shop_id = o.shop_id
WHERE o.order_status = 'DELIVERED'
  AND o.order_date >= TRUNC(SYSDATE) - 7
GROUP BY s.shop_name, TRUNC(o.order_date)
ORDER BY s.shop_name, order_day;

-- Payment summary by payment method
SELECT payment_method,
       payment_status,
       COUNT(*) AS total_payments,
       SUM(amount) AS total_amount
FROM payments
GROUP BY payment_method, payment_status
ORDER BY payment_method, payment_status;

--------------------------------------------------------------------------------
-- 8) SAMPLE INSERTS FOR QUICK MANUAL TESTING (OPTIONAL)
--------------------------------------------------------------------------------
-- Uncomment and adapt carefully. Keep autoCommit OFF if you want to rollback.
--------------------------------------------------------------------------------

-- Example: add a new category
-- INSERT INTO product_categories (
--     product_category_id, category_name, description, is_active, created_at, updated_at
-- ) VALUES (
--     NULL, 'Seasonal Specials', 'Temporary seasonal product type', 'Y', SYSTIMESTAMP, SYSTIMESTAMP
-- );
-- COMMIT;

-- Example: update product stock
-- UPDATE products
-- SET stock = stock - 1,
--     updated_at = SYSTIMESTAMP,
--     update_date = TRUNC(SYSDATE)
-- WHERE product_name = 'Sourdough Bread';
-- COMMIT;

--------------------------------------------------------------------------------
-- 9) USEFUL CLEANUP / DEBUG QUERIES
--------------------------------------------------------------------------------

-- Show migration history
SELECT *
FROM migrations
ORDER BY id;

-- Show all indexes on a table (change table name)
SELECT index_name, table_name, uniqueness, status
FROM user_indexes
WHERE table_name = UPPER('products')
ORDER BY index_name;

-- Show foreign keys and referenced tables
SELECT uc.table_name,
       uc.constraint_name,
       uc.constraint_type,
       ucc.column_name,
       r_uc.table_name AS referenced_table
FROM user_constraints uc
JOIN user_cons_columns ucc
  ON uc.constraint_name = ucc.constraint_name
LEFT JOIN user_constraints r_uc
  ON uc.r_constraint_name = r_uc.constraint_name
WHERE uc.constraint_type = 'R'
ORDER BY uc.table_name, uc.constraint_name;
