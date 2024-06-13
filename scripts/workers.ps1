$timestamp = Get-Date -Format "yyyyMMdd_HHmmss"
$log_file = "logs\image_worker_$timestamp.log"
$error_log_file = "logs\image_worker_$timestamp_error.log"

Start-Process -FilePath "php" -ArgumentList ".\scripts\image_worker.php" -RedirectStandardOutput $log_file -RedirectStandardError $error_log_file -NoNewWindow -PassThru
