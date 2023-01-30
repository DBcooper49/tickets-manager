/*window.addEventListener('load', function(){
    var selectUser = document.getElementsByClassName('.select-user');
    for (var i = 0; i < selectUser.length; i++){
        selectUser[i].addEventListener('change', ()=>{
            let ticketId = selectUser.getAttribute('data-id');
            // alert(ticketId);
            let userId = selectUser.value;
            // alert(userId);

            url = addUserRoute.replaceAll(':id', ticketId)
            .replaceAll(':userId', userId);

            // alert(url);
            let requete = new XMLHttpRequest();
            requete.open('GET', url);

            requete.responseType = 'text';
            requete.send();

            requete.onload = function() {
                if (requete.readyState === XMLHttpRequest.DONE){
                    if(requete.status === 200){
                        console.log("ok");
                    }
                }
            }
        });
    }
});
*/