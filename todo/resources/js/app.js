import './bootstrap';
import Swal from 'sweetalert2';
import Choices from 'choices.js';

window.Choices = Choices;

window.addEventListener('swal:confirm', event => {
    Swal.fire({
        title: event.detail.title,
        text: event.detail.text,
        icon: event.detail.type,
        showCancelButton: true,
        confirmButtonColor: 'rgb(239 68 6)',
        confirmButtonText: 'Да, удалить!',
        cancelButtonText: 'Отмена',
    })
        .then((willDelete) => {
            if (willDelete.isConfirmed) {
                // console.log(event.detail.method, event.detail.id);
                window.livewire.emit(event.detail.method, event.detail.id);
            }
        });
});
