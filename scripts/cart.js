const NUMBEROFPAGES = 4, UIFILLERTIME = 1.5, PAGETRANSITIONTIME = 0.25;
var cartPageHandler = {
    //I don't comment a lot, but since JS doesn't have an explicit inbuilt functionality to mark Object values as private, i feel inclined to tell you not to f**king change this variable but you can read it :-)
    pageState: 1,
    UIpageStateFillerWidth: 0,
    pageTransitionActive: 0,
    changePageState: (pageState) => {
        cartPageHandler.pageState = pageState;
        cartPageHandler.UIpageStateFillerWidth = (100 / (NUMBEROFPAGES - 1)) * (pageState - 1);
        cartPageHandler.UIupdateFillerWidth(cartPageHandler.activatePage, 2);
    },
    UIupdateFillerWidth: (callBackAfterCompletion, args) => {
        document.getElementsByClassName("status-fill")[0].style.width = cartPageHandler.UIpageStateFillerWidth + 1 + "%";
        setTimeout(callBackAfterCompletion, (UIFILLERTIME * 1000), args);
    },
    uncheckPermAddresses: () => {
        let permAddress;
        if (permAddress = document.getElementsByClassName("saved-addresses")[0].children) {
            for (let key in permAddress) {
                try {
                    permAddress[key].firstChild.checked = false;
                }
                catch (error) {

                }
            }
        }
    },
    transitPage: (fromPage, toPage) => {
        let pageOpacity = 0;
        function transitPage(duration, opMax, opMin, pageState) {
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
                    toPage.style.opacity = pageOpacity;
                    pageOpacity += opStepsVal;
                    if(pageOpacity >= opMax) {
                        clearInterval(transitionInterval);
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
                    return document.getElementById("receipt-page");
                case 3:
                    return document.getElementById("checkout-page");
                case 4:
                    return document.getElementById("congratulation-page");
                default:
                    throw new Error("Something went wrong!");
            }
        }
        if (page == 2) {
            cartPageHandler.transitPage(getPageId(1), getPageId(page));
        }
    }
}
var cartProcessor = {
    currentPage: 1,
    changePage: function () {
        cartPageHandler.changePageState(2);
    }
}