#!/bin/bash
# Jalankan artisan serve dengan batas upload/post yang lebih besar
# - upload_max_filesize = 32MB (8MB/gambar × 4 gambar)
# - post_max_size = 64MB (buffer untuk multipart overhead)
php -d upload_max_filesize=32M -d post_max_size=64M artisan serve "$@"
