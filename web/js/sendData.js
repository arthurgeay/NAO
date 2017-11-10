function sendData(id_token, lastname, firstname, email) {
    $.ajax({
        type: 'post',
        url: path,
        data: {
            idtoken: id_token,
            lastname: lastname,
            firstname: firstname,
            email: email
        },
        complete: function(resultat, statut) {
            document.location.href = login;
        }
    });
}