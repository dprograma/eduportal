/**
 * Validate if string is empty
 * @param e string
 * @returns {boolean}
 */
export function isEmpty(e) {
    switch (e) {
        case "":
        case 0:
        case "0":
        case null:
        case false:
        case typeof this == "undefined":
            return true;
        default:
            return false;
    }
}

/**
 * Validate if string is full of all blanks
 * @param str string
 * @returns {boolean}
 */
export function isBlank(str) {
    return (!str || /^\s*$/.test(str));
}

export function isValidStr(str) {
    return !isBlank(str) && !isEmpty(str) && str !== undefined;
}

export function areValidStrs() {
    let len = arguments.length;
    let isValid = true;
    for (let i = 0; i < len; i++) {
        isValid = isValid && isValidStr(arguments[i])
    }

    return isValid;
}

/**
 * Used to set cookie ina web browser
 * @param cookieName cookie name
 * @param cookieValue cookie values
 * @param validityDays number of days before expiration of cookie
 * @param path path
 * @param isSecure to ensure cookie is only sent when https is being used
 */
export function setCookie(cookieName, cookieValue, validityDays, path = "/", isSecure = false) {
    let expiryDate = new Date();
    expiryDate.setTime(expiryDate.getTime() + Math.floor(validityDays * 24 * 60 * 60 * 1000));

    let cookieDetails = cookieName + "=" + cookieValue
        + ";expires=" + expiryDate.toUTCString()
        + ";path=" + path
        + ";domain=" + window.location.hostname;

    if (isSecure) {
        cookieDetails = cookieDetails + ";secure; HttpOnly";
    }

    document.cookie = cookieDetails
}

/**
 * Used to get cookie from web browser
 * @param cookieName cookie name
 * @returns {string} cookie value
 */
export function getCookie(cookieName) {
    let name = cookieName + "=";
    let splits = document.cookie.split(';');
    let len = splits.length;
    for (let i = 0; i < len; i++) {
        let val = splits[i];
        while (val.charAt(0) === ' ') {
            val = val.substring(1);
        }
        if (val.indexOf(name) === 0) {
            return val.substring(name.length, val.length);
        }
    }
    return "";
}

export function deleteCookie(cookieName, path = "/", isSecure = false) {
    if (getCookie(cookieName) !== "") {
        let cookieDetails = cookieName + "=;"
            + ";expires=Thu, 01 Jan 1970 00:00:01 GMT"
            + ";path=" + path
            + ";domain=" + window.location.hostname;

        if (isSecure) {
            cookieDetails = cookieDetails + ";secure; HttpOnly";
        }

        document.cookie = cookieDetails
    }
}

// Source: https://stackoverflow.com/a/1349462/3151251
export function generateRandomStr(len, charSet) {
    charSet = charSet || 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let randomString = '';
    for (let i = 0; i < len; i++) {
        let randomPoz = Math.floor(Math.random() * charSet.length);
        randomString += charSet.substring(randomPoz, randomPoz + 1);
    }
    return randomString;
}

/**
 * Source: https://davidwalsh.name/javascript-polling
 */
export function poll(fn, timeout, interval) {
    let endTime = Number(new Date()) + (timeout || 2000);
    interval = interval || 100;

    let checkCondition = function (resolve, reject) {
        // If the condition is met, we're done!
        let result = fn();
        if (result) {
            resolve(result);
        }
        // If the condition isn't met but the timeout hasn't elapsed, go again
        else if (Number(new Date()) < endTime) {
            setTimeout(checkCondition, interval, resolve, reject);
        }
        // Didn't match and too much time, reject!
        else {
            reject(new Error('timed out for ' + fn + ': ' + arguments));
        }
    };

    return new Promise(checkCondition);
}

/**
 * Saves item in browser local storage if browser supports it
 * @param key key
 * @param value value
 */
export function setInLocalStorage(key, value) {
    if (typeof (Storage) !== "undefined") {
        window.localStorage.setItem(key, value);
    }
}

/**
 * Saves item in browser local storage if browser supports it
 * @param key key
 * @returns stored value (if available) or null
 */
export function getFromLocalStorage(key) {
    if (typeof (Storage) !== "undefined") {
        return window.localStorage.getItem(key);
    } else {
        return null;
    }
}

export function toTitleCase(str) {
    return str.replace(/\p{L}+('\p{L}+)?/gu, function (txt) {
        return txt.charAt(0).toUpperCase() + txt.slice(1)
    })
}


