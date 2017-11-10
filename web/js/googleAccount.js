function onSignIn(googleUser) {

    var profile = googleUser.getBasicProfile();

    // The ID token you need to pass to your backend:
    var id_token = googleUser.getAuthResponse().id_token;

    var lastname = profile.getFamilyName();
    var firstname = profile.getGivenName();
    var email = profile.getEmail();

    sendData(id_token, lastname, firstname, email);
};