import { readdir, readFile } from 'node:fs/promises';
import path from 'node:path';
import pool from '../config/database.js';

export async function runSqlFiles({ directory, journalTable }) {
  const files = (await readdir(directory))
    .filter((file) => file.endsWith('.sql'))
    .sort();

  await pool.query(`
    CREATE TABLE IF NOT EXISTS ${journalTable} (
      filename TEXT PRIMARY KEY,
      applied_at TIMESTAMPTZ NOT NULL DEFAULT NOW()
    )
  `);

  for (const filename of files) {
    const applied = await pool.query(
      `SELECT 1 FROM ${journalTable} WHERE filename = $1`,
      [filename],
    );

    if (applied.rowCount) continue;

    const sql = await readFile(path.join(directory, filename), 'utf8');
    const client = await pool.connect();

    try {
      await client.query('BEGIN');
      await client.query(sql);
      await client.query(`INSERT INTO ${journalTable} (filename) VALUES ($1)`, [filename]);
      await client.query('COMMIT');
      console.log(`Applied ${filename}`);
    } catch (error) {
      await client.query('ROLLBACK');
      throw error;
    } finally {
      client.release();
    }
  }
}
