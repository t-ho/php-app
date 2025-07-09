#!/bin/bash

# Setup Cron Job for Orphaned Image Cleanup
# This script sets up a cron job to run the cleanup script at midnight daily

# Get the absolute path to the project root
PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"

# Cron job entry
CRON_ENTRY="0 0 * * * cd $PROJECT_ROOT && bin/cleanup-orphaned-images >/dev/null 2>&1"

echo "Setting up cron job for orphaned image cleanup..."
echo "Project root: $PROJECT_ROOT"
echo "Cron entry: $CRON_ENTRY"

# Check if cron job already exists
if crontab -l 2>/dev/null | grep -q "cleanup-orphaned-images"; then
  echo "Cron job already exists. Removing old entry..."
  crontab -l 2>/dev/null | grep -v "cleanup-orphaned-images" | crontab -
fi

# Add the new cron job
echo "Adding cron job..."
(
  crontab -l 2>/dev/null
  echo "$CRON_ENTRY"
) | crontab -

# Verify the cron job was added
echo "Current cron jobs:"
crontab -l

echo ""
echo "Cron job setup completed!"
echo "The cleanup script will run daily at midnight (00:00)."
echo ""
echo "To manually run the cleanup script:"
echo "  cd $PROJECT_ROOT"
echo "  bin/cleanup-orphaned-images --days=7"
echo ""
echo "To remove the cron job:"
echo "  crontab -e"
echo "  (then delete the line containing 'cleanup-orphaned-images')"
echo ""
echo "Log files:"
echo "  - Cleanup log: storage/logs/image-cleanup.log"

