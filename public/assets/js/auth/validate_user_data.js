// parametros de validação da matricula
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

// parametros de validação da senha
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