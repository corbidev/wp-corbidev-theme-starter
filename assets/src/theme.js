const STORAGE_KEY = 'theme'
const ALLOWED_THEMES = ['light', 'dark']

const preferenceConfig = window.corbidevThemePreference || {}

function isValidTheme(theme) {
    return ALLOWED_THEMES.includes(theme)
}

function getStoredTheme() {
    try {
        const storedTheme = localStorage.getItem(STORAGE_KEY)
        return isValidTheme(storedTheme) ? storedTheme : null
    } catch (error) {
        return null
    }
}

function setStoredTheme(theme) {
    if (!isValidTheme(theme)) {
        return
    }

    try {
        localStorage.setItem(STORAGE_KEY, theme)
    } catch (error) {
        // noop
    }
}

function getServerTheme() {
    return isValidTheme(preferenceConfig.userTheme) ? preferenceConfig.userTheme : null
}

function applyTheme(theme) {
    document.documentElement.classList.toggle('dark', theme === 'dark')
}

function resolveInitialTheme() {
    const localTheme = getStoredTheme()
    if (localTheme) {
        return localTheme
    }

    const serverTheme = getServerTheme()
    if (serverTheme) {
        setStoredTheme(serverTheme)
        return serverTheme
    }

    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        return 'dark'
    }

    return 'light'
}

async function persistThemeForLoggedUser(theme) {
    if (!isValidTheme(theme)) {
        return
    }

    if (!preferenceConfig.isLoggedIn || !preferenceConfig.restUrl || !preferenceConfig.nonce) {
        return
    }

    try {
        await fetch(preferenceConfig.restUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': preferenceConfig.nonce,
            },
            credentials: 'same-origin',
            body: JSON.stringify({ theme }),
        })

        preferenceConfig.userTheme = theme
    } catch (error) {
        // noop
    }
}

export function getCurrentTheme() {
    return document.documentElement.classList.contains('dark') ? 'dark' : 'light'
}

export function toggleTheme() {
    const currentTheme = getCurrentTheme()
    const nextTheme = currentTheme === 'dark' ? 'light' : 'dark'

    applyTheme(nextTheme)
    setStoredTheme(nextTheme)
    persistThemeForLoggedUser(nextTheme)
}

export function initTheme() {
    const initialTheme = resolveInitialTheme()
    applyTheme(initialTheme)
    setStoredTheme(initialTheme)

    const localTheme = getStoredTheme()
    const serverTheme = getServerTheme()

    if (!preferenceConfig.isLoggedIn) {
        return
    }

    if (!serverTheme) {
        persistThemeForLoggedUser(localTheme || initialTheme)
        return
    }

    if (localTheme && localTheme !== serverTheme) {
        persistThemeForLoggedUser(localTheme)
    }
}
