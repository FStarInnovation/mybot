# Quadratic Prompts Report (quadratichq/quadratic)

Generated: 2026-01-25 16:09:44

## Scope

This report inventories AI prompt content (system/tool/router/docs/provider glue) used in the public repository:

- https://github.com/quadratichq/quadratic

## Architecture overview (how prompts are assembled)

- Messages are represented as `ChatMessage[]` with fields like `role` and `contextType`.
- `getSystemPromptMessages()` splits the conversation into:
  - **System messages**: internal context (built from messages that are treated as system prompt)
  - **Prompt messages**: user-visible conversation + tool results
- System/context content is built in `getQuadraticContext()`, and tool prompts are injected via `getToolUseContext()`.
- Each tool has:
  - `description` and `parameters` (sent as tool/function schema)
  - `prompt` (sent as internal system/toolUse context)
- Provider adapters (Anthropic/GenAI/etc) transform these messages to provider-specific APIs and may add small “glue prompts” after tool results.

## Prompt sources (files)

- **quadratic-api/src/ai/helpers/context.helper.ts** — System/context сборка (основной контекст, tools, rules, languages, date)
  - GitHub: https://github.com/quadratichq/quadratic/blob/main/quadratic-api/src/ai/helpers/context.helper.ts
  - Raw: https://raw.githubusercontent.com/quadratichq/quadratic/main/quadratic-api/src/ai/helpers/context.helper.ts
- **quadratic-api/src/ai/helpers/modelRouter.helper.ts** — Model Router (выбор модели Claude vs 4.1)
  - GitHub: https://github.com/quadratichq/quadratic/blob/main/quadratic-api/src/ai/helpers/modelRouter.helper.ts
  - Raw: https://raw.githubusercontent.com/quadratichq/quadratic/main/quadratic-api/src/ai/helpers/modelRouter.helper.ts
- **quadratic-shared/ai/helpers/message.helper.ts** — Message processing (system vs prompt) + cleanup/injection strings
  - GitHub: https://github.com/quadratichq/quadratic/blob/main/quadratic-shared/ai/helpers/message.helper.ts
  - Raw: https://raw.githubusercontent.com/quadratichq/quadratic/main/quadratic-shared/ai/helpers/message.helper.ts
- **quadratic-shared/ai/specs/aiToolsSpec.ts** — Tool catalog + tool prompts (aiToolsSpec.ts)
  - GitHub: https://github.com/quadratichq/quadratic/blob/main/quadratic-shared/ai/specs/aiToolsSpec.ts
  - Raw: https://raw.githubusercontent.com/quadratichq/quadratic/main/quadratic-shared/ai/specs/aiToolsSpec.ts
- **quadratic-api/src/ai/helpers/anthropic.helper.ts** — Provider adapter (Anthropic) — glue prompt после tool results
  - GitHub: https://github.com/quadratichq/quadratic/blob/main/quadratic-api/src/ai/helpers/anthropic.helper.ts
  - Raw: https://raw.githubusercontent.com/quadratichq/quadratic/main/quadratic-api/src/ai/helpers/anthropic.helper.ts
- **quadratic-api/src/ai/helpers/genai.helper.ts** — Provider adapter (Google GenAI/Gemini) — glue prompt после tool results
  - GitHub: https://github.com/quadratichq/quadratic/blob/main/quadratic-api/src/ai/helpers/genai.helper.ts
  - Raw: https://raw.githubusercontent.com/quadratichq/quadratic/main/quadratic-api/src/ai/helpers/genai.helper.ts
- **quadratic-api/src/ai/helpers/openai.chatCompletions.helper.ts** — Provider adapter (OpenAI Chat Completions)
  - GitHub: https://github.com/quadratichq/quadratic/blob/main/quadratic-api/src/ai/helpers/openai.chatCompletions.helper.ts
  - Raw: https://raw.githubusercontent.com/quadratichq/quadratic/main/quadratic-api/src/ai/helpers/openai.chatCompletions.helper.ts
- **quadratic-api/src/ai/helpers/openai.responses.helper.ts** — Provider adapter (OpenAI Responses API)
  - GitHub: https://github.com/quadratichq/quadratic/blob/main/quadratic-api/src/ai/helpers/openai.responses.helper.ts
  - Raw: https://raw.githubusercontent.com/quadratichq/quadratic/main/quadratic-api/src/ai/helpers/openai.responses.helper.ts
- **quadratic-api/src/ai/helpers/bedrock.helper.ts** — Provider adapter (AWS Bedrock)
  - GitHub: https://github.com/quadratichq/quadratic/blob/main/quadratic-api/src/ai/helpers/bedrock.helper.ts
  - Raw: https://raw.githubusercontent.com/quadratichq/quadratic/main/quadratic-api/src/ai/helpers/bedrock.helper.ts
- **quadratic-api/src/ai/docs/QuadraticDocs.ts** — Docs injected: QuadraticDocs
  - GitHub: https://github.com/quadratichq/quadratic/blob/main/quadratic-api/src/ai/docs/QuadraticDocs.ts
  - Raw: https://raw.githubusercontent.com/quadratichq/quadratic/main/quadratic-api/src/ai/docs/QuadraticDocs.ts
- **quadratic-api/src/ai/docs/PythonDocs.ts** — Docs injected: PythonDocs
  - GitHub: https://github.com/quadratichq/quadratic/blob/main/quadratic-api/src/ai/docs/PythonDocs.ts
  - Raw: https://raw.githubusercontent.com/quadratichq/quadratic/main/quadratic-api/src/ai/docs/PythonDocs.ts
- **quadratic-api/src/ai/docs/JavascriptDocs.ts** — Docs injected: JavascriptDocs
  - GitHub: https://github.com/quadratichq/quadratic/blob/main/quadratic-api/src/ai/docs/JavascriptDocs.ts
  - Raw: https://raw.githubusercontent.com/quadratichq/quadratic/main/quadratic-api/src/ai/docs/JavascriptDocs.ts
- **quadratic-api/src/ai/docs/FormulaDocs.ts** — Docs injected: FormulaDocs
  - GitHub: https://github.com/quadratichq/quadratic/blob/main/quadratic-api/src/ai/docs/FormulaDocs.ts
  - Raw: https://raw.githubusercontent.com/quadratichq/quadratic/main/quadratic-api/src/ai/docs/FormulaDocs.ts
- **quadratic-api/src/ai/docs/ConnectionDocs.ts** — Docs injected: ConnectionDocs
  - GitHub: https://github.com/quadratichq/quadratic/blob/main/quadratic-api/src/ai/docs/ConnectionDocs.ts
  - Raw: https://raw.githubusercontent.com/quadratichq/quadratic/main/quadratic-api/src/ai/docs/ConnectionDocs.ts
- **quadratic-api/src/ai/docs/A1Docs.ts** — Docs injected: A1Docs
  - GitHub: https://github.com/quadratichq/quadratic/blob/main/quadratic-api/src/ai/docs/A1Docs.ts
  - Raw: https://raw.githubusercontent.com/quadratichq/quadratic/main/quadratic-api/src/ai/docs/A1Docs.ts
- **quadratic-api/src/ai/docs/ValidationDocs.ts** — Docs injected: ValidationDocs
  - GitHub: https://github.com/quadratichq/quadratic/blob/main/quadratic-api/src/ai/docs/ValidationDocs.ts
  - Raw: https://raw.githubusercontent.com/quadratichq/quadratic/main/quadratic-api/src/ai/docs/ValidationDocs.ts

---

## Full source text (verbatim)

Below are the key prompt-related files included **in full** to avoid truncation and ensure every prompt string is present verbatim.

### quadratic-api/src/ai/helpers/context.helper.ts

- **Purpose**: System/context сборка (основной контекст, tools, rules, languages, date)
- **GitHub**: https://github.com/quadratichq/quadratic/blob/main/quadratic-api/src/ai/helpers/context.helper.ts
- **Raw**: https://raw.githubusercontent.com/quadratichq/quadratic/main/quadratic-api/src/ai/helpers/context.helper.ts

```ts
import { createTextContent } from 'quadratic-shared/ai/helpers/message.helper';
import { MODELS_CONFIGURATION } from 'quadratic-shared/ai/models/AI_MODELS';
import { aiToolsSpec } from 'quadratic-shared/ai/specs/aiToolsSpec';
import type {
  AILanguagePreferences,
  AIModelKey,
  AISource,
  ChatMessage,
  CodeCellType,
} from 'quadratic-shared/typesAndSchemasAI';
import { allAILanguagePreferences } from 'quadratic-shared/typesAndSchemasAI';

import { A1Docs } from '../docs/A1Docs';
import { ConnectionDocs } from '../docs/ConnectionDocs';
import { FormulaDocs } from '../docs/FormulaDocs';
import { JavascriptDocs } from '../docs/JavascriptDocs';
import { PythonDocs } from '../docs/PythonDocs';
import { QuadraticDocs } from '../docs/QuadraticDocs';
import { ValidationDocs } from '../docs/ValidationDocs';

/**
 * By default, the AI will respond with Python + Formulas, which is why we
 * include them in the context. Additionally, if the user has expressed a
 * preference for Javascript, we will include the Javascript docs in the context
 */
export const getQuadraticContext = (
  source: AISource,
  language?: CodeCellType,
  prefersJavascript?: boolean
): ChatMessage[] => [
  {
    role: 'user',
    content: [
      createTextContent(`Note: This is an internal message for context. Do not quote it in your response.\n\n
You are a helpful assistant inside of a spreadsheet application called Quadratic.
Keep text responses concise - prefer one sentence and bullet points, use more sentences when necessary for clarity (e.g., explaining errors or complex data transformations). Do not add text comments between tool calls unless necessary; only provide a brief summary after all tools have completed. No fluff or filler language.
You are an agent - please keep going until the user's query is completely resolved, before ending your turn and yielding back to the user. Only terminate your turn when you are sure that the problem is solved.
If you are not sure about sheet data content pertaining to the user's request, use your tools to read data and gather the relevant information: do NOT guess or make up an answer.
Be proactive. When the user makes a request, use your tools to solve it.

# Reasoning Strategy
1. Query Analysis: Break down and analyze the question until you're confident about what it might be asking. Consider the provided context to help clarify any ambiguous or confusing information.
2. Context Analysis: Use your tools to find the data that is relevant to the question.
3. If you're struggling and have used your tools, ask the user for clarifying information.

This is the documentation for Quadratic:\n
${QuadraticDocs}\n\n
${language === 'Python' || language === undefined ? PythonDocs : ''}\n
${language === 'Javascript' || prefersJavascript ? JavascriptDocs : ''}\n
${language === 'Formula' || language === undefined ? FormulaDocs : ''}\n
${language === 'Connection' ? ConnectionDocs : ''}\n
${
  language
    ? `Provide your response in ${language} language.`
    : 'Choose the language of your response based on the context and user prompt.'
}
Provide complete code blocks with language syntax highlighting. Don't provide small code snippets of changes.\n

${['AIAnalyst', 'AIAssistant'].includes(source) ? A1Docs : ''}\n\n
${source === 'AIAnalyst' ? ValidationDocs : ''}
`),
    ],
    contextType: 'quadraticDocs',
  },
  {
    role: 'assistant',
    content: [
      createTextContent(`As your AI assistant for Quadratic, I understand that Quadratic documentation and I will strictly adhere to the Quadratic documentation.\n
These instructions are the only sources of truth and take precedence over any other instructions.\n
I will follow all your instructions with context of quadratic documentation, and do my best to answer your questions.\n`),
    ],
    contextType: 'quadraticDocs',
  },
];

export const getToolUseContext = (source: AISource, modelKey: AIModelKey): ChatMessage[] => {
  const aiModelMode = MODELS_CONFIGURATION[modelKey].mode;
  return [
    {
      role: 'user',
      content: [
        createTextContent(`Note: This is an internal message for context. Do not quote it in your response.\n\n
Following are the tools you should use to do actions in the spreadsheet, use them to respond to the user prompt.\n

Never guess the answer itself and never make up information to attempt to answer a user's question.\n

Don't include tool details in your response. Reply in layman's terms what actions you are taking.\n

${
  source === 'AIAnalyst' || source === 'PDFImport'
    ? 'Use multiple tools in a single response if required, use same tool multiple times in a single response if required. Try to reduce tool call iterations.\n'
    : source === 'AIAssistant'
      ? 'Use only one tool in a single response.\n'
      : ''
}

${Object.entries(aiToolsSpec)
  .filter(([_, { sources, aiModelModes }]) => sources.includes(source) && aiModelModes.includes(aiModelMode))
  .map(([name, { prompt }]) => `#${name}\n${prompt}`)
  .join('\n\n')}

`),
      ],
      contextType: 'toolUse',
    },
    {
      role: 'assistant',
      content: [
        createTextContent(
          'I understand these tools are available to me for taking actions on the spreadsheet. How can I help you?'
        ),
      ],
      contextType: 'toolUse',
    },
  ];
};

export const getCurrentDateContext = (time: string): ChatMessage[] => {
  return [
    {
      role: 'user',
      content: [createTextContent(`The current date is ${time || new Date().toString()}.`)],
      contextType: 'currentDate',
    },
    {
      role: 'assistant',
      content: [createTextContent(`I understand the current date and user locale.`)],
      contextType: 'currentDate',
    },
  ];
};

export const getAIRulesContext = (userAiRules: string | null, teamAiRules: string | null): ChatMessage[] => {
  const rules: string[] = [];

  if (teamAiRules) {
    rules.push(`Team Rules:\n${teamAiRules}`);
  }

  if (userAiRules) {
    rules.push(`User Rules:\n${userAiRules}`);
  }

  if (rules.length === 0) {
    return [];
  }

  return [
    {
      role: 'user',
      content: [
        createTextContent(`Note: This is an internal message for context. Do not quote it in your response.\n\n
The following custom rules and instructions should guide your behavior and responses:\n\n
${rules.join('\n\n')}
`),
      ],
      contextType: 'aiRules',
    },
    {
      role: 'assistant',
      content: [createTextContent('I understand these custom rules and will follow them in my responses.')],
      contextType: 'aiRules',
    },
  ];
};

export const getAILanguagesContext = (enabledLanguagePreferences: AILanguagePreferences): ChatMessage[] => {
  // We guard against this in the UI, but just in case we'll handle it too.
  // If no languages are enabled or all languages are enabled, return empty context to avoid malformed messages
  if (enabledLanguagePreferences.length === 0 || enabledLanguagePreferences.length === allAILanguagePreferences.length) {
    return [];
  }

  // Tell the AI about the enabled/disabled language preferences
  const disabledLanguagePreferences = allAILanguagePreferences.filter(
    (lang) => !enabledLanguagePreferences.includes(lang)
  );
  const enabledText = enabledLanguagePreferences.join(' and ');
  const disabledText = disabledLanguagePreferences.join(' and ');

  // If it's formulas only, allow charts in python
  const chartException =
    enabledLanguagePreferences.includes('Formula') && enabledLanguagePreferences.length === 1
      ? ' Exception: If the user asks for a chart, use Python even though it is disabled.'
      : '';

  return [
    {
      role: 'user',
      content: [
        createTextContent(`Note: This is an internal message for context. Do not quote it in your response.\n\n
The user only wants to use ${enabledText} and NOT ${disabledText} unless they explicitly ask for the disabled language.${chartException}\n\n
However, if the user is working with a connection, it’s ok to use SQL for the connection.
`),
      ],
      contextType: 'aiLanguages',
    },
    {
      role: 'assistant',
      content: [
        createTextContent(
          `I understand. I will only use ${enabledText} in my responses unless you explicitly ask me to use ${disabledText}.${
            chartException ? ' I will use Python for charts.' : ''
          }. And if the user is working with a connection, it’s ok to use SQL for the connection.`
        ),
      ],
      contextType: 'aiLanguages',
    },
  ];
};
```

### quadratic-api/src/ai/helpers/modelRouter.helper.ts

- **Purpose**: Model Router (выбор модели Claude vs 4.1)
- **GitHub**: https://github.com/quadratichq/quadratic/blob/main/quadratic-api/src/ai/helpers/modelRouter.helper.ts
- **Raw**: https://raw.githubusercontent.com/quadratichq/quadratic/main/quadratic-api/src/ai/helpers/modelRouter.helper.ts

```ts
import {
  createTextContent,
  getLastAIPromptMessageModelKey,
  getPromptMessagesForAI,
  getUserPromptMessages,
  isContentImage,
  isContentText,
} from 'quadratic-shared/ai/helpers/message.helper';
import { isQuadraticModel } from 'quadratic-shared/ai/helpers/model.helper';
import {
  DEFAULT_BACKUP_MODEL,
  DEFAULT_MODEL_ROUTER_MODEL,
  DEFAULT_MODEL_WITH_IMAGE,
  MODELS_CONFIGURATION,
  RESTRICTED_MODEL_ROUTER_MODEL,
} from 'quadratic-shared/ai/models/AI_MODELS';
import { AITool, aiToolsSpec, MODELS_ROUTER_CONFIGURATION } from 'quadratic-shared/ai/specs/aiToolsSpec';
import type { AIModelKey, AIRequestHelperArgs } from 'quadratic-shared/typesAndSchemasAI';
import logger from '../../utils/logger';
import { handleAIRequest } from '../handler/ai.handler';

export const getModelKey = async (
  modelKey: AIModelKey,
  inputArgs: AIRequestHelperArgs,
  isOnPaidPlan: boolean,
  exceededBillingLimit: boolean,
  restrictedCountry: boolean,
  signal: AbortSignal
): Promise<AIModelKey> => {
  try {
    if (!['AIAnalyst', 'AIAssistant'].includes(inputArgs.source)) {
      return modelKey;
    }

    const messages = inputArgs.messages;
    if (messages.length === 0) {
      throw new Error('Messages are empty');
    }

    const promptMessages = getPromptMessagesForAI(messages);

    // Restricted country that uses restricted model
    if (restrictedCountry) {
      return RESTRICTED_MODEL_ROUTER_MODEL;
    }

    // if the model is the default free model, check if the user prompt contains an image file
    if (!isQuadraticModel(modelKey) && !MODELS_CONFIGURATION[modelKey].imageSupport) {
      const hasImageFile = getUserPromptMessages(promptMessages).some((message) =>
        message.content.some(isContentImage)
      );
      return hasImageFile ? DEFAULT_MODEL_WITH_IMAGE : modelKey;
    }

    // if the model is not the model router model, return the model key
    if (!isQuadraticModel(modelKey)) {
      return modelKey;
    }

    // if the last message is not a user prompt, use the last AI prompt message model key
    const lastPromptMessage = promptMessages[promptMessages.length - 1];
    if (lastPromptMessage.role !== 'user' || lastPromptMessage.contextType !== 'userPrompt') {
      return getLastAIPromptMessageModelKey(promptMessages) ?? DEFAULT_BACKUP_MODEL;
    }

    const userTextPrompt = lastPromptMessage.content
      .filter(isContentText)
      .map((content) => content.text)
      .join('\n')
      .trim();

    if (!userTextPrompt) {
      return DEFAULT_BACKUP_MODEL;
    }

    const args: AIRequestHelperArgs = {
      source: 'ModelRouter',
      messages: [
        {
          role: 'user',
          content: [
            createTextContent(`
 <role>
  You are an AI model selector for a spreadsheet application. Based on the user's prompt, choose the most suitable model.
 </role>

 <models>
  <model name="Claude">
   <capabilities>
    <capability>Creating sample data</capability>
    <capability>Creating calculators</capability>
    <capability>Creating new charts</capability>
    <capability>Editing existing charts</capability>
    <capability>Requests that involve frustration</capability>
    <capability>Onboarding questions</capability>
    <capability>Data cleaning</capability>
    <capability>Augmenting data</capability>
    <capability>Processing images and PDFs</capability>
    <capability>Writing JavaScript</capability>
    <capability>Conditional formatting</capability>
    <capability>Charts that have problems</capability>
    <capability>API requests</capability>
    <capability>Any capabilities not defined in these instructions</capability>
    <capability>Requests that involve frustration</capability>
    <capability>Charts that have problems</capability>
   </capabilities>
  </model>
  <model name="4.1">
   <capabilities>
    <capability>Simple/explicitly defined formatting</capability>
    <capability>Moving data to specific cell locations</capability>
   </capabilities>
  </model>
 </models>

 <instructions>
  Only respond with the model name: "Claude" or "4.1". Do not include any additional text, explanations, or formatting.
 </instructions>

 <examples>
  <example>
   <user>Insert some sample manufacturing data</user>
   <answer>Claude</answer>
  </example>
  <example>
   <user>create a dataset of sales data</user>
   <answer>Claude</answer>
  </example>
  <example>
   <user>Create a debt snowball calculator</user>
   <answer>Claude</answer>
  </example>
  <example>
   <user>Create a mortgage calculator</user>
   <answer>Claude</answer>
  </example>
  <example>
   <user>Hi, I'm new to Quadratic.</user>
   <answer>Claude</answer>
  </example>
  <example>
   <user>Analyze my PDFs</user>
   <answer>Claude</answer>
  </example>
  <example>
   <user>Highlight all the cells with value > 50</user>
   <answer>Claude</answer>
  </example>
  <example>
   <user>change text color to blue in all the rows that have gender male</user>
   <answer>Claude</answer>
  </example>
  <example>
   <user>Remove column B from the data</user>
   <answer>Claude</answer>
  </example>
  <example>
   <user>How much does each crop produce per year?</user>
   <answer>Claude</answer>
  </example>
  <example>
   <user>Highlight all male entries orange</user>
   <answer>Claude</answer>
  </example>
  <example>
   <user>Add an extra axis to my chart</user>
   <answer>Claude</answer>
  </example>
  <example>
   <user>Change the line to blue</user>
   <answer>Claude</answer>
  </example>
  <example>
   <user>Create a chart</user>
   <answer>Claude</answer>
  </example>
  <example>
   <user>Highlight column C blue</user>
   <answer>4.1</answer>
  </example>
  <example>
   <user>Find the mean, filtered by product type</user>
   <answer>Claude</answer>
  </example>
  <example>
   <user>Sum the values in column F</user>
   <answer>Claude</answer>
  </example>
  <example>
    <user>Calculate the mean of costs</user>
    <answer>Claude</answer>
  </example>
  <example>
   <user>move that to A9</user>
   <answer>4.1</answer>
  </example>
  <example>
   <user>That chart has an issue</user>
   <answer>Claude</answer>
  </example>
    <example>
   <user>try again</user>
   <answer>Claude</answer>
  </example>
  <example>
   <user>Why do you keep failing?</user>
   <answer>Claude</answer>
  </example>
  <example>
   <user>Chart is empty or missing data</user>
   <answer>Claude</answer>
  </example>
 </examples>
`),
          ],
          contextType: 'modelRouter',
        },
        {
          role: 'user',
          content: [
            createTextContent(`
Choose the most suitable model for the following prompt:
${userTextPrompt}
`),
          ],
          contextType: 'userPrompt',
        },
      ],
      useStream: false,
      toolName: AITool.SetAIModel,
      useToolsPrompt: false,
      language: undefined,
      useQuadraticContext: false,
    };

    const parsedResponse = await handleAIRequest({
      modelKey: DEFAULT_MODEL_ROUTER_MODEL,
      args,
      isOnPaidPlan,
      exceededBillingLimit,
      signal,
    });

    const setAIModelToolCall = parsedResponse?.responseMessage.toolCalls.find(
      (toolCall) => toolCall.name === AITool.SetAIModel
    );
    if (setAIModelToolCall) {
      const argsObject = JSON.parse(setAIModelToolCall.arguments);
      const { ai_model } = aiToolsSpec[AITool.SetAIModel].responseSchema.parse(argsObject);
      return MODELS_ROUTER_CONFIGURATION[ai_model];
    }
  } catch (error) {
    if (signal?.aborted) {
      logger.info('[getModelKey] AI request aborted by client');
    } else {
      logger.error('Error in getModelKey', error);
    }
  }

  return DEFAULT_BACKUP_MODEL;
};
```

### quadratic-shared/ai/helpers/message.helper.ts

- **Purpose**: Message processing (system vs prompt) + cleanup/injection strings
- **GitHub**: https://github.com/quadratichq/quadratic/blob/main/quadratic-shared/ai/helpers/message.helper.ts
- **Raw**: https://raw.githubusercontent.com/quadratichq/quadratic/main/quadratic-shared/ai/helpers/message.helper.ts

```ts
import type {
  AIMessagePrompt,
  AIModelKey,
  AIResponseContent,
  AIResponseThinkingContent,
  ChatMessage,
  Content,
  GoogleSearchContent,
  GoogleSearchGroundingMetadata,
  ImageContent,
  ImportFilesToGridContent,
  InternalMessage,
  OpenAIReasoningContent,
  PdfFileContent,
  SystemMessage,
  TextContent,
  TextFileContent,
  ToolResultContextType,
  ToolResultMessage,
  UserMessagePrompt,
  UserPromptContextType,
} from 'quadratic-shared/typesAndSchemasAI';
import { isQuadraticModel } from './model.helper';

export const CLEAN_UP_TOOL_CALLS_AFTER = 3;

const getSystemMessages = (messages: ChatMessage[]): string[] => {
  const systemMessages: SystemMessage[] = messages.filter<SystemMessage>(
    (message): message is SystemMessage =>
      message.role === 'user' && message.contextType !== 'userPrompt' && message.contextType !== 'toolResult'
  );
  return systemMessages.flatMap((message) => message.content.map((content) => content.text));
};

const getPromptMessages = (messages: ChatMessage[]): (UserMessagePrompt | ToolResultMessage | AIMessagePrompt)[] => {
  return messages.filter((message) => message.contextType === 'userPrompt' || message.contextType === 'toolResult');
};

export const getPromptAndInternalMessages = (
  messages: ChatMessage[]
): (UserMessagePrompt | ToolResultMessage | AIMessagePrompt | InternalMessage)[] => {
  return messages.filter(
    (message) =>
      message.contextType === 'userPrompt' || message.contextType === 'toolResult' || message.role === 'internal'
  );
};

const getPromptMessagesWithoutPDF = (messages: ChatMessage[]): ChatMessage[] => {
  return messages.map((message) => {
    if (message.role !== 'user' || message.contextType !== 'userPrompt') {
      return message;
    }

    return {
      ...message,
      content: message.content.filter((content) => !isContentPdfFile(content)),
    };
  });
};

export const getMessagesForAI = (messages: ChatMessage[]): ChatMessage[] => {
  const messagesWithoutPDF = getPromptMessagesWithoutPDF(messages);
  const messagesWithoutInternal = messagesWithoutPDF.filter((message) => !isInternalMessage(message));
  const messagesWithUserContext = messagesWithoutInternal.map((message) => {
    if (!isUserPromptMessage(message)) {
      return { ...message };
    }

    const userMessage = { ...message };
    if (message.context?.connection) {
      userMessage.content = [
        createTextContent(`NOTE: This is an internal message for context. Do not quote it in your response.\n\n
User has selected a connection and want to focus on it:

Connection Details: 
type: ${message.context.connection.type}
id: ${message.context.connection.id}
name: ${message.context.connection.name}
`),
        ...userMessage.content,
      ];
    }

    if (message.context?.importFiles?.prompt) {
      userMessage.content = [
        createTextContent(`NOTE: This is an internal message for context. Do not quote it in your response.\n\n
User attached files with this prompt and they were imported as:
${message.context.importFiles.prompt} 
`),
        ...userMessage.content,
      ];
    }

    return userMessage;
  });
  return messagesWithUserContext;
};

export const getPromptMessagesForAI = (
  messages: ChatMessage[]
): (UserMessagePrompt | ToolResultMessage | AIMessagePrompt)[] => {
  return getPromptMessages(getPromptMessagesWithoutPDF(messages));
};

export const removeOldFilesInToolResult = (messages: ChatMessage[], files: Set<string>): ChatMessage[] => {
  return messages.map((message) => {
    if (message.contextType !== 'toolResult') {
      return message;
    }

    return {
      ...message,
      content: message.content.map((result) => ({
        id: result.id,
        content:
          result.content.length === 1
            ? result.content
            : result.content.filter((content) => isContentText(content) || !files.has(content.fileName)),
      })),
    };
  });
};

export const getUserMessages = (messages: ChatMessage[]): (UserMessagePrompt | ToolResultMessage)[] => {
  return getPromptMessages(messages).filter(
    (message): message is UserMessagePrompt | ToolResultMessage => message.role === 'user'
  );
};

export const getUserPromptMessages = (messages: ChatMessage[]): UserMessagePrompt[] => {
  return messages.filter(
    (message): message is UserMessagePrompt => message.role === 'user' && message.contextType === 'userPrompt'
  );
};

const getAIPromptMessages = (messages: ChatMessage[]): AIMessagePrompt[] => {
  return getPromptMessages(messages).filter((message): message is AIMessagePrompt => message.role === 'assistant');
};

export const getLastUserMessage = (messages: ChatMessage[]): UserMessagePrompt | ToolResultMessage => {
  const userMessages = getUserMessages(messages);
  return userMessages[userMessages.length - 1];
};

export const getLastUserMessageType = (messages: ChatMessage[]): UserPromptContextType | ToolResultContextType => {
  return getLastUserMessage(messages).contextType;
};

export const getLastAIPromptMessageIndex = (messages: ChatMessage[]): number => {
  return getAIPromptMessages(messages).length - 1;
};

export const getLastAIPromptMessageModelKey = (messages: ChatMessage[]): AIModelKey | undefined => {
  const aiPromptMessages = getAIPromptMessages(messages);
  for (let i = aiPromptMessages.length - 1; i >= 0; i--) {
    const message = aiPromptMessages[i];
    if (!!message.modelKey && !isQuadraticModel(message.modelKey as AIModelKey)) {
      return message.modelKey as AIModelKey;
    }
  }
};

export const getSystemPromptMessages = (
  messages: ChatMessage[]
): { systemMessages: string[]; promptMessages: ChatMessage[] } => {
  // send internal context messages as system messages
  const systemMessages: string[] = getSystemMessages(messages);
  const promptMessages = getPromptMessages(messages);

  // send all messages as prompt messages
  // const systemMessages: string[] = [];
  // const promptMessages = messages;

  return { systemMessages, promptMessages };
};

export const isUserPromptMessage = (message: ChatMessage): message is UserMessagePrompt => {
  return message.role === 'user' && message.contextType === 'userPrompt';
};

export const isAIPromptMessage = (message: ChatMessage): message is AIMessagePrompt => {
  return message.role === 'assistant' && message.contextType === 'userPrompt';
};

export const isToolResultMessage = (message: ChatMessage): message is ToolResultMessage => {
  return message.role === 'user' && message.contextType === 'toolResult';
};

export const isInternalMessage = (message: ChatMessage): message is InternalMessage => {
  return message.role === 'internal';
};

export const isContentText = (content: Content[number] | AIResponseContent[number]): content is TextContent => {
  return content.type === 'text';
};

export const isContentThinking = (
  content: Content[number] | AIResponseContent[number]
): content is AIResponseThinkingContent => {
  return ['anthropic_thinking', 'google_thinking', 'openai_reasoning_summary', 'openai_reasoning_content'].includes(
    content.type
  );
};

export const isContentOpenAIReasoning = (
  content: Content[number] | AIResponseContent[number]
): content is OpenAIReasoningContent => {
  return ['openai_reasoning_summary', 'openai_reasoning_content'].includes(content.type);
};

export const isContentImage = (content: Content[number] | AIResponseContent[number]): content is ImageContent => {
  return content.type === 'data' && ['image/jpeg', 'image/png', 'image/gif', 'image/webp'].includes(content.mimeType);
};

export const isContentPdfFile = (content: Content[number] | AIResponseContent[number]): content is PdfFileContent => {
  return content.type === 'data' && content.mimeType === 'application/pdf';
};

export const isContentTextFile = (content: Content[number] | AIResponseContent[number]): content is TextFileContent => {
  return content.type === 'data' && content.mimeType === 'text/plain';
};

export const isContentFile = (
  content: Content[number] | AIResponseContent[number]
): content is ImageContent | PdfFileContent | TextFileContent => {
  return isContentImage(content) || isContentPdfFile(content) || isContentTextFile(content);
};

export const isContentGoogleSearchInternal = (content: InternalMessage['content']): content is GoogleSearchContent => {
  return content.source === 'google_search';
};

export const isContentImportFilesToGridInternal = (
  content: InternalMessage['content']
): content is ImportFilesToGridContent => {
  return content.source === 'import_files_to_grid';
};

export const isContentGoogleSearchGroundingMetadata = (
  content: Content[number] | AIResponseContent[number]
): content is GoogleSearchGroundingMetadata => {
  return content.type === 'google_search_grounding_metadata';
};

export const filterImageFilesInChatMessages = (messages: ChatMessage[]): ImageContent[] => {
  return getUserMessages(messages)
    .filter((message) => message.contextType === 'userPrompt')
    .flatMap((message) => message.content)
    .filter(isContentImage);
};

export const filterPdfFilesInChatMessages = (messages: ChatMessage[]): PdfFileContent[] => {
  return getUserMessages(messages)
    .filter((message) => message.contextType === 'userPrompt')
    .flatMap((message) => message.content)
    .filter(isContentPdfFile);
};

export const getPdfFileFromChatMessages = (fileName: string, messages: ChatMessage[]): PdfFileContent | undefined => {
  return filterPdfFilesInChatMessages(messages).find((content) => content.fileName === fileName);
};

export const createTextContent = (text: string): TextContent => {
  return {
    type: 'text' as const,
    text,
  };
};

export const createInternalImportFilesContent = (
  importFilesToGridContent: ImportFilesToGridContent
): InternalMessage => {
  return {
    role: 'internal' as const,
    contextType: 'importFilesToGrid' as const,
    content: { ...importFilesToGridContent },
  };
};

// Cleans up old get_ tool messages to avoid expensive contexts.
export const replaceOldGetToolCallResults = (messages: ChatMessage[]): ChatMessage[] => {
  const CLEAN_UP_MESSAGE =
    'NOTE: the results from this tool call have been removed from the context. If you need to use them, you MUST use Python.';

  const getToolIds = new Set();
  messages.forEach((message) => {
    if (message.role === 'assistant' && message.contextType === 'userPrompt') {
      message.toolCalls.forEach((toolCall) => {
        if (toolCall.name === 'get_cell_data' || toolCall.name === 'get_text_formats') {
          getToolIds.add(toolCall.id);
        }
      });
    }
  });

  // If we have multiple get_cell_data messages, keep only the tool call after a
  // certain number of calls
  return messages.map((message, i) => {
    if (message.role === 'user' && message.contextType === 'toolResult') {
      return {
        ...message,
        content: message.content.map((toolResult) => {
          if (getToolIds.has(toolResult.id)) {
            if (i < messages.length - CLEAN_UP_TOOL_CALLS_AFTER) {
              return {
                id: toolResult.id,
                content: [createTextContent(CLEAN_UP_MESSAGE)],
              };
            } else {
              return toolResult;
            }
          } else {
            // clean up plotly images in tool result
            const content = toolResult.content.filter((content) => !isContentImage(content));
            return {
              id: toolResult.id,
              content:
                content.length > 0
                  ? content
                  : [createTextContent('NOTE: the results from this tool call have been removed from the context.')],
            };
          }
        }),
      };
    } else {
      return message;
    }
  });
};
```

### quadratic-shared/ai/specs/aiToolsSpec.ts

- **Purpose**: Tool catalog + tool prompts (aiToolsSpec.ts)
- **GitHub**: https://github.com/quadratichq/quadratic/blob/main/quadratic-shared/ai/specs/aiToolsSpec.ts
- **Raw**: https://raw.githubusercontent.com/quadratichq/quadratic/main/quadratic-shared/ai/specs/aiToolsSpec.ts

```ts
import type {
  AIModelKey,
  AISource,
  AIToolArgs,
  AIToolArgsPrimitive,
  ModelMode,
} from 'quadratic-shared/typesAndSchemasAI';
import { ConnectionTypeSchema } from 'quadratic-shared/typesAndSchemasConnections';
import { z } from 'zod';

// This provides a list of AI Tools in the order that they will be sent to the
// AI model. If you want to change order, change it here instead of the spec
// below.
export enum AITool {
  SetAIModel = 'set_ai_model',
  SetChatName = 'set_chat_name',
  SetFileName = 'set_file_name',
  AddDataTable = 'add_data_table',
  SetCellValues = 'set_cell_values',
  GetCodeCellValue = 'get_code_cell_value',
  SetCodeCellValue = 'set_code_cell_value',
  GetDatabaseSchemas = 'get_database_schemas',
  SetSQLCodeCellValue = 'set_sql_code_cell_value',
  SetFormulaCellValue = 'set_formula_cell_value',
  MoveCells = 'move_cells',
  DeleteCells = 'delete_cells',
  UpdateCodeCell = 'update_code_cell',
  CodeEditorCompletions = 'code_editor_completions',
  UserPromptSuggestions = 'user_prompt_suggestions',
  EmptyChatPromptSuggestions = 'empty_chat_prompt_suggestions',
  PDFImport = 'pdf_import',
  GetCellData = 'get_cell_data',
  HasCellData = 'has_cell_data',
  SetTextFormats = 'set_text_formats',
  GetTextFormats = 'get_text_formats',
  ConvertToTable = 'convert_to_table',
  WebSearch = 'web_search',
  WebSearchInternal = 'web_search_internal',
  AddSheet = 'add_sheet',
  DuplicateSheet = 'duplicate_sheet',
  RenameSheet = 'rename_sheet',
  DeleteSheet = 'delete_sheet',
  MoveSheet = 'move_sheet',
  ColorSheets = 'color_sheets',
  TextSearch = 'text_search',
  RerunCode = 'rerun_code',
  ResizeColumns = 'resize_columns',
  ResizeRows = 'resize_rows',
  SetBorders = 'set_borders',
  InsertColumns = 'insert_columns',
  InsertRows = 'insert_rows',
  DeleteColumns = 'delete_columns',
  DeleteRows = 'delete_rows',
  TableMeta = 'table_meta',
  TableColumnSettings = 'table_column_settings',
  GetValidations = 'get_validations',
  AddMessage = 'add_message',
  AddLogicalValidation = 'add_logical_validation',
  AddListValidation = 'add_list_validation',
  AddTextValidation = 'add_text_validation',
  AddNumberValidation = 'add_number_validation',
  AddDateTimeValidation = 'add_date_time_validation',
  RemoveValidations = 'remove_validation',
  Undo = 'undo',
  Redo = 'redo',
  ContactUs = 'contact_us',
  OptimizePrompt = 'optimize_prompt',
}

export const AIToolSchema = z.enum([
  AITool.SetAIModel,
  AITool.SetChatName,
  AITool.SetFileName,
  AITool.AddDataTable,
  AITool.SetCellValues,
  AITool.GetCodeCellValue,
  AITool.SetCodeCellValue,
  AITool.GetDatabaseSchemas,
  AITool.SetSQLCodeCellValue,
  AITool.SetFormulaCellValue,
  AITool.MoveCells,
  AITool.DeleteCells,
  AITool.UpdateCodeCell,
  AITool.CodeEditorCompletions,
  AITool.UserPromptSuggestions,
  AITool.EmptyChatPromptSuggestions,
  AITool.PDFImport,
  AITool.GetCellData,
  AITool.HasCellData,
  AITool.SetTextFormats,
  AITool.GetTextFormats,
  AITool.ConvertToTable,
  AITool.WebSearch,
  AITool.WebSearchInternal,
  AITool.AddSheet,
  AITool.DuplicateSheet,
  AITool.RenameSheet,
  AITool.DeleteSheet,
  AITool.MoveSheet,
  AITool.ColorSheets,
  AITool.TextSearch,
  AITool.RerunCode,
  AITool.ResizeColumns,
  AITool.ResizeRows,
  AITool.SetBorders,
  AITool.InsertColumns,
  AITool.InsertRows,
  AITool.DeleteColumns,
  AITool.DeleteRows,
  AITool.TableMeta,
  AITool.TableColumnSettings,
  AITool.GetValidations,
  AITool.AddMessage,
  AITool.AddLogicalValidation,
  AITool.AddListValidation,
  AITool.AddTextValidation,
  AITool.AddNumberValidation,
  AITool.AddDateTimeValidation,
  AITool.RemoveValidations,
  AITool.Undo,
  AITool.Redo,
  AITool.ContactUs,
  AITool.OptimizePrompt,
]);

type AIToolSpec<T extends keyof typeof AIToolsArgsSchema> = {
  sources: AISource[];
  aiModelModes: ModelMode[];
  description: string; // this is sent with tool definition, has a maximum character limit
  parameters: AIToolArgs;
  responseSchema: (typeof AIToolsArgsSchema)[T];
  prompt: string; // this is sent as internal message to AI, no character limit
};

const numberSchema = z.preprocess((val) => {
  if (typeof val === 'number') {
    return val;
  }
  return Number(val);
}, z.number());

const booleanSchema = z.preprocess((val) => {
  if (typeof val === 'boolean') {
    return val;
  }
  return val === 'true';
}, z.boolean());

const booleanNullableOptionalSchema = z.preprocess((val) => {
  if (val === null || val === undefined) {
    return val;
  }
  if (typeof val === 'boolean') {
    return val;
  }
  return val === 'true';
}, z.boolean().nullable().optional());

const stringSchema = z.preprocess((val) => {
  if (typeof val === 'number' || typeof val === 'boolean' || typeof val === 'bigint' || typeof val === 'symbol') {
    return String(val);
  }
  return val;
}, z.string());

const stringNullableOptionalSchema = z.preprocess((val) => {
  if (typeof val === 'number' || typeof val === 'boolean' || typeof val === 'bigint' || typeof val === 'symbol') {
    return String(val);
  }
  return val;
}, z.string().nullable().optional());

const array2DSchema = z
  .array(
    z.array(
      z.union([
        z.string(),
        z.number().transform(String),
        z.undefined().transform(() => ''),
        z.null().transform(() => ''),
      ])
    )
  )
  .or(
    z.string().transform((str) => {
      try {
        const parsed = JSON.parse(str);
        if (Array.isArray(parsed)) {
          return parsed.map((row) => {
            if (!Array.isArray(row)) {
              throw new Error('Invalid 2D array format - each row must be an array');
            }
            return row.map(String);
          });
        }
        throw new Error('Invalid 2D array format');
      } catch {
        throw new Error('Invalid 2D array format');
      }
    })
  )
  .transform((array) => {
    const maxColumns = array.length > 0 ? Math.max(...array.map((row) => row.length)) : 0;
    return array.map((row) => (row.length === maxColumns ? row : row.concat(Array(maxColumns - row.length).fill(''))));
  });

const enumToFirstLetterCapitalSchema = <T extends string>(enumValues: readonly T[]) =>
  z
    .string()
    .transform((val) => val.charAt(0).toUpperCase() + val.slice(1).toLowerCase())
    .pipe(z.enum(enumValues as readonly string[] as [T, ...T[]]));

const cellLanguageSchema = enumToFirstLetterCapitalSchema(['Python', 'Javascript']);

// Common schema for validation message and error
const validationMessageErrorSchema = z.object({
  show_message: booleanSchema.nullable().optional(),
  message_title: z.string().nullable().optional(),
  message_text: z.string().nullable().optional(),
  show_error: booleanSchema.nullable().optional(),
  error_style: enumToFirstLetterCapitalSchema(['Stop', 'Warning', 'Information']).nullable().optional(),
  error_message: z.string().nullable().optional(),
  error_title: z.string().nullable().optional(),
});

export const AIToolsArgsSchema = {
  [AITool.SetAIModel]: z.object({
    ai_model: z
      .string()
      .transform((val) => val.toLowerCase().replace(/\s+/g, '-'))
      .pipe(z.enum(['claude', '4.1'])),
  }),
  [AITool.SetChatName]: z.object({
    chat_name: stringSchema,
  }),
  [AITool.SetFileName]: z.object({
    file_name: stringSchema,
  }),
  [AITool.AddDataTable]: z.object({
    sheet_name: stringSchema,
    top_left_position: stringSchema,
    table_name: stringSchema,
    table_data: array2DSchema,
  }),
  [AITool.GetCodeCellValue]: z.object({
    sheet_name: z.string().nullable().optional(),
    code_cell_name: z.string().nullable().optional(),
    code_cell_position: z.string().nullable().optional(),
  }),
  [AITool.SetCodeCellValue]: z.object({
    sheet_name: stringNullableOptionalSchema,
    code_cell_name: stringSchema,
    code_cell_language: cellLanguageSchema,
    code_cell_position: stringSchema,
    code_string: stringSchema,
  }),
  [AITool.GetDatabaseSchemas]: z.object({
    connection_ids: z
      .preprocess((val) => (val ? val : []), z.array(z.string().uuid()))
      .transform((val) => val.filter((id) => !!id)),
  }),
  [AITool.SetSQLCodeCellValue]: z.object({
    sheet_name: z.string().nullable().optional(),
    code_cell_name: z.string(),
    connection_kind: z
      .string()
      .transform((val) => val.toUpperCase())
      .pipe(ConnectionTypeSchema),
    code_cell_position: z.string(),
    sql_code_string: z.string(),
    connection_id: z.string().uuid(),
  }),
  [AITool.SetFormulaCellValue]: z.object({
    formulas: z
      .array(
        z.object({
          sheet_name: stringNullableOptionalSchema,
          code_cell_position: stringSchema,
          formula_string: stringSchema,
        })
      )
      .min(1),
  }),
  [AITool.SetCellValues]: z.object({
    sheet_name: stringNullableOptionalSchema,
    top_left_position: stringSchema,
    cell_values: array2DSchema,
  }),
  [AITool.MoveCells]: z.object({
    sheet_name: stringNullableOptionalSchema,
    source_selection_rect: stringSchema,
    target_top_left_position: stringSchema,
  }),
  [AITool.DeleteCells]: z.object({
    sheet_name: stringNullableOptionalSchema,
    selection: stringSchema,
  }),
  [AITool.UpdateCodeCell]: z.object({
    code_string: stringSchema,
  }),
  [AITool.CodeEditorCompletions]: z.object({
    text_delta_at_cursor: stringSchema,
  }),
  [AITool.UserPromptSuggestions]: z.object({
    prompt_suggestions: z.array(
      z.object({
        label: stringSchema,
        prompt: stringSchema,
      })
    ),
  }),
  [AITool.EmptyChatPromptSuggestions]: z.object({
    prompt_suggestions: z.array(
      z.object({
        label: stringSchema,
        prompt: stringSchema,
      })
    ),
  }),
  [AITool.PDFImport]: z.object({
    file_name: stringSchema,
    prompt: stringSchema,
  }),
  [AITool.GetCellData]: z.object({
    sheet_name: stringNullableOptionalSchema,
    selection: stringSchema,
    page: numberSchema,
  }),
  [AITool.HasCellData]: z.object({
    sheet_name: z.string().nullable().optional(),
    selection: z.string(),
  }),
  [AITool.SetTextFormats]: z.object({
    formats: z
      .array(
        z.object({
          sheet_name: stringNullableOptionalSchema,
          selection: stringSchema,
          bold: booleanNullableOptionalSchema,
          italic: booleanNullableOptionalSchema,
          underline: booleanNullableOptionalSchema,
          strike_through: booleanNullableOptionalSchema,
          text_color: stringNullableOptionalSchema,
          fill_color: stringNullableOptionalSchema,
          align: stringNullableOptionalSchema,
          vertical_align: stringNullableOptionalSchema,
          wrap: stringNullableOptionalSchema,
          numeric_commas: booleanNullableOptionalSchema,
          number_type: stringNullableOptionalSchema,
          currency_symbol: stringNullableOptionalSchema,
          date_time: stringNullableOptionalSchema,
          font_size: z.number().nullable().optional(),
        })
      )
      .min(1),
  }),
  [AITool.GetTextFormats]: z.object({
    sheet_name: stringNullableOptionalSchema,
    selection: stringSchema,
    page: numberSchema,
  }),
  [AITool.ConvertToTable]: z.object({
    sheet_name: stringNullableOptionalSchema,
    selection: stringSchema,
    table_name: stringSchema,
    first_row_is_column_names: booleanSchema,
  }),
  [AITool.WebSearch]: z.object({
    query: stringSchema,
  }),
  [AITool.WebSearchInternal]: z.object({
    query: stringSchema,
  }),
  [AITool.AddSheet]: z.object({
    sheet_name: z.string(),
    insert_before_sheet_name: z.string().nullable().optional(),
  }),
  [AITool.DuplicateSheet]: z.object({
    sheet_name_to_duplicate: z.string(),
    name_of_new_sheet: z.string(),
  }),
  [AITool.RenameSheet]: z.object({
    sheet_name: z.string(),
    new_name: z.string(),
  }),
  [AITool.DeleteSheet]: z.object({
    sheet_name: z.string(),
  }),
  [AITool.MoveSheet]: z.object({
    sheet_name: z.string(),
    insert_before_sheet_name: z.string().nullable().optional(),
  }),
  [AITool.ColorSheets]: z.object({
    sheet_names_to_color: z.array(
      z.object({
        sheet_name: z.string(),
        color: z.string(),
      })
    ),
  }),
  [AITool.TextSearch]: z.object({
    query: z.string(),
    case_sensitive: booleanSchema,
    whole_cell: booleanSchema,
    search_code: booleanSchema,
    sheet_name: z.string().nullable().optional(),
  }),
  [AITool.RerunCode]: z.object({
    sheet_name: z.string().nullable().optional(),
    selection: z.string().nullable().optional(),
  }),
  [AITool.ResizeColumns]: z.object({
    sheet_name: z.string().nullable().optional(),
    selection: z.string(),
    size: z.enum(['auto', 'default']),
  }),
  [AITool.ResizeRows]: z.object({
    sheet_name: z.string().nullable().optional(),
    selection: z.string(),
    size: z.enum(['auto', 'default']),
  }),
  [AITool.SetBorders]: z.object({
    sheet_name: z.string().nullable().optional(),
    selection: z.string(),
    color: z.string(),
    line: z
      .string()
      .transform((val) => val.toLowerCase())
      .pipe(z.enum(['line1', 'line2', 'line3', 'dotted', 'dashed', 'double', 'clear'])),
    border_selection: z
      .string()
      .transform((val) => val.toLowerCase())
      .pipe(z.enum(['all', 'inner', 'outer', 'horizontal', 'vertical', 'left', 'top', 'right', 'bottom', 'clear'])),
  }),
  [AITool.InsertColumns]: z.object({
    sheet_name: z.string().nullable().optional(),
    column: z.string(),
    right: booleanSchema,
    count: numberSchema,
  }),
  [AITool.InsertRows]: z.object({
    sheet_name: z.string().nullable().optional(),
    row: numberSchema,
    below: booleanSchema,
    count: numberSchema,
  }),
  [AITool.DeleteColumns]: z.object({
    sheet_name: z.string().nullable().optional(),
    columns: z.array(z.string()),
  }),
  [AITool.DeleteRows]: z.object({
    sheet_name: z.string().nullable().optional(),
    rows: z.array(numberSchema),
  }),
  [AITool.TableMeta]: z.object({
    sheet_name: z.string().nullable().optional(),
    table_location: z.string(),
    new_table_name: z.string().nullable().optional(),
    first_row_is_column_names: booleanSchema.nullable().optional(),
    show_name: booleanSchema.nullable().optional(),
    show_columns: booleanSchema.nullable().optional(),
    alternating_row_colors: booleanSchema.nullable().optional(),
  }),
  [AITool.TableColumnSettings]: z.object({
    sheet_name: z.string().nullable().optional(),
    table_location: z.string(),
    column_names: z.array(
      z.object({
        old_name: z.string(),
        new_name: z.string(),
        show: booleanSchema,
      })
    ),
  }),
  [AITool.GetValidations]: z.object({
    sheet_name: z.string().nullable().optional(),
  }),
  [AITool.AddMessage]: z.object({
    sheet_name: z.string().nullable().optional(),
    selection: z.string(),
    message_title: z.string().nullable().optional(),
    message_text: z.string().nullable().optional(),
  }),
  [AITool.AddLogicalValidation]: z
    .object({
      sheet_name: z.string().nullable().optional(),
      selection: z.string(),
      show_checkbox: booleanSchema.nullable().optional(),
      ignore_blank: booleanSchema.nullable().optional(),
    })
    .merge(validationMessageErrorSchema),
  [AITool.AddListValidation]: z
    .object({
      sheet_name: z.string().nullable().optional(),
      selection: z.string(),
      ignore_blank: booleanSchema.nullable().optional(),
      drop_down: booleanSchema.nullable().optional(),
      list_source_list: z.string().nullable().optional(),
      list_source_selection: z.string().nullable().optional(),
    })
    .merge(validationMessageErrorSchema),
  [AITool.AddTextValidation]: z
    .object({
      sheet_name: z.string().nullable().optional(),
      selection: z.string(),
      ignore_blank: booleanSchema.nullable().optional(),
      max_length: numberSchema.nullable().optional(),
      min_length: numberSchema.nullable().optional(),
      contains_case_sensitive: z.string().nullable().optional(),
      contains_case_insensitive: z.string().nullable().optional(),
      not_contains_case_sensitive: z.string().nullable().optional(),
      not_contains_case_insensitive: z.string().nullable().optional(),
      exactly_case_sensitive: z.string().nullable().optional(),
      exactly_case_insensitive: z.string().nullable().optional(),
    })
    .merge(validationMessageErrorSchema),
  [AITool.AddNumberValidation]: z
    .object({
      sheet_name: z.string().nullable().optional(),
      selection: z.string(),
      ignore_blank: booleanSchema.nullable().optional(),
      range: z.string().nullable().optional(),
      equal: z.string().nullable().optional(),
      not_equal: z.string().nullable().optional(),
    })
    .merge(validationMessageErrorSchema),
  [AITool.AddDateTimeValidation]: z
    .object({
      sheet_name: z.string().nullable().optional(),
      selection: z.string(),
      ignore_blank: booleanSchema.nullable().optional(),
      date_range: z.string().nullable().optional(),
      date_equal: z.string().nullable().optional(),
      date_not_equal: z.string().nullable().optional(),
      time_range: z.string().nullable().optional(),
      time_equal: z.string().nullable().optional(),
      time_not_equal: z.string().nullable().optional(),
      require_date: booleanSchema.nullable().optional(),
      require_time: booleanSchema.nullable().optional(),
      prohibit_date: booleanSchema.nullable().optional(),
      prohibit_time: booleanSchema.nullable().optional(),
    })
    .merge(validationMessageErrorSchema),
  [AITool.RemoveValidations]: z.object({
    sheet_name: z.string().nullable().optional(),
    selection: z.string(),
  }),
  [AITool.Undo]: z.object({
    count: numberSchema.nullable().optional(),
  }),
  [AITool.Redo]: z.object({
    count: numberSchema.nullable().optional(),
  }),
  [AITool.ContactUs]: z.object({
    // No parameters needed, but we include a dummy property for schema compatibility.
    // Should we fix this now? Not sure why param would be required.
    acknowledged: booleanSchema.nullable().optional(),
  }),
  [AITool.OptimizePrompt]: z.object({
    optimized_prompt: stringSchema,
  }),
} as const;

export type AIToolsArgs = {
  [K in keyof typeof AIToolsArgsSchema]: z.infer<(typeof AIToolsArgsSchema)[K]>;
};

export type AIToolSpecRecord = {
  [K in AITool]: AIToolSpec<K>;
};

export const MODELS_ROUTER_CONFIGURATION: {
  [key in z.infer<(typeof AIToolsArgsSchema)[AITool.SetAIModel]>['ai_model']]: AIModelKey;
} = {
  claude: 'bedrock-anthropic:us.anthropic.claude-sonnet-4-5-20250929-v1:0:thinking-toggle-on',
  '4.1': 'azure-openai:gpt-4.1',
};

const validationMessageErrorPrompt: Record<string, AIToolArgsPrimitive> = {
  show_message: {
    type: ['boolean', 'null'],
    description:
      'Whether the message is shown whenever the cursor is on the cell with this validation. This is usually set to false unless specifically requested, for example, include instructions.',
  },
  message_title: {
    type: ['string', 'null'],
    description:
      'The title of the message to show when the cursor is on the cell with this validation. This defaults to null.',
  },
  message_text: {
    type: ['string', 'null'],
    description:
      'The text of the message to show when the cursor is on the cell with this validation. This defaults to null.',
  },
  show_error: {
    type: ['boolean', 'null'],
    description: 'Whether an error message is shown when the validation fails. This defaults to true.',
  },
  error_style: {
    type: ['string', 'null'],
    description: `Selected from Stop, Warning, and Information. This is the style of the error. Stop will stop the user from saving the cell; Warning will show a warning message but allows the user to enter the value. Information will show an information message if the validation fails. The default is Stop.`,
  },
  error_message: {
    type: ['string', 'null'],
    description: 'The text of the error message to show when the validation fails. This defaults to null.',
  },
  error_title: {
    type: ['string', 'null'],
    description: 'The title of the error message to show when the validation fails.',
  },
} as const;

export const aiToolsSpec: AIToolSpecRecord = {
  [AITool.SetAIModel]: {
    sources: ['ModelRouter'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
Sets the AI Model to use for this user prompt.\n
Choose the AI model for this user prompt based on the following instructions, always respond with only one the model options matching it exactly.\n
`,
    parameters: {
      type: 'object',
      properties: {
        ai_model: {
          type: 'string',
          description:
            'Value can be only one of the following: "claude" or "4.1" models exactly, this is the model best suited for the user prompt based on examples and model capabilities.\n',
        },
      },
      required: ['ai_model'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.SetAIModel],
    prompt: '',
  },
  [AITool.SetChatName]: {
    sources: ['GetChatName'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
Set the name of the user chat with AI assistant, this is the name of the chat in the chat history\n
You should use the set_chat_name function to set the name of the user chat with AI assistant, this is the name of the chat in the chat history.\n
This function requires the name of the chat, this should be concise and descriptive of the conversation, and should be easily understandable by a non-technical user.\n
The chat name should be based on user's messages and should reflect his/her queries and goals.\n
This name should be from user's perspective, not the assistant's.\n
`,
    parameters: {
      type: 'object',
      properties: {
        chat_name: {
          type: 'string',
          description: 'The name of the chat',
        },
      },
      required: ['chat_name'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.SetChatName],
    prompt: '',
  },
  [AITool.SetFileName]: {
    sources: ['GetFileName'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
Set the name of the file based on the AI chat conversation, this is the name of the file in the file system.\n
You should use the set_file_name function to set the name of the file based on the AI chat conversation between AI assistant and the user.\n
This function requires the name of the file, this should be concise and descriptive of the file's content and purpose, and should be easily understandable by a non-technical user.\n
The file name should be based on user's messages and should reflect the file's purpose and content.\n
This name should be from user's perspective, not the assistant's.\n
IMPORTANT: The file name must be 1-3 words only. Keep it short and concise.\n
The file name should focus on the analysis or topic being explored (e.g., "GDP over time", "Sales trends", "Budget analysis"), not on implementation details like "chart", "table", "report", or "dashboard". Focus on what is being analyzed, not how it's presented.\n
`,
    parameters: {
      type: 'object',
      properties: {
        file_name: {
          type: 'string',
          description: 'The name of the file. Must be 1-3 words only. Keep it short and concise.',
        },
      },
      required: ['file_name'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.SetFileName],
    prompt: '',
  },
  [AITool.GetCellData]: {
    sources: ['AIAnalyst', 'AIAssistant'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool returns the values of the cells in the chosen selection. The selection may be in the sheet or in a data table.\n
Use this tool to get the actual values of data on the sheet. For placement purposes, you MUST use the information in your context about where there is data on all the sheets.
Do NOT use this tool if there is no data based on the data bounds provided for the sheet, or if you already have the data in context.\n
You should use the get_cell_data function to get the values of the cells when you need more data for a successful reference.\n
Include the sheet name in both the selection and the sheet_name parameter. Use the current sheet name in the context unless the user is requesting data from another sheet, in which case use that sheet name.\n
get_cell_data function requires a string representation (in a1 notation) of a selection of cells to get the values of (e.g., "A1:B10", "TableName[Column 1]", or "Sheet2!D:D"), and the name of the current sheet.\n
The get_cell_data function may return page information. Use the page parameter to get the next page of results.\n
`,
    parameters: {
      type: 'object',
      properties: {
        sheet_name: {
          type: 'string',
          description:
            'The sheet name of the current sheet as defined in the context, unless the user is requesting data from another sheet. In which case, use that sheet name.',
        },
        selection: {
          type: 'string',
          description: `
The string representation (in a1 notation) of the selection of cells to get the values of. If the user is requesting data from another sheet, use that sheet name in the selection (e.g., "Sheet 2!A1")`,
        },
        page: {
          type: 'number',
          description:
            'The page number of the results to return. The first page is always 0. Use the parameters with a different page to get the next set of results.',
        },
      },
      required: ['sheet_name', 'selection', 'page'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.GetCellData],
    prompt: `
This tool returns the values of the cells in the chosen selection. The selection may be in the sheet or in a data table.\n
Use this tool to get the actual values of data on the sheet. For placement purposes, you MUST use the information in your context about where there is data on all the sheets.
Do NOT use this tool if there is no data based on the data bounds provided for the sheet, or if you already have the data in context.\n
You should use the get_cell_data function to get the values of the cells when you need more data for a successful reference.\n
Include the sheet name in both the selection and the sheet_name parameter. Use the current sheet name in the context unless the user is requesting data from another sheet, in which case use that sheet name.\n
get_cell_data function requires a string representation (in a1 notation) of a selection of cells to get the values of (e.g., "A1:B10", "TableName[Column 1]", or "Sheet2!D:D"), and the name of the current sheet.\n
The get_cell_data function may return page information. Use the page parameter to get the next page of results.\n
`,
  },
  [AITool.HasCellData]: {
    sources: ['AIAnalyst'],
    aiModelModes: [],
    description: `
This tool checks if the cells in the chosen selection have any data.
Use MUST use this tool before creating or moving tables, code, connections, or cells to avoid spilling cells over existing data.
`,
    parameters: {
      type: 'object',
      properties: {
        sheet_name: {
          type: 'string',
          description:
            'The sheet name of the current sheet as defined in the context, unless the user is requesting data from another sheet. In which case, use that sheet name.',
        },
        selection: {
          type: 'string',
          description: `
The string representation (in a1 notation) of the selection of cells to check for data. If the user is requesting data from another sheet, use that sheet name in the selection (e.g., "Sheet 2!A1")`,
        },
      },
      required: ['sheet_name', 'selection'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.HasCellData],
    prompt: `
This tool checks if the cells in the chosen selection have any data.
Use MUST use this tool before creating or moving tables, code, connections, or cells to avoid spilling cells over existing data.
`,
  },
  [AITool.AddDataTable]: {
    sources: ['AIAnalyst', 'PDFImport'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
Adds a data table to the sheet with sheet_name, requires the sheet name, top left cell position (in a1 notation), the name of the data table and the data to add. The data should be a 2d array of strings, where each sub array represents a row of values.\n
Do NOT use this tool if you want to convert existing data to a data table. Use convert_to_table instead.\n
The first row of the data table is considered to be the header row, and the data table will be created with the first row as the header row.\n
All rows in the 2d array of values should be of the same length. Use empty strings for missing values but always use the same number of columns for each row.\n
Data tables are best for adding new tabular data to the sheet. Do not use this tool for adding non-tabular data to the sheet or data that requires inputs like calculators. Use set_cell_values for that kind of task.\n
Don't use this tool to add data to an existing data table. Use set_cell_values function to add data to an existing data table.\n
`,
    parameters: {
      type: 'object',
      properties: {
        sheet_name: {
          type: 'string',
          description: 'The sheet name of the current sheet as defined in the context',
        },
        top_left_position: {
          type: 'string',
          description:
            'The top left position of the data table on the current open sheet, in a1 notation. This should be a single cell, not a range.',
        },
        table_name: {
          type: 'string',
          description:
            "The name of the data table to add to the current open sheet. This should be a concise and descriptive name of the data table. Don't use special characters or spaces in the name. Always use a unique name for the data table. Spaces, if any, in name are replaced with underscores.",
        },
        table_data: {
          type: 'array',
          items: {
            type: 'array',
            items: {
              type: 'string',
              description: 'The string that is the value to set in the cell',
            },
          },
        },
      },
      required: ['sheet_name', 'top_left_position', 'table_name', 'table_data'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.AddDataTable],
    prompt: `
Adds a data table to the current sheet defined in the context, requires the sheet name, top_left_position (in a1 notation), the name of the data table and the data to add. The data should be a 2d array of strings, where each sub array represents a row of values.\n
top_left_position is the anchor position of the data table.\n
Do NOT use this tool if you want to convert existing data to a data table. Use convert_to_table instead.\n
The first row of the data table is considered to be the header row, and the data table will be created with the first row as the header row.\n
The added table on the sheet contains an extra row with the name of the data table. Always leave 2 rows of extra space on the bottom and 2 columns of extra space on the right when adding data tables on the sheet.\n
All rows in the 2d array of values should be of the same length. Use empty strings for missing values but always use the same number of columns for each row.\n
Data tables are best for adding new tabular data to the sheet. Do not use this tool for adding non-tabular data to the sheet or data that requires inputs like calculators. Use set_cell_values for that kind of task.\n
Don't use this tool to add data to a data table that already exists. Use set_cell_values function to add data to a data table that already exists.\n
All values can be referenced in the code cells immediately. Always refer to the cell by its position on respective sheet, in a1 notation. Don't add values manually in code cells.\n
To delete a data table, use set_cell_values function with the top_left_position of the data table and with just one empty string value at the top_left_position. Overwriting the top_left_position (anchor position) deletes the data table.\n
Don't attempt to add formulas or code to data tables.\n`,
  },
  [AITool.SetCellValues]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
Sets the values of the current open sheet cells to a 2d array of strings, requires the top_left_position (in a1 notation) and the 2d array of strings representing the cell values to set.\n
Unless specifically requested, do NOT place cells over existing data on the sheet. You have enough information in the context to know where all cells are in the sheets.
Use set_cell_values function to add data to the current open sheet. Don't use code cell for adding data. Always add data using this function.\n\n
When adding new data or information to the sheet, bias towards using this function instead of add_data_table, unless the data is clearly tabular data.\n
Values are string representation of text, number, logical, time instant, duration, error, html, code, image, date, time or blank.\n
top_left_position is the position of the top left corner of the 2d array of values on the current open sheet, in a1 notation. This should be a single cell, not a range. Each sub array represents a row of values.\n
All values can be referenced in the code cells immediately. Always refer to the cell by its position on respective sheet, in a1 notation. Don't add values manually in code cells.\n
To clear the values of a cell, set the value to an empty string.\n
Don't use this tool for adding formulas or code. Use set_code_cell_value function for Python/Javascript code or set_formula_cell_value function for formulas.\n
`,
    parameters: {
      type: 'object',
      properties: {
        sheet_name: {
          type: 'string',
          description: 'The sheet name of the current sheet as defined in the context',
        },
        top_left_position: {
          type: 'string',
          description:
            'The position of the top left cell, in a1 notation, in the current open sheet. This is the top left corner of the added 2d array of values on the current open sheet. This should be a single cell, not a range.',
        },
        cell_values: {
          type: 'array',
          items: {
            type: 'array',
            items: {
              type: 'string',
              description: 'The string that is the value to set in the cell',
            },
          },
        },
      },
      required: ['sheet_name', 'top_left_position', 'cell_values'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.SetCellValues],
    prompt: `
You should use the set_cell_values function to set the values of a sheet to a 2d array of strings.\n
Unless specifically requested, do NOT place cells over existing data on the sheet. You have enough information in the context to know where all cells are in the sheets.
Use this function to add data to a sheet. Don't use code cell for adding data. Always add data using this function.\n\n
When adding new data or information to the sheet, bias towards using this function instead of add_data_table, unless the data is clearly tabular data.\n
CRITICALLY IMPORTANT: you MUST insert column headers ABOVE the first row of data.\n
When setting cell values, follow these rules for headers:\n
1. The header row MUST be the first row in the cell_values array\n
2. The header row MUST contain column names that describe the data below\n
3. The header row MUST have the same number of columns as the data rows\n
4. The header row MUST be included in the cell_values array, not as a separate operation\n
5. The top_left_position MUST point to where the header row should start, which is usually the row above the first row of inserted data\n\n
This function requires the sheet name of the current sheet from the context, the top_left_position (in a1 notation) and the 2d array of strings representing the cell values to set. Values are string representation of text, number, logical, time instant, duration, error, html, code, image, date, time or blank.\n
Values set using this function will replace the existing values in the cell and can be referenced in the code cells immediately. Always refer to the cell by its position on respective sheet, in a1 notation. Don't add these in code cells.\n
To clear the values of a cell, set the value to an empty string.\n
Don't use this tool for adding formulas or code. Use set_code_cell_value function for Python/Javascript code or set_formula_cell_value function for formulas.\n
`,
  },
  [AITool.GetCodeCellValue]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool gets the full code for a Python, JavaScript, Formula, or connection cell.\n
Use this tool to view the code in an existing code cell so you can fix errors or make improvements. Once you've read the code, you can improve it using the set_code_cell_value tool call.\n
This tool should be used when users want to make updates to an existing code cell that isn't already in context.\n`,
    parameters: {
      type: 'object',
      properties: {
        sheet_name: {
          type: 'string',
          description: 'The sheet name of the current sheet as defined in the context',
        },
        code_cell_name: {
          type: 'string',
          description: 'The name of the code cell to get the value of',
        },
        code_cell_position: {
          type: 'string',
          description: 'The position of the code cell to get the value of, in a1 notation',
        },
      },
      required: ['sheet_name', 'code_cell_name', 'code_cell_position'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.GetCodeCellValue],
    prompt: `
This tool gets the full code for a Python, JavaScript, Formula, or connection cell.\n
Use this tool to view the code in an existing code cell so you can fix errors or make improvements. Once you've read the code, you can improve it using the set_code_cell_value tool call.\n
This tool should be used when users want to make updates to an existing code cell that isn't already in context.\n`,
  },
  [AITool.SetCodeCellValue]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
Sets the value of a code cell and runs it in the current open sheet, requires the language (Python or Javascript), cell position (in a1 notation), and code string.\n
Default output size of a new plot/chart is 7 wide * 23 tall cells.\n
You should use the set_code_cell_value function to set code cell values; use set_code_cell_value function instead of responding with code.\n
Never use set_code_cell_value function to set the value of a cell to a value that is not code. Don't add static data to the current open sheet using set_code_cell_value function, use set_cell_values instead. set_code_cell_value function is only meant to set the value of a cell to code.\n
Provide a name for the output of the code cell. The name cannot contain spaces or special characters (but _ is allowed).\n
Note: only name the code cell if it is new.\n
If this tool created a spill you MUST delete the original code cell and recreate it at a different location to avoid multiple code cells in the sheet.
Always refer to the data from cell by its position in a1 notation from respective sheet.\n
Do not attempt to add code to data tables, it will result in an error.\n
Do NOT delete the source data or tables that the code cell references unless the user explicitly asks you to. The code depends on this data to function correctly.\n
This tool is for Python and Javascript code only. For formulas, use set_formula_cell_value. For SQL Connections, use set_sql_code_cell_value.\n\n

Code cell (Python and Javascript) placement instructions:\n
- Determine the approximate output size of the code cell before placing it.
- By default, charts will output 7 wide * 23 tall cells (if columns and rows have default width and height). If the code cell is placed in a location that is not empty, it will result in spill error.
- The code cell location should be empty and positioned such that it will not overlap other cells. If there is a value in a single cell where the code result is supposed to go, it will result in spill error. Use current open sheet context to identify empty space.\n
- Leave one extra column gap between the code cell being placed and the nearest content if placing horizontally. If placing vertically, leave one extra row gap between the code cell and the nearest content.
- Pick a location that makes sense relative to the existing contents of the sheet. Line up placements with existing content. E.g. if placing next to a table at A1:C19, place the code cell at E1 (keeping in mind the extra column gap since placing horizontally).
- In case there is not enough empty space near the existing contents of the sheet, choose a distant empty cell.\n
- Consider the overall layout and organization of the current open sheet when placing the code cell, ensuring it doesn't disrupt existing data or interfere with other code cells.\n
- A plot returned by the code cell occupies space on the sheet and spills if there is any data present in the sheet where the plot is supposed to be placed. Default output size of a new plot is 7 wide * 23 tall cells.\n
- Cursor location should not impact placement decisions.\n
- If the sheet is empty, place the code cell at A1.\n
`,
    parameters: {
      type: 'object',
      properties: {
        sheet_name: {
          type: 'string',
          description: 'The sheet name of the current sheet as defined in the context',
        },
        code_cell_name: {
          type: 'string',
          description:
            'What to name the output of the code cell. The name cannot contain spaces or special characters (but _ is allowed). First letter capitalized is preferred.',
        },
        code_cell_language: {
          type: 'string',
          description: 'The language of the code cell, this can be one of Python or Javascript.',
        },
        code_cell_position: {
          type: 'string',
          description:
            'The position of the code cell in the current open sheet, in a1 notation. This should be a single cell, not a range.',
        },
        code_string: {
          type: 'string',
          description: 'The code which will run in the cell',
        },
      },
      required: ['sheet_name', 'code_cell_name', 'code_cell_language', 'code_cell_position', 'code_string'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.SetCodeCellValue],
    prompt: `
Use set_code_cell_value instead of responding with code.\n
set_code_cell_value tool is used to add Python or Javascript code cell to the sheet.\n
Set code cell value tool should be used for relatively complex tasks. Tasks like data transformations, correlations, machine learning, slicing, etc. For more simple tasks, use set_formula_cell_value.\n
If this tool created a spill you MUST delete the original code cell and recreate it at a different location to avoid multiple code cells in the sheet.
Never use set_code_cell_value function to set the value of a cell to a value that is not code. Don't add data to the current open sheet using set_code_cell_value function, use set_cell_values instead. set_code_cell_value function is only meant to set the value of a cell to code.\n
set_code_cell_value function requires language, codeString, and the cell position (single cell in a1 notation).\n
Always refer to the cells on sheet by its position in a1 notation, using q.cells function. Don't add values manually in code cells.\n
Do NOT delete the source data or tables that the code cell references unless the user explicitly asks you to. The code depends on this data to function correctly.\n
This tool is for Python and Javascript code only. For formulas, use set_formula_cell_value.\n

Code cell (Python and Javascript) placement instructions:\n
- The code cell location should be empty and positioned such that it will not overlap other cells. If there is a value in a single cell where the code result is supposed to go, it will result in spill error. Use current open sheet context to identify empty space.\n
- Leave one extra column gap between the code cell being placed and the nearest content if placing horizontally. If placing vertically, leave one extra row gap between the code cell and the nearest content.\n
- Pick a location that makes sense relative to the existing contents of the sheet. Line up placements with existing content. E.g. if placing next to a table at A1:C19, place the code cell at E1 (keeping in mind the extra column gap since placing horizontally).\n
- In case there is not enough empty space near the existing contents of the sheet, choose a distant empty cell.\n
- Consider the overall layout and organization of the current open sheet when placing the code cell, ensuring it doesn't disrupt existing data or interfere with other code cells.\n
- A plot returned by the code cell occupies space on the sheet and spills if there is any data present in the sheet where the plot is supposed to be placed. Default output size of a new plot is 7 wide * 23 tall cells.\n
- Cursor location should not impact placement decisions.\n
- If the sheet is empty, place the code cell at A1.\n

Think carefully about the placement rules and examples. Always ensure the code cell is placed where it does not create a spill error.
`,
  },
  [AITool.GetDatabaseSchemas]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
Retrieves detailed database table schemas including column names, data types, and constraints.\n
Use this tool every time you want to write SQL. You need the table schema to write accurate queries.\n
If connection_ids is an empty array, it will return detailed schemas for all available team connections.\n
`,
    parameters: {
      type: 'object',
      properties: {
        connection_ids: {
          type: 'array',
          items: {
            type: 'string',
            description:
              'UUID string corresponding to the connection ID of the SQL Connection for which you want to get the schemas.',
          },
        },
      },
      required: ['connection_ids'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.GetDatabaseSchemas],
    prompt: `
Retrieves detailed database table schemas including column names, data types, and constraints.\n
Use this tool every time you want to write SQL. You need the table schema to write accurate queries.\n
If connection_ids is an empty array, it will return detailed schemas for all available team connections.\n
This tool should always be called before writing SQL. If you don't have the table schema, you cannot write accurate SQL queries.\n
`,
  },
  [AITool.SetSQLCodeCellValue]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
Adds or updates a SQL Connection code cell and runs it in the 'sheet_name' sheet. Requires the connection_kind, connection_id, cell position (in A1 notation), and code string.\n
Output of the code cell is a table. Provide a name for the output table of the code cell. The name cannot contain spaces or special characters, but _ is allowed.\n
Note: only name the code cell if it is new.\n
Do not attempt to add code to data tables, it will result in an error. Use set_cell_values or add_data_table to add data to the sheet.\n
This tool is for SQL Connection code only. For Python and Javascript use set_code_cell_value. For Formulas, use set_formula_cell_value.\n\n

IMPORTANT: if you've already created a table and user wants to make subsequent queries on that same table, use the existing code cell instead of creating a new query.

For SQL Connection code cells:\n
- Use the Connection ID (uuid) and Connection language: POSTGRES, MYSQL, MSSQL, SNOWFLAKE, BIGQUERY, COCKROACHDB, MARIADB, SUPABASE, NEON or MIXPANEL.\n
- The Connection ID must be from an available database connection in the team.\n
- Use the GetDatabaseSchemas tool to get the database schemas before writing SQL queries.\n
- Write SQL queries that reference the database tables and schemas provided in context.\n

SQL code cell placement instructions:\n
- The code cell location should be empty and positioned such that it will not overlap other cells. If there is an existing value in a single cell where the code result is supposed to go, it will result in spill error. Use current open sheet context to identify empty space.\n
- If the sheet is empty, place the code cell at A1.\n
- Use the existing SQL cell location if editing existing SQL code cell. Queries that are on a table that already exists in the sheet should be edits to existing code tables, not new tables unless the user specifically asks for a new table.\n
`,
    parameters: {
      type: 'object',
      properties: {
        sheet_name: {
          type: 'string',
          description: 'The sheet name of the current sheet as defined in the context',
        },
        code_cell_name: {
          type: 'string',
          description:
            'What to name the output of the code cell. The name cannot contain spaces or special characters (but _ is allowed). First letter capitalized is preferred.',
        },
        connection_kind: {
          type: 'string',
          description:
            'The kind of the sql code cell, this can be one of POSTGRES, MYSQL, MSSQL, SNOWFLAKE, BIGQUERY, COCKROACHDB, MARIADB, SUPABASE, NEON or MIXPANEL.',
        },
        code_cell_position: {
          type: 'string',
          description:
            'The position of the code cell in the current open sheet, in a1 notation. This should be a single cell, not a range.',
        },
        sql_code_string: {
          type: 'string',
          description: 'The code which will run in the cell',
        },
        connection_id: {
          type: 'string',
          description:
            'This is uuid string corresponding to the connection ID of the SQL Connection code cell. There can be multiple connections in the team, so this is required to identify the connection along with the language.',
        },
      },
      required: [
        'sheet_name',
        'code_cell_name',
        'connection_kind',
        'code_cell_position',
        'sql_code_string',
        'connection_id',
      ],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.SetSQLCodeCellValue],
    prompt: `
Adds or updates a SQL Connection code cell and runs it in the 'sheet_name' sheet. Requires the connection_kind, connection_id, cell position (in A1 notation), and code string.\n
Output of the code cell is a table. Provide a name for the output table of the code cell. The name cannot contain spaces or special characters, but _ is allowed.\n
Note: only name the code cell if it is new.\n
Do not attempt to add code to data tables, it will result in an error. Use set_cell_values or add_data_table to add data to the sheet.\n
This tool is for SQL Connection code only. For Python and Javascript use set_code_cell_value. For Formulas, use set_formula_cell_value.\n

IMPORTANT: if you've already created a table and user wants to make subsequent queries on that same table, use the existing code cell instead of creating a new query.

For SQL Connection code cells:\n
- Use the Connection ID (uuid) and Connection language: POSTGRES, MYSQL, MSSQL, SNOWFLAKE, BIGQUERY, COCKROACHDB, MARIADB, SUPABASE, NEON or MIXPANEL.\n
- The Connection ID must be from an available database connection in the team.\n
- Use the GetDatabaseSchemas tool to get the database schemas before writing SQL queries.\n
- Write SQL queries that reference the database tables and schemas provided in context.\n

SQL code cell placement instructions:\n
- The code cell location should be empty and positioned such that it will not overlap other cells. If there is an existing value in a single cell where the code result is supposed to go, it will result in spill error. Use current open sheet context to identify empty space.\n
- If the sheet is empty, place the code cell at A1.\n
- Use the existing SQL cell location if editing existing SQL code cell. Queries that are on a table that already exists in the sheet should be edits to existing code tables, not new tables unless the user specifically asks for a new table.\n
`,
  },

  [AITool.SetFormulaCellValue]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
Sets the value of one or more formula cells and runs them. Use the formulas array to set multiple different formulas in a single call, each with its own sheet, cell position, and formula string.\n
You should use the set_formula_cell_value function to set formula cell values. Use set_formula_cell_value function instead of responding with formulas.\n
Never use set_formula_cell_value function to set the value of a cell to a value that is not a formula. Don't add static data to the current open sheet using set_formula_cell_value function, use set_cell_values instead. set_formula_cell_value function is only meant to set the value of a cell to formulas.\n
Always refer to the data from cell by its position in a1 notation from respective sheet. Don't add values manually in formula cells.\n
Do not attempt to add formulas to data tables, it will result in an error.\n
This tool is for formulas only. For Python and Javascript code, use set_code_cell_value.\n
When using a range, cell references in the formula will automatically adjust relatively for each cell (like copy-paste in spreadsheets). Use $ for absolute references (e.g., $A$1) when you want references to stay fixed.\n
`,
    parameters: {
      type: 'object',
      properties: {
        formulas: {
          type: 'array',
          items: {
            type: 'object',
            properties: {
              sheet_name: {
                type: 'string',
                description: 'The sheet name of the sheet where the formula will be placed, as defined in the context',
              },
              code_cell_position: {
                type: 'string',
                description:
                  'The position of the formula cell(s) in a1 notation. This can be a single cell (e.g., "A1") or a range (e.g., "A1:A10") or a collection (e.g., "A1,A2:B2,A3").',
              },
              formula_string: {
                type: 'string',
                description:
                  'The formula which will run in the cell(s). If code_cell_position is a range or collection, cell references will adjust relatively for each cell (e.g., formula "A1" applied to range B1:B3 becomes "A1", "A2", "A3"). Use $ for absolute references (e.g., "$A$1" stays fixed for all cells).',
              },
            },
            required: ['sheet_name', 'code_cell_position', 'formula_string'],
            additionalProperties: false,
          },
        },
      },
      required: ['formulas'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.SetFormulaCellValue],
    prompt: `
You should use the set_formula_cell_value function to set formula cell values. Use set_formula_cell_value instead of responding with formulas.\n
Never use set_formula_cell_value function to set the value of a cell to a value that is not a formula. Don't add data to the current open sheet using set_formula_cell_value function, use set_cell_values instead. set_formula_cell_value function is only meant to set the value of a cell to a formula.\n
set_formula_cell_value function requires an array of formulas, each with a sheet_name, formula_string, and code_cell_position (single cell or range in a1 notation).\n
Always refer to the cells on sheet by its position in a1 notation. Don't add values manually in formula cells.\n
This tool is for formulas only. For Python and Javascript code, use set_code_cell_value.\n
Don't prefix formulas with \`=\` in formula cells.\n

Using the formulas array:\n
- You can set multiple different formulas at once by providing multiple objects in the formulas array.\n
- Each object requires a sheet_name, code_cell_position, and formula_string.\n
- Example: formulas: [{ sheet_name: "Sheet1", code_cell_position: "A1", formula_string: "SUM(B1:B10)" }, { sheet_name: "Sheet1", code_cell_position: "A2", formula_string: "AVERAGE(B1:B10)" }]\n

Multiple formula cells with relative referencing:\n
- Within each formula object, you can use a range for code_cell_position (e.g., "A1:A10") to apply the same formula pattern.\n
- Cell references in the formula will automatically adjust relatively for each cell, just like when you copy and paste a formula in a spreadsheet.\n
- Example: If you apply formula "SUM(A1)" to range B1:B3, it becomes "SUM(A1)" in B1, "SUM(A2)" in B2, and "SUM(A3)" in B3.\n
- To keep a reference fixed across all cells, use absolute references with $ (e.g., "$A$1" stays as "$A$1" in all cells).\n
- Mixed references are supported: "$A1" keeps column A fixed but row adjusts, "A$1" keeps row 1 fixed but column adjusts.\n

Formulas placement instructions:\n
- The formula cell location should be empty and positioned such that it will not overlap other cells. If there is a value in a single cell where the formula result is supposed to go, it will result in spill error. Use current open sheet context to identify empty space.\n
- The formula cell should be near the data it references, so that it is easy to understand the formula in the context of the data. Identify the data being referenced from the Formula and use the nearest unoccupied cell. If multiple data references are being made, choose the one which is most relevant to the Formula.\n
- Unlike code cell placement, Formula cell placement should not use an extra space; formulas should be placed next to the data they reference or next to a label for the calculation.\n
- Pick the location that makes the most sense next to what is being referenced. E.g. formula aggregations often make sense directly underneath or directly beside the data being referenced or next to the label for the calculation.\n
- When doing a calculation on a table column, place the formula directly below the last row of the table.\n

When to use set_formula_cell_value:\n
Set formula cell value tool should be used for relatively simple tasks. Tasks like aggregations, finding means, totals, counting number of instances, etc. You can use this for calculations that reference values in and out of tables. For more complex tasks, use set_code_cell_value.\n
Examples:
- Finding the mean of a column of numbers
- Counting the number of instances of a value in a column
- Finding the max/min value
- Basic arithmetic operations
- Joining strings
- Applying formulas to multiple cells with relative references (e.g., calculating percentages for a column of data)
`,
  },
  [AITool.MoveCells]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
Moves a rectangular selection of cells from one location to another on the current open sheet, requires the source and target locations.\n
You MUST use this tool to fix spill errors to move code, tables, or charts to a different location.\n
You should use the move_cells function to move a rectangular selection of cells from one location to another on the current open sheet.\n
When moving a single spilled code cell, use the move tool to move just the single anchor cell of that code cell causing the spill.\n
move_cells function requires the source and target locations. Source location is the top left and bottom right corners of the selection rectangle to be moved.\n
When moving a table, leave a space between the table and any surrounding content. This is more aesthetic and easier to read.\n
Target location is the top left corner of the target location on the current open sheet.\n
`,
    parameters: {
      type: 'object',
      properties: {
        sheet_name: {
          type: 'string',
          description: 'The sheet name of the current sheet in the context',
        },
        source_selection_rect: {
          type: 'string',
          description:
            'The selection of cells, in a1 notation, to be moved in the current open sheet. This is string representation of the rectangular selection of cells to be moved',
        },
        target_top_left_position: {
          type: 'string',
          description:
            'The top left position of the target location on the current open sheet, in a1 notation. This should be a single cell, not a range. This will be the top left corner of the source selection rectangle after moving.',
        },
      },
      required: ['sheet_name', 'source_selection_rect', 'target_top_left_position'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.MoveCells],
    prompt: `
You should use the move_cells function to move a rectangular selection of cells from one location to another on the current open sheet.\n
You MUST use this tool to fix spill errors to move code, tables, or charts to a different location.\n
When moving a single spilled code cell, use the move tool to move just the single anchor cell of that code cell causing the spill.\n
move_cells function requires the current sheet name provided in the context, the source selection, and the target position. Source selection is the string representation (in a1 notation) of a selection rectangle to be moved.\n
Target position is the top left corner of the target position on the current open sheet, in a1 notation. This should be a single cell, not a range.\n
`,
  },
  [AITool.DeleteCells]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
Deletes the value(s) of a selection of cells, requires a string representation of a selection of cells to delete. Selection can be a single cell or a range of cells or multiple ranges in a1 notation.\n
You should use the delete_cells function to delete the value(s) of a selection of cells in the sheet with sheet_name.\n
You MUST NOT delete cells or tables that are referenced by code cells unless the user explicitly asks you to. If code references data, deleting that data will break the code.\n
delete_cells functions requires a string representation (in a1 notation) of a selection of cells to delete. Selection can be a single cell or a range of cells or multiple ranges in a1 notation.\n
You MUST use this tool to delete columns in tables by providing it with the column name in A1. For example, "TableName[Column Name]".
You MUST use this tool to delete tables by providing it with the table name in A1. For example, "TableName".
`,
    parameters: {
      type: 'object',
      properties: {
        sheet_name: {
          type: 'string',
          description: 'The sheet name of the current sheet as defined in the context',
        },
        selection: {
          type: 'string',
          description:
            'The string representation (in a1 notation) of the selection of cells to delete, this can be a single cell or a range of cells or multiple ranges in a1 notation',
        },
      },
      required: ['sheet_name', 'selection'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.DeleteCells],
    prompt: `
You should use the delete_cells function to delete the value(s) of a selection of cells in the sheet with sheet_name.\n
You MUST NOT delete cells that are referenced by code cells unless the user explicitly asks you to. For example, if you write Python code that references cells, you MUST NOT delete the original cells or the Python code will stop working.\n
You MUST use this tool to delete columns in tables by providing it with the column name in A1. For example, "TableName[Column Name]".
You MUST use this tool to delete tables by providing it with the table name in A1. For example, "TableName".
delete_cells functions requires the current sheet name provided in the context, and a string representation (in a1 notation) of a selection of cells to delete. Selection can be a single cell or a range of cells or multiple ranges in a1 notation.\n
`,
  },
  [AITool.UpdateCodeCell]: {
    sources: ['AIAssistant'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool updates the code in the code cell you are currently editing, requires the code string to update the code cell with. Provide the full code string, don't provide partial code. This will replace the existing code in the code cell.\n
The code cell editor will switch to diff editor mode and will show the changes you made to the code cell, user can accept or reject the changes.\n
New code runs in the cell immediately, so the user can see output of the code cell after it is updates.\n
Never include code in the chat when using this tool, always explain brief what changes are made and why.\n
When using this tool, make sure this is the only tool used in the response.\n
`,
    parameters: {
      type: 'object',
      properties: {
        code_string: {
          type: 'string',
          description: 'The code string to update the code cell with',
        },
      },
      required: ['code_string'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.UpdateCodeCell],
    prompt: `
You should use the update_code_cell function to update the code in the code cell you are currently editing.\n
update_code_cell function requires the code string to update the code cell with.\n
Provide the full code string, don't provide partial code. This will replace the existing code in the code cell.\n
The code cell editor will switch to diff editor mode and will show the changes you made to the code cell, user can accept or reject the changes.\n
New code runs in the cell immediately, so the user can see output of the code cell after it is updates.\n
Never include code in the chat when using this tool, always explain brief what changes are made and why.\n
When using this tool, make sure this is the only tool used in the response.\n
When using this tool, make sure the code cell is the only cell being edited.\n
`,
  },
  [AITool.GetTextFormats]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool returns the text formatting information of a selection of cells on a specified sheet, requires the sheet name, the selection of cells to get the formats of.\n
Do NOT use this tool if there is no formatting in the region based on the format bounds provided for the sheet.\n
It should be used to find formatting within a sheet's formatting bounds.\n
It returns a string representation of the formatting information of the cells in the selection.\n
If there are multiple pages of formatting information, use the page parameter to get the next set of results.\n
`,
    parameters: {
      type: 'object',
      properties: {
        sheet_name: {
          type: 'string',
          description: 'The sheet name of the current sheet as defined in the context',
        },
        selection: {
          type: 'string',
          description: 'The selection of cells to get the formats of, in a1 notation',
        },
        page: {
          type: 'number',
          description:
            'The page number of the results to return. The first page is always 0. Use the parameters with a different page to get the next set of results.',
        },
      },
      required: ['sheet_name', 'selection', 'page'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.GetTextFormats],
    prompt: `
The get_text_formats tool returns the text formatting information of a selection of cells on a specified sheet, requires the sheet name, the selection of cells to get the formats of.\n
Do NOT use this tool if there is no formatting in the region based on the format bounds provided for the sheet.\n
It should be used to find formatting within a sheet's formatting bounds.\n
It returns a string representation of the formatting information of the cells in the selection.\n
If too large, the results will include page information:\n
- If page information is provided, perform actions on the current page's results before requesting the next page of results.\n
- Always review all pages of results; as you get each page, immediately perform any actions before moving to the next page.\n
`,
  },
  [AITool.SetTextFormats]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool sets the text formats of one or more selections of cells. Use the formats array to apply different formatting to multiple selections in a single call.\n
Each format entry must have at least one non-null format to set.\n
You can set bold, italic, underline, strike through, text/fill colors, alignment, wrapping, numeric formats, date formats, and font size.\n
Percentages in Quadratic work the same as in any spreadsheet. E.g. formatting .01 as a percentage will show as 1%. Formatting 1 as a percentage will show 100%.\n
`,
    parameters: {
      type: 'object',
      properties: {
        formats: {
          type: 'array',
          items: {
            type: 'object',
            properties: {
              sheet_name: {
                type: 'string',
                description: 'The sheet name of the current sheet as defined in the context',
              },
              selection: {
                type: 'string',
                description: `The selection of cells to set the formats of, in A1 notation. ALWAYS use table names when formatting entire tables (e.g., "Table1"). Only use A1 notation for partial table selections or non-table data. When formatting multiple non-contiguous cells, use comma-separated ranges (e.g., "A1,B2:D5,E20").`,
              },
              bold: {
                type: ['boolean', 'null'],
                description: 'Whether to set the cell to bold. Set to null to remove bold formatting.',
              },
              italic: {
                type: ['boolean', 'null'],
                description: 'Whether to set the cell to italic. Set to null to remove italic formatting.',
              },
              underline: {
                type: ['boolean', 'null'],
                description: 'Whether to set the cell to underline. Set to null to remove underline formatting.',
              },
              strike_through: {
                type: ['boolean', 'null'],
                description:
                  'Whether to set the cell to strike through. Set to null to remove strike through formatting.',
              },
              text_color: {
                type: ['string', 'null'],
                description:
                  'The color of the text, in hex format. To remove the text color, set the value to an empty string.',
              },
              fill_color: {
                type: ['string', 'null'],
                description:
                  'The color of the background, in hex format. To remove the fill color, set the value to an empty string.',
              },
              align: {
                type: ['string', 'null'],
                description:
                  'The horizontal alignment of the text, this can be one of "left", "center", "right". Set to null to remove alignment formatting.',
              },
              vertical_align: {
                type: ['string', 'null'],
                description:
                  'The vertical alignment of the text, this can be one of "top", "middle", "bottom". Set to null to remove vertical alignment formatting.',
              },
              wrap: {
                type: ['string', 'null'],
                description:
                  'The wrapping of the text, this can be one of "wrap", "clip", "overflow". Set to null to remove wrap formatting.',
              },
              numeric_commas: {
                type: ['boolean', 'null'],
                description:
                  'For numbers larger than three digits, whether to show commas. If true, then numbers will be formatted with commas. Set to null to remove comma formatting.',
              },
              number_type: {
                type: ['string', 'null'],
                description:
                  'The type for the numbers, this can be one of "number", "currency", "percentage", or "exponential". If "currency" is set, you MUST set the currency_symbol. Set to null to remove number type formatting.',
              },
              currency_symbol: {
                type: ['string', 'null'],
                description:
                  'If number_type is "currency", use this to set the currency symbol, for example "$" for USD or "€" for EUR. Set to null to remove currency symbol.',
              },
              date_time: {
                type: ['string', 'null'],
                description:
                  'formats a date time value using Rust\'s chrono::format, e.g., "%Y-%m-%d %H:%M:%S", "%d/%m/%Y". Set to null to remove date/time formatting.',
              },
              font_size: {
                type: ['number', 'null'],
                description:
                  'The font size in points. Default is 10. Set to a number to change the font size (e.g., 16). Set to null to remove font size formatting.',
              },
            },
            required: ['sheet_name', 'selection', 'bold', 'italic', 'underline', 'strike_through', 'text_color', 'fill_color', 'align', 'vertical_align', 'wrap', 'numeric_commas', 'number_type', 'currency_symbol', 'date_time', 'font_size'],
            additionalProperties: false,
          },
        },
      },
      required: ['formats'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.SetTextFormats],
    prompt: `The set_text_formats tool sets the text formats of one or more selections of cells. Use the formats array to apply different formatting to multiple selections in a single call.\n
Each format entry requires a selection and at least one format property to set.\n
Here are the formats you can set in each entry:\n
- bold, italics, underline, or strike through\n
- text color and fill color using hex format, for example, #FF0000 for red. To remove colors, set to an empty string.\n
- horizontal alignment, this can be one of "left", "center", "right"\n
- vertical alignment, this can be one of "top", "middle", "bottom"\n
- wrapping, this can be one of "wrap", "clip", "overflow"\n
- numeric_commas, adds or removes commas from numbers\n
- number_type, this can be one of "number", "currency", "percentage", or "exponential". If "currency" is set, you MUST set the currency_symbol.\n
- currency_symbol, if number_type is "currency", use this to set the currency symbol, for example "$" for USD or "€" for EUR\n
- date_time, formats a date time value using Rust's chrono::format, e.g., "%Y-%m-%d %H:%M:%S", "%d/%m/%Y"\n
- font_size, the size of the font in points (default is 10)\n
To clear/remove a format, set the value to null (or empty string for colors). Omit fields you don't want to change.\n
Percentages in Quadratic work the same as in any spreadsheet. E.g. formatting .01 as a percentage will show as 1%. Formatting 1 as a percentage will show 100%.\n
Example: To bold A1:B5 and make C1:D5 italic with red text, use: { "formats": [{ "selection": "A1:B5", "bold": true }, { "selection": "C1:D5", "italic": true, "text_color": "#FF0000" }] }\n
You MAY want to use the get_text_formats function if you need to check the current text formats of the cells before setting them.\n`,
  },
  [AITool.CodeEditorCompletions]: {
    sources: ['CodeEditorCompletions'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool provides inline completions for the code in the code cell you are currently editing, requires the completion for the code in the code cell.\n
You are provided with the prefix and suffix of the cursor position in the code cell.\n
Completion is the delta that will be inserted at the cursor position in the code cell.\n
`,
    parameters: {
      type: 'object',
      properties: {
        text_delta_at_cursor: {
          type: 'string',
          description: 'The completion for the code in the code cell at the cursor position',
        },
      },
      required: ['text_delta_at_cursor'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.CodeEditorCompletions],
    prompt: `
This tool provides inline completions for the code in the code cell you are currently editing, you are provided with the prefix and suffix of the cursor position in the code cell.\n
You should use this tool to provide inline completions for the code in the code cell you are currently editing.\n
Completion is the delta that will be inserted at the cursor position in the code cell.\n
`,
  },
  [AITool.UserPromptSuggestions]: {
    sources: ['AIAnalyst', 'GetUserPromptSuggestions'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool provides prompt suggestions for the user, requires an array of three prompt suggestions.\n
Each prompt suggestion is an object with a label and a prompt.\n
The label is a descriptive label for the prompt suggestion with maximum 40 characters, this will be displayed to the user in the UI.\n
The prompt is the actual detailed prompt that will be executed by the AI agent to take actions on the spreadsheet.\n
Use the internal context and the chat history to provide the prompt suggestions.\n
Always maintain strong correlation between the follow up prompts and the user's chat history and the internal context.\n
IMPORTANT: This tool should always be called after you have provided the response to the user's prompt and all tool calls are finished, to provide user follow up prompts suggestions.\n
`,
    parameters: {
      type: 'object',
      properties: {
        prompt_suggestions: {
          type: 'array',
          items: {
            type: 'object',
            properties: {
              label: {
                type: 'string',
                description: 'The label of the follow up prompt, maximum 40 characters',
              },
              prompt: {
                type: 'string',
                description:
                  'Detailed prompt for the user that will be executed by the AI agent to take actions on the spreadsheet',
              },
            },
            required: ['label', 'prompt'],
            additionalProperties: false,
          },
        },
      },
      required: ['prompt_suggestions'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.UserPromptSuggestions],
    prompt: `
This tool provides prompt suggestions for the user, requires an array of three prompt suggestions.\n
Each prompt suggestion is an object with a label and a prompt.\n
The label is a descriptive label for the prompt suggestion with maximum 40 characters, this will be displayed to the user in the UI.\n
The prompt is the actual detailed prompt that will be executed by the AI agent to take actions on the spreadsheet.\n
Use the internal context and the chat history to provide the prompt suggestions.\n
Always maintain strong correlation between the prompt suggestions and the user's chat history and the internal context.\n
IMPORTANT: This tool should always be called after you have provided the response to the user's prompt and all tool calls are finished, to provide user follow up prompts suggestions.\n
`,
  },
  [AITool.EmptyChatPromptSuggestions]: {
    sources: ['GetEmptyChatPromptSuggestions'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool provides prompt suggestions for the user for an empty chat when user attaches a file or adds a connection or code cell to context, requires an array of three prompt suggestions.\n
Each prompt suggestion is an object with a label and a prompt.\n
The label is a descriptive label for the prompt suggestion with maximum 25 characters, this will be displayed to the user in the UI.\n
The prompt is the actual detailed prompt that will be executed by the AI agent to take actions on the spreadsheet.\n
Always maintain strong correlation between the context, the files, the connections and the code cells to provide the prompt suggestions.\n
`,
    parameters: {
      type: 'object',
      properties: {
        prompt_suggestions: {
          type: 'array',
          items: {
            type: 'object',
            properties: {
              label: {
                type: 'string',
                description: 'The label of the follow up prompt, maximum 25 characters',
              },
              prompt: {
                type: 'string',
                description:
                  'Detailed prompt for the user that will be executed by the AI agent to take actions on the spreadsheet. Should be in strong correlation with the context, the files, the connections and the code cells',
              },
            },
            required: ['label', 'prompt'],
            additionalProperties: false,
          },
        },
      },
      required: ['prompt_suggestions'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.EmptyChatPromptSuggestions],
    prompt: `
This tool provides prompt suggestions for the user when they attach a file or add a connection to an empty chat. It requires an array of three prompt suggestions.\n
Each prompt suggestion is an object with a label and a prompt.\n
The label is a descriptive label for the prompt suggestion with maximum 25 characters, this will be displayed to the user in the UI.\n
The prompt is the actual detailed prompt that will be executed by the AI agent to take actions on the spreadsheet.\n
Always maintain strong correlation between the context, the files, the connections and the code cells to provide the prompt suggestions.\n
`,
  },
  [AITool.PDFImport]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool extracts data from the attached PDF files and converts it into a structured format i.e. as Data Tables on the sheet.\n
This tool requires the file_name of the PDF and a clear and explicit prompt to extract data from that PDF file.\n
Forward the actual user prompt as much as possible that is related to the PDF file.\n
Always capture user intention exactly and give a clear and explicit prompt to extract data from PDF files.\n
Use this tool only if there is a PDF file that needs to be extracted. If there is no PDF file, do not use this tool.\n
Never extract data from PDF files that are not relevant to the user's prompt. Never try to extract data from PDF files on your own. Always use the pdf_import tool when dealing with PDF files.\n
Follow the user's instructions carefully and provide accurate and relevant data. If there are insufficient instructions, always ask the user for more information.\n
Do not use multiple tools at the same time when dealing with PDF files. pdf_import should be the only tool call in a reply when dealing with PDF files. Any analysis on imported data should only be done after import is successful.\n
`,
    parameters: {
      type: 'object',
      properties: {
        file_name: {
          type: 'string',
          description: 'The name of the PDF file to extract data from.',
        },
        prompt: {
          type: 'string',
          description:
            "The prompt based on the user's intention and the context of the conversation to extract data from PDF files, which will be used by the pdf_import tool to extract data from PDF files.",
        },
      },
      required: ['file_name', 'prompt'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.PDFImport],
    prompt: `
This tool extracts data from the attached PDF files and converts it into a structured format i.e. as Data Tables on the sheet.\n
This tool requires the file_name of the PDF and a clear and explicit prompt to extract data from that PDF file.\n
Forward the actual user prompt as much as possible that is related to the PDF file.\n
Always capture user intention exactly and give a clear and explicit prompt to extract data from PDF files.\n
Use this tool only if there is a PDF file that needs to be extracted. If there is no PDF file, do not use this tool.\n
Never extract data from PDF files that are not relevant to the user's prompt. Never try to extract data from PDF files on your own. Always use the pdf_import tool when dealing with PDF files.\n
Follow the user's instructions carefully and provide accurate and relevant data. If there are insufficient instructions, always ask the user for more information.\n
Do not use multiple tools at the same time when dealing with PDF files. pdf_import should be the only tool call in a reply when dealing with PDF files. Any analysis on imported data should only be done after import is successful.\n
`,
  },
  [AITool.ConvertToTable]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool converts a selection of cells on a specified sheet into a data table.\n
IMPORTANT: the selection can NOT contain any code cells or data tables.\n
It requires the sheet name, a rectangular selection of cells to convert to a data table, the name of the data table and whether the first row is the column names.\n
A data table cannot be created over any existing code cells or data tables.\n
The data table will be created with the first row as the header row if first_row_is_column_names is true, otherwise the first row will be the first row of the data.\n
`,
    parameters: {
      type: 'object',
      properties: {
        sheet_name: {
          type: 'string',
          description: 'The sheet name of the current sheet as defined in the context',
        },
        selection: {
          type: 'string',
          description:
            'The selection of cells to convert to a data table, in a1 notation. This MUST be a rectangle, like A2:D20',
        },
        table_name: {
          type: 'string',
          description:
            "The name of the data table to create, this should be a concise and descriptive name of the data table. Don't use special characters or spaces in the name. Always use a unique name for the data table. Spaces, if any, in name are replaced with underscores.",
        },
        first_row_is_column_names: {
          type: 'boolean',
          description: 'Whether the first row of the selection is the column names',
        },
      },
      required: ['sheet_name', 'selection', 'table_name', 'first_row_is_column_names'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.ConvertToTable],
    prompt: `
This tool converts a selection of cells on a specified sheet into a data table.\n
IMPORTANT: the selection can NOT contain any code cells or data tables.\n
It requires the sheet name, a rectangular selection of cells to convert to a data table, the name of the data table and whether the first row is the column names.\n
A data table cannot be created over any existing code cells or data tables.\n
The table will be created with the first row as the header row if first_row_is_column_names is true, otherwise the first row will be the first row of the data.\n
The data table will include a table name as the first row, which will push down all data by one row. Example: if the data previously occupied A1:A6, it now occupies A1:A7 since adding the table name shifted the data down by one row.\n
`,
  },
  [AITool.WebSearch]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool searches the web for information based on the query.\n
Use this tool when the user asks for information that is not already available in the context.\n
When you would otherwise try to answer from memory or not have a way to answer the user's question, use this tool to retrieve the needed data from the web.\n
This tool should also be used when trying to retrieve information for how to construct API requests that are not well-known from memory and when requiring information on code libraries that are not well-known from memory.\n
It requires the query to search for.\n
`,
    parameters: {
      type: 'object',
      properties: {
        query: {
          type: 'string',
          description: 'The search query',
        },
      },
      required: ['query'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.WebSearch],
    prompt: `
This tool searches the web for information based on the query.\n
Use this tool when the user asks for information that is not already available in the context.\n
When you would otherwise try to answer from memory or not have a way to answer the user's question, use this tool to retrieve the needed data from the web.\n
This tool should also be used when trying to retrieve information for how to construct API requests that are not well-known from memory and when requiring information on code libraries that are not well-known from memory.\n
It requires the query to search for.\n
`,
  },
  // This is tool internal to AI model and is called by `WebSearch` tool.
  [AITool.WebSearchInternal]: {
    sources: ['WebSearch'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool searches the web for information based on the query.\n
It requires the query to search for.\n
`,
    parameters: {
      type: 'object',
      properties: {
        query: {
          type: 'string',
          description: 'The search query',
        },
      },
      required: ['query'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.WebSearchInternal],
    prompt: `
This tool searches the web for information based on the query.\n
It requires the query to search for.\n
`,
  },
  [AITool.AddSheet]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool adds a new sheet in the file.\n
It requires the name of the new sheet, and an optional name of a sheet to insert the new sheet before.\n
This tool is meant to be used whenever users ask to create new sheets or ask to perform an analysis or task in a new sheet.\n
This tool should not be used to list the sheets in the file. The names of all sheets in the file are available in context.\n
`,
    parameters: {
      type: 'object',
      properties: {
        sheet_name: {
          type: 'string',
          description:
            'The new name of the sheet. This must be a unique name and cannot be more than 31 characters. It cannot contain any of the following characters: / \\ ? * : [ ].',
        },
        insert_before_sheet_name: {
          type: ['string', 'null'],
          description:
            'The name of a sheet to insert the new sheet before. If not provided, the new sheet will be added to the end of the sheet list.',
        },
      },
      required: ['sheet_name', 'insert_before_sheet_name'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.AddSheet],
    prompt: `
This tool adds a new sheet in the file.\n
It requires the name of the new sheet, and an optional name of a sheet to insert the new sheet before.\n
This tool is meant to be used whenever users ask to create new sheets or ask to perform an analysis or task in a new sheet.\n
This tool should not be used to list the sheets in the file. The names of all sheets in the file are available in context.\n
`,
  },
  [AITool.DuplicateSheet]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool duplicates a sheet in the file.\n
It requires the name of the sheet to duplicate and the name of the new sheet.\n
`,
    parameters: {
      type: 'object',
      properties: {
        sheet_name_to_duplicate: {
          type: 'string',
          description: 'The name of the sheet to duplicate.',
        },
        name_of_new_sheet: {
          type: 'string',
          description:
            'The new name of the sheet. This must be a unique name and cannot be more than 31 characters. It cannot contain any of the following characters: / \\ ? * : [ ].',
        },
      },
      required: ['sheet_name_to_duplicate', 'name_of_new_sheet'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.DuplicateSheet],
    prompt: `
This tool duplicates a sheet in the file.\n
It requires the name of the sheet to duplicate and the name of the new sheet.\n
This tool should be used primarily when users explicitly ask to create a new sheet from the existing content or ask directly to copy or duplicate a sheet.\n
`,
  },
  [AITool.RenameSheet]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool renames a sheet in the file.\n
It requires the name of the sheet to rename and the new name. This must be a unique name.\n
`,
    parameters: {
      type: 'object',
      properties: {
        sheet_name: {
          type: 'string',
          description: 'The name of the sheet to rename',
        },
        new_name: {
          type: 'string',
          description:
            'The new name of the sheet. This must be a unique name and cannot be more than 31 characters. It cannot contain any of the following characters: / \\ ? * : [ ].',
        },
      },
      required: ['sheet_name', 'new_name'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.RenameSheet],
    prompt: `
This tool renames a sheet in the file.\n
It requires the name of the sheet to rename and the new name. This must be a unique name.\n
`,
  },
  [AITool.DeleteSheet]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool deletes a sheet in the file.\n
It requires the name of the sheet to delete.\n
`,
    parameters: {
      type: 'object',
      properties: {
        sheet_name: {
          type: 'string',
          description: 'The name of the sheet to delete',
        },
      },
      required: ['sheet_name'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.DeleteSheet],
    prompt: `
This tool deletes a sheet in the file.\n
It requires the name of the sheet to delete.\n
`,
  },
  [AITool.MoveSheet]: {
    sources: ['AIAnalyst'],
    aiModelModes: [],
    description: `
This tool moves a sheet within the sheet list.\n
It requires the name of the sheet to move and an optional name of a sheet to insert the sheet before. If no sheet name is provided, the sheet will be added to the end of the sheet list.\n
`,
    parameters: {
      type: 'object',
      properties: {
        sheet_name: {
          type: 'string',
          description: 'The name of the sheet to move',
        },
        insert_before_sheet_name: {
          type: ['string', 'null'],
          description:
            'The name of a sheet to insert the moved sheet before. If not provided, the sheet will be added to the end of the sheet list.',
        },
      },
      required: ['sheet_name', 'insert_before_sheet_name'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.MoveSheet],
    prompt: `
This tool moves a sheet in the sheet list.\n
It requires the name of the sheet to move and an optional name of a sheet to insert the sheet before. If no sheet name is provided, the sheet will be added to the end of the sheet list.\n
`,
  },
  [AITool.ColorSheets]: {
    sources: ['AIAnalyst'],
    aiModelModes: [],
    description: `
This tool colors the sheet tabs in the file.\n
It requires a array of objects with sheet names and new colors.\n
`,
    parameters: {
      type: 'object',
      properties: {
        sheet_names_to_color: {
          type: 'array',
          items: {
            type: 'object',
            properties: {
              sheet_name: {
                type: 'string',
                description: 'The name of the sheet to color',
              },
              color: {
                type: 'string',
                description: 'The new color of the sheet. This must be a valid CSS color string.',
              },
            },
            required: ['sheet_name', 'color'],
            additionalProperties: false,
          },
        },
      },
      required: ['sheet_names_to_color'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.ColorSheets],
    prompt: `
This tool colors the sheet tabs in the file.\n
It requires a array of objects with sheet names and new colors.\n
`,
  },
  [AITool.TextSearch]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool searches for text in cells within a specific sheet or the entire file.\n
Use this tool when looking for a specific piece of output in the file.\n
This tool can only search for outputs that exist in cells within the file. This tool cannot search for code, only the outputs and contents in the sheet.\n
`,
    parameters: {
      type: 'object',
      properties: {
        query: {
          type: 'string',
          description: 'The query to search for',
        },
        case_sensitive: {
          type: 'boolean',
          description: 'Whether the search should be case sensitive',
        },
        whole_cell: {
          type: 'boolean',
          description:
            'Whether the search should be for the whole cell (i.e., if true, then a cell with "Hello World" would not be found with a search for "Hello"; if false, it would be).',
        },
        search_code: {
          type: 'boolean',
          description: 'Whether the search should include code within code cells',
        },
        sheet_name: {
          type: ['string', 'null'],
          description: 'The sheet name to search in. If not provided, then it searches all sheets.',
        },
      },
      required: ['query', 'case_sensitive', 'whole_cell', 'search_code', 'sheet_name'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.TextSearch],
    prompt: `
This tool searches for text in cells within a specific sheet or the entire file.\n
Use this tool when looking for a specific piece of output in the file.\n
This tool can only search for outputs that exist in cells within the file. This tool cannot search for code, only the outputs and contents in the sheet.\n
`,
  },
  [AITool.RerunCode]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool reruns the code in code cells. This may also be known as "refresh the data" or "update the data".\n
You can optionally provide a sheet name and/or a selection (in A1 notation) to rerun specific code cells.\n
If you only provide a sheet name, then all code cells within that sheet will run.\n
If you provide a selection and sheet name, then only code cells within that selection will run.\n
If you provide neither a sheet name nor a selection, then all code cells in the file will run.\n
`,
    parameters: {
      type: 'object',
      properties: {
        sheet_name: {
          type: ['string', 'null'],
          description: 'The sheet name to rerun code in. If not provided, then it reruns all code cells in the file.',
        },
        selection: {
          type: ['string', 'null'],
          description:
            'The selection (in A1 notation) of code cells to rerun. If not provided, then it reruns all code cells in the sheet. For example, A1:D100',
        },
      },
      required: ['sheet_name', 'selection'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.RerunCode],
    prompt: `
This tool reruns the code in code cells.\n
You can optionally provide a sheet name and a selection (in A1 notation) to rerun specific code cells.\n
If you only provide a sheet name, then all code cells within that sheet will run.\n
If you provide a selection and sheet name, then only code cells within that selection will run.\n
If you provide neither a sheet name nor a selection, then all code cells in the file will run.\n
`,
  },
  [AITool.ResizeColumns]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool resizes columns in a sheet.\n
It requires the sheet name, a selection (in A1 notation) of columns to resize, and the size to resize to.\n
The selection is a range of columns, for example: A1:D1.\n
The size is either "default" or "auto". Auto will resize the column to the width of the largest cell in the column. Default will resize the column to its default width.\n
Use this tool when the user specifically asks to resize columns or when the user asks to prettify the sheet.\n
`,
    parameters: {
      type: 'object',
      properties: {
        sheet_name: {
          type: 'string',
          description: 'The sheet name to resize columns in',
        },
        selection: {
          type: 'string',
          description: 'The selection (in A1 notation) of columns to resize, for example: A1:D1',
        },
        size: {
          type: 'string',
          description:
            'The size to resize the columns to. Either "default" or "auto". Auto will resize the column to the width of the largest cell in the column. Default will resize the column to its default width.',
        },
      },
      required: ['sheet_name', 'selection', 'size'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.ResizeColumns],
    prompt: `
This tool resizes columns in a sheet.\n
It requires the sheet name, a selection (in A1 notation) of columns to resize, and the size to resize to.\n
The selection is a range of columns, for example: A1:D1.\n
The size is either "default" or "auto". Auto will resize the column to the width of the largest cell in the column. Default will resize the column to its default width.\n
Use this tool when the user specifically asks to resize columns or when the user asks to prettify the sheet.\n
`,
  },
  [AITool.ResizeRows]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool resizes rows in a sheet.\n
It requires the sheet name, a selection (in A1 notation) of rows to resize, and the size to resize to.\n
The selection is a range of rows, for example: A1:A100.\n
The size is either "default" or "auto". Auto will resize the row to the height of the largest cell in the row. Default will resize the row to its default height.\n
Use this tool when the user specifically asks to resize rows.\n
`,
    parameters: {
      type: 'object',
      properties: {
        sheet_name: {
          type: 'string',
          description: 'The sheet name to resize rows in',
        },
        selection: {
          type: 'string',
          description: 'The selection (in A1 notation) of rows to resize, for example: A1:A100',
        },
        size: {
          type: 'string',
          description:
            'The size to resize the rows to. Either "default" or "auto". Auto will resize the row to the height of the largest cell in the row. Default will resize the row to its default height.',
        },
      },
      required: ['sheet_name', 'selection', 'size'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.ResizeRows],
    prompt: `
This tool resizes rows in a sheet.\n
It requires the sheet name, a selection (in A1 notation) of rows to resize, and the size to resize to.\n
The selection is a range of rows in A1 notation, for example: A1:A100.\n
The size is either "default" or "auto". Auto will resize the row to the height of the largest cell in the row. Default will resize the row to its default height.\n
Use this tool when the user specifically asks to resize rows.\n
`,
  },
  [AITool.SetBorders]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool sets the borders in a sheet.\n
It requires the sheet name, a selection (in A1 notation) of cells to set the borders on, and the color, line type, and border_selection of the borders.\n
`,
    parameters: {
      type: 'object',
      properties: {
        sheet_name: {
          type: 'string',
          description: 'The sheet name to set borders in',
        },
        selection: {
          type: 'string',
          description:
            'The selection (in A1 notation) of cells to set borders on. For example: A1:D1. For border_selection like "Outer", it will draw borders around the outside of the selection box.',
        },
        color: {
          type: 'string',
          description: 'The color of the borders. This must be a valid CSS color string.',
        },
        line: {
          type: 'string',
          description: `
This provides the line type of the borders.\n
It must be one of the following: line1, line2, line3, dotted, dashed, double, clear.\n
"line1" is a thin line.\n
"line2" is a thicker line.\n
"line3" is the thickest line.\n
"dotted" is a dotted line.\n
"dashed" is a dashed line.\n
"double" is a doubled line.\n
"clear" will remove all borders in selection.`,
        },
        border_selection: {
          type: 'string',
          description: `
The border selection to set the borders on. This must be one of the following: all, inner, outer, horizontal, vertical, left, top, right, bottom, clear.\n
"all" will set borders on all cells in the selection.\n
"inner" will set borders on the inside of the selection box.\n
"outer" will set borders on the outside of the selection box.\n
"horizontal" will set borders on the horizontal sides of the selection box.\n
"vertical" will set borders on the vertical sides of the selection box.\n
"left" will set borders on the left side of the selection box.\n
"top" will set borders on the top side of the selection box.\n
"right" will set borders on the right side of the selection box.\n
"bottom" will set borders on the bottom side of the selection box.\n
"clear" will remove all borders in selection.`,
        },
      },
      required: ['sheet_name', 'selection', 'color', 'line', 'border_selection'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.SetBorders],
    prompt: `
This tool sets the borders in a sheet.\n
It requires the sheet name, a selection (in A1 notation) of cells to set the borders on, and the color, line type, and border_selection of the borders.\n
The selection is a range of cells, for example: A1:D1.\n
The color must be a valid CSS color string.\n
The line type must be one of: line1, line2, line3, dotted, dashed, double, clear.\n
The border_selection must be one of: all, inner, outer, horizontal, vertical, left, top, right, bottom, clear.\n
`,
  },
  [AITool.InsertColumns]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool inserts columns in a sheet, adjusted columns to the right of the insertion. The new columns will share the formatting of the column provided.\n
It requires the sheet name, the column to insert the columns at, whether to insert to the right or left of the column, and the number of columns to insert.\n
`,
    parameters: {
      type: 'object',
      properties: {
        sheet_name: {
          type: 'string',
          description: 'The sheet name to insert columns in',
        },
        column: {
          type: 'string',
          description:
            'The column to insert the columns at. This must be a valid column name, for example A or ZA. The new columns will share the formatting of this column.',
        },
        right: {
          type: 'boolean',
          description:
            'Whether to insert to the right or left of the column. If true, insert to the right of the column. If false, insert to the left of the column.',
        },
        count: {
          type: 'number',
          description: 'The number of columns to insert',
        },
      },
      required: ['sheet_name', 'column', 'right', 'count'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.InsertColumns],
    prompt: `
This tool inserts columns in a sheet, adjusted columns to the right of the insertion.\n
It requires the sheet name, the column to insert the columns at, whether to insert to the right or left of the column, and the number of columns to insert.\n`,
  },
  [AITool.InsertRows]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool inserts rows in a sheet, adjusted rows below the insertion.\n
It requires the sheet name, the row to insert the rows at, whether to insert below or above the row, and the number of rows to insert. The new rows will share the formatting of the row provided.\n
`,
    parameters: {
      type: 'object',
      properties: {
        sheet_name: {
          type: 'string',
          description: 'The sheet name to insert rows in',
        },
        row: {
          type: 'number',
          description:
            'The row to insert the rows at. This should be a number, for example 1, 2, 35, etc. The new rows will share the formatting of this row.',
        },
        below: {
          type: 'boolean',
          description:
            'Whether to insert below or above the row. If true, insert below the row. If false, insert above the row.',
        },
        count: {
          type: 'number',
          description: 'The number of rows to insert',
        },
      },
      required: ['sheet_name', 'row', 'below', 'count'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.InsertRows],
    prompt: `
This tool inserts rows in a sheet, adjusted rows below the insertion.\n
It requires the sheet name, the row to insert the rows at, whether to insert below or above the row, and the number of rows to insert. The new rows will share the formatting of the row provided.\n`,
  },
  [AITool.DeleteColumns]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool deletes columns in a sheet, adjusting columns to the right of the deletion.\n
It requires the sheet name and an array of sheet columns to delete.\n
`,
    parameters: {
      type: 'object',
      properties: {
        sheet_name: {
          type: 'string',
          description: 'The sheet name to delete columns in',
        },
        columns: {
          type: 'array',
          items: {
            type: 'string',
            description: 'The column to delete. This must be a valid column name, for example "A" or "ZB".',
          },
        },
      },
      required: ['sheet_name', 'columns'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.DeleteColumns],
    prompt: `
This tool deletes columns in a sheet, adjusting columns to the right of the deletion.\n
It requires the sheet name and an array of sheet columns to delete.\n`,
  },
  [AITool.DeleteRows]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool deletes rows in a sheet, adjusting rows below the deletion.\n
It requires the sheet name and an array of sheet rows to delete.\n
`,
    parameters: {
      type: 'object',
      properties: {
        sheet_name: {
          type: 'string',
          description: 'The sheet name to delete rows in',
        },
        rows: {
          type: 'array',
          items: {
            type: 'number',
            description: 'The row to delete. This must be a number, for example 1, 2, 35, etc.',
          },
        },
      },
      required: ['sheet_name', 'rows'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.DeleteRows],
    prompt: `
This tool deletes rows in a sheet, adjusting rows below the deletion.\n
It requires the sheet name and an array of sheet rows to delete.\n`,
  },
  [AITool.TableMeta]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool sets the meta data for a table. One or more options can be changed on the table at once.\n
`,
    parameters: {
      type: 'object',
      properties: {
        sheet_name: {
          type: 'string',
          description: 'The sheet name that contains the table',
        },
        table_location: {
          type: 'string',
          description: 'The anchor location of the table (ie, the top-left cell of the table). For example: A5',
        },
        new_table_name: {
          type: ['string', 'null'],
          description: 'The optional new name of the table.',
        },
        first_row_is_column_names: {
          type: ['boolean', 'null'],
          description:
            'The optional boolean as to whether the first row of the table contains the column names. If set to true, the first row will be used as the column names for the table. If set to false, default column names will be used instead.',
        },
        show_name: {
          type: ['boolean', 'null'],
          description:
            'The optional boolean that toggles whether the table name is shown for the table. This is true by default. If true, then the top row of the table only contains the table name.',
        },
        show_columns: {
          type: ['boolean', 'null'],
          description:
            'The optional boolean that toggles whether the column names are shown for the table. This is true by default. If true, then the first row of the table contains the column names.',
        },
        alternating_row_colors: {
          type: ['boolean', 'null'],
          description:
            'The optional boolean that toggles whether the table has alternating row colors. This is true by default. If true, then the table will have alternating row colors.',
        },
      },
      required: [
        'sheet_name',
        'table_location',
        'new_table_name',
        'first_row_is_column_names',
        'show_name',
        'show_columns',
        'alternating_row_colors',
      ],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.TableMeta],
    prompt: `
This tool sets the meta data for a table. One or more options can be changed on the table at once.\n
`,
  },
  [AITool.TableColumnSettings]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool changes the columns of a table. It can rename them or show or hide them.\n
Use the delete_cells tool to delete columns by providing it with the column name. For example, "TableName[Column Name]". Don't hide the column unless the user requests it.
In the parameters, include only columns that you want to change. The remaining columns will remain the same.\n`,
    parameters: {
      type: 'object',
      properties: {
        sheet_name: {
          type: 'string',
          description: 'The sheet name that contains the table',
        },
        table_location: {
          type: 'string',
          description: 'The anchor location of the table (ie, the top-left cell of the table). For example: A5',
        },
        column_names: {
          type: 'array',
          items: {
            type: 'object',
            properties: {
              old_name: {
                type: 'string',
                description: 'The old name of the column',
              },
              new_name: {
                type: 'string',
                description:
                  'The new name of the column. If the new name is the same as the old name, the column will not be renamed.',
              },
              show: {
                type: 'boolean',
                description: 'Whether the column is shown in the table. This is true by default.',
              },
            },
            required: ['old_name', 'new_name', 'show'],
            additionalProperties: false,
          },
        },
      },
      required: ['sheet_name', 'table_location', 'column_names'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.TableColumnSettings],
    prompt: `
This tool changes the columns of a table. It can rename them or show or hide them.\n
Use the delete_cells tool to delete columns by providing it with the column name. For example, "TableName[Column Name]". Don't hide the column unless the user requests it.
In the parameters, include only columns that you want to change. The remaining columns will remain the same.\n`,
  },

  [AITool.GetValidations]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool gets the validations in a sheet.\n
It requires the sheet name.\n
`,
    parameters: {
      type: 'object',
      properties: {
        sheet_name: {
          type: 'string',
          description: 'The sheet name to get the validations in',
        },
      },
      required: ['sheet_name'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.GetValidations],
    prompt: `
This tool gets the validations in a sheet.\n
It requires the sheet name.\n
`,
  },
  [AITool.AddMessage]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool adds a message to a sheet using validations.\n`,
    parameters: {
      type: 'object',
      properties: {
        sheet_name: {
          type: 'string',
          description: 'The sheet name to add the message to',
        },
        selection: {
          type: 'string',
          description:
            'The selection of cells to add the message to. This must be in A1 notation, for example: A1:D1 or TableName[Column 1]',
        },
        message_title: {
          type: 'string',
          description: 'The title of the message to add',
        },
        message_text: {
          type: 'string',
          description: 'The text of the message to add',
        },
      },
      required: ['sheet_name', 'selection', 'message_title', 'message_text'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.AddMessage],
    prompt: `
This tool adds a message to a sheet using validations.\n`,
  },
  [AITool.AddLogicalValidation]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool adds a logical validation to a sheet. This also can display a checkbox in a cell to allow the user to toggle the cell between true and false.\n`,
    parameters: {
      type: 'object',
      properties: {
        sheet_name: {
          type: 'string',
          description: 'The sheet name to add the logical validation to',
        },
        selection: {
          type: 'string',
          description:
            'The selection of cells to add the logical validation to. This must be in A1 notation, for example: A1:D1 or TableName[Column 1]',
        },
        show_checkbox: {
          type: ['boolean', 'null'],
          description:
            'Whether to show a checkbox in the cell to allow the user to toggle the cell between true and false. This defaults to false.',
        },
        ignore_blank: {
          type: ['boolean', 'null'],
          description: 'Whether to ignore blank cells when validating. This defaults to true.',
        },
        ...validationMessageErrorPrompt,
      },
      required: [
        'sheet_name',
        'selection',
        'show_checkbox',
        'ignore_blank',
        ...Object.keys(validationMessageErrorPrompt),
      ],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.AddLogicalValidation],
    prompt: `
This tool adds a logical validation to a sheet. This also can display a checkbox in a cell to allow the user to toggle the cell between true and false.\n`,
  },
  [AITool.AddListValidation]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool adds a list validation to a sheet. This can be used to limit the values that can be entered into a cell to a list of values.\n
The list should have either a list_source_list or a list_source_selection, but not both.\n`,
    parameters: {
      type: 'object',
      properties: {
        sheet_name: {
          type: 'string',
          description: 'The sheet name to add the list validation to',
        },
        selection: {
          type: 'string',
          description:
            'The selection of cells to add the list validation to. This must be in A1 notation, for example: A1:D1 or TableName[Column 1]',
        },
        ignore_blank: {
          type: ['boolean', 'null'],
          description: 'Whether to ignore blank cells when validating. This defaults to true.',
        },
        drop_down: {
          type: 'boolean',
          description: 'Whether to show a drop down list of values in the cell. This defaults to false.',
        },
        list_source_list: {
          type: ['string', 'null'],
          description:
            'The value to add to the list validation. The items should be in a list format separated by commas, for example: "Item 1, Item 2, Item 3". This defaults to null.',
        },
        list_source_selection: {
          type: ['string', 'null'],
          description:
            'The selection of cells to add to the list validation. This must be in A1 notation, for example: A1:D1 or TableName[Column 1]. This defaults to null.',
        },
        ...validationMessageErrorPrompt,
      },
      required: [
        'sheet_name',
        'selection',
        'ignore_blank',
        'drop_down',
        'list_source_list',
        'list_source_selection',
        ...Object.keys(validationMessageErrorPrompt),
      ],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.AddListValidation],
    prompt: `
This tool adds a text validation to a sheet. This can be used to limit the values that can be entered into a cell to text rules.\n`,
  },
  [AITool.AddTextValidation]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool adds a text validation to a sheet. This validates a text string to ensure it meets certain criteria.\n`,
    parameters: {
      type: 'object',
      properties: {
        sheet_name: {
          type: 'string',
          description: 'The sheet name to add the text validation to',
        },
        selection: {
          type: 'string',
          description:
            'The selection of cells to add the text validation to. This must be in A1 notation, for example: A1:D1 or TableName[Column 1]',
        },
        ignore_blank: {
          type: ['boolean', 'null'],
          description: 'Whether to ignore blank cells when validating. This defaults to true.',
        },
        max_length: {
          type: ['number', 'null'],
          description: 'The maximum length of the text. This defaults to null.',
        },
        min_length: {
          type: ['number', 'null'],
          description: 'The minimum length of the text. This defaults to null.',
        },
        contains_case_sensitive: {
          type: ['string', 'null'],
          description:
            'The text to check if the cell contains it. This can be text or items separated by commas. The list is case sensitive. This defaults to null.',
        },
        contains_case_insensitive: {
          type: ['string', 'null'],
          description:
            'The text to check if the cell contains it. This can be text or items separated by commas. The list is case insensitive. This defaults to null.',
        },
        not_contains_case_sensitive: {
          type: ['string', 'null'],
          description:
            'The text to check if the cell does not contain it. This can be text or items separated by commas. The list is case sensitive. This defaults to null.',
        },
        not_contains_case_insensitive: {
          type: ['string', 'null'],
          description:
            'The text to check if the cell does not contain it. This can be text or items separated by commas. The list is case insensitive. This defaults to null.',
        },
        exactly_case_sensitive: {
          type: ['string', 'null'],
          description:
            'The text to check if the cell exactly matches it. This can be text or items separated by commas. The list is case sensitive. This defaults to null.',
        },
        exactly_case_insensitive: {
          type: ['string', 'null'],
          description:
            'The text to check if the cell exactly matches it. This can be text or items separated by commas. The list is case insensitive. This defaults to null.',
        },
        ...validationMessageErrorPrompt,
      },
      required: [
        'sheet_name',
        'selection',
        'ignore_blank',
        'max_length',
        'min_length',
        'contains_case_sensitive',
        'contains_case_insensitive',
        'not_contains_case_sensitive',
        'not_contains_case_insensitive',
        'exactly_case_sensitive',
        'exactly_case_insensitive',
        ...Object.keys(validationMessageErrorPrompt),
      ],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.AddTextValidation],
    prompt: `
This tool adds a text validation to a sheet. This validates a text string to ensure it meets certain criteria.\n`,
  },
  [AITool.AddNumberValidation]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool adds a number validation to a sheet. This validates a number to ensure it meets certain criteria.\n`,
    parameters: {
      type: 'object',
      properties: {
        sheet_name: {
          type: 'string',
          description: 'The sheet name to add the number validation to',
        },
        selection: {
          type: 'string',
          description:
            'The selection of cells to add the number validation to. This must be in A1 notation, for example: A1:D1 or TableName[Column 1]',
        },
        ignore_blank: {
          type: ['boolean', 'null'],
          description: 'Whether to ignore blank cells when validating. This defaults to true.',
        },
        range: {
          type: ['string', 'null'],
          description:
            'A list of ranges of numbers. For example: "5..10,2..20,30..,..2". Each range is separated by a comma and must contain "..". You can leave the start or end blank to indicate no minimum or maximum. This defaults to null.',
        },
        equal: {
          type: ['string', 'null'],
          description:
            'A list of numbers that the cell must be equal to. This must be a list of numbers separated by commas. This defaults to null.',
        },
        not_equal: {
          type: ['string', 'null'],
          description:
            'A list of numbers that the cell must not be equal to. This must be a list of numbers separated by commas. This defaults to null.',
        },
        ...validationMessageErrorPrompt,
      },
      required: [
        'sheet_name',
        'selection',
        'ignore_blank',
        'range',
        'equal',
        'not_equal',
        ...Object.keys(validationMessageErrorPrompt),
      ],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.AddNumberValidation],
    prompt: `
This tool adds a number validation to a sheet. This validates a number to ensure it meets certain criteria.\n`,
  },
  [AITool.AddDateTimeValidation]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool adds a date time validation to a sheet. This validates a date time to ensure it meets certain criteria.\n`,
    parameters: {
      type: 'object',
      properties: {
        sheet_name: {
          type: 'string',
          description: 'The sheet name to add the date time validation to',
        },
        selection: {
          type: 'string',
          description:
            'The selection of cells to add the date time validation to. This must be in A1 notation, for example: A1:D1 or TableName[Column 1]',
        },
        ignore_blank: {
          type: ['boolean', 'null'],
          description: 'Whether to ignore blank cells when validating. This defaults to true.',
        },
        require_date: {
          type: ['boolean', 'null'],
          description: 'Whether the cell must be a date. This defaults to false.',
        },
        require_time: {
          type: ['boolean', 'null'],
          description: 'Whether the cell must be a time. This defaults to false.',
        },
        prohibit_date: {
          type: ['boolean', 'null'],
          description: 'Whether the cell must not be a date. This defaults to false.',
        },
        prohibit_time: {
          type: ['boolean', 'null'],
          description: 'Whether the cell must not be a time. This defaults to false.',
        },
        date_range: {
          type: ['string', 'null'],
          description:
            'A list of ranges of dates. Use YYYY/MM/DD or YYYY-MM-DD HH:MM:SS. For example: "2025/01/01..2025/01/31,2025/02/01 11:10:10..2025/02/28 05:00:00,2025/12/31 13:12:11..,..2025/02/01". Use ".." to create a range. You can leave the start or end blank to indicate no minimum or maximum. This defaults to null.',
        },
        time_range: {
          type: ['string', 'null'],
          description:
            'A list of ranges of times. For example: "10:00..12:00,14:00..16:00,18:00..,..10:00". Use ".." to create a range. You can leave the start or end blank to indicate no minimum or maximum. This defaults to null.',
        },
        date_equal: {
          type: ['string', 'null'],
          description:
            'A list of dates that the cell must be equal to. Use YYYY/MM/DD or YYYY-MM-DD HH:MM:SS. This must be a list of dates separated by commas. This defaults to null.',
        },
        date_not_equal: {
          type: ['string', 'null'],
          description:
            'A list of dates that the cell must not be equal to. Use YYYY/MM/DD or YYYY-MM-DD HH:MM:SS. This must be a list of dates separated by commas. This defaults to null.',
        },
        time_equal: {
          type: ['string', 'null'],
          description:
            'A list of times that the cell must be equal to. Use HH:MM:SS. This must be a list of times separated by commas. This defaults to null.',
        },
        time_not_equal: {
          type: ['string', 'null'],
          description:
            'A list of times that the cell must not be equal to. Use HH:MM:SS. This must be a list of times separated by commas. This defaults to null.',
        },
        ...validationMessageErrorPrompt,
      },
      required: [
        'sheet_name',
        'selection',
        'ignore_blank',
        'require_date',
        'require_time',
        'prohibit_date',
        'prohibit_time',
        'date_range',
        'time_range',
        'date_equal',
        'date_not_equal',
        'time_equal',
        'time_not_equal',
        ...Object.keys(validationMessageErrorPrompt),
      ],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.AddDateTimeValidation],
    prompt: `
This tool adds a date time validation to a sheet. This validates a date time to ensure it meets certain criteria.\n`,
  },
  [AITool.RemoveValidations]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool removes all validations in a sheet from a range.\n`,
    parameters: {
      type: 'object',
      properties: {
        sheet_name: {
          type: 'string',
          description: 'The sheet name to remove the validations from',
        },
        selection: {
          type: 'string',
          description:
            'The selection of cells to remove the validations from. This must be in A1 notation, for example: A1:D1 or TableName[Column 1]. All validations in this range will be removed.',
        },
      },
      required: ['sheet_name', 'selection'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.RemoveValidations],
    prompt: `
This tool removes all validations in a sheet from a range.\n`,
  },
  [AITool.Undo]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool undoes the last action. You MUST use the aiUpdates context to understand the relevant actions and the count of actions to undo.\n
Always pass in the count of actions to undo when using the undo tool, even if the count to undo is 1.\n
If the user's undo request is multiple transactions in the past, use the count parameter to pass the number of transactions to undo.\n`,
    parameters: {
      type: 'object',
      properties: {
        count: {
          type: 'number',
          description:
            'The number of transactions to undo. Should be a number and at least 1 (which only performs an undo on the last transaction)',
        },
      },
      required: ['count'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.Undo],
    prompt: `
This tool undoes the last action. You MUST use the aiUpdates context to understand the last action and what is undoable.\n
Always pass in the count of actions to undo when using the undo tool, even if the count to undo is 1.\n
If the user's undo request is multiple transactions in the past, use the count parameter to pass the number of transactions to undo.\n`,
  },
  [AITool.Redo]: {
    sources: ['AIAnalyst'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool redoes the last action. You MUST use the aiUpdates context to understand the relevant actions and the count of actions to redo.\n
Always pass in the count of actions to redo when using the redo tool, even if the count to redo is 1.\n
If the user's redo request is multiple transactions, use the count parameter to pass the number of transactions to redo.\n`,
    parameters: {
      type: 'object',
      properties: {
        count: {
          type: 'number',
          description:
            'The number of transactions to redo. Should be a number and at least 1 (which only performs an redo on the last transaction). Can only redo after the same number of undos have been performed.',
        },
      },
      required: ['count'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.Redo],
    prompt: `
This tool redoes the last action. You MUST use the aiUpdates context to understand the relevant actions and the count of actions to redo.\n
Always pass in the count of actions to redo when using the redo tool, even if the count to redo is 1.\n
If the user's redo request is multiple transactions, use the count parameter to pass the number of transactions to redo.\n`,
  },
  [AITool.ContactUs]: {
    sources: ['AIAnalyst', 'AIAssistant'],
    aiModelModes: ['disabled', 'fast', 'max', 'others'],
    description: `
This tool provides a way for users to get help from the Quadratic team when experiencing frustration or issues.\n
Use this tool when the user expresses high levels of frustration, uses cursing or degrading language, or explicitly asks to speak with the team.\n
The tool displays a contact form with options to reach out to the team or start a new chat.\n`,
    parameters: {
      type: 'object',
      properties: {
        acknowledged: {
          type: ['boolean', 'null'],
          description: 'Acknowledgment flag (can be null or boolean)',
        },
      },
      required: ['acknowledged'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.ContactUs],
    prompt: `
This tool provides a way for users to get help from the Quadratic team when they are experiencing frustration or issues.\n
Use this tool when the user expresses high levels of frustration, uses cursing or degrading language, or explicitly asks to speak with the team.\n
This should be used to help frustrated users get direct support from the Quadratic team.\n
The tool displays "Get help from our team" as the title, "Provide your feedback and we'll get in touch soon." as the description,\n
and includes a recommendation message: "Contact us or consider starting a new chat to give the AI a fresh start."\n
It provides both a "Contact us" button and a "New chat" button for the user.\n`,
  },
  [AITool.OptimizePrompt]: {
    sources: ['OptimizePrompt'],
    aiModelModes: ['disabled', 'fast', 'max'],
    description: `
This tool restructures a user's prompt into clear, step-by-step bulleted instructions.\n
The output MUST be a bulleted list with specific sections covering the task, output creation, and any other relevant details.\n
Use the spreadsheet context to make instructions specific and actionable.\n`,
    parameters: {
      type: 'object',
      properties: {
        optimized_prompt: {
          type: 'string',
          description: 'The restructured prompt as a bulleted list with clear step-by-step instructions',
        },
      },
      required: ['optimized_prompt'],
      additionalProperties: false,
    },
    responseSchema: AIToolsArgsSchema[AITool.OptimizePrompt],
    prompt: `
This tool restructures a user's prompt into clear, step-by-step bulleted instructions.\n
You have access to the full spreadsheet context, including all sheets, tables, data locations, and existing content. Use this information to make the instructions specific.\n

REQUIRED OUTPUT FORMAT - a bulleted list with these sections:\n

- Task: [Detailed description of what analysis/calculation to perform, specifying exactly what data to analyze from which table/sheet. Be specific about what aspects of the data to examine.]\n
- Create: [Specify what output format to generate - code for metrics summaries, charts, tables, etc. If the user doesn't clearly define the output format, make a recommendation like "metrics summaries and relevant charts" based on the task.]\n
- [Any other relevant details like placement location, specific requirements, or constraints]\n

Rules for creating the output:\n
1. Always start with "- Task:" describing WHAT to analyze and WHERE the data is (use actual table/sheet names from context)\n
2. Always include "- Create:" describing the output format (metrics, charts, tables, code, etc.)\n
3. Be specific about the analysis details - don't just say "analyze data", say WHAT aspects to analyze\n
4. If the user doesn't specify output format, recommend appropriate formats (metrics, charts, summaries)\n
5. Add any other relevant bullet points for placement, constraints, or special requirements\n
6. Use actual table names and sheet names from the context when available\n
7. Default placement to "an open location right of existing data" if not specified\n
8. IMPORTANT: Use plain text only - NO markdown formatting like **bold**, *italics*, or any other formatting. Just use dashes and plain text.\n

Example transformations:\n

Original: "graph my sales"\n
Context: Sales_Data table exists with columns: date, revenue, region\n
Optimized:\n
- Task: Analyze sales trends over time using the Sales_Data table, examining revenue patterns across different dates and regions\n
- Create: Generate a line chart showing revenue trends, with additional summary metrics for total and average sales\n
- Place results in an open location right of existing data\n

Original: "analyze customer data"\n
Context: Customers table with columns: age, purchase_count, total_spent\n
Optimized:\n
- Task: Analyze customer demographics and purchase behavior using the Customers table, examining relationships between age, purchase frequency, and spending patterns\n
- Create: Generate summary metrics (average age, total purchases, spending distribution) and create charts showing customer segmentation and purchase trends\n
- Place results in an open location right of existing data\n

Original: "calculate totals for revenue"\n
Context: Revenue column in Sheet1\n
Optimized:\n
- Task: Calculate sum totals for the Revenue column in Sheet1\n
- Create: Display the total as a single cell value with a label\n
- Place the result directly below the Revenue column\n

Be specific, detailed, and actionable in every bullet point.\n`,
  },
} as const;
```

### quadratic-api/src/ai/helpers/anthropic.helper.ts

- **Purpose**: Provider adapter (Anthropic) — glue prompt после tool results
- **GitHub**: https://github.com/quadratichq/quadratic/blob/main/quadratic-api/src/ai/helpers/anthropic.helper.ts
- **Raw**: https://raw.githubusercontent.com/quadratichq/quadratic/main/quadratic-api/src/ai/helpers/anthropic.helper.ts

```ts
import type Anthropic from '@anthropic-ai/sdk';
import type {
  ContentBlockParam,
  DocumentBlockParam,
  ImageBlockParam,
  MessageParam,
  TextBlockParam,
  Tool,
  ToolChoice,
} from '@anthropic-ai/sdk/resources';
import type { Stream } from '@anthropic-ai/sdk/streaming';
import type { Response } from 'express';
import {
  createTextContent,
  getSystemPromptMessages,
  isAIPromptMessage,
  isContentImage,
  isContentPdfFile,
  isContentText,
  isContentTextFile,
  isInternalMessage,
  isToolResultMessage,
} from 'quadratic-shared/ai/helpers/message.helper';
import type { AITool } from 'quadratic-shared/ai/specs/aiToolsSpec';
import type { ApiTypes } from 'quadratic-shared/typesAndSchemas';
import type {
  AIRequestHelperArgs,
  AISource,
  AIUsage,
  AnthropicModelKey,
  BedrockAnthropicModelKey,
  Content,
  ModelMode,
  ParsedAIResponse,
  ToolResultContent,
  VertexAIAnthropicModelKey,
} from 'quadratic-shared/typesAndSchemasAI';
import { getAIToolsInOrder } from './tools';

function convertContent(content: Content): Array<ContentBlockParam> {
  return content
    .filter((content) => !('text' in content) || !!content.text.trim())
    .map((content) => {
      if (isContentImage(content)) {
        const imageBlockParam: ImageBlockParam = {
          type: 'image' as const,
          source: {
            data: content.data,
            media_type: content.mimeType,
            type: 'base64' as const,
          },
        };
        return imageBlockParam;
      } else if (isContentPdfFile(content)) {
        const documentBlockParam: DocumentBlockParam = {
          type: 'document' as const,
          source: {
            data: content.data,
            media_type: content.mimeType,
            type: 'base64' as const,
          },
          title: content.fileName,
        };
        return documentBlockParam;
      } else if (isContentTextFile(content)) {
        const documentBlockParam: DocumentBlockParam = {
          type: 'document' as const,
          source: {
            data: content.data,
            media_type: content.mimeType,
            type: 'text' as const,
          },
          title: content.fileName,
        };
        return documentBlockParam;
      } else if (isContentText(content)) {
        const textBlockParam: TextBlockParam = createTextContent(content.text.trim());
        return textBlockParam;
      } else {
        return undefined;
      }
    })
    .filter((content) => content !== undefined);
}

function convertToolResultContent(content: ToolResultContent): Array<TextBlockParam | ImageBlockParam> {
  return content
    .filter((content) => !('text' in content) || !!content.text.trim())
    .map((content) => {
      if (isContentImage(content)) {
        const imageBlockParam: ImageBlockParam = {
          type: 'image' as const,
          source: {
            data: content.data,
            media_type: content.mimeType,
            type: 'base64' as const,
          },
        };
        return imageBlockParam;
      } else {
        const textBlockParam: TextBlockParam = createTextContent(content.text.trim());
        return textBlockParam;
      }
    });
}

export function getAnthropicApiArgs(
  args: AIRequestHelperArgs,
  aiModelMode: ModelMode,
  promptCaching: boolean,
  thinking: boolean | undefined
): {
  system: TextBlockParam[] | undefined;
  messages: MessageParam[];
  tools: Tool[] | undefined;
  tool_choice: ToolChoice | undefined;
} {
  const { messages: chatMessages, toolName, source } = args;

  const { systemMessages, promptMessages } = getSystemPromptMessages(chatMessages);

  let cacheRemaining = promptCaching ? 4 : 0;
  const system: TextBlockParam[] = systemMessages.map((message) => ({
    type: 'text' as const,
    text: message.trim(),
    ...(cacheRemaining-- > 0 ? { cache_control: { type: 'ephemeral' } } : {}),
  }));

  const messages: MessageParam[] = promptMessages.reduce<MessageParam[]>((acc, message) => {
    if (isInternalMessage(message)) {
      return acc;
    } else if (isAIPromptMessage(message)) {
      const anthropicMessage: MessageParam = {
        role: message.role,
        content: [
          ...message.content
            .filter(
              (content) =>
                !!content.text.trim() &&
                (content.type !== 'anthropic_thinking' || !!content.signature) &&
                (!!thinking || isContentText(content))
            )
            .map((content) => {
              switch (content.type) {
                case 'anthropic_thinking':
                  return {
                    type: 'thinking' as const,
                    thinking: content.text,
                    signature: content.signature,
                  };
                case 'anthropic_redacted_thinking':
                  return {
                    type: 'redacted_thinking' as const,
                    data: content.text,
                  };
                default:
                  return createTextContent(content.text.trim());
              }
            }),
          ...message.toolCalls.map((toolCall) => ({
            type: 'tool_use' as const,
            id: toolCall.id,
            name: toolCall.name,
            input: toolCall.arguments ? JSON.parse(toolCall.arguments) : {},
          })),
        ],
      };
      return [...acc, anthropicMessage];
    } else if (isToolResultMessage(message)) {
      const anthropicMessages: MessageParam = {
        role: message.role,
        content: [
          ...message.content.map((toolResult) => ({
            type: 'tool_result' as const,
            tool_use_id: toolResult.id,
            content: convertToolResultContent(toolResult.content),
          })),
          createTextContent('Given the above tool calls results, continue with your response.'),
        ],
      };
      return [...acc, anthropicMessages];
    } else if (message.content.length) {
      const anthropicMessage: MessageParam = {
        role: message.role,
        content: convertContent(message.content),
      };
      return [...acc, anthropicMessage];
    } else {
      return acc;
    }
  }, []);

  const tools = getAnthropicTools(source, aiModelMode, toolName);
  const tool_choice = tools?.length ? getAnthropicToolChoice(toolName) : undefined;

  return { system, messages, tools, tool_choice };
}

function getAnthropicTools(source: AISource, aiModelMode: ModelMode, toolName?: AITool): Tool[] | undefined {
  const tools = getAIToolsInOrder().filter(([name, toolSpec]) => {
    if (!toolSpec.aiModelModes.includes(aiModelMode)) {
      return false;
    }
    if (toolName === undefined) {
      return toolSpec.sources.includes(source);
    }
    return name === toolName;
  });

  if (tools.length === 0) {
    return undefined;
  }

  const anthropicTools: Tool[] = tools.map(
    ([name, { description, parameters: input_schema }]): Tool => ({
      name,
      description,
      input_schema,
    })
  );

  return anthropicTools;
}

function getAnthropicToolChoice(toolName?: AITool): ToolChoice {
  return toolName === undefined ? { type: 'auto', disable_parallel_tool_use: true } : { type: 'tool', name: toolName };
}

export async function parseAnthropicStream(
  chunks: Stream<Anthropic.Messages.RawMessageStreamEvent>,
  modelKey: VertexAIAnthropicModelKey | BedrockAnthropicModelKey | AnthropicModelKey,
  isOnPaidPlan: boolean,
  exceededBillingLimit: boolean,
  response?: Response
): Promise<ParsedAIResponse> {
  const responseMessage: ApiTypes['/v0/ai/chat.POST.response'] = {
    role: 'assistant',
    content: [],
    contextType: 'userPrompt',
    toolCalls: [],
    modelKey,
    isOnPaidPlan,
    exceededBillingLimit,
  };

  response?.write(`data: ${JSON.stringify(responseMessage)}\n\n`);

  const usage: AIUsage = {
    inputTokens: 0,
    outputTokens: 0,
    cacheReadTokens: 0,
    cacheWriteTokens: 0,
  };

  for await (const chunk of chunks) {
    if (!response?.writableEnded) {
      switch (chunk.type) {
        case 'content_block_start':
          if (chunk.content_block.type === 'text') {
            if (chunk.content_block.text.trim()) {
              responseMessage.content.push(createTextContent(chunk.content_block.text));
              responseMessage.toolCalls.forEach((toolCall) => {
                toolCall.loading = false;
              });
            }
          } else if (chunk.content_block.type === 'tool_use') {
            responseMessage.toolCalls.push({
              id: chunk.content_block.id,
              name: chunk.content_block.name,
              arguments: '',
              loading: true,
            });
          } else if (chunk.content_block.type === 'thinking') {
            if (chunk.content_block.thinking) {
              responseMessage.content.push({
                type: 'anthropic_thinking',
                text: chunk.content_block.thinking,
                signature: chunk.content_block.signature,
              });

              responseMessage.toolCalls.forEach((toolCall) => {
                toolCall.loading = false;
              });
            }
          } else if (chunk.content_block.type === 'redacted_thinking') {
            if (chunk.content_block.data) {
              responseMessage.content.push({
                type: 'anthropic_redacted_thinking',
                text: chunk.content_block.data,
              });

              responseMessage.toolCalls.forEach((toolCall) => {
                toolCall.loading = false;
              });
            }
          }
          break;

        case 'content_block_delta':
          if (chunk.delta.type === 'text_delta') {
            if (chunk.delta.text) {
              let currentContent = responseMessage.content.pop();
              if (currentContent?.type !== 'text') {
                if (currentContent?.text) {
                  responseMessage.content.push(currentContent);
                }
                currentContent = createTextContent('');
              }

              currentContent.text += chunk.delta.text ?? '';
              responseMessage.content.push(currentContent);
            }
          } else if (chunk.delta.type === 'input_json_delta') {
            if (chunk.delta.partial_json) {
              const toolCall = {
                ...(responseMessage.toolCalls.pop() ?? {
                  id: '',
                  name: '',
                  arguments: '',
                  loading: true,
                }),
              };

              toolCall.arguments += chunk.delta.partial_json;
              responseMessage.toolCalls.push(toolCall);
            }
          } else if (chunk.delta.type === 'thinking_delta') {
            if (chunk.delta.thinking) {
              let currentContent = responseMessage.content.pop();
              if (currentContent?.type !== 'anthropic_thinking') {
                if (currentContent?.text) {
                  responseMessage.content.push(currentContent);
                }
                currentContent = {
                  type: 'anthropic_thinking',
                  text: '',
                  signature: '',
                };
              }

              currentContent.text += chunk.delta.thinking;
              responseMessage.content.push(currentContent);
            }
          } else if (chunk.delta.type === 'signature_delta') {
            if (chunk.delta.signature) {
              let currentContent = responseMessage.content.pop();
              if (currentContent?.type !== 'anthropic_thinking') {
                if (currentContent?.text) {
                  responseMessage.content.push(currentContent);
                }
                currentContent = {
                  type: 'anthropic_thinking',
                  text: '',
                  signature: '',
                };
              }

              if (currentContent.type === 'anthropic_thinking') {
                currentContent.signature += chunk.delta.signature;
              }
              responseMessage.content.push(currentContent);
            }
          }
          break;

        case 'content_block_stop':
          {
            const toolCall = responseMessage.toolCalls.pop();
            if (toolCall) {
              responseMessage.toolCalls.push({ ...toolCall, loading: false });
            }
          }
          break;

        case 'message_start':
          if (chunk.message.usage) {
            usage.inputTokens = Math.max(usage.inputTokens, chunk.message.usage.input_tokens);
            usage.outputTokens = Math.max(usage.outputTokens, chunk.message.usage.output_tokens);
            usage.cacheReadTokens = Math.max(usage.cacheReadTokens, chunk.message.usage.cache_read_input_tokens ?? 0);
            usage.cacheWriteTokens = Math.max(
              usage.cacheWriteTokens,
              chunk.message.usage.cache_creation_input_tokens ?? 0
            );
          }
          break;

        case 'message_delta':
          if (chunk.usage) {
            usage.outputTokens = Math.max(usage.outputTokens, chunk.usage.output_tokens);
          }
          break;
      }

      response?.write(`data: ${JSON.stringify(responseMessage)}\n\n`);
    } else {
      break;
    }
  }

  responseMessage.content = responseMessage.content.filter(
    (content) => !!content.text && (content.type !== 'anthropic_thinking' || !!content.signature)
  );

  if (responseMessage.content.length === 0 && responseMessage.toolCalls.length === 0) {
    throw new Error('Empty response');
  }

  if (responseMessage.toolCalls.some((toolCall) => toolCall.loading)) {
    responseMessage.toolCalls.forEach((toolCall) => {
      toolCall.loading = false;
    });
  }

  response?.write(`data: ${JSON.stringify(responseMessage)}\n\n`);
  if (!response?.writableEnded) {
    response?.end();
  }

  return { responseMessage, usage };
}

export function parseAnthropicResponse(
  result: Anthropic.Messages.Message,
  modelKey: VertexAIAnthropicModelKey | BedrockAnthropicModelKey | AnthropicModelKey,
  isOnPaidPlan: boolean,
  exceededBillingLimit: boolean,
  response?: Response
): ParsedAIResponse {
  const responseMessage: ApiTypes['/v0/ai/chat.POST.response'] = {
    role: 'assistant',
    content: [],
    contextType: 'userPrompt',
    toolCalls: [],
    modelKey,
    isOnPaidPlan,
    exceededBillingLimit,
  };

  result.content?.forEach((message) => {
    switch (message.type) {
      case 'text':
        if (message.text) {
          responseMessage.content.push(createTextContent(message.text));
        }
        break;

      case 'tool_use':
        responseMessage.toolCalls.push({
          id: message.id,
          name: message.name,
          arguments: JSON.stringify(message.input),
          loading: false,
        });
        break;

      case 'thinking':
        if (message.thinking) {
          responseMessage.content.push({
            type: 'anthropic_thinking',
            text: message.thinking,
            signature: message.signature,
          });
        }
        break;

      case 'redacted_thinking':
        if (message.data) {
          responseMessage.content.push({
            type: 'anthropic_redacted_thinking',
            text: message.data,
          });
        }
        break;
    }
  });

  if (responseMessage.content.length === 0 && responseMessage.toolCalls.length === 0) {
    throw new Error('Empty response');
  }

  response?.json(responseMessage);

  const usage: AIUsage = {
    inputTokens: result.usage.input_tokens,
    outputTokens: result.usage.output_tokens,
    cacheReadTokens: result.usage.cache_read_input_tokens ?? 0,
    cacheWriteTokens: result.usage.cache_creation_input_tokens ?? 0,
  };

  return { responseMessage, usage };
}
```

### quadratic-api/src/ai/helpers/genai.helper.ts

- **Purpose**: Provider adapter (Google GenAI/Gemini) — glue prompt после tool results
- **GitHub**: https://github.com/quadratichq/quadratic/blob/main/quadratic-api/src/ai/helpers/genai.helper.ts
- **Raw**: https://raw.githubusercontent.com/quadratichq/quadratic/main/quadratic-api/src/ai/helpers/genai.helper.ts

```ts
import type { GenerateContentResponse, Schema } from '@google/genai';
import {
  FunctionCallingConfigMode,
  Type,
  type FunctionDeclaration,
  type Content as GenAIContent,
  type Part,
  type Tool,
  type ToolConfig,
} from '@google/genai';
import type { Response } from 'express';
import {
  createTextContent,
  getSystemPromptMessages,
  isAIPromptMessage,
  isContentFile,
  isContentText,
  isInternalMessage,
  isToolResultMessage,
} from 'quadratic-shared/ai/helpers/message.helper';
import { AITool } from 'quadratic-shared/ai/specs/aiToolsSpec';
import type { ApiTypes } from 'quadratic-shared/typesAndSchemas';
import type {
  AIRequestHelperArgs,
  AISource,
  AIToolArgs,
  AIToolArgsArray,
  AIToolArgsPrimitive,
  AIUsage,
  Content,
  GeminiAIModelKey,
  ModelMode,
  ParsedAIResponse,
  TextContent,
  ToolResultContent,
  VertexAIModelKey,
} from 'quadratic-shared/typesAndSchemasAI';
import { v4 } from 'uuid';
import { getAIToolsInOrder } from './tools';

function convertContent(content: Content): Part[] {
  return content
    .filter((content) => !('text' in content) || !!content.text.trim())
    .map((content) => {
      if (isContentText(content)) {
        return { text: content.text.trim() };
      } else if (isContentFile(content)) {
        return {
          inlineData: {
            data: content.data,
            mimeType: content.mimeType,
          },
        };
      } else {
        return undefined;
      }
    })
    .filter((content) => content !== undefined);
}

function convertToolResultContent(content: ToolResultContent): string {
  return content
    .filter((content): content is TextContent => isContentText(content) && !!content.text.trim())
    .map((content) => content.text.trim())
    .join('\n');
}

export function getGenAIApiArgs(
  args: AIRequestHelperArgs,
  aiModelMode: ModelMode
): {
  system: GenAIContent | undefined;
  messages: GenAIContent[];
  tools: Tool[] | undefined;
  tool_choice: ToolConfig | undefined;
} {
  const { messages: chatMessages, toolName, source } = args;

  const { systemMessages, promptMessages } = getSystemPromptMessages(chatMessages);

  const system: GenAIContent | undefined =
    systemMessages.length > 0
      ? {
          role: 'user',
          parts: systemMessages.map((message) => ({ text: message.trim() })),
        }
      : undefined;

  const messages: GenAIContent[] = promptMessages.reduce<GenAIContent[]>((acc, message) => {
    if (isInternalMessage(message)) {
      return acc;
    } else if (isAIPromptMessage(message)) {
      const genaiMessage: GenAIContent = {
        role: 'model',
        parts: [
          ...message.content
            .filter((content) => isContentText(content) && !!content.text.trim())
            .map((content) => ({
              text: content.text.trim(),
            })),
          ...message.toolCalls.map((toolCall) => ({
            functionCall: {
              name: toolCall.name,
              args: toolCall.arguments ? JSON.parse(toolCall.arguments) : {},
            },
          })),
        ],
      };
      return [...acc, genaiMessage];
    } else if (isToolResultMessage(message)) {
      const genaiMessage: GenAIContent = {
        role: message.role,
        parts: [
          ...message.content.map((toolResult) => ({
            functionResponse: {
              name: toolResult.id,
              response: { res: convertToolResultContent(toolResult.content) },
            },
          })),
          {
            text: 'Given the above tool calls results, please provide your final answer to the user.',
          },
        ],
      };
      return [...acc, genaiMessage];
    } else if (message.content) {
      const genaiMessage: GenAIContent = {
        role: message.role === 'assistant' ? 'model' : message.role,
        parts: convertContent(message.content),
      };
      return [...acc, genaiMessage];
    } else {
      return acc;
    }
  }, []);

  const tools = getGenAITools(source, aiModelMode, toolName);
  const tool_choice = tools?.length ? getGenAIToolChoice(toolName) : undefined;

  return { system, messages, tools, tool_choice };
}

function handleMultipleTypes(parameter: AIToolArgsPrimitive): Schema {
  const types = parameter.type as string[];

  // Check if it's a simple nullable type (e.g., ['boolean', 'null'] or ['string', 'null'])
  if (types.length === 2 && types.includes('null')) {
    const nonNullType = types.find((t) => t !== 'null');
    if (nonNullType) {
      const baseSchema = convertSingleType(nonNullType);
      return {
        ...baseSchema,
        nullable: true,
        description: parameter.description,
      };
    }
  }

  // For more complex union types, use anyOf
  return {
    anyOf: types.map((type) => convertSingleType(type)),
    description: parameter.description,
  };
}

function convertSingleType(type: string): Schema {
  switch (type) {
    case 'string':
      return { type: Type.STRING };
    case 'number':
      return { type: Type.NUMBER };
    case 'boolean':
      return { type: Type.BOOLEAN };
    case 'null':
      return { type: Type.NULL };
    default:
      throw new Error(`Unknown type: ${type}`);
  }
}

function convertParametersToGenAISchema(parameter: AIToolArgsPrimitive | AIToolArgsArray | AIToolArgs): Schema {
  // Handle array of types (union types)
  if (Array.isArray(parameter.type)) {
    return handleMultipleTypes(parameter as AIToolArgsPrimitive);
  }

  switch (parameter.type) {
    case 'object':
      return {
        type: Type.OBJECT,
        properties: Object.fromEntries(
          Object.entries(parameter.properties).map(([key, value]) => [key, convertParametersToGenAISchema(value)])
        ),
        required: parameter.required,
      };
    case 'array':
      return {
        type: Type.ARRAY,
        items: convertParametersToGenAISchema(parameter.items),
      };
    case 'string':
      return {
        type: Type.STRING,
        description: parameter.description,
      };
    case 'number':
      return {
        type: Type.NUMBER,
        description: parameter.description,
      };
    case 'boolean':
      return {
        type: Type.BOOLEAN,
        description: parameter.description,
      };
    case 'null':
      return {
        type: Type.NULL,
        description: parameter.description,
      };
    default:
      throw new Error(`Unknown parameter type: ${parameter.type}`);
  }
}

function getGenAITools(source: AISource, aiModelMode: ModelMode, toolName?: AITool): Tool[] | undefined {
  let hasWebSearchInternal = toolName === AITool.WebSearchInternal;
  const tools = getAIToolsInOrder().filter(([name, toolSpec]) => {
    if (!toolSpec.sources.includes(source) || !toolSpec.aiModelModes.includes(aiModelMode)) {
      return false;
    }
    if (name === AITool.WebSearchInternal) {
      hasWebSearchInternal = true;
      return false;
    }
    return toolName ? name === toolName : true;
  });

  if (tools.length === 0 && !hasWebSearchInternal) {
    return undefined;
  }

  const genaiTools: Tool[] = [
    {
      functionDeclarations: tools.map(
        ([name, { description, parameters }]): FunctionDeclaration => ({
          name,
          description,
          parameters: convertParametersToGenAISchema(parameters),
        })
      ),
      googleSearch: hasWebSearchInternal ? {} : undefined,
    },
  ];

  return genaiTools;
}

function getGenAIToolChoice(toolName?: AITool): ToolConfig {
  return toolName === undefined
    ? { functionCallingConfig: { mode: FunctionCallingConfigMode.AUTO } }
    : toolName === AITool.WebSearchInternal
      ? { functionCallingConfig: { mode: FunctionCallingConfigMode.ANY, allowedFunctionNames: ['google_search'] } }
      : { functionCallingConfig: { mode: FunctionCallingConfigMode.ANY, allowedFunctionNames: [toolName] } };
}

export async function parseGenAIStream(
  result: AsyncGenerator<GenerateContentResponse, any, any>,
  modelKey: VertexAIModelKey | GeminiAIModelKey,
  isOnPaidPlan: boolean,
  exceededBillingLimit: boolean,
  response?: Response
): Promise<ParsedAIResponse> {
  const responseMessage: ApiTypes['/v0/ai/chat.POST.response'] = {
    role: 'assistant',
    content: [],
    contextType: 'userPrompt',
    toolCalls: [],
    modelKey,
    isOnPaidPlan,
    exceededBillingLimit,
  };

  response?.write(`data: ${JSON.stringify(responseMessage)}\n\n`);

  const usage: AIUsage = {
    inputTokens: 0,
    outputTokens: 0,
    cacheReadTokens: 0,
    cacheWriteTokens: 0,
  };

  for await (const chunk of result) {
    if (chunk.usageMetadata) {
      usage.inputTokens = Math.max(
        usage.inputTokens,
        (chunk.usageMetadata?.promptTokenCount ?? 0) - (chunk.usageMetadata?.cachedContentTokenCount ?? 0)
      );
      usage.outputTokens = Math.max(usage.outputTokens, chunk.usageMetadata.candidatesTokenCount ?? 0);
      usage.cacheReadTokens = Math.max(usage.cacheReadTokens, chunk.usageMetadata.cachedContentTokenCount ?? 0);
    }

    if (!response?.writableEnded) {
      const candidate = chunk.candidates?.[0];

      // text and tool calls
      for (const part of candidate?.content?.parts ?? []) {
        if (part.text?.trim()) {
          // thinking text
          if (part.thought) {
            let currentContent = responseMessage.content.pop();
            if (currentContent?.type !== 'google_thinking') {
              if (currentContent?.text.trim()) {
                responseMessage.content.push(currentContent);
              }
              currentContent = {
                type: 'google_thinking',
                text: '',
              };
            }
            currentContent.text += part.text;
            responseMessage.content.push(currentContent);
          }
          // chat text
          else {
            let currentContent = responseMessage.content.pop();
            if (currentContent?.type !== 'text') {
              if (currentContent?.text.trim()) {
                responseMessage.content.push(currentContent);
              }
              currentContent = createTextContent('');
            }
            currentContent.text += part.text;
            responseMessage.content.push(currentContent);
          }
        }

        // tool call
        if (part.functionCall?.name) {
          responseMessage.toolCalls.push({
            id: part.functionCall.id ?? v4(),
            name: part.functionCall.name,
            arguments: JSON.stringify(part.functionCall.args),
            loading: false,
          });
        }
      }

      // search grounding metadata
      if (candidate?.groundingMetadata && Object.keys(candidate.groundingMetadata).length > 0) {
        responseMessage.content.push({
          type: 'google_search_grounding_metadata',
          text: JSON.stringify(candidate.groundingMetadata),
        });
      }

      response?.write(`data: ${JSON.stringify(responseMessage)}\n\n`);
    } else {
      break;
    }
  }

  if (responseMessage.content.length === 0 && responseMessage.toolCalls.length === 0) {
    throw new Error('Empty response');
  }

  if (responseMessage.toolCalls.some((toolCall) => toolCall.loading)) {
    responseMessage.toolCalls.forEach((toolCall) => {
      toolCall.loading = false;
    });
  }

  response?.write(`data: ${JSON.stringify(responseMessage)}\n\n`);

  if (!response?.writableEnded) {
    response?.end();
  }

  return { responseMessage, usage };
}

export function parseGenAIResponse(
  result: GenerateContentResponse,
  modelKey: VertexAIModelKey | GeminiAIModelKey,
  isOnPaidPlan: boolean,
  exceededBillingLimit: boolean,
  response?: Response
): ParsedAIResponse {
  const responseMessage: ApiTypes['/v0/ai/chat.POST.response'] = {
    role: 'assistant',
    content: [],
    contextType: 'userPrompt',
    toolCalls: [],
    modelKey,
    isOnPaidPlan,
    exceededBillingLimit,
  };

  const candidate = result?.candidates?.[0];

  // text and tool calls
  candidate?.content?.parts?.forEach((message) => {
    if (message.text) {
      responseMessage.content.push(createTextContent(message.text.trim()));
    } else if (message.functionCall?.name) {
      responseMessage.toolCalls.push({
        id: message.functionCall.id ?? v4(),
        name: message.functionCall.name,
        arguments: JSON.stringify(message.functionCall.args),
        loading: false,
      });
    }
  });

  // search grounding metadata
  if (candidate?.groundingMetadata) {
    responseMessage.content.push({
      type: 'google_search_grounding_metadata',
      text: JSON.stringify(candidate?.groundingMetadata),
    });
  }

  if (responseMessage.content.length === 0 && responseMessage.toolCalls.length === 0) {
    throw new Error('Empty response');
  }

  response?.json(responseMessage);

  const usage: AIUsage = {
    inputTokens: (result.usageMetadata?.promptTokenCount ?? 0) - (result.usageMetadata?.cachedContentTokenCount ?? 0),
    outputTokens: result.usageMetadata?.candidatesTokenCount ?? 0,
    cacheReadTokens: result.usageMetadata?.cachedContentTokenCount ?? 0,
    cacheWriteTokens: 0,
  };

  return { responseMessage, usage };
}
```

### quadratic-api/src/ai/helpers/openai.chatCompletions.helper.ts

- **Purpose**: Provider adapter (OpenAI Chat Completions)
- **GitHub**: https://github.com/quadratichq/quadratic/blob/main/quadratic-api/src/ai/helpers/openai.chatCompletions.helper.ts
- **Raw**: https://raw.githubusercontent.com/quadratichq/quadratic/main/quadratic-api/src/ai/helpers/openai.chatCompletions.helper.ts

```ts
import type { Response } from 'express';
import type OpenAI from 'openai';
import type {
  ChatCompletionContentPart,
  ChatCompletionContentPartText,
  ChatCompletionMessageParam,
  ChatCompletionTool,
  ChatCompletionToolChoiceOption,
} from 'openai/resources';
import type { Stream } from 'openai/streaming';
import { getDataBase64String } from 'quadratic-shared/ai/helpers/files.helper';
import {
  createTextContent,
  getSystemPromptMessages,
  isAIPromptMessage,
  isContentImage,
  isContentText,
  isInternalMessage,
  isToolResultMessage,
} from 'quadratic-shared/ai/helpers/message.helper';
import type { AITool } from 'quadratic-shared/ai/specs/aiToolsSpec';
import type { ApiTypes } from 'quadratic-shared/typesAndSchemas';
import type {
  AIRequestHelperArgs,
  AISource,
  AIUsage,
  AzureOpenAIModelKey,
  BasetenModelKey,
  Content,
  FireworksModelKey,
  ImageContent,
  ModelMode,
  OpenRouterModelKey,
  ParsedAIResponse,
  TextContent,
  ToolResultContent,
  XAIModelKey,
} from 'quadratic-shared/typesAndSchemasAI';
import { v4 } from 'uuid';
import { getAIToolsInOrder } from './tools';

function convertContent(content: Content, imageSupport: boolean): Array<ChatCompletionContentPart> {
  return content
    .filter((content) => !('text' in content) || !!content.text.trim())
    .filter(
      (content): content is TextContent | ImageContent =>
        (imageSupport && isContentImage(content)) || isContentText(content)
    )
    .map((content) => {
      if (isContentText(content)) {
        return content;
      } else {
        return {
          type: 'image_url',
          image_url: {
            url: getDataBase64String(content),
          },
        };
      }
    });
}

function convertToolResultContent(content: ToolResultContent): Array<ChatCompletionContentPartText> {
  return content
    .filter((content) => !('text' in content) || !!content.text.trim())
    .filter((content): content is TextContent => isContentText(content))
    .map((content) => createTextContent(content.text.trim()));
}

export function getOpenAIChatCompletionsApiArgs(
  args: AIRequestHelperArgs,
  aiModelMode: ModelMode,
  strictParams: boolean,
  imageSupport: boolean
): {
  messages: ChatCompletionMessageParam[];
  tools: ChatCompletionTool[] | undefined;
  tool_choice: ChatCompletionToolChoiceOption | undefined;
} {
  const { messages: chatMessages, toolName, source } = args;

  const { systemMessages, promptMessages } = getSystemPromptMessages(chatMessages);
  const messages: ChatCompletionMessageParam[] = promptMessages.reduce<ChatCompletionMessageParam[]>((acc, message) => {
    if (isInternalMessage(message)) {
      return acc;
    } else if (isAIPromptMessage(message)) {
      const openaiMessage: ChatCompletionMessageParam = {
        role: message.role,
        content: message.content
          .filter((content) => isContentText(content) && !!content.text.trim())
          .map((content) => createTextContent(content.text.trim())),
        tool_calls:
          message.toolCalls.length > 0
            ? message.toolCalls.map((toolCall) => ({
                id: toolCall.id,
                type: 'function' as const,
                function: {
                  name: toolCall.name,
                  arguments: toolCall.arguments,
                },
              }))
            : undefined,
      };
      return [...acc, openaiMessage];
    } else if (isToolResultMessage(message)) {
      const openaiMessages: ChatCompletionMessageParam[] = message.content.map((toolResult) => ({
        role: 'tool' as const,
        tool_call_id: toolResult.id,
        content: convertToolResultContent(toolResult.content),
      }));
      return [...acc, ...openaiMessages];
    } else if (message.role === 'user') {
      const openaiMessage: ChatCompletionMessageParam = {
        role: message.role,
        content: convertContent(message.content, imageSupport),
      };
      return [...acc, openaiMessage];
    } else {
      const openaiMessage: ChatCompletionMessageParam = {
        role: message.role,
        content: message.content,
      };
      return [...acc, openaiMessage];
    }
  }, []);

  const openaiMessages: ChatCompletionMessageParam[] = [
    { role: 'system', content: systemMessages.map((message) => createTextContent(message.trim())) },
    ...messages,
  ];

  const tools = getOpenAITools(source, aiModelMode, toolName, strictParams);
  const tool_choice = tools?.length ? getOpenAIToolChoice(toolName) : undefined;

  return { messages: openaiMessages, tools, tool_choice };
}

function getOpenAITools(
  source: AISource,
  aiModelMode: ModelMode,
  toolName: AITool | undefined,
  strictParams: boolean
): ChatCompletionTool[] | undefined {
  const tools = getAIToolsInOrder().filter(([name, toolSpec]) => {
    if (!toolSpec.aiModelModes.includes(aiModelMode)) {
      return false;
    }
    if (toolName === undefined) {
      return toolSpec.sources.includes(source);
    }
    return name === toolName;
  });

  if (tools.length === 0) {
    return undefined;
  }

  const openaiTools: ChatCompletionTool[] = tools.map(
    ([name, { description, parameters }]): ChatCompletionTool => ({
      type: 'function' as const,
      function: {
        name,
        description,
        parameters,
        strict: strictParams,
      },
    })
  );

  return openaiTools;
}

function getOpenAIToolChoice(name?: AITool): ChatCompletionToolChoiceOption {
  return name === undefined ? 'auto' : { type: 'function', function: { name } };
}

export async function parseOpenAIChatCompletionsStream(
  chunks: Stream<OpenAI.Chat.Completions.ChatCompletionChunk>,
  modelKey: AzureOpenAIModelKey | XAIModelKey | BasetenModelKey | FireworksModelKey | OpenRouterModelKey,
  isOnPaidPlan: boolean,
  exceededBillingLimit: boolean,
  response?: Response
): Promise<ParsedAIResponse> {
  const responseMessage: ApiTypes['/v0/ai/chat.POST.response'] = {
    role: 'assistant',
    content: [],
    contextType: 'userPrompt',
    toolCalls: [],
    modelKey,
    isOnPaidPlan,
    exceededBillingLimit,
  };

  response?.write(`data: ${JSON.stringify(responseMessage)}\n\n`);

  const usage: AIUsage = {
    inputTokens: 0,
    outputTokens: 0,
    cacheReadTokens: 0,
    cacheWriteTokens: 0,
  };

  for await (const chunk of chunks) {
    if (chunk.usage) {
      usage.inputTokens = Math.max(usage.inputTokens, chunk.usage.prompt_tokens);
      usage.outputTokens = Math.max(usage.outputTokens, chunk.usage.completion_tokens);
      usage.cacheReadTokens = Math.max(usage.cacheReadTokens, chunk.usage.prompt_tokens_details?.cached_tokens ?? 0);
      usage.inputTokens -= usage.cacheReadTokens;
    }

    if (!response?.writableEnded) {
      if (chunk.choices && chunk.choices[0] && chunk.choices[0].delta) {
        // text delta
        if (chunk.choices[0].delta.content) {
          const currentContent = { ...(responseMessage.content.pop() ?? createTextContent('')) };
          currentContent.text += chunk.choices[0].delta.content;
          responseMessage.content.push(currentContent);

          responseMessage.toolCalls = responseMessage.toolCalls.map((toolCall) => ({
            ...toolCall,
            loading: false,
          }));
        }
        // tool use delta
        else if (chunk.choices[0].delta.tool_calls) {
          chunk.choices[0].delta.tool_calls.forEach((tool_call) => {
            const toolCall = responseMessage.toolCalls.pop();
            if (toolCall) {
              responseMessage.toolCalls.push({
                ...toolCall,
                loading: true,
              });
            }
            if (tool_call.function?.name) {
              // New tool call
              responseMessage.toolCalls.push({
                id: tool_call.id ?? v4(),
                name: tool_call.function.name,
                arguments: tool_call.function.arguments ?? '',
                loading: true,
              });
            } else {
              // Append to existing tool call
              const currentToolCall = responseMessage.toolCalls.pop() ?? {
                id: '',
                name: '',
                arguments: '',
                loading: true,
              };

              responseMessage.toolCalls.push({
                ...currentToolCall,
                arguments: currentToolCall.arguments + (tool_call.function?.arguments ?? ''),
              });
            }
          });
        }
        // tool use stop
        else if (chunk.choices[0].finish_reason === 'tool_calls') {
          responseMessage.toolCalls.forEach((toolCall) => {
            toolCall.loading = false;
          });
        }
      }

      response?.write(`data: ${JSON.stringify(responseMessage)}\n\n`);
    } else {
      break;
    }
  }

  responseMessage.content = responseMessage.content.filter((content) => content.text !== '');

  if (responseMessage.content.length === 0 && responseMessage.toolCalls.length === 0) {
    throw new Error('Empty response');
  }

  if (responseMessage.toolCalls.some((toolCall) => toolCall.loading)) {
    responseMessage.toolCalls = responseMessage.toolCalls.map((toolCall) => ({
      ...toolCall,
      loading: false,
    }));
  }

  response?.write(`data: ${JSON.stringify(responseMessage)}\n\n`);
  if (!response?.writableEnded) {
    response?.end();
  }

  return { responseMessage, usage };
}

export function parseOpenAIChatCompletionsResponse(
  result: OpenAI.Chat.Completions.ChatCompletion,
  modelKey: AzureOpenAIModelKey | XAIModelKey | BasetenModelKey | FireworksModelKey | OpenRouterModelKey,
  isOnPaidPlan: boolean,
  exceededBillingLimit: boolean,
  response?: Response
): ParsedAIResponse {
  const responseMessage: ApiTypes['/v0/ai/chat.POST.response'] = {
    role: 'assistant',
    content: [],
    contextType: 'userPrompt',
    toolCalls: [],
    modelKey,
    isOnPaidPlan,
    exceededBillingLimit,
  };

  const message = result.choices[0].message;

  if (message.content) {
    responseMessage.content.push(createTextContent(message.content.trim()));
  }

  if (message.tool_calls) {
    message.tool_calls.forEach((toolCall) => {
      if (toolCall.type === 'function') {
        responseMessage.toolCalls.push({
          id: toolCall.id,
          name: toolCall.function.name,
          arguments: toolCall.function.arguments,
          loading: false,
        });
      }
    });
  }

  if (responseMessage.content.length === 0 && responseMessage.toolCalls.length === 0) {
    throw new Error('Empty response');
  }

  response?.json(responseMessage);

  const cacheReadTokens = result.usage?.prompt_tokens_details?.cached_tokens ?? 0;
  const usage: AIUsage = {
    inputTokens: (result.usage?.prompt_tokens ?? 0) - cacheReadTokens,
    outputTokens: result.usage?.completion_tokens ?? 0,
    cacheReadTokens,
    cacheWriteTokens: 0,
  };

  return { responseMessage, usage };
}
```

### quadratic-api/src/ai/helpers/openai.responses.helper.ts

- **Purpose**: Provider adapter (OpenAI Responses API)
- **GitHub**: https://github.com/quadratichq/quadratic/blob/main/quadratic-api/src/ai/helpers/openai.responses.helper.ts
- **Raw**: https://raw.githubusercontent.com/quadratichq/quadratic/main/quadratic-api/src/ai/helpers/openai.responses.helper.ts

```ts
import type { Response } from 'express';
import type OpenAI from 'openai';
import type {
  ResponseFunctionToolCall,
  ResponseInput,
  ResponseInputContent,
  ResponseInputItem,
  ResponseReasoningItem,
  Tool,
  ToolChoiceFunction,
  ToolChoiceOptions,
  ToolChoiceTypes,
} from 'openai/resources/responses/responses';
import type { Stream } from 'openai/streaming';
import { getDataBase64String } from 'quadratic-shared/ai/helpers/files.helper';
import {
  createTextContent,
  getSystemPromptMessages,
  isAIPromptMessage,
  isContentImage,
  isContentOpenAIReasoning,
  isContentText,
  isInternalMessage,
  isToolResultMessage,
} from 'quadratic-shared/ai/helpers/message.helper';
import type { AITool } from 'quadratic-shared/ai/specs/aiToolsSpec';
import { aiToolsSpec } from 'quadratic-shared/ai/specs/aiToolsSpec';
import type { ApiTypes } from 'quadratic-shared/typesAndSchemas';
import type {
  AIRequestHelperArgs,
  AIResponseThinkingContent,
  AISource,
  AIUsage,
  AzureOpenAIModelKey,
  Content,
  ImageContent,
  ModelMode,
  OpenAIModelKey,
  ParsedAIResponse,
  TextContent,
  ToolResultContent,
} from 'quadratic-shared/typesAndSchemasAI';
import { v4 } from 'uuid';

function convertInputTextContent(content: TextContent): ResponseInputContent {
  return {
    type: 'input_text' as const,
    text: content.text.trim(),
  };
}

function convertImageContent(content: ImageContent): ResponseInputContent {
  return {
    type: 'input_image' as const,
    image_url: getDataBase64String(content),
    detail: 'auto',
  };
}

function convertInputContent(content: Content | ToolResultContent, imageSupport: boolean): Array<ResponseInputContent> {
  return content
    .filter((content) => !('text' in content) || !!content.text.trim())
    .filter(
      (content): content is TextContent | ImageContent =>
        (imageSupport && isContentImage(content)) || isContentText(content)
    )
    .map((content) => {
      if (isContentText(content)) {
        return convertInputTextContent(content);
      } else {
        return convertImageContent(content);
      }
    });
}

export function getOpenAIResponsesApiArgs(
  args: AIRequestHelperArgs,
  aiModelMode: ModelMode,
  strictParams: boolean,
  imageSupport: boolean
): {
  messages: ResponseInput;
  tools: Array<Tool> | undefined;
  tool_choice: ToolChoiceOptions | ToolChoiceTypes | ToolChoiceFunction | undefined;
} {
  const { messages: chatMessages, toolName, source } = args;

  const { systemMessages, promptMessages } = getSystemPromptMessages(chatMessages);
  const messages: Array<ResponseInputItem> = promptMessages.reduce<Array<ResponseInputItem>>((acc, message) => {
    if (isInternalMessage(message)) {
      return acc;
    } else if (isAIPromptMessage(message)) {
      const reasoningItems: ResponseReasoningItem[] = [];
      const openaiMessages: ResponseInputItem[] = [
        {
          role: message.role,
          content: message.content
            .filter((content) => !('text' in content) || !!content.text.trim())
            .filter((content): content is TextContent | AIResponseThinkingContent => {
              if (isContentOpenAIReasoning(content)) {
                let currentReasoningItem = reasoningItems.pop();
                if (currentReasoningItem?.id !== content.id) {
                  if (currentReasoningItem?.summary?.length || currentReasoningItem?.content?.length) {
                    reasoningItems.push(currentReasoningItem);
                  }
                  currentReasoningItem = {
                    id: content.id,
                    type: 'reasoning' as const,
                    summary: [],
                    content: [],
                  };
                }
                if (content.type === 'openai_reasoning_summary') {
                  currentReasoningItem.summary.push({
                    type: 'summary_text' as const,
                    text: content.text.trim(),
                  });
                } else if (content.type === 'openai_reasoning_content') {
                  if (!currentReasoningItem.content) {
                    currentReasoningItem.content = [];
                  }
                  currentReasoningItem.content.push({
                    type: 'reasoning_text' as const,
                    text: content.text.trim(),
                  });
                }
                return false;
              }

              return isContentText(content);
            })
            .map((content) => ({
              type: 'output_text' as const,
              text: content.text.trim(),
              annotations: [],
              logprobs: [],
            })),
          id: message.id?.startsWith('msg_') ? message.id : `msg_${v4()}`,
          status: 'completed',
          type: 'message',
        },
        ...message.toolCalls.map<ResponseFunctionToolCall>((toolCall) => ({
          call_id: toolCall.id.startsWith('call_') ? toolCall.id : `call_${toolCall.id}`,
          type: 'function_call' as const,
          name: toolCall.name,
          arguments: toolCall.arguments,
        })),
      ] as ResponseInputItem[];
      return [...acc, ...openaiMessages];
    } else if (isToolResultMessage(message)) {
      const openaiMessages: ResponseInputItem[] = message.content.map((toolResult) => ({
        call_id: toolResult.id.startsWith('call_') ? toolResult.id : `call_${toolResult.id}`,
        type: 'function_call_output' as const,
        output: JSON.stringify(convertInputContent(toolResult.content, false)),
      }));
      return [...acc, ...openaiMessages];
    } else if (message.role === 'user') {
      const openaiMessage: ResponseInputItem = {
        role: message.role,
        content: convertInputContent(message.content, imageSupport),
      };
      return [...acc, openaiMessage];
    } else {
      const openaiMessage: ResponseInputItem = {
        role: message.role,
        content: message.content.map((content) => ({
          type: 'output_text' as const,
          text: content.text,
          annotations: [],
          logprobs: [],
        })),
        id: v4(),
        status: 'completed',
        type: 'message',
      };
      return [...acc, openaiMessage];
    }
  }, []);

  const openaiMessages: ResponseInput = [
    {
      role: 'system',
      content: systemMessages.map((message) => ({ type: 'input_text' as const, text: message.trim() })),
    },
    ...messages,
  ];

  const tools = getOpenAITools(source, aiModelMode, toolName, strictParams);
  const tool_choice = tools?.length ? getOpenAIToolChoice(toolName) : undefined;

  return { messages: openaiMessages, tools, tool_choice };
}

function getOpenAITools(
  source: AISource,
  aiModelMode: ModelMode,
  toolName: AITool | undefined,
  strictParams: boolean
): Tool[] | undefined {
  const tools = Object.entries(aiToolsSpec).filter(([name, toolSpec]) => {
    if (!toolSpec.aiModelModes.includes(aiModelMode)) {
      return false;
    }
    if (toolName === undefined) {
      return toolSpec.sources.includes(source);
    }
    return name === toolName;
  });

  if (tools.length === 0) {
    return undefined;
  }

  const openaiTools: Tool[] = tools.map(
    ([name, { description, parameters }]): Tool => ({
      type: 'function' as const,
      name,
      description,
      parameters,
      strict: strictParams,
    })
  );

  return openaiTools;
}

function getOpenAIToolChoice(name?: AITool): ToolChoiceOptions | ToolChoiceTypes | ToolChoiceFunction {
  return name === undefined ? 'auto' : { type: 'function', name };
}

export async function parseOpenAIResponsesStream(
  chunks: Stream<OpenAI.Responses.ResponseStreamEvent>,
  modelKey: OpenAIModelKey | AzureOpenAIModelKey,
  isOnPaidPlan: boolean,
  exceededBillingLimit: boolean,
  response?: Response
): Promise<ParsedAIResponse> {
  const responseMessage: ApiTypes['/v0/ai/chat.POST.response'] = {
    role: 'assistant',
    content: [],
    contextType: 'userPrompt',
    toolCalls: [],
    modelKey,
    isOnPaidPlan,
    exceededBillingLimit,
  };

  response?.write(`data: ${JSON.stringify(responseMessage)}\n\n`);

  const usage: AIUsage = {
    inputTokens: 0,
    outputTokens: 0,
    cacheReadTokens: 0,
    cacheWriteTokens: 0,
  };

  for await (const chunk of chunks) {
    if (!('response' in chunk)) {
      continue;
    }

    if (chunk.response.usage) {
      usage.inputTokens = Math.max(usage.inputTokens, chunk.response.usage.input_tokens);
      usage.outputTokens = Math.max(usage.outputTokens, chunk.response.usage.output_tokens);
      usage.cacheReadTokens = Math.max(
        usage.cacheReadTokens,
        chunk.response.usage.input_tokens_details?.cached_tokens ?? 0
      );
      usage.inputTokens -= usage.cacheReadTokens;
    }

    if (!response?.writableEnded) {
      for (const output of chunk.response.output) {
        switch (output.type) {
          case 'message':
            for (const content of output.content) {
              switch (content.type) {
                case 'output_text':
                  responseMessage.content.push(createTextContent(content.text));
              }
            }
            break;

          case 'function_call':
            responseMessage.toolCalls.push({
              id: output.call_id ?? `call_${v4()}`,
              name: output.name,
              arguments: output.arguments,
              loading: false,
            });
            break;

          case 'reasoning':
            for (const reasoning of output.summary) {
              let currentContent = responseMessage.content.pop();
              if (currentContent?.type !== 'openai_reasoning_summary' || currentContent.id !== output.id) {
                if (currentContent?.text) {
                  responseMessage.content.push(currentContent);
                }
                currentContent = {
                  type: 'openai_reasoning_summary',
                  text: '',
                  id: output.id,
                };
              }
              currentContent.text += `\n${reasoning.text}`;
              responseMessage.content.push(currentContent);
            }
            for (const reasoning of output.content ?? []) {
              let currentContent = responseMessage.content.pop();
              if (currentContent?.type !== 'openai_reasoning_content') {
                if (currentContent?.text) {
                  responseMessage.content.push(currentContent);
                }
                currentContent = {
                  type: 'openai_reasoning_content',
                  text: '',
                  id: output.id,
                };
              }
              currentContent.text += `\n${reasoning.text}`;
              responseMessage.content.push(currentContent);
            }
            break;
        }
      }

      response?.write(`data: ${JSON.stringify(responseMessage)}\n\n`);
    } else {
      break;
    }
  }

  responseMessage.content = responseMessage.content.filter((content) => content.text !== '');

  if (responseMessage.content.length === 0 && responseMessage.toolCalls.length === 0) {
    throw new Error('Empty response');
  }

  if (responseMessage.toolCalls.some((toolCall) => toolCall.loading)) {
    responseMessage.toolCalls = responseMessage.toolCalls.map((toolCall) => ({
      ...toolCall,
      loading: false,
    }));
  }

  response?.write(`data: ${JSON.stringify(responseMessage)}\n\n`);
  if (!response?.writableEnded) {
    response?.end();
  }

  return { responseMessage, usage };
}

export function parseOpenAIResponsesResponse(
  result: OpenAI.Responses.Response,
  modelKey: OpenAIModelKey | AzureOpenAIModelKey,
  isOnPaidPlan: boolean,
  exceededBillingLimit: boolean,
  response?: Response
): ParsedAIResponse {
  const responseMessage: ApiTypes['/v0/ai/chat.POST.response'] = {
    role: 'assistant',
    content: [],
    contextType: 'userPrompt',
    toolCalls: [],
    modelKey,
    isOnPaidPlan,
    exceededBillingLimit,
  };

  for (const output of result.output) {
    switch (output.type) {
      case 'message':
        responseMessage.id = output.id;
        output.content.forEach((content) => {
          switch (content.type) {
            case 'output_text':
              responseMessage.content.push(createTextContent(content.text.trim()));
              break;
          }
        });
        break;
      case 'function_call':
        responseMessage.toolCalls.push({
          id: output.call_id ?? v4(),
          name: output.name,
          arguments: output.arguments,
          loading: false,
        });
        break;
    }
  }

  if (responseMessage.content.length === 0 && responseMessage.toolCalls.length === 0) {
    throw new Error('Empty response');
  }

  response?.json(responseMessage);

  const cacheReadTokens = result.usage?.input_tokens_details.cached_tokens ?? 0;
  const usage: AIUsage = {
    inputTokens: (result.usage?.input_tokens ?? 0) - cacheReadTokens,
    outputTokens: result.usage?.output_tokens ?? 0,
    cacheReadTokens,
    cacheWriteTokens: 0,
  };

  return { responseMessage, usage };
}
```

### quadratic-api/src/ai/helpers/bedrock.helper.ts

- **Purpose**: Provider adapter (AWS Bedrock)
- **GitHub**: https://github.com/quadratichq/quadratic/blob/main/quadratic-api/src/ai/helpers/bedrock.helper.ts
- **Raw**: https://raw.githubusercontent.com/quadratichq/quadratic/main/quadratic-api/src/ai/helpers/bedrock.helper.ts

```ts
import type {
  ContentBlock,
  ConverseResponse,
  ConverseStreamOutput,
  DocumentBlock,
  DocumentFormat,
  ImageBlock,
  ImageFormat,
  Message,
  SystemContentBlock,
  Tool,
  ToolChoice,
  ToolResultContentBlock,
} from '@aws-sdk/client-bedrock-runtime';
import type { Response } from 'express';
import {
  createTextContent,
  getSystemPromptMessages,
  isAIPromptMessage,
  isContentImage,
  isContentPdfFile,
  isContentText,
  isContentTextFile,
  isInternalMessage,
  isToolResultMessage,
} from 'quadratic-shared/ai/helpers/message.helper';
import type { AITool } from 'quadratic-shared/ai/specs/aiToolsSpec';
import type { ApiTypes } from 'quadratic-shared/typesAndSchemas';
import type {
  AIRequestHelperArgs,
  AISource,
  AIUsage,
  BedrockModelKey,
  Content,
  ModelMode,
  ParsedAIResponse,
  ToolResultContent,
} from 'quadratic-shared/typesAndSchemasAI';
import { v4 } from 'uuid';
import { getAIToolsInOrder } from './tools';

function convertContent(content: Content): ContentBlock[] {
  return content
    .filter((content) => !('text' in content) || !!content.text.trim())
    .map((content) => {
      if (isContentImage(content)) {
        const image: ImageBlock = {
          format: content.mimeType.split('/')[1] as ImageFormat,
          source: { bytes: Uint8Array.from(Buffer.from(content.data, 'base64')) },
        };
        return { image };
      } else if (isContentPdfFile(content) || isContentTextFile(content)) {
        const document: DocumentBlock = {
          format: content.mimeType.split('/')[1] as DocumentFormat,
          name: content.fileName,
          source: { bytes: Uint8Array.from(Buffer.from(content.data, 'base64')) },
        };
        return { document };
      } else if (isContentText(content)) {
        return {
          text: content.text.trim(),
        };
      } else {
        return undefined;
      }
    })
    .filter((content) => content !== undefined);
}

function convertToolResultContent(content: ToolResultContent): ToolResultContentBlock[] {
  return content
    .filter((content) => !('text' in content) || !!content.text.trim())
    .map((content) => {
      if (isContentImage(content)) {
        const image: ImageBlock = {
          format: content.mimeType.split('/')[1] as ImageFormat,
          source: { bytes: Uint8Array.from(Buffer.from(content.data, 'base64')) },
        };
        return { image };
      } else {
        return {
          text: content.text.trim(),
        };
      }
    });
}

export function getBedrockApiArgs(
  args: AIRequestHelperArgs,
  aiModelMode: ModelMode
): {
  system: SystemContentBlock[] | undefined;
  messages: Message[];
  tools: Tool[] | undefined;
  tool_choice: ToolChoice | undefined;
} {
  const { messages: chatMessages, toolName, source } = args;

  const { systemMessages, promptMessages } = getSystemPromptMessages(chatMessages);
  const system: SystemContentBlock[] = systemMessages.map((message) => ({ text: message.trim() }));
  const messages: Message[] = promptMessages.reduce<Message[]>((acc, message) => {
    if (isInternalMessage(message)) {
      return acc;
    } else if (isAIPromptMessage(message)) {
      const bedrockMessage: Message = {
        role: message.role,
        content: [
          ...message.content
            .filter((content) => content.type === 'text' && !!content.text.trim())
            .map((content) => ({
              text: content.text.trim(),
            })),
          ...message.toolCalls.map((toolCall) => ({
            toolUse: {
              toolUseId: toolCall.id,
              name: toolCall.name,
              input: toolCall.arguments ? JSON.parse(toolCall.arguments) : {},
            },
          })),
        ],
      };
      return [...acc, bedrockMessage];
    } else if (isToolResultMessage(message)) {
      const bedrockMessage: Message = {
        role: message.role,
        content: [
          ...message.content.map((toolResult) => ({
            toolResult: {
              toolUseId: toolResult.id,
              content: convertToolResultContent(toolResult.content),
              status: 'success' as const,
            },
          })),
        ],
      };
      return [...acc, bedrockMessage];
    } else if (message.content) {
      const bedrockMessage: Message = {
        role: message.role,
        content: convertContent(message.content),
      };
      return [...acc, bedrockMessage];
    } else {
      return acc;
    }
  }, []);

  const tools = getBedrockTools(source, aiModelMode, toolName);
  const tool_choice = tools?.length ? getBedrockToolChoice(toolName) : undefined;

  return { system, messages, tools, tool_choice };
}

function getBedrockTools(source: AISource, aiModelMode: ModelMode, toolName?: AITool): Tool[] | undefined {
  const tools = getAIToolsInOrder().filter(([name, toolSpec]) => {
    if (!toolSpec.aiModelModes.includes(aiModelMode)) {
      return false;
    }
    if (toolName === undefined) {
      return toolSpec.sources.includes(source);
    }
    return name === toolName;
  });

  if (tools.length === 0) {
    return undefined;
  }

  const bedrockTools: Tool[] = tools.map(
    ([name, { description, parameters: input_schema }]): Tool => ({
      toolSpec: {
        name,
        description,
        inputSchema: {
          json: input_schema,
        },
      },
    })
  );

  return bedrockTools;
}

function getBedrockToolChoice(toolName?: AITool): ToolChoice {
  return toolName === undefined ? { auto: {} } : { tool: { name: toolName } };
}

export async function parseBedrockStream(
  chunks: AsyncIterable<ConverseStreamOutput> | never[],
  modelKey: BedrockModelKey,
  isOnPaidPlan: boolean,
  exceededBillingLimit: boolean,
  response?: Response
): Promise<ParsedAIResponse> {
  const responseMessage: ApiTypes['/v0/ai/chat.POST.response'] = {
    role: 'assistant',
    content: [],
    contextType: 'userPrompt',
    toolCalls: [],
    modelKey,
    isOnPaidPlan,
    exceededBillingLimit,
  };

  response?.write(`data: ${JSON.stringify(responseMessage)}\n\n`);

  const usage: AIUsage = {
    inputTokens: 0,
    outputTokens: 0,
    cacheReadTokens: 0,
    cacheWriteTokens: 0,
  };

  for await (const chunk of chunks) {
    if (chunk.metadata) {
      usage.inputTokens = Math.max(usage.inputTokens, chunk.metadata.usage?.inputTokens ?? 0);
      usage.outputTokens = Math.max(usage.outputTokens, chunk.metadata.usage?.outputTokens ?? 0);
    }

    if (!response?.writableEnded) {
      if (chunk.contentBlockStart) {
        // tool use start
        if (chunk.contentBlockStart.start && chunk.contentBlockStart.start.toolUse) {
          const toolCall = {
            id: chunk.contentBlockStart.start.toolUse.toolUseId ?? v4(),
            name: chunk.contentBlockStart.start.toolUse.name ?? '',
            arguments: '',
            loading: true,
          };
          responseMessage.toolCalls.push(toolCall);
        }
      }
      // tool use stop
      else if (chunk.contentBlockStop) {
        let toolCall = responseMessage.toolCalls.pop();
        if (toolCall) {
          toolCall = { ...toolCall, loading: false };
          responseMessage.toolCalls.push(toolCall);
        }
      } else if (chunk.contentBlockDelta) {
        if (chunk.contentBlockDelta.delta) {
          // text delta
          if ('text' in chunk.contentBlockDelta.delta && chunk.contentBlockDelta.delta.text) {
            const currentContent = { ...(responseMessage.content.pop() ?? createTextContent('')) };
            currentContent.text += chunk.contentBlockDelta.delta.text;
            responseMessage.content.push(currentContent);
          }

          // tool use delta
          if ('toolUse' in chunk.contentBlockDelta.delta && chunk.contentBlockDelta.delta.toolUse) {
            const toolCall = {
              ...(responseMessage.toolCalls.pop() ?? {
                id: '',
                name: '',
                arguments: '',
                loading: true,
              }),
            };
            toolCall.arguments += chunk.contentBlockDelta.delta.toolUse.input ?? '';
            responseMessage.toolCalls.push(toolCall);
          }
        }
      }

      response?.write(`data: ${JSON.stringify(responseMessage)}\n\n`);
    } else {
      break;
    }
  }

  responseMessage.content = responseMessage.content.filter((content) => content.text !== '');

  if (responseMessage.content.length === 0 && responseMessage.toolCalls.length === 0) {
    throw new Error('Empty response');
  }

  if (responseMessage.toolCalls.some((toolCall) => toolCall.loading)) {
    responseMessage.toolCalls = responseMessage.toolCalls.map((toolCall) => ({
      ...toolCall,
      loading: false,
    }));
  }

  response?.write(`data: ${JSON.stringify(responseMessage)}\n\n`);
  if (!response?.writableEnded) {
    response?.end();
  }

  return { responseMessage, usage };
}

export function parseBedrockResponse(
  result: ConverseResponse,
  modelKey: BedrockModelKey,
  isOnPaidPlan: boolean,
  exceededBillingLimit: boolean,
  response?: Response
): ParsedAIResponse {
  const responseMessage: ApiTypes['/v0/ai/chat.POST.response'] = {
    role: 'assistant',
    content: [],
    contextType: 'userPrompt',
    toolCalls: [],
    modelKey,
    isOnPaidPlan,
    exceededBillingLimit,
  };

  result.output?.message?.content?.forEach((contentBlock) => {
    if ('text' in contentBlock && contentBlock.text) {
      responseMessage.content.push(createTextContent(contentBlock.text.trim()));
    }

    if ('toolUse' in contentBlock && contentBlock.toolUse) {
      responseMessage.toolCalls.push({
        id: contentBlock.toolUse.toolUseId ?? v4(),
        name: contentBlock.toolUse.name ?? '',
        arguments: JSON.stringify(contentBlock.toolUse.input),
        loading: false,
      });
    }
  });

  if (responseMessage.content.length === 0 && responseMessage.toolCalls.length === 0) {
    throw new Error('Empty response');
  }

  response?.json(responseMessage);

  const usage: AIUsage = {
    inputTokens: result.usage?.inputTokens ?? 0,
    outputTokens: result.usage?.outputTokens ?? 0,
    cacheReadTokens: 0,
    cacheWriteTokens: 0,
  };

  return { responseMessage, usage };
}
```

### quadratic-api/src/ai/docs/QuadraticDocs.ts

- **Purpose**: Docs injected: QuadraticDocs
- **GitHub**: https://github.com/quadratichq/quadratic/blob/main/quadratic-api/src/ai/docs/QuadraticDocs.ts
- **Raw**: https://raw.githubusercontent.com/quadratichq/quadratic/main/quadratic-api/src/ai/docs/QuadraticDocs.ts

```ts
export const QuadraticDocs = `# Quadratic Docs

Quadratic is a modern AI-enabled spreadsheet. Quadratic is purpose built to make working with data easier and faster.

Quadratic combines a familiar spreadsheet and formulas with the power of AI and modern coding languages like Python, SQL, and JavaScript.

## File and Data support in Quadratic

Files can be imported by drag and dropping into the sheet, those supported file types are: csv, excel, parquet. SQL can be used to connect to databases. Once in the sheet, data can be analyzed with coding languages and AI.

Data can be exported by following these steps: 1. highlight the data 2. right-click the highlighted data 3. select export to csv

You can also export via the file menu. File > Download > choose file type. You can export to Quadratic file type .grid, Excel as .xlsx, and CSV.

Files can be shared with other users by selecting the share button in the top right. Quadratic .grid files can be download from the file menu.

The AI chat can import PDFs and images. To add PDFs and images to the chat, use the paperclip attach button to attach your file from the chat box. You can also paste PDFs and images into the chat box or drag and drop.

## Code in Quadratic

Code is inserted via AI or by pressing / while in a cell to open the code selection menu. Code cells can be re-opened to view and edit the code by double clicking or pressing / when selected.

Quadratic cells can be formatted from the toolbar options or by AI, but not via code.

## Tables in Quadratic

Quadratic uses tables commonly to structure data. IMPORTANT: tables do not support Formulas or Code but will in the future. You cannot place Code or Formulas inside of tables.

Code generated in Quadratic is not global to other code cells. The data the code cell outputs to the sheet can be referenced by other cells, but variables in one code cell cannot be read in another. Imports in one code cell do not automatically apply to other code cells.

## Placing cells, tables, code, and connections in Quadratic

Unless specifically requested, you MUST NOT place cells, tables, code, or connections over existing content on the sheet. In the context provided, you have information about where all data exists in the sheet. (Although you may not have the actual data, you have the ranges of all data.) Use that information to find the correct location to place any new data. Take into account the expected size of the new data, and ensure there is sufficient room for that data. Before placing any data, you MUST use these steps:

1. identify the existing data context using information provided to you (all information about data on the sheets is provided below)
2. identify empty spaces using the existing ranges. For example, if there is data in A1:D5, place the data outside that, below row 5 or to the right of column D
3. once you have done that calculation, place content in an empty area. In most cases, leave one cell of space between the old data and the new data

## Formatting values in Quadratic

Values in the sheet can be formatted by using the formatting toolbar or by AI.

Treat values as spreadsheet values. E.g. if you want to represent 1%, enter and use it as .01 - formatting .01 as % will show 1%. Formatting 1 as a percentage will show 100%.

Emojis are supported in Quadratic and may be inserted by including the emoji directly in the cell or code.

## Spills in Quadratic

Code, data tables, and charts may take up more than one cell on the sheet. When they expand, they may overlap existing content, either directly on the sheet or in other code, table, or chart cells. If this happens, it is called a spill.

To fix a spill, you MUST use the move_cells tool to move the table, code, or connection to a different position. Ensure that the position has sufficient space to accommodate the entire range without creating another spill. Ideally, leave on cell of space between the new position and any surrounding content.
`;
```

### quadratic-api/src/ai/docs/PythonDocs.ts

- **Purpose**: Docs injected: PythonDocs
- **GitHub**: https://github.com/quadratichq/quadratic/blob/main/quadratic-api/src/ai/docs/PythonDocs.ts
- **Raw**: https://raw.githubusercontent.com/quadratichq/quadratic/main/quadratic-api/src/ai/docs/PythonDocs.ts

```ts
export const PythonDocs = `# Python Documentation for Quadratic

You can reference cells in the spreadsheet to use in code, and you can return results from your Python code back to the spreadsheet. The last line of code is returned to the spreadsheet.
Python does not support conditional returns in Quadratic. Only the last line of code is returned to the sheet. There can be only one variable returned to the sheet per code cell.

Data, variables, and imports are not global; they are scoped to the code cell they exist in and must be imported or referenced in every code cell that uses them.

When the data that code references is updated, the code cell is automatically re-run. Editing code and data dependencies always re-runs any dependencies.

## Reference cells from Python

You can reference tables, individual cells, and ranges of cells using Python.

Use table references by default when referencing data that is in a table; use A1 references when referencing data not in a table. 

### Referencing tables

\`\`\`python
# References entire table, including headers, places into DataFrame with table headers as DataFrame headers 
df = q.cells("Table1")

# Retrieves the column data and its header, places into single column DataFrame
df_column = q.cells("Table1[column_name]")

# Creates an empty DataFrame with just the DataFrame's headers as table's column names
df_headers = q.cells("Table1[#HEADERS]")

# Reference a range of columns from a table, e.g. in following example we reference columns 1, 2, and 3. Columns can then be dropped or manipulated using Pandas DataFrame logic.
df_columns = q.cells("Table1[[Column 1]:[Column 3]]")
\`\`\`python

Tables should be used whenever possible with tables. Use ranged A1 references or single cell references otherwise. 

### Referencing individual cells

\`\`\`python
# Reads the value in cell A1 and stores in variable x 
x = q.cells('A1')
\`\`\`

\`\`\`python
q.cells('A1') + q.cells('A2') # sum of values in A1 and A2
\`\`\`

### Referencing a range of cells

To reference a range of cells, use the global function \`q.cells\`, ranged references will always return a Pandas DataFrame.

\`\`\`python
q.cells('A1:A5') # Returns a 1x5 DataFrame spanning from A1 to A5
q.cells('A1:C7') # Returns a 3x7 DataFrame spanning from A1 to C7
q.cells('A') # Returns all values in column A into a single-column DataFrame
q.cells('A:C') # Returns all values in columns A to C into a three-column DataFrame
q.cells('A5:A') # Returns all values in column A starting at A5 and going down until the next blank cell 
q.cells('A5:C') # Returns all values in columns A to C, starting at A5 and going down
\`\`\`

If the first row of cells is a header, you should set \`first_row_header\` as an argument. This makes the first row of your received DataFrame the column names, otherwise will default to the default integer column names as 0, 1, 2, 3, etc. If the data being referenced in a ranged reference has headers, you should ALWAYS set first_row_header=True.

IMPORTANT: Use first_row_header when you have column names that you want as the header of the DataFrame. You can tell when a column name should be a header when the column name describes the data below. 

\`\`\`python
# first_row_header=True will be used any time the first row is the intended header for that data.
q.cells('A1:B9', first_row_header=True) # returns a 2x9 DataFrame with first row as DataFrame headers
\`\`\`

### Referencing another sheet

\`\`\`python
# Use the sheet name as an argument for referencing range of cells 
q.cells("'Sheet_name_here'!A1:C9", first_row_header=True)

# For individual cell reference 
q.cells("'Sheet_name_here'!A1")

# Since tables are global to a file, they can be referenced across sheets without defining sheet name
q.cells("Table1")
\`\`\`

### Relative vs absolute references

By default when you copy paste a reference it will update the row reference unless you use $ notation in your references. 

\`\`\`python
# Copy pasting this one row down will change reference to A2
q.cells('A1')

# Copy pasting this one row down will keep reference as A1
q.cells('A$1')

# Example using ranges - row references will not change
q.cells('A$1:B$20)

# Only A reference will change when copied down
q.cells('A1:B$20')
\`\`\`

## Return data to the sheet

Return the data from your Python code to the spreadsheet.

By default, the last line of code is output to the spreadsheet. Primarily return results to the spreadsheet rather than using print statements; print statements do not get returned to the sheet.

Only one value or variable (single value, single list, single dataframe, single series, single chart, etc) can be returned per code cell. If you need to return multiple things, such as numerical results of an analysis and a chart, you should use multiple code cells, outputting the analysis in one cell and the chart in another.

IMPORTANT: only one value or variable being returned means that you cannot return some kind of data and a chart in the same cell. You should return the data in one cell and the chart in another. In all cases, only one value or variable can be returned to the sheet per code cell.

Tuples, dictionaries, and sets are not ideal return types because they are output to a single cell formatted as they would if printed.

### Single value

\`\`\`python
# create variable 
x = 5 

# last line of code gets returned to the sheet, so the value 5 gets returned
x
\`\`\`

### List of values 

Lists can be returned directly to the sheet.  
\`\`\`python
my_list = [1, 2, 3, 4, 5]

# Each value of list occupies their own cell respectively  
my_list
\`\`\`

### DataFrame

You can return your DataFrames directly to the sheet by putting the DataFrame's variable name as the last line of code. The DataFrame's column names will be returned to the sheet as table headers. If no columns are named, column headers will not display in the sheet. To display column headers in the sheet, name the headers. The columns can still be referenced by their default DataFrame integer values. 

\`\`\`python
# import pandas 
import pandas as pd
 
# create some sample data 
data = [['tom', 30], ['nick', 19], ['julie', 42]]
 
# Create the DataFrame
df = pd.DataFrame(data, columns=['Name', 'Age'])
 
# return DataFrame to the sheet with "Name" and "Age" as column headers
df
\`\`\`

DataFrame and series index will not be returned to the sheet. Reset index to return the index to the sheet. 

\`\`\`python
# use reset_index() method where df is the dataframe name
df.reset_index()
\`\`\`

This is necessary any time you use describe() method in Pandas or any method that returns a named index.

### Charts

\`\`\`python
import plotly.express as px

# replace this df with your data
df = px.data.gapminder().query("country=='Canada'")

# create your chart type
fig = px.line(df, x="year", y="lifeExp", title='Life expectancy in Canada')

# display chart in sheet
fig.show()
\`\`\`

### Function outputs

You can not use the \`return\` keyword to return data to the sheet. 
Here is an example of successfully using a Python function to return data to the sheet. 

\`\`\`python
def do_some_math(x): 
    return x+1

# since this is the last line of code, it returns the result of do_some_math(), which in this case is 6 
do_some_math(5)
\`\`\`

Note that conditionals will not return the value to the sheet if the last line is a conditional. The following is an example that will return nothing to the sheet:

NEGATIVE EXAMPLE:  
\`\`\`python
x = 3
y = 0 
if x == 3: 
    y = True
else: 
    y = False
\`\`\`

The following is a positive example of how you would return the result of that conditional to the sheet.
 
\`\`\`python
x = 3
y = 0 
if x == 3: 
    y = True
else: 
    y = False
y
\`\`\`

Do NOT try to use try-except blocks. It is much more useful to simply return the output to the sheet and let the error surface in the sheet and the console. 

If you create an error and need to see the data, a print statement (e.g. print(df.head(3)) of the data can allow you to see the data to continue with a more useful result. 

Negative example: 
\`\`\`python
x = 3

try: 
    x += 1 
except: 
    print('error')
\`\`\`

Instead, simply return the output to the sheet. If an error occurs it will surface to the sheet and the console correctly. Never use try-except blocks.
Positive example: 
\`\`\`python
x = 3

# since this is the last line of code, it returns the result of x + 1, which in this case is 4 
x += 1
\`\`\`

### Formatting 

Do NOT try to use formatting options like f-strings (f"") or .format() on numerical return types. Returning formatted data will not flow through to the sheet; the sheet will read formatted numerical values as strings, keeping formatting options like currencies and significant digits from working on the returned values. 

### Supported sizes 

When returning DataFrames, default to returning the entire DataFrame. Do not use df.head() unless the user asks for it. The spreadsheet can comfortably handle a few million rows of data.

### Return single item per code cell 

You can only return a single item per code cell. For example, you can only return one table or one chart etc. You cannot return both a table and a chart to the sheet from the same cell. You cannot return multiple tables nor multiple charts from the same cell. Use individual code cells for each subsequent step you want to return to the sheet.

IMPORTANT: THIS IS AN EXAMPLE OF BAD CODE. IT WILL ONLY RETURN THE RAW CORRELATION VALUES AND NOT THE CHART! IF YOU WANT THE CHART AS WELL, CREATE A SEPARATE CODE CELL.

\`\`\`python
import pandas as pd
import numpy as np
import plotly.express as px
import plotly.graph_objects as go
from plotly.subplots import make_subplots

# Get the Walmart sales data
df = q.cells("Sales_Data", first_row_header=True)

# Calculate correlation matrix
correlation_matrix = df[['Weekly_Sales', 'Temperature', 'Fuel_Price', 'CPI', 'Unemployment']].corr()

# Create correlation heatmap
fig = px.imshow(correlation_matrix,
               color_continuous_scale='RdBu_r',
               zmin=-1, zmax=1,
               title='Correlation Matrix: Unemployment vs Other Variables',
               text_auto=True)

fig.update_layout(width=600, height=500)
fig.show()

# Return the correlation matrix for reference
correlation_matrix.round(3)
\`\`\`

NOTE THAT IN THE ABOVE EXAMPLE, ONLY THE CORRELATION MATRIX IS RETURNED TO THE SHEET. THE CHART DOES NOT GET SHOWN SINCE IT IS NOT THE LAST LINE OF CODE. IF YOU WANT THE CHART AS WELL, CREATE A SEPARATE CODE CELL. ONLY ONE ITEM CAN BE RETURNED TO THE SHEET PER CODE CELL.

## Packages

Using and installing Python packages.

### Default Packages

Some libraries are included by default, here are some examples (note that they need to be imported in every cell they are used even though they're included by default):

* Pandas 
* NumPy 
* SciPy 
* Plotly
* Scikit-learn
* Statsmodels
* Nltk
* Regex

Default packages can be imported like any other native Python package.

\`\`\`python
import pandas as pd
import numpy as np 
import scipy
\`\`\`

### Additional packages

Micropip can be used to install additional Python packages that aren't automatically supported. 

\`\`\`python
import micropip

# \`await\` is required to wait until the package is installed
await micropip.install("faker")

from faker import Faker

fake = Faker()
fake.name()
\`\`\`

This only works for packages that are either pure Python or for packages with C extensions that are built in Pyodide.

If you receive the following error then the library is likely not available in Quadratic or you've misspelled the library name: 
"Can't find a pure Python 3 wheel."

## API requests

API Requests in Python must use the Requests library.

## Charts/visualizations

Plotly is the ONLY charting library supported in Quadratic. 

You cannot return multiple charts from the same cell. You must return each chart in a separate code cell or use Plotly subplots to show multiple charts in the same cell.

### Trendlines 

When using Trendlines in Plotly you MUST import statsmodels for the trendline to work. Note an example trendline below.

\`\`\`python
import plotly.express as px
import pandas as pd
import statsmodels

# Get the data
df = q.cells("concrete_data")

# Create scatter plot
fig = px.scatter(df, x='age', y='strength', 
                title='Concrete Strength vs Age',
                # THIS LINE CREATES THE REQUIREMENT FOR STATSMODELS
                trendline="lowess")

# Update layout
fig.update_layout(
    xaxis_title="Age (days)",
    yaxis_title="Strength (MPa)",
    plot_bgcolor='white'
)

fig.show()
\`\`\`

## Time-series analysis

For time-series analysis a good starting point is using statsmodels library for a simple ARIMA analysis. You can reference sheet data using table and sheet references to build these kinds of analysis.

\`\`\`python
import pandas as pd
import numpy as np
import plotly.express as px
import plotly.graph_objects as go
from .tsa.arima.model import ARIMA
from statsmodels.tsa.stattools import adfuller

# Generate sample time series data
dates = pd.date_range(start='2023-01-01', end='2023-12-31', freq='D')
np.random.seed(42)
values = np.random.normal(loc=100, scale=10, size=len(dates))
values = np.cumsum(values)

# Create DataFrame
df = pd.DataFrame({
    'Date': dates,
    'Value': values
})

# Fit ARIMA model
model = ARIMA(df['Value'], order=(1,1,1))
results = model.fit()

# Make predictions
forecast = results.get_forecast(steps=30)
forecast_mean = forecast.predicted_mean
forecast_dates = pd.date_range(start=dates[-1], periods=31)[1:]

# Create plot with original data and forecast
fig = go.Figure()

# Add original data
fig.add_trace(go.Scatter(x=dates, y=values, name='Original Data'))

# Add forecast
fig.add_trace(go.Scatter(x=forecast_dates, y=forecast_mean, 
                        name='ARIMA Forecast',
                        line=dict(dash='dash')))

# Update layout
fig.update_layout(
    title='Time Series with ARIMA(1,1,1) Forecast',
    xaxis_title='Date',
    yaxis_title='Value',
    plot_bgcolor='white'
)

fig.show()
\`\`\`

## Machine learning

For machine learning, Scikit-learn is recommended. Here's a simple sklearn example. 

When generating scikit-learn examples it helps to add a visualization, but it is not strictly required.

\`\`\`python
import pandas as pd
import numpy as np
import plotly.graph_objects as go
from sklearn.model_selection import train_test_split
from sklearn.linear_model import LogisticRegression
from sklearn.metrics import accuracy_score

# Load data from the Sample_Data table
df = q.cells("Sample_Data_Table")

# Extract features and target
X = df[['Feature1', 'Feature2']].values
y = df['Target'].astype(int).values  # Convert target to integers

# Split data into training and testing sets
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.3, random_state=42)

# Create and train the model
model = LogisticRegression()
model.fit(X_train, y_train)

# Make predictions
y_pred = model.predict(X_test)

# Calculate accuracy
accuracy = accuracy_score(y_test, y_pred)

# Create a meshgrid for visualization
x_min, x_max = X[:, 0].min() - 0.5, X[:, 0].max() + 0.5
y_min, y_max = X[:, 1].min() - 0.5, X[:, 1].max() + 0.5
xx, yy = np.meshgrid(np.arange(x_min, x_max, 0.01),
                     np.arange(y_min, y_max, 0.01))

# Get predictions for the meshgrid
Z = model.predict(np.c_[xx.ravel(), yy.ravel()])
Z = Z.reshape(xx.shape)

# Create plot with decision boundary
fig = go.Figure()

# Add decision boundary contour
fig.add_trace(
    go.Contour(
        z=Z,
        x=np.arange(x_min, x_max, 0.01),
        y=np.arange(y_min, y_max, 0.01),
        showscale=False,
        colorscale='RdBu',
        opacity=0.4,
        contours=dict(showlines=False)
    )
)

# Add scatter points for class 0
fig.add_trace(
    go.Scatter(
        x=X[y==0, 0],
        y=X[y==0, 1],
        mode='markers',
        name='Class 0',
        marker=dict(color='blue', size=10)
    )
)

# Add scatter points for class 1
fig.add_trace(
    go.Scatter(
        x=X[y==1, 0],
        y=X[y==1, 1],
        mode='markers',
        name='Class 1',
        marker=dict(color='red', size=10)
    )
)

# Update layout
fig.update_layout(
    title=f'Logistic Regression Decision Boundary (Accuracy: {accuracy:.2f})',
    xaxis_title='Feature 1',
    yaxis_title='Feature 2',
    plot_bgcolor='white'
)

fig.show()
\`\`\`

## Correlations

Do not attempt to build a correlation analysis unless the user asks for it. 

Note there are two code examples here. A good correlation analysis will have two code cells generated - the first is for the correlations, the second visualizes in a heatmap.

First code block finds the correlations. 
\`\`\`python
import pandas as pd
import numpy as np

# Get the stock data
df = q.cells("Stock_Market_Data")

# Calculate daily returns for each stock (better for correlation analysis than raw prices)
stock_columns = ['AAPL', 'MSFT', 'GOOGL', 'AMZN', 'META']
df_returns = df.copy()

for col in stock_columns:
    df_returns[f'{col}_return'] = df[col].pct_change() * 100

# Drop the first row (which has NaN returns) and keep only return columns
df_returns = df_returns.drop(columns=stock_columns + ['Date']).dropna()

# Calculate correlation matrix of returns
correlation_matrix = df_returns.corr()

# Round the result that is returned to the sheet so it is more readable 
correlation_matrix.round(3)
\`\`\`

Second code block visualizes the correlations in a heatmap since only one item can be returned to the sheet per code cell.
\`\`\`python
import plotly.express as px

# Get the correlation matrix from previous code - previous code outputs table is named "Python2"
df = q.cells("Python2")

# Create a heatmap visualization
fig = px.imshow(df,
               color_continuous_scale='RdBu_r',
               zmin=-1, zmax=1,
               title='Stock Returns Correlation Matrix')

fig.update_layout(
    xaxis_title='Stock',
    yaxis_title='Stock',
    coloraxis_colorbar=dict(
        title='Correlation',
    ),
    plot_bgcolor='white'
)

# Display the heatmap
fig.show()
\`\`\`

## File imports and exports
Python can NOT be used to import files like .xlsx, .pqt, .csv. Users should import xlsx, .pqt, and csv files to Quadratic by drag and dropping them directly into the sheet. They can then be read into Python with q.cells(). Python can not be used to import files (.xlsx, .csv, .pqt, etc).

To import PDF and image files, insert them to the AI chat with the paperclip attach button, copy/paste, or drag and drop directly in the chat. PDF and image files can not be imported via Python. Once in the sheet, they can be analyzed by first being read into Python with q.cells().

Python can also not be used to export/download data as various file types. To download data from Quadratic, highlight the data you'd like to download, right click, and select the "Download as CSV" button.

## Sentiment analysis 

For sentiment analysis, NLTK is recommended. Here's a simple NLTK example.

\`\`\`python
import nltk
from nltk.sentiment import SentimentIntensityAnalyzer
import pandas as pd

# Download required NLTK data
nltk.download('vader_lexicon')

# Get text data and create DataFrame
text_data = q.cells('A1:A3')
df = pd.DataFrame(text_data).rename(columns={0: 'Text'})

# Initialize the NLTK sentiment analyzer
sia = SentimentIntensityAnalyzer()

# Analyze sentiment
df['Sentiment_Scores'] = df['Text'].apply(lambda x: sia.polarity_scores(x)['compound'])

# Define sentiment categories
df['Sentiment'] = df['Sentiment_Scores'].apply(lambda x: 'Positive' if x > 0.05 
                                             else ('Negative' if x < -0.05 
                                             else 'Neutral'))

# Return the resulting DataFrame
df
\`\`\`

## Web scraping

You should use Beautifulsoup4 for web scraping.

Here is a successful example of web scraping. 
\`\`\`python
# Import necessary libraries
import requests
import pandas as pd
import micropip

# Install BeautifulSoup4
await micropip.install('beautifulsoup4')
from bs4 import BeautifulSoup

# URL of the Denver Nuggets Wikipedia page
url = 'https://en.wikipedia.org/wiki/Denver_Nuggets'

# Send a GET request to fetch the webpage
response = requests.get(url)

# Parse the HTML content
soup = BeautifulSoup(response.content, 'html.parser')

# Extract the page title
title = soup.find('h1', {'id': 'firstHeading'}).text
print(f"Page title: {title}")

# Extract team information from the infobox
infobox = soup.find('table', {'class': 'infobox'})

# Initialize lists to store the data
info_labels = []
info_values = []

# Extract data from the infobox
if infobox:
    rows = infobox.find_all('tr')
    for row in rows:
        header = row.find('th')
        data = row.find('td')
        if header and data:
            info_labels.append(header.text.strip())
            info_values.append(data.text.strip())

# Create a DataFrame with the extracted information
nuggets_info = pd.DataFrame({
    'Category': info_labels,
    'Information': info_values
})

# Extract section headers for team history
section_titles = []
section_ids = []

for heading in soup.find_all(['h2', 'h3']):
    if heading.get('id'):
        section_titles.append(heading.text.strip())
        section_ids.append(heading.get('id'))

sections_df = pd.DataFrame({
    'Section': section_titles,
    'ID': section_ids
})

# Return the team information DataFrame
nuggets_info
\`\`\`

## Summarizing data 

\`\`\`python
import pandas as pd
import plotly.express as px

# This example references a table named "Sales_Data"
df = q.cells("Sales_Data")

# Explicitly set types to what they should be for each DataFrame column
df['Units_Sold'] = pd.to_numeric(df['Units_Sold'])
df['Revenue'] = pd.to_numeric(df['Revenue'])
df['Cost'] = pd.to_numeric(df['Cost'])
df['Profit'] = pd.to_numeric(df['Profit'])
df['Date'] = pd.to_datetime(df['Date'])

# Generate a statistical summary
summary = df.describe().reset_index()

# Add additional metrics
product_summary = df.groupby('Product').agg({
    'Units_Sold': 'sum',
    'Revenue': 'sum',
    'Profit': 'sum'
}).reset_index()

region_summary = df.groupby('Region').agg({
    'Units_Sold': 'sum',
    'Revenue': 'sum',
    'Profit': 'sum'
}).reset_index()

# Return the summary statistics
summary
\`\`\`

## Reading JSON strings

It is advised before reading a JSON string to print an example so you can see the format of the data before trying to process it into the sheet. 
`;
```

### quadratic-api/src/ai/docs/JavascriptDocs.ts

- **Purpose**: Docs injected: JavascriptDocs
- **GitHub**: https://github.com/quadratichq/quadratic/blob/main/quadratic-api/src/ai/docs/JavascriptDocs.ts
- **Raw**: https://raw.githubusercontent.com/quadratichq/quadratic/main/quadratic-api/src/ai/docs/JavascriptDocs.ts

```ts
export const JavascriptDocs = `# Javascript Docs

## Referencing tables (and named outputs)

\`\`\`javascript
// Reference full table 
let x = q.cells("Table_name")

// Single table column 
let x = q.cells("Table_name[column_name]")

// Table headers
let x = q.cells("Table_name[#HEADERS]")

// Range of columns in a table
let x = q.cells("Table_name[[Column_name]:[Column_name]]")
\`\`\`

## Referencing individual cells

\`\`\`javascript
// single value
let x = q.cells('A1')

// return statement gets returned in JavaScript 
return x;
\`\`\`

## Referencing a range of cells

To reference a range of cells, use the global function \`q.cells\`. This returns an array.

\`\`\`javascript
let x = q.cells('A1:A5') // Returns a 1x5 array spanning from A1 to A5
\`\`\`

## Referencing another sheet

\`\`\`javascript
// Use the sheet name as an argument for referencing range of cells 
let x = q.cells("'Sheet_name_here'!A1:C9")

// For individual cell reference 
let x = q.cells("'Sheet_name_here'!A1")
\`\`\`

## Return data to the sheet

\`\`\`javascript
let data = 5; 

// return this value to the sheet
return data;
\`\`\`

1-d array 

\`\`\`javascript
let data = [1, 2, 3, 4, 5];

return data;
\`\`\`

2-d array 

\`\`\`javascript
let data = [[1,2,3,4,5],[1,2,3,4,5]];

return data;
\`\`\`

## Charts

Chart.js is the only JavaScript charting library supported. 

\`\`\`javascript
import Chart from 'https://esm.run/chart.js/auto';

let canvas = new OffscreenCanvas(800, 450);
let context = canvas.getContext('2d');

// create data 
let data = [['Africa', 'Asia', 'Europe', 'Latin America', 'North America'],[2478, 5267, 734, 784, 433]]

// print data to console 
console.log(data);

// Create chart 
new Chart(canvas, {
    type: 'bar',
    data: {
        labels: data[0],
        datasets: [
        {
            label: "Population (millions)",
            backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#e8c3b9","#c45850"],
            data: data[1]
        }
        ]
    },
    options: {
        legend: { display: false },
        title: {
        display: true,
        text: 'Predicted world population (millions) in 2050'
        }
    }
});

// return chart to the sheet 
return canvas;
\`\`\`

## API Requests

How to make API requests in JavaScript.

\`\`\`javascript
// API for get requests
let res = await fetch("https://jsonplaceholder.typicode.com/todos/1");
let json = await res.json();

console.log(json);

return [Object.keys(json), Object.values(json)];
\`\`\`

GET request with error handling 

\`\`\`javascript
async function getData() {
  const url = "https://jsonplaceholder.typicode.com/todos/1";
  try {
    const response = await fetch(url);
    if (!response.ok) {
      throw new Error(\`Response status: \${response.status}\`);
    }

    const json = await response.json();
    // Return the JSON object as a 2D array
    return [Object.keys(json), Object.values(json)];
  } catch (error) {
    console.error(error.message);
    // Return the error message to the sheet
    return \`Error: \${error.message}\`;
  }
}

// Call the function and return its result to the sheet
return await getData();
\`\`\`

## Packages

Packages in Javascript are supported using ESM. You can use a third-party JS CDN to load third-party packages. Some possible CDNs include: 

* We recommend using esm.run from https://www.jsdelivr.com/esm
* https://www.unpkg.com
* https://esm.sh

Below are examples on how to correctly use esm.run to import packages in JavaScript. 

Chart.js is the only charting library in Javascript supported in Quadratic. 

\`\`\`javascript
import Chart from 'https://esm.run/chart.js/auto';
\`\`\`

Analytics

D3.js is a common analytics library for JavaScript.

\`\`\`javascript
import * as d3 from 'https://esm.run/d3';

let my_data = [1,2,3]
let sum = d3.sum(my_data)
return sum
\`\`\`

Brain.js is a Machine Learning library that works in Quadratic

\`\`\`javascript
import * as brain from 'https://esm.run/brain.js';

// provide optional config object (or undefined). Defaults shown.
const config = {
  binaryThresh: 0.5,
  hiddenLayers: [3], // array of ints for the sizes of the hidden layers in the network
  activation: 'sigmoid', // supported activation types: ['sigmoid', 'relu', 'leaky-relu', 'tanh'],
  leakyReluAlpha: 0.01, // supported for activation type 'leaky-relu'
};

// create a simple feed-forward neural network with backpropagation
const net = new brain.NeuralNetwork(config);

await net.train([
  { input: [0, 0], output: [0] },
  { input: [0, 1], output: [1] },
  { input: [1, 0], output: [1] },
  { input: [1, 1], output: [0] },
]);

const output = net.run([1, 0]); // [0.987]

return output[0]
\`\`\`

## File imports and exports
JavaScript can not be used to import files like .xlsx or .csv. Users should import those files directly to Quadratic by drag and dropping them directly into the sheet. They can then be read into JavaScript with q.cells(). JavaScript can not be used to import files (.xlsx, .csv, .pqt, etc).

JavaScript can also not be used to export/download data as various file types. To download data from Quadratic highlight the data you'd like to download, right click, and select the "Download as CSV" button.
`;
```

### quadratic-api/src/ai/docs/FormulaDocs.ts

- **Purpose**: Docs injected: FormulaDocs
- **GitHub**: https://github.com/quadratichq/quadratic/blob/main/quadratic-api/src/ai/docs/FormulaDocs.ts
- **Raw**: https://raw.githubusercontent.com/quadratichq/quadratic/main/quadratic-api/src/ai/docs/FormulaDocs.ts

```ts
export const FormulaDocs = `# Formula Docs

Formulas in Quadratic work the same as in any traditional spreadsheet. 

## Formula references 
Formulas are relatively referenced by default, with $ notation to support absolute references. 

Formulas can reference data both in and out of tables using standard A1 notation. 

### Formula reference examples 

=SUM(A1:A10)
=SUM(Table1[Column 1])
=SUM(Sheet1!A1:A10)
=SUM(Sheet1!Table1[Column 1])
`;
```

### quadratic-api/src/ai/docs/ConnectionDocs.ts

- **Purpose**: Docs injected: ConnectionDocs
- **GitHub**: https://github.com/quadratichq/quadratic/blob/main/quadratic-api/src/ai/docs/ConnectionDocs.ts
- **Raw**: https://raw.githubusercontent.com/quadratichq/quadratic/main/quadratic-api/src/ai/docs/ConnectionDocs.ts

```ts
export const ConnectionDocs = `# Connections Docs

Use SQL to create connections from spreadsheets to databases and data warehouses.

You can read from databases in Quadratic. The data read from a SQL cell is directly written to the sheet as a code table.

IMPORTANT: DO NOT under any circumstance perform SQL queries that write to the database unless a user asks for it; only perform reads by default.

You cannot do two queries at once in SQL in Quadratic. For example, you can not create a table and then query that table in the same SQL query. You'll want to generate two distinct code blocks if two queries are involved. Or 3 code blocks if three queries are involved, etc.

## SQL syntax

There are some slight differences between SQL syntax across databases to keep in mind:
* In Postgres it is best practice use quotes around table names and column names.
* In MySQL it is best practice to use backticks around table names and column names.
* In MS SQL Server it is best practice to use double quotes around table names and column names.
* In Snowflake it is best practice to use double quotes around table names and column names.
* BIGQUERY uses Standard SQL with nested and repeated fields, requiring backticks for table references and GoogleSQL functions for analytics\n
* COCKROACHDB, SUPABASE and NEON have the same syntax as POSTGRES
* MARIADB has the same syntax as MySQL
* SYNCED and MIXPANEL use readonly ANSI SQL syntax. It does not support CREATE, ALTER, DROP, or INSERT statements.

In PostgreSQL, identifiers like table names and column names that contain spaces or are reserved keywords need to be enclosed in double quotes.

## SQL references

You can create parametrized SQL queries that reference sheet data by using {{}} notation. This may include one cell, or a 1d range of cells. For example, if you want to reference the cell A1, you would use {{A1}}. If you want to reference the cells A1:A5, you would use {{A1:A5}}.

Note, this will add the naked values of the cells to the query. It will not place quotation marks around those values. So if the SQL query needs quotation marks, you will need to add them yourself (e.g., '{{A1}}').

You may also reference table columns using the A1 table column reference, eg, {{Table1[Column name]}}. When referencing a table column, Quadratic will insert the values as a comma-delimited list (e.g., 123,456,789). Your SQL query must account for this format.

IMPORTANT: Since Quadratic inserts raw comma-delimited values without quotes, this works well for numeric values with the IN clause. For string values, you'll need to use database-specific functions:
- MySQL/MariaDB: FIND_IN_SET()
- PostgreSQL/CockroachDB/Supabase/Neon: string_to_array() with = ANY or UNNEST
- MS SQL Server: STRING_SPLIT()
- BigQuery: SPLIT() with UNNEST
- Snowflake: SPLIT() with ARRAY_CONTAINS or IN with TABLE(FLATTEN())

If you're working with a connection type not listed above, you'll need to research how that specific database handles comma-delimited string values in SQL queries. Look for string splitting or array functions that can convert the naked comma-delimited list into a format that can be used with IN clauses or comparison operators.

### Examples

#### Single Cell References

Parametrized queries in SQL can read single cells from the file. They can only be read using A1 notation.

\`\`\`sql
SELECT * FROM {{A1}} WHERE {{column_name}} = {{Sheet2!B7}}
\`\`\`

#### MySQL Examples

\`\`\`mysql
-- For numeric values, use IN clause
SELECT * FROM \`users\` WHERE \`user_id\` IN ({{Table1[User ID]}})

-- For string values, use FIND_IN_SET (searches if column value exists in the comma-delimited list)
SELECT * FROM \`users\` WHERE FIND_IN_SET(\`email\`, '{{Table1[Email]}}') > 0
\`\`\`

#### PostgreSQL Examples (also applies to CockroachDB, Supabase, Neon)

\`\`\`sql
-- For numeric values, use IN clause
SELECT * FROM "users" WHERE "user_id" IN ({{Table1[User ID]}})

-- For string values, use = ANY with string_to_array
SELECT * FROM "users" WHERE "email" = ANY(string_to_array('{{Table1[Email]}}', ','))

-- Alternative for strings: use IN with UNNEST
SELECT * FROM "users" WHERE "email" IN (SELECT unnest(string_to_array('{{Table1[Email]}}', ',')))
\`\`\`

#### MS SQL Server Examples

\`\`\`sql
-- For numeric values, use IN clause
SELECT * FROM "users" WHERE "user_id" IN ({{Table1[User ID]}})

-- For string values, use STRING_SPLIT (SQL Server 2016+)
SELECT * FROM "users" WHERE "email" IN (SELECT value FROM STRING_SPLIT('{{Table1[Email]}}', ','))
\`\`\`

#### BigQuery Examples

\`\`\`sql
-- For numeric values, use IN clause
SELECT * FROM \`project.dataset.users\` WHERE \`user_id\` IN ({{Table1[User ID]}})

-- For string values, use SPLIT
SELECT * FROM \`project.dataset.users\` WHERE \`email\` IN UNNEST(SPLIT('{{Table1[Email]}}', ','))
\`\`\`

#### Snowflake Examples

\`\`\`sql
-- For numeric values, use IN clause
SELECT * FROM "users" WHERE "user_id" IN ({{Table1[User ID]}})

-- For string values, use ARRAY_CONTAINS with SPLIT
SELECT * FROM "users" WHERE ARRAY_CONTAINS("email"::VARIANT, SPLIT('{{Table1[Email]}}', ','))

-- Alternative for strings: use IN with TABLE(FLATTEN())
SELECT * FROM "users" WHERE "email" IN (SELECT value::STRING FROM TABLE(FLATTEN(SPLIT('{{Table1[Email]}}', ','))))
\`\`\`

## Getting Schema from Database

Use the get_database_schemas tool to get the schema of a database.
`;
```

### quadratic-api/src/ai/docs/A1Docs.ts

- **Purpose**: Docs injected: A1Docs
- **GitHub**: https://github.com/quadratichq/quadratic/blob/main/quadratic-api/src/ai/docs/A1Docs.ts
- **Raw**: https://raw.githubusercontent.com/quadratichq/quadratic/main/quadratic-api/src/ai/docs/A1Docs.ts

```ts
export const A1Docs = `# A1 Docs

Cell references in Quadratic are in A1 notation.

## Rectangular Ranges

Rectangular ranges are references to a range of cells in a sheet. They are referenced by the top left cell and the bottom right cell. For example, A1:C3 is a rectangular range that references the cells A1, B1, C1, A2, B2, C2, A3, B3, and C3.

### Rectangular Range Examples

- D5 - references the cell D5
- A1:C3 - references the cells A1, B1, C1, A2, B2, C2, A3, B3, and C3
- A1:A3 - references the cells A1, A2, and A3
- A1:C1 - references the cells A1, B1, and C1

## Column and Row References

In A1 notation, when referencing an entire column or row, use the column or row name(s).

### Column and Row Examples

- B - references the entire column B
- B3:B - references all rows in column B starting from row 3
- 10 - references the entire row 10
- C4:C - references all columns in row C starting from row 4
- D2:2 - references all columns in row 2 starting from column D

## Table References

**PREFERRED**: Always use table names (e.g., Table_Name) when working with entire tables or entire columns within tables. Use A1 notation only for non-table data or partial table selections.

Columns within tables may be referenced by their name in A1 notation, eg, Table1[Column Name]. To reference multiple columns within a table, you use Table1[[Name]:[Address]]. In tables, you can also reference parts of the table. If you only want the table names, you can reference it with Table1[#HEADERS]. If you want the data and the headers, you would use Table1[[#DATA],[#HEADERS]]. By default, tables are referenced as Table1[#DATA].

If you need individual cells within a table, you need to use normal A1 reference. For example, if you want the first row of a table, you would reference it using its corresponding A1 reference. Remember that tables usually include a name row as the first row, and a column header row as the second row. (Although these may sometimes be hidden.)

### Table Examples

- Table1 - references the entire table's data
- Table1[#HEADERS] - references the table headers
- Table1[[#DATA],[#HEADERS]] - references the entire table including the headers
- Table1[Column 2] - references a single column within Table1
- Table1[[Name]:[Address]] - references a range of columns

## Multiple Ranges

 You can reference multiple ranges by combining ranges with commas.

### Multiple Range Examples

- A1:C3,D5:F7
- A1:C3,D5:F7,G9:I11
- Table1[Column 2], A1:C3

## Other Sheets

You can reference cells in other sheets by using the sheet name as part of the reference. Note, table names do not need sheet names as table names are unique within the file.

### Other Sheet Examples

- Sheet1!A1:C3
- Sheet1!A1:C3,Sheet2!D5:F7
- Sheet1!A1:C3,Sheet2!D5:F7,Sheet3!G9:I11
- Table1[Column 2],Sheet2!A1:C3
`;
```

### quadratic-api/src/ai/docs/ValidationDocs.ts

- **Purpose**: Docs injected: ValidationDocs
- **GitHub**: https://github.com/quadratichq/quadratic/blob/main/quadratic-api/src/ai/docs/ValidationDocs.ts
- **Raw**: https://raw.githubusercontent.com/quadratichq/quadratic/main/quadratic-api/src/ai/docs/ValidationDocs.ts

```ts
export const ValidationDocs = `
# Validations

## Overview

Validations are used to both verify that cells have allowable data, and to provide messages to the user when they enter a cell.

## How validations are stored

Validations are set per sheet so you cannot add a selection from another sheet to a validation in a different sheet.

## One validation per cell

Each cell can only have one validation. If you add a validation to a range then any validations that overlap with the new validation will be removed. The algorithm either changes the selection to remove the overlap, or, if the selection becomes empty, removes the validation.

## Messages and errors

Adding a message to validations will show the message when the user enters the cell with the cursor. An error will show when the user either enters the cell or hovers over it with the cursor.

## Purpose of validations

You can use validations to create checkboxes and dropdown lists, as well as to add messages or errors to the user when they enter a cell. They make the user experience more interactive and engaging.`;
```
