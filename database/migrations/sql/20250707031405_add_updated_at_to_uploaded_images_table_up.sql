ALTER TABLE uploaded_images
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- Update existing records to set updated_at = uploaded_at for existing images
UPDATE uploaded_images 
SET updated_at = created_at 
WHERE updated_at IS NULL;