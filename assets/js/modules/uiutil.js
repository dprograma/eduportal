import Swal from '/cbt/webjars/sweetalert2/9.10.12/src/sweetalert2.js';
import purify from '/cbt/webjars/dompurify/2.3.6/dist/purify.es.js';
import {generateRandomStr, getCookie, isBlank, isEmpty, isValidStr, setCookie} from "./util.js";
import {
    ALLOWED_HTML_ATTRIBUTES_EXTRA,
    ALLOWED_HTML_TAGS_EXTRA,
    GREEN_BRIDGE_COOKIE_APP_ADVERT_WAS_SHOWN
} from "../constants.js";

/**
 * Used to display a simple error message with title, details and an ok button
 * @param errorTitle error title
 * @param errorDetails error details
 */
export function alertSimpleError(errorTitle, errorDetails) {
    try {
        Swal.fire({
            icon: 'error',
            title: errorTitle,
            html: errorDetails,
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        })
    } catch (e) {
        alertSimpleErrorWithBootstrapModal(errorTitle, errorDetails)
    }
}

/**
 * Used to show simple error alert with bootstrap modal in case sweet alert
 * library experiences an issue on the client's browser. This also comes
 * in handy during integration tests, as the htmlunit (testing framework)
 * is not compatible with sweet alert. Hence all integration test error alerts
 * fallback to this function.
 * @param errorTitle error title
 * @param errorDetails error details
 */
export function alertSimpleErrorWithBootstrapModal(errorTitle, errorDetails) {
    document.getElementById("generic-simple-error-alert-modal-msg-heading").innerText = errorTitle;
    document.getElementById("generic-simple-error-alert-modal-msg-details").innerText = errorDetails;
    balanceModalPadding('generic-simple-error-notification-modal');
    $('#generic-simple-error-notification-modal').modal('show');
}

/**
 * Used to alert user about failed AJAX operation.
 * Requires that the page imports sweetalert2.js,
 * sweetalert2.css and animate.css. Will fallback to
 * using bootstrap modal to alert if sweet
 * alert has issues
 * @param jqXHR data
 * @param textStatus status text
 * @param onCloseCallback callback to execute when pop up is closed
 * @param onOpenCallback callback to execute as soon as error alert is displayed
 */
export function alertError(jqXHR, textStatus, onCloseCallback = null, onOpenCallback = null) {
    let title, details, actionText, fallbackText;

    if (typeof jqXHR === 'string' || jqXHR instanceof String) {
        let result = JSON.parse(jqXHR);
        title = result.error;
        details = result.message;
        actionText = result.action;
        fallbackText = result.fallback;
    } else if (!isBlank(jqXHR.responseText)) {
        let result = JSON.parse(jqXHR.responseText);
        title = result.error;
        details = result.message;
        actionText = result.action;
        fallbackText = result.fallback;
    } else if (jqXHR.responseJSON !== null
        && (typeof jqXHR.responseJSON !== undefined)) {
        title = jqXHR.responseJSON.error
        details = jqXHR.responseJSON.message;
        actionText = jqXHR.responseJSON.result.action;
        fallbackText = jqXHR.responseJSON.result.fallback;
    }
    if (isEmpty(title) || isBlank(title)
        || isEmpty(details) || isBlank(details)) {

        // If in dashboard page
        if (typeof $("#default-translations-div") !== undefined) {
            let defaultErrorMessage = getDefaultErrorFromDashboardUI();
            title = defaultErrorMessage.errorTitle;
            details = defaultErrorMessage.errorDetails;
        } else {  // If outside dashboard page
            title = "Error";
            details = "Unable to Perform Operation";
        }
    }
    if (isEmpty(actionText) || isBlank(actionText)
        || isEmpty(fallbackText) || isBlank(fallbackText)) {
        actionText = "OK";
        fallbackText = "Report Issue";
    }

    try {
        showErrorAlert(title, details, actionText, fallbackText, onCloseCallback, onOpenCallback)
    } catch (e) {
        showErrorAlertWithBootstrapModal(title, details, actionText, fallbackText, onCloseCallback, onOpenCallback);
    }
}

export function showErrorAlert(title, details, actionText, fallbackText, onCloseCallback = null, onOpenCallback = null) {
    try {
        Swal.fire({
            icon: 'error',
            title: title,
            html: details,
            confirmButtonText: actionText,
            footer: '<a href="/cbt/users/help" target="_blank">' + fallbackText + '</a>',
            showCloseButton: true,
            allowOutsideClick: false,
            onOpen: () => {
                if (onOpenCallback !== null) {
                    onOpenCallback();
                }
            },
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        }).then((result) => {
            if (onCloseCallback !== null) {
                onCloseCallback()
            }
        });
    } catch (e) {
        showErrorAlertWithBootstrapModal(title, details, actionText, fallbackText, onCloseCallback, onOpenCallback);
    }
}

export function showSimpleInfoAlert(title, details, actionText, onCloseCallback = null,
                                    onOpenCallback = null, runOnCloseOnlyIfTriggered = false) {
    try {
        Swal.fire({
            icon: 'info',
            title: title,
            html: details,
            confirmButtonText: actionText,
            showCloseButton: true,
            allowOutsideClick: false,
            onOpen: () => {
                if (onOpenCallback !== null) {
                    onOpenCallback();
                }
            },
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        }).then((result) => {
            if (onCloseCallback !== null) {
                if (runOnCloseOnlyIfTriggered) {
                    if (result.value === true) {
                        console.log(result)
                        onCloseCallback();
                    }
                } else {
                    onCloseCallback()
                }

            }
        });
    } catch (e) {
    }
}

/**
 * Used to show error alert with bootstrap modal in case sweet alert
 * library experiences an issue on the client's browser. This also comes
 * in handy during integration tests, as the htmlunit (testing framework)
 * is not compatible with sweet alert. Hence all integration test error alerts
 * fallback to this function.
 * @param title title of alert message
 * @param details details of alert message
 * @param actionText text to show on OK button (for translation purpose)
 * @param fallbackText text to show on link to help centre page (for translation purpose)
 * @param onCloseCallback function to be executed ass soon as the pop-up closes
 * @param onOpenCallback function to be executed ass soon as the pop-up displays
 */
export function showErrorAlertWithBootstrapModal(title, details, actionText, fallbackText,
                                                 onCloseCallback = null, onOpenCallback = null) {
    document.getElementById("generic-error-alert-modal-msg-heading").innerText = title;
    document.getElementById("generic-error-alert-modal-msg-details").innerText = details;
    document.getElementById("generic-error-alert-modal-action-button").innerText = actionText;
    document.getElementById("generic-error-alert-modal-fallback-link").innerText = fallbackText;
    balanceModalPadding('generic-error-notification-modal');

    let genericBootstrapModal = $('#generic-error-notification-modal');

    genericBootstrapModal.modal('show');

    if (onOpenCallback !== null) {
        onOpenCallback();
    }

    genericBootstrapModal.on('hidden.bs.modal', function () {
        if (onCloseCallback != null) {
            onCloseCallback()
        }
    });

}

/**
 * Displays confirmation dialogue expecting user to respond OK/NOT OK
 * @param title question title
 * @param text question details
 * @param cancelLabel cancel button text
 * @param okLabel ok button text
 * @param okCallback callback to be executed if user clicks ok
 * @param cancelCallback callback to be executed if user clicks cancel
 */
export function showConfirmationDialogue(title, text, cancelLabel, okLabel, okCallback, cancelCallback,
                                         onOpenCallback = null) {
    if (title == null || typeof title === "undefined") {
        title = $("#generic-confirmation-alert-modal-msg-heading").data("translation-are-you-sure");
    }
    if (text == null || typeof text === "undefined") {
        text = $("#generic-confirmation-alert-modal-msg-details").data("translation-default-confirmation-question");
    }
    if (cancelLabel == null || typeof cancelLabel === "undefined") {
        cancelLabel = $("#generic-confirmation-alert-modal-cancel-button").data("translation-cancel");
    }
    if (okLabel == null || typeof okLabel === "undefined") {
        okLabel = $("#generic-confirmation-alert-modal-ok-button").data("translation-yes-please");
    }

    try {
        Swal.fire({
            title: title,
            html: text,
            icon: 'question',
            showCancelButton: true,
            cancelButtonText: cancelLabel,
            confirmButtonColor: '#1a54ba',
            cancelButtonColor: '#B22222',
            confirmButtonText: okLabel,
            reverseButtons: true,
            onOpen: function () {
                if (onOpenCallback != null) {
                    onOpenCallback();
                }

            },
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        }).then((result) => {
            if (result.value === true) {
                okCallback();
            } else if (cancelCallback != null) {
                cancelCallback();
            }
        });
    } catch (e) {
        showConfirmationDialogueWithBootstrapModal(title, text, cancelLabel, okLabel, okCallback, cancelCallback);
    }
}

/**
 * Displays confirmation dialogue expecting user to respond OK/NOT OK. Serves as
 * fallback for web browsers that do not support `Swal`
 *
 * @param title question title
 * @param text question details
 * @param cancelLabel cancel button text
 * @param okLabel ok button text
 * @param okCallback call back to be executed if user clicks ok
 * @param cancelCallback call back to be executed if user clicks cancel
 */
export function showConfirmationDialogueWithBootstrapModal(title, text, cancelLabel, okLabel, okCallback, cancelCallback) {
    if (title != null && typeof title !== "undefined") {
        document.getElementById("generic-confirmation-alert-modal-msg-heading").innerText = title;
    }

    if (text != null && typeof text !== "undefined") {
        document.getElementById("generic-confirmation-alert-modal-msg-details").innerText = text;
    }

    if (cancelLabel != null && typeof cancelLabel !== "undefined") {
        document.getElementById("generic-confirmation-alert-modal-cancel-button").innerText = cancelLabel;
    }

    if (okLabel != null && typeof okLabel !== "undefined") {
        document.getElementById("generic-confirmation-alert-modal-ok-button").innerText = okLabel;
    }

    $('#generic-confirmation-alert-modal-ok-button')
        .unbind("click").click(function (event) {
        if (okCallback != null) {
            okCallback();
        }
    });

    $('#generic-confirmation-alert-modal-cancel-button')
        .unbind("click").click(function (event) {
        if (cancelCallback != null) {
            cancelCallback();
        }
    });

    balanceModalPadding('generic-confirmation-notification-modal');
    $('#generic-confirmation-notification-modal').modal('show');
}

/**
 * Validates form entry
 * @param formId id of form whose validity is to be checked
 * @param reportInvalidity will report invalidity if set to true
 * @returns {boolean} return true if form content is valid and false if otherwise
 */
export function isValidFormEntry(formId, reportInvalidity = false) {
    let form = document.forms[formId];

    if (!form.checkValidity()) {
        if (reportInvalidity) {
            form.reportValidity();
        }
        return false;
    }
    return true
}

/**
 * Trim text content of a given set of html elements with the sma class name
 * @param className common class name to search for
 * @param toLen lengths of all texts within elements with the
 * given class name will be reduced to toLen
 */
export function trimTextContent(className, toLen) {
    let elements = document.getElementsByClassName(className);

    if (elements == null || elements.length === 0) {
        return;
    }

    let i, text, len;
    for (i = 0; i < elements.length; i++) {
        text = elements[i].textContent;
        len = text.length;
        if (len > toLen) {
            text = text.substr(0, toLen) + "...";
            elements[i].textContent = text;
        }
    }
}

/**
 * Used to trim text contents in an element
 * @param elementId element to examine
 * @param toLen length to trim text to if current
 * length is > toLen
 */
export function trimTextContentInElement(elementId, toLen) {
    $("." + elementId).each(function (i) {
        let len = $(this).text().trim().length;
        if (len > toLen) {
            $(this).text($(this).text().substr(0, toLen) + '...');
        }
    });
}

/**
 * Used to display loading animation
 */
export function showLoading() {
    document.getElementById("overlay").style.display = "block";
}

/**
 * Used to end the display of loading animation
 */
export function endShowLoading() {
    document.getElementById("overlay").style.display = "none";
}

/**
 * @param elementId id of element to set debouncing on
 * @param callback function to call after debouncing
 * @param durationMilli how long to wait (in milliseconds) before triggering callback
 */
export function addDebouncerToElement(elementId, callback, durationMilli) {
    let element = document.getElementById(elementId);
    if (element == null || typeof element === "undefined") {
        return;
    }
    let debouncedSearch = debounce(function () {
        let txt = element.value;
        callback(txt);
    }, durationMilli);
    try {
        // No API in javascript to check 'isListenerSet', hence this try catch
        element.removeEventListener('keyup', debouncedSearch);
    } catch (e) {
    }
    element.addEventListener('keyup', debouncedSearch);
}

/**
 * Returns a function, that, as long as it continues to be invoked, will not
 * be triggered. The function will be called after it stops being called for
 * N milliseconds. If `immediate` is passed, trigger the function on the
 * leading edge, instead of the trailing.
 * Source: https://davidwalsh.name/javascript-debounce-function
 * @param func throttling function
 * @param wait wait time in milliseconds
 * @param immediate true if function should be fired immediately and false if otherwise
 * @returns {function(...[*]=)}
 */
export function debounce(func, wait, immediate) {
    let timeout;
    return function () {
        let context = this, args = arguments;
        let later = function () {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        let callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
}

/**
 * Used to check the text content of two input fields to be sure they are the same.
 * An example use case is for password & password confirmation match in sign up form
 */
export function areContentsTheSame(element1Id, element2Id, reportResult, errorMsg) {
    const element1 = document.getElementById(element1Id);
    const element2 = document.getElementById(element2Id);

    if (element1.value === element2.value) {
        if (reportResult) {
            try {
                element2.setCustomValidity('');
            } catch (e) {
                // browser not HTML 5 compliant
            }
        }
        return true;
    } else {
        if (reportResult) {
            try {
                element2.setCustomValidity(errorMsg);
            } catch (e) {
                // browser not HTML 5 compliant
            }
        }
        return false;
    }
}

/**
 * Used to balance padding of modals, as they
 * are shown by default with a right padding of 12px
 * @param modalId modal to balance
 * @param leftPadding left padding value
 * @param rightPadding right padding value
 */
export function balanceModalPadding(modalId, leftPadding = '14px', rightPadding = '10px') {

    let specificModal = document.getElementById(modalId);

    if (specificModal != null) {
        specificModal.style.paddingLeft = leftPadding;
        specificModal.style.paddingRight = rightPadding;

        let jQuerySelection = $('#' + modalId);
        jQuerySelection.on('shown.bs.modal', function () {
            jQuerySelection.css('padding-left', leftPadding);
            jQuerySelection.css('padding-right', rightPadding);
        });
    }
}

/**
 * Adds listener to a button
 * @param buttonName name of submit button
 * @param validator form validator function
 * @param fun function to call after validation
 */
export function addListenerToButton(buttonName, validator, fun) {
    $('#' + buttonName).unbind("click").click(function (event) {
        event.preventDefault();
        if (validator()) {
            fun();
        }
    });
}

/**
 *
 * @param buttonName name of submit button
 * @param formId id of form controlled by the submit button
 * @param fun function to call when submit button is pressed
 */
export function addListenerToButtonInForm(buttonName, formId, fun) {
    $('#' + buttonName).unbind("click").click(function (event) {
        event.preventDefault();
        if (isValidFormEntry(formId, true)) {
            fun();
        }
    });
}

/**
 * Alerts user of the success of their operation
 * @param title title of alert
 * @param text details of alert
 * @param onCloseCallback function to run after user presses ok (if any)
 * @param onOpenCallback function to run when pop up shows
 */
export function alertSimpleSuccess(title, text, onCloseCallback = null, onOpenCallback = null) {
    try {
        Swal.fire({
            icon: 'success',
            title: title,
            html: text,
            showConfirmButton: true,
            allowOutsideClick: false,
            onOpen: () => {
                if (onOpenCallback != null) {
                    onOpenCallback();
                }
            },
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        }).then((result) => {
            if (result.value) {
                if (onCloseCallback != null) {
                    onCloseCallback();
                }
            }
        })
    } catch (e) {
        // In case SweetAlert doesnt work on client browser
        bootstrapModalSuccessAlert(title, text, onCloseCallback);
    }
}

export function bootstrapModalSuccessAlert(title, text, fun) {
    document.getElementById("generic-success-alert-modal-msg-heading").innerText = title;
    document.getElementById("generic-success-alert-modal-msg-details").innerText = text;
    $('#generic-success-alert-modal-ok-button').unbind("click").click(function () {
        if (fun != null) {
            fun();
        }
    });
    balanceModalPadding('generic-success-notification-modal');
    $('#generic-success-notification-modal').modal('show');
}

export function showSuccessToast(title, text, onCloseCallback = null, onOpenCallback = null) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000, // 3 seconds
        timerProgressBar: true,
        onOpen: (toast) => {
            if (onCloseCallback != null) {
                onCloseCallback(); // do not wait for toast countdown to complete before executing onCloseCallback
            }
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        },
    })

    if (isValidStr(text)) {
        Toast.fire({
            icon: 'success',
            title: title,
            html: text,
        })
    } else {
        Toast.fire({
            icon: 'success',
            title: title
        })
    }

}

/**
 * Used to attach a progress bar showing password strength to a password
 * input field
 * @param inputElementId input element where password will be entered
 * @param progressBarId id of progress bar to be used to display password strength
 */
export function enablePasswordStrengthEvaluation(inputElementId, progressBarId) {
    let inputElement = document.getElementById(inputElementId);
    $("#" + inputElementId).unbind("keyup").on("keyup", function () {
        moveProgressBar(inputElement, progressBarId);
    });
}

/**
 * Used to move progress bar
 * @param inputElement progress bar
 * @param progressBarId id of progress bar
 */
export function moveProgressBar(inputElement, progressBarId) {
    let progressBar = $("#" + progressBarId);
    let progressBarElement = document.getElementById(progressBarId);
    let inputVal = inputElement.value

    // Reset if password length is zero
    if (inputVal.length === 0) {
        progressBarElement.style.width = "0%";
        return;
    }

    // Check progress
    let progressVal = [/[A-Z]/, /[0-9]/, /[a-z]/]
        .reduce((memo, test) => memo + test.test(inputVal), 0);

    // Length must be at least 8 chars
    if (inputVal.length >= 8) {
        progressVal++;
    }
    progressVal = progressVal * 25;

    // Display Strength
    if (progressVal <= 25) {
        progressBar.removeClass("bg-warning");
        progressBar.removeClass("bg-info");
        progressBar.removeClass("bg-success");
        progressBar.addClass("bg-danger");
    } else if (progressVal <= 50) {
        progressBar.removeClass("bg-danger");
        progressBar.removeClass("bg-info");
        progressBar.removeClass("bg-success");
        progressBar.addClass("bg-warning");
    } else if (progressVal <= 75) {
        progressBar.removeClass("bg-warning");
        progressBar.removeClass("bg-danger");
        progressBar.removeClass("bg-success");
        progressBar.addClass("bg-info");
    } else if (progressVal <= 100) {
        progressBar.removeClass("bg-warning");
        progressBar.removeClass("bg-info");
        progressBar.removeClass("bg-danger");
        progressBar.addClass("bg-success");
    }
    progressBarElement.style.width = progressVal + "%";
}

export function enablePasswordMatchConfirmation(mainPasswordInputId,
                                                confirmationPasswordInputId,
                                                matchIndicatorId) {
    $(document).ready(function () {
        $("#" + confirmationPasswordInputId).keyup(function () {
            let passwordMatchIndicator = $("#" + matchIndicatorId);
            let passwordVal = $("#" + mainPasswordInputId).val();
            let confirmPasswordVal = $("#" + confirmationPasswordInputId).val();

            if (passwordVal !== confirmPasswordVal) {
                if (passwordVal.length <= confirmPasswordVal.length) {
                    passwordMatchIndicator.css("color", "darkred");
                    passwordMatchIndicator.removeClass("fa-check-circle");
                    // JQuery internally checks if class already exist before adding
                    passwordMatchIndicator.addClass("far");
                    passwordMatchIndicator.addClass("fa-times-circle")
                } else {
                    passwordMatchIndicator.removeClass("far");
                    passwordMatchIndicator.removeClass("fa-times-circle");
                    passwordMatchIndicator.removeClass("fa-check-circle");
                }
            } else {
                passwordMatchIndicator.css("color", "var(--bs-success)");
                passwordMatchIndicator.removeClass("fa-times-circle");
                // JQuery internally checks if class already exist before adding
                passwordMatchIndicator.addClass("far");
                passwordMatchIndicator.addClass("fa-check-circle")
            }
        });
    });
}

/**
 * Used to make sure uri is kept the same when language is changed.
 * Normally, the query string is lost during the refesh of the page
 * when language is changed. This function helps prevent that.
 * @param classOnElement
 */
export function reWriteLanguageAnchorRef(classOnElement) {
    let currentUri = window.location.href;

    let elements = document
        .getElementsByClassName(classOnElement);

    if (typeof elements === undefined) {
        return
    }

    let i, appender, currentHref, currentLang;
    for (i = 0; i < elements.length; i++) {
        currentHref = elements[i].getAttribute("href");
        appender = "?";

        currentLang = getParameterByName(currentUri, "lang");

        if (currentLang !== "") {
            currentUri = currentUri.replace("?lang=" + currentLang, "");
            currentUri = currentUri.replace("&lang=" + currentLang, "");
        }

        if (currentUri.includes("?")) {
            appender = "&";
        }
        currentHref = currentHref.replace("?", appender);
        elements[i].href = currentUri + currentHref;
    }
}

export function enableAddBackgroundToMenuBarOnScroll() {
    $(window).scroll(function () {
        if ($(document).scrollTop() > 100) {
            $("nav").addClass("scrolled");
        } else {
            $("nav").removeClass("scrolled");
        }
    });
}

/**
 * Used to get query string values by name.
 * Source: https://stackoverflow.com/a/25801565/3151251
 * @param uri uri to search in
 * @param name name of query parameter to find
 * @returns {string} value found or empty string if nothing is found
 */
export function getParameterByName(uri, name) {
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    let regexS = "[\\?&]" + name.toLowerCase() + "=([^&#]*)";
    let regex = new RegExp(regexS);
    let results = regex.exec(uri.toLowerCase());
    if (results == null)
        return "";
    else
        return decodeURIComponent(results[1].replace(/\+/g, " "));
}

export function initializeCalendar(calendarId) {
    $('#' + calendarId).datepicker({
        title: "",
        changeMonth: true,
        changeYear: true,
        format: "dd/mm/yyyy",
        todayBtn: "linked",
        autoclose: true,
        clearBtn: true,
        todayHighlight: true,
        language: getUserPreferredLanguage(),
    });
}

export function initializeCalendarByClass(classId) {
    $('.' + classId).datepicker({
        title: "",
        changeMonth: true,
        changeYear: true,
        format: "dd/mm/yyyy",
        todayBtn: "linked",
        autoclose: true,
        clearBtn: true,
        todayHighlight: true,
        language: getUserPreferredLanguage(),
    });
}

export function initializeAllTimePickersOnPage(elementsCls) {
    mdtimepicker(document.querySelectorAll('.' + elementsCls));
}

export function extractDate(elementId) {
    let dateInput = document.getElementById(elementId);
    let dateStr = dateInput.value;
    //TODO: Use RegExp that validates leap year
    let dateParts = dateStr.split('/');
    let matchResult = dateStr.match(/^(0?[1-9]|[12][0-9]|3[01])[\/](0?[1-9]|1[012])[\/]\d{4}$/);
    if (dateParts.length !== 3 || matchResult == null) {
        return null;
    }
    let dateValue = new Date(Date.UTC(dateParts[2], dateParts[1] - 1, dateParts[0]));
    return dateValue.getTime();
}

/**
 * Used to create counter animation in a web page
 */
export function setUpStatsCounter() {
    (function ($) {
        $.fn.countTo = function (options) {
            options = options || {};

            return $(this).each(function () {
                // set options for current element
                let settings = $.extend({}, $.fn.countTo.defaults, {
                    from: $(this).data('from'),
                    to: $(this).data('to'),
                    speed: $(this).data('speed'),
                    refreshInterval: $(this).data('refresh-interval'),
                    decimals: $(this).data('decimals')
                }, options);

                // how many times to update the value, and how much to increment the value on each update
                let loops = Math.ceil(settings.speed / settings.refreshInterval),
                    increment = (settings.to - settings.from) / loops;

                // references & variables that will change with each update
                let self = this,
                    $self = $(this),
                    loopCount = 0,
                    value = settings.from,
                    data = $self.data('countTo') || {};

                $self.data('countTo', data);

                // if an existing interval can be found, clear it first
                if (data.interval) {
                    clearInterval(data.interval);
                }
                data.interval = setInterval(updateTimer, settings.refreshInterval);

                // initialize the element with the starting value
                render(value);

                function updateTimer() {
                    value += increment;
                    loopCount++;

                    render(value);

                    if (typeof (settings.onUpdate) == 'function') {
                        settings.onUpdate.call(self, value);
                    }

                    if (loopCount >= loops) {
                        // remove the interval
                        $self.removeData('countTo');
                        clearInterval(data.interval);
                        value = settings.to;

                        if (typeof (settings.onComplete) == 'function') {
                            settings.onComplete.call(self, value);
                        }
                    }
                }

                function render(value) {
                    let formattedValue = settings.formatter.call(self, value, settings);
                    $self.html(formattedValue);
                }
            });
        };

        $.fn.countTo.defaults = {
            from: 0,               // the number the element should start at
            to: 0,                 // the number the element should end at
            speed: 1000,           // how long it should take to count between the target numbers
            refreshInterval: 100,  // how often the element should be updated
            decimals: 0,           // the number of decimal places to show
            formatter: formatter,  // handler for formatting the value before rendering
            onUpdate: null,        // callback method for every time the element is updated
            onComplete: null       // callback method for when the element finishes updating
        };

        function formatter(value, settings) {
            return value.toFixed(settings.decimals);
        }
    }(jQuery));

    jQuery(function ($) {
        // custom formatting example
        $('.count-number').data('countToOptions', {
            formatter: function (value, options) {
                return value.toFixed(options.decimals).replace(/\B(?=(?:\d{3})+(?!\d))/g, ',');
            }
        });

        // start all the timers
        $('.timer').each(count);

        function count(options) {
            let $this = $(this);
            options = $.extend({}, options || {}, $this.data('countToOptions') || {});
            $this.countTo(options);
        }
    });
}

export function getUserPreferredLanguage() {
    let userPreferredLanguageDiv = $("#user-preferred-language");
    if (userPreferredLanguageDiv === null || userPreferredLanguageDiv === undefined) {
        return 'en'
    }

    let validLanguages = ['de', 'en', 'es', 'fr', 'ha', 'ig', 'pt', 'nl', 'sw', 'yo']
    let preferredLanguage = userPreferredLanguageDiv.data("preferred-language");

    if (!isValidStr(preferredLanguage) ||
        !validLanguages.includes(preferredLanguage)) {
        return 'en'
    }

    return preferredLanguage
}

export function setUpDataTable(tableId) {
    let preferredLanguage = getUserPreferredLanguage();
    if (preferredLanguage !== null && preferredLanguage !== undefined
        && preferredLanguage !== 'en') {
        $('#' + tableId).DataTable({
            language: {
                url: '/cbt/js/translation/data-table/' + preferredLanguage + '.json'
            }
        });
    } else {
        $('#' + tableId).DataTable();
    }
}

/**
 * Toggle the status of a checkbox and re-add back listener
 * @param switchId switchId
 * @param currentStatus current status
 * @param listener listener to re-add
 */
export function toggleSwitch(switchId, currentStatus, listener) {
    let boxToToggle = $("#" + $.escapeSelector(switchId));
    boxToToggle.unbind("change"); // Remove listener
    boxToToggle.prop("checked", !currentStatus); // Revert switch
    if (listener != null) {
        listener(); // Add back listener
    }
}

/**
 * Initialize Js Draggable on page
 * Source: https://johnny.github.io/jquery-sortable/
 *
 * @param cssSelector css selector e.g 'ol.my-class' will
 * look for an ordered list with class 'my-class'
 * @param onDropCb function to call when drop succeeds
 * @param onDragStartCb function to call on start drag
 * @param onDragCb function to call onDrag
 */
export function initializeDraggable(cssSelector, onDropCb, onDragStartCb, onDragCb, numberDisplaySelector = null) {
    let adjustment;
    $(cssSelector).sortable({
        group: generateRandomStr(10),
        pullPlaceholder: false,
        dropOnEmpty: true,
        // animation on drop
        onDrop: function ($item, container, _super, event) {
            let $clonedItem = $('<li/>').css({height: 0});
            $item.before($clonedItem);
            $clonedItem.animate({'height': $item.height()});

            $item.animate($clonedItem.position(), function () {
                $clonedItem.detach();
                _super($item, container);
            });

            if (numberDisplaySelector != null) {
                $(numberDisplaySelector).each(function (i) {
                    let number = i + 1;
                    $(this).html(number + '');
                });
            }

            if (onDropCb != null) {
                onDropCb($item, container, _super, event);
            }

            return false;

        },

        // set $item relative to cursor position
        onDragStart: function ($item, container, _super, event) {
            let offset = $item.offset(),
                pointer = container.rootGroup.pointer;

            adjustment = {
                left: pointer.left - offset.left,
                top: pointer.top - offset.top
            };

            _super($item, container);

            if (onDragStartCb != null) {
                onDragStartCb($item, container, _super, event);
            }
        },
        onDrag: function ($item, position, _super, event) {
            $item.css({
                left: position.left - adjustment.left,
                top: position.top - adjustment.top
            });

            if (onDragCb != null) {
                onDragCb($item, position, _super, event);
            }

        }
    });
}

/**
 * Initializes all bootstrap select objects on page and adds
 * border colour to them
 */
export function initializeBootstrapSelect() {
    try {
        $(".selectpicker").selectpicker('refresh');

        // Add border to all select boxes
        let allSelectBoxes = document.getElementsByClassName(
            "btn dropdown-toggle bs-placeholder");

        if (allSelectBoxes == null
            || typeof allSelectBoxes === "undefined") {
            return;
        }

        let numOfSelectBoxes = allSelectBoxes.length;
        for (let i = 0; i < numOfSelectBoxes; i++) {
            allSelectBoxes[i].style.borderColor = "#050505";
        }
    } catch (e) {
    }

}

export function toggleElementVisibility(elementId) {
    let element = document.getElementById(elementId);
    if (element == null || typeof element === "undefined") {
        return;
    }

    if (element.style.display === "none") {
        element.style.display = "block";
    } else {
        element.style.display = "none";
    }
}

export function showElement(elementId) {
    let element = document.getElementById(elementId);
    element.style.display = "";
}

export function hideElement(elementId) {
    let element = document.getElementById(elementId);
    element.style.display = "none";
}

export function getDefaultErrorFromDashboardUI() {
    let translationsDiv = $("#default-translations-div");
    return {
        "errorTitle": translationsDiv.data("general-error-title"),
        "errorDetails": translationsDiv.data("general-error-details")
    }
}

export function addListenerToLoadThumbnail() {
    $(".require-thumbnail").unbind("change").change(function (event) {
        event.preventDefault();
        let output = document.getElementById($(this).data("thumbnail-display-id"));
        output.src = URL.createObjectURL(event.target.files[0]);
    });
}

export function addListenerToClearInputThumbnail() {

    $('.thumbnail-clearer').unbind("click").click(function (event) {
        event.preventDefault();

        let thumbnailShowerId = $(this).data("shower-id");
        let inputId = $(this).data("input-element-id");

        // Clear thumbnail
        let output = document.getElementById(thumbnailShowerId);
        output.src = "";

        // Clear input
        let inputElement = document.getElementById(inputId);
        inputElement.value = [];
        inputElement.files = null;
        inputElement.type = '';
        inputElement.type = 'file';
    });
}

/**
 * Return the value of the selected drop down option
 * @param id id of dropdown element
 * @returns value of selected option
 */
export function getDropDownElementSelection(id) {
    let element = document.getElementById(id);
    return element.options[element.selectedIndex].value;
}

export function sanitizeHtmlInput(htmlString) {
    return purify.sanitize(htmlString, {ADD_TAGS: ALLOWED_HTML_TAGS_EXTRA, ADD_ATTR: ALLOWED_HTML_ATTRIBUTES_EXTRA});
}

export function renderMathsInThisElement(element) {
    renderMathInElement(element);
}

export function renderMathsInElementWithId(elementId) {
    renderMathsInThisElement(document.getElementById(elementId));
}

export function cleanReusableModalOnClose() {
    $('#default-dashboard-modal').unbind("hidden.bs.modal").on('hidden.bs.modal', function (e) {
        let reusableModal = $("#re-usable-modal-pane");
        if (reusableModal !== null) {
            reusableModal.html('');
        }
    })
}

export function fadeOutOldAndFadeInNewView(elementClassSelector,
                                           executionDuringFadeInAnimation = null,
                                           executionDuringFadeOutAnimation = null) {
    // Fade out a view
    animateView(
        elementClassSelector,
        "fadeOut",
        "animate__",
        executionDuringFadeInAnimation,
        executionDuringFadeOutAnimation).then((message) => {
        // Fade in new view
        animateView(
            elementClassSelector,
            "fadeIn",
            "animate__",
            executionDuringFadeInAnimation,
            executionDuringFadeOutAnimation);
    });

}

export const animateView = (element, animation, prefix = "animate__",
                            executionDuringFadeInAnimation = null,
                            executionDuringFadeOutAnimation = null) =>
    // We create a Promise and return it
    new Promise((resolve, reject) => {
        const animationName = `${prefix}${animation}`;
        const node = document.querySelector(element);

        node.classList.add(`${prefix}animated`, animationName);

        if (animation === "fadeOut" && executionDuringFadeOutAnimation !== null) {
            executionDuringFadeOutAnimation();
        }

        if (animation === "fadeIn" && executionDuringFadeInAnimation !== null) {
            executionDuringFadeInAnimation();
        }

        // When the animation ends, we clean the classes and resolve the Promise
        function handleAnimationEnd(event) {
            event.stopPropagation();
            node.classList.remove(`${prefix}animated`, animationName);
            resolve('Animation ended');
        }

        node.addEventListener('animationend', handleAnimationEnd, {once: true});
    });

/**
 *
 * @param data html data to place inside the re-usable modal
 */
export function showReusableModal(data) {
    let reusableModal = $("#re-usable-modal-pane");
    reusableModal.html('');
    reusableModal.html(data);
    $("#default-dashboard-modal").modal('show');
}


/**
 * Used to post create a form and post. Post operations do not show request params
 * in browser address bar. This post form method can be used in such cases where
 * developer does not want detail sent to the end-point to show on the address bar
 * @param path path
 * @param params map of key value pairs
 */
export function makeAndPostForm(path, params) {
    let form = document.createElement('form');
    form.setAttribute("method", "post");
    form.setAttribute('action', path);

    for (let key in params) {
        if (params.hasOwnProperty(key)) {
            let hiddenField = document.createElement('input');
            hiddenField.setAttribute('type', 'hidden');
            hiddenField.setAttribute('name', key);
            hiddenField.setAttribute('value', params[key]);

            form.appendChild(hiddenField);
        }
    }

    document.body.appendChild(form);
    form.submit();
}

export function triggerShowAppAdvert() {
    setTimeout(function () {
        showAppAdvert()
    }, 2000);
}

function showAppAdvert() {
    if (isValidStr(getCookie(GREEN_BRIDGE_COOKIE_APP_ADVERT_WAS_SHOWN))) {
        return
    }

    let bannerUrl = '/cbt/images/banner/bnr_app_advert.png';
    if (isMobileScreen()) {
        bannerUrl = '/cbt/images/banner/bnr_app_advert_mobile.png'
    }

    try {
        Swal.fire({
            imageUrl: bannerUrl,
            width: 1024,
            imageAlt: 'Download App Banner',
            showConfirmButton: true,
            allowOutsideClick: false,
            showCloseButton: true,
            confirmButtonText: 'Download App',
            confirmButtonColor: '#3d8644',
            footer: '<a href="https://www.instagram.com/greenbridgecbt/" target="_blank">Connect With Us on Instagram</a>',
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            },
            onOpen: () => {
                setCookie(GREEN_BRIDGE_COOKIE_APP_ADVERT_WAS_SHOWN, "Yes",
                    0.010416667, // 15 minutes
                )
            },

        }).then((result) => {
            if (result.value === true) {
                let mobileAppUrl = 'https://play.google.com/store/apps/details?id=com.eaglebeacon.learning.mobile.greenbridge.prod'
                window.open(mobileAppUrl, "_blank");
            }
        })
    } catch (ignore) {
        // In case SweetAlert does not work on client browser
    }
}

function isMobileScreen() {
    return ((window.innerWidth <= 800));
}