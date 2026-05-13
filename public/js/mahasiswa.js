// Public copy of mahasiswa.js (inlined from resources)
document.addEventListener('DOMContentLoaded', function () {
	// Carousel arrow handlers (no images, just visual)
	var left = document.querySelector('.arrow.left');
	var right = document.querySelector('.arrow.right');
	var dots = Array.from(document.querySelectorAll('.dot'));
	var activeIndex = dots.findIndex(function (d) { return d.classList.contains('active'); });
	if (activeIndex < 0) activeIndex = 0;

	function setActive(i) {
		dots.forEach(function (d) { d.classList.remove('active'); d.style.width = ''; });
		var d = dots[i]; if (!d) return;
		d.classList.add('active'); d.style.width = '24px';
		activeIndex = i;
	}

	if (left) left.addEventListener('click', function () { setActive((activeIndex - 1 + dots.length) % dots.length); });
	if (right) right.addEventListener('click', function () { setActive((activeIndex + 1) % dots.length); });
});
