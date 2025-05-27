import { json } from '@sveltejs/kit';
import { env } from '$env/dynamic/private';
import pg from 'pg';

// Создаем клиент для подключения к Neon
const createClient = () => {
  const { Pool } = pg;
  return new Pool({
    connectionString: env.DATABASE_URL || 'postgres://neondb_owner:***@ep-cool-shadow-123456.us-east-2.aws.neon.tech/neondb',
    ssl: {
      rejectUnauthorized: false
    }
  });
};

// Получение информации о конкретном товаре по ID
export async function GET({ params }) {
  const { id } = params;
  const pool = createClient();
  
  try {
    // Запрос информации о товаре
    const query = 'SELECT * FROM products WHERE id = $1';
    const result = await pool.query(query, [id]);
    
    // Если товар не найден
    if (result.rows.length === 0) {
      return json({ error: `Товар с ID ${id} не найден` }, { status: 404 });
    }
    
    return json(result.rows[0]);
  } catch (error) {
    console.error(`Ошибка при получении товара с ID ${id}:`, error);
    return json({ error: 'Ошибка при получении товара' }, { status: 500 });
  } finally {
    // Закрываем соединение с базой данных
    await pool.end();
  }
}
