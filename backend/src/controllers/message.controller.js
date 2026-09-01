import pool from '../config/database.js';

const contactRoles = (role) => ['counselor', 'admin'].includes(role) ? ['user', 'parent'] : ['counselor'];
const invalid = (response, errors) => response.status(422).json({ message: 'Validation failed.', errors });

async function contactsFor(user) { const { rows } = await pool.query('SELECT id, name, email, role FROM users WHERE role = ANY($1) ORDER BY name', [contactRoles(user.role)]); return rows; }
async function threadsFor(userId) {
  const { rows } = await pool.query('SELECT * FROM messages WHERE sender_id = $1 OR recipient_id = $1 ORDER BY created_at DESC', [userId]);
  const groups = new Map();
  for (const message of rows) { const partnerId = message.sender_id === userId ? message.recipient_id : message.sender_id; if (!groups.has(partnerId)) groups.set(partnerId, []); groups.get(partnerId).push(message); }
  const ids = [...groups.keys()]; if (!ids.length) return [];
  const users = await pool.query('SELECT id, name, email, role FROM users WHERE id = ANY($1)', [ids]); const people = new Map(users.rows.map((person) => [person.id, person]));
  return [...groups.entries()].map(([id, group]) => ({ partner: people.get(id), latestMessage: group[0], unreadCount: group.filter((message) => message.recipient_id === userId && !message.read_at).length })).filter((thread) => thread.partner);
}

export async function messages(request, response, next) {
  try {
    const contacts = await contactsFor(request.user); const activePartnerId = request.query.with || contacts[0]?.id || null;
    const activePartner = contacts.find((contact) => contact.id === activePartnerId) || null;
    let conversation = [];
    if (activePartner) {
      const result = await pool.query('SELECT * FROM messages WHERE (sender_id = $1 AND recipient_id = $2) OR (sender_id = $2 AND recipient_id = $1) ORDER BY created_at', [request.user.id, activePartnerId]); conversation = result.rows;
      await pool.query('UPDATE messages SET read_at = NOW() WHERE sender_id = $1 AND recipient_id = $2 AND read_at IS NULL', [activePartnerId, request.user.id]);
    }
    response.json({ contacts, threads: await threadsFor(request.user.id), activePartner, activePartnerId, messages: conversation });
  } catch (error) { next(error); }
}

export async function createMessage(request, response, next) {
  try {
    const { recipientId, body } = request.body;
    if (typeof recipientId !== 'string' || typeof body !== 'string' || !body.trim() || body.length > 2000) return invalid(response, { message: 'Recipient and a message up to 2,000 characters are required.' });
    const allowed = await pool.query('SELECT id FROM users WHERE id = $1 AND role = ANY($2)', [recipientId, contactRoles(request.user.role)]);
    if (!allowed.rowCount) return invalid(response, { recipientId: 'That recipient is not available for messaging.' });
    const { rows } = await pool.query('INSERT INTO messages (sender_id, recipient_id, body) VALUES ($1, $2, $3) RETURNING *', [request.user.id, recipientId, body.trim()]);
    response.status(201).json({ message: rows[0] });
  } catch (error) { next(error); }
}
