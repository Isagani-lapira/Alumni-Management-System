document.addEventListener('DOMContentLoaded', function () {
    const openModalBtn = document.getElementById('openModalBtn');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const modal = document.getElementById('modal');

    openModalBtn.addEventListener('click', () => {
        modal.classList.remove('hidden');
    });

    closeModalBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
    });
});











const useTemplateRadio = document.getElementById('useTemplate');
const createOwnRadio = document.getElementById('createOwn');
const useTemplateDiv = document.getElementById('template');
const createOwnDiv = document.getElementById('own');

useTemplateRadio.addEventListener('change', () => {
    useTemplateDiv.classList.remove('hidden');
    createOwnDiv.classList.add('hidden');
});

createOwnRadio.addEventListener('change', () => {
    useTemplateDiv.classList.add('hidden');
    createOwnDiv.classList.remove('hidden');
});






