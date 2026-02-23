// detection du scroll de la page (requestAnimationFrame)

console.info('module scrolled loaded')

const offsetTop = 1
let lastScrollTop = 0

function scrolled() {
	const scrollTop = window.pageYOffset || document.documentElement.scrollTop
	document.body.classList.toggle('scrolled', scrollTop > offsetTop)
	lastScrollTop = scrollTop < 0 ? 0 : scrollTop
	requestAnimationFrame(scrolled)
}

document.addEventListener('DOMContentLoaded', () => {
	requestAnimationFrame(scrolled)
})
