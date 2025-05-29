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
const testProducts = [
  {
    name: "Смартфон Pixel 7 Pro",
    description: "Флагманский смартфон Google с продвинутой камерой и чистым Android.",
    price: 79990,
    image: "https://lh3.googleusercontent.com/spp/AP8QK7eVDkXVTvwQrOX-dHpSS6zwL-RKuYcOEfKJO3FUpVBL4Cal7tUHUHxs6AxP5uyDCPJHXCYG5d3h5oNGRTOkrQI-B8naPE8Y2Zy9GWNwpJOXmF7YYcTIRrEmeQdlLZzBoPuexbaTGJjgGOaF3sQUPWyR5wQw=w100-h192-rw-no",
    category: "Смартфоны",
    availability: true,
    rating: 4.8,
    reviewCount: 246
  },
  {
    name: "Ноутбук MacBook Air M2",
    description: "Тонкий и лёгкий ноутбук Apple с процессором M2, долгим временем работы и ярким дисплеем.",
    price: 119990,
    image: "https://lh3.googleusercontent.com/spp/AP8QK7fzxaX9JoQBPF1IhRoCRgUe44bianVXxay988QA4AzbBVYgJ20Mpdg4bZGFz-Qi9GHsF8TzkhxBmaHHH4DKgGEwqAUu6oOL5cfdY2RHsLzKNDLhZ1WbZFTYRWkEoxOITWNXJLKsXcWp-9zcqK-pZWGdRCUb=w100-h67-rw-no",
    category: "Ноутбуки",
    availability: true,
    rating: 4.9,
    reviewCount: 183
  },
  {
    name: "Наушники AirPods Pro 2",
    description: "Беспроводные наушники с активным шумоподавлением, адаптивным эквалайзером и режимом прозрачности.",
    price: 24990,
    image: "https://lh3.googleusercontent.com/spp/AP8QK7dX9z6PhmRE0HKKoiuKKMO-0qYrd31UtxeAQsLe7HdvDQQn7UxMrhJlE1YDvCWj2yQ8M61XYJ1gPD7gK-vdQWxpL9Mmv-wj9Pj4tZvA5KTfkNJDLfbHHYnHm1WR7QwHxOh0eL4GNrE8QiOe6cTd7nXzuqeH=w100-h100-rw-no",
    category: "Наушники",
    availability: true,
    rating: 4.7,
    reviewCount: 329
  },
  {
    name: 'Планшет iPad Pro 12.9"',
    description: "Планшет с процессором M2, дисплеем Liquid Retina XDR и поддержкой Apple Pencil.",
    price: 129990,
    image: "https://lh3.googleusercontent.com/spp/AP8QK7eipA0CZTXYA1Hv_Kamy6bNjCt6n3fMQHawrYO6cV5bOXTH4byIKJCCCUGbJHzKkXtSFvokZ38i8WQ90OvNdMUDOdtztfWRZyM1wJUz4MSGOq8zomRVn2e8iFKnC5KO-xWtWkRFYSjCq56H80TqRNHKJaQ=w100-h76-rw-no",
    category: "Планшеты",
    availability: true,
    rating: 4.8,
    reviewCount: 156
  },
  {
    name: "Умные часы Apple Watch Ultra",
    description: "Прочные умные часы с увеличенным временем работы, улучшенным GPS и множеством функций для спорта.",
    price: 79990,
    image: "https://lh3.googleusercontent.com/spp/AP8QK7dOYxsycGP3O23A0Q0tdH-IVWLA1OWoAR-5-RHkLtM_4q5XQ2_e0BDc4aUwZxYN0e4gQgNPX0CiIxL1k_QhIAMYVBZD26lQxQB7UOvVbA3BnKv2LPFYnS-qhkQFPj0Eiq4z3c36dh5i_OAMo6fLe7lq5n8F=w100-h100-rw-no",
    category: "Умные часы",
    availability: false,
    rating: 4.6,
    reviewCount: 78
  },
  {
    name: "Фотоаппарат Sony Alpha A7 IV",
    description: "Полнокадровая беззеркальная камера с разрешением 33 Мп, 4K видео и улучшенным автофокусом.",
    price: 199990,
    image: "https://lh3.googleusercontent.com/spp/AP8QK7f6Cl03u6YudGgbpPvFrTHWqCKXCnCJAIGhJFn6KeTFZzW7uUymXyJFUBWcQYdJBJmAtLfeSBSMHxJTMMQrFG4rPRCuFfS5v3eRyOUNqvF5oSMcxaGBphjzd3xSAyeFtP3JmDGNjuDh-j9MQV5cKExXl2z_=w100-h66-rw-no",
    category: "Фототехника",
    availability: true,
    rating: 4.9,
    reviewCount: 45
  },
  {
    name: "Игровая консоль PlayStation 5",
    description: "Мощная игровая консоль с SSD накопителем, поддержкой 4K и технологией трассировки лучей.",
    price: 49990,
    image: "https://lh3.googleusercontent.com/spp/AP8QK7fwVWyO1IvTTgmJ3zHsGu6KbCPCNcWHAZXyWM3gOuZxOWrA0_pS_OBBWB-YdwoJ9ZvOgfLj2L5f0GD-nK0WwPVjEWXvw2dppz9UPwSgYHqkX_S6ieCnP-6gQMI0qM-a5FeWyGlP5XCIzV-VepWpKSV5L4U=w100-h81-rw-no",
    category: "Игровые консоли",
    availability: false,
    rating: 4.8,
    reviewCount: 412
  },
  {
    name: "Умная колонка Яндекс Станция Мини",
    description: "Компактная умная колонка с голосовым помощником Алиса, качественным звуком и умным домом.",
    price: 5990,
    image: "https://lh3.googleusercontent.com/spp/AP8QK7cI4YIWRXeNlIu0FD14kTxezIQX1z-KMpGgUWUg7I18Ium-Lw-GxkN11yMDiIzTg-1S9WXpZzlp0Fz_JqM_U5xpjOoR9cpvBUJsdPvt7fQjfaxr5_YSKs-3mzR9g9KT1tOw_oc1OXf2qbWsq4oBe6HZ-Q4I=w100-h100-rw-no",
    category: "Умный дом",
    availability: true,
    rating: 4.5,
    reviewCount: 267
  },
  {
    name: "Робот-пылесос Xiaomi Robot Vacuum S10+",
    description: "Умный робот-пылесос с лазерным дальномером, мощным всасыванием и функцией влажной уборки.",
    price: 29990,
    image: "https://lh3.googleusercontent.com/spp/AP8QK7eqRdZG7Uy6abK2vLsQSXHGpb8fQVP8Y2Qd4ixRuBLQVfQ1ZKswUCWX81XS_Ui2KyEQr97P5I9fBxOAMp_S9UJqJx0ZwGJVGGK9fBL4TRG9CpV45BF7k_Ir-qG7YnI7lUimCz7k_cj35E2QOVLPkBFrQ-Ok=w100-h100-rw-no",
    category: "Умный дом",
    availability: true,
    rating: 4.6,
    reviewCount: 132
  },
  {
    name: "Графический планшет Wacom Intuos Pro",
    description: "Профессиональный графический планшет с чувствительным к нажатию пером и настраиваемыми кнопками.",
    price: 39990,
    image: "https://lh3.googleusercontent.com/spp/AP8QK7cD9mDRGnhST8Xo-aKwVQ15f0wchNnCLMZCYPqE-I_SQv9UZCzQe_6T4eWIb4Lsi4-4WJCdEd8-ZZY3wBskVkLDwXb6cXX5F5hLTnylvpRJ7Oj1JMfBrXx3jgLh-Vc1mJ7pR4QZnOZWDOtDwjCWqVBHMCLe=w100-h71-rw-no",
    category: "Периферия",
    availability: true,
    rating: 4.7,
    reviewCount: 87
  },
  {
    name: 'Монитор LG UltraGear 27" 4K',
    description: "4K игровой монитор с частотой обновления 144 Гц, HDR и поддержкой NVIDIA G-Sync.",
    price: 49990,
    image: "https://lh3.googleusercontent.com/spp/AP8QK7d-JG3pOqtU3MxBGD7NbVT-Vx-4jzP6R9cXn9xvuQSLuYV_hLyEV2zW8hzTOrgwNYg4IEQaTL0ZK3h1VFgD2FIBZllG3zRubmjIgmWf0IFYvDgRq1Sx4Xen-cE6f2oJ6KOkGTVzPIRJGdjR0NZh2mXiB-WP=w100-h68-rw-no",
    category: "Мониторы",
    availability: true,
    rating: 4.5,
    reviewCount: 124
  },
  {
    name: "Клавиатура Logitech MX Keys",
    description: "Беспроводная клавиатура с подсветкой, низкопрофильными клавишами и подключением к нескольким устройствам.",
    price: 9990,
    image: "https://lh3.googleusercontent.com/spp/AP8QK7f3nSLCCdmBZ1B8n-2S4DLp1rvTDdlHSO_-qyEEDsw--gTIAU8MsQm5RfcwJ2_l2KQmxWOlGWg40y_j1dCmvwMTplxjMhB_3ymPcR8b6KrRRYjf2Zj3EQrfKr2HQVAc_c7h1N_GEejn85pEQdKJk-PJQsU=w100-h50-rw-no",
    category: "Периферия",
    availability: true,
    rating: 4.8,
    reviewCount: 198
  }
];
async function POST() {
  const pool = createClient();
  try {
    await pool.query(`
      CREATE TABLE IF NOT EXISTS products (
        id SERIAL PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        price NUMERIC(10, 2) NOT NULL,
        image TEXT,
        category VARCHAR(100),
        availability BOOLEAN DEFAULT TRUE,
        rating NUMERIC(3, 1),
        review_count INTEGER,
        attributes JSONB,
        created_at TIMESTAMP DEFAULT NOW(),
        updated_at TIMESTAMP DEFAULT NOW()
      )
    `);
    const countResult = await pool.query("SELECT COUNT(*) FROM products");
    const count = parseInt(countResult.rows[0].count);
    if (count > 0) {
      return json({
        message: `Таблица products уже содержит ${count} товаров`
      });
    }
    for (const product of testProducts) {
      await pool.query(
        `INSERT INTO products (name, description, price, image, category, availability, rating, review_count)
         VALUES ($1, $2, $3, $4, $5, $6, $7, $8)`,
        [
          product.name,
          product.description,
          product.price,
          product.image,
          product.category,
          product.availability,
          product.rating,
          product.reviewCount
        ]
      );
    }
    return json({
      message: `Успешно создано ${testProducts.length} тестовых товаров`
    });
  } catch (error) {
    console.error("Ошибка при создании тестовых данных:", error);
    return json({ error: "Ошибка при создании тестовых данных" }, { status: 500 });
  } finally {
    await pool.end();
  }
}
export {
  POST
};
