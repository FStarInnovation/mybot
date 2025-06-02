/// <reference types="@sveltejs/kit" />

// Service Worker and Push API Type Definitions
interface ExtendableEvent extends Event {
  waitUntil(promise: Promise<any>): void;
}

interface PushEvent extends ExtendableEvent {
  readonly data: PushMessageData;
}

interface PushMessageData {
  arrayBuffer(): Promise<ArrayBuffer>;
  blob(): Promise<Blob>;
  json<T = any>(): Promise<T>;
  text(): Promise<string>;
  formData(): Promise<FormData>;
}

interface NotificationEvent extends ExtendableEvent {
  readonly notification: Notification;
  readonly action?: string;
}

interface Client {
  readonly id: string;
  readonly url: string;
  readonly type: string;
  postMessage(message: any, transfer?: Transferable[]): void;
  focus(): void;
}

interface Clients {
  matchAll(options?: { includeUncontrolled?: boolean; type?: string }): Promise<Client[]>;
  openWindow(url: string): Promise<WindowClient | null>;
  claim(): Promise<void>;
}

declare var clients: Clients;

declare interface ServiceWorkerGlobalScope {
  readonly clients: Clients;
  readonly registration: ServiceWorkerRegistration;
  skipWaiting(): Promise<void>;
  addEventListener(
    type: 'install' | 'activate' | 'fetch' | 'push' | 'notificationclick' | 'sync',
    listener: (event: any) => void,
    options?: boolean | AddEventListenerOptions
  ): void;
}

declare var self: ServiceWorkerGlobalScope;

// App types
declare namespace App {
  interface Locals {}
  interface PageData {}
  interface Platform {}
}

// Environment variables
declare namespace NodeJS {
  interface ProcessEnv {
    VITE_VAPID_PUBLIC_KEY: string;
  }
}
declare module '*.svelte' {
  import type { ComponentType } from 'svelte';
  const component: ComponentType;
  export default component;
}
