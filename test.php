<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cartToggle = document.querySelector('.cart-button');
            const cartModal = document.querySelector('.cart-modal');
            const cartCloseBtn = document.querySelector('.cart-close');

            cartToggle.addEventListener('click', function() {
                cartModal.style.transform = 'translate(0, -50%)';
            });

            cartCloseBtn.addEventListener('click', function() {
                cartModal.style.transform = 'translate(100%, -50%)';
            });
        });
    </script>
</head>
<body>
    <div class="cart-modal" style="position:fixed;right: 0; width: 30vw;height: 30vh; background-color: lightgrey; border-radius: 10px 0 0 10px; padding: 20px 20px 20px 40px;transform: translate(100%, -50%); top: 50%;z-index:10;">
        <div class="cart-button" style="width: 70px;height:70px;background-color:lightgrey;border-radius:100%;transform:translate(-45%, 0);position:absolute; top:0;left: 0;z-index:-2;"></div>
        <h2>Cart Modal</h2>
        <p>This is a sample cart modal.</p>
        <button class="cart-close" aria-label="Close Cart">Close Cart</button>



    </div>
</body>
</html>