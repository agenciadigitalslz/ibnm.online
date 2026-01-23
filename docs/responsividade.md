---
Tipo: analise
Data: 2025-08-09
Autor: André Lopes
Status: ativo
---

# Auditoria 360 — Responsividade (Mobile) — SaaS Igrejas

Escopo: site público em `@igreja/` (páginas: `index.php`, `igreja.php`, `igrejas.php`, `eventos.php`, `membro.php`) e estilos `css/estilos_site.css`, `css/produtos.css`.

Stack visual atual:
- Tailwind via CDN + CSS custom (`estilos_site.css`, `produtos.css`)
- GSAP + ScrollTrigger, jQuery, SweetAlert2
- Componentes: cards, carrossel, modal, FAQ

## Metodologia (amostra)
- Inspeção de breakpoints 360–768 px (portrait) e 740–1024 px (landscape)
- Verificação de grids, imagens, tipografia, interações e performance

## Sumário de achados
- Alta prioridade
  - Mistura de estratégias responsivas (Tailwind + JS de redimensionamento de logo) pode causar jank em mobile
  - Iframes de vídeo com altura fixa (`h-96`/`h-[500px]`) em `igreja.php` e no modal de `eventos.php` podem estourar viewport em devices pequenos
  - Imagens sem `loading="lazy"` e `decoding="async"` (cards e banners) aumentam LCP em 3G/4G
  - `membro.php` usa tema antigo (Bootstrap + CSS legado), inconsistência com o novo estilo

- Média prioridade
  - Carrossel mobile (`.ocultar_web`) só exibe em mobile; ok, mas sem controls visuais de "swipe"; bullets estáticos (3 fixos)
  - Botões CTA com ícones: tamanhos e área de toque variam; alguns <44px (recomendação mínima)
  - Animações GSAP sem fallback `prefers-reduced-motion`
  - Ícone flutuante do WhatsApp pode sobrepor CTAs em telas pequenas (bottom offset fixo 20px)

- Baixa prioridade
  - Títulos grandes (`text-4xl`) em algumas seções podem exigir `clamp()`/`sm:text-...`
  - Contraste ok em geral; revisar textos cinza claro sobre background degradê em devices com brilho baixo

## Recomendações

1) Remover JS de resize de logo e migrar para CSS
- Substituir listeners que ajustam `.large-logo`/`.large-logo-footer` por classes utilitárias e media queries (já há regras em `estilos_site.css`).

2) Iframes responsivos (vídeos)
- Adotar container com `aspect-ratio: 16 / 9` e largura 100%.
```css
.responsive-embed { position: relative; width: 100%; aspect-ratio: 16 / 9; }
.responsive-embed > iframe { position: absolute; inset: 0; width: 100%; height: 100%; border: 0; }
```
- Aplicar em `igreja.php` (área "Vídeo de Apresentação") e no modal de `eventos.php`.

3) Imagens: lazy loading e decoding
- Em listas e banners, adicionar `loading="lazy" decoding="async"` às tags `<img>`.
- Quando possível, informar `width`/`height` conhecidos (reduz layout shift).

4) Área de toque mínima e hierarquia dos botões
- Garantir altura mínima de 44px nos CTAs e ícones; ajustar paddings em classes utilitárias.
- Para botões de navegação (ex.: "Voltar para a Igreja"), manter estilo discreto porém com área de toque ampla.

5) Carrossel mobile
- Ajustar bullets para refletirem número dinâmico de slides ou ocultar bullets quando não sincronizados.
- Adicionar dica de swipe (ex.: pseudo-elemento ou tooltip leve) apenas em mobile.

6) Reduzir movimento para usuários sensíveis
- Envolver animações GSAP com media query `prefers-reduced-motion: reduce` e reduzir/omitir efeitos.
```css
@media (prefers-reduced-motion: reduce) {
  .gsap-fade-up, .gsap-stagger-item { transition: none !important; animation: none !important; }
}
```

7) Ícone WhatsApp flutuante
- Ajustar offset inferior com safe-area, ex.: `bottom: calc(20px + env(safe-area-inset-bottom));` e evitar sobreposição de CTAs com `z-index` adequado.

8) `membro.php` (tema antigo)
- Prioritário migrar para o novo tema (Tailwind + estilos do site) ou garantir consistência mínima: grid responsivo, inputs com `type` adequados (`tel`, `email`, `date`), e botões com área de toque >44px.

## Intervenções sugeridas (ordem de execução)
- Lote 1 (quick wins)
  - Lazy loading em imagens de `index.php`, `igreja.php`, `eventos.php`, `igrejas.php`
  - `responsive-embed` para iframes (página e modal)
  - Ajuste do botão WhatsApp com safe-area

- Lote 2
  - Remoção do JS de resize de logos e padronização somente em CSS
  - Ajuste de bullets do carrossel (dinâmicos) ou ocultação condicional
  - `prefers-reduced-motion`

- Lote 3
  - Revisão/migração de `membro.php` para o novo padrão visual

## Indicadores de validação pós-ajustes
- Lighthouse (Mobile): LCP < 2.5s, CLS < 0.1, TBT/INP dentro do aceitável
- Acessibilidade: contraste, foco visível, sem armadilhas de teclado
- UX: sem sobreposição de CTAs pelo botão WhatsApp; carrossel navegável

## Snippets de apoio
- Exemplo de imagem com lazy load:
```html
<img src=".../img.jpg" alt="Imagem da Igreja" loading="lazy" decoding="async" class="w-full h-28 object-cover rounded-md" />
```
- Exemplo de iframe responsivo:
```html
<div class="responsive-embed">
  <iframe src="..." title="Vídeo" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
</div>
```

## Risco/Impacto
- Baixo risco técnico; mudanças majoritariamente de marcação e CSS
- Impacto positivo em LCP/CLS e usabilidade mobile

## Conclusão
O frontend mobile está funcional, porém há melhorias claras de desempenho, consistência e acessibilidade. Recomenda-se aplicar os quick wins e planejar a migração do `membro.php` para o padrão atual.

---

## Execução — Lote 1 (v6.02)
- [x] Correção de overflow horizontal no mobile e ajuste de viewport (`100dvh`)
- [x] Iframes responsivos com `responsive-embed` (página e modal)
- [x] Lazy loading em imagens principais (carrossel/listas)
- [x] Ícone WhatsApp com safe-area (`.whats-fixed`)
- [x] Redes sociais no mobile: ícones-only com botões circulares; rótulos apenas em md+

## Próximos — Lote 2 (planejado)
- [x] Remover JS de resize de logos (migrado para CSS)
- [x] Ajustar bullets do carrossel (dinâmicos) ou ocultar quando não sincronizados
- [x] `prefers-reduced-motion` integrado ao GSAP
