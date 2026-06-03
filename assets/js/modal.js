const modalBtn = document.querySelectorAll ('[data-modal]');
const body = document.body;
const modalcancel = document.querySelectorAll ('.modal__cancel');
const modal = document.querySelectorAll ('.modal');
        
modalBtn.forEach(item => {
    item.addEventListener('click', event => {
        let $this = event.target;
        let modalid = $this.getAttribute ('data-modal');
        let modal = document.getElementById(modalid);
        let modalContent = modal.querySelector ('.modal__inner');

        modalContent.addEventListener('click', event => {
            vent.stopPropagation();
        });

        modal.classList.add('show');
        body.classList.add('no-scroll');

        setTimeout(() => {
            modalContent.style.transform = 'none'
            modalContent.style.opacity = '1'

        }, 1);

    });

});

modalcancel.forEach(item => {
    item.addEventListener('click', event => {
        let currentModal = event.target.closest('.modal');
        

        CloseModal(currentModal);

        });

});

modal.forEach(item => {
    item.addEventListener('click', event => {
        let currentModal = event.target;
        

        CloseModal(currentModal);


        });

});

function CloseModal(currentModal) {
    let modalContent = currentModal.querySelector ('.modal__inner');
    modalContent.removeAttribute ('style');

    setTimeout(() => {
            currentModal.classList.remove('show');
            body.classList.remove('no-scroll');

        }, 400);
}