import path from 'node:path';
import { fileURLToPath } from 'node:url';
import pool from '../config/database.js';
import { runSqlFiles } from './run-sql-files.js';

const currentDirectory = path.dirname(fileURLToPath(import.meta.url));

try {
  await runSqlFiles({
    directory: path.join(currentDirectory, 'migrations'),
    journalTable: 'schema_migrations',
  });
} finally {
  await pool.end();
}
