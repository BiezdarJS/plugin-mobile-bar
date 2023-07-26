# plugin-mobile-bar

Plugin wordpress dodający ikony kontaktu w wersji mobilnej strony. Wykorzystane narzędzia, PHP, Wordpress API, Ajax, jQuery

Uwaga:

Aby plugin 'fixed-navbar' zadzialal, do footer.php trzeba dodaæ jeszcze nastepujacy kod (np: w footer.php na końcu pliku) :

```

<?php if (function_exists('mobile_bar_print_slides')) { 
mobile_bar_print_slides();
} ?>

```

![alt text](https://piotrdeja.pl/mobile-bar.jpg)
