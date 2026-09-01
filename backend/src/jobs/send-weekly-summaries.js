import pool from '../config/database.js';
import { sendWeeklySummaries } from '../services/weekly-summary.service.js';
try { console.log(`Sent ${await sendWeeklySummaries()} weekly summaries.`); } finally { await pool.end(); }
