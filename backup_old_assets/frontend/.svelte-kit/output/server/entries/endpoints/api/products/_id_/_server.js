import { j as json } from "../../../../../chunks/index.js";
import { d as private_env } from "../../../../../chunks/shared-server.js";
import pg from "pg";
const createClient = () => {
  const { Pool } = pg;
  return new Pool({
    connectionString: private_env.DATABASE_URL || "postgres://neondb_owner:***@ep-cool-shadow-123456.us-east-2.aws.neon.tech/neondb",
    ssl: {
      rejectUnauthorized: false
    }
  });
};
async function GET({ params }) {
  const { id } = params;
  const pool = createClient();
  try {
    const query = "SELECT * FROM products WHERE id = $1";
    const result = await pool.query(query, [id]);
    if (result.rows.length === 0) {
      return json({ error: `Товар с ID ${id} не найден` }, { status: 404 });
    }
    return json(result.rows[0]);
  } catch (error) {
    console.error(`Ошибка при получении товара с ID ${id}:`, error);
    return json({ error: "Ошибка при получении товара" }, { status: 500 });
  } finally {
    await pool.end();
  }
}
export {
  GET
};
