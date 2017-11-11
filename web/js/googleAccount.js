function onSignIn(googleUser) {

    var profile = googleUser.getBasicProfile();

    
    var id = profile.getId();

    var lastname = profile.getFamilyName();
    var firstname = profile.getGivenName();
    var email = profile.getEmail();

    sendData(id, lastname, firstname, email);
};