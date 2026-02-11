/**
 * LGPD Cookie Consent - IBNM.online
 * Componente simples de aviso de cookies
 */
class LgpdCookie extends HTMLElement {
  connectedCallback() {
    const text = this.getAttribute('text') || 'Este site utiliza cookies para melhorar sua experiência.';
    
    // Verificar se já aceitou
    if (localStorage.getItem('lgpd-aceito')) return;

    const banner = document.createElement('div');
    banner.innerHTML = `
      <div style="position:fixed;bottom:0;left:0;right:0;background:#1a1a2e;color:#fff;padding:15px 20px;display:flex;align-items:center;justify-content:space-between;gap:15px;z-index:9999;font-size:14px;box-shadow:0 -2px 10px rgba(0,0,0,0.3);">
        <span>🍪 ${text}</span>
        <button id="lgpd-aceitar" style="background:#3498db;color:#fff;border:none;padding:8px 20px;border-radius:6px;cursor:pointer;font-weight:600;white-space:nowrap;">Aceitar</button>
      </div>
    `;
    document.body.appendChild(banner);

    document.getElementById('lgpd-aceitar').addEventListener('click', () => {
      localStorage.setItem('lgpd-aceito', 'true');
      banner.remove();
    });
  }
}

customElements.define('lgpd-cookie', LgpdCookie);
