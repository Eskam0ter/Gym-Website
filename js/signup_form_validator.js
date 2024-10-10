export function isValidPassword(password) {
    return password.length >= 8;
}

export function doPasswordsMatch(password, confirm_password) {
    return password === confirm_password;
}

export function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}