# plugin-mobile-bar

Uwaga:

Aby plugin 'fixed-navbar' zadzialal, do footer.php trzeba dodaæ jeszcze nastepujacy kod :

```

<?php if (function_exists('mobile_bar_print_slides')) { 
mobile_bar_print_slides();
} ?>

```

![alt text](https://piotrdeja.pl/mobile-bar.jpg)
