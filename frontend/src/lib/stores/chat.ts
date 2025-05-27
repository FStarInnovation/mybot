import { writable } from 'svelte/store';

// Типы сообщений по спецификации AG-UI
export enum MessageType {
  TEXT = 'text',
  TOOL_CALL = 'tool_call',
  TOOL_RESPONSE = 'tool_response',
  FORM = 'form',
  FORM_RESPONSE = 'form_response',
  CARD = 'card',
  LIST = 'list',
  STATUS = 'status'
}

// Интерфейс базового сообщения
export interface BaseMessage {
  id: string;
  timestamp: string;
  sender: 'user' | 'bot';
}

// Текстовое сообщение
export interface TextMessage extends BaseMessage {
  type: MessageType.TEXT;
  content: string;
}

// Карточка товара
export interface ProductCard {
  id: string;
  title: string;
  description: string;
  price: string;
  imageUrl?: string;
  details?: Record<string, string>;
}

// Сообщение с карточкой товара
export interface CardMessage extends BaseMessage {
  type: MessageType.CARD;
  card: ProductCard;
}

// Вызов инструмента (tool call)
export interface ToolCall extends BaseMessage {
  type: MessageType.TOOL_CALL;
  tool: string;
  arguments: Record<string, any>;
}

// Ответ инструмента
export interface ToolResponse extends BaseMessage {
  type: MessageType.TOOL_RESPONSE;
  toolCall: string; // ID вызова инструмента
  result: any;
}

// Форма для ввода данных
export interface FormField {
  id: string;
  label: string;
  type: 'text' | 'number' | 'select' | 'checkbox' | 'date';
  required?: boolean;
  options?: string[]; // Для select
  defaultValue?: any;
}

export interface FormMessage extends BaseMessage {
  type: MessageType.FORM;
  title: string;
  fields: FormField[];
  submitLabel?: string;
}

export interface FormResponseMessage extends BaseMessage {
  type: MessageType.FORM_RESPONSE;
  formId: string; // ID формы
  values: Record<string, any>;
}

// Список элементов
export interface ListItem {
  id: string;
  title: string;
  description?: string;
  imageUrl?: string;
  action?: string;
}

export interface ListMessage extends BaseMessage {
  type: MessageType.LIST;
  title?: string;
  items: ListItem[];
}

// Сообщение о статусе
export interface StatusMessage extends BaseMessage {
  type: MessageType.STATUS;
  status: 'info' | 'success' | 'warning' | 'error';
  message: string;
}

// Объединенный тип сообщения
export type Message = 
  | TextMessage 
  | CardMessage 
  | ToolCall 
  | ToolResponse 
  | FormMessage 
  | FormResponseMessage 
  | ListMessage 
  | StatusMessage;

// Создание хранилища сообщений
function createChatStore() {
  const { subscribe, update, set } = writable<Message[]>([]);

  return {
    subscribe,
    addMessage: (message: Message) => {
      update(messages => [...messages, message]);
    },
    // Создание текстового сообщения от пользователя
    sendUserMessage: (content: string) => {
      const message: TextMessage = {
        id: Date.now().toString(),
        timestamp: new Date().toISOString(),
        sender: 'user',
        type: MessageType.TEXT,
        content
      };
      update(messages => [...messages, message]);
      return message;
    },
    // Создание текстового сообщения от бота
    addBotMessage: (content: string) => {
      const message: TextMessage = {
        id: Date.now().toString(),
        timestamp: new Date().toISOString(),
        sender: 'bot',
        type: MessageType.TEXT,
        content
      };
      update(messages => [...messages, message]);
      return message;
    },
    // Добавление карточки товара
    addProductCard: (product: ProductCard) => {
      const message: CardMessage = {
        id: Date.now().toString(),
        timestamp: new Date().toISOString(),
        sender: 'bot',
        type: MessageType.CARD,
        card: product
      };
      update(messages => [...messages, message]);
      return message;
    },
    // Отправка формы
    sendForm: (title: string, fields: FormField[]) => {
      const message: FormMessage = {
        id: Date.now().toString(),
        timestamp: new Date().toISOString(),
        sender: 'bot',
        type: MessageType.FORM,
        title,
        fields,
        submitLabel: 'Отправить'
      };
      update(messages => [...messages, message]);
      return message;
    },
    // Ответ на форму
    submitForm: (formId: string, values: Record<string, any>) => {
      const message: FormResponseMessage = {
        id: Date.now().toString(),
        timestamp: new Date().toISOString(),
        sender: 'user',
        type: MessageType.FORM_RESPONSE,
        formId,
        values
      };
      update(messages => [...messages, message]);
      return message;
    },
    // Отправка списка элементов
    sendList: (title: string, items: ListItem[]) => {
      const message: ListMessage = {
        id: Date.now().toString(),
        timestamp: new Date().toISOString(),
        sender: 'bot',
        type: MessageType.LIST,
        title,
        items
      };
      update(messages => [...messages, message]);
      return message;
    },
    // Инициализация или сброс хранилища
    reset: () => set([]),
  };
}

export const chatStore = createChatStore();
