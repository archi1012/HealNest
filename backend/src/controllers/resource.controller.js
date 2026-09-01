import pool from '../config/database.js';
export async function resources(_request, response, next) { try { const { rows } = await pool.query('SELECT id, title, category, icon, description, external_url FROM resources ORDER BY created_at DESC'); response.json({ resources: rows }); } catch (error) { next(error); } }
