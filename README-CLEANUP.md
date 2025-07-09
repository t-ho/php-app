# Orphaned Image Cleanup

Automatic cleanup system for removing unused images from the filesystem and database.

## Quick Start

### Local Development
```bash
# Run cleanup manually
bin/cleanup-orphaned-images

# Run with custom threshold
bin/cleanup-orphaned-images --days=14

# Set up automated daily cleanup
scripts/setup-cron.sh
```

### Docker Environment
```bash
# Run cleanup in Docker container
docker compose exec php-app bin/cleanup-orphaned-images

# Run with custom threshold
docker compose exec php-app bin/cleanup-orphaned-images --days=14

# Run with environment variable override
docker compose exec -e CLEANUP_DAYS=30 php-app bin/cleanup-orphaned-images
```

## How It Works

1. **Image Tracking**: TinyMCE uploads are tracked in `uploaded_images` table
2. **Orphan Detection**: Images are orphaned when:
   - Marked for deletion and older than specified days
   - Not associated with any entity (post) and older than specified days
3. **Cleanup Process**: Removes both database records and filesystem files
4. **Logging**: Detailed logs written to `storage/logs/image-cleanup.log`

## Files

- **`bin/cleanup-orphaned-images`** - Main CLI executable
- **`scripts/setup-cron.sh`** - Sets up automated midnight execution

## Cron Setup

```bash
# Automatic setup
./scripts/setup-cron.sh

# Manual cron entry (runs daily at midnight)
0 0 * * * cd /path/to/project && bin/cleanup-orphaned-images >/dev/null 2>&1
```

## Configuration

### Environment Variables (.env)
```bash
# Cleanup Configuration
CLEANUP_DAYS=7              # Days threshold for orphaned images
CLEANUP_LOG_LEVEL=INFO      # Log level (INFO, ERROR)
```

### Priority Order
1. **CLI argument**: `--days=N` (highest priority)
2. **Environment variable**: `CLEANUP_DAYS`
3. **Default**: 7 days

### Log Files
- **`storage/logs/image-cleanup.log`** - Detailed cleanup information

## Docker Usage

The CLI tool works seamlessly in Docker environments:

### Basic Docker Commands
```bash
# Standard execution
docker compose exec php-app bin/cleanup-orphaned-images

# With custom days threshold
docker compose exec php-app bin/cleanup-orphaned-images --days=30

# With environment variable override
docker compose exec -e CLEANUP_DAYS=14 php-app bin/cleanup-orphaned-images

# View help
docker compose exec php-app bin/cleanup-orphaned-images --help
```

### Docker Environment Detection
The script automatically detects Docker environment and:
- Adjusts error logging to `storage/logs/image-cleanup.log`
- Uses container-appropriate paths
- Respects Docker environment variables

### Viewing Logs in Docker
```bash
# View cleanup logs
docker compose exec php-app tail -f storage/logs/image-cleanup.log

# View recent log entries
docker compose exec php-app tail -n 50 storage/logs/image-cleanup.log
```

## Architecture

### Deletion Logic

- **File exists + deletes successfully**: Delete database record
- **File exists + deletion fails**: Keep database record (allows retry)
- **File doesn't exist**: Delete database record (cleanup orphaned record)

### Performance

- **Batch operations**: Process all files first, then batch delete DB records
- **Single DB query**: `DELETE FROM uploaded_images WHERE id IN (...)`
- **10x-100x faster**: Compared to individual deletions

## API Usage

```php
// Batch cleanup (used internally)
$results = ImageDeletionService::batchForceDeleteImages($images);

// Simple batch delete
$deletedCount = UploadedImage::batchDelete([1, 2, 3]);
```

## Troubleshooting

### Local Environment
```bash
# View cleanup logs
tail -f storage/logs/image-cleanup.log

# Check permissions
chmod +x bin/cleanup-orphaned-images

# Test manually
bin/cleanup-orphaned-images --help
```

### Docker Environment
```bash
# View cleanup logs
docker compose exec php-app tail -f storage/logs/image-cleanup.log

# Check if container is running
docker compose ps

# Test manually
docker compose exec php-app bin/cleanup-orphaned-images --help

# Debug with verbose output
docker compose exec php-app bin/cleanup-orphaned-images --days=1
```

### Common Issues
1. **Permission Denied**: Ensure scripts are executable (`chmod +x`)
2. **Database Connection**: Verify the application can connect to the database
3. **File System Access**: Ensure the script has permission to delete files
4. **Docker Issues**: Verify `docker compose` is available and containers are running
5. **Environment Variables**: Check `.env` file contains `CLEANUP_DAYS` setting

