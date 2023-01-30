window.addEventListener('load', function(){
    [...document.querySelectorAll('.select-user')].forEach(function(button) {
        button.addEventListener('change', ()=>{
            let ticketId = button.getAttribute('data-id');
            let userId = button.value;

            url = addUserRoute.replaceAll(':id', ticketId)
            .replaceAll(':userId', userId);

            console.log(url);
            let requete = new XMLHttpRequest();
            requete.open('GET', url);

            requete.onreadystatechange = function() {
                if (requete.readyState === XMLHttpRequest.DONE && requete.status === 200) {
                    let response = JSON.parse(requete.responseText);
                    console.log(response);
                    let assignDiv = document.querySelector('.col-assigned-to-' + ticketId);
                    assignDiv.innerHTML = response['user'];
                }
            };
            requete.send();
        });
    });
});
