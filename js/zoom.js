document.querySelectorAll('.image').forEach(elem => {
  let x, y, width, height;
  elem.addEventListener('mouseenter', () => {
    const size = elem.getBoundingClientRect();
    console.log(size);
    x = size.x;
    y = size.y;
    width = size.width;
    height = size.height;
    elem.classList.add('zoomed');
  });
  elem.addEventListener('mouseleave', () => {
    elem.classList.remove('zoomed');
  });
  elem.addEventListener('mousemove', e => {
    const horizontal = (e.clientX - x) / width * 100;
    const vertical = (e.clientY - y) / height * 100;
    elem.style.setProperty('--x', horizontal + '%');
    elem.style.setProperty('--y', vertical + '%');
  });
});
