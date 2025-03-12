let questionCount = 0;
let categoryCount = 0;
let categories = [];

// Tipos de resposta fixos e pré-definidos
const fixedAnswerTypes = [
  'Texto',
  'Múltiplas Escolhas',
  'Verdadeiro/Falso',
  'Email',
  'Data',
  'Número',
  'Telefone',
  'Arquivo'
];

// Função para adicionar uma nova categoria
function addCategory() {
  categoryCount++;
  const categoryContainer = document.createElement('div');
  categoryContainer.classList.add('category');
  categoryContainer.setAttribute('id', 'category-' + categoryCount);

  categoryContainer.innerHTML = `
    <input type="text" id="category-name-${categoryCount}" placeholder="Digite o nome da categoria" required>
    <button type="button" onclick="removeCategory(${categoryCount})">Remover Categoria</button>
  `;

  document.getElementById('category-list').appendChild(categoryContainer);
}

// Função para remover uma categoria
function removeCategory(categoryId) {
  const category = document.getElementById('category-' + categoryId);
  category.remove();
  categories = categories.filter((_, index) => index !== categoryId - 1);
}

// Função para adicionar uma nova pergunta
function addQuestion() {
  questionCount++;
  const questionContainer = document.createElement('div');
  questionContainer.classList.add('question');
  questionContainer.setAttribute('id', 'question-' + questionCount);

  // Criar dropdown para categorias
  const categoryOptions = categories.map(category => `<option value="${category}">${category}</option>`).join('');

  questionContainer.innerHTML = `
    <label for="question-${questionCount}">Pergunta ${questionCount}</label>
    <input type="text" id="question-text-${questionCount}" placeholder="Digite a pergunta" required>
    
    <label for="question-category-${questionCount}">Categoria</label>
    <select id="question-category-${questionCount}">
      <option value="">Selecione uma Categoria</option>
      ${categoryOptions}
    </select>
    
    <label for="question-type-${questionCount}">Tipo de Resposta</label>
    <select id="question-type-${questionCount}" onchange="toggleAnswerOptions(${questionCount})">
      ${fixedAnswerTypes.map(type => `<option value="${type}">${type}</option>`).join('')}
    </select>

    <div class="answer-options hidden" id="answer-options-${questionCount}">
      <label>Opções de Resposta</label>
      <input type="text" placeholder="Resposta 1">
      <input type="text" placeholder="Resposta 2">
      <button type="button" onclick="addAnswerOption(${questionCount})">Adicionar Opção</button>
    </div>

    <div id="conditional-question-${questionCount}" class="conditional-questions"></div>

    <label for="add-next-question-${questionCount}">Deseja adicionar outra pergunta?</label>
    <select id="add-next-question-${questionCount}" onchange="handleAddNextQuestion(${questionCount})">
      <option value="não">Não</option>
      <option value="sim">Sim</option>
    </select>
  `;

  document.getElementById('questions-container').appendChild(questionContainer);

  // Atualizar a visibilidade das opções de resposta
  toggleAnswerOptions(questionCount);
}

// Função para adicionar opções de resposta
function addAnswerOption(questionId) {
  const answerOptionsDiv = document.getElementById('answer-options-' + questionId);
  const newOption = document.createElement('input');
  newOption.type = 'text';
  newOption.placeholder = `Resposta ${answerOptionsDiv.children.length - 1}`;
  answerOptionsDiv.insertBefore(newOption, answerOptionsDiv.lastElementChild);
}

// Função para alternar a visibilidade das opções de resposta
function toggleAnswerOptions(questionId) {
  const questionType = document.getElementById('question-type-' + questionId).value;
  const answerOptionsDiv = document.getElementById('answer-options-' + questionId);

  // Exibir as opções de resposta apenas para perguntas de "Múltiplas Escolhas"
  if (questionType === 'Múltiplas Escolhas') {
    answerOptionsDiv.classList.remove('hidden');
  } else {
    answerOptionsDiv.classList.add('hidden');
  }

  // Se a pergunta for do tipo "Verdadeiro/Falso", mostrar a lógica condicional
  if (questionType === 'Verdadeiro/Falso') {
    showConditionalQuestions(questionId);
  }
}

// Função para exibir perguntas condicionais
function showConditionalQuestions(questionId) {
  const questionType = document.getElementById('question-type-' + questionId).value;
  const conditionalQuestionContainer = document.getElementById('conditional-question-' + questionId);

  // Mostrar opções condicionais baseadas nas respostas
  if (questionType === 'Verdadeiro/Falso') {
    const conditionalQuestionHTML = `
      <label for="conditional-question-yes-${questionId}">Se a resposta for "Sim"</label>
      <input type="text" id="conditional-question-yes-${questionId}" placeholder="Escreva a pergunta relacionada ao 'Sim'">
      <label for="conditional-question-no-${questionId}">Se a resposta for "Não"</label>
      <input type="text" id="conditional-question-no-${questionId}" placeholder="Escreva a pergunta relacionada ao 'Não'">
    `;
    conditionalQuestionContainer.innerHTML = conditionalQuestionHTML;
  } else {
    conditionalQuestionContainer.innerHTML = '';
  }
}

// Função para lidar com a escolha de adicionar outra pergunta
function handleAddNextQuestion(questionId) {
  const addNextQuestionSelect = document.getElementById('add-next-question-' + questionId);
  const choice = addNextQuestionSelect.value;

  if (choice === 'sim') {
    // Adicionar a próxima pergunta automaticamente
    addQuestion();
  }
}

// Função para pré-visualizar o formulário
function previewForm() {
  let previewContent = 'Prévia do Formulário:\n\n';
  const formName = document.getElementById('formName').value;

  previewContent += `Formulário: ${formName}\n`;

  for (let i = 1; i <= questionCount; i++) {
    const questionText = document.getElementById('question-text-' + i).value;
    const questionCategory = document.getElementById('question-category-' + i).value;
    const questionType = document.getElementById('question-type-' + i).value;
    previewContent += `Pergunta ${i}: ${questionText} (${questionType})\n`;

    if (questionCategory) {
      previewContent += `  Categoria: ${questionCategory}\n`;
    }
    
    if (questionType === 'Múltiplas Escolhas') {
      const answerOptionsDiv = document.getElementById('answer-options-' + i);
      const options = answerOptionsDiv.getElementsByTagName('input');
      Array.from(options).forEach((option, index) => {
        previewContent += `  Resposta ${index + 1}: ${option.value}\n`;
      });
    }

    // Adicionar perguntas condicionais (se houver)
    const conditionalYes = document.getElementById('conditional-question-yes-' + i);
    const conditionalNo = document.getElementById('conditional-question-no-' + i);
    if (conditionalYes && conditionalYes.value) {
      previewContent += `  Condicional para "Sim": ${conditionalYes.value}\n`;
    }
    if (conditionalNo && conditionalNo.value) {
      previewContent += `  Condicional para "Não": ${conditionalNo.value}\n`;
    }
    
    previewContent += '\n';
  }

  alert(previewContent);
}
