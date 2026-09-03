import pool from '../config/database.js';

const questionBanks = {
  PHQ9: ['Little interest or pleasure in doing things', 'Feeling down, depressed, or hopeless', 'Trouble falling or staying asleep, or sleeping too much', 'Feeling tired or having little energy', 'Poor appetite or overeating', 'Feeling bad about yourself', 'Trouble concentrating on things', 'Moving or speaking slowly (or being fidgety/restless)', 'Thoughts that you would be better off dead'],
  GAD7: ['Feeling nervous, anxious, or on edge', 'Not being able to stop or control worrying', 'Worrying too much about different things', 'Trouble relaxing', 'Being so restless that it is hard to sit still', 'Becoming easily annoyed or irritable', 'Feeling afraid, as if something awful might happen'],
  GENERAL: ['I have felt happy and positive about life', 'I have been able to manage stress well', 'I have been sleeping well and feeling rested', 'I have felt connected to people around me', 'I have had energy to do things I enjoy', 'I have felt calm and at ease', 'I have been able to concentrate on tasks', 'I have felt good about myself', 'I have been able to cope with daily challenges', 'Overall, I feel my mental well-being is good'],
};
const riskFor = (score) => score <= 4 ? 'minimal' : score <= 9 ? 'mild' : score <= 14 ? 'moderate' : 'severe';

export async function assessmentIndex(request, response, next) {
  try { const { rows } = await pool.query('SELECT DISTINCT ON (type) id, type, score, risk_level, taken_at FROM assessments WHERE user_id = $1 ORDER BY type, taken_at DESC', [request.user.id]); return response.json({ lastTaken: Object.fromEntries(rows.map((row) => [row.type, row])) }); } catch (error) { next(error); }
}
export function assessmentQuestions(request, response) { const type = request.params.type.toUpperCase(); return questionBanks[type] ? response.json({ type, questions: questionBanks[type] }) : response.status(404).json({ message: 'Assessment type not found.' }); }
export async function submitAssessment(request, response, next) {
  const type = request.params.type.toUpperCase(); const questions = questionBanks[type]; const { responses } = request.body;
  if (!questions || !Array.isArray(responses) || responses.length !== questions.length || responses.some((value) => !Number.isInteger(value) || value < 0 || value > 3)) return response.status(422).json({ message: 'Validation failed.', errors: { responses: 'All assessment responses must be integers from 0 to 3.' } });
  const score = responses.reduce((sum, value) => sum + value, 0); const riskLevel = riskFor(score); const client = await pool.connect();
  try { await client.query('BEGIN'); const result = await client.query('INSERT INTO assessments (user_id, type, score, risk_level) VALUES ($1, $2, $3, $4) RETURNING id, type, score, risk_level, taken_at', [request.user.id, type, score, riskLevel]); for (const [index, value] of responses.entries()) await client.query('INSERT INTO assessment_responses (assessment_id, question_index, response_value) VALUES ($1, $2, $3)', [result.rows[0].id, index, value]); if (score >= 10) await client.query("INSERT INTO alerts (user_id, assessment_id, risk_level, status) VALUES ($1, $2, $3, 'open')", [request.user.id, result.rows[0].id, riskLevel]); await client.query('COMMIT'); return response.status(201).json({ assessment: result.rows[0] }); } catch (error) { await client.query('ROLLBACK'); next(error); } finally { client.release(); }
}
export async function assessmentResult(request, response, next) { try { const { rows } = await pool.query('SELECT id, type, score, risk_level, taken_at FROM assessments WHERE id = $1 AND user_id = $2', [request.params.id, request.user.id]); return rows[0] ? response.json({ assessment: rows[0] }) : response.status(404).json({ message: 'Assessment not found.' }); } catch (error) { next(error); } }
