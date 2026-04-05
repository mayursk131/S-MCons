
class LanguageManager {
    constructor() {
        this.currentLang = localStorage.getItem('adminLanguage') || 'en';
        this.translations = {};
        this.loadLanguage(this.currentLang);
    }

    async loadLanguage(lang) {
        try {
            const response = await fetch(`scripts/utils/lang/${lang}.json`);
            this.translations = await response.json();
            this.currentLang = lang;
            localStorage.setItem('adminLanguage', lang);
            this.updateUI();
        } catch (error) {
            console.error('Failed to load language:', error);
            
            if (lang !== 'en') {
                this.loadLanguage('en');
            }
        }
    }

    t(key) {
        return this.translations[key] || key;
    }

    updateUI() {
        
        document.querySelectorAll('[data-translate]').forEach(element => {
            const key = element.getAttribute('data-translate');
            const translation = this.translations[key] || key;
            
            if (translation.includes('<') || translation.includes('>')) {
                element.innerHTML = translation;
            } else {
                element.textContent = translation;
            }
        });

        
        document.querySelectorAll('[data-translate-placeholder]').forEach(element => {
            const key = element.getAttribute('data-translate-placeholder');
            element.placeholder = this.translations[key] || key;
        });

        
        document.querySelectorAll('[data-translate-title]').forEach(element => {
            const key = element.getAttribute('data-translate-title');
            element.title = this.translations[key] || key;
        });

        
        this.updateLogo();

        
        if (typeof admin !== 'undefined') {
            admin.updateLanguage();
        }
    }

    updateLogo() {
        const logoElement = document.getElementById('smlogo');
        if (logoElement) {
            const currentLang = localStorage.getItem('adminLanguage') || 'en';
            if (currentLang === 'kn') {
                
                logoElement.innerHTML = 'ಎಸ್<span class="text-gold">&</span>ಎಂ <span class="text-gold">ಇನ್‌ಫ್ರಾ</span>';
            } else {
                
                logoElement.innerHTML = 'S<span class="text-gold">&</span>M <span class="text-gold">INFRA</span>';
            }
        }

        
        const footerLogoElement = document.getElementById('footerLogo');
        if (footerLogoElement) {
            const currentLang = localStorage.getItem('adminLanguage') || 'en';
            if (currentLang === 'kn') {
                
                footerLogoElement.innerHTML = 'ಎಸ್<span class="text-gold">&</span>ಎಂ <span class="text-gold">ಇನ್‌ಫ್ರಾ</span>';
            } else {
                
                footerLogoElement.innerHTML = 'S<span class="text-gold">&</span>M <span class="text-gold">INFRA</span>';
            }
        }
    }

    createLanguageSelector() {
        const languages = [
            { code: 'en', name: 'English', flag: '🇺🇸' },
            { code: 'hi', name: 'हिंदी', flag: '🇮🇳' },
            { code: 'mr', name: 'मराठी', flag: '🇮🇳' },
            { code: 'kn', name: 'ಕನ್ನಡ', flag: '🇮🇳' }
        ];

        let selectorHTML = `
            <div class="language-selector">
                <select class="form-select custom-input" id="languageSelect" onchange="languageManager.loadLanguage(this.value)">
        `;

        languages.forEach(lang => {
            const selected = lang.code === this.currentLang ? 'selected' : '';
            selectorHTML += `<option value="${lang.code}" ${selected}>${lang.flag} ${lang.name}</option>`;
        });

        selectorHTML += `
                </select>
            </div>
        `;

        return selectorHTML;
    }
}


let languageManager;
document.addEventListener('DOMContentLoaded', function() {
    languageManager = new LanguageManager();
});
