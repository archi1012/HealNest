import path from 'node:path';
import { fileURLToPath } from 'node:url';
import pool from '../config/database.js';
import { runSqlFiles } from './run-sql-files.js';

const currentDirectory = path.dirname(fileURLToPath(import.meta.url));

try {
  await runSqlFiles({
    directory: path.join(currentDirectory, 'seeds'),
    journalTable: 'seed_history',
  });
} finally {
  await pool.end();
}
