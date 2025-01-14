import {isValidStr} from "./util.js";
import {alertError, alertSimpleSuccess, showSuccessToast} from "./uiutil.js";

export function sendPutOrPostRequest(url, payload, successOnCloseCallback, failureOnCloseCallback,
                                     alwaysCallback, submitBtnId, requestType,
                                     successOnOpenCallback = null, failureOnOpenCallback = null) {

    if (!isValidStr(requestType)) {
        requestType = 'PUT'
    }

    if (requestType !== "PUT" && requestType !== "POST") {
        return;
    }

    if (isValidStr(submitBtnId)) {
        $("#" + submitBtnId).attr('disabled', true);
    }

    $.ajax({
        async: true,
        type: requestType,
        url: url,
        headers: getDefaultHttpHeaders(),
        contentType: "application/json; charset=utf-8",
        data: String(payload),
        cache: false,
        timeout: 10000,
    })
        .done(function (data) {
            try {
                processAjaxSuccessResult(data, successOnCloseCallback, successOnOpenCallback);
            } catch (e) {
            }
        })
        .fail(function (jqXHR, textStatus) {
            try {
                processAjaxErrorResponse(jqXHR, textStatus, failureOnCloseCallback, failureOnOpenCallback);
            } catch (e) {
            }
        })
        .always(function () {
            if (isValidStr(submitBtnId)) {
                $("#" + submitBtnId).attr('disabled', false);
            }

            if (alwaysCallback != null) {
                alwaysCallback();
            }
        });
}

export function sendDeleteRequest(url, successOnCloseCallback, failureOnCloseCallback,
                                  alwaysCallback, submitBtnId,
                                  successOnOpenCallback = null,
                                  failureOnOpenCallback = null) {

    if (isValidStr(submitBtnId)) {
        $("#" + submitBtnId).attr('disabled', true);
    }

    $.ajax({
        async: true,
        type: 'DELETE',
        url: url,
        headers: getDefaultHttpHeaders(),
        contentType: "application/json; charset=utf-8",
        cache: false,
        timeout: 10000,
    })
        .done(function (data) {
            try {
                processAjaxSuccessResult(data, successOnCloseCallback, successOnOpenCallback);
            } catch (e) {
            }

        })
        .fail(function (jqXHR, textStatus) {
            try {
                processAjaxErrorResponse(jqXHR, textStatus, failureOnCloseCallback, failureOnOpenCallback);
            } catch (e) {
            }

        })
        .always(function () {
            if (isValidStr(submitBtnId)) {
                $("#" + submitBtnId).attr('disabled', false);
            }

            if (alwaysCallback != null) {
                alwaysCallback();
            }
        });
}

export function sendGetRequest(url, successCallback, failureCallback,
                               alwaysCallback, submitBtnId) {

    if (isValidStr(submitBtnId)) {
        $("#" + submitBtnId).attr('disabled', true);
    }

    $.ajax({
        async: true,
        type: 'GET',
        url: url,
        headers: getDefaultHttpHeaders(),
        contentType: "application/json; charset=utf-8",
        cache: false,
        timeout: 10000,
    })
        .done(function (data) {
            try {
                if (successCallback != null) {
                    successCallback(data);
                }
            } catch (e) {
            }
        })
        .fail(function (jqXHR, textStatus) {
            try {
                processAjaxErrorResponse(jqXHR, textStatus, failureCallback);
            } catch (e) {
            }
        })
        .always(function () {
            if (isValidStr(submitBtnId)) {
                $("#" + submitBtnId).attr('disabled', false);
            }

            if (alwaysCallback != null) {
                alwaysCallback();
            }
        });
}


export function processAjaxSuccessResult(data, onCloseCallback = null, onOpenCallback = null) {
    // If there is no success message to display
    if (typeof data === 'string' && !isValidStr(data)) {
        if (onCloseCallback != null) {
            onCloseCallback();
        }

        return;
    }

    let result;
    if (typeof data === 'string') {
        result = JSON.parse(data);
    } else {
        result = JSON.parse(JSON.stringify(data))
    }
    if (result.displayAsToast === "true") {
        showSuccessToast(result.alertTitle, result.alertDetails,
            onCloseCallback, onOpenCallback);
    } else {
        alertSimpleSuccess(result.alertTitle,
            result.alertDetails, onCloseCallback, onOpenCallback);
    }

}

export function processAjaxErrorResponse(jqXHR, textStatus, onCloseCallback = null, onOpenCallback = null) {
    if (jqXHR.status === 302) {
        window.location.href = jqXHR.responseText;
        return;
    }

    try {
        alertError(jqXHR, textStatus, onCloseCallback, onOpenCallback);
    } catch (ignore) {
        if (onCloseCallback !== null) {
            onCloseCallback();
        }
    }

}

export function getDefaultHttpHeaders() {
    return {"dashboard-view-id": window.sessionStorage.getItem("dashboard-view-id")};
}
