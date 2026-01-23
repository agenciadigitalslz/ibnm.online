---
Tipo: analise
Data: 2025-08-09
Autor: André Lopes
Status: ativo
---

# Documentação do Projeto — SaaS Igrejas

Este repositório usa documentação enxuta com documentos-chave. Mantenha-os sempre atualizados e evite acúmulo de arquivos.

## Documentos-chave (docs/)
- README.md: visão geral da documentação (este arquivo)
- updates.md: changelog contínuo (obrigatório em toda alteração; versão v6.01+)
- planejamentos.md: backlog imediato e alvos da sprint
- progresso_projeto.md: registros de avanços diários
- horas.md (opcional)
- SPRINTS.md (opcional)

## Metadados obrigatórios
Inclua no topo de todo .md:
```yaml
---
Tipo: [analise|implementacao|correcao|deploy|backup]
Data: YYYY-MM-DD
Autor: André Lopes
Status: ativo
---
```

## Versionamento e disciplina
- Padrão: vMAJOR.MINOR (dois dígitos) — base v6.0; incrementos a partir de v6.01
- Regra: toda alteração relevante deve entrar no updates.md com data, escopo e impacto

## Fluxo de manutenção dos docs
1) Planeje no planejamentos.md
2) Implemente no código
3) Registre no updates.md (nova versão quando aplicável)
4) Anote no progresso_projeto.md (resumo do dia)

Checklist de merge: updates.md atualizado; sem logs de debug; regras de segurança respeitadas.