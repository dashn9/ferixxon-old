// JavaScript Document
const maxCartLength = 6;
const maxBatchLength = 8;
const imageAnimationStepWaitSeconds = 4000;
const productOrderMax = 20;
const productOrderMin = 1;

//This cart is a multi-dimensional array of what i call order-batches in each batch will further contain products orders in json format
var cart = [
    ["Batch 1", []]
];
class AjaxCommunicator {
    constructor(url, callback, message = null, method = "post", contentType = "application/x-www-form-urlencoded") {
        this.xhr = this.createXhr();
        this.url = url;
        this.callBack = callback;
        this.message = message;
        this.method = method;
        this.contentType = contentType;
    }
    resetAjax() {
        this.xhr = this.createXhr();
    }
    initiateCommunication() {
        var _this = this;
        customPrompts.enableLoadingModal();
        function xhrReady() {
            customPrompts.disableLoadingModal();
            if (this.readyState == 4 && this.status == 200) {
                if (_this.callBack) {
                    _this.callBack(this.responseText);
                }
                else {
                    customPrompts.alert("Server communication has been successful");
                }
            }
        }
        if (this.method == "post") {
            this.xhr.open("POST", this.url, true);
            this.xhr.setRequestHeader("Content-type", this.contentType);
            this.xhr.onreadystatechange = xhrReady;
            this.xhr.send(this.message);
        }
        else if (this.method == "get") {
            this.xhr.open("GET", this.url + "?" + this.message, true);
            this.xhr.setRequestHeader("Content-type", this.contentType);
            this.xhr.onreadystatechange = xhrReady;
            this.xhr.send(null);
        }
        else {
            customPrompts.alert("Unacceptable method of server communication");
        }
    }
    createXhr() {

        try {

            return new XMLHttpRequest();

        } catch (exception) {

            try {

                return new ActiveXObject("Microsoft.XMLHTTP");

            } catch (exception) {

                return new ActiveXObject("Msxml2.XMLHTTP");

            }


        }
    };;
}
var animateImageSlide = {

    imageSlide: document.getElementsByClassName("images-hold-slide")[0],

    slideStates: document.getElementsByClassName("image-slide-state"),

    noOfImagesInImageSlide: 4,

    imageAnimationStep: 1,

    animateImageSlide() {

        let marginLeft = 0,

            imageSlideAnimationInterval;

        function setAnimationSlideTimeout(whatToDo) {

            if (whatToDo == "reset") {

                marginLeft = 0;

                animateImageSlide.imageAnimationStep = 1;

                setTimeout((function () {

                    animateImageSlide.imageSlide.style.marginLeft = 0;

                    animateImageSlide.slideStates[animateImageSlide.noOfImagesInImageSlide - 1].style.background = "none";

                    animateImageSlide.slideStates[animateImageSlide.noOfImagesInImageSlide - 1].style.opacity = 0.8;

                    animateImageSlide.slideStates[animateImageSlide.imageAnimationStep - 1].style.background = "#FFF";

                    animateImageSlide.slideStates[animateImageSlide.imageAnimationStep - 1].style.opacity = 1;

                    setTimeout((function () {

                        imageSlideAnimationInterval = setInterval(incrementMarginLeft, 10, 4);

                    }), imageAnimationStepWaitSeconds)

                }), imageAnimationStepWaitSeconds);

            } else {

                setTimeout((function () {

                    imageSlideAnimationInterval = setInterval(incrementMarginLeft, 10, 4);

                }), imageAnimationStepWaitSeconds);

            }

        }

        function setSlideStates(imageAnimationStep) {

            animateImageSlide.slideStates[imageAnimationStep - 2].style.background = "none";

            animateImageSlide.slideStates[imageAnimationStep - 2].style.opacity = 0.8;

            animateImageSlide.slideStates[imageAnimationStep - 1].style.background = "#FFF";

            animateImageSlide.slideStates[imageAnimationStep - 1].style.opacity = 1;

            if (imageAnimationStep >= animateImageSlide.noOfImagesInImageSlide) {

                animateImageSlide.slideStates[animateImageSlide.noOfImagesInImageSlide - 1].style.background = "#FFF";

                animateImageSlide.slideStates[animateImageSlide.noOfImagesInImageSlide - 1].style.opacity = 1;

            }

        }

        const incrementMarginLeft = toIncrement => {

            if ((-1 * marginLeft) / this.imageAnimationStep >= 100) {

                this.imageAnimationStep += 1;

                clearInterval(imageSlideAnimationInterval);

                setSlideStates(this.imageAnimationStep);

                if (this.imageAnimationStep < this.noOfImagesInImageSlide) {

                    setAnimationSlideTimeout();

                } else {

                    setAnimationSlideTimeout("reset");

                }

            } else {

                marginLeft -= toIncrement;

                this.imageSlide.style.marginLeft = marginLeft + "%";

            }


        }


        setTimeout((function () {

            imageSlideAnimationInterval = setInterval(incrementMarginLeft, 10, 4);

        }), imageAnimationStepWaitSeconds);
    }

};

var customPrompts = {
    messager: document.getElementsByClassName("message-overlay")[0],
    interactionBlocker: document.getElementById("interaction-blocker"),
    centerLoadingModal: document.getElementById("loading-wrapper"),
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
    enableLoadingModal: () => {
        customPrompts.centerLoadingModal.style.display = "initial";
    },
    disableLoadingModal: () => {
        customPrompts.centerLoadingModal.style.display = "none";
    }
};

var orderProcessor = {

    orderChildElement: document.getElementById("orders"),

    messager: document.getElementById("message-overlay"),

    totalValue: document.getElementById("total-value"),

    requestedKitchenMenuJSON: null,

    createOrderChildElement: (title, price, batchKey, productNid) => {

        let orderChildElement = document.createElement("div");

        orderChildElement.setAttribute("class", "order-elements");

        orderChildElement.setAttribute("data-batch-key", batchKey);

        orderChildElement.setAttribute("data-product-nid", productNid);



        let cartSub = cart[batchKey][1];



        let orderChildElementSub = document.createElement("span");

        orderChildElementSub.innerHTML = title + " ( <span>" + price + "</span> )";

        orderChildElement.appendChild(orderChildElementSub);



        orderChildElementSub = document.createElement("input");

        orderChildElementSub.setAttribute("type", "number");

        //orderChildElementSub.setAttribute("value", productOrderMin);

        orderChildElementSub.setAttribute("min", productOrderMin);

        orderChildElementSub.setAttribute("max", productOrderMax);





        orderChildElementSub.onchange = function (event) {

            let batchKey = event.target.parentElement.getAttribute("data-batch-key"),

                productNid = event.target.parentElement.getAttribute("data-product-nid");

            if (event.target.value < productOrderMin) {

                event.target.value = productOrderMin;

            } else if (event.target.value > productOrderMax) {

                event.target.value = productOrderMax;

            }

            for (let key in cartSub) {

                if (cartSub[key].productNid == productNid) {

                    cartSub[key].quantity = event.target.value;

                    orderProcessor.saveCart();

                    break;

                }

            }

            orderProcessor.calculateTotal();

            event.target.previousElementSibling.firstElementChild.innerHTML = event.target.value * price;

        }

        orderChildElement.appendChild(orderChildElementSub);

        for (let key in cartSub) {

            if (cartSub[key].productNid == productNid) {

                orderChildElementSub.value = cartSub[key].quantity;

                orderChildElementSub.previousElementSibling.firstElementChild.innerHTML = orderChildElementSub.value * price;

                break;

            }

        }

        return orderChildElement;
    },
    pasteTextOnlyForEditableContent: (event) => {
        event.preventDefault();
        let clipboardText = event.clipboardData ? (event.originalEvent || event).clipboardData.getData("text/plain")
            : window.clipboardData ? window.clipboardData.getData("Text") : "";

        if (document.queryCommandSupported("insertText")) {
            document.execCommand('insertText', false, clipboardText);
        }
        else {
            let range = document.getSelection().getRangeAt(0);
            range.deleteContents();

            let textNode = document.createTextNode(clipboardText);
            range.insertNode(textNode);
            range.selectNodeContents(textNode);
            range.collapse(false);

            selection = window.getSelection();
            selection.removeAllRanges();
            selection.addRange(range);
        }
    },
    createBatchElement: (batchId, batchName) => {

        let batchElement = document.createElement("div");

        batchElement.setAttribute("class", "batch");

        batchElement.setAttribute("id", "batch-" + batchId);





        let batchElementChild = document.createElement("h4");

        batchElementChild.setAttribute("contenteditable", true);

        batchElementChild.setAttribute("data-batch-key", batchId);

        batchElementChild.setAttribute("title", "Please don't paste into this box");

        batchElementChild.addEventListener("keyup", orderProcessor.editAndUpdateBatchName);
        batchElementChild.addEventListener("cut", orderProcessor.editAndUpdateBatchName);
        batchElementChild.addEventListener("paste", orderProcessor.pasteTextOnlyForEditableContent);
        batchElementChild.addEventListener("paste", orderProcessor.editAndUpdateBatchName);

        batchElementChild.appendChild(document.createTextNode(batchName));

        batchElement.appendChild(batchElementChild);



        batchElementChild = document.createElement("div");

        batchElement.appendChild(batchElementChild);



        return batchElement;
    },


    hideMessager: () => {

        orderProcessor.messager.style.display = "none";

    },

    saveCart: () => {

        localStorage.localCart = JSON.stringify(cart);

    },

    loadCart: () => {

        if (localStorage.localCart) {

            cart = JSON.parse(localStorage.localCart);

            orderProcessor.displayOrder();

        }

        else {

            cart = [

                ["Batch 1", []]

            ];

        }


    },

    displayOrderLock: false,

    displayOrder: async () => {

        if (!orderProcessor.displayOrderLock) {

            orderProcessor.displayOrderLock = true;

            orderProcessor.orderChildElement.innerHTML = "";

            for (let key in cart) {

                let cartSub = cart[key][1];

                let batchElement = orderProcessor.createBatchElement(key, cart[key][0]);

                for (let key0 in cartSub) {

                    batchElement.children[1].appendChild(await orderProcessor.addToOrder(cartSub[key0].productNid, true, key));

                }

                orderProcessor.orderChildElement.appendChild(batchElement);

            }

            orderProcessor.calculateTotal();

            orderProcessor.displayOrderLock = false;

        }

    },

    createBatch: () => {

        if (cart.length <= 5) {

            cart.push(["Batch " + eval(cart.length + 1), []]);

        }
        else {
            customPrompts.alert("You can't create more than 6 batches");
        }
        orderProcessor.saveCart();

        orderProcessor.displayOrder();
    },
    editAndUpdateBatchName: (event) => {
        let targetEl = event.target, targetBatchKey = targetEl.getAttribute("data-batch-key");
        if (targetBatchKey >= 0 && targetBatchKey <= maxCartLength - 1) {
            if (targetEl.innerHTML.match(/<.*>/gi) || targetEl.innerHTML.match(/&nbsp;/) || targetEl.innerHTML.match(/\u00a0/g)) {
                targetEl.innerHTML = targetEl.innerHTML.replace(/<.*>/g, "");
                targetEl.innerHTML = targetEl.innerHTML.replace(/&nbsp;/g, " ");
                targetEl.innerHTML = targetEl.innerHTML.replace(/\u00a0/g, " ");
            }
            if (!targetEl.innerHTML.match(/[^A-Za-z0-9 ()_-]/)) {
                if (!(targetEl.innerHTML.length >= 1 && targetEl.innerHTML.length <= 20)) {
                    customPrompts.alert("Your Batch Title can't be less than 1(one) character and more than 20(twelve) characters");
                    targetEl.innerHTML = targetEl.innerHTML.substr(0, 12);
                }
            }
            else {
                customPrompts.alert("Sorry, but you can't use this character(s). Only [A-Za-z0-9 ()_- ] are acceptable");

                targetEl.innerHTML = targetEl.innerHTML.replace(/[^A-Za-z0-9 (-)_]/gm, "");
            }
            if (targetEl.innerHTML.match(/[ ]{2,}/)) {
                targetEl.innerHTML = targetEl.innerHTML.replace(/[ ]{2,}/gi, " ");
            }

            cart[targetBatchKey][0] = targetEl.innerHTML;
            orderProcessor.saveCart();
        }
        else {
            customPrompts.alert("Invalid batch key");
        }
    },
    addToBatch: (batchKey, productNid) => {

        let cartSub = cart[batchKey][1];

        if (cartSub.length <= 0) {

            cart[batchKey][1].push({

                "productNid": productNid,

                "quantity": 1

            });

        }
        else {

            for (let key in cartSub) {

                if (cartSub[key].productNid == productNid) {

                    if (cartSub[key].quantity < productOrderMin) {

                        cartSub[key].quantity = productOrderMin;

                    } else if (cartSub[key].quantity > productOrderMax) {

                        cartSub[key].quantity = productOrderMax;

                    } else {

                        cartSub[key].quantity = parseInt(cartSub[key].quantity) + 1;

                    }

                    break;


                } else if (key == cartSub.length - 1) {
                    if (cartSub.length >= maxBatchLength) {
                        customPrompts.alert("This batch already has 8(eight) orders; Max Batch orders are 8(eight)");
                    }
                    else {
                        cart[batchKey][1].push({

                            "productNid": productNid,

                            "quantity": 1

                        });
                    }
                }

            }

        }
        orderProcessor.hideMessager();

        orderProcessor.saveCart();

        orderProcessor.displayOrder();
    },


    chooseBatch: (productNid) => {

        orderProcessor.messager.style.display = "block";

        let messengerChild = orderProcessor.messager.firstElementChild.children[1];

        messengerChild.innerHTML = "";

        let elementChild = document.createElement("h3");

        elementChild.setAttribute("class", "message-topic");

        elementChild.appendChild(document.createTextNode("Which batch would you like to add this order to?"));

        messengerChild.appendChild(elementChild);



        for (let key in cart) {

            elementChild = document.createElement("Button");

            elementChild.appendChild(document.createTextNode(cart[key][0]));

            elementChild.setAttribute("class", "select-batch");

            elementChild.onclick = function () {

                orderProcessor.addToBatch(key, productNid);

            }


            messengerChild.appendChild(elementChild);

        }


    },

    deleteBatch: () => {

        orderProcessor.messager.style.display = "block";

        let messengerChild = orderProcessor.messager.firstElementChild.children[1];

        messengerChild.innerHTML = "";

        let elementChild = document.createElement("h3");

        elementChild.setAttribute("class", "message-topic");

        elementChild.appendChild(document.createTextNode("Which batch do you intend on deleting?"));

        messengerChild.appendChild(elementChild);



        for (let key in cart) {

            elementChild = document.createElement("Button");

            elementChild.appendChild(document.createTextNode(cart[key][0]));

            elementChild.setAttribute("class", "select-batch");

            elementChild.onclick = function () {

                orderProcessor.hideMessager();

                customPrompts.confirm("Are you sure you want to delete the BATCH: " + cart[key][0], function () { cart.splice(key, 1); orderProcessor.saveCart(); orderProcessor.displayOrder(); });

            }


            messengerChild.appendChild(elementChild);

        }



    },
    addToOrder: async (productNid, toReturn, batchKey) => {

        if (orderProcessor.requestedKitchenMenuJSON) {

            //You can optimize the code in the future by directly parsing after retrieiving rather than sending to other objects to parse for themselves

            let requestedKitchenMenuJSON = JSON.parse(orderProcessor.requestedKitchenMenuJSON);

            for (let kEl in requestedKitchenMenuJSON) {

                if (requestedKitchenMenuJSON[kEl].nid == productNid) {

                    if (toReturn) {

                        return orderProcessor.createOrderChildElement(requestedKitchenMenuJSON[kEl].title, requestedKitchenMenuJSON[kEl].ppu, batchKey, productNid);

                    } else {

                        orderProcessor.orderChildElement.appendChild(orderProcessor.createOrderChildElement(requestedKitchenMenuJSON[kEl].title, requestedKitchenMenuJSON[kEl].ppu, batchKey, productNid));

                    }

                    break;

                }


            }

        } else {

            let requestedProductDetails = JSON.parse(await KitchenSelectNProcessor.getProduct(productNid).then(() => {

                return productDetails;

            }))[0];

            if (toReturn) {

                return orderProcessor.createOrderChildElement(requestedProductDetails.title, requestedProductDetails.ppu, batchKey, productNid);

            } else {

                orderProcessor.orderChildElement.appendChild(orderProcessor.createOrderChildElement(requestedProductDetails.title, requestedProductDetails.ppu, batchKey, productNid));

            }


        }

    },


    resetCart: () => {

        function resetCart() {

            cart = [

                ["Batch 1", []]

            ];

            orderProcessor.displayOrder();

        }

        customPrompts.confirm("Are You Sure?", resetCart);

    },
    fetchProductPrice: async (productNid) => {

        if (orderProcessor.requestedKitchenMenuJSON) {

            //You can optimize the code in the future by directly parsing after retrieiving rather than sending to other objects to parse for themselves

            let requestedKitchenMenuJSON = JSON.parse(orderProcessor.requestedKitchenMenuJSON);

            for (let kEl in requestedKitchenMenuJSON) {

                if (requestedKitchenMenuJSON[kEl].nid == productNid) {

                    return requestedKitchenMenuJSON[kEl].ppu;

                }


            }

        } else {

            let requestedProductDetails = JSON.parse(await KitchenSelectNProcessor.getProduct(productNid).then(() => {

                return productDetails;

            }))[0];

            return requestedProductDetails.ppu;

        }


    },


    calculateTotalLock: false,
    calculateTotal: async () => {

        // if (!orderProcessor.calculateTotalLock) {

        orderProcessor.displayOrderLock = true;

        let total = 0;

        for (let key in cart) {

            let cartSub = cart[key][1];



            for (let key in cartSub) {

                total = total + (await orderProcessor.fetchProductPrice(cartSub[key].productNid) * cartSub[key].quantity);

            }


        }


        orderProcessor.totalValue.innerHTML = "&#x20a6 " + total;

        orderProcessor.displayOrderLock = false;

        // }


    },


    submitCart: () => {

        let cartForm = document.createElement("form");

        cartForm.action = "cart.php";

        cartForm.method = "POST";



        let cartFormInput = document.createElement("input");

        cartFormInput.name = "cart";

        cartFormInput.value = JSON.stringify(cart);



        cartForm.appendChild(cartFormInput);



        document.body.appendChild(cartForm);





        cartForm.submit();

        document.body.removeChild(cartForm);


    },
    retrieveCartsFromAccount: () => {

    },
    saveCartToAccount: () => {
        function saveCart(cartTitle) {
            cartTitle = cartTitle.trim();
            function cartSaverResponseProcessor(response) {
                let in_response;
                if (in_response = JSON.parse(response)) {
                    if (in_response.msg) {
                        customPrompts.alert(in_response.msg);
                    }
                }
            }
            if ((cartTitle.length > 0 && cartTitle.length <= 20) && !cartTitle.match(/[^A-Za-z0-9 ()_-]/) && !cartTitle.match(/[ ]{2,}/)) {
                let ajx = new AjaxCommunicator("/server_scripts/cart_perm_saver.php", cartSaverResponseProcessor, "cart_title=" + cartTitle + "&cart=" + JSON.stringify(cart), "post");
                ajx.initiateCommunication();
            }
            else {
                customPrompts.alert("The Cart title length can't be less than 0, more than 20 and can't contain characters other than [A-Za-z ()_-0-9] with no more than a space in succession");
            }
        }
        customPrompts.prompt("Enter Cart Title", "Cart Title", saveCart);
    },
    toggleCartDisplay: () => {
        let cart = document.getElementById("orders-bar");
        if (this.isCartDisplayed === undefined) {
            var interval = setInterval(function () {
                if (!window.matchMedia("(max-width: 500px").matches) {
                    cart.style.left = "initial";
                    this.isCartDisplayed = undefined;
                    clearInterval(interval); 
                }

            }, 2000);
            
        }
        if (!this.isCartDisplayed) {
            cart.style.left = "5%";
            this.isCartDisplayed = true;
        }
        else {
            cart.style.left = "105%";
            this.isCartDisplayed = false;
        }
        

    }

};


var KitchenSelectNProcessor = {

    UnOrderedListSelector: document.getElementsByClassName("kitchen-body-select")[0],

    loadingCircle: document.getElementsByClassName("loading-circle")[1].style,

    kitchensMenuBodyElement: document.getElementsByClassName("kitchens-body"),

    kitchensMenuHeadElement: document.getElementsByClassName("kitchens-head")[0].getElementsByTagName("h2")[0],



    KitchenMenu: {

        retriever: new XMLHttpRequest(),

        retrieveUrl: "/server_scripts/kitchen_menu.php?kitchen=",

        productRetrieveUrl: "/server_scripts/get_product.php?product_nid=",

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


        processKitchenMenu(kitchenDataJSON, kitchenId) {
            if (kitchenDataJSON) {

                KitchenSelectNProcessor.kitchensMenuBodyElement[0].innerHTML = "";

                if (kitchenId == "kitchen-0") {

                    KitchenSelectNProcessor.kitchensMenuHeadElement.innerHTML = "Chicken Republic";

                } else if (kitchenId == "kitchen-1") {

                    KitchenSelectNProcessor.kitchensMenuHeadElement.innerHTML = "Kum Chop";

                } else if (kitchenId == "kitchen-2") {

                    KitchenSelectNProcessor.kitchensMenuHeadElement.innerHTML = "Simple";

                }

                let kitchenData = JSON.parse(kitchenDataJSON);

                if (kitchenData.length > 0) {

                    for (var kEl in kitchenData) {

                        if (kitchenData.hasOwnProperty(kEl)) {

                            let divElement = document.createElement("div");

                            divElement.setAttribute("class", "product-bar");



                            let divChildElement = document.createElement("h3");

                            divChildElement.appendChild(document.createTextNode(kitchenData[kEl].title));

                            divElement.appendChild(divChildElement);



                            divChildElement = document.createElement("p");

                            divChildElement.innerHTML = kitchenData[kEl].description;

                            divElement.appendChild(divChildElement);



                            divChildElement = document.createElement("p");

                            divChildElement.appendChild(document.createTextNode("Price: N" + kitchenData[kEl].ppu))

                            divElement.appendChild(divChildElement);



                            let divChildElement2 = document.createElement("button");

                            divChildElement2.setAttribute("data-product-id", kitchenData[kEl].nid)

                            divChildElement2.setAttribute("class", "add-to-order-button");

                            divChildElement2.appendChild(document.createTextNode("To Orders"));

                            divChildElement2.onclick = function (event) {

                                orderProcessor.chooseBatch(event.target.getAttribute("data-product-id"));

                            }



                            divChildElement = document.createElement("div");

                            divChildElement.setAttribute("class", "buttons-bar");

                            divChildElement.appendChild(divChildElement2);



                            divElement.appendChild(divChildElement);



                            KitchenSelectNProcessor.kitchensMenuBodyElement[0].appendChild(divElement);
                        }
                    }

                }
                else {
                    let pElement = document.createElement("p");
                    pElement.appendChild(document.createTextNode("This Kitchen does not have any products to offer as of this moment."));

                    KitchenSelectNProcessor.kitchensMenuBodyElement[0].appendChild(pElement);
                }


            }


            KitchenSelectNProcessor.loadingCircle.display = "none";


        },


        KitchenMenu: (kitchen) => {

            let target = KitchenSelectNProcessor.KitchenMenu;

            target.retriever = target.createXhr();

            KitchenSelectNProcessor.loadingCircle.display = "inline-block";

            target.retriever.onreadystatechange = () => {

                if (target.retriever.readyState == 4 && target.retriever.status == 200) {

                    orderProcessor.requestedKitchenMenuJSON = target.retriever.responseText;

                    target.processKitchenMenu(target.retriever.responseText, kitchen);

                }

            }

            target.retriever.open("GET", target.retrieveUrl + kitchen, true);

            target.retriever.send(null);

        }
    },
    uploadCartForSave: (cartStringified) => {

        let target = KitchenSelectNProcessor.KitchenMenu;

        target.retriever = target.createXhr();

        target.retriever.onreadystatechange = () => {

            if (target.retriever.readyState == 4 && target.retriever.status == 200) {

                orderProcessor.requestedKitchenMenuJSON = target.retriever.responseText;

                target.processKitchenMenu(target.retriever.responseText, kitchen);

            }

        }

        target.retriever.open("POST", "/server_scripts/save_cart_order.php", true);

        target.retriever.send(cartStringified);

    },
    getProduct: (productNid) => {

        if (true) {



            return new Promise((resolve, reject) => {

                let target = KitchenSelectNProcessor.KitchenMenu;

                target.retriever = target.createXhr();

                target.retriever.overrideMimeType("application/json");



                function checkStateChange() {

                    if (target.retriever.readyState == 4 && target.retriever.status == 200) {

                        resolve(productDetails = target.retriever.responseText);

                    } else if (target.retriever.readyState != 4 && target.retriever.status != 200) {

                        setTimeout(checkStateChange, 50);

                    } else {

                        reject({

                            "readyState": target.retriever.readyState,

                            "Status": target.retriever.status

                        });

                    }

                }

                target.onreadystatechange = checkStateChange();





                target.retriever.open("GET", target.productRetrieveUrl + productNid, true);

                target.retriever.send(null);
            });





        } else {
            alert("Stop It");

        }
    },


    main: function () {

        this.UnOrderedListSelector.onclick = this.dropdownList;

        window.onclick = () => {

            KitchenSelectNProcessor.UnOrderedListSelector.children[2].classList.remove("kitchen-body-select-list-height-transitor");

            KitchenSelectNProcessor.UnOrderedListSelector.children[1].classList.remove("li-arrow-up");

        }

        Array.from(KitchenSelectNProcessor.UnOrderedListSelector.children[2].children).forEach((element) => {

            if (element.nodeName == "LI" && element.hasAttribute("data-kitchen")) {

                element.onclick = function () {

                    KitchenSelectNProcessor.KitchenMenu.KitchenMenu(element.getAttribute("data-kitchen"));

                    KitchenSelectNProcessor.selectKitchen(event);

                }

            }

        })

    },

    dropdownList: function () {

        KitchenSelectNProcessor.UnOrderedListSelector.children[2].classList.toggle("kitchen-body-select-list-height-transitor");

        KitchenSelectNProcessor.UnOrderedListSelector.children[1].classList.toggle("li-arrow-up");

        event.stopPropagation();


    },


    selectKitchen: function (event) {

        KitchenSelectNProcessor.UnOrderedListSelector.children[0].innerHTML = event.target.innerHTML;

    }
};


var autoPositionOrder = {

    orderElement: document.getElementById("orders-bar"),

    orderElementMarginValue: 2,

    orderElementAnimation: null,

    animateOrderElementMargin: (YOffset, marginIncrement, interval) => {

        clearInterval(autoPositionOrder.orderElementAnimation);



        function animateMargin(toMoveUpOrDown) {

            if (!toMoveUpOrDown) {

                if (autoPositionOrder.orderElementMarginValue >= YOffset) {

                    autoPositionOrder.orderElementMarginValue -= marginIncrement;

                    autoPositionOrder.orderElement.style.top = autoPositionOrder.orderElementMarginValue + 8 + "px";
                    

                } else {

                    clearInterval(autoPositionOrder.orderElementAnimation);

                }

            } else if (toMoveUpOrDown) {

                if (autoPositionOrder.orderElementMarginValue <= YOffset) {

                    autoPositionOrder.orderElementMarginValue += marginIncrement;

                    autoPositionOrder.orderElement.style.top = autoPositionOrder.orderElementMarginValue + 8 + "px";

                } else {

                    clearInterval(autoPositionOrder.orderElementAnimation);

                }

            }

            autoPositionOrder.orderElement.innerHTML = autoPositionOrder.orderElementMarginValue + " " + YOffset;

        }

        if (autoPositionOrder.orderElementMarginValue != YOffset) {



            if (YOffset < autoPositionOrder.orderElementMarginValue) {

                autoPositionOrder.orderElementAnimation = setInterval(animateMargin, interval, false);

            } else if (YOffset > autoPositionOrder.orderElementMarginValue) {

                autoPositionOrder.orderElementAnimation = setInterval(animateMargin, interval, true);

            }
        }

    },


    animateOrderElementMargin2: (pageYOffset, withAnimationDuration) => {

        if (withAnimationDuration) {

            autoPositionOrder.orderElement.style.transition = "top";

            autoPositionOrder.orderElement.style.transitionDuration = withAnimationDuration + "s";

        }
        else {
            autoPositionOrder.orderElement.style.transition = "none";

            autoPositionOrder.orderElement.style.transitionDuration = 0;
        }

        let orderParentHeight = autoPositionOrder.orderElement.parentElement.clientHeight;

        if (pageYOffset < (orderParentHeight - autoPositionOrder.orderElement.clientHeight)) {

            autoPositionOrder.orderElement.style.top = pageYOffset * 100 / (orderParentHeight) + 1 + "%";

        }
        else {
            autoPositionOrder.orderElement.style.top = 100 - (autoPositionOrder.orderElement.clientHeight * 100 / (orderParentHeight)) + "%";
        }

    },

    initiate: () => {

        window.onscroll = () => {
            if (!window.matchMedia("(max-width: 500px").matches) {
                autoPositionOrder.animateOrderElementMargin2(this.scrollY, 1.5);
            }
        }

    }

};


(function main() {
    
    KitchenSelectNProcessor.main();

    autoPositionOrder.initiate();

    animateImageSlide.animateImageSlide();

    orderProcessor.loadCart();

}());