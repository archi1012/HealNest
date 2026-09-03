import app from './app.js';
import env from './config/env.js';

console.log('Using DATABASE_URL:', env.databaseUrl);
app.listen(env.port, () => {
  console.log(`HealNest API listening on port ${env.port}`);
});
