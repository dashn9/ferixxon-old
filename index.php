<?php
session_name("user");
session_start();
$id = null;
$username = null;
$acc_bal = 0;
if (isset($_SESSION["UNQ_ID"])) {
    $id = $_SESSION["UNQ_ID"];
    if (isset($_SESSION["USERNAME"])) {
        $username = $_SESSION["USERNAME"];
    }
    if (isset($_SESSION["AKUENTE_BAELENCE"])) {
        $acc_bal = $_SESSION["AKUENTE_BAELENCE"];
    }
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Ferixxon is a company that renders food and groceries delivery service by using world class system logistics model that maximizes your convenience, affordability and time. we are always here for you" />
    <meta name="keywords" content="ferixxon, ferixxon.com, logistics, delivery, food delivery, campus, campus food delivery, campus food" />

    <title>Home | FERIXXON</title>
    <link rel="stylesheet" type="text/css" hreflang="EN" href="./stylers/header_footer.css" />
    <link rel="stylesheet" type="text/css" hreflang="EN" href="./stylers/cindex.css" />

</head>

<body>
    <div id="direct-sub-body">
        <div id="message-overlay">
            <div id="message-overlay-sub">
                <img class="exit-image" src="./front-icons/icons8-delete-24.png" onclick="orderProcessor.hideMessenger()" />
                <div id="message-body"></div>
            </div>
        </div>
        <div id="interaction-blocker"></div>
        <div class="message-overlay">
            <img class="exit-image" src="front-icons/icons8-delete-24.png" onclick="customPrompts.hideMessenger()" />
            <div id="message-body">Are you sure?</div>
            <div id="input"><input type="text" required /><button id="submit-message-input">Submit</button></div>
            <div id="message-response"><button id="button-one">Yes</button><button id="button-two">No</button></div>
        </div>
        <div id="loading-wrapper" style="display: none">
            <div class="loading-circle loading-circle-1">
                <div>
                    <div></div>
                </div>
            </div>
        </div>
        <header> <img class="ferixxon-logo" src="./images/ferixxon-logo-1.png" />
            <div class="header-input-division">
                <input placeholder="What would you like to eat today, Dear?" type="text" />
                <input type="button" value="GO!" />
            </div>
            <div class="nav-links-division">
                <nav>
                    <?php

                    try {
                        $login_nav = '<ul>
                    <li><a href="./register"><img src="./front-icons/icons8-sign-up-24.png" alt="Sign Up Icon" class="nav-links-icons" />SIGN UP</a>
                    </li>
                    <li><a href="./signin"><img src="./front-icons/icons8-log-in-24.png" alt="Log In Icon" class="nav-links-icons">LOG IN</a>
                    </li>
                </ul>';
                        $account_html_username = "
                <div id=\"account\">
                    <span>
                    <img src=\"/front-icons/icons8-user-30.png\" width=\"15px\"  alt=\"User Icon\" /><a href=\"\user_dashboard\">"
                            . $username  .
                            "</a></span>
                    <div class=\"account-sub\">
                        <div class=\"account-first-sub\">
                            <div>
                                <span>IS</span>
                                <br />
                                <span>customer_id: " . $id . "</span>
                            </div>
                            <div>
                            <span class=\"naira-currency\">&#x20A6</span>" . number_format($acc_bal) . "
                            </div>
                        </div>
                        <div class=\"account-second-sub\">
                            <p>
                            <a href=\"\user_dashboard\">
                                Account Dashboard
                            </a>
                            </p>
                            <p>
                                Recharge Account
                            </p>
                        </div>
                        <a href=\"https://ferixxon.com/logout.php/\">
                            <p>
                                Logout
                            </p>
                        </a>
                    </div>
                </div>";
                        $account_html = "<div id=\"account\">
                <span><img src=\"/front-icons/icons8-user-30.png\" width=\"15px\" alt=\"User Icon\" />User</span>
                </div>";

                        if (isset($id, $username)) {
                            if ($username != "Edit Username") {
                                echo $account_html_username;
                            } else {
                                echo $account_html;
                            }
                        } else {
                            echo $login_nav;
                        }
                    } catch (Error $error) {
                        echo $login_nav;
                    }
                    ?>
                </nav>
            </div>
        </header>
        <div id="sub-body">
            <div class="cart-toggler" onclick="orderProcessor.toggleCartDisplay()"><img src="./front-icons/icons8-fast-cart-24.png" alt="ferixxon - fast cart" /></div>
            <div class="images-slide-bar">
                <div class="images-hold-slide"> <img src="./images/promotional-images/ferixxon-poster-one.png" alt="ferixxon 20% promotional image" /> <img src="./images/promotional-images/ferixxon-poster-two.png" alt="Ferixxon poster for orders quick delivery" /> <img src="./images/promotional-images/ferixxon-poster-three.png" alt="Ferixxon Poster of Foods" /> <img src="./images/promotional-images/ferixxon-poster-four.png" alt="Vanilla ice cream" /> </div>
                <div id="images-slide-bar-overlay"> </div>
                <div id="image-slide-states-bar">
                    <div id="slide-text">
                        <div></div>
                    </div>
                    <div class="image-slide-state"></div>
                    <div class="image-slide-state"></div>
                    <div class="image-slide-state"></div>
                    <div class="image-slide-state"></div>
                </div>
            </div>
            <hr id="hr-after-image-slide" />
            <aside id="orders-bar">
                <h3>Orders</h3>
                <div id="orders-buttons"><img title="Create a new order batch" src="./front-icons/icons8-add-basket-24.png" onclick="orderProcessor.createBatch()" /><img title="Save existing cart permanently" src="./front-icons/icons8-cart-user-24.png" alt="save icon" onclick="orderProcessor.saveCartToAccount()" /><img title="Retrieve saved carts" src="./front-icons/icons8-buying-24.png" alt="save icon" onclick="orderProcessor.retrieveCartsFromAccount()" /><img title="Delete an existing order batch" src="./front-icons/icons8-shopping-basket-remove-24.png" src="./remove basket" onclick="orderProcessor.deleteBatch()" /><img src="./front-icons/icons8-delete-view-24.png" title="Delete Everything in the cart" onclick="orderProcessor.resetCart()" />
                </div>
                <div id="orders">
                    <div class="batch">
                        <h4 contenteditable="true">Batch 1</h4>
                        <div>

                            <!--<div class="order-elements"><span>Jollof Rice ( <span>N100</span> )</span> <input value="1" type="number" min="1" max="30" /></div>-->

                        </div>

                    </div>
                </div>
                <div id="price-and-checkout"><span id="total-label">Total: </span><span id="total-value">&#x20a6 0</span><button class="checkout-button" onClick="orderProcessor.submitCart();">Checkout</button>
                </div>
            </aside>
            <div id="order-by-kitchen">

                <div id="order-by-kitchen-header"><span>Order</span> <em>by kitchen</em>
                </div>
                <div class="selection-and-adjustment-bar">
                    <ul class="kitchen-body-select">
                        <li>-- Select Kitchen --</li><span class="arrow"></span>
                        <ul class="kitchen-body-select-list">
                            <li data-kitchen="kitchen-0">Chicken Republic</li>
                            <li data-kitchen="kitchen-1">Kum Chop</li>
                            <li data-kitchen="kitchen-2">Simple</li>
                        </ul>
                    </ul>
                    <div class="loading-circle">
                        <div>
                            <div></div>
                        </div>
                    </div>
                </div>

                <div id="order-by-kitchen-body">
                    <div class="kitchens-head">
                        <h2>-- Select Kitchen --</h2>
                    </div>
                    <div class="kitchens-body">

                        <p>Select a Kitchen</p>
                        <!--
                <div class="product-bar">
                    
                    <h3>Product Title</h3>
                    <p>Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum Lorem Ipsum </p>
                    <p>Product Price</p><div class="buttons-bar"><button class="add-to-order-button">To Order</button></div>
                </div>
                    -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <footer class="footer-b-bg">
        <div class="sub-divs footer-group-1">
            <ul>
                <li><img src="./front-icons/icons8-home-50.png" width="16px" height="16px" alt="Home Icon - Ferixxon" /><a href="https://www.ferixxon.com/">Home</a></li>
                <li><img src="./front-icons/icons8-terms-and-conditions-24.png" width="16px" height="16px" alt="Terms of Use Icon - Ferixxon" /><a>Terms of Use</a></li>
                <li><img src="./front-icons/icons8-about-24.png" width="16px" height="16px" alt="About Us Icon - Ferixxon" /><a>About Us</a></li>
            </ul>
        </div>
        <div class="sub-divs footer-group-2">
            <ul>
                <li><img src="./front-icons/icons8-contact-us-24.png" width="16px" height="16px" alt="Contact Us Icon - Ferixxon" /><a href="https://www.ferixxon.com/contact" >Contact Us</a></li>
                <li><img src="./front-icons/icons8-small-business-30.png" width="16px" height="16px" alt="Business Icon - Ferixxon" /><a href="https://www.ferixxon.com/enlist-your-business" >Enlist your business</a></li>
                <li><img src="./front-icons/icons8-whistle-60.png" width="16px" height="16px" alt="Whistle Icon - Ferixxon" /><a href="https://www.ferixxon.com/whistle-blower" >Whistleblower</a></li>
                <li><img src="./front-icons/icons8-idea-30.png" width="16px" height="16px" alt="Idea(Suggestion) - Ferixxon" /><a>Raise a Suggestion</a></li>
            </ul>
        </div>
        <div class=" sub-divs footer-group-3">
            <ul>
                <li><img src="./front-icons/icons8-subscription-24.png" width="16px" height="16px" alt="Subscription Icon - Ferixxon" /><a>Newsletter Subscription</a></li>
                <li><img src="./front-icons/icons8-discount-30.png" width="16px" height="16px" alt="Promotion Icon - Ferixxon" /><a>Offers and Promotions</a></li>
                <li id="follow-us"><img src="./front-icons/icons8-love-circled-24.png" width="16px" height="16px" alt="Follow(Love) Icon - Ferixxon" />Follow us:
                    <div class="follow-elements-bar">
                        <a><img src="./front-icons/icons8-instagram-50.png" width="16px" height="16px" alt="Home Icon - Ferixxon" /></a>
                        <a><img src="./front-icons/icons8-facebook-50.png" width="16px" height="16px" alt="Home Icon - Ferixxon" /></a>
                        <a><img src="./front-icons/icons8-twitter-50.png" width="16px" height="16px" alt="Home Icon - Ferixxon" /></a>
                    </div>
                </li>
                <li><img src="./front-icons/icons8-icons8-24.png" width="16px" height="16px" alt="Home Icon - Ferixxon" />Icons (<a href="./https://icons8.com" id="icons8-link">Icons 8</a>)</li>
            </ul>
        </div>

        <p id="footer-foot">All rights reserved &copy; ferixxon | 2022</p>
    </footer>
</body>
<script src="./scripts/cindex.js">
</script>

</html>