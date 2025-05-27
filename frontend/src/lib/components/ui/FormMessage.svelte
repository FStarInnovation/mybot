<script lang="ts">
  import { createEventDispatcher } from 'svelte';
  import type { AgUIFormField } from '../../ag-ui/types';
  
  export let id: string;
  export let title: string | undefined = undefined;
  export let description: string | undefined = undefined;
  export let fields: AgUIFormField[] = [];
  export let submitLabel: string = 'Отправить';
  export let cancelLabel: string = 'Отмена';
  
  // Создаем локальные копии значений полей
  let formValues: Record<string, any> = {};
  
  // Инициализируем значения полей
  $: {
    fields.forEach(field => {
      if (field.value !== undefined && formValues[field.id] === undefined) {
        formValues[field.id] = field.value;
      }
    });
  }
  
  const dispatch = createEventDispatcher();
  
  // Обработчик отправки формы
  function handleSubmit() {
    // Проверяем обязательные поля
    const requiredFields = fields.filter(field => field.required);
    const missingFields = requiredFields.filter(field => !formValues[field.id]);
    
    if (missingFields.length > 0) {
      // Есть незаполненные обязательные поля
      return;
    }
    
    // Отправляем событие с данными формы
    dispatch('submit', {
      formId: id,
      values: formValues
    });
  }
  
  // Обработчик отмены
  function handleCancel() {
    dispatch('cancel', {
      formId: id
    });
  }
  
  // Обработчик изменения значения поля
  function handleFieldChange(fieldId: string, value: any) {
    formValues[fieldId] = value;
  }
</script>

<div class="form-message">
  {#if title}
    <h3 class="form-title">{title}</h3>
  {/if}
  
  {#if description}
    <p class="form-description">{description}</p>
  {/if}
  
  <div class="form-fields">
    {#each fields as field}
      <div class="form-field" class:required={field.required}>
        <label for={field.id}>{field.label}</label>
        
        {#if field.type === 'text'}
          <input 
            type="text" 
            id={field.id} 
            placeholder={field.placeholder || ''}
            bind:value={formValues[field.id]}
            required={field.required}
            minlength={field.validation?.minLength}
            maxlength={field.validation?.maxLength}
            pattern={field.validation?.pattern}
          />
        {:else if field.type === 'email'}
          <input 
            type="email" 
            id={field.id} 
            placeholder={field.placeholder || ''}
            bind:value={formValues[field.id]}
            required={field.required}
            pattern={field.validation?.pattern}
          />
        {:else if field.type === 'password'}
          <input 
            type="password" 
            id={field.id} 
            placeholder={field.placeholder || ''}
            bind:value={formValues[field.id]}
            required={field.required}
            minlength={field.validation?.minLength}
            maxlength={field.validation?.maxLength}
          />
        {:else if field.type === 'number'}
          <input 
            type="number" 
            id={field.id} 
            placeholder={field.placeholder || ''}
            bind:value={formValues[field.id]}
            required={field.required}
            min={field.validation?.min}
            max={field.validation?.max}
          />
        {:else if field.type === 'textarea'}
          <textarea 
            id={field.id} 
            placeholder={field.placeholder || ''}
            bind:value={formValues[field.id]}
            required={field.required}
            minlength={field.validation?.minLength}
            maxlength={field.validation?.maxLength}
          ></textarea>
        {:else if field.type === 'select'}
          <select 
            id={field.id} 
            bind:value={formValues[field.id]}
            required={field.required}
          >
            <option value="" disabled selected hidden>
              {field.placeholder || 'Выберите...'}
            </option>
            {#each field.options || [] as option}
              <option value={option.value}>{option.label}</option>
            {/each}
          </select>
        {:else if field.type === 'checkbox'}
          <div class="checkbox-wrapper">
            <input 
              type="checkbox" 
              id={field.id} 
              bind:checked={formValues[field.id]}
              required={field.required}
            />
            {#if field.placeholder}
              <span class="checkbox-label">{field.placeholder}</span>
            {/if}
          </div>
        {:else if field.type === 'radio' && field.options}
          <div class="radio-group">
            {#each field.options as option}
              <div class="radio-option">
                <input 
                  type="radio" 
                  id={`${field.id}-${option.value}`}
                  name={field.id}
                  value={option.value}
                  bind:group={formValues[field.id]}
                  required={field.required}
                />
                <label for={`${field.id}-${option.value}`}>{option.label}</label>
              </div>
            {/each}
          </div>
        {:else if field.type === 'date'}
          <input 
            type="date" 
            id={field.id} 
            bind:value={formValues[field.id]}
            required={field.required}
            min={field.validation?.min}
            max={field.validation?.max}
          />
        {/if}
      </div>
    {/each}
  </div>
  
  <div class="form-actions">
    <button type="button" class="form-cancel" on:click={handleCancel}>
      {cancelLabel}
    </button>
    <button type="button" class="form-submit" on:click={handleSubmit}>
      {submitLabel}
    </button>
  </div>
</div>

<style>
  .form-message {
    background-color: var(--bg-secondary);
    border-radius: 12px;
    padding: 1rem;
    margin: 0.5rem 0;
    max-width: 500px;
    width: 100%;
  }
  
  .form-title {
    margin: 0 0 0.5rem 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--text-primary);
  }
  
  .form-description {
    margin: 0 0 1rem 0;
    font-size: 0.9rem;
    color: var(--text-secondary);
    line-height: 1.4;
  }
  
  .form-fields {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 1rem;
  }
  
  .form-field {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
  }
  
  .form-field label {
    font-size: 0.9rem;
    font-weight: 500;
    color: var(--text-primary);
  }
  
  .form-field.required label::after {
    content: '*';
    color: #ef4444;
    margin-left: 0.25rem;
  }
  
  .form-field input[type="text"],
  .form-field input[type="email"],
  .form-field input[type="password"],
  .form-field input[type="number"],
  .form-field input[type="date"],
  .form-field textarea,
  .form-field select {
    padding: 0.5rem;
    border-radius: 6px;
    border: 1px solid var(--border-color);
    background-color: var(--input-bg);
    color: var(--text-primary);
    font-size: 0.9rem;
    transition: border-color 0.2s ease, box-shadow 0.2s ease;
  }
  
  .form-field input:focus,
  .form-field textarea:focus,
  .form-field select:focus {
    outline: none;
    border-color: var(--accent-primary);
    box-shadow: 0 0 0 2px rgba(var(--accent-primary-rgb), 0.1);
  }
  
  .form-field textarea {
    min-height: 100px;
    resize: vertical;
  }
  
  .checkbox-wrapper,
  .radio-option {
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }
  
  .radio-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }
  
  .form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 0.75rem;
    margin-top: 1.5rem;
  }
  
  .form-cancel {
    padding: 0.5rem 1rem;
    border-radius: 6px;
    border: 1px solid var(--border-color);
    background-color: transparent;
    color: var(--text-secondary);
    font-size: 0.9rem;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s ease, color 0.2s ease;
  }
  
  .form-cancel:hover {
    background-color: var(--bg-tertiary);
    color: var(--text-primary);
  }
  
  .form-submit {
    padding: 0.5rem 1rem;
    border-radius: 6px;
    border: none;
    background-color: var(--accent-primary);
    color: var(--button-text);
    font-size: 0.9rem;
    font-weight: 500;
    cursor: pointer;
    transition: background-color 0.2s ease, transform 0.2s ease;
  }
  
  .form-submit:hover {
    background-color: var(--button-hover-bg);
    transform: translateY(-1px);
  }
  
  .form-submit:active {
    transform: translateY(0);
  }
</style>
