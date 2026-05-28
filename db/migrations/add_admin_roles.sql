-- Add name and role columns to admin_users
-- role: 'super_admin' can manage users and settings; 'staff' has read/write access but no user management
-- Run: psql $DATABASE_URL -f db/migrations/add_admin_roles.sql

ALTER TABLE admin_users
  ADD COLUMN IF NOT EXISTS name     VARCHAR(255),
  ADD COLUMN IF NOT EXISTS role     VARCHAR(20) NOT NULL DEFAULT 'super_admin';

-- Existing accounts keep super_admin — manually downgrade via admin/users.php if needed
