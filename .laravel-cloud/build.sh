#!/bin/bash

echo "=== Building SvelteKit frontend ==="
cd /var/www/html/frontend
npm ci
npm run build

echo "=== Copying built assets to public directory ==="
cp -R /var/www/html/frontend/.svelte-kit/output/client/* /var/www/html/public/build/

echo "=== Frontend build and deployment complete ==="
