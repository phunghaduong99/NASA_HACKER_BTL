function logout() {
    document.cookie="Authorization=;path=/"
    window.location.href = "/users/login";
}

function goToProfile(userId= null){
    var url = "/users/view_profile";

    if (userId) {
        url += "/" + userId
    }

    window.location.href = url
}