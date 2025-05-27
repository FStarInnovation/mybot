/**
 * Адаптер типов AG-UI для интеграции с чат-интерфейсом
 * На основе https://github.com/ag-ui-protocol/ag-ui
 */

// Основные типы сообщений AG-UI
export enum AgUIEventType {
  TEXT = 'text',
  ACTION = 'action',
  CARD = 'card',
  FORM = 'form',
  TABLE = 'table',
  CHART = 'chart',
  IMAGE = 'image',
  FILE = 'file',
  LIST = 'list',
  STATUS = 'status',
  TOOL_CALL = 'tool_call',
  TOOL_RESULT = 'tool_result',
  ERROR = 'error',
  THINKING = 'thinking',
  DELTA = 'delta',
  CUSTOM = 'custom'
}

// Базовое событие AG-UI
export interface AgUIBaseEvent {
  type: AgUIEventType;
  id: string;
  timestamp?: string;
  sender?: 'user' | 'bot';
}

// Текстовое сообщение
export interface AgUITextEvent extends AgUIBaseEvent {
  type: AgUIEventType.TEXT;
  text: string;
  markdown?: boolean;
}

// Действие
export interface AgUIAction {
  id: string;
  label: string;
  description?: string;
  icon?: string;
  disabled?: boolean;
  style?: 'primary' | 'secondary' | 'danger' | 'success' | 'warning' | 'info';
}

// Событие действия
export interface AgUIActionEvent extends AgUIBaseEvent {
  type: AgUIEventType.ACTION;
  actions: AgUIAction[];
}

// Карточка
export interface AgUICard {
  title: string;
  description?: string;
  image?: string;
  actions?: AgUIAction[];
  fields?: {
    name: string;
    value: string;
    inline?: boolean;
  }[];
  footer?: string;
  url?: string;
}

// Событие карточки
export interface AgUICardEvent extends AgUIBaseEvent {
  type: AgUIEventType.CARD;
  card: AgUICard;
}

// Поле формы
export interface AgUIFormField {
  id: string;
  label: string;
  type: 'text' | 'number' | 'email' | 'password' | 'select' | 'checkbox' | 'radio' | 'textarea' | 'date';
  placeholder?: string;
  required?: boolean;
  value?: any;
  options?: { label: string; value: string }[];
  validation?: {
    pattern?: string;
    min?: number;
    max?: number;
    minLength?: number;
    maxLength?: number;
  };
}

// Событие формы
export interface AgUIFormEvent extends AgUIBaseEvent {
  type: AgUIEventType.FORM;
  title?: string;
  description?: string;
  fields: AgUIFormField[];
  submitLabel?: string;
  cancelLabel?: string;
}

// Событие таблицы
export interface AgUITableEvent extends AgUIBaseEvent {
  type: AgUIEventType.TABLE;
  title?: string;
  headers: string[];
  rows: any[][];
  pagination?: {
    currentPage: number;
    totalPages: number;
    pageSize: number;
  };
}

// Событие списка
export interface AgUIListEvent extends AgUIBaseEvent {
  type: AgUIEventType.LIST;
  title?: string;
  items: {
    id: string;
    title: string;
    description?: string;
    image?: string;
    actions?: AgUIAction[];
  }[];
}

// Событие статуса
export interface AgUIStatusEvent extends AgUIBaseEvent {
  type: AgUIEventType.STATUS;
  status: 'info' | 'success' | 'warning' | 'error';
  message: string;
}

// Событие вызова инструмента
export interface AgUIToolCallEvent extends AgUIBaseEvent {
  type: AgUIEventType.TOOL_CALL;
  tool: string;
  args: any;
}

// Событие результата инструмента
export interface AgUIToolResultEvent extends AgUIBaseEvent {
  type: AgUIEventType.TOOL_RESULT;
  tool: string;
  result: any;
}

// Событие ошибки
export interface AgUIErrorEvent extends AgUIBaseEvent {
  type: AgUIEventType.ERROR;
  error: string;
  code?: string;
  details?: any;
}

// Событие "думает"
export interface AgUIThinkingEvent extends AgUIBaseEvent {
  type: AgUIEventType.THINKING;
  message?: string;
}

// Событие графика
export interface AgUIChartEvent extends AgUIBaseEvent {
  type: AgUIEventType.CHART;
  chartType: 'bar' | 'line' | 'pie' | 'area' | 'scatter';
  title?: string;
  labels: string[];
  datasets: {
    label: string;
    data: number[];
    backgroundColor?: string | string[];
    borderColor?: string;
  }[];
  options?: Record<string, any>;
}

// Событие изображения
export interface AgUIImageEvent extends AgUIBaseEvent {
  type: AgUIEventType.IMAGE;
  url: string;
  alt?: string;
  caption?: string;
  width?: number;
  height?: number;
}

// Событие файла
export interface AgUIFileEvent extends AgUIBaseEvent {
  type: AgUIEventType.FILE;
  name: string;
  size?: number;
  fileType?: string;
  url?: string;
  preview?: string;
}

// Событие пользовательского компонента
export interface AgUICustomEvent extends AgUIBaseEvent {
  type: AgUIEventType.CUSTOM;
  componentName: string;
  props: Record<string, any>;
}

// Событие дельты (для потоковых сообщений)
export interface AgUIDeltaEvent extends AgUIBaseEvent {
  type: AgUIEventType.DELTA;
  textDelta?: string;
  done?: boolean;
}

// Объединенный тип события AG-UI
export type AgUIEvent =
  | AgUITextEvent
  | AgUIActionEvent
  | AgUICardEvent
  | AgUIFormEvent
  | AgUITableEvent
  | AgUIListEvent
  | AgUIStatusEvent
  | AgUIToolCallEvent
  | AgUIToolResultEvent
  | AgUIErrorEvent
  | AgUIThinkingEvent
  | AgUIChartEvent
  | AgUIImageEvent
  | AgUIFileEvent
  | AgUICustomEvent
  | AgUIDeltaEvent;
