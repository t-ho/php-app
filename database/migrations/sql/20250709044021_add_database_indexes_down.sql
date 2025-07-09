-- Remove optimized composite database indexes

-- Users table indexes
DROP INDEX idx_users_role ON users;

-- Posts table indexes
DROP INDEX idx_posts_user_created ON posts;
DROP INDEX idx_posts_views_desc ON posts;
DROP INDEX idx_posts_title_search ON posts;
DROP INDEX idx_posts_content_search ON posts;
DROP INDEX idx_posts_created_desc ON posts;

-- Comments table indexes
DROP INDEX idx_comments_post_created ON comments;
DROP INDEX idx_comments_user_id ON comments;

-- Remember tokens table indexes
DROP INDEX idx_remember_tokens_token_expires ON remember_tokens;
DROP INDEX idx_remember_tokens_user_id ON remember_tokens;

-- Uploaded images table indexes
DROP INDEX idx_uploaded_images_user_id ON uploaded_images;
DROP INDEX idx_uploaded_images_cleanup_created ON uploaded_images;