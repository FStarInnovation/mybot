/**
 * Хранилище AG-UI для интеграции с чат-интерфейсом
 */
import { writable } from 'svelte/store';
import type { 
  AgUIEvent, 
  AgUITextEvent, 
  AgUICardEvent, 
  AgUIFormEvent, 
  AgUIListEvent, 
  AgUIToolCallEvent, 
  AgUIToolResultEvent, 
  AgUIStatusEvent,
  AgUIAction,
  AgUICard,
  AgUIFormField
} from './types';
import { AgUIEventType } from './types';

// Тип для массива событий, который будет использоваться в хранилище
type AgUIEventArray = Array<AgUIEvent>;

// Хранилище событий AG-UI
function createAgUIStore() {
  const { subscribe, update, set } = writable<AgUIEventArray>([]);

  return {
    subscribe,
    // Добавление события
    addEvent: (event: AgUIEvent) => {
      update(events => [...events, event]);
    },
    // Обновление события
    updateEvent: (id: string, eventData: Partial<AgUIEvent>) => {
      update(events => events.map(event => 
        event.id === id ? { ...event, ...eventData } as AgUIEvent : event
      ));
    },
    // Удаление события
    removeEvent: (id: string) => {
      update(events => events.filter(event => event.id !== id));
    },
    // Отправка текстового сообщения от пользователя
    sendUserText: (text: string) => {
      const event: AgUITextEvent = {
        type: AgUIEventType.TEXT,
        id: `user-${Date.now()}`,
        timestamp: new Date().toISOString(),
        sender: 'user',
        text,
        markdown: false
      };
      update(events => [...events, event]);
      return event;
    },
    // Добавление текстового сообщения от бота
    addBotText: (text: string, markdown = false) => {
      const event: AgUITextEvent = {
        type: AgUIEventType.TEXT,
        id: `bot-${Date.now()}`,
        timestamp: new Date().toISOString(),
        sender: 'bot',
        text,
        markdown
      };
      update(events => [...events, event]);
      return event;
    },
    // Добавление карточки
    addCard: (card: AgUICard) => {
      const event: AgUICardEvent = {
        type: AgUIEventType.CARD,
        id: `card-${Date.now()}`,
        timestamp: new Date().toISOString(),
        sender: 'bot',
        card
      };
      update(events => [...events, event]);
      return event;
    },
    // Добавление формы
    addForm: (formData: {
      title?: string;
      description?: string;
      fields: AgUIFormField[];
      submitLabel?: string;
      cancelLabel?: string;
    }) => {
      const event: AgUIFormEvent = {
        type: AgUIEventType.FORM,
        id: `form-${Date.now()}`,
        timestamp: new Date().toISOString(),
        sender: 'bot',
        title: formData.title,
        description: formData.description,
        fields: formData.fields,
        submitLabel: formData.submitLabel,
        cancelLabel: formData.cancelLabel
      };
      update(events => [...events, event]);
      return event;
    },
    // Добавление списка
    addList: (listData: {
      title?: string;
      items: {
        id: string;
        title: string;
        description?: string;
        image?: string;
        actions?: AgUIAction[];
      }[];
    }) => {
      const event: AgUIListEvent = {
        type: AgUIEventType.LIST,
        id: `list-${Date.now()}`,
        timestamp: new Date().toISOString(),
        sender: 'bot',
        title: listData.title,
        items: listData.items
      };
      update(events => [...events, event]);
      return event;
    },
    // Добавление вызова инструмента
    addToolCall: (tool: string, args: any) => {
      const event: AgUIToolCallEvent = {
        type: AgUIEventType.TOOL_CALL,
        id: `tool-call-${Date.now()}`,
        timestamp: new Date().toISOString(),
        sender: 'bot',
        tool,
        args
      };
      update(events => [...events, event]);
      return event;
    },
    // Добавление результата инструмента
    addToolResult: (tool: string, result: any) => {
      const event: AgUIToolResultEvent = {
        type: AgUIEventType.TOOL_RESULT,
        id: `tool-result-${Date.now()}`,
        timestamp: new Date().toISOString(),
        sender: 'user',
        tool,
        result
      };
      update(events => [...events, event]);
      return event;
    },
    // Добавление статуса
    addStatus: (status: 'info' | 'success' | 'warning' | 'error', message: string) => {
      const event: AgUIStatusEvent = {
        type: AgUIEventType.STATUS,
        id: `status-${Date.now()}`,
        timestamp: new Date().toISOString(),
        sender: 'bot',
        status,
        message
      };
      update(events => [...events, event]);
      return event;
    },
    // Сброс хранилища
    reset: () => set([])
  };
}

export const agUIStore = createAgUIStore();
