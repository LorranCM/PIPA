export function is_valid_registration(registration) {
    const validchars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    for (let i = 0; i < registration.length; i++) {
        if (!validchars.includes(registration[i])) {
            return "invalid characters";
        }
    }

    if (!registration || registration === "") {
        return "empty registration";
    }

    return "";
}

export function is_valid_password(password) {
    if (password.length < 8) {
        return "short password";
    } else if (password.includes(" ")) {
        return "password contains spaces";
    }

    if (!password || password === "") {
        return "empty password";
    }

    return "";
}

export function is_valid_name(name) {
    const validchars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZàáâãäåèéêëìíîïòóôõöùúûüçÀÁÂÃÄÅÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÇ\' ';
    for (let i = 0; i < name.length; i++) {
        if (!validchars.includes(name[i])) {
            return "invalid characters";
        }
    }

    if (!name || name === "") {
        return "empty name";
    }

    return "";
}