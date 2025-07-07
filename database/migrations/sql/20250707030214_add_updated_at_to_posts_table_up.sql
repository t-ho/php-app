ALTER TABLE posts
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- Update existing records to set updated_at = created_at for existing posts
UPDATE posts 
SET updated_at = created_at 
WHERE updated_at IS NULL;