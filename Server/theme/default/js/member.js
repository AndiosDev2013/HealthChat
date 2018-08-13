
function on_login() {
    var pwd = document.getElementById('password');
    var digest = document.getElementById('digest');
    digest.value = getDigest(pwd.value);
    pwd.value = "";

    return true;
}

function on_profile()
{
    $new_pwd = $('#new_pwd').val();
    $confirm_pwd = $('#confirm_pwd').val();
    if ($new_pwd != $confirm_pwd) {
        alert('New password does not match confirm password.');
        $('#confirm_pwd').focus();
        return false;
    }
    $('#digest1').val(getDigest($('#old_pwd').val()));
    $('#digest2').val(getDigest($('#new_pwd').val()));
    //$('#old_pwd').val('');
    //$('#new_pwd').val('');        
    return true;
}

function on_signup()
{
    var email = document.getElementById('email');
    var pwd = document.getElementById('password');
    var confirm = document.getElementById('confirm_pwd');
    var digest = document.getElementById('digest');
    if (email.value == '') {
        alert('The User name field is required.');
        return false;
    }

    if (pwd.value == '') {
        alert('The password field is required.');
        return false;
    }

    if (confirm.value == '') {
        alert('The confirm password field is required.');
        return false;
    }

    if (pwd.value != confirm.value) {
        alert('The password and confirm password is different.');
        return false;
    }

    digest.value = getDigest(pwd.value);
    confirm.value = "";
    pwd.value = "";

    return true;
}

