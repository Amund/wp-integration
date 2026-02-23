// fix vw/vh with scrollbars (see css/2-variables.css)
// TODO: fix without js https://github.com/w3c/csswg-drafts/issues/6026#issuecomment-1722407312

console.info('module fix-scrollbar-width loaded')

function resize() {
	const vertical = window.innerWidth - document.documentElement.clientWidth
	const horizontal = window.innerHeight - document.documentElement.clientHeight
	document.querySelector(':root').style.setProperty('--vertical-scrollbar-width', `${vertical}px`)
	document.querySelector(':root').style.setProperty('--horizontal-scrollbar-width', `${horizontal}px`)
}

let resizeTimeout

const resizeObserver = new ResizeObserver(() => {
	if (resizeTimeout) cancelAnimationFrame(resizeTimeout)
	resizeTimeout = requestAnimationFrame(resize)
})

resizeObserver.observe(document.documentElement)
document.addEventListener('DOMContentLoaded', resize)
