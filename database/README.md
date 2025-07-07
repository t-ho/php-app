# Database Structure

This directory contains all database-related files following best practices:

## Directory Structure

```
database/
├── README.md                           # This file - documentation
├── schema.sql                         # Current database schema (used by Docker)
├── migrations/                        # Phinx migration files (for future changes)
│   ├── BaseMigration.php                  # Base class with SQL file loading
│   ├── YYYYMMDDHHMMSS_migration_name.php       # Migration PHP classes (Phinx format)
│   └── sql/                               # External SQL files directory
│       ├── YYYYMMDDHHMMSS_migration_name_up.sql   # Migration SQL files
│       └── YYYYMMDDHHMMSS_migration_name_down.sql # Rollback SQL files
└── seeds/                             # Database seeders (when needed)
```

## Database Setup Approach

This project uses a two-tier database management approach:

### Current Schema (schema.sql)

- **Purpose**: Handles the existing, stable database structure
- **Usage**: Automatically loaded by Docker containers on startup
- **Contains**: All current tables (users, posts, comments, remember_tokens, uploaded_images)
- **When to modify**: Avoid direct edits; use migrations for changes

### Future Changes (Phinx Migrations)

- **Purpose**: Handle all future database schema modifications
- **Usage**: Run manually when adding new features or modifying existing structure
- **Benefits**: Version control, rollback capability, team collaboration

## Migrations

Migrations are version-controlled database schema changes using Phinx for **future modifications only**.

### Common Commands

```bash
# Create a new migration
vendor/bin/phinx create CreateUsersTable

# Run pending migrations
vendor/bin/phinx migrate

# Rollback last migration
vendor/bin/phinx rollback

# Check migration status
vendor/bin/phinx status

# Seed database
vendor/bin/phinx seed:run
```

### Environments

All environments use MySQL/MariaDB and are configured via environment variables:

- **development**: Uses `DB_NAME` from .env (e.g., `app_db`)
- **testing**: Uses `DB_NAME_TEST` from .env (e.g., `app_db_test`)
- **production**: Uses `DB_NAME` from .env (e.g., `app_db_prod`)

### External SQL Files Structure

This project uses the **standard external SQL files approach** for better organization:

#### **Structure Benefits:**
- **Clean separation** - PHP logic and SQL separated
- **Syntax highlighting and IDE support** for SQL files
- **Easy editing and version control** 
- **Database-specific optimizations** possible
- **Standard Phinx workflow** - No custom setup required

#### **How it works:**
1. **PHP migration classes** - Handle Phinx lifecycle and logic
2. **External SQL files** - Contain actual database changes
3. **BaseMigration helper** - Loads SQL files automatically

#### **Naming Convention:**
- **PHP files**: Follow Phinx's timestamp format (e.g., `20250706140329_add_updated_at_to_users_table.php`)
- **SQL files**: Use matching timestamp format `YYYYMMDDHHMMSS_description_up/down.sql`
  - `YYYYMMDDHHMMSS`: Full timestamp matching the PHP file (20250706140329)
  - `description`: Clear purpose description
  - `up/down`: Migration direction

#### **Migration structure example:**

```
migrations/
├── 20250706140329_add_updated_at_to_users_table.php  # Migration class (Phinx format)
└── sql/
    ├── 20250706140329_add_updated_at_to_users_table_up.sql    # Migration SQL
    └── 20250706140329_add_updated_at_to_users_table_down.sql  # Rollback SQL
```

```php
// PHP migration class: 20250706140329_add_updated_at_to_users_table.php
require_once __DIR__ . '/BaseMigration.php';

final class AddUpdatedAtToUsersTable extends BaseMigration
{
    public function up(): void
    {
        $this->runSqlFile('20250706140329_add_updated_at_to_users_table_up.sql');
    }

    public function down(): void
    {
        $this->runSqlFile('20250706140329_add_updated_at_to_users_table_down.sql');
    }
}
```

```sql
-- File: sql/20250706140329_add_updated_at_to_users_table_up.sql
ALTER TABLE users 
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;
```

### Database Setup

**Required Environment Variables:**

```bash
DB_HOST=mariadb           # Database server host (mariadb for Docker)
DB_NAME=app_db            # Primary database name
DB_USER=db_user           # Database username
DB_PASS=db_password       # Database password
DB_PORT=3306              # Database port (default: 3306)
DB_NAME_TEST=app_db_test  # Testing database name (for Phinx testing environment)
```

**Environment Files:**

- `.env` or `.env.development` - Development settings
- `.env.production` - Production settings
- `.env.ssl` - SSL production settings

## Docker Workflow

### Initial Setup (Current Schema)

1. **Start containers:**

   ```bash
   docker-compose up -d
   ```

2. **Database ready automatically** - `schema.sql` creates all tables during container startup

3. **Optional - Run seeders:**

   ```bash
   docker exec php-app vendor/bin/phinx seed:run
   ```

### How it works

- **Container startup**: Uses `schema.sql` for complete database setup
- **No migration needed**: Current schema is already established
- **Ready to use**: Application works immediately after containers start

### Future Development Workflow (When Schema Changes Needed)

```bash
# Create new migration for future changes
docker exec php-app vendor/bin/phinx create AddNewFeatureColumn

# Run migrations (only when you have new ones)
docker exec php-app vendor/bin/phinx migrate

# Check migration status
docker exec php-app vendor/bin/phinx status

# Test rollback
docker exec php-app vendor/bin/phinx rollback
```

### Production Deployment

```bash
# Start production containers
docker-compose -f docker-compose.prod.yml up -d

# Run any new migrations (if you've added features)
docker exec php-app vendor/bin/phinx migrate -e production
```

## Seeds

Seeders populate the database with initial or test data.

#

## Best Practices

1. **Never edit migration files after they've been run in production**
2. **Always create new migrations for schema changes**
3. **Use descriptive names for migrations and seeds**
4. **Test migrations both up and down**
5. **Keep seeds idempotent (safe to run multiple times)**
6. **Edit SQL in .sql files for syntax highlighting and validation**
7. **Use database-specific SQL variants when targeting specific platforms**

## Creating New Migrations

1. **Generate migration class:**

   ```bash
   vendor/bin/phinx create MyNewMigration
   ```

2. **Create SQL files:**

   ```bash
   # Create SQL files in the sql directory (use same timestamp as PHP file)
   # Replace YYYYMMDDHHMMSS with the actual timestamp from your PHP migration file
   touch database/migrations/sql/YYYYMMDDHHMMSS_my_new_migration_up.sql
   touch database/migrations/sql/YYYYMMDDHHMMSS_my_new_migration_down.sql
   ```

3. **Edit the PHP class to extend BaseMigration:**

   ```php
   require_once __DIR__ . '/BaseMigration.php';
   
   final class MyNewMigration extends BaseMigration
   {
       public function up(): void
       {
           $this->runSqlFile('YYYYMMDDHHMMSS_my_new_migration_up.sql');
       }

       public function down(): void
       {
           $this->runSqlFile('YYYYMMDDHHMMSS_my_new_migration_down.sql');
       }
   }
   ```

4. **Write your SQL in the .sql files with full IDE support!**

5. **Run migrations:**
   ```bash
   vendor/bin/phinx migrate
   ```

## Example Migration

Here's a real example from this project showing how to add an `updated_at` column to the users table:

### Migration: Add Updated At Column to Users Table

**File:** `20250706140329_add_updated_at_to_users_table.php`

```php
final class AddUpdatedAtToUsersTable extends BaseMigration
{
    public function up(): void
    {
        $this->runSqlFile('20250706140329_add_updated_at_to_users_table_up.sql');
    }

    public function down(): void
    {
        $this->runSqlFile('20250706140329_add_updated_at_to_users_table_down.sql');
    }
}
```

**File:** `sql/20250706140329_add_updated_at_to_users_table_up.sql`

```sql
ALTER TABLE users
ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- Update existing records to set updated_at = created_at for existing users
UPDATE users 
SET updated_at = created_at 
WHERE updated_at IS NULL;
```

**File:** `sql/20250706140329_add_updated_at_to_users_table_down.sql`

```sql
ALTER TABLE users
DROP COLUMN updated_at;
```

### Running This Migration

```bash
# Check current status
docker exec php-app vendor/bin/phinx status

# Run the migration
docker exec php-app vendor/bin/phinx migrate

# Verify the column was added
docker exec php-app-mariadb mysql -u db_user -pdb_password app_db -e "DESCRIBE users;"

# If needed, rollback
docker exec php-app vendor/bin/phinx rollback
```

**What this migration does:**

- **Adds `updated_at` column** with automatic timestamp updating
- **Sets default value** to current timestamp for existing records
- **Auto-updates** whenever a user record is modified (`ON UPDATE CURRENT_TIMESTAMP`)
- **Provides clean rollback** by dropping the column

