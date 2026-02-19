// Wait for n milliseconds (promise)
export function wait(ms) {
	return new Promise((r) => setTimeout(r, ms))
}

// Throttle
export function throttle(func, delay) {
	let timer = null
	return function (...args) {
		if (timer === null) {
			timer = setTimeout(() => {
				func.apply(this, args)
				timer = null
			}, delay)
		}
	}
}

// Injection d'une icon svg
export function icon(id, ...classes) {
	classes = ['svg', `icon-${id}`, ...classes]
	return `<svg class="${classes.join(' ')}" aria-label="icon"><use xlink:href="#${id}"></use></svg>`
}

// Traitement des chaines de caractères pour la recherche
// (suppression des diacritics + minuscule)
export function normalizeString(str) {
	return str
		.normalize('NFD')
		.replace(/[\u0300-\u036f]/g, '')
		.toLowerCase()
}
