function onSignIn(googleUser) {

    var profile = googleUser.getBasicProfile();
    var id = profile.getId();

    document.location.href = path + '?api=' + id;
};