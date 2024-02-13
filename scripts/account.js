// JavaScript Document
//If any changes were made to the html element, make sure to update it here also
const CANVASPARENTWIDTHASPECT = 2;
const CANVASPARENTWIDTH = 800;

const CANVASPARENTHEIGHTASPECT = 1;
const CANVASPARENTHEIGHT = 400;
var accountDetails = {

};
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
    };
}
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
    prompt: (statement, inputPlaceHolder, callBack) => {
        let messager = customPrompts.messager;
        customPrompts.interactionBlocker.style.display = "block";
        messager.children[2].style.display = "block";
        messager.children[3].style.display = "none";
        messager.children[1].innerHTML = statement;
        messager.classList.add("message-overlay-active");


        messager.children[2].firstElementChild.placeholder = inputPlaceHolder;
        messager.children[2].lastElementChild.onclick = function () {

            if (messager.children[2].firstElementChild.value.length >= messager.children[2].firstElementChild.getAttribute("minlength") && messager.children[2].firstElementChild.value.length <= messager.children[2].firstElementChild.getAttribute("maxlength")) {
                callBack(messager.children[2].firstElementChild.value);
                customPrompts.interactionBlocker.style.display = "none";
                customPrompts.messager.classList.remove("message-overlay-active");
                messager.children[2].firstElementChild.value = "";
            }
            else {
                customPrompts.alert("The length of your input is not enough or too much" + messager.children[2].firstElementChild);
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
        document.getElementsByClassName("horizontal-loader")[0].style.display = "block";
    },
    disableLoadingModal: () => {
        document.getElementsByClassName("horizontal-loader")[0].style.display = "none";
    }
};
var accountInformationProcessor = {
    editTrigger: document.getElementsByClassName("edit-icon"),
    addresses: [],
    deliveryInformationBar: document.getElementsByClassName("delivery-information")[0].getElementsByClassName("account-info-body")[0],
    bsiUsername: document.getElementById("bsi-username-value"),
    bsiTel: document.getElementById("bsi-tel-value"),
    bsiEmail: document.getElementById("bsi-email-value"),
    diAddress: document.getElementsByClassName("di-address-value"),
    diCatchphrase: document.getElementById("di-catchphrase-value"),
    hasBeenTriggeredForBsiEdit: false,
    hasBeenTriggeredForDiEdit: false,
    hasBeenEdited: false,
    ajax: null,
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
    processResponse: (jsonData) => {
        try {
            let parsedData = JSON.parse(jsonData);
            let parsedDataErr = parsedData.error_type;
            if (parsedDataErr == "TO_SOON") {
                customPrompts.alert(`You have to wait ${parsedData.time_to_wait} before you can update your profile`);
            }
            else if (parsedDataErr == "NO_CHGS") {
                customPrompts.alert(`${parsedData.msg}`);
            }
            else if (parsedDataErr == "NO_LOG") {
                customPrompts.alert(`${parsedData.msg}`);
            }
            else if (parsedDataErr == "UNAME_INVALID") {
                customPrompts.alert(`${parsedData.msg}`);
            }
            else if (parsedDataErr == "MAIL_INVALID") {
                customPrompts.alert(`${parsedData.msg}`);
            }
            else if (parsedDataErr == "TEL_INVALID") {
                customPrompts.alert(`${parsedData.msg}`);
            }
            else if (parsedDataErr == "ADDR_INVALID") {
                customPrompts.alert(`${parsedData.msg}`);
            }
            else if (parsedDataErr == "UNKNOWN") {
                customPrompts.alert(`${parsedData.msg}`);
            }
            else if (parsedDataErr == "NO_ERR") {
                customPrompts.alert(`${parsedData.msg}`);
            }

        }
        catch (error) {
            alert(jsonData);
        }
    },
    sendData: () => {
        accountInformationProcessor.ajax = accountInformationProcessor.createXhr();
        accountInformationProcessor.ajax.open("POST", "/server_scripts/account_details_updater.php", true);
        customPrompts.enableLoadingModal();
        customPrompts.interactionBlocker.style.display = "block";
        accountInformationProcessor.ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        accountInformationProcessor.ajax.onreadystatechange = function () {
            if (this.readyState == 4 && this.status == 200) {
                accountInformationProcessor.processResponse(this.responseText);
                customPrompts.disableLoadingModal();
                customPrompts.interactionBlocker.style.display = "none";
            }
        };
        accountInformationProcessor.remediateAddresses();
        let params = `username=${accountInformationProcessor.bsiUsername.innerHTML}&email=${accountInformationProcessor.bsiEmail.innerHTML}&tel=${accountInformationProcessor.bsiTel.innerHTML}&address=${JSON.stringify(accountInformationProcessor.addresses)}&catch_phrase=${accountInformationProcessor.diCatchphrase.innerHTML}`
        accountInformationProcessor.ajax.send(params);
    },
    activateBsiEditable: (event, username, tel, email) => {
        function editEventOpacity(val) {
            if (event.target) {
                event.target.style.opacity = val;
            }
        }
        function activateEdit() {

            accountInformationProcessor.hasBeenTriggeredForBsiEdit = true;
            editEventOpacity(1.0);
            if (username) {
                accountInformationProcessor.bsiUsername.contentEditable = true;
                accountInformationProcessor.hasBeenEdited = true;
            }
            if (tel) {
                accountInformationProcessor.bsiTel.contentEditable = true;
                accountInformationProcessor.hasBeenEdited = true;
            }
            if (email) {
                accountInformationProcessor.bsiEmail.contentEditable = true;
                accountInformationProcessor.hasBeenEdited = true;
            }
        }
        if (!accountInformationProcessor.hasBeenTriggeredForBsiEdit) {
            customPrompts.confirm("Are you sure you want to edit your information?", activateEdit, null);
        }
        else {
            editEventOpacity(0.65);
            accountInformationProcessor.hasBeenTriggeredForBsiEdit = false;
            accountInformationProcessor.bsiUsername.contentEditable = false;
            accountInformationProcessor.bsiTel.contentEditable = false;
            accountInformationProcessor.bsiEmail.contentEditable = false;
        }
    },
    activateDiEditable: (event, address, catchphrase) => {
        function editEventOpacity(val) {
            if (event.target) {
                event.target.style.opacity = val;
            }
        }
        function activateEdit() {

            editEventOpacity(1.0);
            accountInformationProcessor.hasBeenTriggeredForDiEdit = true;

            if (address) {
                for (i = 0; i < accountInformationProcessor.diAddress.length; i++) {
                    accountInformationProcessor.diAddress[i].contentEditable = true;
                }
                accountInformationProcessor.hasBeenEdited = true;
            }
            if (catchphrase) {
                accountInformationProcessor.diCatchphrase.contentEditable = true;
                accountInformationProcessor.hasBeenEdited = true;
            }
        }
        if (!accountInformationProcessor.hasBeenTriggeredForDiEdit) {
            customPrompts.confirm("Are you sure you want to edit your information?", activateEdit, null);
        } else {
            editEventOpacity(0.65);
            accountInformationProcessor.hasBeenTriggeredForDiEdit = false;
            for (i = 0; i < accountInformationProcessor.diAddress.length; i++) {
                accountInformationProcessor.diAddress[i].contentEditable = false;
            }
            accountInformationProcessor.diCatchphrase.contentEditable = false;
        }
    },
    remediateAddresses: () => {
        let addressesRendered = accountInformationProcessor.deliveryInformationBar.querySelectorAll(".di-address-value");
        if (addressesRendered.length > 0) {
            let i = 0;
            Array.prototype.forEach.call(addressesRendered, function (node) {
                if (node.innerHTML != "Please enter an address.") {
                    accountInformationProcessor.addresses[i] = node.innerHTML;
                    i++;
                }
            })
        }
    },
    addressRenderer: () => {
        let addressesRendered = accountInformationProcessor.deliveryInformationBar.querySelectorAll(".di-address");
        if (addressesRendered.length > 0) {
            Array.prototype.forEach.call(addressesRendered, function (newNode) {
                newNode.parentNode.removeChild(newNode);
            })
        }
        for (i = 0; i < 3; i++) {

            if (i == 0) {
                let diChild = document.createElement("p");
                diChild.setAttribute("class", "di-details di-address");

                let diChildExtra = document.createTextNode("Address 1:");
                diChild.appendChild(diChildExtra);

                diChildExtra = document.createElement("span");
                diChildExtra.setAttribute("class", "di-details-value di-address-value");
                diChildExtra.setAttribute("maxlength", 70);
                if (accountInformationProcessor.addresses[0] != null && accountInformationProcessor.addresses[0] != "") {
                    diChildExtra.appendChild(
                        document.createTextNode(accountInformationProcessor.addresses[0])
                    );
                } else {
                    diChildExtra.appendChild(
                        document.createTextNode("Please enter an address.")
                    );
                }
                diChild.appendChild(diChildExtra);

                if (accountInformationProcessor.addresses[1] == null) {
                    diChildExtra = document.createElement("img");
                    diChildExtra.setAttribute("src", "front-icons/icons8-edit-24.png");
                    diChildExtra.setAttribute("alt", "create or/add an edit icon");
                    diChildExtra.setAttribute("onclick", "accountInformationProcessor.addAddress()");
                    diChild.appendChild(diChildExtra);
                }

                accountInformationProcessor.deliveryInformationBar.insertBefore(
                    diChild,
                    accountInformationProcessor.deliveryInformationBar.children[i]
                );
            } else if (accountInformationProcessor.addresses[i] != null) {
                let diChild = document.createElement("p");
                diChild.setAttribute("class", "di-details di-address");

                let diChildExtra = document.createTextNode(
                    "Address " + eval(parseInt(i) + 1) + ":"
                );
                diChild.appendChild(diChildExtra);

                diChildExtra = document.createElement("span");
                diChildExtra.setAttribute("class", "di-details-value di-address-value");
                diChildExtra.appendChild(
                    document.createTextNode(accountInformationProcessor.addresses[i])
                );
                diChild.appendChild(diChildExtra);

                if (i == 1 && accountInformationProcessor.addresses[2] == null) {
                    diChildExtra = document.createElement("img");
                    diChildExtra.setAttribute("src", "front-icons/icons8-edit-24.png");
                    diChildExtra.setAttribute("alt", "create or/add an edit icon");
                    diChildExtra.setAttribute("onclick", "accountInformationProcessor.addAddress()");
                    diChild.appendChild(diChildExtra);
                }

                accountInformationProcessor.deliveryInformationBar.insertBefore(
                    diChild,
                    accountInformationProcessor.deliveryInformationBar.children[i]
                );
            }
        }
    },
    addAddress: () => {
        function addAddress(address) {
            for (i = 0; i < 3; i++) {
                if (accountInformationProcessor.addresses[i] == null || accountInformationProcessor.addresses[i] == "") {
                    accountInformationProcessor.addresses[i] = address;
                    break;
                }
                else {
                    continue;
                }
            }
            accountInformationProcessor.addressRenderer();
        }
        if (!accountInformationProcessor.hasBeenTriggeredForDiEdit) {
            customPrompts.alert("You have to turn on the \"Edit State\" for this section of your account which is right above the icon you just clicked");
        }
        else if (accountInformationProcessor.addresses.length == 3 && accountInformationProcessor.addresses.every(function (eachValue) { return eachValue != null })) {
            customPrompts.alert("You can't have more than 3 addresses");
        }
        else if (!(accountInformationProcessor.addresses.length <= 3)) {
            customPrompts.alert("An unknown error occured in your address. Try again, if it doesn't work, please contact the administrator");
            customPrompts.address = [null, null, null];
        }
        else {
            customPrompts.prompt("Enter an address", "Address", addAddress);
        }
    },
    deleteSavedOrder: (event) => {
        function responseProcessor(data) {
            if (data) {
                customPrompts.alert("The saved order has successfully been deleted");
                event.target.parentElement.parentElement.removeChild(event.target.parentElement);
            }
            else {
                customPrompts.alert("Something went wrong while trying to delete the saved order");
            }
        }
        let ajx = new AjaxCommunicator("/server_scripts/remove_so/", responseProcessor, `saved-cart-id=${event.target.getAttribute("data-so-id")}`);
        ajx.initiateCommunication();
    },
    hideSideBar() {
        document.getElementById("account-links-aside-nav").style.left = "-50%";
    },
    displaySideBar() {
        document.getElementById("account-links-aside-nav").style.left = "0";
    }
};
var imageHandler = {
    //Image handler canvas to render image to before upload
    ihc: document.getElementById("image-sub-bar-canvas"),
    ajx: null,
    img: null, 
    cropper: {
        cropElement: document.getElementById("crop-handle"),
        activateCropElementMove: function () {
            var mouseXPos = 0, mouseYPos = 0, posXDifference = 0, posYDifference = 0, cropElWidth = 0, cropElHeight = 0, cropElWidth2 = 0, cropElHeight2 = 0, cropElOffsetX = 0, cropElOffsetY = 0, maxDim = 0;
            cropper = imageHandler.cropper;
            cropper.cropElement.onmousedown = mouseDown;
            function updateCropperConstraints(maxD) {
                cropElWidth2 = cropper.cropElement.offsetWidth;
                cropElHeight2 = cropper.cropElement.offsetHeight;

                cropElOffsetX = cropper.cropElement.offsetLeft;

                if (maxD > cropElWidth2) {
                    cropper.cropElement.style.maxWidth = maxD + "px";
                    cropper.cropElement.style.maxHeight = maxD + "px";
                }
                else {
                    //Set the cropper Crop Handle maxWidth, Width and Max Height to the same value if provided maxDimension is not greater than the Handle's original width
                    cropper.cropElement.style.maxWidth = cropElWidth2 + "px";
                    cropper.cropElement.style.width = cropElWidth2 + "px";
                    cropper.cropElement.style.maxHeight = cropElWidth2 + "px";
                }
                maxDim = maxD;

            }
            //This function checks if the crop handler exceeds the bounds of it's parent and rectifies it's position in the event so.
            function reparateBoundsOut() {
                cropElWidth2 = cropper.cropElement.offsetWidth;
                cropElHeight2 = cropper.cropElement.offsetHeight;

                cropElOffsetX = cropper.cropElement.offsetLeft;
                cropElOffsetY = cropper.cropElement.offsetTop;
                //Check if Crop Handler X axis position is lesser than 0(Which is the very Left end of the parent "relatively")
                if (cropElOffsetX < 0) {
                    cropper.cropElement.style.left = 0 + "px";
                }
                else if ((cropElOffsetX + cropElWidth2) > cropper.cropElement.parentElement.offsetWidth) {
                    cropper.cropElement.style.left = cropper.cropElement.parentElement.offsetWidth - cropElWidth2 + "px";
                }
                if (cropElOffsetY < 0) {
                    cropper.cropElement.style.top = 0 + "px";
                }
                else if ((cropElOffsetY + cropElWidth2) > cropper.cropElement.parentElement.offsetHeight) {
                    cropper.cropElement.style.top = cropper.cropElement.parentElement.offsetHeight - cropElHeight2 + "px";
                }
            }
            function mouseDown(event) {
                cropElWidth = cropper.cropElement.offsetWidth;
                cropElHeight = cropper.cropElement.offsetHeight;

                mouseXPos = event.clientX;
                mouseYPos = event.clientY;

                document.onmousemove = moveElementBound;

                document.onmouseup = terminateMouseMove;

            };
            function moveElementBound(event) {
                cropElWidth2 = cropper.cropElement.offsetWidth;
                cropElHeight2 = cropper.cropElement.offsetHeight;

                cropElOffsetX = cropper.cropElement.offsetLeft;
                cropElOffsetY = cropper.cropElement.offsetTop;

                if (((cropElWidth2 != cropElWidth) || (cropElHeight2 != cropElHeight)) && !(cropElWidth2 >= maxDim)) {
                    terminateMouseMove();
                    return;
                }
                {

                    posXDifference = event.clientX - mouseXPos;
                    posYDifference = event.clientY - mouseYPos;

                    mouseXPos = event.clientX;
                    mouseYPos = event.clientY;

                    cropper.cropElement.style.left = (cropElOffsetX + posXDifference) + "px";
                    cropper.cropElement.style.top = (cropElOffsetY + posYDifference) + "px";

                }
                updateCropperConstraints(Math.min(cropper.cropElement.parentElement.offsetWidth - cropElOffsetX, cropper.cropElement.parentElement.offsetHeight - cropElOffsetY));
            };
            function terminateMouseMove() {
                reparateBoundsOut();
                document.onmousemove = null;
                cropper.cropElement.onmouseup = null;
            }
        }
    },
    cropImage: function(ctx) {
        if(ctx) {
            try {
                let CANVASPARENTWIDTH = ctx.canvas.width, CANVASPARENTHEIGHT = ctx.canvas.height;
                var ratioToUse = Math.min(CANVASPARENTWIDTH / img.width, CANVASPARENTHEIGHT / img.height);

                let imageWidthToUse = img.width * ratioToUse;
                let imageHeightToUse = img.height * ratioToUse;
                
                let cropXRatio = img.width / imageWidthToUse;
                let cropYRatio = img.height / imageHeightToUse;

                let imageWidthRelativeRelativeToCropWidth = cropper.cropElement.offsetWidth * cropXRatio;
                let imageHeightRelativeToCropHeight = cropper.cropElement.offsetHeight * cropYRatio;

                ratioToUse = Math.min(CANVASPARENTWIDTH / imageWidthRelativeRelativeToCropWidth, CANVASPARENTHEIGHT / imageHeightRelativeToCropHeight);

                imageWidthToUse = imageWidthRelativeRelativeToCropWidth * ratioToUse;
                imageHeightToUse = imageHeightRelativeToCropHeight * ratioToUse;

                if(!(ratioToUse > 1)) {
                    ctx.canvas.style.width = imageWidthToUse + "px";
                ctx.canvas.parentElement.style.width = imageWidthToUse + "px";
                ctx.canvas.width = imageWidthToUse;

                ctx.canvas.style.height = imageHeightToUse + "px";
                ctx.canvas.parentElement.style.height = imageHeightToUse + "px";
                ctx.canvas.height = imageHeightToUse;

                ctx.drawImage(img, cropper.cropElement.offsetLeft * cropXRatio, cropper.cropElement.offsetTop * cropYRatio, imageWidthRelativeRelativeToCropWidth, imageHeightRelativeToCropHeight, 0 , 0, imageWidthToUse, imageHeightToUse);

                }
                else {
                    ctx.canvas.style.width = imageWidthRelativeRelativeToCropWidth + "px";
                    ctx.canvas.parentElement.style.width = imageWidthRelativeRelativeToCropWidth + "px";
                    ctx.canvas.width = imageWidthRelativeRelativeToCropWidth;
    
                    ctx.canvas.style.height = imageHeightRelativeToCropHeight + "px";
                    ctx.canvas.parentElement.style.height = imageHeightRelativeToCropHeight + "px";
                    ctx.canvas.height = imageHeightRelativeToCropHeight;
                    ctx.drawImage(img, cropper.cropElement.offsetLeft * cropXRatio, cropper.cropElement.offsetTop * cropYRatio, imageWidthRelativeRelativeToCropWidth, imageHeightRelativeToCropHeight, 0 , 0, imageWidthRelativeRelativeToCropWidth, imageHeightRelativeToCropHeight)
                }
                cropper.cropElement.style.display = "none";
            } catch (error) {
                customPrompts.alert("Something went wrong while working on the image");
            }
        }
    },
    cropAndSaveImageToDevice: function() {
        let canvas = imageHandler.ihc;
        imageHandler.cropImage(canvas.getContext("2d"));
        let newImage;
        if(img.type) {
            newImage = canvas.toDataURL(img.type).replace(img.type, "image/octet-stream");
        }
        else {
             newImage = canvas.toDataURL("image/png", 0.7).replace("image/png", "image/octet-stream");
        }
       let link = document.createElement("a");
       link.setAttribute("download", "ferixxon-" + img.name);
       link.setAttribute("href", newImage);
       link.click();
    }, 
    dataUrlToFile: function(dataUrl, filename, dataurl_mime) {
        let dataUrlSplitted = dataUrl.split(","),
        mime,
        decodedASCIIDataUrl = atob(dataUrlSplitted[1]),
        decodedASCIIDataUrlLength = decodedASCIIDataUrl.length;
        newDecodedDataUrl = new Uint8Array(decodedASCIIDataUrlLength);
        if(dataurl_mime) {
            mime = dataurl_mime;
        }
        else {
            mime = dataUrlSplitted[0].match(/(?<=:).*?(?=;)/)[0];
        }
        while(decodedASCIIDataUrlLength--) {
            newDecodedDataUrl[decodedASCIIDataUrlLength] = decodedASCIIDataUrl.charCodeAt(decodedASCIIDataUrlLength);
        }
        return new File([newDecodedDataUrl], filename, {type: dataurl_mime});
    },
    cropAndUploadImage: function() {
        let canvas = imageHandler.ihc;
        imageHandler.cropImage(canvas.getContext("2d"));
        let newImage;
        if(img.type) {
            newImage = canvas.toDataURL(img.type);
        }
        else {
             newImage = canvas.toDataURL("image/png", 0.7);
        }
        imageHandler.ajx = accountInformationProcessor.createXhr();
        imageHandler.ajx.onload = function() {
            if(this.readyState == 4 && this.status == 200) {
                console.log(this.responseText);
            }
        }
        imageHandler.ajx.open("post", "/server_scripts/primup.php");
        let formData = new FormData();
        formData.append("C_IMAGE", imageHandler.dataUrlToFile(newImage, img.name, img.type));
        imageHandler.dataUrlToFile(newImage);
        imageHandler.ajx.send(formData);
    },
    //function to fetch and render image to ihc
    processImageToIhc: function (event) {
        function renderImage(ctx, imgFileUrl) {
            img = new Image();
            img.onload = function () {
                var ratioToUse = Math.min(CANVASPARENTWIDTH / img.width, CANVASPARENTHEIGHT / img.height);

                let imageWidthToUse = img.width * ratioToUse;
                let imageHeightToUse = img.height * ratioToUse;

                
                if(!(ratioToUse > 1)) {
                ctx.canvas.style.width = imageWidthToUse + "px";
                ctx.canvas.parentElement.style.width = imageWidthToUse + "px";
                ctx.canvas.width = imageWidthToUse;

                ctx.canvas.style.height = imageHeightToUse + "px";
                ctx.canvas.parentElement.style.height = imageHeightToUse + "px";
                ctx.canvas.height = imageHeightToUse;

                ctx.drawImage(img, 0, 0, imageWidthToUse, imageHeightToUse);
                }
                else {
                ctx.canvas.style.width = img.width + "px";
                ctx.canvas.parentElement.style.width = img.width + "px";
                ctx.canvas.width = img.width;

                ctx.canvas.style.height = img.height + "px";
                ctx.canvas.parentElement.style.height = img.height + "px";
                ctx.canvas.height = img.height;

                ctx.drawImage(img, 0, 0, img.width, img.height);
                }

                imageHandler.cropper.cropElement.style.display = "block";
                imageHandler.cropper.activateCropElementMove();
            };
            img.src = imgFileUrl;

            img.type = imageFile["type"];
            img.name = imageFile["name"];
        }
        event = event || window.event;
        let imageFile = event.target.files[0];
        let supportedImageTypes = ["image/jpeg", "image/png"];
        if (!supportedImageTypes.includes(imageFile["type"])) {
            customPrompts.alert("File type is unsupported, Please use a static image.");
        }
        else {
            let file = new FileReader();
            file.onload = function (event) {
                renderImage(imageHandler.ihc.getContext("2d"), event.target.result);
                imageHandler.showImageHandlerBar();
            };
            file.readAsDataURL(imageFile);
        }
    },
    hideImageHandlerBar: () => {
        document.getElementById("user-image-handler-bar").style.display = 'none';
    },
    showImageHandlerBar: () => {
        document.getElementById("user-image-handler-bar").style.display = 'block';
    }
};
//Executors and Setters
{
    accountInformationProcessor.addressRenderer();
    document.getElementById("save-to-device-button").onclick = function() {
        imageHandler.cropAndSaveImageToDevice();
    }
    document.getElementById("image-submit-button").onclick = function() {
        imageHandler.cropAndUploadImage();
    }
}
var pageActivator = {
    accountInformationPage: document.getElementById("account-information-content"),
    rechargeAccountPage: document.getElementById("recharge-account"),
    ordersHistoryPage: document.getElementById("orders-history"),
    savedOrdersPage: document.getElementById("saved-orders"),
    deactivateAccountInformationPage: () => {
        pageActivator.accountInformationPage.style.display = "none";
    },
    deactivateRechargeAccountPage: () => {
        pageActivator.rechargeAccountPage.style.display = "none";
    },
    deactivateOrdersHistoryPage: () => {
        pageActivator.ordersHistoryPage.style.display = "none";
    },
    deactivateSavedOrdersPage: () => {
        pageActivator.savedOrdersPage.style.display = "none";
    },
    deactivateAllPages: () => {
        pageActivator.deactivateAccountInformationPage();
        pageActivator.deactivateRechargeAccountPage();
        pageActivator.deactivateOrdersHistoryPage();
        pageActivator.deactivateSavedOrdersPage();
    },
    activateAccountInformationPage: () => {
        pageActivator.deactivateAllPages();
        pageActivator.accountInformationPage.style.display = "block";
    },
    activateRechargeAccountPage: () => {
        pageActivator.deactivateAllPages();
        pageActivator.rechargeAccountPage.style.display = "block";
    },
    activateOrdersHistoryPage: () => {
        pageActivator.deactivateAllPages();
        pageActivator.ordersHistoryPage.style.display = "block";
    },
    activateSavedOrdersPage: () => {
        pageActivator.deactivateAllPages();
        pageActivator.savedOrdersPage.style.display = "block";
    },
    initRechargePayment: () => {
        function referenceProcessor(response) {
            response = JSON.parse(response);
            if (response["status"] == "ERR") {
                customPrompts.alert(response["msg"]);
                return false;
            }
            else if(response["status"] = "SUCC") {
                pageActivator.activatePaystack(response["amt"], response["ref"]);
                return true;
            }
            customPrompts.alert("An unprecdented error might have occurred, if error persist - contact administrator");
            return false;
        }
        let ajx = new AjaxCommunicator("/server_scripts/init_recharge?amount="+document.getElementById('recharge-amount').value, referenceProcessor, null, "get");
        ajx.initiateCommunication();
    },
    activatePaystack: (amount, txref) => {
        function setUpPaystack() {
            var handler = PaystackPop.setup({
                key: 'pk_test_29e1fa3c67cacd4a1bc861e86f046f1df4bcae62',
                email: document.getElementById('bsi-email-value').textContent || document.getElementById('bsi-email-value').innerText,
                amount: amount, // the amount value is multiplied by 100 to convert to the lowest currency unit
                currency: 'NGN', // Use GHS for Ghana Cedis or USD for US Dollars
                ref: txref, // Replace with a reference you generated
                callback: function(response) {
                //this happens after the payment is completed successfully
                var reference = response.reference;
                alert('Payment complete! Reference: ' + reference);
                // Make an AJAX call to your server with the reference to verify the transaction
                },
                onClose: function() {
                alert('Transaction was not completed, window closed.');
                },
            });
            handler.openIframe();
        }
        setUpPaystack();
    }
}