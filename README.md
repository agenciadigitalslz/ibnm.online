# Sistema de Gestão para Igrejas - SAAS

## Visão Geral do Sistema Igreja

O Sistema de Gestão para Igrejas é uma solução SaaS (Software as a Service) desenvolvida para facilitar a administração completa de igrejas, suas filiais e ministérios. Projetado com foco na usabilidade e funcionalidade, o sistema oferece ferramentas robustas para gerenciamento de membros, finanças, eventos, patrimônios e comunicação, centralizando todas as operações administrativas em uma única plataforma acessível e intuitiva.

### Propósito

O sistema tem como propósito principal oferecer uma solução tecnológica que permita às igrejas modernizarem sua gestão administrativa, otimizando processos e proporcionando maior controle sobre informações cruciais, desde o gerenciamento de membros até o controle financeiro detalhado.

### Características Principais

- **Arquitetura Multinível**: Suporte para igreja matriz e filiais com hierarquia definida
- **Multiusuário**: Diferentes níveis de acesso com permissões personalizadas
- **Responsivo**: Interface adaptada para diversos dispositivos
- **Seguro**: 4 Sprints de Security Hardening (v7.00→v7.30) — Enterprise Grade, Defense in Depth
- **Customizável**: Adaptável às necessidades específicas de cada igreja
- **Modular**: Organizado em módulos independentes e integrados

## Estrutura do Sistema

### Arquitetura

O sistema está estruturado em duas áreas principais:

1. **Painel Administrativo**: Gerenciamento geral de todas as igrejas, controle de acesso e configurações globais
2. **Painel da Igreja**: Gestão específica de cada unidade (matriz ou filial)

### Banco de Dados

O sistema utiliza banco de dados relacional MySQL com tabelas bem estruturadas para:
- Igrejas (matriz e filiais)
- Usuários e permissões
- Membros e visitantes
- Finanças (receitas, despesas, dízimos, ofertas)
- Eventos e atividades
- Patrimônios e recursos

## Funcionalidades Detalhadas

### Gestão Administrativa

#### Igrejas
- Cadastro completo de igreja matriz e filiais
- Upload de logo e imagem para identidade visual
- Configurações específicas para cada unidade
- Dados completos de contato e localização
- Estatísticas gerais de cada igreja

#### Usuários e Permissões
- Níveis de acesso predefinidos (Administrador, Pastor, Tesoureiro, Secretário, Mídia)
- Permissões customizáveis por funcionalidade
- Registro detalhado de atividades (logs)
- Autenticação segura com senha criptografada

### Gestão de Pessoas

#### Membros
- Cadastro completo com dados pessoais, contato e foto
- Histórico de participação e atividades
- Registro de batismo e outras cerimônias
- Aniversariantes do mês com notificações
- Importação e exportação de dados

#### Visitantes
- Registro de pessoas que visitaram a igreja
- Acompanhamento de frequência
- Conversão de visitante para membro
- Fichas de visitante com feedback

#### Pastores, Tesoureiros e Secretários
- Cadastro específico com funções e responsabilidades
- Histórico de atividades
- Permissões especiais de acesso

### Gestão Financeira

#### Receitas
- Dízimos (identificados por membro)
- Ofertas (gerais ou específicas)
- Doações e contribuições
- Vendas e outras receitas
- Controle de contas a receber

#### Despesas
- Categorização de despesas
- Controle de contas a pagar
- Vencimentos e pagamentos pendentes
- Fornecedores cadastrados
- Histórico de pagamentos

#### Relatórios Financeiros
- Balanços mensais e anuais
- Movimentações financeiras
- Relatórios comparativos
- Fechamentos de caixa
- Exportação para formatos diversos

### Gestão de Atividades

#### Eventos
- Calendário de eventos e celebrações
- Pregações e cultos especiais
- Configuração de recorrência
- Divulgação automática
- Registro de participantes

#### Cultos
- Programação regular de cultos
- Escalas de ministração
- Registro de frequência
- Informações específicas para cada culto

#### Células e Grupos
- Cadastro de células e pequenos grupos
- Líderes e participantes
- Localização e horários
- Acompanhamento de crescimento
- Estatísticas de participação

### Gestão de Recursos

#### Patrimônios
- Inventário completo de bens
- Valor, data de aquisição e depreciação
- Localização de cada item
- Transferências entre unidades
- Baixa de patrimônios

#### Documentos
- Armazenamento de documentos importantes
- Organização por categorias
- Controle de acesso
- Versionamento

### Comunicação

#### Alertas
- Sistema de alertas internos
- Notificações importantes
- Data de expiração configurável
- Priorização de mensagens

#### Informativos
- Informativos de culto
- Comunicados para a comunidade
- Versículos e reflexões
- Materiais para mídia e divulgação

## Níveis de Acesso e Perfis

### Administrador do Sistema
- Acesso completo a todas as funcionalidades
- Gerenciamento de todas as igrejas
- Configurações globais e parâmetros
- Gestão de usuários e permissões

### Pastor
- Acesso à sua igreja específica
- Visualização de membros e atividades
- Relatórios gerais financeiros
- Aprovações e autorizações

### Tesoureiro
- Gestão financeira completa
- Lançamentos de receitas e despesas
- Dízimos e ofertas
- Relatórios financeiros detalhados

### Secretário
- Cadastro e atualização de membros
- Gestão de eventos e atividades
- Patrimônios e documentos
- Comunicações e informativos

### Mídia
- Acesso limitado a funções específicas
- Gestão de eventos e informativos
- Divulgação de conteúdo
- Versículos e materiais de comunicação

## Interface e Experiência do Usuário

### Design Responsivo
- Adaptação para desktop, tablet e smartphones
- Acessibilidade em diversos dispositivos
- Navegação intuitiva e organizada

### Dashboard
- Visualização rápida de estatísticas importantes
- Cards informativos modernos e intuitivos
- Gráficos e indicadores visuais
- Acesso rápido às funções mais utilizadas

### Temas e Personalização
- Modo claro e escuro disponíveis
- Cores e estilos personalizáveis
- Adaptação da identidade visual
- Customização de elementos da interface

## Recursos Técnicos

### Tecnologias Utilizadas
- PHP para backend
- MySQL para banco de dados
- JavaScript e jQuery para interações frontend
- Bootstrap para interface responsiva
- FontAwesome para iconografia
- ApexCharts para gráficos e estatísticas

### Segurança
- Autenticação segura de usuários
- Criptografia de dados sensíveis
- Proteção contra injeção SQL
- Backup automatizado
- Log de atividades e auditoria

### Performance
- Otimização de consultas ao banco de dados
- Carregamento rápido de páginas
- Armazenamento eficiente de imagens
- Limpeza automática de logs antigos

## Integração e Extensibilidade

### Relatórios Exportáveis
- Exportação para PDF
- Relatórios impressos formatados
- Dados exportáveis para análise externa

### Extensibilidade
- Arquitetura que permite adição de novos módulos
- Customização para necessidades específicas
- Possibilidade de integração com outros sistemas

## Benefícios do Sistema

### Para a Administração
- Centralização de informações
- Tomada de decisão baseada em dados
- Redução de erros administrativos
- Economia de tempo em processos manuais
- Segurança e backup de informações importantes

### Para a Comunidade
- Melhor acompanhamento pastoral
- Comunicação mais eficiente
- Transparência na gestão
- Melhor organização de eventos e atividades

## Implementações Recentes

### Cards Modernos
- Nova interface com cards modernos
- Efeitos visuais aprimorados
- Melhor visualização em modo escuro
- Organização visual mais eficiente

### Perfil de Mídia
- Novo nível de acesso específico para equipe de mídia
- Permissões customizadas para gerenciamento de conteúdo
- Ocultação de informações financeiras sensíveis
- Foco em comunicação e eventos

### Tema Escuro
- Implementação completa de tema escuro
- Ajustes de contraste e legibilidade
- Preservação das cores funcionais importantes
- Transição suave entre modos claro e escuro

## Alterações Realizadas pela Agência Digital SLZ (2024-2025)

### Alterações no Sistema (Back-end)

#### Março 2025
- Correção de bug na exibição de variáveis não definidas no painel administrativo e painel da igreja
- Implementação de melhorias de segurança no sistema de conexão com o banco de dados
- Otimização das consultas SQL para melhor desempenho

#### Abril 2025
- Integração com API do WhatsApp para envio de notificações automáticas
- Correção de erros de variáveis não definidas em diversos arquivos
- Melhoria na função de backup automático do banco de dados

### Alterações no Front-end

#### Layout e Design
- Atualização completa da interface do usuário para um design mais moderno e responsivo
- Implementação de componentes de UI avançados (cards, modais, dropdowns)
- Nova paleta de cores e sistema de design consistente
- Melhorias na navegação móvel com menu responsivo

#### Março 2025
- Redesenho da página inicial com modal de seleção de igrejas
- Implementação de slider de eventos na página inicial
- Melhorias no sistema de formulários com validação avançada

#### Abril 2025
- Novo design para cadastro de membros com formulário por etapas
- Implementação de sistema de busca avançada na navegação
- Otimização de carregamento de páginas com lazy loading de imagens
- Melhorias na acessibilidade do sistema
- Atualização do cabeçalho e rodapé para design mais limpo e funcional

### Melhorias de Desempenho
- Minificação e otimização de arquivos CSS e JavaScript
- Implementação de cache para recursos estáticos
- Otimização de consultas ao banco de dados
- Carregamento assíncrono de recursos não críticos

### Otimização para Dispositivos Móveis
- Redesenho completo da experiência móvel
- Implementação de design responsivo em todas as páginas
- Melhorias nos formulários para facilitar preenchimento em dispositivos móveis
- Ajustes no layout para melhor visualização em telas pequenas

## Próximos Desenvolvimentos Planejados

- Implementação de sistema de doações online
- Aplicativo móvel dedicado para membros
- Expansão da API para integração com outros sistemas
- Módulo avançado de business intelligence para análise de dados

---

Desenvolvido e mantido por [Agência Digital SLZ](https://agenciadigitalslz.com) - © 2025