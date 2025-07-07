-- Add the updated_at column
ALTER TABLE users 
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- Update existing records to set updated_at = created_at for existing users
UPDATE users 
SET updated_at = created_at 
WHERE updated_at IS NULL;
