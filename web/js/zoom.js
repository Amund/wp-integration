console.info('module zoom loaded')

const imgs = document.querySelectorAll('img.zoom')

if (imgs.length) {
	const opacityDuration = 200 // ms
	const zoomDuration = 300 // ms

	let currentImg = null

	const modal = document.createElement('div')
	document.body.appendChild(modal)
	modal.classList.add('modal', 'zoom')
	modal.style.transition = `opacity ${opacityDuration}ms ease-in-out`
	modal.style.position = 'fixed'
	modal.style.top = 0
	modal.style.left = 0
	modal.style.width = '100%'
	modal.style.height = '100%'
	modal.style.display = 'none'
	modal.style.opacity = 0
	modal.style.zIndex = 10000
	modal.style.backgroundColor = 'rgba(0, 0, 0, 0.8)'
	modal.style.overflow = 'hidden'
	modal.style.overscrollBehavior = 'contain'
	modal.style.cursor = 'zoom-out'

	modal.addEventListener('click', () => {
		const img = currentImg
		const original = modal.querySelector('img')
		if (!img || !original) return
		const rect = img.getBoundingClientRect()

		original.style.transition = `all ${zoomDuration}ms ease-out`
		original.style.top = `${rect.top + rect.height / 2}px`
		original.style.left = `${rect.left + rect.width / 2}px`
		original.style.width = `${rect.width}px`
		original.style.height = `${rect.height}px`
		original.style.borderRadius = getComputedStyle(img).borderRadius
		modal.style.transition = `opacity ${opacityDuration}ms ${zoomDuration}ms ease-out`
		modal.style.opacity = 0
		setTimeout(() => {
			modal.style.display = 'none'
			modal.innerHTML = ''
		}, zoomDuration + opacityDuration)
		currentImg = null
	})

	for (const img of imgs) {
		img.style.cursor = 'zoom-in'
		img.addEventListener('click', () => {
			currentImg = img

			// get image position in viewport
			const rect = img.getBoundingClientRect()
			const width = img.getAttribute('width')
			const height = img.getAttribute('height')
			const ratio = width / height
			const viewWidth = document.documentElement.clientWidth
			const viewHeight = document.documentElement.clientHeight
			const viewRatio = viewWidth / viewHeight

			// inject original image in modal
			const original = document.createElement('img')
			original.src = img.dataset.original
			original.style.position = 'absolute'
			original.style.translate = '-50% -50%'
			original.style.objectFit = 'cover'
			original.style.top = `${rect.top + rect.height / 2}px`
			original.style.left = `${rect.left + rect.width / 2}px`
			original.style.width = `${rect.width}px`
			original.style.height = `${rect.height}px`
			original.style.borderRadius = getComputedStyle(img).borderRadius
			original.style.transition = `all ${zoomDuration}ms ${opacityDuration}ms ease-out`
			modal.appendChild(original)

			// show modal
			modal.style.display = 'block'
			setTimeout(() => {
				modal.style.transition = `opacity ${opacityDuration}ms ease-out`
				modal.style.opacity = 1
				original.style.top = '50%'
				original.style.left = '50%'
				original.style.borderRadius = '0'
				if (ratio > 1) {
					if (ratio > viewRatio) {
						original.style.width = '100vw'
						original.style.height = `${(height / width) * 100}vw`
					} else {
						original.style.width = `${(width / height) * 100}vh`
						original.style.height = '100vh'
					}
				} else {
					if (ratio > viewRatio) {
						original.style.width = '100vw'
						original.style.height = `${(height / width) * 100}vw`
					} else {
						original.style.width = `${(width / height) * 100}vh`
						original.style.height = '100vh'
					}
				}
			}, 50)
		})
	}
}
