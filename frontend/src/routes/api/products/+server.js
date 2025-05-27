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

// Получение списка всех товаров с пагинацией
export async function GET({ url }) {
  const page = parseInt(url.searchParams.get('page') || '1');
  const limit = parseInt(url.searchParams.get('limit') || '10');
  const category = url.searchParams.get('category');
  const search = url.searchParams.get('search');
  const sort = url.searchParams.get('sort') || 'name_asc';
  
  const offset = (page - 1) * limit;
  const pool = createClient();
  
  try {
    // Начинаем строить запрос
    let query = 'SELECT * FROM products';
    const values = [];
    let whereClause = [];
    let index = 1;
    
    // Добавляем условия фильтрации, если они заданы
    if (category) {
      whereClause.push(`category = $${index++}`);
      values.push(category);
    }
    
    if (search) {
      whereClause.push(`(name ILIKE $${index++} OR description ILIKE $${index++})`);
      values.push(`%${search}%`, `%${search}%`);
    }
    
    if (whereClause.length > 0) {
      query += ' WHERE ' + whereClause.join(' AND ');
    }
    
    // Добавляем сортировку
    switch(sort) {
      case 'price_asc':
        query += ' ORDER BY price ASC';
        break;
      case 'price_desc':
        query += ' ORDER BY price DESC';
        break;
      case 'name_desc':
        query += ' ORDER BY name DESC';
        break;
      case 'rating':
        query += ' ORDER BY rating DESC NULLS LAST';
        break;
      case 'newest':
        query += ' ORDER BY created_at DESC';
        break;
      default:
        query += ' ORDER BY name ASC';
    }
    
    // Добавляем пагинацию
    query += ` LIMIT $${index++} OFFSET $${index++}`;
    values.push(limit, offset);
    
    // Выполняем запрос
    const result = await pool.query(query, values);
    
    // Получаем общее количество товаров для пагинации
    let countQuery = 'SELECT COUNT(*) as total FROM products';
    if (whereClause.length > 0) {
      countQuery += ' WHERE ' + whereClause.join(' AND ');
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
    console.error('Ошибка при получении товаров из БД:', error);
    return json({ error: 'Ошибка при получении товаров' }, { status: 500 });
  } finally {
    // Закрываем соединение с базой данных
    await pool.end();
  }
}
