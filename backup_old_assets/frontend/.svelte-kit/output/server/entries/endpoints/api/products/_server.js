import { j as json } from "../../../../chunks/index.js";
import { d as private_env } from "../../../../chunks/shared-server.js";
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
async function GET({ url }) {
  const page = parseInt(url.searchParams.get("page") || "1");
  const limit = parseInt(url.searchParams.get("limit") || "10");
  const category = url.searchParams.get("category");
  const search = url.searchParams.get("search");
  const sort = url.searchParams.get("sort") || "name_asc";
  const offset = (page - 1) * limit;
  const pool = createClient();
  try {
    let query = "SELECT * FROM products";
    const values = [];
    let whereClause = [];
    let index = 1;
    if (category) {
      whereClause.push(`category = $${index++}`);
      values.push(category);
    }
    if (search) {
      whereClause.push(`(name ILIKE $${index++} OR description ILIKE $${index++})`);
      values.push(`%${search}%`, `%${search}%`);
    }
    if (whereClause.length > 0) {
      query += " WHERE " + whereClause.join(" AND ");
    }
    switch (sort) {
      case "price_asc":
        query += " ORDER BY price ASC";
        break;
      case "price_desc":
        query += " ORDER BY price DESC";
        break;
      case "name_desc":
        query += " ORDER BY name DESC";
        break;
      case "rating":
        query += " ORDER BY rating DESC NULLS LAST";
        break;
      case "newest":
        query += " ORDER BY created_at DESC";
        break;
      default:
        query += " ORDER BY name ASC";
    }
    query += ` LIMIT $${index++} OFFSET $${index++}`;
    values.push(limit, offset);
    const result = await pool.query(query, values);
    let countQuery = "SELECT COUNT(*) as total FROM products";
    if (whereClause.length > 0) {
      countQuery += " WHERE " + whereClause.join(" AND ");
    }
    const countResult = await pool.query(countQuery, values.slice(0, index - 3));
    const total = parseInt(countResult.rows[0].total);
    return json({
      products: result.rows,
      total,
      page,
      limit
    });
  } catch (error) {
    console.error("Ошибка при получении товаров из БД:", error);
    return json({ error: "Ошибка при получении товаров" }, { status: 500 });
  } finally {
    await pool.end();
  }
}
export {
  GET
};
