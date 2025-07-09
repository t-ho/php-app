-- Add optimized composite database indexes for better query performance
-- Note: Some indexes already exist in schema.sql and are not recreated

-- Users table indexes
-- email already has UNIQUE constraint which provides index functionality
CREATE INDEX idx_users_role ON users(role);

-- Posts table indexes - optimized for actual query patterns
CREATE INDEX idx_posts_user_created ON posts(user_id, created_at DESC);
CREATE INDEX idx_posts_views_desc ON posts(views DESC);
CREATE INDEX idx_posts_title_search ON posts(title);
CREATE INDEX idx_posts_content_search ON posts(sanitized_html_content(255));
CREATE INDEX idx_posts_created_desc ON posts(created_at DESC);

-- Comments table indexes - optimized for forPost() query
CREATE INDEX idx_comments_post_created ON comments(post_id, created_at DESC);
CREATE INDEX idx_comments_user_id ON comments(user_id);

-- Remember tokens table indexes - optimized for findValid() query
CREATE INDEX idx_remember_tokens_token_expires ON remember_tokens(token, expires_at);
CREATE INDEX idx_remember_tokens_user_id ON remember_tokens(user_id);

-- Uploaded images table indexes
-- Note: idx_file_path, idx_cleanup, idx_orphan_check, idx_entity_type_entity_id already exist in schema.sql
CREATE INDEX idx_uploaded_images_user_id ON uploaded_images(user_id);
CREATE INDEX idx_uploaded_images_cleanup_created ON uploaded_images(marked_for_deletion_at, created_at);