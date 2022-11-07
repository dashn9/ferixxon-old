const NUMBEROFPAGES = 4, UIFILLERTIME = 1.5, PAGETRANSITIONTIME = 0.15;
var customPrompts = {
    messager: document.getElementsByClassName("message-overlay")[0],
    interactionBlocker: document.getElementById("interaction-blocker"),
    confirm: (question, yesCallBack, noCallBack) => {
        let messager = customPrompts.messager;
        customPrompts.interactionBlocker.style.display = "block";
        messager.children[2].style.display = "none";
        messager.children[3].style.display = "block";
        messager.children[1].innerHTML = question;
        messager.classList.add("message-overlay-active");

        messager.lastElementChild.lastElementChild.onclick = function () {
            customPrompts.messager.classList.remove("message-overlay-active");
            customPrompts.interactionBlocker.style.display = "none";
            if (noCallBack) {
                noCallBack();
            }
        };

        messager.lastElementChild.firstElementChild.onclick = function () {
            customPrompts.messager.classList.remove("message-overlay-active");
            customPrompts.interactionBlocker.style.display = "none";
            yesCallBack();
        };
    },
    prompt: (statement, inputPlaceHolder, callBack, minInLen = 1, maxInLen = 20) => {
        let messager = customPrompts.messager;
        customPrompts.interactionBlocker.style.display = "block";
        messager.children[2].style.display = "block";
        messager.children[3].style.display = "none";
        messager.children[1].innerHTML = statement;
        messager.classList.add("message-overlay-active");


        messager.children[2].firstElementChild.placeholder = inputPlaceHolder;
        messager.children[2].lastElementChild.onclick = function () {

            if (messager.children[2].firstElementChild.value.length >= minInLen && messager.children[2].firstElementChild.value.length <= maxInLen) {
                customPrompts.interactionBlocker.style.display = "none";
                customPrompts.messager.classList.remove("message-overlay-active");
                callBack(messager.children[2].firstElementChild.value);
                messager.children[2].firstElementChild.value = "";
            }
            else {
                customPrompts.alert("The minimum length of your input must be " + minInLen + ", maximum is " + maxInLen);
            }
        };
    },
    alert: (statement) => {
        let messager = customPrompts.messager;
        customPrompts.interactionBlocker.style.display = "block";
        messager.children[2].style.display = "none";
        messager.children[3].style.display = "none";
        messager.children[1].innerHTML = statement;
        messager.classList.add("message-overlay-active");
    },
    hideMessager: () => {
        customPrompts.messager.classList.remove("message-overlay-active");
        customPrompts.interactionBlocker.style.display = "none";
    },
};
var cartPageHandler = {
    //I don't comment a lot, but since JS doesn't have an explicit inbuilt functionality to mark Object values as private, i feel inclined to tell you not to f**king change this variable but you can read it :-)
    pageState: 1,
    UIpageStateFillerWidth: 0,
    pageTransitionActive: 0,
    parsedDeliveryDetails: null,
    subPageTransitionLock: false,
    createXhr: () => {
        try {
            return new XMLHttpRequest();
        } catch (exception) {
            try {
                return new ActiveXObject("Microsoft.XMLHTTP");
            } catch (exception) {
                return new ActiveXObject("Msxml2.XMLHTTP");
            }
        }
    },
    validateDeliveryDetails: () => {
        let tempAddrEl = document.getElementById("temp-address");
        let tempEmailEl = document.getElementById("email");
        let tempTelEl = document.getElementById("tel");
        let addrEl = document.querySelector('input[name="addr"]:checked');
        let orderTitle = document.getElementById("order-title");
        let orderDesc = document.getElementById("order-description");
        if (addrEl) {
            if (!(tempAddrEl.value || addrEl.value)) {
                customPrompts.alert("You need specify an address you want us to deliver to")
                return false;
            }
            if (tempAddrEl.value && addrEl.value) {
                customPrompts.alert("You can't attempt to use two addresses at the same")
                return false;
            }
        }
        else {
            if (!tempAddrEl.value) {
                customPrompts.alert("Please specify an address");
                return false;
            }
        }
        addr = "";
        if(tempAddrEl.value) {
            addr = tempAddrEl.value;
        }
        if(addrEl) {
            addr = addrEl.value;
        }
        cartPageHandler.parsedDeliveryDetails = `addr=${addr}&email=${tempEmailEl.value}&tel=${tempTelEl.value}&order_title=${orderTitle.value}&order_desc=${orderDesc.value}`;
        return true;
    },
    remorphPaystackProcessor: () => {
        paystackBackground = document.querySelector("[id^=\"inline-background-\"]");
        paystackBackground.style.display = "none";

        paystackMainFrame = document.querySelector("[id^=\"inline-checkout-\"]");
        paystackMainFrame.style.position = "initial";
        paystackMainFrame.style.minHeight = "75vh";
        paystackMainFrame.style.margin = "3% 0 0 0";
    },
    terminatePaystack: () => {
        paystackMainFrame = document.querySelector("[id^=\"inline-checkout-\"]");
        paystackBackground = document.querySelector("[id^=\"inline-background-\"]");
        if (paystackBackground) {
            paystackBackground.remove();
        }
        if (paystackMainFrame) {
            paystackMainFrame.remove();
        }
        cartPageHandler.activateTransactionStatusBanner("Your Pending Payment Process via Paystack Was Terminated");
        //paystackMainFrame.contentWindow.document.getElementsByTagName("section")[0].style.padding = "0";
        //paystackMainFrame.contentWindow.document.getElementsByTagName("footer")[0].style.padding = "6px 0 0 0";
    },
    saveReceiptToPDF: () => {
        window.jsPDF = window.jspdf.jsPDF;
        var receiptPDF = new jsPDF();
        receiptPDF.html(document.getElementById("receipt-view"), {
            callback: function(receiptPDF) {
                // Save the PDF
                receiptPDF.save('order-receipt.pdf');
            },
            x: 15,
            y: 15,
            width: 170, //target width in the PDF document
            windowWidth: 650 //window width in CSS pixels
        });
    },
    activateTransactionStatusBanner: (text, type="error") => {
        let banner = document.getElementsByClassName("status-banner")[0];
        banner.style.display = "block";
        document.getElementById("status-banner-text").innerHTML = text;
        if(type == "success") {
            banner.style.backgroundColor = "#34b514";
        }
        else {
            banner.style.backgroundColor = "red";
            setTimeout(function() {banner.style.display = "none";}, 8000);
        }
        
    },
    initializeTransaction: () => {
        function paystack(access_code) {
            const paystack = new PaystackPop();
            async function payWithPaystack(access_code) {
                const transactionObj = {
                    accessCode: access_code,
                    onLoad: (transaction) => {
                    },
                    onError: (message) => {
                        cartPageHandler.activateTransactionStatusBanner("An Unknown Error Occurred During Your Transaction");
                        console.log("An Transaction Error has occured, Error: " + message);
                    },
                    onSuccess: (transaction) => {
                        cartPageHandler.activateTransactionStatusBanner("Your Order Has Been Successfully Placed <a href=\"https://www.ferixxon.com\">Go Home</a>", "success");
                        document.getElementById("transaction-reference-info").getElementsByClassName("receipt-info-values")[0].innerHTML = transaction.reference;
                        document.getElementById("transaction-info").getElementsByClassName("receipt-info-values")[0].innerHTML = transaction.transaction;
                        document.getElementById("transaction-message-info").getElementsByClassName("receipt-info-values")[0].innerHTML = transaction.message;
                        document.getElementById("payment-status-info").getElementsByClassName("receipt-info-values")[0].innerHTML = transaction.status;
                        cartProcessor.fowardPage();
                    },
                    onCancel: () => {
                        cartPageHandler.activateTransactionStatusBanner("Your Pending Payment Process via Paystack Was Terminated");
                        cartProcessor.reversePage();
                    }
                };
                try {
                    const transaction = await paystack.checkout(transactionObj);
                } catch (error) {
                    console.log("Error: ", error);
                }
            }
            payWithPaystack(access_code);
        }
        
        //payWithPaystack();
        let xhr = cartPageHandler.createXhr();
        xhr.onreadystatechange = () => {
            if (xhr.readyState == 4 && xhr.status == 200) {
                response = xhr.responseText;
                try {
                    if(!response)  {
                        customPrompts.alert("Did not receive any response which is odd, if you have any issues, please contact the administrator");
                        cartProcessor.changePage(1);
                    }
                    else {
                        let parsedData = JSON.parse(response);
                        let parsedDataStatus = parsedData.type;
                        if (parsedDataStatus != "SUCCESS") {
                            cartProcessor.changePage(1);
                            customPrompts.alert(parsedData.msg);
                        }
                        else {
                            paystack(parsedData.msg);
                            cartPageHandler.remorphPaystackProcessor();
                        }
                    }
                    
                }
                catch(e) {
                    console.log(e);
                    customPrompts.alert("Something Went Wrong While Deciphering Response")
                    cartProcessor.changePage(1);
                }
            }
        }
        xhr.open("POST", "./server_scripts/init_payment", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.send(cartPageHandler.parsedDeliveryDetails);
    },
    changePageState: (pageState) => {
        cartPageHandler.pageState = pageState;
        cartPageHandler.UIpageStateFillerWidth = (33 * (pageState - 1));
        cartPageHandler.UIupdateFillerWidth(cartPageHandler.activatePage, pageState);
    },
    UIupdateFillerWidth: (callBackAfterCompletion, args) => {
        cartPageHandler.subPageTransitionLock = true;
        document.getElementsByClassName("status-fill")[0].style.width = cartPageHandler.UIpageStateFillerWidth + 1 + "%";
        setTimeout(callBackAfterCompletion, (UIFILLERTIME * 1000), args);
    },
    uncheckPermAddresses: () => {
        if (document.getElementsByClassName("saved-addresses")[0]) {
            let permAddress;
            permAddress = document.getElementsByClassName("saved-addresses")[0].children
            for (let key in permAddress) {
                try {
                    permAddress[key].firstChild.checked = false;
                }
                catch (error) {

                }
            }
        }
    },
    changePageHead: (text) => {
        document.getElementById("header-completion").innerHTML = text;
    },
    transitPage: (fromPage, toPage) => {
        function transitPage(duration, opMax, opMin, pageState) {
            let pageOpacity = 0;
            pageOpacity = opMin;
            let intervalDuration = 50;
            duration = (duration * 1000) / intervalDuration;
            let opStepsVal = opMax / duration;

            let transitionInterval;
            if(!pageState) {
                pageOpacity = opMax;
                transitionInterval = setInterval(function() {
                    fromPage.style.opacity = pageOpacity;
                    pageOpacity -= opStepsVal;
                    if(pageOpacity <= opMin) {
                        clearInterval(transitionInterval);
                        fromPage.style.display = "none";
                        transitPage(PAGETRANSITIONTIME, 1, 0, true);
                    }
                }, intervalDuration);
            }
           else if(pageState) {
               pageOpacity = opMin;
               toPage.style.display = "block";
                transitionInterval = setInterval(function() { 
                    pageOpacity += opStepsVal;
                    toPage.style.opacity = pageOpacity;
                    if(pageOpacity >= opMax) {
                        clearInterval(transitionInterval);
                        cartPageHandler.subPageTransitionLock = false;
                    }
                }, intervalDuration);
            }
        }
        transitPage(PAGETRANSITIONTIME, 1, 0, false);
    },
    activatePage: (page) => {
        function getPageId(pageId) {
            switch (pageId) {
                case 1:
                    return document.getElementById("address-page");
                case 2:
                    return document.getElementById("cart-view-page");
                case 3:
                    return document.getElementById("checkout-page");
                case 4:
                    return document.getElementById("receipt-page");
                default:
                    throw new Error("Something went wrong!");
            }
        }
        if (page == 1) {
            cartPageHandler.transitPage(getPageId(2), getPageId(page));
            cartPageHandler.changePageHead("Delivery Information");
        }
        if (page == 2) {
            cartPageHandler.transitPage(getPageId(1), getPageId(page));
            cartPageHandler.changePageHead("View Receipt");
        }
        else if (page == 3) {
            cartPageHandler.transitPage(getPageId(2), getPageId(page));
            cartPageHandler.changePageHead("Checkout");
            cartPageHandler.initializeTransaction();
        }
        else if (page == 4) {
            cartPageHandler.transitPage(getPageId(3), getPageId(page));
            cartPageHandler.changePageHead("Order Completed");
        }
    }
}
var cartProcessor = {
    currentPage: 1,
    fowardPage: function () {
        if (cartPageHandler.validateDeliveryDetails() && !cartPageHandler.subPageTransitionLock) {
            cartProcessor.currentPage += 1;
            cartPageHandler.changePageState(this.currentPage);
        }
        if (cartProcessor.currentPage >= 3) {
            document.getElementById("proceed").setAttribute("disabled", true);
            if (cartProcessor.currentPage == 4) {
                document.getElementById("back").setAttribute("disabled", true);
                document.getElementById("proceed").setAttribute("disabled", true);
            }
        }
    },
    changePage: function (page) {
        if (cartProcessor.currentPage == 3) {
            cartPageHandler.terminatePaystack();
            document.getElementById("proceed").removeAttribute("disabled");
        }
        if (page >= 1 && !cartPageHandler.subPageTransitionLock) {
            cartProcessor.currentPage = page;
            cartPageHandler.changePageState(page);
        }
    },
    reversePage: function () {
        if (cartProcessor.currentPage == 3) {
            cartPageHandler.terminatePaystack();
            document.getElementById("proceed").removeAttribute("disabled");
        }
        if (cartProcessor.currentPage > 1 && !cartPageHandler.subPageTransitionLock) {
            cartProcessor.currentPage -= 1;
            cartPageHandler.changePageState(cartProcessor.currentPage);
        }
    }
}