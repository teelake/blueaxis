php-error.log — PHP errors and exceptions are written here by the application.

The .htaccess file in this folder contains "Require all denied".
That ONLY blocks browsers from downloading log files over HTTP.
It does NOT stop PHP from writing to php-error.log.

If the log file is missing, the app had no errors yet OR storage/logs is not writable.
After visiting the site once, you should see a line: "Error logging initialized".
