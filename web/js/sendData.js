function sendData(id, lastname, firstname, email) {
    $.ajax({
        type: 'post',
        url: path,
        data: {
            id: id,
            lastname: lastname,
            firstname: firstname,
            email: email
        },
        complete: function(resultat, statut) {
            document.location.href = returnPath;
        }
    });
}