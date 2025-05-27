# MyBot Frontend

A modern, progressive web application built with SvelteKit and TypeScript.

## Features

- 🚀 Built with SvelteKit
- 📱 PWA Support (offline mode, installable)
- 🎨 Responsive design
- ⚡ Fast and optimized performance
- 🔄 API proxy to Laravel backend

## Prerequisites

- Node.js 16.x or later
- npm 8.x or later

## Getting Started

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd mybot/frontend
   ```

2. **Install dependencies**
   ```bash
   npm install
   ```

3. **Start the development server**
   ```bash
   npm run dev
   ```
   The app will be available at `http://localhost:3002`

4. **Build for production**
   ```bash
   npm run build
   ```

## Project Structure

```
frontend/
├── src/
│   ├── lib/
│   │   ├── components/     # Reusable components
│   │   └── registerSW.ts   # Service worker registration
│   ├── routes/            # Application routes
│   ├── app.css            # Global styles
│   └── app.html           # Main HTML template
├── static/                # Static assets
│   ├── favicon.png
│   ├── pwa-192x192.png
│   ├── pwa-512x512.png
│   └── manifest.json
├── .gitignore
├── package.json
├── svelte.config.js
├── tsconfig.json
└── vite.config.ts
```

## PWA Features

The application includes the following PWA features:

- Offline support with service worker
- Install prompt for mobile and desktop
- Splash screen on app launch
- Automatic updates
- Caching of static assets and API responses

## Environment Variables

Create a `.env` file in the project root with the following variables:

```env
VITE_API_BASE_URL=http://localhost:8000/api
```

## Development

- `npm run dev`: Start development server
- `npm run build`: Build for production
- `npm run preview`: Preview production build
- `npm run check`: Run type checking
- `npm run lint`: Run ESLint
- `npm run format`: Format code with Prettier

## Deployment

The application can be deployed to any static hosting service that supports SvelteKit, such as:

- Vercel
- Netlify
- Cloudflare Pages
- S3 + CloudFront

## License

MIT
