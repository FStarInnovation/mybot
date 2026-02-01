import pandas as pd

# Read the Excel file
df = pd.read_excel('/Users/zv/projects/mybot/products_export_2026-01-29_17-48-22.xlsx')

# Search for exact La Roche Posay Oil Control product (not combos)
mask = (df['Title'].str.contains('La Roche Posay', case=False, na=False) & 
        df['Title'].str.contains('Oil Control', case=False, na=False) &
        ~df['Title'].str.contains('Combo', case=False, na=False))

results = df[mask]

if len(results) > 0:
    for idx, row in results.iterrows():
        print(f'Товар: {row["Title"]}')
        price = row["Price"]
        print(f'Цена: ${price:,.2f}')
        discount = row["Discount %"]
        if pd.notna(discount):
            print(f'Скидка: {discount}%')
        else:
            print('Скидка: нет')
        print(f'Ссылка: {row["URL"]}')
        print(f'Бренд: {row["Brand"]}')
        print(f'SKU: {row["SKU ID"]}')
        print(f'Категория: {row["Categories"]}')
        print(f'Наличие: {row["Availability"]}')
        print('-' * 60)
else:
    print('Отдельный товар La Roche Posay Oil Control не найден')
    print('Показаны все найденные товары с La Roche Posay Oil Control (включая комбо):')
    
    mask_all = df['Title'].str.contains('La Roche Posay', case=False, na=False) & df['Title'].str.contains('Oil Control', case=False, na=False)
    results_all = df[mask_all]
    
    for idx, row in results_all.iterrows():
        print(f'Товар: {row["Title"]}')
        price = row["Price"]
        print(f'Цена: ${price:,.2f}')
        discount = row["Discount %"]
        if pd.notna(discount):
            print(f'Скидка: {discount}%')
        else:
            print('Скидка: нет')
        print(f'Ссылка: {row["URL"]}')
        print('-' * 40)
