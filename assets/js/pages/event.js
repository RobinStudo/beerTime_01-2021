const button = document.getElementById('event_join');

button.addEventListener('click', () => {
    let xhr = new XMLHttpRequest();
    let url = button.getAttribute('data-url');
    xhr.open('POST', url);
    xhr.send();

    xhr.onload = () => {
        let data = JSON.parse( xhr.response );

        if( data.status === true ){
            button.setAttribute('disabled', 'disabled');
            button.innerText = 'Inscription confirmée';
        }
    };
});