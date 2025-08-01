(() => {
  const button = document.getElementById('theme-toggle-btn');
  if (!button) return;

  // SVG icons for sun and moon
  const sunIcon = `
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" 
         stroke-linecap="round" stroke-linejoin="round" >
      <circle cx="12" cy="12" r="5"></circle>
      <line x1="12" y1="1" x2="12" y2="3"></line>
      <line x1="12" y1="21" x2="12" y2="23"></line>
      <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
      <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
      <line x1="1" y1="12" x2="3" y2="12"></line>
      <line x1="21" y1="12" x2="23" y2="12"></line>
      <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
      <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
    </svg>
  `;

  const moonIcon = `
    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
         stroke-linecap="round" stroke-linejoin="round">
      <path d="M21 12.79A9 9 0 0111.21 3 7 7 0 0012 21a9 9 0 009-8.21z"></path>
    </svg>
  `;

  let theme = localStorage.getItem('theme') || 'dark';
  setTheme(theme);

  function setTheme(newTheme) {
    theme = newTheme;
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
    button.innerHTML = theme === 'dark' ? sunIcon : moonIcon;
  }

  button.addEventListener('click', () => {
    setTheme(theme === 'dark' ? 'light' : 'dark');
  });
})();