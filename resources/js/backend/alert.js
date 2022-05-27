const SwalModal = (
    icon,
    title,
    html,
    show_confirm_button = false,
    outside_click = true
) => {
    Swal.fire({
        icon,
        title,
        html,
        showConfirmButton: show_confirm_button,
        allowOutsideClick: outside_click
    })
}

const SwalConfirm = (
    icon,
    title,
    html,
    confirmButtonText,
    method,
    params,
    callback,
    callback_params,
    show_cancel_button = true,
    show_confirm_button = true,
    allow_outside_click= true,
) => {
    Swal.fire({
        icon,
        title,
        html,
        showConfirmButton: show_confirm_button,
        showCancelButton: show_cancel_button,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: confirmButtonText,
        reverseButtons: true,
        allowOutsideClick:allow_outside_click
    }).then(result => {
        if (result.value) {
            return window.livewire.emit(method, params)
        }

        if (callback) {
            return window.livewire.emit(callback, callback_params)
        }
    })
}

const SwalAlert = (icon, title, timeout = 7000) => {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: timeout,
        onOpen: toast => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    })

    Toast.fire({
        icon,
        title
    })
}
document.addEventListener('DOMContentLoaded', () => {
    window.livewire.on('swal:modal', data => {
        SwalModal(data.icon, data.title, data.text, data.show_confirm_button,  data.outside_click)
    });

    window.livewire.on('swal:confirm', data => {
        SwalConfirm(
            data.icon,
            data.title,
            data.text,
            data.confirmText,
            data.method,
            data.params,
            data.callback,
            data.callback_params,
            data.show_cancel_button,
            data.show_confirm_button,
            data.allow_outside_click,
        );
    });

    window.livewire.on('swal:alert', data => {
        SwalAlert(data.icon, data.title, data.timeout)
    });

});

