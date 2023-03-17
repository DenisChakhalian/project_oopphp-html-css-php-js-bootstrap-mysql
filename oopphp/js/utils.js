export function isValid(value) {
    return value.length >= 10;
}

export function createModal(type, content) {
    const modal = document.createElement('div')
    modal.classList.add('modal');

    modal.innerHTML = `
    <h1>${type}</h1>
    <div class="modal-content">${content}</div> `;

    if (type === 'Войти') {
        modal.innerHTML = `${modal.innerHTML}
      <a id="register" 
            style="cursor: pointer; 
            color: #00aced;
            position: relative; 
            float: right;
            margin-right: 25px;}">
            Регистрация
          </a>`
    } else if (type === 'Регистрация') {
        modal.innerHTML = `${modal.innerHTML} 
      <a id="register" 
            style="cursor: pointer; 
            color: #00aced;
            position: relative; 
            float: right;
            margin-right: 25px;}">
            Войти
          </a>`
    }
    mui.overlay('on', modal);
}

export function closeModal() {
    mui.overlay('off');

}
