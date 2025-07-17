<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Online Certified Copy


## Pg Commands

# Export entire database
pg_dump -U certified_user -h localhost -d certified_copy > backup.sql

# Export with compression
pg_dump -U certified_user -h localhost -d certified_copy | gzip > backup.sql.gz

# Export specific tables
pg_dump -U certified_user -h localhost -d certified_copy -t users -t documents > tables_backup.sql

# Export data only (no schema)
pg_dump -U certified_user -h localhost -d certified_copy --data-only > data_backup.sql

# Export schema only (no data)
pg_dump -U certified_user -h localhost -d certified_copy --schema-only > schema_backup.sql


## Install DB 

# Import from SQL file
psql -U certified_user -h localhost -d certified_copy < backup.sql

# Import compressed file
gunzip -c backup.sql.gz | psql -U certified_user -h localhost -d certified_copy

# Import with verbose output
psql -U certified_user -h localhost -d certified_copy -f backup.sql -v ON_ERROR_STOP=1

# Drop and recreate database before import
dropdb -U postgres certified_copy
createdb -U postgres certified_copy
psql -U certified_user -h localhost -d certified_copy < backup.sql

## Installation

1. Clone the repository
```bash
git clone https://github.com/yourusername/online-certified-copy.git
cd online-certified-copy
