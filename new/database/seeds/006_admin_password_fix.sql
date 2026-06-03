-- Fix admin password when login fails (hash must match a password you know).
-- Default after this update: ChangeMe123! — then run set-admin-password.php on the server.
UPDATE admins SET password = '$2y$12$hqKLVmhT9p9r1fXWI.N4henAJtPcApcD0eX97QwOm8dUfiGirRCly'
WHERE email = 'admin@blueaxis.com';
